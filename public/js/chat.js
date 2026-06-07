/* ══════════════════════════════════════════════════════════════════════
   chats.js — Orchestrateur principal Chat Direct
   v2.0 — Mise à jour dynamique · localStorage · UX optimisée
   ══════════════════════════════════════════════════════════════════════ */

import {
  apiGetConversations, apiGetMessages, apiSendMessage,
  apiSetIA, apiMarkRead, apiSetBlocked, apiGetProfile,
  apiCreateSubscription, apiExportConversation,
  apiMarkTestimonial, apiMarkRequiresAdmin, apiUploadMedia
} from './chat_api.js'

import {
  renderConvItem, renderMessage, renderDateSep,
  renderProfile, renderUploadPreview, toast, escapeHtml, nowTime
} from './chat_render.js'

// ── Constantes ────────────────────────────────────────────────────────
const POLL_INTERVAL    = 4000   // ms — polling messages conv active
const CONV_INTERVAL    = 8000   // ms — polling liste conversations
const DRAFT_PREFIX     = 'fdk_draft_'
const LS_ACTIVE_CONV   = 'fdk_active_conv'
const LS_ACTIVE_TAB    = 'fdk_active_tab'
const LS_SCROLL_PREFIX = 'fdk_scroll_'
const MSG_BATCH        = 60

// ── État global ───────────────────────────────────────────────────────
const State = {
  convs:        [],          // liste complète des conversations
  activeUid:    null,        // userId actif
  activeConv:   null,        // objet conv actif
  messages:     [],          // messages chargés
  profile:      null,        // profil colonne droite
  tab:          'all',       // filtre actif
  search:       '',          // recherche actuelle
  replyId:      null,        // message répondu
  uploadFile:   null,        // fichier en attente
  iaEnabled:    false,
  isBlocked:    false,
  lastMsgId:    null,        // dernier message ID (polling)
  pendingIds:   new Set(),   // IDs en cours d'envoi (anti-doublon)
  _pollMsg:     null,        // timer polling messages
  _pollConv:    null,        // timer polling convs
  _userScrolled: false,      // l'user a scrollé manuellement
}

// ── DOM refs ──────────────────────────────────────────────────────────
const $ = id => document.getElementById(id)
const DOM = {
  convList:    () => $('conv-list'),
  msgFeed:     () => $('messages-feed'),
  composeIn:   () => $('compose-input'),
  composeArea: () => $('compose-area'),
  sendBtn:     () => $('send-btn'),
  sendLabel:   () => $('send-label'),
  chatAv:      () => $('chat-av'),
  chatName:    () => $('chat-name'),
  chatHandle:  () => $('chat-handle'),
  iaToggle:    () => $('ia-toggle'),
  iaBanner:    () => $('ia-banner'),
  blockedBanner: () => $('blocked-banner'),
  composeCount:  () => $('compose-count'),
  replyPreview:  () => $('reply-preview'),
  replyText:     () => $('reply-text'),
  uploadPreview: () => $('upload-preview'),
  profileCol:    () => $('profile-col'),
  profileOverlay: () => $('profile-overlay'),
  navUnread:    () => $('nav-unread-badge'),
  tabAdminCount: () => $('tab-admin-count'),
  tabUnreadCount: () => $('tab-unread-count'),
  convCol:      () => $('conv-col'),
  messagesCol:  () => $('messages-col'),
}

// ══════════════════════════════════════════════════════════════════════
// INIT
// ══════════════════════════════════════════════════════════════════════
async function init() {
  // Restaurer l'état depuis localStorage
  State.tab = ls(LS_ACTIVE_TAB) || 'all'
  activateTab(State.tab)

  // Charger les conversations
  await loadConversations()

  // Restaurer la conversation active
  const savedUid = ls(LS_ACTIVE_CONV)
  if (savedUid) {
    const conv = State.convs.find(c => String(c.user_id) === String(savedUid))
    if (conv) {
      const item = $(`conv-${savedUid}`)
      if (item) selectConv(savedUid, item)
    }
  }

  // Polling liste conversations
  State._pollConv = setInterval(pollConversations, CONV_INTERVAL)

  // Écouter les brouillons
  DOM.composeIn()?.addEventListener('input', function() {
    autoResize(this)
    saveDraft(State.activeUid, this.value)
    const c = DOM.composeCount()
    if (c) c.textContent = `${this.value.length} / 4096`
  })

  // Scroll position tracking
  DOM.msgFeed()?.addEventListener('scroll', function() {
    const nearBottom = this.scrollHeight - this.scrollTop - this.clientHeight < 80
    State._userScrolled = !nearBottom
    if (State.activeUid) {
      lsSet(LS_SCROLL_PREFIX + State.activeUid, this.scrollTop)
    }
  })

  // Keyboard escape
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeProfilePanel()
  })

  // Resize handlers
  window.addEventListener('resize', () => {
    if (!isMobile()) {
      DOM.convCol()?.classList.remove('hidden-mobile')
      DOM.messagesCol()?.classList.remove('visible-mobile')
    }
    if (!isTablet()) closeProfilePanel()
  })
}

// ══════════════════════════════════════════════════════════════════════
// CONVERSATIONS — chargement & polling
// ══════════════════════════════════════════════════════════════════════
async function loadConversations() {
  try {
    const data = await apiGetConversations(State.tab, State.search)
    State.convs = data.conversations || data || []
    renderConvList(State.convs)
    updateStats()
  } catch (e) {
    console.warn('[chats] loadConversations:', e.message)
    // Afficher état vide sans crasher
    DOM.convList().innerHTML = emptyState('Impossible de charger les conversations')
  }
}

async function pollConversations() {
  try {
    const data = await apiGetConversations(State.tab, State.search)
    const fresh = data.conversations || data || []

    // Détecter s'il y a des changements
    const hasChanges = fresh.some(fc => {
      const old = State.convs.find(c => c.user_id === fc.user_id)
      return !old
        || old.last_message !== fc.last_message
        || old.unread_count !== fc.unread_count
    })

    if (hasChanges) {
      State.convs = fresh
      renderConvList(fresh)
      updateStats()
    }
  } catch (e) {
    // Silencieux en polling
  }
}

// Remontée d'une conversation en tête de liste (comme WhatsApp)
function bumpConversation(userId) {
  const idx = State.convs.findIndex(c => String(c.user_id) === String(userId))
  if (idx > 0) {
    const [conv] = State.convs.splice(idx, 1)
    State.convs.unshift(conv)
    renderConvList(State.convs)
  }
}

function renderConvList(convs) {
  const el = DOM.convList()
  const q  = State.search.toLowerCase()
  const filtered = q
    ? convs.filter(c => (c.name || '').toLowerCase().includes(q))
    : convs

  if (!filtered.length) {
    el.innerHTML = emptyState(q ? 'Aucun résultat' : 'Aucune conversation')
    return
  }

  // Préserver la sélection active
  el.innerHTML = filtered.map(renderConvItem).join('')

  // Marquer actif
  if (State.activeUid) {
    $(`conv-${State.activeUid}`)?.classList.add('active')
  }
}

function updateStats() {
  const totalUnread   = State.convs.reduce((s, c) => s + (c.unread_count || 0), 0)
  const adminRequired = State.convs.filter(c => c.requires_admin).length

  const badge = DOM.navUnread()
  if (badge) badge.textContent = totalUnread || ''

  const adminBadge = DOM.tabAdminCount()
  if (adminBadge) adminBadge.textContent = adminRequired || ''

  const unreadBadge = DOM.tabUnreadCount()
  if (unreadBadge) unreadBadge.textContent = totalUnread || ''
}

// ══════════════════════════════════════════════════════════════════════
// SÉLECTION CONVERSATION
// ══════════════════════════════════════════════════════════════════════
async function selectConv(userId, el) {
  // Nettoyer le polling précédent
  clearInterval(State._pollMsg)

  // Marquer actif visuellement
  document.querySelectorAll('.conv-item').forEach(c => c.classList.remove('active'))
  el?.classList.add('active')

  State.activeUid  = userId
  State.lastMsgId  = null
  State._userScrolled = false

  // Persister
  lsSet(LS_ACTIVE_CONV, userId)

  // Trouver les données de la conversation
  State.activeConv = State.convs.find(c => String(c.user_id) === String(userId)) || {}

  // Mettre à jour le header
  updateChatHeader(State.activeConv)

  // Mobile : montrer le fil
  if (isMobile()) showMessagesPanel()

  // Indicateur de chargement discret
  showFeedLoader()

  // Charger messages + profil en parallèle
  try {
    const [msgs, profile] = await Promise.all([
      apiGetMessages(userId, MSG_BATCH),
      apiGetProfile(userId).catch(() => null),
    ])

    State.messages = msgs.messages || msgs || []
    State.lastMsgId = State.messages.length
      ? Math.max(...State.messages.map(m => m.id || 0))
      : null

    renderFeed(State.messages)

    // Scroll : restaurer position ou aller en bas
    const savedScroll = ls(LS_SCROLL_PREFIX + userId)
    scrollFeed(savedScroll ? parseInt(savedScroll) : 'bottom')

    // Profil
    if (profile) {
      State.profile = profile
      renderProfilePanel(profile)
    }

    // Zone de saisie
    showComposeArea(State.activeConv)

    // Restaurer le brouillon
    const draft = ls(DRAFT_PREFIX + userId)
    if (draft) {
      const inp = DOM.composeIn()
      inp.value = draft
      autoResize(inp)
    }

    // Marquer comme lu
    apiMarkRead(userId).catch(() => {})
    markConvRead(userId)

    // Démarrer le polling des messages
    State._pollMsg = setInterval(() => pollMessages(userId), POLL_INTERVAL)

  } catch (e) {
    DOM.msgFeed().innerHTML = emptyState('Erreur de chargement')
    toast('Erreur lors du chargement', 'error')
  }
}

// ══════════════════════════════════════════════════════════════════════
// POLLING MESSAGES
// ══════════════════════════════════════════════════════════════════════
async function pollMessages(userId) {
  if (!State.activeUid || String(State.activeUid) !== String(userId)) return

  try {
    const msgs = await apiGetMessages(userId, 20, null, State.lastMsgId)
    const fresh = (msgs.messages || msgs || []).filter(m => {
      // Anti-doublon : ignorer les IDs déjà affichés ou en cours d'envoi
      return m.id > (State.lastMsgId || 0) && !State.pendingIds.has(m.id)
    })

    if (!fresh.length) return

    // Mettre à jour lastMsgId
    State.lastMsgId = Math.max(...fresh.map(m => m.id), State.lastMsgId || 0)

    // Ajouter au feed sans re-render complet
    appendMessages(fresh)

    // Remonter la conv en tête de liste
    bumpConversation(userId)

    // Mettre à jour les données dans State.convs
    const idx = State.convs.findIndex(c => String(c.user_id) === String(userId))
    if (idx !== -1 && fresh.length) {
      const last = fresh[fresh.length - 1]
      State.convs[idx].last_message = last.message_text || ''
      State.convs[idx].last_activity = last.created_at
    }

    // Auto-scroll si l'user est en bas
    if (!State._userScrolled) scrollFeed('bottom')

    // Marquer comme lu silencieusement
    apiMarkRead(userId).catch(() => {})
    markConvRead(userId)

  } catch (e) {
    // Silencieux
  }
}

function appendMessages(msgs) {
  const feed = DOM.msgFeed()
  msgs.forEach(msg => {
    const div = document.createElement('div')
    div.innerHTML = renderMessage(msg, State.activeConv)
    while (div.firstChild) feed.appendChild(div.firstChild)
  })
  State.messages.push(...msgs)
}

// ══════════════════════════════════════════════════════════════════════
// RENDU FEED
// ══════════════════════════════════════════════════════════════════════
function renderFeed(messages) {
  const feed = DOM.msgFeed()
  if (!messages.length) {
    feed.innerHTML = emptyState('Aucun message')
    return
  }

  const parts = []
  let lastDate = null

  messages.forEach(msg => {
    const d = msg.created_at ? new Date(msg.created_at).toDateString() : null
    if (d && d !== lastDate) {
      parts.push(renderDateSep(msg.created_at))
      lastDate = d
    }
    parts.push(renderMessage(msg, State.activeConv))
  })

  feed.innerHTML = parts.join('')
}

function showFeedLoader() {
  DOM.msgFeed().innerHTML = `
    <div style="display:flex;align-items:center;justify-content:center;height:100%;gap:6px;color:#3f3f46;font-size:12px;">
      <span class="loader-dot"></span><span class="loader-dot" style="animation-delay:.15s"></span><span class="loader-dot" style="animation-delay:.3s"></span>
    </div>`
}

// ══════════════════════════════════════════════════════════════════════
// ENVOI MESSAGE
// ══════════════════════════════════════════════════════════════════════
async function sendMessage() {
  const inp = DOM.composeIn()
  const val = inp.value.trim()
  if (!val && !State.uploadFile) return
  if (!State.activeUid) return

  const uid = State.activeUid

  // Optimistic update — afficher immédiatement
  const tempId = `temp_${Date.now()}`
  const tempMsg = {
    id:           tempId,
    direction:    'outbound',
    answered_by:  'admin',
    message_text: val,
    created_at:   new Date().toISOString(),
    status:       'sending',
    replied_to_id: State.replyId,
    replied_to_text: State.replyId ? DOM.replyText()?.textContent : null,
  }

  appendMessages([tempMsg])
  scrollFeed('bottom')

  // Reset UI
  inp.value = ''
  inp.style.height = 'auto'
  const draft = DRAFT_PREFIX + uid
  localStorage.removeItem(draft)
  const count = DOM.composeCount()
  if (count) count.textContent = '0 / 4096'
  clearReply()

  // Construire le payload
  const payload = {
    message_text: val,
    answered_by:  'admin',
    replied_to_id: State.replyId || undefined,
  }

  // Upload si fichier joint
  if (State.uploadFile) {
    try {
      const upload = await apiUploadMedia(uid, State.uploadFile)
      payload.media_url   = upload.media_url
      payload.message_type = upload.message_type || 'document'
    } catch {
      toast('Erreur upload fichier', 'error')
    }
    clearUpload()
  }

  State.replyId = null

  try {
    const sent = await apiSendMessage(uid, payload)

    // Remplacer le message temporaire par le vrai
    const tempEl = document.querySelector(`[data-temp="${tempId}"]`)
    if (tempEl && sent.id) {
      const div = document.createElement('div')
      div.innerHTML = renderMessage({ ...tempMsg, ...sent, status: 'sent' }, State.activeConv)
      tempEl.replaceWith(...div.childNodes)
    }

    // Mettre à jour lastMsgId
    if (sent.id && sent.id > (State.lastMsgId || 0)) {
      State.lastMsgId = sent.id
    }

    // Remonter la conv
    bumpConversation(uid)
    const idx = State.convs.findIndex(c => String(c.user_id) === String(uid))
    if (idx !== -1) {
      State.convs[idx].last_message  = val
      State.convs[idx].last_activity = new Date().toISOString()
    }

  } catch (e) {
    toast('Erreur envoi : ' + e.message, 'error')
    // Supprimer le message temporaire
    const tempEl = document.querySelector(`[data-temp="${tempId}"]`)
    tempEl?.remove()
  }
}

// ══════════════════════════════════════════════════════════════════════
// UI HELPERS
// ══════════════════════════════════════════════════════════════════════
function updateChatHeader(conv) {
  const name   = conv.name || 'Conversation'
  const handle = conv.name ? `${conv.name} · ID ${conv.user_id}` : ''

  const av = DOM.chatAv()
  av.className = `av ${avatarClass(conv.name)}`
  av.style.cssText = 'width:32px;height:32px;font-size:12px;'
  av.textContent = avatarText(conv.name)
  DOM.chatName().textContent  = name
  DOM.chatHandle().textContent = handle

  State.iaEnabled  = conv.ia_enabled || false
  State.isBlocked  = conv.is_blocked || false

  const tog = DOM.iaToggle()
  if (tog) tog.className = 'toggle' + (State.iaEnabled ? ' on' : '')

  DOM.blockedBanner().style.display = State.isBlocked ? 'flex' : 'none'
  DOM.iaBanner().style.display = (!State.isBlocked && State.iaEnabled) ? 'flex' : 'none'
}

function showComposeArea(conv) {
  const area = DOM.composeArea()
  if (!area) return
  area.style.display = conv.is_blocked ? 'none' : 'block'
  const inp = DOM.composeIn()
  if (inp) {
    inp.placeholder = conv.is_blocked
      ? 'Membre bloqué'
      : `Envoyer un message à ${(conv.name || '').split(' ')[0] || ''}…`
    inp.disabled = !!conv.is_blocked
  }
}

function renderProfilePanel(profile) {
  DOM.profileCol().innerHTML = renderProfile(profile)
}

function markConvRead(userId) {
  const conv = State.convs.find(c => String(c.user_id) === String(userId))
  if (conv) conv.unread_count = 0
  const item = $(`conv-${userId}`)
  item?.querySelector('.unread-dot')?.remove()
  item?.querySelector('.unread-badge')?.remove()
  updateStats()
}

function scrollFeed(target) {
  const feed = DOM.msgFeed()
  if (!feed) return
  if (target === 'bottom') {
    feed.scrollTop = feed.scrollHeight
  } else if (typeof target === 'number') {
    feed.scrollTop = target
  }
}

function emptyState(msg) {
  return `<div style="padding:40px;text-align:center;color:#3f3f46;font-size:12px;">${msg}</div>`
}

// ══════════════════════════════════════════════════════════════════════
// FILTRES & RECHERCHE
// ══════════════════════════════════════════════════════════════════════
function filterConvs(q) {
  State.search = q
  renderConvList(State.convs)
}

function switchConvTab(el, tab) {
  State.tab = tab
  lsSet(LS_ACTIVE_TAB, tab)
  el.closest('div').querySelectorAll('.tab').forEach(t => t.classList.remove('active'))
  el.classList.add('active')
  loadConversations()
}

function activateTab(tab) {
  const tabs = document.querySelectorAll('.tab')
  tabs.forEach(t => {
    if (t.getAttribute('onclick')?.includes(`'${tab}'`)) t.classList.add('active')
    else t.classList.remove('active')
  })
}

// ══════════════════════════════════════════════════════════════════════
// TOGGLE IA
// ══════════════════════════════════════════════════════════════════════
async function toggleIA(btn) {
  if (!State.activeUid) return
  btn.classList.toggle('on')
  const isOn = btn.classList.contains('on')
  State.iaEnabled = isOn
  DOM.iaBanner().style.display = isOn ? 'flex' : 'none'
  try {
    await apiSetIA(State.activeUid, isOn)
    const conv = State.convs.find(c => String(c.user_id) === String(State.activeUid))
    if (conv) conv.ia_enabled = isOn
  } catch {
    // Rollback
    btn.classList.toggle('on')
    State.iaEnabled = !isOn
    DOM.iaBanner().style.display = State.iaEnabled ? 'flex' : 'none'
    toast('Erreur mise à jour IA', 'error')
  }
}

// ══════════════════════════════════════════════════════════════════════
// REPLY
// ══════════════════════════════════════════════════════════════════════
function setReply(msgId, text) {
  State.replyId = msgId
  const rp = DOM.replyPreview()
  rp.style.display = 'flex'
  DOM.replyText().textContent = text
  DOM.composeIn()?.focus()
}

function clearReply() {
  State.replyId = null
  const rp = DOM.replyPreview()
  if (rp) rp.style.display = 'none'
}

// ══════════════════════════════════════════════════════════════════════
// UPLOAD
// ══════════════════════════════════════════════════════════════════════
function triggerUpload() { $('file-input')?.click() }

function handleFileSelect(input) {
  if (!input.files.length) return
  State.uploadFile = input.files[0]
  const preview = DOM.uploadPreview()
  preview.style.display = 'block'
  preview.innerHTML = renderUploadPreview(State.uploadFile)
}

function clearUpload() {
  State.uploadFile = null
  const preview = DOM.uploadPreview()
  if (preview) { preview.style.display = 'none'; preview.innerHTML = '' }
  const fi = $('file-input')
  if (fi) fi.value = ''
}

// ══════════════════════════════════════════════════════════════════════
// ABONNEMENT
// ══════════════════════════════════════════════════════════════════════
async function createSubscription() {
  if (!State.activeUid) return
  const select = document.querySelector('#modal-subscription select')
  const note   = document.querySelector('#modal-subscription textarea')
  try {
    await apiCreateSubscription(State.activeUid, select?.value, note?.value || '')
    toast('Abonnement créé', 'success')
    closeModal('modal-subscription')
  } catch (e) {
    toast('Erreur : ' + e.message, 'error')
  }
}

function openSubscriptionModal() {
  const conv = State.activeConv
  const nameEl = $('sub-modal-name')
  if (nameEl) nameEl.textContent = conv?.name || '—'
  openModal('modal-subscription')
}

// ══════════════════════════════════════════════════════════════════════
// EXPORT
// ══════════════════════════════════════════════════════════════════════
function exportConv(fmt = 'json') {
  if (!State.activeUid) return
  window.open(apiExportConversation(State.activeUid, fmt), '_blank')
}

// ══════════════════════════════════════════════════════════════════════
// TESTIMONIAL / ADMIN FLAG
// ══════════════════════════════════════════════════════════════════════
async function markTestimonial(msgId, value) {
  try {
    await apiMarkTestimonial(msgId, value)
    toast(value ? 'Témoignage marqué ⭐' : 'Témoignage retiré', 'success')
    // Re-render du feed pour mettre à jour
    const msg = State.messages.find(m => m.id === msgId)
    if (msg) { msg.is_testimonial = !!value; renderFeed(State.messages) }
  } catch (e) {
    toast('Erreur', 'error')
  }
}

// ══════════════════════════════════════════════════════════════════════
// SIDEBAR MOBILE
// ══════════════════════════════════════════════════════════════════════
function openSidebar() {
  $('sidebar')?.classList.add('open')
  $('sidebar-overlay')?.classList.add('open')
  document.body.style.overflow = 'hidden'
}

function closeSidebar() {
  $('sidebar')?.classList.remove('open')
  $('sidebar-overlay')?.classList.remove('open')
  document.body.style.overflow = ''
}

// ══════════════════════════════════════════════════════════════════════
// NAVIGATION MOBILE CHAT (liste ↔ messages)
// ══════════════════════════════════════════════════════════════════════
function showMessagesPanel() {
  DOM.convCol()?.classList.add('hidden-mobile')
  DOM.messagesCol()?.classList.add('visible-mobile')
}

function backToList() {
  DOM.convCol()?.classList.remove('hidden-mobile')
  DOM.messagesCol()?.classList.remove('visible-mobile')
  closeProfilePanel()
}

// ══════════════════════════════════════════════════════════════════════
// PROFIL PANEL
// ══════════════════════════════════════════════════════════════════════
function openProfilePanel() {
  DOM.profileCol()?.classList.add('open')
  DOM.profileOverlay()?.classList.add('open')
  document.body.style.overflow = 'hidden'
}

function closeProfilePanel() {
  DOM.profileCol()?.classList.remove('open')
  DOM.profileOverlay()?.classList.remove('open')
  document.body.style.overflow = ''
}

// ══════════════════════════════════════════════════════════════════════
// MODALS (globales)
// ══════════════════════════════════════════════════════════════════════
function openModal(id) {
  const el = $(id)
  if (el) el.classList.add('open')
}

function closeModal(id) {
  const el = $(id)
  if (el) el.classList.remove('open')
}

// Fermer modal en cliquant sur l'overlay
document.querySelectorAll('.modal-overlay').forEach(overlay => {
  overlay.addEventListener('click', e => {
    if (e.target === overlay) overlay.classList.remove('open')
  })
})

// ══════════════════════════════════════════════════════════════════════
// TEXTAREA — auto-resize & keyboard
// ══════════════════════════════════════════════════════════════════════
function autoResize(el) {
  el.style.height = 'auto'
  el.style.height = Math.min(el.scrollHeight, 120) + 'px'
}

function handleKey(e) {
  if (e.key === 'Enter' && !e.shiftKey) {
    e.preventDefault()
    sendMessage()
  }
}

// ══════════════════════════════════════════════════════════════════════
// localStorage helpers
// ══════════════════════════════════════════════════════════════════════
function ls(key) {
  try { return localStorage.getItem(key) } catch { return null }
}

function lsSet(key, val) {
  try { localStorage.setItem(key, val) } catch {}
}

function saveDraft(uid, text) {
  if (!uid) return
  if (text) lsSet(DRAFT_PREFIX + uid, text)
  else try { localStorage.removeItem(DRAFT_PREFIX + uid) } catch {}
}

// Nettoyage des vieilles données (> 7 jours)
function cleanupLocalStorage() {
  const MAX_AGE = 7 * 24 * 60 * 60 * 1000
  const now = Date.now()
  try {
    Object.keys(localStorage)
      .filter(k => k.startsWith('fdk_scroll_') || k.startsWith('fdk_draft_'))
      .forEach(k => {
        const ts = localStorage.getItem(k + '_ts')
        if (ts && now - parseInt(ts) > MAX_AGE) {
          localStorage.removeItem(k)
          localStorage.removeItem(k + '_ts')
        }
      })
  } catch {}
}

// ══════════════════════════════════════════════════════════════════════
// Utilitaires avatar (fallback si chat_render n'est pas accessible)
// ══════════════════════════════════════════════════════════════════════
function avatarClass(name) {
  const classes = ['av-sky','av-green','av-amber','av-violet','av-teal','av-coral']
  if (!name) return 'av-default'
  return classes[name.charCodeAt(0) % classes.length]
}

function avatarText(name) {
  if (!name) return '??'
  return name.slice(0, 2).toUpperCase()
}

const isMobile  = () => window.innerWidth <= 700
const isTablet  = () => window.innerWidth <= 1024

// ══════════════════════════════════════════════════════════════════════
// API PUBLIQUE — appelée depuis le HTML via onclick="App.xxx()"
// ══════════════════════════════════════════════════════════════════════
window.App = {
  selectConv,
  filterConvs,
  switchConvTab,
  toggleIA,
  setReply,
  clearReply,
  sendMessage,
  triggerUpload,
  handleFileSelect,
  clearUpload,
  autoResize,
  handleKey,
  backToList,
  openProfilePanel,
  closeProfilePanel,
  openSubscriptionModal,
  createSubscription,
  exportConv,
  markTestimonial,
}

window.openSidebar  = openSidebar
window.closeSidebar = closeSidebar
window.openModal    = openModal
window.closeModal   = closeModal

// ══════════════════════════════════════════════════════════════════════
// DÉMARRAGE
// ══════════════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
  cleanupLocalStorage()
  init()

  // File input
  $('file-input')?.addEventListener('change', function() {
    App.handleFileSelect(this)
  })

  // Sidebar close button
  $('sidebar-close')?.addEventListener('click', closeSidebar)
  $('sidebar-overlay')?.addEventListener('click', closeSidebar)
})