/* ══════════════════════════════════════════════════════════════════════
   chat_app.js — Orchestrateur principal
   - Badges unread basés sur localStorage (dernier msg lu par conv)
   - Auto-scroll à l'ouverture
   - Remontée automatique des convs (comme WhatsApp)
   - Polling sans saut visuel
   ══════════════════════════════════════════════════════════════════════ */

import * as API    from './chat_api.js'
import * as Render from './chat_render.js'

// ── Constantes ────────────────────────────────────────────────────────
const POLL_MSG_MS   = 4000   // polling nouveaux messages conv active
const POLL_CONV_MS  = 8000   // polling liste conversations
const MSG_LIMIT     = 60
const LS_LAST_READ  = 'fdk_last_read_'   // + userId → dernier msg_id lu
const LS_LAST_READ_TS = 'fdk_last_read_ts_' // + userId → timestamp lecture
const LS_DRAFT      = 'fdk_draft_'
const LS_ACTIVE     = 'fdk_active_conv'
const LS_TAB        = 'fdk_active_tab'
const LS_SCROLL     = 'fdk_scroll_'

// ── Types fichiers acceptés ───────────────────────────────────────────
const ACCEPTED_FILES = [
  'image/jpeg','image/png','image/gif','image/webp',
  'video/mp4','video/quicktime','video/x-msvideo','video/x-matroska','video/webm',
  'application/pdf','application/msword',
  'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
  'application/vnd.ms-excel',
  'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
  'application/vnd.ms-powerpoint',
  'application/vnd.openxmlformats-officedocument.presentationml.presentation',
  'text/plain','application/zip','application/x-rar-compressed',
]

// ── State ─────────────────────────────────────────────────────────────
const S = {
  convs:       [],
  uid:         null,   // userId conversation active
  conv:        null,   // objet conversation actif
  profile:     null,
  messages:    [],
  oldestId:    null,
  newestId:    null,
  tab:         'all',
  search:      '',
  replyId:     null,
  replyText:   null,
  uploadFile:  null,
  uploadResult:null,
  sending:     false,
  _pollMsg:    null,
  _pollConv:   null,
  _atBottom:   true,   // l'utilisateur est en bas du feed
  _loading:    false,
}

const $ = id => document.getElementById(id)

// ══════════════════════════════════════════════════════════════════════
// localStorage helpers
// ══════════════════════════════════════════════════════════════════════
function lsGet(k)      { try { return localStorage.getItem(k) }       catch { return null } }
function lsSet(k, v)   { try { localStorage.setItem(k, String(v)) }   catch {} }
function lsDel(k)      { try { localStorage.removeItem(k) }           catch {} }

// Nettoyage des entrées > 30 jours
function lsClean() {
  const MAX = 30 * 86400 * 1000
  const now = Date.now()
  try {
    Object.keys(localStorage)
      .filter(k => k.startsWith('fdk_'))
      .forEach(k => {
        if (!k.includes('_ts_')) return
        const ts = parseInt(localStorage.getItem(k) || '0')
        if (now - ts > MAX) {
          const base = k.replace('_ts_', '_')
          lsDel(k); lsDel(base)
        }
      })
  } catch {}
}

// ── Dernier message lu par conversation ───────────────────────────────
function getLastRead(userId)       { return parseInt(lsGet(LS_LAST_READ + userId) || '0') }
function setLastRead(userId, msgId){ lsSet(LS_LAST_READ + userId, msgId); lsSet(LS_LAST_READ_TS + userId, Date.now()) }

// Calcule le vrai unread_count côté client à partir du last_read
// On fait confiance au serveur pour unread_count mais on le corrige
// si l'admin a déjà lu jusqu'à un msg_id plus récent
function getEffectiveUnread(conv) {
  const lastRead = getLastRead(conv.user_id)
  if (!lastRead) return conv.unread_count || 0
  // Si le dernier msg connu est <= lastRead → déjà tout lu
  if (conv.last_message_id && conv.last_message_id <= lastRead) return 0
  return conv.unread_count || 0
}

// ══════════════════════════════════════════════════════════════════════
// INIT
// ══════════════════════════════════════════════════════════════════════
async function init() {
  lsClean()

  S.tab = lsGet(LS_TAB) || 'all'
  _activateTab(S.tab)

  await loadConversations()

  // Restaurer conv active
  const savedUid = lsGet(LS_ACTIVE)
  if (savedUid) {
    const item = $(`conv-${savedUid}`)
    if (item) selectConv(parseInt(savedUid), item)
  }

  // File input
  const fi = $('file-input')
  if (fi) {
    fi.setAttribute('accept', ACCEPTED_FILES.join(','))
    fi.addEventListener('change', e => {
      if (e.target.files.length) _previewUpload(e.target.files[0])
    })
  }

  // Scroll tracking
  $('messages-feed')?.addEventListener('scroll', function() {
    S._atBottom = this.scrollHeight - this.scrollTop - this.clientHeight < 80
    if (S.uid) lsSet(LS_SCROLL + S.uid, this.scrollTop)
  })

  // Compose input → draft + counter
  $('compose-input')?.addEventListener('input', function() {
    autoResize(this)
    if (S.uid) {
      if (this.value) lsSet(LS_DRAFT + S.uid, this.value)
      else            lsDel(LS_DRAFT + S.uid)
    }
    const c = $('compose-count')
    if (c) c.textContent = `${this.value.length} / 4096`
  })

  // Escape
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeProfilePanel()
  })

  // Resize
  window.addEventListener('resize', () => {
    if (window.innerWidth > 700) {
      $('conv-col')?.classList.remove('hidden-mobile')
      $('messages-col')?.classList.remove('visible', 'visible-mobile')
    }
    if (window.innerWidth > 1024) closeProfilePanel()
  })

  // Polling conversations
  S._pollConv = setInterval(pollConversations, POLL_CONV_MS)
}

// ══════════════════════════════════════════════════════════════════════
// CONVERSATIONS
// ══════════════════════════════════════════════════════════════════════
async function loadConversations() {
  try {
    const data = await API.apiGetConversations(S.tab, S.search)
    S.convs = data.conversations || data || []
    _renderConvList()
    _updateStats()
  } catch (e) {
    console.warn('[chat_app] loadConversations:', e.message)
    const el = $('conv-list')
    if (el) el.innerHTML = _empty('Impossible de charger')
  }
}

async function pollConversations() {
  try {
    const data  = await API.apiGetConversations(S.tab, S.search)
    const fresh = data.conversations || data || []

    // Détecter changements pour éviter re-render inutile
    const changed = fresh.some(fc => {
      const old = S.convs.find(c => c.user_id === fc.user_id)
      return !old
        || old.last_message    !== fc.last_message
        || old.unread_count    !== fc.unread_count
        || old.last_message_id !== fc.last_message_id
    })

    if (changed) {
      S.convs = fresh
      _renderConvList()
      _updateStats()
    }
  } catch { /* silencieux */ }
}

// Remontée automatique d'une conv (nouveau message reçu/envoyé)
function _bumpConv(userId) {
  const idx = S.convs.findIndex(c => String(c.user_id) === String(userId))
  if (idx > 0) {
    const [conv] = S.convs.splice(idx, 1)
    S.convs.unshift(conv)
    _renderConvList()
  }
}

function _renderConvList() {
  const el = $('conv-list')
  if (!el) return
  const q = S.search.toLowerCase()
  const list = q ? S.convs.filter(c => (c.name || '').toLowerCase().includes(q)) : S.convs

  if (!list.length) {
    el.innerHTML = _empty(q ? 'Aucun résultat' : 'Aucune conversation')
    return
  }

  // Reconstruire le HTML avec unread corrigé côté client
  el.innerHTML = list.map(conv => {
    const effectiveUnread = getEffectiveUnread(conv)
    return Render.renderConvItem({ ...conv, unread_count: effectiveUnread })
  }).join('')

  // Restaurer la sélection active
  if (S.uid) $(`conv-${S.uid}`)?.classList.add('active')
}

function _updateStats() {
  // Compter avec correction client
  const totalUnread = S.convs.reduce((sum, c) => sum + getEffectiveUnread(c), 0)
  const adminCount  = S.convs.filter(c => c.requires_admin).length

  const navBadge = $('nav-unread-badge')
  if (navBadge) {
    navBadge.textContent = totalUnread || ''
    navBadge.style.display = totalUnread ? '' : 'none'
  }
  const adminBadge = $('tab-admin-count')
  if (adminBadge) adminBadge.textContent = adminCount || ''
  const unreadBadge = $('tab-unread-count')
  if (unreadBadge) unreadBadge.textContent = totalUnread || ''
}

// ══════════════════════════════════════════════════════════════════════
// SÉLECTION CONVERSATION
// ══════════════════════════════════════════════════════════════════════
async function selectConv(userId, el) {
  // Stop polling précédent
  clearInterval(S._pollMsg)

  // Activer visuellement
  document.querySelectorAll('.conv-item').forEach(c => c.classList.remove('active'))
  el?.classList.add('active')

  S.uid    = userId
  S.conv   = S.convs.find(c => String(c.user_id) === String(userId)) || {}
  S._atBottom = true
  lsSet(LS_ACTIVE, userId)

  // Mettre à jour le header immédiatement (nom déjà connu)
  _updateHeader(S.conv, null)

  // Sur mobile : afficher le panneau messages
  if (window.innerWidth <= 700) {
    $('conv-col')?.classList.add('hidden-mobile')
    $('messages-col')?.classList.add('visible')
  }

  // Loader discret
  _showLoader()

  try {
    // Charger messages + profil en parallèle
    const [msgsData, profile] = await Promise.all([
      API.apiGetMessages(userId, MSG_LIMIT),
      API.apiGetProfile(userId).catch(() => null),
    ])

    S.messages  = msgsData.messages || msgsData || []
    S.oldestId  = S.messages.length ? S.messages[0].id : null
    S.newestId  = S.messages.length ? S.messages[S.messages.length - 1].id : null
    S.profile   = profile

    // ── Marquer comme lu : on retient le dernier msg_id ─────────────
    if (S.newestId) {
      setLastRead(userId, S.newestId)
    }

    // Mettre à jour le unread dans State local → badge = 0
    const convIdx = S.convs.findIndex(c => String(c.user_id) === String(userId))
    if (convIdx !== -1) S.convs[convIdx].unread_count = 0

    // Mettre à jour le badge sur l'item de la liste
    _clearConvBadge(userId)
    _updateStats()

    // Mettre à jour le header avec le profil complet
    if (profile) _updateHeader(S.conv, profile)

    // Rendre le profil
    _renderProfile(profile)

    // Rendre le feed
    _renderFeed()

    // Scroll : position sauvegardée ou bas par défaut
    const saved = lsGet(LS_SCROLL + userId)
    _scrollFeed(saved ? parseInt(saved) : 'bottom')

    // Zone de saisie
    _showCompose()

    // Restaurer le brouillon
    const draft = lsGet(LS_DRAFT + userId)
    const inp   = $('compose-input')
    if (inp) {
      inp.value = draft || ''
      autoResize(inp)
      inp.placeholder = `Envoyer un message à ${(S.conv.name || '').split(' ')[0] || ''}…`
    }

    // API mark read (serveur)
    API.apiMarkRead(userId).catch(() => {})

    // Démarrer le polling
    S._pollMsg = setInterval(() => _pollMessages(userId), POLL_MSG_MS)

  } catch (e) {
    $('messages-feed').innerHTML = _empty('Erreur de chargement')
    Render.toast('Erreur chargement conversation', 'error')
  }
}

// Supprime le badge unread sur l'item de la liste
function _clearConvBadge(userId) {
  const item = $(`conv-${userId}`)
  if (!item) return
  // Retirer tous les badges numériques
  item.querySelectorAll('[style*="border-radius:50%"]').forEach(b => b.remove())
  item.querySelectorAll('.unread-badge').forEach(b => b.remove())
}

// ══════════════════════════════════════════════════════════════════════
// POLLING NOUVEAUX MESSAGES
// ══════════════════════════════════════════════════════════════════════
async function _pollMessages(userId) {
  if (!S.uid || String(S.uid) !== String(userId)) return
  if (!S.newestId) return

  try {
    const data    = await API.apiGetMessages(userId, 20, null, S.newestId)
    const newMsgs = (data.messages || data || []).filter(m => m.id > S.newestId)

    if (!newMsgs.length) return

    // Mettre à jour newestId + mémoriser comme lu
    S.newestId = Math.max(...newMsgs.map(m => m.id))
    setLastRead(userId, S.newestId)

    // Injecter dans le feed sans re-render
    _appendMessages(newMsgs)

    // Remonter la conv en tête
    if (S.convs.length) {
      const last = newMsgs[newMsgs.length - 1]
      const idx  = S.convs.findIndex(c => String(c.user_id) === String(userId))
      if (idx !== -1) {
        S.convs[idx].last_message    = last.message_text || ''
        S.convs[idx].last_activity   = last.created_at
        S.convs[idx].last_message_id = last.id
        S.convs[idx].unread_count    = 0 // on est en train de lire
      }
      _bumpConv(userId)
    }

    // Auto-scroll si on était en bas
    if (S._atBottom) _scrollFeed('bottom')

    API.apiMarkRead(userId).catch(() => {})

  } catch { /* silencieux */ }
}

function _appendMessages(msgs) {
  const feed = $('messages-feed')
  if (!feed) return
  msgs.forEach(msg => {
    const div = document.createElement('div')
    div.innerHTML = Render.renderMessage(msg, S.conv)
    while (div.firstChild) feed.appendChild(div.firstChild)
  })
  S.messages.push(...msgs)
}

// ══════════════════════════════════════════════════════════════════════
// CHARGEMENT MESSAGES PLUS ANCIENS (scroll vers le haut)
// ══════════════════════════════════════════════════════════════════════
async function _loadOlder() {
  if (S._loading || !S.uid || !S.oldestId) return
  S._loading = true

  const feed     = $('messages-feed')
  const prevH    = feed?.scrollHeight || 0

  try {
    const data   = await API.apiGetMessages(S.uid, 30, S.oldestId)
    const older  = data.messages || data || []
    if (!older.length) { S._loading = false; return }

    S.messages  = [...older, ...S.messages]
    S.oldestId  = older[0].id

    // Injecter en haut sans tout re-render
    const frag = document.createDocumentFragment()
    older.reverse().forEach(msg => {
      const div = document.createElement('div')
      div.innerHTML = Render.renderMessage(msg, S.conv)
      while (div.firstChild) frag.insertBefore(div.firstChild, frag.firstChild)
    })
    // Re-reverser pour ordre chrono
    const nodes = [...frag.childNodes].reverse()
    nodes.forEach(n => feed.insertBefore(n, feed.firstChild))

    // Maintenir la position de scroll
    if (feed) feed.scrollTop = feed.scrollHeight - prevH
  } catch (e) {
    Render.toast('Erreur chargement anciens messages', 'error')
  } finally {
    S._loading = false
  }
}

// ══════════════════════════════════════════════════════════════════════
// ENVOI MESSAGE
// ══════════════════════════════════════════════════════════════════════
async function sendMessage() {
  if (S.sending || !S.uid) return
  if (S.uploadFile && !S.uploadResult) {
    Render.toast('Fichier en cours d\'upload, patientez…', 'info')
    return
  }

  const inp  = $('compose-input')
  const text = inp?.value.trim() || ''
  if (!text && !S.uploadResult) return

  S.sending = true
  const btn = $('send-btn')
  if (btn) btn.disabled = true

  // Payload
  const payload = {
    message_type: 'text',
    message_text: text,
  }
  if (S.uploadResult) {
    payload.message_type = S.uploadResult.type
    payload.media_url    = S.uploadResult.url
  }
  if (S.replyId) payload.replied_to_id = S.replyId

  // Message optimiste
  const tempId  = `opt_${Date.now()}`
  const tempMsg = {
    id:              tempId,
    direction:       'outbound',
    answered_by:     'admin',
    message_text:    text,
    message_type:    payload.message_type,
    media_url:       S.uploadResult?.url || null,
    status:          'sending',
    created_at:      new Date().toISOString(),
    replied_to_id:   S.replyId   || null,
    replied_to_text: S.replyText || null,
  }

  const feed = $('messages-feed')
  if (feed) {
    const div = document.createElement('div')
    div.innerHTML = Render.renderMessage(tempMsg, S.conv)
    const node = div.firstElementChild
    if (node) { node.dataset.tempId = tempId; feed.appendChild(node) }
    _scrollFeed('bottom')
  }

  // Reset UI
  if (inp) { inp.value = ''; inp.style.height = 'auto' }
  const ctr = $('compose-count'); if (ctr) ctr.textContent = '0 / 4096'
  lsDel(LS_DRAFT + S.uid)
  clearReply()
  clearUpload()

  try {
    const sent = await API.apiSendMessage(S.uid, payload)

    // Remplacer optimiste par vrai message
    const node = feed?.querySelector(`[data-temp-id="${tempId}"]`)
    if (node) {
      const div = document.createElement('div')
      div.innerHTML = Render.renderMessage(sent, S.conv)
      node.replaceWith(div.firstElementChild)
    }

    // Mémoriser comme lu
    if (sent.id) { S.newestId = sent.id; setLastRead(S.uid, sent.id) }

    // Remonter la conv
    const idx = S.convs.findIndex(c => String(c.user_id) === String(S.uid))
    if (idx !== -1) {
      S.convs[idx].last_message    = text
      S.convs[idx].last_activity   = new Date().toISOString()
      S.convs[idx].last_message_id = sent.id
      S.convs[idx].unread_count    = 0
    }
    _bumpConv(S.uid)

  } catch (e) {
    const node = feed?.querySelector(`[data-temp-id="${tempId}"]`)
    if (node) {
      const err = document.createElement('div')
      err.style.cssText = 'font-size:11px;color:#f87171;text-align:right;padding:2px 4px;'
      err.textContent   = '⚠ Échec — ' + (e.message || 'Erreur')
      node.appendChild(err)
    }
    Render.toast('Message non envoyé', 'error')
  } finally {
    S.sending = false
    if (btn) btn.disabled = false
  }
}

// ══════════════════════════════════════════════════════════════════════
// UPLOAD
// ══════════════════════════════════════════════════════════════════════
function triggerUpload() {
  if (S.uploadFile) { Render.toast('Supprimez d\'abord le fichier en attente', 'info'); return }
  $('file-input')?.click()
}

function _previewUpload(file) {
  if (!ACCEPTED_FILES.includes(file.type)) {
    Render.toast(`Type non supporté (${file.type})`, 'error'); return
  }
  S.uploadFile   = file
  S.uploadResult = null
  const prev = $('upload-preview')
  if (prev) { prev.innerHTML = Render.renderUploadPreview(file); prev.style.display = 'block' }
  _uploadNow(file)
}

async function _uploadNow(file) {
  const prev = $('upload-preview')
  if (prev) {
    const l = document.createElement('div')
    l.id = 'upload-status'
    l.style.cssText = 'font-size:10px;color:#a1a1aa;margin-top:4px;'
    l.textContent = 'Envoi en cours…'
    prev.appendChild(l)
  }
  try {
    const res = await API.apiUploadMedia(S.uid, file)
    S.uploadResult = res
    const st = $('upload-status')
    if (st) { st.textContent = '✓ Prêt'; st.style.color = '#34d399' }
  } catch {
    Render.toast('Erreur upload', 'error')
    clearUpload()
  }
}

function clearUpload() {
  S.uploadFile = S.uploadResult = null
  const prev = $('upload-preview')
  if (prev) { prev.innerHTML = ''; prev.style.display = 'none' }
  const fi = $('file-input'); if (fi) fi.value = ''
}

// ══════════════════════════════════════════════════════════════════════
// REPLY
// ══════════════════════════════════════════════════════════════════════
function setReply(msgId, text) {
  S.replyId   = msgId
  S.replyText = text
  const rp = $('reply-preview')
  if (rp) { $('reply-text').textContent = text; rp.style.display = 'flex' }
  $('compose-input')?.focus()
}

function clearReply() {
  S.replyId = S.replyText = null
  const rp = $('reply-preview')
  if (rp) rp.style.display = 'none'
}

// ══════════════════════════════════════════════════════════════════════
// TOGGLE IA
// ══════════════════════════════════════════════════════════════════════
async function toggleIA(btn) {
  if (!S.uid) return
  btn.classList.toggle('on')
  const on = btn.classList.contains('on')
  const banner = $('ia-banner')
  if (banner) banner.style.display = on ? 'flex' : 'none'
  try {
    await API.apiSetIA(S.uid, on)
    if (S.conv) S.conv.ia_enabled = on ? 1 : 0
  } catch {
    btn.classList.toggle('on')
    if (banner) banner.style.display = on ? 'none' : 'flex'
    Render.toast('Erreur IA', 'error')
  }
}

// ══════════════════════════════════════════════════════════════════════
// TABS & FILTRES
// ══════════════════════════════════════════════════════════════════════
async function switchConvTab(el, tab) {
  el.closest('.tabs-wrap').querySelectorAll('.tab').forEach(t => t.classList.remove('active'))
  el.classList.add('active')
  S.tab = tab
  lsSet(LS_TAB, tab)
  await loadConversations()
}

function filterConvs(q) {
  S.search = q
  clearTimeout(S._searchTimer)
  S._searchTimer = setTimeout(() => loadConversations(), 300)
}

function _activateTab(tab) {
  document.querySelectorAll('.tab').forEach(t => {
    const matches = t.getAttribute('onclick')?.includes(`'${tab}'`)
    t.classList.toggle('active', !!matches)
  })
}

// ══════════════════════════════════════════════════════════════════════
// NAVIGATION MOBILE
// ══════════════════════════════════════════════════════════════════════
function backToList() {
  $('conv-col')?.classList.remove('hidden-mobile')
  $('messages-col')?.classList.remove('visible', 'visible-mobile')
  closeProfilePanel()
  clearInterval(S._pollMsg)
}

// ══════════════════════════════════════════════════════════════════════
// PANNEAU PROFIL
// ══════════════════════════════════════════════════════════════════════
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

// ══════════════════════════════════════════════════════════════════════
// ABONNEMENT
// ══════════════════════════════════════════════════════════════════════
function openSubscriptionModal() {
  const nameEl = $('sub-modal-name')
  if (nameEl) nameEl.textContent = S.conv?.name || '—'
  window.openModal?.('modal-subscription')
}

async function createSubscription() {
  const plan = document.querySelector('#modal-subscription select')?.value
  const note = document.querySelector('#modal-subscription textarea')?.value || ''
  if (!plan || !S.uid) return
  try {
    await API.apiCreateSubscription(S.uid, plan, note)
    Render.toast('Abonnement créé ✓', 'success')
    window.closeModal?.('modal-subscription')
    const profile = await API.apiGetProfile(S.uid)
    S.profile = profile
    _renderProfile(profile)
  } catch (e) {
    Render.toast('Erreur : ' + e.message, 'error')
  }
}

// ══════════════════════════════════════════════════════════════════════
// TÉMOIGNAGE / ADMIN FLAG
// ══════════════════════════════════════════════════════════════════════
async function markTestimonial(msgId, value) {
  try {
    await API.apiMarkTestimonial(msgId, value)
    const msg = S.messages.find(m => m.id === msgId)
    if (msg) { msg.is_testimonial = value; _renderFeed() }
    Render.toast(value ? '⭐ Marqué' : 'Retiré', 'success')
  } catch { Render.toast('Erreur', 'error') }
}

async function markRequiresAdmin(msgId, value) {
  try {
    await API.apiMarkRequiresAdmin(msgId, value)
    const msg = S.messages.find(m => m.id === msgId)
    if (msg) { msg.requires_admin = value; _renderFeed() }
    Render.toast(value ? '⚡ Marqué pour admin' : 'Retiré', 'success')
  } catch { Render.toast('Erreur', 'error') }
}

// ══════════════════════════════════════════════════════════════════════
// EXPORT
// ══════════════════════════════════════════════════════════════════════
function exportConv(fmt = 'json') {
  if (!S.uid) return
  window.open(API.apiExportConversation(S.uid, fmt), '_blank')
}

// ══════════════════════════════════════════════════════════════════════
// UI HELPERS
// ══════════════════════════════════════════════════════════════════════
function _updateHeader(conv, profile) {
  const name   = profile?.name || conv?.name || 'Conversation'
  const handle = profile ? `${profile.name} · ID ${conv.user_id}` : (conv?.name || '')

  const av = $('chat-av')
  if (av) {
    av.className   = `av av-md ${Render.avatarClass(name)}`
    av.textContent = Render.avatarText(name)
  }
  const n = $('chat-name');    if (n) n.textContent = name
  const h = $('chat-handle');  if (h) h.textContent = handle

  const blocked = conv?.is_blocked
  const ia      = !blocked && conv?.ia_enabled

  const bb = $('blocked-banner'); if (bb) bb.style.display = blocked ? 'flex' : 'none'
  const ib = $('ia-banner');      if (ib) ib.style.display = ia     ? 'flex' : 'none'

  const tog = $('ia-toggle')
  if (tog) tog.className = 'toggle' + (ia ? ' on' : '')
}

function _showCompose() {
  const area = $('compose-area')
  if (!area) return
  area.style.display = S.conv?.is_blocked ? 'none' : 'block'
}

function _renderFeed() {
  const feed = $('messages-feed')
  if (!feed) return

  const parts  = []
  let lastDate = null

  S.messages.forEach(msg => {
    const d = msg.created_at ? msg.created_at.slice(0, 10) : null
    if (d && d !== lastDate) { parts.push(Render.renderDateSep(d)); lastDate = d }
    parts.push(Render.renderMessage(msg, S.conv))
  })

  feed.innerHTML = parts.join('') + '<div style="height:8px"></div>'

  // Scroll listener pour charger les anciens
  feed.onscroll = () => {
    S._atBottom = feed.scrollHeight - feed.scrollTop - feed.clientHeight < 80
    if (feed.scrollTop < 60) _loadOlder()
    if (S.uid) lsSet(LS_SCROLL + S.uid, feed.scrollTop)
  }
}

function _renderProfile(profile) {
  const col = $('profile-col')
  if (!col) return
  col.innerHTML = profile
    ? `<div class="profile-handle"><span></span></div>${Render.renderProfile(profile)}`
    : '<div class="profile-empty">Sélectionnez une conversation</div>'
}

function _showLoader() {
  const feed = $('messages-feed')
  if (feed) feed.innerHTML = '<div class="dots"><div class="dot"></div><div class="dot"></div><div class="dot"></div></div>'
}

function _scrollFeed(target) {
  const feed = $('messages-feed')
  if (!feed) return
  requestAnimationFrame(() => {
    feed.scrollTop = target === 'bottom' ? feed.scrollHeight : (target || 0)
  })
}

function _empty(msg) {
  return `<div style="padding:32px;text-align:center;color:var(--txt-5);font-size:12px;">${msg}</div>`
}

function autoResize(el) {
  el.style.height = 'auto'
  el.style.height = Math.min(el.scrollHeight, 120) + 'px'
}

function handleKey(e) {
  if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage() }
}

// ══════════════════════════════════════════════════════════════════════
// API PUBLIQUE window.App
// ══════════════════════════════════════════════════════════════════════
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

// ══════════════════════════════════════════════════════════════════════
// BOOT
// ══════════════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => { App.init() })