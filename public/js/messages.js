/* ═══════════════════════════════════════════════════════════════
   messages.js  —  UI pure, pas d'appels API ici
   Les appels API sont dans message_config_api.js
   ═══════════════════════════════════════════════════════════════ */

// ── État upload ───────────────────────────────────────────────────
// Stocke l'URL locale retournée par /chat/media/upload
// Réinitialisé à null à chaque changement de format ou suppression
let _uploadedMediaUrl = null

function getUploadedMediaUrl()       { return _uploadedMediaUrl }
function setUploadedMediaUrl(url)    { _uploadedMediaUrl = url  }
function clearUploadedMediaUrl()     { _uploadedMediaUrl = null }

// ── Format ────────────────────────────────────────────────────────
function switchFormat(fmt, el) {
  document.querySelectorAll('[id^="fmt-"]').forEach(b => b.classList.remove('active'))
  el.classList.add('active')

  const hasMedia = fmt !== 'text'
  const hasText  = fmt !== 'image' && fmt !== 'video'

  document.getElementById('media-upload').style.display = hasMedia ? 'block' : 'none'
  document.getElementById('text-block').style.display   = hasText  ? 'block' : 'none'

  const previewMedia       = document.getElementById('preview-media')
  const previewMediaMobile = document.getElementById('preview-media-mobile')
  if (previewMedia)       previewMedia.style.display       = hasMedia ? 'block' : 'none'
  if (previewMediaMobile) previewMediaMobile.style.display = hasMedia ? 'block' : 'none'

  // Réinitialise l'upload si on change de format
  if (!hasMedia) {
    clearUploadedMediaUrl()
    _resetUploadZone()
  }

  updateSummary()
}

// ── Destinataires ─────────────────────────────────────────────────
function switchDest(type, el) {
  document.querySelectorAll('[id^="dest-"].format-btn').forEach(b => b.classList.remove('active'))
  el.classList.add('active')
  document.getElementById('dest-block-category').style.display = type === 'category' ? 'block' : 'none'
  document.getElementById('dest-block-ids').style.display      = type === 'ids'      ? 'block' : 'none'
  document.getElementById('dest-block-all').style.display      = type === 'all'      ? 'block' : 'none'

  updateSummary()
}

// ── Filtres avancés ───────────────────────────────────────────────
function toggleFilters() {
  const p = document.getElementById('filters-panel')
  p.style.display = p.style.display === 'none' ? 'block' : 'none'
  const count = document.getElementById('active-filters').children.length
  const badge = document.getElementById('filter-count')
  badge.style.display = count > 0 ? 'inline-flex' : 'none'
  badge.textContent   = count
}

function removeFilter(btn) {
  btn.closest('.filter-tag').remove()
  const count = document.getElementById('active-filters').children.length
  const badge = document.getElementById('filter-count')
  badge.style.display = count > 0 ? 'inline-flex' : 'none'
  badge.textContent   = count
}

// ── Variables ─────────────────────────────────────────────────────
function insertVar(v) {
  const ta  = document.getElementById('msg-textarea')
  const pos = ta.selectionStart
  ta.value  = ta.value.substring(0, pos) + v + ta.value.substring(pos)
  ta.selectionStart = ta.selectionEnd = pos + v.length
  ta.focus()
  updatePreview()
  updateCount(ta)
}

function addVarRow() {
  const container = document.getElementById('custom-vars')
  const row = document.createElement('div')
  row.className = 'flex items-center gap-2'
  row.innerHTML = `
    <input class="input" type="text" placeholder="+variable" style="width:110px;font-size:12px;font-family:'Geist Mono',monospace;flex-shrink:0;">
    <span style="color:#3f3f46;font-size:12px;">→</span>
    <input class="input" type="text" placeholder="valeur" style="font-size:12px;">
    <button class="btn-icon" style="width:24px;height:24px;" onclick="this.closest('div').remove()">
      <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
      </svg>
    </button>`
  container.appendChild(row)
}

// ── Compteur chars ────────────────────────────────────────────────
function updateCount(ta) {
  document.getElementById('char-count').textContent = `${ta.value.length} / 4096`
}

// ── Preview ───────────────────────────────────────────────────────
function updatePreview() {
  const raw = document.getElementById('msg-textarea').value
    || 'Bonjour +prenom, votre message apparaîtra ici...'

  const rendered = raw
    .replace(/\+prenom/g, '<span style="color:#7dd3fc;">Marc</span>')
    .replace(/\+offre/g,  '<span style="color:#7dd3fc;">50%</span>')
    .replace(/\+lien/g,   '<span style="color:#7dd3fc;">https://...</span>')
    .replace(/\+perf/g,   '<span style="color:#7dd3fc;">+4.2%</span>')
    .replace(/\+date/g,   '<span style="color:#7dd3fc;">19/04/2026</span>')
    .replace(/\+plan/g,   '<span style="color:#7dd3fc;">Premium</span>')
    .replace(/\n/g, '<br>')

  ;[
    document.getElementById('preview-text'),
    document.getElementById('preview-text-mobile'),
    document.getElementById('modal-preview-text'),
  ].forEach(el => { if (el) el.innerHTML = rendered })
}

// ── Toggle switch ─────────────────────────────────────────────────
function toggleSwitch(btn) {
  btn.classList.toggle('on')
}

// ── Modals ────────────────────────────────────────────────────────
function openModal(id)  { document.getElementById(id)?.classList.add('open') }
function closeModal(id) { document.getElementById(id)?.classList.remove('open') }


// ════════════════════════════════════════════════════════════════════════════
// ── UPLOAD FICHIER ────────────────────────────────────────────────────────
// ════════════════════════════════════════════════════════════════════════════

/**
 * Crée (une seule fois) l'input file caché et l'injecte dans le DOM.
 * Retourne toujours le même élément.
 */
function _getFileInput() {
  let input = document.getElementById('_hidden-file-input')
  if (!input) {
    input          = document.createElement('input')
    input.type     = 'file'
    input.id       = '_hidden-file-input'
    input.accept   = 'image/*,video/*'
    input.style.display = 'none'
    document.body.appendChild(input)

    input.addEventListener('change', () => {
      if (input.files && input.files[0]) {
        _handleFileSelected(input.files[0])
      }
      // Reset pour permettre de re-sélectionner le même fichier
      input.value = ''
    })
  }
  return input
}

/**
 * Déclenché au clic sur la zone upload ou le bouton "parcourir".
 */
function triggerUpload() {
  _getFileInput().click()
}

/**
 * Traite le fichier sélectionné :
 * 1. Affiche preview locale immédiate
 * 2. Upload vers /chat/media/upload
 * 3. Stocke l'URL retournée dans _uploadedMediaUrl
 */
async function _handleFileSelected(file) {
  const fmt = document.querySelector('[id^="fmt-"].active')?.id
              ?.replace('fmt-', '')
              ?.replace('imagetext', 'image+text')
              ?.replace('videotext', 'video+text') || 'text'

  // ── Validation type ──────────────────────────────────────────────
  const isImage = file.type.startsWith('image/')
  const isVideo = file.type.startsWith('video/')

  if ((fmt === 'image' || fmt === 'image+text') && !isImage) {
    _showUploadError('Ce format attend une image (jpg, png, webp...)')
    return
  }
  if ((fmt === 'video' || fmt === 'video+text') && !isVideo) {
    _showUploadError('Ce format attend une vidéo (mp4, mov...)')
    return
  }

  // ── Validation taille ────────────────────────────────────────────
  const MAX_IMAGE = 10 * 1024 * 1024  // 10 MB
  const MAX_VIDEO = 50 * 1024 * 1024  // 50 MB
  if (isImage && file.size > MAX_IMAGE) {
    _showUploadError(`Image trop lourde (max 10 MB, actuel : ${(file.size/1024/1024).toFixed(1)} MB)`)
    return
  }
  if (isVideo && file.size > MAX_VIDEO) {
    _showUploadError(`Vidéo trop lourde (max 50 MB, actuel : ${(file.size/1024/1024).toFixed(1)} MB)`)
    return
  }

  // ── Preview locale immédiate ─────────────────────────────────────
  _showUploadLoading(file.name, file.size)

  if (isImage) {
    const reader = new FileReader()
    reader.onload = e => _showImagePreview(e.target.result, file.name, file.size)
    reader.readAsDataURL(file)
  } else if (isVideo) {
    const objUrl = URL.createObjectURL(file)
    _showVideoPreview(objUrl, file.name, file.size)
  }

  // ── Upload vers le backend ───────────────────────────────────────
  try {
    const url = await _uploadToServer(file)
    setUploadedMediaUrl(url)
    _markUploadSuccess(url)
  } catch (err) {
    clearUploadedMediaUrl()
    _showUploadError(`Erreur upload : ${err.message}`)
  }
}

/**
 * Envoie le fichier en multipart vers POST /chat/media/upload.
 * Réutilise exactement la route existante dans routes_chat.py.
 * Retourne l'URL locale du fichier stocké.
 */
async function _uploadToServer(file) {
  const API_URL = window.API_URL || 'https://fdkvip.com'

  // user_id admin = 0 pour les broadcasts (le fichier n'est pas lié à un user)
  const form = new FormData()
  form.append('user_id', '0')
  form.append('file', file)

  const res = await fetch(`${API_URL}/chat/media/upload`, {
    method: 'POST',
    body:   form,
  })

  const data = await res.json()

  if (!res.ok) throw new Error(data.detail || 'Upload échoué')
  if (data.error) throw new Error(data.error)
  if (!data.url)  throw new Error('URL non retournée par le serveur')

  return data.url
}


// ════════════════════════════════════════════════════════════════════════════
// ── RENDU ZONE UPLOAD ─────────────────────────────────────────────────────
// ════════════════════════════════════════════════════════════════════════════

/** Taille lisible en Ko / Mo */
function _humanSize(bytes) {
  if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(0)} Ko`
  return `${(bytes / 1024 / 1024).toFixed(1)} Mo`
}

/** Zone neutre initiale (état par défaut) */
function _resetUploadZone() {
  const zone = document.querySelector('.upload-zone')
  if (!zone) return
  zone.innerHTML = `
    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
         stroke-width="1.5" style="margin:0 auto 6px;">
      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
      <polyline points="17 8 12 3 7 8"/>
      <line x1="12" y1="3" x2="12" y2="15"/>
    </svg>
    <p class="text-xs">Glisser un fichier ou <span style="color:#38bdf8;">parcourir</span></p>
    <p class="text-[10px] mt-1" style="color:#3f3f46;">ou coller un file_id Telegram directement</p>
  `
  zone.style.borderColor = ''
  zone.style.background  = ''
  zone.style.color       = ''

  // Vide aussi le file_id manuel
  const manualInput = document.querySelector('#media-upload input[type="text"]')
  if (manualInput) manualInput.value = ''
}

/** Spinner pendant l'upload */
function _showUploadLoading(name, size) {
  const zone = document.querySelector('.upload-zone')
  if (!zone) return
  zone.innerHTML = `
    <div style="display:flex;flex-direction:column;align-items:center;gap:8px;">
      <div style="width:24px;height:24px;border:2px solid rgba(56,189,248,.2);
                  border-top-color:#38bdf8;border-radius:50%;animation:spin .7s linear infinite;"></div>
      <p class="text-xs" style="color:#38bdf8;">Upload en cours…</p>
      <p class="text-[10px]" style="color:#3f3f46;">${name} · ${_humanSize(size)}</p>
    </div>
  `
  // Ajoute keyframe spin si absent
  if (!document.getElementById('_spin-style')) {
    const s = document.createElement('style')
    s.id        = '_spin-style'
    s.textContent = '@keyframes spin{to{transform:rotate(360deg)}}'
    document.head.appendChild(s)
  }
}

/** Preview image dans la zone */
function _showImagePreview(dataUrl, name, size) {
  const zone = document.querySelector('.upload-zone')
  if (!zone) return
  zone.innerHTML = `
    <div style="position:relative;">
      <img src="${dataUrl}" alt="preview"
           style="max-height:120px;max-width:100%;border-radius:8px;object-fit:cover;opacity:.5;">
      <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
        <div style="width:20px;height:20px;border:2px solid rgba(56,189,248,.3);
                    border-top-color:#38bdf8;border-radius:50%;animation:spin .7s linear infinite;"></div>
      </div>
    </div>
    <p class="text-[10px] mt-2" style="color:#52525b;">${name} · ${_humanSize(size)}</p>
  `
}

/** Preview vidéo dans la zone */
function _showVideoPreview(objUrl, name, size) {
  const zone = document.querySelector('.upload-zone')
  if (!zone) return
  zone.innerHTML = `
    <div style="position:relative;">
      <video src="${objUrl}" muted
             style="max-height:100px;max-width:100%;border-radius:8px;object-fit:cover;opacity:.5;"></video>
      <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
        <div style="width:20px;height:20px;border:2px solid rgba(56,189,248,.3);
                    border-top-color:#38bdf8;border-radius:50%;animation:spin .7s linear infinite;"></div>
      </div>
    </div>
    <p class="text-[10px] mt-2" style="color:#52525b;">${name} · ${_humanSize(size)}</p>
  `
}

/** ✅ Upload réussi */
function _markUploadSuccess(url) {
  const zone = document.querySelector('.upload-zone')
  if (!zone) return

  // Récupère les éléments preview existants (img ou video) pour les rendre nets
  const media = zone.querySelector('img, video')
  if (media) media.style.opacity = '1'

  // Enlève le spinner
  const spinner = zone.querySelector('div[style*="spin"]')
  if (spinner) spinner.remove()

  // Ajoute bandeau succès + bouton supprimer
  const filename = url.split('/').pop()
  const banner   = document.createElement('div')
  banner.style.cssText = `
    display:flex;align-items:center;justify-content:space-between;
    margin-top:8px;padding:6px 10px;
    background:rgba(52,211,153,.08);border:1px solid rgba(52,211,153,.2);
    border-radius:8px;gap:8px;
  `
  banner.innerHTML = `
    <div style="display:flex;align-items:center;gap:6px;min-width:0;">
      <svg width="12" height="12" fill="none" stroke="#34d399" viewBox="0 0 24 24" stroke-width="2">
        <polyline points="20 6 9 17 4 12"/>
      </svg>
      <span style="font-size:11px;color:#34d399;white-space:nowrap;overflow:hidden;
                   text-overflow:ellipsis;max-width:200px;" title="${url}">${filename}</span>
    </div>
    <button onclick="_removeUpload()" style="
      background:none;border:none;cursor:pointer;color:#52525b;padding:0;
      display:flex;align-items:center;flex-shrink:0;
    " title="Supprimer">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
      </svg>
    </button>
  `
  zone.appendChild(banner)
  zone.style.borderColor = 'rgba(52,211,153,.3)'
  zone.style.background  = 'rgba(52,211,153,.03)'
}

/** ❌ Erreur upload */
function _showUploadError(msg) {
  const zone = document.querySelector('.upload-zone')
  if (!zone) return
  zone.innerHTML = `
    <div style="display:flex;flex-direction:column;align-items:center;gap:6px;">
      <svg width="20" height="20" fill="none" stroke="#f87171" viewBox="0 0 24 24" stroke-width="1.5">
        <circle cx="12" cy="12" r="10"/>
        <path d="M12 8v4M12 16h.01"/>
      </svg>
      <p class="text-xs" style="color:#f87171;">${msg}</p>
      <button onclick="triggerUpload()" style="
        font-size:11px;color:#38bdf8;background:none;border:none;cursor:pointer;margin-top:2px;
      ">Réessayer</button>
    </div>
  `
  zone.style.borderColor = 'rgba(248,113,113,.3)'
  zone.style.background  = 'rgba(248,113,113,.03)'
}

/** Supprime l'upload et remet la zone à l'état initial */
function _removeUpload() {
  clearUploadedMediaUrl()
  _resetUploadZone()
}


// ════════════════════════════════════════════════════════════════════════════
// ── DRAG & DROP ───────────────────────────────────────────────────────────
// ════════════════════════════════════════════════════════════════════════════

function _initDragDrop() {
  const zone = document.querySelector('.upload-zone')
  if (!zone) return

  zone.addEventListener('dragover', e => {
    e.preventDefault()
    zone.style.borderColor = 'rgba(56,189,248,.5)'
    zone.style.background  = 'rgba(56,189,248,.06)'
  })

  zone.addEventListener('dragleave', () => {
    if (!getUploadedMediaUrl()) {
      zone.style.borderColor = ''
      zone.style.background  = ''
    }
  })

  zone.addEventListener('drop', e => {
    e.preventDefault()
    const file = e.dataTransfer?.files?.[0]
    if (file) _handleFileSelected(file)
  })
}


// ════════════════════════════════════════════════════════════════════════════
// ── INITIALISATION ────────────────────────────────────────────────────────
// ════════════════════════════════════════════════════════════════════════════

document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open') })
  })
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape')
      document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('open'))
  })

  // Crée l'input file caché dès le départ
  _getFileInput()

  // Drag & drop sur la zone upload
  _initDragDrop()
})