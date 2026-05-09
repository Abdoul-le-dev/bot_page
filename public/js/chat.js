/* ══════════════════════════════════════════════════════════════════════
   chat-direct.js
   JS spécifique à chat-direct.html.
   Le JS global (openSidebar, closeSidebar, openModal, closeModal,
   hamburger) vient de dashboard.js.
   ══════════════════════════════════════════════════════════════════════ */

// ── Données statiques conversations ──────────────────────────────────
// En prod : remplacer par un fetch('/api/conversations') ou des données
// passées en data-attribute depuis le PHP.
const CONVS = {
  sa: { name:'Sophie Amar',  handle:'@sophie_a · ID 2310',  avClass:'av-sky',     avText:'SA', blocked:false, ia:true,
        badges:'<span class="badge badge-sky" style="font-size:10px;">Prospect</span><span class="badge badge-green" style="font-size:10px;">Engagé</span>' },
  mr: { name:'Marc Renaud',  handle:'@marc_renaud · ID 1042',avClass:'av-green',   avText:'MR', blocked:false, ia:true,
        badges:'<span class="badge badge-green" style="font-size:10px;">Client</span><span class="badge badge-violet" style="font-size:10px;">Premium</span>' },
  tk: { name:'Thomas Klein', handle:'@t_klein · ID 789',     avClass:'av-amber',   avText:'TK', blocked:false, ia:false,
        badges:'<span class="badge badge-amber" style="font-size:10px;">Inactif</span>' },
  lb: { name:'Lucie Bernard',handle:'@lucie_b · ID 567',     avClass:'av-violet',  avText:'LB', blocked:false, ia:false,
        badges:'<span class="badge badge-green" style="font-size:10px;">Client</span><span class="badge badge-teal" style="font-size:10px;">Testi ✓</span>' },
  nm: { name:'Nicolas Morel',handle:'@nicolas_m · ID 3891',  avClass:'av-default', avText:'NM', blocked:false, ia:false,
        badges:'<span class="badge badge-sky" style="font-size:10px;">Prospect</span>' },
  pm: { name:'Pierre Martin',handle:'@pierre_m · ID 4201',   avClass:'av-red',     avText:'PM', blocked:true,  ia:false,
        badges:'<span class="badge badge-red" style="font-size:10px;">Bloqué</span>' },
}

// ── Références DOM ────────────────────────────────────────────────────
const convCol        = document.getElementById('conv-col')
const messagesCol    = document.getElementById('messages-col')
const profileCol     = document.getElementById('profile-col')
const profileOverlay = document.getElementById('profile-overlay')

// ── Utilitaires breakpoints ───────────────────────────────────────────
const isMobile = () => window.innerWidth <= 700
const isTablet = () => window.innerWidth <= 1024

// ════════════════════════════════════════════════════════════════════════
// NAVIGATION MOBILE — stack liste ↔ messages
// ════════════════════════════════════════════════════════════════════════
function showMessages() {
  if (!isMobile()) return
  convCol.classList.add('hidden-mobile')
  messagesCol.classList.add('visible')
}

function backToList() {
  convCol.classList.remove('hidden-mobile')
  messagesCol.classList.remove('visible')
  closeProfilePanel()
}

window.addEventListener('resize', () => {
  if (!isMobile()) {
    convCol.classList.remove('hidden-mobile')
    messagesCol.classList.remove('visible')
  }
  if (!isTablet()) closeProfilePanel()
})

document.getElementById('btn-back-conv').addEventListener('click', backToList)

// ════════════════════════════════════════════════════════════════════════
// PANNEAU PROFIL — drawer (tablet) / bottom sheet (mobile)
// ════════════════════════════════════════════════════════════════════════
function openProfilePanel() {
  profileCol.classList.add('open')
  profileOverlay.classList.add('open')
  document.body.style.overflow = 'hidden'
}

function closeProfilePanel() {
  profileCol.classList.remove('open')
  profileOverlay.classList.remove('open')
  document.body.style.overflow = ''
}

document.getElementById('btn-show-profile').addEventListener('click', openProfilePanel)
profileOverlay.addEventListener('click', closeProfilePanel)

// ════════════════════════════════════════════════════════════════════════
// SÉLECTION CONVERSATION
// ════════════════════════════════════════════════════════════════════════
function selectConv(uid, el) {
  document.querySelectorAll('.conv-item').forEach(c => c.classList.remove('active'))
  el.classList.add('active')

  const d = CONVS[uid]
  if (!d) return

  // Header
  const chatAv = document.getElementById('chat-av')
  chatAv.className = 'av ' + d.avClass
  chatAv.style.cssText = 'width:32px;height:32px;font-size:12px;'
  chatAv.textContent = d.avText
  document.getElementById('chat-name').textContent = d.name
  document.getElementById('chat-handle').textContent = d.handle

  // Profil
  const profAv = document.getElementById('profile-av')
  profAv.className = 'av ' + d.avClass
  profAv.style.cssText = 'width:44px;height:44px;font-size:15px;'
  profAv.textContent = d.avText
  document.getElementById('profile-name').textContent = d.name
  document.getElementById('profile-handle').textContent = d.handle
  document.getElementById('profile-badges').innerHTML = d.badges

  // Compose
  document.getElementById('compose-input').placeholder = 'Envoyer un message à ' + d.name.split(' ')[0] + '…'

  // Bandeaux
  document.getElementById('blocked-banner').style.display = d.blocked ? 'flex' : 'none'
  document.getElementById('ia-banner').style.display = (!d.blocked && d.ia) ? 'flex' : 'none'
  document.getElementById('compose-area').style.display = d.blocked ? 'none' : 'block'

  // Toggle IA
  document.getElementById('ia-toggle').className = 'toggle' + (d.ia ? ' on' : '')

  // Mobile : afficher messages
  showMessages()

  // API: GET /api/messages?user_id={uid}&limit=50
}

// ════════════════════════════════════════════════════════════════════════
// FILTRER / TABS
// ════════════════════════════════════════════════════════════════════════
function filterConvs(q) {
  const query = q.toLowerCase()
  document.querySelectorAll('.conv-item').forEach(item => {
    const name = item.querySelector('p')?.textContent?.toLowerCase() || ''
    item.style.display = name.includes(query) ? 'flex' : 'none'
  })
}

function switchConvTab(el, tab) {
  el.closest('div').querySelectorAll('.tab').forEach(t => t.classList.remove('active'))
  el.classList.add('active')
  // API: GET /api/conversations?filter={tab}
}

// ════════════════════════════════════════════════════════════════════════
// TOGGLE AGENT IA
// ════════════════════════════════════════════════════════════════════════
function toggleIA(btn) {
  btn.classList.toggle('on')
  const isOn = btn.classList.contains('on')
  document.getElementById('ia-banner').style.display = isOn ? 'flex' : 'none'
  // API: PATCH /api/conversations/{uid}/ia  { enabled: isOn }
}

// ════════════════════════════════════════════════════════════════════════
// REPLY
// ════════════════════════════════════════════════════════════════════════
function setReply(text) {
  const rp = document.getElementById('reply-preview')
  rp.style.display = 'flex'
  document.getElementById('reply-text').textContent = text
  document.getElementById('compose-input').focus()
}

function clearReply() {
  document.getElementById('reply-preview').style.display = 'none'
  document.getElementById('reply-text').textContent = '—'
}

// ════════════════════════════════════════════════════════════════════════
// ENVOI MESSAGE
// ════════════════════════════════════════════════════════════════════════
function sendMessage() {
  const input = document.getElementById('compose-input')
  const val   = input.value.trim()
  if (!val) return

  // API: POST /api/messages { user_id, message_text, answered_by:'admin' }
  const feed  = document.getElementById('messages-feed')
  const group = document.createElement('div')
  group.className = 'msg-group fadein'
  group.innerHTML =
    '<div style="display:flex;align-items:flex-end;justify-content:flex-end;gap:8px;">' +
      '<div>' +
        '<div class="bubble bubble-admin">' + escapeHtml(val).replace(/\n/g,'<br>') + '</div>' +
        '<div class="msg-meta" style="justify-content:flex-end;">' +
          nowTime() + ' <span class="status-sent">✓</span>' +
          ' <span style="color:#a1a1aa;">· Admin</span>' +
        '</div>' +
      '</div>' +
    '</div>'

  feed.insertBefore(group, feed.lastElementChild)
  feed.scrollTop = feed.scrollHeight

  input.value = ''
  input.style.height = 'auto'
  const counter = document.getElementById('compose-count')
  if (counter) counter.textContent = '0 / 4096'
  clearReply()
  clearUpload()
}

function escapeHtml(str) {
  return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;')
}

function nowTime() {
  const d = new Date()
  return d.getHours().toString().padStart(2,'0') + ':' + d.getMinutes().toString().padStart(2,'0')
}

// ── Auto-resize textarea ──────────────────────────────────────────────
function autoResize(el) {
  el.style.height = 'auto'
  el.style.height = Math.min(el.scrollHeight, 120) + 'px'
  const c = document.getElementById('compose-count')
  if (c) c.textContent = el.value.length + ' / 4096'
}

function handleKey(e) {
  if (e.key === 'Enter' && !e.shiftKey) { e.preventDefault(); sendMessage() }
}

// ════════════════════════════════════════════════════════════════════════
// UPLOAD
// ════════════════════════════════════════════════════════════════════════
function triggerUpload() { document.getElementById('file-input').click() }

function previewUpload(input) {
  if (!input.files.length) return
  document.getElementById('upload-name').textContent = input.files[0].name
  document.getElementById('upload-preview').style.display = 'flex'
  // API: POST /api/upload → { file_id, media_url }
}

function clearUpload() {
  document.getElementById('upload-preview').style.display = 'none'
  const fi = document.getElementById('file-input')
  if (fi) fi.value = ''
}

// ════════════════════════════════════════════════════════════════════════
// INIT
// ════════════════════════════════════════════════════════════════════════
document.addEventListener('DOMContentLoaded', () => {
  // Scroll fil vers le bas
  const feed = document.getElementById('messages-feed')
  if (feed) feed.scrollTop = feed.scrollHeight

  // Échap ferme le panneau profil
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeProfilePanel()
  })
})