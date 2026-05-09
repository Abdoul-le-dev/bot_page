/* ═══════════════════════════════════════════════════════════════
   message_config_api.js  —  Appels API uniquement
   Dépend de messages.js pour : getUploadedMediaUrl(), updateSummary()
   ═══════════════════════════════════════════════════════════════ */

const API_URL = window.API_URL || 'http://54.226.165.244:8000'

// ── Chargement des catégories ─────────────────────────────────────
async function loadCategories() {
  try {
    const res  = await fetch(`${API_URL}/categories`)
    const data = await res.json()

    const select = document.querySelector('#dest-block-category select')
    select.innerHTML = '<option value="">Sélectionner une catégorie...</option>'

    data.forEach(cat => {
      const opt       = document.createElement('option')
      opt.value       = cat.name
      opt.textContent = `${cat.name} (${cat.total})`
      select.appendChild(opt)
    })

  } catch (err) {
    console.error('Erreur chargement catégories :', err)
  }
}

// ── Construction du payload ───────────────────────────────────────
function buildPayload() {
  const destType = document.querySelector('[id^="dest-"].format-btn.active')?.id

  let category = null
  let user_ids = null

  if (destType === 'dest-category') {
    category = document.querySelector('#dest-block-category select').value || null
  } else if (destType === 'dest-ids') {
    const raw = document.querySelector('#dest-block-ids textarea').value
    user_ids  = raw.split(',').map(s => parseInt(s.trim())).filter(n => !isNaN(n))
  } else if (destType === 'dest-all') {
    category = 'all'
  }

  const excludeRaw       = document.querySelector('#dest-block-category input[type="text"]')?.value || ''
  const exclude_user_ids = excludeRaw.split(',').map(s => parseInt(s.trim())).filter(n => !isNaN(n))

  const format = document.querySelector('[id^="fmt-"].active')?.id
                         ?.replace('fmt-', '')
                         ?.replace('imagetext', 'image+text')
                         ?.replace('videotext', 'video+text') || 'text'

  const message = document.getElementById('msg-textarea')?.value || ''

  // ── Résolution media_url ─────────────────────────────────────────
  // Priorité 1 : fichier uploadé via la zone (URL locale /media/uuid.ext)
  // Priorité 2 : file_id Telegram saisi manuellement dans l'input texte
  const uploadedUrl  = typeof getUploadedMediaUrl === 'function' ? getUploadedMediaUrl() : null
  const manualFileId = document.querySelector('#media-upload input[type="text"]')?.value?.trim() || null
  const media_url    = uploadedUrl || manualFileId || null

  const variables = {}
  document.querySelectorAll('#custom-vars > div').forEach(row => {
    const inputs = row.querySelectorAll('input')
    const key    = inputs[0]?.value?.trim()
    const val    = inputs[1]?.value?.trim()
    if (key && val) variables[key] = val
  })

  const filters    = {}
  const dateInputs = document.querySelectorAll('#filters-panel input[type="date"]')
  if (dateInputs[0]?.value) filters.created_after  = dateInputs[0].value
  if (dateInputs[1]?.value) filters.created_before = dateInputs[1].value

  const delay        = parseFloat(document.querySelector('.options-grid input[type="number"]')?.value) || 0.1
  const tagInputs    = document.querySelectorAll('.options-grid input[type="text"]')
  const tag          = tagInputs[0]?.value?.trim() || ''
  const retry        = document.getElementById('toggle-retry')?.classList.contains('on') ?? true
  const callback_url = tagInputs[1]?.value?.trim() || null
  const scheduled_at = document.getElementById('btn-schedule')?.dataset.scheduledAt || null

  return {
    message,
    format,
    media_url,
    category,
    user_ids,
    scheduled_at,
    delay,
    retry,
    exclude_user_ids,
    variables,
    filters,
    tag,
    callback_url,
  }
}

// ── Validation ────────────────────────────────────────────────────
function validatePayload(payload) {
  if (!payload.category && (!payload.user_ids || payload.user_ids.length === 0))
    return 'Sélectionne des destinataires avant d\'envoyer.'

  if (payload.format === 'text' && !payload.message.trim())
    return 'Le message ne peut pas être vide.'

  if (['image', 'video', 'image+text', 'video+text'].includes(payload.format)) {
    if (!payload.media_url) {
      return 'Ajoute un fichier (glisse-le dans la zone) ou colle un file_id Telegram.'
    }

    // Si c'est une URL locale, l'upload doit être terminé
    // (si getUploadedMediaUrl() retourne une valeur, c'est que le serveur a bien répondu)
    const uploadedUrl = typeof getUploadedMediaUrl === 'function' ? getUploadedMediaUrl() : null
    if (uploadedUrl && uploadedUrl !== payload.media_url) {
      return 'L\'upload du fichier est encore en cours. Attends quelques secondes.'
    }
  }

  if (payload.format === 'text' && payload.message.length > 4096)
    return 'Le message dépasse 4096 caractères.'

  return null
}

// ── Ouvre le modal confirm avec les vraies données ────────────────
function openConfirmModal() {
  const payload = buildPayload()

  const error = validatePayload(payload)
  if (error) { _showToast(error, 'error'); return }

  // Rempli bloc Destinataires
  const destEl = document.getElementById('confirm-dest')
  if (destEl) {
    if (payload.category === 'all') {
      destEl.textContent = 'Tous les membres'
    } else if (payload.category) {
      const select = document.querySelector('#dest-block-category select')
      destEl.textContent = select?.options[select.selectedIndex]?.textContent || payload.category
    } else if (payload.user_ids?.length) {
      destEl.textContent = `${payload.user_ids.length} IDs manuels`
    }
  }

  // Rempli bloc Paramètres
  const metaEl = document.getElementById('confirm-meta')
  if (metaEl) {
    const mediaInfo = payload.media_url
      ? ` · média: ${payload.media_url.split('/').pop()}`
      : ''
    metaEl.textContent =
      `format: ${payload.format}${mediaInfo} · delay: ${payload.delay}s · retry: ${payload.retry} · tag: ${payload.tag || '—'}`
  }

  openModal('modal-confirm')
}

// ── Envoi du broadcast ────────────────────────────────────────────
async function sendBroadcast() {
  const payload = buildPayload()
  const error   = validatePayload(payload)
  if (error) { _showToast(error, 'error'); return }

  const btnConfirm = document.getElementById('btn-confirm')
  const btnSend    = document.getElementById('btn-send')

  btnConfirm.disabled    = true
  btnConfirm.textContent = 'Envoi en cours...'
  if (btnSend) btnSend.disabled = true

  try {
    // Envoi JSON — le media_url est déjà uploadé sur le serveur
    // broadcast_engine.py lira le fichier local via ce chemin
    const res = await fetch(`${API_URL}/broadcast`, {
      method:  'POST',
      headers: { 'Content-Type': 'application/json' },
      body:    JSON.stringify(payload),
    })

    const report = await res.json()
    if (!res.ok) throw new Error(report.detail || 'Erreur inconnue')

    // ── Succès ────────────────────────────────────────────────────
    btnConfirm.textContent      = `✓ ${report.sent}/${report.total} envoyés`
    btnConfirm.style.background = '#34d399'
    btnConfirm.style.color      = '#052e16'

    if (btnSend) {
      btnSend.disabled = false
      const span = btnSend.querySelector('.topbar-btn-label')
      if (span) span.textContent = `✓ ${report.sent} envoyés`
      btnSend.style.background = '#34d399'
      btnSend.style.color      = '#052e16'
    }

    _showToast(`Diffusion lancée · ${report.sent}/${report.total} messages`, 'success')

    // Remet le bouton à l'état normal après 3s
    setTimeout(() => {
      btnConfirm.disabled    = false
      btnConfirm.textContent = 'Lancer l\'envoi →'
      btnConfirm.style.background = ''
      btnConfirm.style.color      = ''
      closeModal('modal-confirm')
    }, 2000)

  } catch (err) {
    _showToast(`Erreur : ${err.message}`, 'error')
    btnConfirm.disabled    = false
    btnConfirm.textContent = 'Lancer l\'envoi →'
    if (btnSend) btnSend.disabled = false
  }
}

// ── Résumé sidebar ────────────────────────────────────────────────
function updateSummary() {
  const destType = document.querySelector('[id^="dest-"].format-btn.active')?.id
  const format   = document.querySelector('[id^="fmt-"].active')?.id
                           ?.replace('fmt-', '')
                           ?.replace('imagetext', 'image+text')
                           ?.replace('videotext', 'video+text') || 'text'

  const destEl = document.getElementById('summary-dest')
  if (destEl) {
    if (destType === 'dest-all') {
      destEl.textContent = 'Tous'
    } else if (destType === 'dest-ids') {
      const raw   = document.querySelector('#dest-block-ids textarea')?.value || ''
      const count = raw.split(',').filter(s => s.trim()).length
      destEl.textContent = `${count} IDs manuels`
    } else {
      const select = document.querySelector('#dest-block-category select')
      destEl.textContent = select?.options[select.selectedIndex]?.textContent || '—'
    }
  }

  const fmtEl = document.getElementById('summary-format')
  if (fmtEl) fmtEl.textContent = format

  updateDuration()
}

// ── Durée estimée ─────────────────────────────────────────────────
function updateDuration() {
  const delay    = parseFloat(document.querySelector('.options-grid input[type="number"]')?.value) || 0.1
  const destType = document.querySelector('[id^="dest-"].format-btn.active')?.id

  let total = 0
  if (destType === 'dest-category') {
    const select = document.querySelector('#dest-block-category select')
    const match  = select?.options[select.selectedIndex]?.textContent?.match(/\((\d+)\)/)
    total = match ? parseInt(match[1]) : 0
  } else if (destType === 'dest-ids') {
    const raw = document.querySelector('#dest-block-ids textarea')?.value || ''
    total = raw.split(',').filter(s => s.trim()).length
  }

  const seconds = Math.round(total * delay)
  const mins    = Math.floor(seconds / 60)
  const secs    = seconds % 60

  const durEl   = document.getElementById('est-duration')
  const infoEl  = document.querySelector('#est-duration + p')
  const countEl = document.getElementById('summary-count')

  if (durEl)   durEl.textContent   = total > 0 ? `~${mins}m ${secs}s` : '—'
  if (infoEl)  infoEl.textContent  = `avec delay ${delay}s · ${total} destinataires`
  if (countEl) countEl.textContent = `${total} msgs`
}

// ── Historique ────────────────────────────────────────────────────
async function loadHistory() {
  try {
    const res  = await fetch(`${API_URL}/broadcast/history`)
    const data = await res.json()
    renderHistory(data)
    renderHistoryStats(data)
    renderHistoryMobile(data)
  } catch (err) {
    console.error('Erreur chargement historique :', err)
  }
}

function renderHistoryStats(data) {
  const now    = new Date()
  const thisMo = data.filter(c => {
    const d = new Date(c.started_at)
    return d.getMonth() === now.getMonth() && d.getFullYear() === now.getFullYear()
  })

  const totalSent   = data.reduce((a, c) => a + c.sent,   0)
  const totalErrors = data.reduce((a, c) => a + c.errors, 0)
  const avgTaux     = data.length > 0
    ? Math.round(data.reduce((a, c) => a + (c.total > 0 ? c.sent / c.total : 0), 0) / data.length * 100)
    : 0

  const s = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v }
  s('stat-campagnes', thisMo.length)
  s('stat-messages',  totalSent.toLocaleString('fr-FR'))
  s('stat-taux',      `${avgTaux}%`)
  s('stat-erreurs',   totalErrors)
}

function renderHistory(data) {
  const table = document.querySelector('.camp-table-desktop')
  if (!table) return

  const header = table.querySelector('.camp-table-header')
  table.innerHTML = ''
  if (header) table.appendChild(header)

  if (!data.length) {
    const p = document.createElement('p')
    p.style.cssText  = 'color:#52525b;font-size:12px;padding:16px;'
    p.textContent    = 'Aucune campagne pour l\'instant.'
    table.appendChild(p)
    return
  }

  data.forEach(camp => {
    const taux    = camp.total > 0 ? Math.round((camp.sent / camp.total) * 100) : 0
    const couleur = taux >= 70 ? '#34d399' : taux >= 40 ? '#fbbf24' : '#f87171'

    const row         = document.createElement('div')
    row.className     = 'camp-row'
    row.style.cursor  = 'pointer'
    row.onclick       = () => openDetailModal(camp)
    row.innerHTML     = `
      <div class="camp-col-name">
        <p class="text-xs font-medium text-zinc-200">${camp.tag || 'Sans tag'}</p>
        <p class="text-[10px] mt-0.5" style="color:#52525b;">
          ${camp.started_at} · ${camp.category || 'IDs manuels'} · ${camp.format}
        </p>
      </div>
      <span class="text-xs tabular-nums text-zinc-400 camp-col">${camp.sent}</span>
      <div class="camp-col">
        <p class="text-xs tabular-nums" style="color:${couleur};">${taux}%</p>
        <div class="stat-bar-track mt-1">
          <div class="stat-bar-fill" style="width:${taux}%;background:${couleur};"></div>
        </div>
      </div>
      <span class="text-xs tabular-nums camp-col" style="color:#52525b;">${camp.total}</span>
      <span class="text-xs tabular-nums camp-col" style="color:#f87171;">${camp.errors}</span>
      <span class="camp-col"><span class="badge badge-green">Terminé</span></span>
    `
    table.appendChild(row)
  })
}

function renderHistoryMobile(data) {
  const container = document.querySelector('.camp-cards-mobile')
  if (!container) return

  container.innerHTML = ''

  if (!data.length) {
    container.innerHTML = '<p style="color:#52525b;font-size:12px;padding:16px;">Aucune campagne pour l\'instant.</p>'
    return
  }

  data.forEach(camp => {
    const taux    = camp.total > 0 ? Math.round((camp.sent / camp.total) * 100) : 0
    const couleur = taux >= 70 ? '#34d399' : taux >= 40 ? '#fbbf24' : '#f87171'

    const card       = document.createElement('div')
    card.className   = 'card msg-card cursor-pointer'
    card.onclick     = () => openDetailModal(camp)
    card.innerHTML   = `
      <div class="flex items-start justify-between mb-2">
        <div>
          <p class="text-xs font-medium text-zinc-200">${camp.tag || 'Sans tag'}</p>
          <p class="text-[10px] mt-0.5" style="color:#52525b;">${camp.started_at} · ${camp.category || 'IDs manuels'}</p>
        </div>
        <span class="badge badge-green">Terminé</span>
      </div>
      <div class="grid grid-cols-3 gap-2 mt-3">
        <div style="text-align:center;">
          <p class="text-sm font-light text-white">${camp.sent}</p>
          <p class="text-[10px]" style="color:#52525b;">Envoyés</p>
        </div>
        <div style="text-align:center;">
          <p class="text-sm font-light" style="color:${couleur};">${taux}%</p>
          <p class="text-[10px]" style="color:#52525b;">Taux</p>
        </div>
        <div style="text-align:center;">
          <p class="text-sm font-light" style="color:#f87171;">${camp.errors}</p>
          <p class="text-[10px]" style="color:#52525b;">Erreurs</p>
        </div>
      </div>
    `
    container.appendChild(card)
  })
}

// ── Modal détail ──────────────────────────────────────────────────
function openDetailModal(camp) {
  const taux = camp.total > 0 ? Math.round((camp.sent / camp.total) * 100) : 0

  const titleEl = document.getElementById('modal-detail-title')
  const datesEl = document.getElementById('modal-detail-dates')
  if (titleEl) titleEl.textContent = camp.tag || 'Sans tag'
  if (datesEl) datesEl.textContent = `${camp.started_at} → ${camp.finished_at}`

  const s = (id, v) => { const el = document.getElementById(id); if (el) el.textContent = v }
  s('detail-sent',   camp.sent)
  s('detail-taux',   `${taux}%`)
  s('detail-total',  camp.total)
  s('detail-errors', camp.errors)

  const preEl = document.getElementById('detail-payload')
  if (preEl) preEl.textContent = JSON.stringify({
    category: camp.category,
    format:   camp.format,
    tag:      camp.tag,
    total:    camp.total,
    sent:     camp.sent,
    errors:   camp.errors,
  }, null, 2)

  openModal('modal-detail')
}

// ── Planification ─────────────────────────────────────────────────
function confirmSchedule() {
  const input = document.querySelector('#modal-schedule input[type="datetime-local"]')
  if (!input?.value) { _showToast('Sélectionne une date et heure.', 'error'); return }

  const formatted = input.value.replace('T', ' ') + ':00'
  document.getElementById('btn-schedule').dataset.scheduledAt = formatted

  const summaryEl = document.getElementById('summary-schedule')
  if (summaryEl) summaryEl.textContent = formatted

  const btnSend = document.getElementById('btn-send')
  if (btnSend) {
    const span = btnSend.querySelector('.topbar-btn-label')
    if (span) span.textContent   = 'Envoi planifié ✓'
    btnSend.style.background     = '#34d399'
    btnSend.style.color          = '#052e16'
  }

  _showToast(`Envoi planifié le ${formatted}`, 'success')
  closeModal('modal-schedule')
}

// ── Vue Composer / Historique ─────────────────────────────────────
function switchView(view, el) {
  document.getElementById('view-compose').style.display = view === 'compose' ? 'flex' : 'none'
  document.getElementById('view-history').style.display = view === 'history' ? 'flex' : 'none'
  el.closest('div').querySelectorAll('.tab').forEach(t => t.classList.remove('active'))
  el.classList.add('active')

  if (view === 'history') {
    document.getElementById('view-history').style.flexDirection = 'column'
    loadHistory()
  }
}

// ── Toast notifications ───────────────────────────────────────────
function _showToast(msg, type = 'info') {
  let container = document.getElementById('_toast-container')
  if (!container) {
    container = document.createElement('div')
    container.id = '_toast-container'
    container.style.cssText = `
      position:fixed;bottom:24px;right:24px;z-index:999;
      display:flex;flex-direction:column;gap:8px;pointer-events:none;
    `
    document.body.appendChild(container)
  }

  const colors = {
    success: { bg: 'rgba(52,211,153,.12)', border: 'rgba(52,211,153,.25)', text: '#34d399' },
    error:   { bg: 'rgba(248,113,113,.12)', border: 'rgba(248,113,113,.25)', text: '#f87171' },
    info:    { bg: 'rgba(56,189,248,.12)',  border: 'rgba(56,189,248,.25)',  text: '#38bdf8' },
  }
  const c = colors[type] || colors.info

  const toast = document.createElement('div')
  toast.style.cssText = `
    padding:10px 16px;border-radius:9px;font-size:12px;
    background:${c.bg};border:1px solid ${c.border};color:${c.text};
    font-family:'Geist',sans-serif;pointer-events:auto;
    animation:_toastIn .2s ease;max-width:320px;
    box-shadow:0 4px 16px rgba(0,0,0,.4);
  `
  toast.textContent = msg

  if (!document.getElementById('_toast-style')) {
    const s = document.createElement('style')
    s.id = '_toast-style'
    s.textContent = `
      @keyframes _toastIn  { from { opacity:0; transform:translateY(8px) } to { opacity:1; transform:none } }
      @keyframes _toastOut { from { opacity:1 } to { opacity:0; transform:translateY(4px) } }
    `
    document.head.appendChild(s)
  }

  container.appendChild(toast)
  setTimeout(() => {
    toast.style.animation = '_toastOut .2s ease forwards'
    setTimeout(() => toast.remove(), 200)
  }, 3500)
}

// ── Initialisation ────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open') })
  })
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape')
      document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('open'))
  })

  loadCategories()
  updateSummary()
})