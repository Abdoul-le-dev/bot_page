/* ══════════════════════════════════════════════════════════════════════
   chat_app.js — Orchestration, state global, logique métier
   ══════════════════════════════════════════════════════════════════════ */

import * as API    from './chat_api.js'
import * as Render from './chat_render.js'

// ── Types de fichiers acceptés (alignés avec ALLOWED_MEDIA backend) ──
const ACCEPTED_FILES = [
  'image/jpeg', 'image/png', 'image/gif', 'image/webp',
  'video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-matroska', 'video/webm',
  'application/pdf',
  'application/msword',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
  'application/vnd.ms-excel',
  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
  'application/vnd.ms-powerpoint',
  'application/vnd.openxmlformats-officedocument.presentationml.presentation',
  'text/plain',
  'application/zip',
  'application/x-rar-compressed',
]

// ── Mapping mime_type → type simplifié (identique au backend) ──────────
const MIME_TO_TYPE = {
  'image/jpeg': 'image', 'image/png': 'image', 'image/gif': 'image', 'image/webp': 'image',
  'video/mp4': 'video', 'video/quicktime': 'video', 'video/x-msvideo': 'video',
  'video/x-matroska': 'video', 'video/webm': 'video',
  'application/pdf': 'pdf',
  'application/msword': 'word',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'word',
  'application/vnd.ms-excel': 'excel',
  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'excel',
  'application/vnd.ms-powerpoint': 'powerpoint',
  'application/vnd.openxmlformats-officedocument.presentationml.presentation': 'powerpoint',
  'text/plain': 'text',
  'application/zip': 'archive',
  'application/x-rar-compressed': 'archive',
}

// ── State global ──────────────────────────────────────────────────────
const State = {
  conversations:    [],
  currentUserId:    null,
  currentConv:      null,
  currentProfile:   null,
  messages:         [],
  oldestMessageId:  null,
  newestMessageId:  null,
  tab:              'all',
  search:           '',
  replyToId:        null,
  replyToText:      null,
  uploadFile:       null,       // File object sélectionné
  uploadResult:     null,       // Réponse du serveur après upload
  uploadMimeType:   null,       // mime_type du fichier en cours
  sending:          false,
  pollingTimer:     null,
  loadingMessages:  false,
}

// ── Refs DOM ──────────────────────────────────────────────────────────
const $ = (id) => document.getElementById(id)

// ── Init ──────────────────────────────────────────────────────────────

async function init() {
  await loadConversations()

  const fi = $('file-input')
  if (fi) {
    fi.setAttribute('accept', ACCEPTED_FILES.join(','))
    fi.addEventListener('change', (e) => {
      if (e.target.files.length) previewUpload(e.target.files[0])
    })
  }
}

// ── Conversations ─────────────────────────────────────────────────────

async function loadConversations() {
  try {
    const data = await API.apiGetConversations(State.tab, State.search)
    State.conversations = data.conversations || []
    renderConvList()

    const stats = await API.apiGetConversationStats()
    const badge = document.querySelector('.nav-item.active .badge-sky')
    const unreadCount = document.getElementById('tab-unread-count')
    if (unreadCount) unreadCount.textContent = stats.total_unread || ''
    const adminCount = document.getElementById('tab-admin-count')
    if (adminCount) adminCount.textContent = stats.requires_admin_count || ''
    if (badge && stats.total_unread) badge.textContent = stats.total_unread
  } catch (e) {
    Render.toast('Erreur chargement conversations', 'error')
  }
}

function renderConvList() {
  const list = $('conv-list')
  if (!list) return
  list.innerHTML = State.conversations.map(Render.renderConvItem).join('')
}

async function selectConv(userId, el) {
  document.querySelectorAll('.conv-item').forEach(c => c.classList.remove('active'))
  el?.classList.add('active')

  State.currentUserId   = userId
  State.messages        = []
  State.oldestMessageId = null
  State.newestMessageId = null

  API.apiMarkRead(userId).catch(() => {})

  try {
    const [conv, profile] = await Promise.all([
      API.apiGetConversation(userId),
      API.apiGetProfile(userId),
    ])
    State.currentConv    = conv
    State.currentProfile = profile
    updateChatHeader(conv, profile)
    updateProfilePanel(profile)
  } catch (e) {
    Render.toast('Erreur chargement conversation', 'error')
    return
  }

  await loadMessages()

  if (window.innerWidth <= 700) {
    $('conv-col')?.classList.add('hidden-mobile')
    $('messages-col')?.classList.add('visible')
  }

  startPolling()
}

function updateChatHeader(conv, profile) {
  if (!conv || !profile) return
  const avClass = Render.avatarClass(profile.name)
  const avTxt   = Render.avatarText(profile.name, profile.name)

  const chatAv = $('chat-av')
  if (chatAv) {
    chatAv.className  = `av ${avClass}`
    chatAv.style.cssText = 'width:32px;height:32px;font-size:12px;'
    chatAv.textContent = avTxt
  }
  const chatName = $('chat-name')
  if (chatName) chatName.textContent = profile.name || ''

  const chatHandle = $('chat-handle')
  if (chatHandle) chatHandle.textContent = `${profile.name || ''} · ID ${conv.user_id}`

  $('blocked-banner').style.display  = conv.is_blocked ? 'flex' : 'none'
  $('ia-banner').style.display       = (!conv.is_blocked && conv.ia_enabled) ? 'flex' : 'none'
  $('compose-area').style.display    = conv.is_blocked ? 'none' : 'block'

  const toggle = $('ia-toggle')
  if (toggle) toggle.className = 'toggle' + (conv.ia_enabled ? ' on' : '')

  const input = $('compose-input')
  if (input) input.placeholder = `Envoyer un message à ${profile.name || 'ce membre'}…`
}

function updateProfilePanel(profile) {
  const col = $('profile-col')
  if (!col || !profile) return
  col.innerHTML = Render.renderProfile(profile)
}

// ── Messages ──────────────────────────────────────────────────────────

async function loadMessages() {
  if (State.loadingMessages || !State.currentUserId) return
  State.loadingMessages = true

  try {
    const data = await API.apiGetMessages(State.currentUserId, 50)
    State.messages = data.messages || []

    if (State.messages.length) {
      State.oldestMessageId = State.messages[0].id
      State.newestMessageId = State.messages[State.messages.length - 1].id
    }

    renderMessagesFeed(true)
  } catch (e) {
    Render.toast('Erreur chargement messages', 'error')
  } finally {
    State.loadingMessages = false
  }
}

async function loadOlderMessages() {
  if (State.loadingMessages || !State.currentUserId || !State.oldestMessageId) return
  State.loadingMessages = true

  const feed       = $('messages-feed')
  const prevHeight = feed?.scrollHeight || 0

  try {
    const data  = await API.apiGetMessages(State.currentUserId, 30, State.oldestMessageId)
    const older = data.messages || []
    if (!older.length) return

    State.messages        = [...older, ...State.messages]
    State.oldestMessageId = older[0].id
    renderMessagesFeed(false)

    if (feed) feed.scrollTop = feed.scrollHeight - prevHeight
  } catch (e) {
    Render.toast('Erreur chargement anciens messages', 'error')
  } finally {
    State.loadingMessages = false
  }
}

function renderMessagesFeed(scrollToBottom = true) {
  const feed = $('messages-feed')
  if (!feed) return

  const groups = {}
  for (const msg of State.messages) {
    const date = msg.created_at?.slice(0, 10) || 'unknown'
    if (!groups[date]) groups[date] = []
    groups[date].push(msg)
  }

  let html = ''
  for (const [date, msgs] of Object.entries(groups)) {
    html += Render.renderDateSep(date)
    html += msgs.map(m => Render.renderMessage(m, State.currentConv)).join('')
  }
  html += '<div style="height:8px;"></div>'

  feed.innerHTML = html
  if (scrollToBottom) feed.scrollTop = feed.scrollHeight

  feed.onscroll = () => {
    if (feed.scrollTop < 60) loadOlderMessages()
  }
}

// ── Polling nouveaux messages (toutes les 5s) ─────────────────────────

function startPolling() {
  stopPolling()
  State.pollingTimer = setInterval(pollNewMessages, 5000)
}

function stopPolling() {
  if (State.pollingTimer) {
    clearInterval(State.pollingTimer)
    State.pollingTimer = null
  }
}

async function pollNewMessages() {
  if (!State.currentUserId || !State.newestMessageId) return
  try {
    const data    = await API.apiGetMessages(State.currentUserId, 20, null, State.newestMessageId)
    const newMsgs = data.messages || []
    if (!newMsgs.length) return

    State.messages        = [...State.messages, ...newMsgs]
    State.newestMessageId = newMsgs[newMsgs.length - 1].id

    const feed = $('messages-feed')
    if (!feed) return
    const atBottom = feed.scrollHeight - feed.scrollTop - feed.clientHeight < 80

    const spacer = feed.lastElementChild
    for (const msg of newMsgs) {
      const tmp = document.createElement('div')
      tmp.innerHTML = Render.renderMessage(msg, State.currentConv)
      feed.insertBefore(tmp.firstElementChild, spacer)
    }
    if (atBottom) feed.scrollTop = feed.scrollHeight

    loadConversations()
  } catch (e) {
    // Silencieux
  }
}

// ════════════════════════════════════════════════════════════════════════
// ENVOI MESSAGE
// Logique :
//   1. Un seul fichier à la fois — si un upload est en cours on bloque.
//   2. Le message_type est déduit du mime_type du fichier, pas du tout "text".
//   3. Si l'API retourne une erreur, on affiche un bandeau rouge sous le
//      message optimiste, et on le retire de State.messages.
// ════════════════════════════════════════════════════════════════════════

async function sendMessage() {
  if (State.sending || !State.currentUserId) return

  // Bloquer l'envoi si l'upload est en cours mais pas encore terminé
  if (State.uploadFile && !State.uploadResult) {
    Render.toast('Fichier en cours d\'envoi, veuillez patienter…', 'info')
    return
  }

  const input = $('compose-input')
  const text  = input?.value.trim() || ''

  if (!text && !State.uploadResult) return

  State.sending = true
  const sendBtn = $('send-btn')
  if (sendBtn) sendBtn.disabled = true

  // ── Construire le payload ─────────────────────────────────────────
  // Le message_type est toujours déduit du fichier si présent.
  let payload = {
    message_type: 'text',
    message_text: text,
  }

  if (State.uploadResult) {
    // Utiliser le type retourné par le backend lors de l'upload
    // (qui correspond au mime_type réel du fichier)
    payload.message_type = State.uploadResult.type   // image | video | pdf | word | excel | ...
    payload.media_url    = State.uploadResult.url
    if (text) payload.message_text = text
  }

  if (State.replyToId) {
    payload.replied_to_id = State.replyToId
  }

  // ── Message optimiste (affiché immédiatement) ─────────────────────
  const optimisticId = `opt_${Date.now()}`
  const optimisticMsg = {
    id:           optimisticId,
    user_id:      State.currentUserId,
    message_text: text,
    message_type: payload.message_type,
    media_url:    State.uploadResult?.url || null,
    direction:    'outbound',
    answered_by:  'admin',
    status:       'sending',   // statut temporaire
    created_at:   new Date().toISOString(),
    replied_to_id:   State.replyToId   || null,
    replied_to_text: State.replyToText || null,
  }

  State.messages.push(optimisticMsg)
  State.newestMessageId = optimisticId   // temporaire

  const feed = $('messages-feed')
  if (feed) {
    const spacer = feed.lastElementChild
    const tmp = document.createElement('div')
    tmp.innerHTML = Render.renderMessage(optimisticMsg, State.currentConv)
    const node = tmp.firstElementChild
    node.setAttribute('data-optimistic-id', optimisticId)
    feed.insertBefore(node, spacer)
    feed.scrollTop = feed.scrollHeight
  }

  // Reset textarea immédiatement (UX réactive)
  const savedText   = text
  const savedUpload = { ...State.uploadResult }
  if (input) { input.value = ''; input.style.height = 'auto' }
  const counter = $('compose-count')
  if (counter) counter.textContent = '0 / 4096'
  clearReply()
  clearUpload()

  // ── Envoi réel ────────────────────────────────────────────────────
  try {
    const msg = await API.apiSendMessage(State.currentUserId, payload)

    // Remplacer le message optimiste par la vraie réponse du serveur
    const idx = State.messages.findIndex(m => m.id === optimisticId)
    if (idx !== -1) State.messages[idx] = msg
    State.newestMessageId = msg.id

    // Remplacer le nœud DOM optimiste
    const optNode = feed?.querySelector(`[data-optimistic-id="${optimisticId}"]`)
    if (optNode && feed) {
      const tmp = document.createElement('div')
      tmp.innerHTML = Render.renderMessage(msg, State.currentConv)
      feed.replaceChild(tmp.firstElementChild, optNode)
    }

  } catch (e) {
    // ── Échec : marquer le message optimiste comme "error" ──────────
    const errLabel = e?.message || 'Erreur envoi'

    // Mettre à jour le statut dans le state
    const idx = State.messages.findIndex(m => m.id === optimisticId)
    if (idx !== -1) State.messages[idx].status = 'error'

    // Mettre à jour le DOM
    const optNode = feed?.querySelector(`[data-optimistic-id="${optimisticId}"]`)
    if (optNode) {
      // Ajouter une ligne d'erreur sous la bulle
      const errDiv = document.createElement('div')
      errDiv.style.cssText = 'font-size:11px;color:#f87171;text-align:right;margin-top:3px;padding-right:4px;'
      errDiv.innerHTML = `⚠ Échec — ${Render.escapeHtml(errLabel)}`
      optNode.appendChild(errDiv)
    }

    Render.toast('Message non envoyé — vérifiez la connexion Telegram', 'error')
  } finally {
    State.sending = false
    if (sendBtn) sendBtn.disabled = false
  }
}

// ── Upload ────────────────────────────────────────────────────────────

function triggerUpload() {
  // Empêcher un second upload si un fichier est déjà sélectionné
  if (State.uploadFile) {
    Render.toast('Un fichier est déjà en attente. Supprimez-le d\'abord.', 'info')
    return
  }
  $('file-input')?.click()
}

function previewUpload(file) {
  if (!ACCEPTED_FILES.includes(file.type)) {
    Render.toast(`Type de fichier non supporté (${file.type})`, 'error')
    return
  }

  // Un seul fichier à la fois
  if (State.uploadFile) {
    Render.toast('Un fichier est déjà en attente. Supprimez-le d\'abord.', 'info')
    return
  }

  State.uploadFile   = file
  State.uploadResult = null
  State.uploadMimeType = file.type

  const preview = $('upload-preview')
  if (preview) {
    preview.innerHTML = Render.renderUploadPreview(file)
    preview.style.display = 'block'
  }

  uploadFileNow(file)
}

async function uploadFileNow(file) {
  // Indicateur de chargement dans la preview
  const preview = $('upload-preview')
  if (preview) {
    const loader = preview.querySelector('.upload-loader')
    if (!loader) {
      const l = document.createElement('div')
      l.className = 'upload-loader'
      l.style.cssText = 'font-size:10px;color:#a1a1aa;margin-top:4px;'
      l.textContent = 'Envoi en cours…'
      preview.appendChild(l)
    }
  }

  try {
    const result = await API.apiUploadMedia(State.currentUserId, file)
    State.uploadResult = result

    // Retirer l'indicateur de chargement
    if (preview) {
      const loader = preview.querySelector('.upload-loader')
      if (loader) loader.remove()
      // Ajouter une confirmation visuelle
      const ok = document.createElement('div')
      ok.style.cssText = 'font-size:10px;color:#34d399;margin-top:4px;'
      ok.textContent = '✓ Prêt à envoyer'
      preview.appendChild(ok)
    }
  } catch (e) {
    Render.toast('Erreur upload fichier', 'error')
    clearUpload()
  }
}

function clearUpload() {
  State.uploadFile     = null
  State.uploadResult   = null
  State.uploadMimeType = null
  const preview = $('upload-preview')
  if (preview) { preview.innerHTML = ''; preview.style.display = 'none' }
  const fi = $('file-input')
  if (fi) fi.value = ''
}

// ── Reply ─────────────────────────────────────────────────────────────

function setReply(messageId, text) {
  State.replyToId   = messageId
  State.replyToText = text
  const preview = $('reply-preview')
  if (preview) {
    $('reply-text').textContent = text
    preview.style.display = 'flex'
  }
  $('compose-input')?.focus()
}

function clearReply() {
  State.replyToId   = null
  State.replyToText = null
  const preview = $('reply-preview')
  if (preview) preview.style.display = 'none'
}

// ── Toggle IA ─────────────────────────────────────────────────────────

async function toggleIA(btn) {
  if (!State.currentUserId) return
  btn.classList.toggle('on')
  const isOn = btn.classList.contains('on')
  $('ia-banner').style.display = isOn ? 'flex' : 'none'
  try {
    await API.apiSetIA(State.currentUserId, isOn)
    if (State.currentConv) State.currentConv.ia_enabled = isOn ? 1 : 0
  } catch (e) {
    btn.classList.toggle('on')
    $('ia-banner').style.display = isOn ? 'none' : 'flex'
    Render.toast('Erreur mise à jour IA', 'error')
  }
}

// ── Tabs conversations ────────────────────────────────────────────────

async function switchConvTab(el, tab) {
  el.closest('div').querySelectorAll('.tab').forEach(t => t.classList.remove('active'))
  el.classList.add('active')
  State.tab = tab
  await loadConversations()
}

function filterConvs(q) {
  State.search = q
  clearTimeout(State._searchTimer)
  State._searchTimer = setTimeout(() => loadConversations(), 300)
}

// ── Navigation mobile ─────────────────────────────────────────────────

function backToList() {
  $('conv-col')?.classList.remove('hidden-mobile')
  $('messages-col')?.classList.remove('visible')
  closeProfilePanel()
  stopPolling()
}

window.addEventListener('resize', () => {
  if (window.innerWidth > 700) {
    $('conv-col')?.classList.remove('hidden-mobile')
    $('messages-col')?.classList.remove('visible')
  }
  if (window.innerWidth > 1024) closeProfilePanel()
})

// ── Panneau profil ────────────────────────────────────────────────────

function openProfilePanel() {
  $('profile-col')?.classList.add('open')
  $('profile-overlay')?.classList.add('open')
  document.body.style.overflow = 'hidden'
}

function closeProfilePanel() {
  $('profile-col')?.classList.remove('open')
  $('profile-overlay')?.classList.remove('open')
  document.body.style.overflow = ''
}

// ── Modal abonnement ──────────────────────────────────────────────────
// ══════════════════════════════════════════════════════════════
// DRAWERS & MODALS
// ══════════════════════════════════════════════════════════════
function openModal(id)  { document.getElementById(id)?.classList.add('open') }
function closeModal(id) { document.getElementById(id)?.classList.remove('open') }  
function openSubscriptionModal() {
  openModal('modal-subscription')
}

async function createSubscription() {
  const select = document.querySelector('#modal-subscription select')
  const note   = document.querySelector('#modal-subscription textarea')
  const plan   = select?.value
  if (!plan || !State.currentUserId) return

  try {
    await API.apiCreateSubscription(State.currentUserId, plan, note?.value || '')
    Render.toast('Abonnement créé', 'success')
    closeModal('modal-subscription')
    const profile = await API.apiGetProfile(State.currentUserId)
    State.currentProfile = profile
    updateProfilePanel(profile)
  } catch (e) {
    Render.toast('Erreur création abonnement', 'error')
  }
}

// ── Export conversation ───────────────────────────────────────────────
async function markTestimonial(messageId, value) {
  try {
    await API.apiMarkTestimonial(messageId, value)
    const msg = State.messages.find(m => m.id === messageId)
    if (msg) msg.is_testimonial = value
    renderMessagesFeed(false)
    Render.toast(value ? '⭐ Témoignage marqué' : 'Témoignage retiré', 'success')
  } catch (e) {
    Render.toast('Erreur mise à jour témoignage', 'error')
  }
}

async function markRequiresAdmin(messageId, value) {
  try {
    await API.apiMarkRequiresAdmin(messageId, value)
    const msg = State.messages.find(m => m.id === messageId)
    if (msg) msg.requires_admin = value
    renderMessagesFeed(false)
    Render.toast(value ? '⚡ Marqué pour admin' : 'Marquage retiré', 'success')
  } catch (e) {
    Render.toast('Erreur mise à jour', 'error')
  }
}
function exportConv(fmt = 'json') {
  if (!State.currentUserId) return
  const url = API.apiExportConversation(State.currentUserId, fmt)
  window.open(url, '_blank')
}

// ── Keyboard ──────────────────────────────────────────────────────────

function handleKey(e) {
  if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage() }
}

function autoResize(el) {
  el.style.height = 'auto'
  el.style.height = Math.min(el.scrollHeight, 120) + 'px'
  const c = $('compose-count')
  if (c) c.textContent = `${el.value.length} / 4096`
}

// ── Expose window.App ─────────────────────────────────────────────────

window.App = {
  init,
  selectConv,
  sendMessage,
  triggerUpload,
  clearUpload,
  setReply,
  clearReply,
  toggleIA,
  switchConvTab,
  filterConvs,
  backToList,
  openProfilePanel,
  closeProfilePanel,
  openSubscriptionModal,
  createSubscription,
  exportConv,
  handleKey,
  autoResize,
  markTestimonial,
  markRequiresAdmin,
}

// ── Boot ──────────────────────────────────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
  App.init()

  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeProfilePanel()
  })

  const feed = $('messages-feed')
  if (feed) feed.scrollTop = feed.scrollHeight
})