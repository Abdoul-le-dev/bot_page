/**
 * forms.js — TradingBot Form Builder
 */

'use strict';

import {
  apiGetForms,
  apiGetForm,
  apiSaveForm,
  apiDeleteForm,
  apiGetFormStats,
  apiGetResponses,
  apiGetUserResponses,
} from './form_api.js'

/* ══════════════════════════════════════════════════════════════════════
   STATE
══════════════════════════════════════════════════════════════════════ */
let curView       = 'list'
let curStep       = 0
let fCtr          = 0
let fields        = []
let hist          = []
let hIdx          = -1
let saveTimer     = null
let sortable      = null
let currentFormId = null
let formsList     = []

// État modal réponses
let _detailFormId    = null
let _detailAllUsers  = []        // toutes les soumissions du formulaire courant
let _detailCurrentId = null      // telegram_id sélectionné

/* ══════════════════════════════════════════════════════════════════════
   FIELD TYPES
══════════════════════════════════════════════════════════════════════ */
const TM = {
  text:     { label:'Texte court',          color:'#38bdf8', bg:'rgba(56,189,248,.12)',  api:'Texte libre',                     opts:false, media:false, hasAns:false },
  long:     { label:'Texte long',           color:'#38bdf8', bg:'rgba(56,189,248,.12)',  api:'Texte libre',                     opts:false, media:false, hasAns:false },
  email:    { label:'Email',                color:'#34d399', bg:'rgba(52,211,153,.12)',  api:'Texte libre (email)',              opts:false, media:false, hasAns:false },
  number:   { label:'Nombre',               color:'#fbbf24', bg:'rgba(251,191,36,.12)',  api:'Texte libre (nombre)',             opts:false, media:false, hasAns:true  },
  qcm:      { label:'QCM — 1 réponse',      color:'#fb923c', bg:'rgba(251,146,60,.12)',  api:'InlineKeyboardMarkup',             opts:true,  media:false, hasAns:true  },
  multi:    { label:'QCM — plusieurs rép.', color:'#fb923c', bg:'rgba(251,146,60,.12)',  api:'InlineKeyboardMarkup + Valider',   opts:true,  media:false, hasAns:true  },
  oui_non:  { label:'Oui / Non',            color:'#fb923c', bg:'rgba(251,146,60,.12)',  api:'InlineKeyboardMarkup [Oui][Non]',  opts:false, media:false, hasAns:true  },
  note5:    { label:'Note 1–5',             color:'#fbbf24', bg:'rgba(251,191,36,.12)',  api:'InlineKeyboardMarkup [⭐1]…[⭐5]', opts:false, media:false, hasAns:false },
  nps:      { label:'NPS 0–10',             color:'#2dd4bf', bg:'rgba(45,212,191,.12)',  api:'InlineKeyboardMarkup [0]…[10]',   opts:false, media:false, hasAns:false },
  photo:    { label:'Photo / Image',        color:'#a78bfa', bg:'rgba(167,139,250,.12)', api:'message_handler (photo)',          opts:false, media:true,  hasAns:false },
  video:    { label:'Vidéo',               color:'#a78bfa', bg:'rgba(167,139,250,.12)', api:'message_handler (video)',          opts:false, media:true,  hasAns:false },
  audio:    { label:'Message vocal',        color:'#a78bfa', bg:'rgba(167,139,250,.12)', api:'message_handler (voice)',          opts:false, media:true,  hasAns:false },
  document: { label:'Document',            color:'#f472b6', bg:'rgba(244,114,182,.12)', api:'message_handler (document)',       opts:false, media:true,  hasAns:false },
  contact:  { label:'Contact (tél.)',       color:'#2dd4bf', bg:'rgba(45,212,191,.12)',  api:'KeyboardButton (request_contact)', opts:false, media:false, hasAns:false },
  info:     { label:'Message info',         color:'#71717a', bg:'rgba(113,113,122,.12)', api:'sendMessage — pas de réponse',     opts:false, media:false, hasAns:false },
}

const TYPE_LABELS = {
  text:'Texte', long:'Texte long', email:'Email', number:'Nombre',
  qcm:'QCM', multi:'Multi-choix', oui_non:'Oui/Non',
  note5:'Note ⭐', nps:'NPS', photo:'Photo', video:'Vidéo',
  audio:'Vocal', document:'Document', contact:'Contact', info:'Info'
}

const ICO = {
  text:    `<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M4 6h16M4 12h8"/></svg>`,
  long:    `<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>`,
  email:   `<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>`,
  number:  `<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/></svg>`,
  qcm:     `<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/></svg>`,
  multi:   `<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>`,
  oui_non: `<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>`,
  note5:   `<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>`,
  nps:     `<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M3 3v18h18"/><path d="m7 16 4-4 4 4 4-6"/></svg>`,
  photo:   `<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>`,
  video:   `<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 8-6 4 6 4V8z"/><rect x="2" y="6" width="14" height="12" rx="2"/></svg>`,
  audio:   `<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/></svg>`,
  document:`<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>`,
  contact: `<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13z"/></svg>`,
  info:    `<svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
}

/* ══════════════════════════════════════════════════════════════════════
   TOAST
══════════════════════════════════════════════════════════════════════ */
function toast(msg, type = 'info', duration = 3000) {
  let container = document.getElementById('toast-container')
  if (!container) {
    container = document.createElement('div')
    container.id = 'toast-container'
    document.body.appendChild(container)
  }
  const icons = {
    success: `<svg width="14" height="14" fill="none" stroke="#34d399" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>`,
    error:   `<svg width="14" height="14" fill="none" stroke="#f87171" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>`,
    info:    `<svg width="14" height="14" fill="none" stroke="#38bdf8" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
    warning: `<svg width="14" height="14" fill="none" stroke="#fbbf24" viewBox="0 0 24 24" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>`,
  }
  const el = document.createElement('div')
  el.className = `toast ${type}`
  el.innerHTML = `<span class="toast-icon">${icons[type] || icons.info}</span><span>${msg}</span>`
  container.appendChild(el)
  setTimeout(() => {
    el.classList.add('out')
    setTimeout(() => el.remove(), 220)
  }, duration)
}

/* ══════════════════════════════════════════════════════════════════════
   CONFIRM DIALOG
══════════════════════════════════════════════════════════════════════ */
function confirmDialog(msg, onConfirm, { danger = false } = {}) {
  const ov = document.createElement('div')
  ov.className = 'overlay show'
  ov.innerHTML = `
    <div class="confirm-box">
      <p style="font-size:13px;font-weight:500;color:#e4e4e7;margin-bottom:8px">Confirmer</p>
      <p style="font-size:12px;color:#a1a1aa;margin-bottom:16px;line-height:1.5">${msg}</p>
      <div style="display:flex;justify-content:flex-end;gap:7px">
        <button class="btn ghost sm" id="c-cancel">Annuler</button>
        <button class="btn ${danger ? 'danger' : 'sky'} sm" id="c-ok">Confirmer</button>
      </div>
    </div>`
  document.body.appendChild(ov)
  ov.querySelector('#c-cancel').onclick = () => ov.remove()
  ov.querySelector('#c-ok').onclick     = () => { ov.remove(); onConfirm() }
  ov.addEventListener('click', e => { if (e.target === ov) ov.remove() })
}

/* ══════════════════════════════════════════════════════════════════════
   NAVIGATION
══════════════════════════════════════════════════════════════════════ */
function goView(view) {
  document.querySelectorAll('.view').forEach(el => el.style.display = 'none')
  const el = document.getElementById('view-' + view)
  if (el) el.style.display = 'flex'

  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'))
  document.querySelectorAll(`.tab[data-view="${view}"]`).forEach(t => t.classList.add('active'))

  curView = view

  const isB = view === 'builder'
  const suEl = document.getElementById('save-ui')
  const uuEl = document.getElementById('undo-ui')
  if (suEl) suEl.style.display = isB ? 'flex' : 'none'
  if (uuEl) uuEl.style.display = isB ? 'flex' : 'none'

  if (view === 'list')      loadFormsList()
  if (view === 'builder')   { buildDots(); renderStep(0) }
  if (view === 'responses') loadResponses()
}

/* ══════════════════════════════════════════════════════════════════════
   SIDEBAR / PREVIEW
══════════════════════════════════════════════════════════════════════ */
function openSidebar()  { document.getElementById('sidebar')?.classList.add('open');    document.getElementById('sb-overlay')?.classList.add('show') }
function closeSidebar() { document.getElementById('sidebar')?.classList.remove('open'); document.getElementById('sb-overlay')?.classList.remove('show') }
function togglePreview(){ document.getElementById('col-r')?.classList.toggle('open') }

/* ══════════════════════════════════════════════════════════════════════
   VUE LISTE
══════════════════════════════════════════════════════════════════════ */
async function loadFormsList() {
  const tbody    = document.getElementById('forms-tbody')
  const kpiTotal = document.getElementById('kpi-total')
  const kpiRep   = document.getElementById('kpi-reponses')
  const kpiComp  = document.getElementById('kpi-completion')
  const kpiScore = document.getElementById('kpi-score')
  if (!tbody) return

  tbody.innerHTML = `<div class="tbl-row"><div style="flex:1"><div class="skeleton" style="height:12px;width:55%;margin-bottom:5px"></div><div class="skeleton" style="height:10px;width:35%"></div></div></div>`.repeat(3)

  try {
    const data = await apiGetForms()
    formsList = data
    renderFormsList(data)

    const active  = data.filter(f => f.actif).length
    const totalR  = data.reduce((a, f) => a + (f.stats?.total || 0), 0)
    const avgComp = data.length ? Math.round(data.reduce((a, f) => a + (f.stats?.completion_pct || 0), 0) / data.length) : 0
    const avgSc   = data.length ? Math.round(data.reduce((a, f) => a + (f.stats?.avg_score || 0), 0) / data.length) : 0
    if (kpiTotal) kpiTotal.textContent = active
    if (kpiRep)   kpiRep.textContent   = totalR.toLocaleString('fr')
    if (kpiComp)  kpiComp.textContent  = avgComp + '%'
    if (kpiScore) kpiScore.textContent = avgSc + '%'
  } catch (e) {
    toast('Impossible de charger les formulaires', 'error')
    tbody.innerHTML = '<div class="empty-state"><p class="empty-ttl">Erreur de chargement</p></div>'
  }
}

function renderFormsList(forms) {
  const tbody = document.getElementById('forms-tbody')
  if (!tbody) return

  if (!forms.length) {
    tbody.innerHTML = `<div class="empty-state">
      <div class="empty-ico"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg></div>
      <p class="empty-ttl">Aucun formulaire</p>
      <p class="empty-sub">Crée ton premier formulaire ou charge un template.</p>
    </div>`
    return
  }

  const TYPE_BADGE = { inscription:'sky', sondage:'amber', quiz:'violet', journal:'teal', temoignage:'pink', custom:'zinc' }

  tbody.innerHTML = forms.map(f => {
    const stats    = f.stats || {}
    const pct      = stats.completion_pct || 0
    const badgeC   = TYPE_BADGE[f.type] || 'zinc'
    const pbarColor = pct >= 70 ? '#34d399' : pct >= 40 ? '#fbbf24' : '#f87171'
    const isActif   = f.actif

    return `
    <div class="tbl-row" onclick="editForm(${f.id})">
      <div style="flex:1">
        <p class="row-n">${esc(f.name)}</p>
        <p class="row-c">${esc(f.command)} · ${esc(f.trigger_type)}</p>
      </div>
      <span class="c-type"><span class="badge ${badgeC}">${esc(f.type)}</span></span>
      <span class="c-num">${(f.fields || []).length}</span>
      <span class="c-num">${(stats.total || 0).toLocaleString('fr')}</span>
      <div class="c-comp">
        <p style="font-size:12px;color:${pbarColor}">${pct}%</p>
        <div class="pbar"><div class="pbar-f" style="width:${pct}%;background:${pbarColor}"></div></div>
      </div>
      <span class="c-stat"><span class="badge ${isActif ? 'green' : 'zinc'}">${isActif ? 'Actif' : 'Inactif'}</span></span>
      <div class="row-actions" onclick="event.stopPropagation()">
        <button class="icon-btn" title="Modifier" onclick="editForm(${f.id})">
          <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
        </button>
        <button class="icon-btn" title="Voir réponses" onclick="openDetailForForm(${f.id});event.stopPropagation()">
          <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
        ${!isActif
          ? `<button class="btn-activate" title="Réactiver" onclick="activateForm(${f.id},event)">Activer</button>`
          : `<button class="icon-btn del" title="Supprimer" onclick="deleteForm(${f.id}, event)">
               <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
             </button>`
        }
      </div>
    </div>`
  }).join('')
}

async function editForm(id) {
  currentFormId = id
  try {
    const form = await apiGetForm(id)
    loadFromData(form)
    goView('builder')
  } catch (e) {
    toast('Impossible de charger le formulaire', 'error')
  }
}

async function deleteForm(id, e) {
  e.stopPropagation()
  confirmDialog('Supprimer ce formulaire ? Cette action est irréversible.', async () => {
    try {
      await apiDeleteForm(id)
      toast('Formulaire supprimé', 'success')
      loadFormsList()
    } catch (err) {
      toast('Erreur lors de la suppression', 'error')
    }
  }, { danger: true })
}

async function activateForm(id, e) {
  e.stopPropagation()
  try {
    const r = await fetch(`http://127.0.0.1:8000/forms/${id}/activate`, { method: 'POST' })
    if (!r.ok) throw new Error()
    toast('Formulaire réactivé', 'success')
    loadFormsList()
  } catch (e) {
    toast('Erreur lors de la réactivation', 'error')
  }
}

/* ══════════════════════════════════════════════════════════════════════
   VUE RÉPONSES — liste formulaires + tableau
══════════════════════════════════════════════════════════════════════ */
async function loadResponses() {
  const sel = document.getElementById('resp-form-sel')
  if (!sel) return

  try {
    if (!formsList.length) formsList = await apiGetForms()
    sel.innerHTML = formsList.map(f =>
      `<option value="${f.id}">${esc(f.name)} (${f.stats?.total || 0} rép.)</option>`
    ).join('') || `<option>Aucun formulaire</option>`
    if (formsList.length) loadResponsesForForm(formsList[0].id)
  } catch (e) {
    sel.innerHTML = `<option value="">Aucun formulaire disponible</option>`
  }
}

async function loadResponsesForForm(formId) {
  const tbody = document.getElementById('resp-tbody')
  if (!tbody) return
  tbody.innerHTML = `<div class="tbl-row"><div class="skeleton" style="height:12px;width:60%;flex:1"></div></div>`.repeat(4)

  try {
    const data = await apiGetResponses(formId)
    window._allResponses = data
    renderResponsesTable(data)
    _updateRespKPIs(data)
  } catch (e) {
    tbody.innerHTML = '<div class="empty-state"><p class="empty-ttl">Erreur de chargement</p></div>'
  }
}

function renderResponsesTable(data) {
  const tbody = document.getElementById('resp-tbody')
  if (!tbody) return

  if (!data.length) {
    tbody.innerHTML = `<div class="empty-state"><p class="empty-ttl">Aucune réponse</p><p class="empty-sub">Ce formulaire n'a pas encore été complété.</p></div>`
    return
  }

  tbody.innerHTML = data.map(r => {
    const initials   = (r.prenom || '?').substring(0, 2).toUpperCase()
    const pct        = r.pct || 0
    const scoreColor = pct >= 70 ? '#34d399' : pct >= 50 ? '#fbbf24' : '#f87171'
    const formId     = document.getElementById('resp-form-sel')?.value

    return `<div class="tbl-row">
      <input type="checkbox" style="accent-color:#38bdf8;flex-shrink:0">
      <div style="display:flex;align-items:center;gap:8px;flex:1;cursor:pointer" onclick="openResponseDetail(${r.telegram_id})">
        <div class="av-sm" style="background:rgba(56,189,248,.15);color:#38bdf8">${initials}</div>
        <div>
          <p style="font-size:12px;color:#e4e4e7;font-weight:500">${esc(r.prenom || 'User ' + r.telegram_id)}</p>
          <p style="font-size:10px;color:#52525b">ID : ${r.telegram_id}</p>
        </div>
      </div>
      <span class="c-num" style="font-size:11px;color:#71717a">${r.field_count || '—'}</span>
      ${r.score_max
        ? `<span class="c-sc" style="color:${scoreColor};font-weight:500">${r.score_final}/${r.score_max}</span>`
        : `<span class="c-sc" style="color:#52525b">—</span>`}
      <span style="width:90px;font-size:11px;color:#52525b">${r.submitted_at ? new Date(r.submitted_at).toLocaleDateString('fr') : '—'}</span>
      <button class="icon-btn" title="Voir le détail" onclick="openResponseDetail(${r.telegram_id})">
        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
      </button>
    </div>`
  }).join('')
}

function _updateRespKPIs(data) {
  const total     = data.length
  const completed = data.filter(r => r.status !== 'abandoned').length
  const avgScore  = total ? Math.round(data.reduce((a, r) => a + (r.pct || 0), 0) / total) : 0
  const compPct   = total ? Math.round(completed / total * 100) : 0

  const el = id => document.getElementById(id)
  if (el('r-total'))     el('r-total').textContent     = total.toLocaleString('fr')
  if (el('r-completed')) el('r-completed').textContent = completed.toLocaleString('fr')
  if (el('r-score'))     el('r-score').textContent     = avgScore + '%'
  if (el('r-time'))      el('r-time').textContent      = compPct + '%'
}

function filterResponses(status) {
  const data = status
    ? (window._allResponses || []).filter(r =>
        status === 'completed' ? r.status !== 'abandoned' : r.status === 'abandoned')
    : (window._allResponses || [])
  renderResponsesTable(data)
}

function toggleAllChecks(checked) {
  document.querySelectorAll('#resp-tbody input[type=checkbox]').forEach(cb => cb.checked = checked)
}

async function exportCSV() {
  const sel    = document.getElementById('resp-form-sel')
  const formId = sel?.value
  if (!formId) { toast('Sélectionne un formulaire', 'warning'); return }
  try {
    const data = await apiGetResponses(formId, 10000)
    if (!data.length) { toast('Aucune donnée à exporter', 'info'); return }
    const keys = Object.keys(data[0])
    const csv  = [keys.join(','), ...data.map(r => keys.map(k => `"${String(r[k] || '').replace(/"/g, '""')}"`).join(','))].join('\n')
    const blob = new Blob([csv], { type: 'text/csv' })
    const url  = URL.createObjectURL(blob)
    const a    = document.createElement('a')
    a.href = url; a.download = `reponses_form_${formId}.csv`; a.click()
    URL.revokeObjectURL(url)
    toast('Export CSV téléchargé', 'success')
  } catch (e) {
    toast("Erreur lors de l'export", 'error')
  }
}

/* ══════════════════════════════════════════════════════════════════════
   MODAL RÉPONSES DÉTAIL — ouverture depuis la liste ou depuis le tableau
══════════════════════════════════════════════════════════════════════ */

/** Appelé depuis le bouton "voir réponses" de la vue liste */
async function openDetailForForm(formId) {
  _detailFormId   = formId
  _detailAllUsers = []
  _resetDetailModal()
  openModal('m-detail')
  await _loadAllUsers(formId)
}

/** Appelé depuis la vue réponses (tableau) — ouvre sur un user précis */
async function openResponseDetail(telegramId) {
  const sel    = document.getElementById('resp-form-sel')
  const formId = sel?.value
  if (!formId) { toast('Sélectionne un formulaire', 'warning'); return }

  _detailFormId   = formId
  _detailAllUsers = []
  _resetDetailModal()
  openModal('m-detail')

  await _loadAllUsers(formId)
  // Sélectionne l'utilisateur ciblé dès que la liste est chargée
  const user = _detailAllUsers.find(u => String(u.telegram_id) === String(telegramId))
  if (user) _selectUser(user)
}

function _resetDetailModal() {
  _detailCurrentId = null

  const el = id => document.getElementById(id)
  el('resp-user-list').innerHTML = ['','',''].map(() =>
    '<div class="skeleton" style="height:50px;margin:4px 10px;border-radius:6px"></div>'
  ).join('')
  el('resp-detail-body').innerHTML = `
    <div class="resp-empty-state">
      <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
        <circle cx="9" cy="7" r="4"/>
      </svg>
      <p style="font-size:12px">Sélectionne un participant</p>
    </div>`
  el('det-content-head').style.display = 'none'
  el('det-name').textContent           = 'Chargement…'
  el('det-meta').textContent           = '—'
  el('det-score-pill').innerHTML       = ''
  el('det-total-count').textContent    = ''
  if (el('resp-user-search')) el('resp-user-search').value = ''
}

async function _loadAllUsers(formId) {
  try {
    const data      = await apiGetResponses(formId, 500)
    _detailAllUsers = data
    _renderUserList(data)
    document.getElementById('det-total-count').textContent =
      `${data.length} participant${data.length > 1 ? 's' : ''}`
    // Sélection automatique du premier si aucun ciblé
    if (data.length && !_detailCurrentId) _selectUser(data[0])
  } catch (e) {
    document.getElementById('resp-user-list').innerHTML =
      '<p style="font-size:12px;color:#52525b;padding:14px 16px">Erreur de chargement</p>'
  }
}

function _renderUserList(users) {
  const el = document.getElementById('resp-user-list')
  if (!users.length) {
    el.innerHTML = '<p style="font-size:12px;color:#52525b;padding:14px 16px">Aucune réponse</p>'
    return
  }
  el.innerHTML = users.map(u => {
    const initials = (u.prenom || '?').substring(0, 2).toUpperCase()
    const pct      = u.pct || 0
    const color    = pct >= 70 ? '#34d399' : pct >= 50 ? '#fbbf24' : '#f87171'
    const active   = String(u.telegram_id) === String(_detailCurrentId) ? ' active' : ''
    return `
      <div class="resp-user-item${active}" id="uitem-${u.telegram_id}"
           onclick="_selectUser_${u.telegram_id}()">
        <div class="resp-av">${esc(initials)}</div>
        <div style="min-width:0;flex:1">
          <p class="resp-user-name">${esc(u.prenom || 'User ' + u.telegram_id)}</p>
          <p class="resp-user-meta">${u.submitted_at ? new Date(u.submitted_at).toLocaleDateString('fr') : 'En cours'}</p>
        </div>
        ${u.score_max ? `<span class="resp-user-score" style="color:${color}">${u.score_final}/${u.score_max}</span>` : ''}
      </div>`
  }).join('')

  // Attache les handlers (évite les problèmes de sérialisation JSON dans onclick)
  users.forEach(u => {
    window[`_selectUser_${u.telegram_id}`] = () => _selectUser(u)
  })
}

function filterUserList(q) {
  const filtered = q
    ? _detailAllUsers.filter(u =>
        (u.prenom || '').toLowerCase().includes(q.toLowerCase()) ||
        String(u.telegram_id).includes(q))
    : _detailAllUsers
  _renderUserList(filtered)
}

async function _selectUser(u) {
  _detailCurrentId = u.telegram_id

  // Highlight sidebar
  document.querySelectorAll('.resp-user-item').forEach(el => el.classList.remove('active'))
  document.getElementById('uitem-' + u.telegram_id)?.classList.add('active')

  // Header modal
  const initials = (u.prenom || '?').substring(0, 2).toUpperCase()
  document.getElementById('det-av').textContent   = initials
  document.getElementById('det-name').textContent = u.prenom || 'User ' + u.telegram_id
  document.getElementById('det-meta').textContent = `ID Telegram : ${u.telegram_id}`

  // Score pill
  const pill = document.getElementById('det-score-pill')
  if (u.score_max) {
    const pct   = u.pct || 0
    const color = pct >= 70 ? '#34d399' : pct >= 50 ? '#fbbf24' : '#f87171'
    pill.innerHTML = `<span style="font-size:13px;font-weight:600;color:${color}">${u.score_final}/${u.score_max} — ${pct}%</span>`
  } else { pill.innerHTML = '' }

  // Panneau contenu
  document.getElementById('det-content-head').style.display = 'flex'
  document.getElementById('det-content-name').textContent   = u.prenom || 'User ' + u.telegram_id
  document.getElementById('det-content-date').textContent   = u.submitted_at
    ? 'Soumis le ' + new Date(u.submitted_at).toLocaleString('fr')
    : 'Soumission en cours'

  // Loading spinner
  const body = document.getElementById('resp-detail-body')
  body.innerHTML = `
    <div style="display:flex;align-items:center;justify-content:center;padding:30px;gap:10px;color:#52525b;font-size:12px">
      <div class="spinner"></div> Chargement des réponses…
    </div>`

  try {
    const answers = await apiGetUserResponses(_detailFormId, u.telegram_id)
    _renderAnswers(body, answers, u)
  } catch (e) {
    body.innerHTML = '<div style="padding:20px;color:#f87171;font-size:12px;text-align:center">Impossible de charger les réponses.</div>'
  }
}

/* ══════════════════════════════════════════════════════════════════════
   RENDU DES RÉPONSES INDIVIDUELLES
══════════════════════════════════════════════════════════════════════ */
function _renderAnswers(container, answers, user) {
  if (!answers.length) {
    container.innerHTML = '<div class="resp-empty-state"><p style="font-size:12px">Aucune réponse enregistrée.</p></div>'
    return
  }

  let html = ''

  // Score summary si quiz
  if (user.score_max > 0) {
    const pct   = user.pct || 0
    const color = pct >= 70 ? '#34d399' : pct >= 50 ? '#fbbf24' : '#f87171'
    html += `
      <div class="score-panel">
        <div>
          <p class="score-big">${user.score_final} <span style="font-size:14px;color:#52525b">/ ${user.score_max}</span></p>
        </div>
        <div class="score-detail">
          Score final<br>
          <span style="color:${color};font-weight:600">${pct}%</span>
        </div>
      </div>`
  }

  answers.forEach((ans, idx) => {
    const correct   = ans.is_correct
    const isCorrect = correct === 1 || correct === true
    const hasQuiz   = correct !== null && correct !== undefined
    const cardBorder = hasQuiz
      ? (isCorrect ? 'border-left:2px solid #34d399' : 'border-left:2px solid #f87171')
      : ''
    const typeLabel = TYPE_LABELS[ans.field_type] || ans.field_type
    const isMedia   = ['photo', 'video', 'audio', 'document'].includes(ans.field_type)
    const value     = ans.value || ''

    let valueHtml = ''
    if (value === '__skip__') {
      valueHtml = '<p style="font-size:11px;color:#52525b;font-style:italic">Passé (optionnel)</p>'
    } else if (value === '__info__') {
      valueHtml = '<p style="font-size:11px;color:#52525b;font-style:italic">Message informatif</p>'
    } else if (isMedia && value && value !== '__media__') {
      valueHtml = _renderMedia(ans.field_type, value)
    } else {
      valueHtml = `<p style="font-size:13px;color:#e4e4e7;line-height:1.5;word-break:break-word">${
        value
          ? esc(value).replace(/\n/g, '<br>')
          : '<span style="color:#52525b;font-style:italic">—</span>'
      }</p>`
    }

    let scoreBadge = ''
    if (hasQuiz) {
      scoreBadge = `<span class="badge ${isCorrect ? 'green' : 'red'}" style="margin-left:auto;font-size:9px;flex-shrink:0">
        ${isCorrect ? '✓ Correct' : '✗ Incorrect'}${ans.points > 0 ? ' · +' + ans.points + ' pts' : ''}
      </span>`
    }

    html += `
      <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:8px;padding:10px 13px;${cardBorder}">
        <div style="display:flex;align-items:center;gap:7px;margin-bottom:6px">
          <span style="font-size:9px;font-weight:600;color:#38bdf8;background:rgba(56,189,248,.12);padding:2px 6px;border-radius:3px;font-family:'Geist Mono',monospace">
            Q${idx + 1}
          </span>
          <span style="font-size:10px;color:#71717a">${esc(typeLabel)}</span>
          ${scoreBadge}
        </div>
        ${ans.field_label ? `<p style="font-size:11px;color:#71717a;margin-bottom:5px;font-style:italic">${esc(ans.field_label)}</p>` : ''}
        ${valueHtml}
        ${ans.answered_at
          ? `<p style="font-size:9px;color:#3f3f46;margin-top:6px;text-align:right">${new Date(ans.answered_at).toLocaleTimeString('fr')}</p>`
          : ''}
      </div>`
  })

  container.innerHTML = html
}
const MEDIA_BASE = 'https://fdkvip.com'
function _mediaUrl(value) {
  if (!value) return ''
  if (value.startsWith('http')) return value
  return MEDIA_BASE + value
}
/* ══════════════════════════════════════════════════════════════════════
   RENDU DES MÉDIAS (photo / vidéo / audio / document)
══════════════════════════════════════════════════════════════════════ */
function _renderMedia(type, value) {
  if (!value || value === '__skip__' || value === '__media__') {
    return '<p style="font-size:11px;color:#52525b;font-style:italic">Fichier non disponible</p>'
  }
 
  const url      = _mediaUrl(value)
  const filename = value.split('/').pop()

  ///alert(url)
 
  // ── Photo ──────────────────────────────────────────────────────────
  if (type === 'photo') {
    return `
      <div style="margin-top:8px;border-radius:7px;overflow:hidden;background:#000;border:1px solid rgba(255,255,255,.07)">
        <img src="${esc(url)}"
             alt="Photo"
             loading="lazy"
             style="max-width:100%;max-height:300px;object-fit:contain;display:block;cursor:pointer;"
             onclick="window.open('${esc(url)}','_blank')"
             onerror="this.parentElement.innerHTML='<div style=\\'padding:12px;font-size:11px;color:#71717a;text-align:center\\'>Image introuvable</div>'">
      </div>`
  }
 
  // ── Vidéo ──────────────────────────────────────────────────────────
  if (type === 'video') {
    return `
      <div style="margin-top:8px;border-radius:7px;overflow:hidden;background:#000;border:1px solid rgba(255,255,255,.07)">
        <video controls preload="metadata"
               style="max-width:100%;max-height:260px;display:block;border-radius:7px;">
          <source src="${esc(url)}">
        </video>
      </div>`
  }
 
  // ── Audio / Message vocal ──────────────────────────────────────────
  if (type === 'audio') {
    return `
      <div style="margin-top:8px;border-radius:7px;background:rgba(167,139,250,.06);border:1px solid rgba(167,139,250,.15);padding:10px 12px">
        <audio controls preload="metadata" style="width:100%">
          <source src="${esc(url)}">
        </audio>
        <p style="font-size:9px;color:#52525b;margin-top:5px;text-align:center">
          Message vocal · ${esc(filename)}
        </p>
      </div>`
  }
 
  // ── Document ──────────────────────────────────────────────────────
  if (type === 'document') {
    // Détecter l'extension pour l'icône
    const ext = filename.split('.').pop().toLowerCase()
    const iconColor = ext === 'pdf' ? '#f87171'
                    : ext === 'docx' || ext === 'doc' ? '#38bdf8'
                    : ext === 'xlsx' || ext === 'xls' ? '#34d399'
                    : '#f472b6'
 
    return `
      <div style="margin-top:8px;border-radius:7px;border:1px solid rgba(255,255,255,.08);overflow:hidden">
        <a href="${esc(url)}"
           target="_blank"
           rel="noopener"
           download
           style="display:flex;align-items:center;gap:10px;padding:10px 13px;
                  color:${iconColor};font-size:12px;text-decoration:none;
                  background:rgba(255,255,255,.03);">
          <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="flex-shrink:0">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
          </svg>
          <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;color:#e4e4e7">
            ${esc(filename)}
          </span>
          <svg width="12" height="12" fill="none" stroke="#52525b" viewBox="0 0 24 24" stroke-width="1.5" style="flex-shrink:0">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/>
            <line x1="12" y1="15" x2="12" y2="3"/>
          </svg>
        </a>
      </div>`
  }
 
  // Fallback texte
  return `<p style="font-size:11px;color:#71717a;word-break:break-all;margin-top:4px">${esc(value)}</p>`
}
 

/* ══════════════════════════════════════════════════════════════════════
   FIELD RENDERING (builder)
══════════════════════════════════════════════════════════════════════ */
function renderAll() {
  const c = document.getElementById('fc')
  c.innerHTML = ''
  fields.forEach(f => c.appendChild(mkFiEl(f)))
  initSort()
  buildDots()
  document.getElementById('field-count').textContent = `(${fields.length})`
}



function mkFiEl(f) {
  const m  = TM[f.type] || TM.text
  const ic = ICO[f.type] || ICO.text
  const el = document.createElement('div')
  el.className = 'fi fadein'
  el.id        = 'fi-' + f.id
  el.dataset.id = f.id
  el.innerHTML = `
    <div class="fi-head" onclick="toggleFi('${f.id}')">
      <span class="drag" title="Glisser pour réorganiser">⠿</span>
      <div class="fi-type-ico" style="background:${m.bg};color:${m.color}">${ic}</div>
      <p class="fi-label" id="fl-${f.id}">${esc(f.label || m.label)}</p>
      ${f.quiz     ? '<span class="badge violet" style="font-size:9px">Quiz</span>'  : ''}
      ${f.required ? '<span class="badge zinc"   style="font-size:9px">Requis</span>' : ''}
      <div style="display:flex;gap:3px;margin-left:auto;flex-shrink:0" onclick="event.stopPropagation()">
        <button class="icon-btn" title="Dupliquer" onclick="dupF('${f.id}')">
          <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="8" y="8" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
        </button>
        <button class="icon-btn del" title="Supprimer" onclick="confirmDelField('${f.id}')">
          <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>
      <div class="fi-chevron">
        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
      </div>
    </div>
    <div class="fi-body" id="fb-${f.id}">${mkBody(f)}</div>`
  return el
}

function mkBody(f) {
  const m = TM[f.type] || TM.text
  let h = `<div class="api-note"><span>📡</span> Telegram API : <b>${m.api}</b></div>`

  h += `<div class="mb8">
    <p class="lbl">Question envoyée par le bot</p>
    <textarea class="inp" style="min-height:44px" oninput="setFP('${f.id}','label',this.value)">${esc(f.label || '')}</textarea>
  </div>`

  if (['text','long','email','number'].includes(f.type)) {
    h += `<div class="mb8">
      <p class="lbl">Exemple de réponse (preview)</p>
      <input class="inp" type="text" value="${esc(f.ph || '')}" oninput="setFP('${f.id}','ph',this.value)" placeholder="ex: marc@gmail.com">
    </div>`
  }

  if (m.opts) {
    h += `<p class="lbl" style="margin-bottom:6px">Options (boutons)</p><div id="opts-${f.id}">`
    ;(f.opts || []).forEach((o, i) => {
      h += `<div class="opt-row">
        <div class="cdot${o.c ? ' on' : ''}" title="Marquer comme bonne réponse" onclick="toggleC('${f.id}',${i})"></div>
        <input class="inp" style="flex:1;font-size:12px" type="text" value="${esc(o.t || '')}" oninput="setOpt('${f.id}',${i},'t',this.value)">
        ${f.quiz ? `<input class="inp" type="number" min="0" value="${o.pts || 10}" style="width:50px;text-align:center" oninput="setOpt('${f.id}',${i},'pts',+this.value)" title="Points">` : ''}
        <button class="icon-btn del" onclick="delOpt('${f.id}',${i})"><svg width="8" height="8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
      </div>`
    })
    h += `</div><button class="btn ghost sm" style="margin-top:6px" onclick="addOpt('${f.id}')">+ Option</button>`
  }

  if (f.type === 'oui_non') {
    h += `<div style="display:flex;gap:7px;margin-bottom:8px">
      <div style="flex:1;padding:6px;border-radius:7px;border:1px solid rgba(52,211,153,.25);background:rgba(52,211,153,.05);font-size:11px;color:#34d399;text-align:center">✅ Oui</div>
      <div style="flex:1;padding:6px;border-radius:7px;border:1px solid rgba(248,113,113,.25);background:rgba(248,113,113,.05);font-size:11px;color:#f87171;text-align:center">❌ Non</div>
    </div>
    <div class="mb8"><p class="lbl">Réponse correcte (si quiz)</p>
      <select class="inp" oninput="setFP('${f.id}','correctAnswer',this.value)">
        <option value="">— Pas de correction —</option>
        <option value="oui"${f.correctAnswer === 'oui' ? ' selected' : ''}>Oui</option>
        <option value="non"${f.correctAnswer === 'non' ? ' selected' : ''}>Non</option>
      </select></div>`
  }

  if (f.type === 'note5') {
    h += `<div style="display:flex;gap:4px;margin-bottom:8px">${[1,2,3,4,5].map(n => `<div style="flex:1;padding:5px;border-radius:6px;border:1px solid rgba(251,191,36,.2);background:rgba(251,191,36,.04);font-size:11px;color:#fbbf24;text-align:center">⭐${n}</div>`).join('')}</div>`
  }

  if (f.type === 'nps') {
    h += `<div style="display:flex;flex-wrap:wrap;gap:3px;margin-bottom:8px">${[...Array(11)].map((_, n) => `<div style="padding:4px 7px;border-radius:5px;border:1px solid rgba(45,212,191,.2);background:rgba(45,212,191,.03);font-size:11px;color:#2dd4bf">${n}</div>`).join('')}</div>`
  }

  if (m.media) {
    const hints = { photo:'image/jpeg, image/png', video:'video/mp4 (max 20 Mo)', audio:'audio/ogg, audio/mp3', document:'PDF, ZIP, DOCX…' }
    h += `<div class="mb8" style="font-size:11px;color:#71717a;padding:6px 10px;border:1px solid rgba(255,255,255,.06);border-radius:6px;font-family:'Geist Mono',monospace">${hints[f.type] || '*'}</div>`
  }

  if (f.type === 'contact') {
    h += `<div class="api-note" style="background:rgba(45,212,191,.04);border-color:rgba(45,212,191,.2);color:#2dd4bf;margin-bottom:8px"><span>📱</span> Telegram affiche un bouton natif "Partager mon numéro".</div>`
  }

  if (f.type === 'info') {
    h += `<div class="api-note" style="margin-bottom:8px"><span>ℹ️</span> Ce champ envoie un message sans attendre de réponse de l'utilisateur.</div>`
  }

  if (m.hasAns) {
    h += `<div style="padding-top:8px;border-top:1px solid rgba(255,255,255,.04);margin-top:8px">
      <div class="tog-row mb8">
        <div><p class="opt-p">Mode quiz</p><p class="opt-sub">Réponse correcte + points</p></div>
        <button class="toggle${f.quiz ? ' on' : ''}" onclick="toggleQuiz('${f.id}')"></button>
      </div>`
    if (f.quiz) {
      if (['text','long','email','number'].includes(f.type)) {
        h += `<div class="abox mb8">
          <p class="lbl" style="color:#34d399;font-weight:500;margin-bottom:5px">✓ Réponse correcte attendue</p>
          <input class="inp" type="text" value="${esc(f.correctAnswer || '')}" oninput="setFP('${f.id}','correctAnswer',this.value)" placeholder="Réponse attendue...">
          <div style="margin-top:6px"><p class="lbl">Points si correct</p><input class="inp" type="number" min="0" value="${f.pts || 10}" oninput="setFP('${f.id}','pts',+this.value)"></div>
        </div>`
      }
      h += `<div class="mb8"><p class="lbl">Explication envoyée après correction</p>
        <textarea class="inp" style="min-height:36px" placeholder="Ex: Le RSI > 70 indique un marché suracheté." oninput="setFP('${f.id}','expl',this.value)">${esc(f.expl || '')}</textarea>
      </div>`
    }
    h += `</div>`
  }

  h += `<div class="tog-row" style="margin-top:8px">
    <div><p class="opt-p">Champ requis</p><p class="opt-sub">Bloque si pas de réponse</p></div>
    <button class="toggle${f.required ? ' on' : ''}" onclick="toggleReq('${f.id}')"></button>
  </div>`

  return h
}

/* ══════════════════════════════════════════════════════════════════════
   FIELD OPS
══════════════════════════════════════════════════════════════════════ */
function addF(type) {
  pushH()
  const id = ++fCtr
  const m  = TM[type] || TM.text
  fields.push({ id, type, label:'', ph:'', required:true, quiz:false, opts: m.opts ? [{t:'Option A',c:false,pts:10},{t:'Option B',c:false,pts:10}] : [], correctAnswer:null, pts:10, expl:'' })
  const el = mkFiEl(fields[fields.length - 1])
  document.getElementById('fc').appendChild(el)
  toggleFi(id, true)
  el.scrollIntoView({ behavior:'smooth', block:'nearest' })
  initSort(); buildDots(); scheduleSave()
  document.getElementById('field-count').textContent = `(${fields.length})`
}

function confirmDelField(id) { confirmDialog('Supprimer ce champ ?', () => delF(id), { danger: true }) }

function delF(id) {
  pushH()
  fields = fields.filter(f => f.id != id)
  const el = document.getElementById('fi-' + id)
  if (el) { el.style.transition = 'all .15s'; el.style.opacity = '0'; el.style.transform = 'translateX(-8px)'; setTimeout(() => el.remove(), 150) }
  if (curStep > fields.length) curStep = fields.length
  buildDots(); renderStep(curStep); scheduleSave()
  document.getElementById('field-count').textContent = `(${fields.length})`
}

function dupF(id) {
  pushH()
  const orig = fields.find(f => f.id == id); if (!orig) return
  const clone = JSON.parse(JSON.stringify(orig))
  clone.id = ++fCtr; clone.label += ' (copie)'
  fields.splice(fields.findIndex(f => f.id == id) + 1, 0, clone)
  renderAll(); scheduleSave()
  toast('Champ dupliqué', 'info', 1500)
}

function toggleFi(id, forceOpen) {
  const b = document.getElementById('fb-' + id), i = document.getElementById('fi-' + id)
  if (!b || !i) return
  const open = forceOpen !== undefined ? forceOpen : !b.classList.contains('show')
  b.classList.toggle('show', open)
  i.classList.toggle('open', open)
}

function collapseAll() {
  fields.forEach(f => {
    document.getElementById('fb-' + f.id)?.classList.remove('show')
    document.getElementById('fi-' + f.id)?.classList.remove('open')
  })
}

function setFP(id, p, v) {
  const f = fields.find(f => f.id == id); if (!f) return
  f[p] = v
  if (p === 'label') { const l = document.getElementById('fl-' + id); if (l) l.textContent = v || TM[f.type]?.label }
  scheduleSave(); if (curView === 'builder') renderStep(curStep)
}

function toggleReq(id)  { const f = fields.find(f => f.id == id); if (!f) return; f.required = !f.required; rebuildBody(id) }
function toggleQuiz(id) { pushH(); const f = fields.find(f => f.id == id); if (!f) return; f.quiz = !f.quiz; rebuildBody(id); scheduleSave() }
function rebuildBody(id){ const f = fields.find(f => f.id == id), b = document.getElementById('fb-' + id); if (f && b) b.innerHTML = mkBody(f) }

function addOpt(fid)        { const f = fields.find(f => f.id == fid); if (!f) return; f.opts.push({t:'Nouvelle option',c:false,pts:10}); rebuildBody(fid) }
function delOpt(fid, i)     { const f = fields.find(f => f.id == fid); if (!f) return; f.opts.splice(i, 1); rebuildBody(fid) }
function setOpt(fid, i, p, v) { const f = fields.find(f => f.id == fid); if (!f || !f.opts[i]) return; f.opts[i][p] = v; scheduleSave() }
function toggleC(fid, i) {
  const f = fields.find(f => f.id == fid); if (!f || !f.opts[i]) return
  if (f.type === 'qcm') f.opts.forEach((o, j) => o.c = j === i)
  else f.opts[i].c = !f.opts[i].c
  rebuildBody(fid); scheduleSave()
}

/* ══════════════════════════════════════════════════════════════════════
   PALETTE
══════════════════════════════════════════════════════════════════════ */
function togglePal() { const p = document.getElementById('palette'); p.style.display = p.style.display !== 'none' ? 'none' : 'block' }
function hidePal()   { const p = document.getElementById('palette'); if (p) p.style.display = 'none' }

/* ══════════════════════════════════════════════════════════════════════
   DRAG & DROP
══════════════════════════════════════════════════════════════════════ */
function initSort() {
  if (sortable) sortable.destroy()
  sortable = Sortable.create(document.getElementById('fc'), {
    handle: '.drag', animation: 140, ghostClass: 'sortable-ghost',
    onEnd(e) {
      pushH()
      const m = fields.splice(e.oldIndex, 1)[0]
      fields.splice(e.newIndex, 0, m)
      buildDots(); scheduleSave()
    },
  })
}

/* ══════════════════════════════════════════════════════════════════════
   HISTORY
══════════════════════════════════════════════════════════════════════ */
function pushH() {
  const s = JSON.stringify(fields)
  hist = hist.slice(0, hIdx + 1); hist.push(s)
  if (hist.length > 60) hist.shift()
  hIdx = hist.length - 1; updH()
}
function undo() { if (hIdx <= 0) return; hIdx--; fields = JSON.parse(hist[hIdx]); renderAll(); renderStep(Math.min(curStep, fields.length)); updH() }
function redo() { if (hIdx >= hist.length - 1) return; hIdx++; fields = JSON.parse(hist[hIdx]); renderAll(); renderStep(Math.min(curStep, fields.length)); updH() }
function updH() {
  const u = document.getElementById('ubtn'), r = document.getElementById('rbtn')
  if (u) u.disabled = hIdx <= 0; if (r) r.disabled = hIdx >= hist.length - 1
}

/* ══════════════════════════════════════════════════════════════════════
   AUTOSAVE
══════════════════════════════════════════════════════════════════════ */
function scheduleSave() {
  setSave('pending'); clearTimeout(saveTimer)
  saveTimer = setTimeout(() => { setSave('saved'); pushH() }, 900)
}
function setSave(s) {
  const dot = document.getElementById('save-dot'), txt = document.getElementById('save-txt')
  if (!dot || !txt) return
  if (s === 'pending') { dot.style.background = '#fbbf24'; txt.textContent = 'Modifications…'; txt.style.color = '#fbbf24' }
  else                 { dot.style.background = '#34d399'; txt.textContent = 'Sauvegardé';     txt.style.color = '#52525b' }
}

/* ══════════════════════════════════════════════════════════════════════
   CONDITIONS & ACTIONS
══════════════════════════════════════════════════════════════════════ */
function getEndActions() {
  return [...document.querySelectorAll('#end-actions .act-row')].map(row => ({
    type:  row.querySelectorAll('select')[0]?.value || '',
    value: row.querySelectorAll('input')[0]?.value  || '',
  }))
}

function getConditions() {
  return [...document.querySelectorAll('#conds > div')].map(w => {
    const cs = w.querySelector('.cond-row')?.querySelectorAll('select,input') || []
    const as = w.querySelector('.act-row')?.querySelectorAll('select,input')  || []
    return {
      if:   { field: cs[0]?.value || '', op: cs[1]?.value || '=', value: cs[2]?.value || '' },
      then: { action: as[0]?.value || '', value: as[1]?.value || '' },
    }
  }).filter(c => c.if.field)
}

function addCond() {
  const opts = fields.map(f => `<option value="${esc(f.label || 'Champ')}">${esc(f.label || TM[f.type]?.label || 'Champ')}</option>`).join('')
  const d = document.createElement('div')
  d.style.marginBottom = '6px'
  d.innerHTML = `
    <div class="cond-row">
      <span class="cond-lbl" style="color:#52525b">SI</span>
      <select class="inp" style="font-size:11px;padding:3px 6px;flex:2">${opts || '<option>—</option>'}</select>
      <select class="inp" style="font-size:11px;padding:3px 6px;width:80px">
        <option value="=">=</option><option value="≠">≠</option><option value="contient">contient</option>
      </select>
      <input class="inp" type="text" placeholder="valeur" style="font-size:11px;padding:3px 6px;flex:1">
      <button class="icon-btn del" onclick="this.closest('.cond-row').closest('div').remove()">
        <svg width="8" height="8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="act-row">
      <span class="cond-lbl" style="color:#38bdf8">ALORS</span>
      <select class="inp" style="font-size:11px;padding:3px 6px;flex:2">
        <option>Ajouter catégorie</option><option>Passer le champ</option><option>Envoyer message</option><option>Notifier admin</option>
      </select>
      <input class="inp" type="text" placeholder="valeur..." style="font-size:11px;padding:3px 6px;flex:1">
    </div>`
  document.getElementById('conds').appendChild(d)
}

function addAction() {
  const d = document.createElement('div')
  d.innerHTML = `<div class="act-row" style="margin-top:4px">
    <svg width="10" height="10" fill="none" stroke="#38bdf8" viewBox="0 0 24 24" stroke-width="1.5" style="flex-shrink:0"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg>
    <select class="inp" style="font-size:11px;padding:3px 6px;flex:0 0 120px">
      <option>Ajouter catégorie</option><option>Envoyer message</option><option>Notifier admin</option><option>Broadcast</option>
    </select>
    <input class="inp" type="text" placeholder="valeur..." style="font-size:11px;padding:3px 6px;flex:1">
    <button class="icon-btn del" onclick="this.closest('.act-row').remove()"><svg width="8" height="8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
  </div>`
  document.getElementById('end-actions').appendChild(d)
}

/* ══════════════════════════════════════════════════════════════════════
   PUBLISH
══════════════════════════════════════════════════════════════════════ */
async function publish() {
  const name = document.getElementById('f-name')?.value?.trim()
  const cmd  = document.getElementById('f-cmd')?.value?.trim()

  if (!name)         { toast('Le nom du formulaire est requis', 'warning'); return }
  if (!cmd)          { toast('La commande Telegram est requise', 'warning'); return }
  if (!fields.length){ toast('Ajoute au moins un champ', 'warning'); return }

  const btn  = document.querySelector('[onclick="publish()"]')
  const orig = btn?.innerHTML
  if (btn) { btn.innerHTML = `<div class="spinner"></div> Publication…`; btn.style.opacity = '.7'; btn.style.pointerEvents = 'none' }

  // Lecture trigger_value (date ou cron)
  const triggerSel  = document.getElementById('f-trigger')?.value
  const triggerDate = document.getElementById('f-trigger-value')?.value
  const triggerCron = document.getElementById('f-trigger-cron')?.value?.trim()
  const triggerVal  = triggerCron || triggerDate || null

  const payload = {
    name,
    command:      cmd,
    type:         document.getElementById('f-type')?.value,
    trigger:      triggerSel,
    trigger_value: triggerVal,
    intro:        document.getElementById('f-intro')?.value,
    outro:        document.getElementById('f-outro')?.value,
    fields:       fields.map(f => ({ ...f, tgApi: TM[f.type]?.api })),
    actions:      getEndActions(),
    conditions:   getConditions(),
    quiz_config: {
      max:     +(document.getElementById('q-max')?.value     || 0),
      pts:     +(document.getElementById('q-pts')?.value     || 10),
      penalty: +(document.getElementById('q-penalty')?.value || 0),
    },
    options: {
      resume:       document.getElementById('opt-resume')?.classList.contains('on')       ?? true,
      progress:     document.getElementById('opt-progress')?.classList.contains('on')     ?? true,
      one_per_user: document.getElementById('opt-one-per-user')?.classList.contains('on') ?? true,
      notify_admin: document.getElementById('opt-notify')?.classList.contains('on')       ?? false,
      target_category: document.getElementById('f-target-cat')?.value || null,
    },
  }

  try {
    const data = await apiSaveForm(payload)
    currentFormId = data.form_id
    setSave('saved')
    toast(`Formulaire "${name}" publié avec succès !`, 'success')
    if (btn) { btn.innerHTML = `<svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg> Publié !`; btn.style.background = '#34d399' }
    setTimeout(() => {
      if (btn) { btn.innerHTML = orig; btn.style.background = ''; btn.style.opacity = ''; btn.style.pointerEvents = '' }
    }, 2200)
  } catch (e) {
    toast(`Erreur : ${e.message}`, 'error')
    if (btn) { btn.innerHTML = orig; btn.style.opacity = ''; btn.style.pointerEvents = '' }
  }
}

/* ══════════════════════════════════════════════════════════════════════
   LOAD FORM FROM API
══════════════════════════════════════════════════════════════════════ */
function loadFromData(form) {
  document.getElementById('f-name').value    = form.name    || ''
  document.getElementById('f-cmd').value     = (form.command || '').replace(/^\//, '')
  document.getElementById('f-type').value    = form.type    || 'custom'
  document.getElementById('f-trigger').value =
    form.trigger_type === 'scheduled'  ? 'Planifié (date/heure)'       :
    form.trigger_type === 'start'      ? "À l'inscription (/start)"    :
    form.trigger_type === 'condition'  ? 'Automatique (condition)'      :
                                          'Commande manuelle'
  document.getElementById('f-intro').value   = form.intro   || ''
  document.getElementById('f-outro').value   = form.outro   || ''
  document.getElementById('quiz-cfg').style.display = form.type === 'quiz' ? 'block' : 'none'

  // trigger_value si planifié
  if (form.trigger_value && document.getElementById('f-trigger-cron')) {
    document.getElementById('f-trigger-cron').value = form.trigger_value
    document.getElementById('trigger-date-wrap')?.classList.add('show')
  }
  updateMeta()

  fields = []; fCtr = 0
  ;(form.fields || []).forEach(f => { const id = ++fCtr; fields.push({ id, ...f, opts: JSON.parse(JSON.stringify(f.opts || [])) }) })
  renderAll(); pushH(); curStep = 0; buildDots(); renderStep(0)
  toast(`Formulaire "${form.name}" chargé`, 'info', 2000)
}

/* ══════════════════════════════════════════════════════════════════════
   TELEGRAM PREVIEW
══════════════════════════════════════════════════════════════════════ */
function steps() { return ['__intro__', ...fields, '__outro__'] }

function buildDots() {
  const c = document.getElementById('step-dots'); if (!c) return
  c.innerHTML = ''
  const ss = steps()
  ss.forEach((_, i) => {
    const isI = i === 0, isO = i === ss.length - 1
    const d = document.createElement('div')
    d.className = 'sdot' + (i === curStep ? ' on' : '') + (isI ? ' intro' : '') + (isO ? ' outro' : '')
    d.title  = isI ? 'Intro' : isO ? 'Outro' : `Champ ${i}`
    d.onclick = () => renderStep(i)
    c.appendChild(d)
  })
}

function renderStep(idx) {
  const ss = steps(); if (idx < 0 || idx >= ss.length) return
  curStep = idx

  document.querySelectorAll('.sdot').forEach((d, i) => {
    const isI = i === 0, isO = i === ss.length - 1
    d.className = 'sdot' + (i === idx ? ' on' : '') + (isI ? ' intro' : '') + (isO ? ' outro' : '')
  })

  const pct = ss.length > 1 ? Math.round(idx / (ss.length - 1) * 100) : 0
  const prog  = document.getElementById('prev-prog'), progt = document.getElementById('prev-prog-txt')
  if (prog)  prog.style.width    = pct + '%'
  if (progt) progt.textContent   = (idx > 0 && idx < ss.length - 1) ? `${idx}/${ss.length - 2}` : ''
  const si = document.getElementById('step-info'); if (si) si.textContent = `Étape ${idx + 1} / ${ss.length}`

  const feed = document.getElementById('tg-feed'), hint = document.getElementById('tg-hint'), rk = document.getElementById('tg-rk')
  if (!feed || !hint || !rk) return
  feed.innerHTML = ''; rk.innerHTML = ''; rk.style.display = 'none'

  const step = ss[idx]

  if (step === '__intro__') {
    const cmd = document.getElementById('f-cmd')?.value || 'formulaire'
    const txt = document.getElementById('f-intro')?.value || 'Bonjour ! Le formulaire va démarrer.'
    botMsg(feed, `<code style="background:rgba(167,139,250,.15);color:#a78bfa;padding:1px 5px;border-radius:4px;font-family:'Geist Mono',monospace;font-size:10px">/${cmd}</code>`)
    botMsg(feed, esc(txt).replace(/\n/g, '<br>'))
    feed.appendChild(mkBtn('▶️ Commencer', nextStep))
    hint.textContent = 'Appuie pour démarrer…'; return
  }

  if (step === '__outro__') {
    const txt = document.getElementById('f-outro')?.value || '✅ Merci pour tes réponses !'
    botMsg(feed, esc(txt).replace(/\n/g, '<br>'))
    hint.textContent = 'Formulaire terminé !'; return
  }

  const f = step, m = TM[f.type] || TM.text
  botMsg(feed, esc(f.label || m.label))

  if (['text','long','email','number'].includes(f.type)) {
    hint.textContent = f.ph || 'Tape ta réponse…'
    feed.appendChild(mkBtn('Envoyer ↗', () => {
      usrMsg(feed, f.ph || "Réponse de l'utilisateur")
      if (f.quiz && f.correctAnswer) {
        const ok = (f.ph || '').toLowerCase().includes((f.correctAnswer || '').toLowerCase())
        botMsg(feed, ok ? `✅ <b>Correct !</b>${f.expl ? '<br>' + esc(f.expl) : ''}` : `❌ <b>Incorrect.</b> Réponse : <i>${esc(f.correctAnswer)}</i>${f.expl ? '<br>' + esc(f.expl) : ''}`)
        setTimeout(nextStep, 900)
      } else { setTimeout(nextStep, 400) }
    }))
  }
  else if (f.type === 'qcm') {
    hint.textContent = 'Sélectionne une option…'
    ;(f.opts || []).forEach(o => {
      feed.appendChild(mkBtn(o.t, () => {
        usrMsg(feed, o.t)
        if (f.quiz) botMsg(feed, o.c ? `✅ <b>Correct !</b>${f.expl ? '<br>' + esc(f.expl) : ''}` : `❌ <b>Incorrect.</b>${f.opts.find(x => x.c) ? '<br>→ ' + esc(f.opts.find(x => x.c).t) : ''}${f.expl ? '<br>' + esc(f.expl) : ''}`)
        setTimeout(nextStep, f.quiz ? 900 : 400)
      }))
    })
  }
  else if (f.type === 'multi') {
    hint.textContent = 'Sélectionne une ou plusieurs options…'
    const sel = new Set()
    ;(f.opts || []).forEach((o, i) => {
      const b = mkBtn(o.t, () => {
        sel.has(i) ? sel.delete(i) : sel.add(i)
        b.style.background  = sel.has(i) ? 'rgba(56,189,248,.15)' : ''
        b.style.borderColor = sel.has(i) ? 'rgba(56,189,248,.4)'  : ''
      })
      feed.appendChild(b)
    })
    const val = mkBtn('✅ Valider', () => { usrMsg(feed, [...sel].map(i => f.opts[i].t).join(', ') || '—'); setTimeout(nextStep, 400) })
    val.style.marginTop = '5px'; feed.appendChild(val)
  }
  else if (f.type === 'oui_non') {
    hint.textContent = 'Choisis…'
    ;['✅ Oui','❌ Non'].forEach(t => feed.appendChild(mkBtn(t, () => { usrMsg(feed, t); setTimeout(nextStep, 400) })))
  }
  else if (f.type === 'note5') {
    hint.textContent = '1 = Mauvais · 5 = Excellent'
    const row = document.createElement('div'); row.style.cssText = 'display:flex;gap:4px;margin-top:3px'
    for (let i = 1; i <= 5; i++) { const n=i; const b = mkBtn('⭐' + n, () => { usrMsg(feed, '⭐'.repeat(n) + ` (${n}/5)`); setTimeout(nextStep, 400) }); b.style.flex = '1'; row.appendChild(b) }
    feed.appendChild(row)
  }
  else if (f.type === 'nps') {
    hint.textContent = '0 = Pas du tout · 10 = Absolument'
    const row = document.createElement('div'); row.style.cssText = 'display:flex;flex-wrap:wrap;gap:3px;margin-top:3px'
    for (let i = 0; i <= 10; i++) { const n=i; const b = mkBtn(String(n), () => { usrMsg(feed, `${n}/10`); setTimeout(nextStep, 400) }); b.style.cssText += 'padding:5px 8px;min-width:28px;flex:none'; row.appendChild(b) }
    feed.appendChild(row)
  }
  else if (m.media) {
    const lbl = { photo:'📸 Envoie ta photo', video:'🎬 Envoie ta vidéo', audio:'🎙️ Envoie un vocal', document:'📄 Envoie ton document' }
    hint.textContent = lbl[f.type] || 'Envoie un fichier…'
    const z = document.createElement('div'); z.className = 'upload-zone'
    z.innerHTML = `<p style="font-size:22px;margin-bottom:5px">${{photo:'📸',video:'🎬',audio:'🎙️',document:'📄'}[f.type]}</p><p style="font-size:10px;color:#64b5f6">${lbl[f.type]}</p>`
    z.onclick = () => { z.innerHTML = '<p style="font-size:11px;color:#34d399">✅ Fichier reçu</p>'; setTimeout(nextStep, 600) }
    feed.appendChild(z)
  }
  else if (f.type === 'contact') {
    hint.textContent = 'Partage ton numéro…'
    rk.style.display = 'flex'
    const kb = document.createElement('div'); kb.className = 'tg-rk-btn'; kb.style.width = '100%'; kb.textContent = '📱 Partager mon numéro'
    kb.onclick = () => { usrMsg(feed, '📱 +33 6 12 34 56 78'); rk.style.display = 'none'; setTimeout(nextStep, 400) }
    rk.appendChild(kb)
  }
  else if (f.type === 'info') {
    hint.textContent = ''; feed.appendChild(mkBtn('Continuer →', nextStep))
  }

  feed.scrollTop = feed.scrollHeight
}

function botMsg(feed, html) {
  const d = document.createElement('div')
  d.innerHTML = `<div class="bot-bbl">${html}</div><p class="tg-time">${ftime()}</p>`
  feed.appendChild(d); feed.scrollTop = feed.scrollHeight
}
function usrMsg(feed, txt) {
  const d = document.createElement('div'); d.style.cssText = 'align-self:flex-end'
  d.innerHTML = `<div class="user-bbl">${esc(txt)}</div><p class="tg-time r">${ftime()}</p>`
  feed.appendChild(d); feed.scrollTop = feed.scrollHeight
}
function mkBtn(txt, cb) { const b = document.createElement('div'); b.className = 'tg-btn'; b.textContent = txt; b.onclick = cb; return b }
function ftime()        { const d = new Date(); return `${String(d.getHours()).padStart(2,'0')}:${String(d.getMinutes()).padStart(2,'0')}` }
function nextStep()     { const ss = steps(); if (curStep < ss.length - 1) renderStep(curStep + 1) }
function prevStep()     { if (curStep > 0) renderStep(curStep - 1) }
function resetPrev()    { curStep = 0; buildDots(); renderStep(0) }

function updateMeta() {
  const n = document.getElementById('prev-name'), nm = document.getElementById('f-name')?.value
  if (n && nm) n.textContent = nm || 'TradingBot'
  const p = document.getElementById('cmd-pill'), c = document.getElementById('f-cmd')?.value
  if (p) p.textContent = c ? '/' + c : '/formulaire'
}
function sanitizeCmd(el) { el.value = el.value.toLowerCase().replace(/[^a-z0-9_]/g, ''); updateMeta(); scheduleSave() }
function onTypeChange()  { document.getElementById('quiz-cfg').style.display = document.getElementById('f-type')?.value === 'quiz' ? 'block' : 'none'; scheduleSave() }
function onTriggerChange() {
  const sel  = document.getElementById('f-trigger')
  const wrap = document.getElementById('trigger-date-wrap')
  if (!wrap) return
  wrap.classList.toggle('show', sel?.value === 'Planifié (date/heure)')
}

/* ══════════════════════════════════════════════════════════════════════
   MODAL
══════════════════════════════════════════════════════════════════════ */
function openModal(id)  { document.getElementById(id)?.classList.add('show') }
function closeModal(id) { document.getElementById(id)?.classList.remove('show') }

/* ══════════════════════════════════════════════════════════════════════
   TEMPLATES
══════════════════════════════════════════════════════════════════════ */
const TPLS = {
  inscription:{ name:'Onboarding Forex', cmd:'start', type:'inscription',
    intro:'Bonjour +prenom ! 👋\n\nBienvenue dans la communauté TradingBot.\nRéponds à ces questions pour personnaliser ton expérience.',
    outro:'✅ Bienvenue +prenom !\n\nTon profil est créé. Tu vas recevoir les signaux adaptés à ton niveau.',
    fields:[
      {type:'text',  label:'Quel est ton prénom ?',             ph:'Marc',               required:true,  quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
      {type:'email', label:'Ton adresse email ?',               ph:'marc@gmail.com',      required:true,  quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
      {type:'qcm',   label:'Ton niveau en trading ?',           required:true,  quiz:false, opts:[{t:'🟢 Débutant',c:false,pts:10},{t:'🟡 Intermédiaire',c:false,pts:10},{t:'🔴 Expert',c:false,pts:10}], correctAnswer:null, pts:10, expl:''},
      {type:'long',  label:'Quel est ton objectif principal ?', ph:'Ex: viser +5% par mois', required:false, quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
    ]},
  sondage:{ name:'Sondage satisfaction', cmd:'sondage', type:'sondage',
    intro:'📊 Sondage de satisfaction\n\n3 questions rapides pour améliorer notre service.',
    outro:'🙏 Merci +prenom ! Ton avis compte.',
    fields:[
      {type:'note5', label:'Note la qualité des signaux cette semaine (1 à 5) ?', required:true,  quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
      {type:'nps',   label:'Sur 10, tu recommanderais TradingBot à un ami ?',     required:true,  quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
      {type:'qcm',   label:"Qu'est-ce que tu apprécies le plus ?",               required:true,  quiz:false, opts:[{t:'📈 Les signaux',c:false,pts:10},{t:'💬 Le support',c:false,pts:10},{t:'📚 La formation',c:false,pts:10}], correctAnswer:null, pts:10, expl:''},
      {type:'long',  label:'Un commentaire ou une suggestion ?',                  ph:'Tout retour est bienvenu…', required:false, quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
    ]},
  quiz:{ name:'Quiz Analyse Technique', cmd:'quiz', type:'quiz',
    intro:'📚 Quiz Analyse Technique\n\nBonjour +prenom ! 4 questions pour tester tes connaissances. Bonne chance ! 🎯',
    outro:'🎉 Quiz terminé +prenom !\n\nTon score : +score / +total',
    fields:[
      {type:'qcm',    label:"Qu'est-ce qu'un Doji indique ?",            required:true, quiz:true, expl:'Le Doji = indécision.',        opts:[{t:'Indécision du marché',c:true,pts:10},{t:'Tendance haussière forte',c:false,pts:0},{t:'Signal de vente fiable',c:false,pts:0}], correctAnswer:null, pts:10},
      {type:'qcm',    label:'Le RSI à 75 signifie que le marché est :', required:true, quiz:true, expl:'RSI > 70 = suracheté.',        opts:[{t:'Suracheté 🔴',c:true,pts:10},{t:'Survendu 🟢',c:false,pts:0},{t:'Neutre ⚪',c:false,pts:0}], correctAnswer:null, pts:10},
      {type:'oui_non',label:'Le croisement MA20/MA50 à la hausse est-il haussier ?', required:true, quiz:true, correctAnswer:'oui', expl:'Oui — signal classique.', opts:[], pts:10},
      {type:'multi',  label:"Quels indicateurs sont de l'analyse technique ?", required:true, quiz:true, expl:'', opts:[{t:'RSI',c:true,pts:5},{t:'PIB',c:false,pts:0},{t:'MACD',c:true,pts:5},{t:'Inflation',c:false,pts:0},{t:'Bollinger',c:true,pts:5}], correctAnswer:null, pts:5},
    ]},
  journal:{ name:'Journal de trading hebdo', cmd:'journal', type:'journal',
    intro:'📓 Journal — Semaine du +date\n\nBonjour +prenom ! Prends 2 min pour enregistrer ta semaine.',
    outro:'✅ Journal enregistré +prenom ! Rendez-vous vendredi prochain 💪',
    fields:[
      {type:'qcm',   label:'Quelle paire as-tu principalement tradée ?', required:true,  quiz:false, opts:[{t:'EUR/USD',c:false,pts:10},{t:'GBP/USD',c:false,pts:10},{t:'XAU/USD',c:false,pts:10},{t:'BTC/USD',c:false,pts:10},{t:'Autre',c:false,pts:10}], correctAnswer:null, pts:10, expl:''},
      {type:'note5', label:'Note ta discipline cette semaine (1 à 5) ?', required:true,  quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
      {type:'number',label:'Combien de trades as-tu réalisés ?',         ph:'5',         required:true,  quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
      {type:'photo', label:'Screenshot de ton meilleur trade 📸 (optionnel)', required:false, quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
      {type:'audio', label:'Message vocal — comment tu te sens ? 🎙️',   required:false, quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
      {type:'long',  label:'Tes notes et observations',                  ph:"Qu'as-tu appris cette semaine ?", required:false, quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
    ]},
  temoignage:{ name:'Témoignage performance', cmd:'temoignage', type:'temoignage',
    intro:'⭐ Partage ton témoignage +prenom !',
    outro:'✨ Merci +prenom ! Ton témoignage sera partagé 🚀',
    fields:[
      {type:'note5', label:'Sur 5, quelle note donnes-tu à notre méthode ?', required:true,  quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
      {type:'long',  label:'Décris ta meilleure performance récente 📈',      ph:'Ex: +4.2% sur EUR/USD…', required:true, quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
      {type:'video', label:'Témoignage vidéo (30 sec max) 🎬',               required:false, quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
      {type:'photo', label:'Photo de ton écran en profit 📸',                 required:false, quiz:false, opts:[], correctAnswer:null, pts:10, expl:''},
    ]},
}

function loadTpl(id) {
  const t = TPLS[id] || TPLS.inscription
  currentFormId = null
  document.getElementById('f-name').value    = t.name  || ''
  document.getElementById('f-cmd').value     = t.cmd   || ''
  document.getElementById('f-type').value    = t.type  || 'custom'
  document.getElementById('f-intro').value   = t.intro || ''
  document.getElementById('f-outro').value   = t.outro || ''
  document.getElementById('quiz-cfg').style.display = t.type === 'quiz' ? 'block' : 'none'
  updateMeta()
  fields = []; fCtr = 0
  ;(t.fields || []).forEach(f => { const id = ++fCtr; fields.push({ id, ...f, opts: JSON.parse(JSON.stringify(f.opts || [])) }) })
  renderAll(); pushH(); curStep = 0; buildDots(); renderStep(0)
}

function newForm() {
  currentFormId = null
  ;['f-name','f-cmd','f-intro','f-outro'].forEach(id => { const el = document.getElementById(id); if (el) el.value = '' })
  document.getElementById('f-type').value = 'custom'
  document.getElementById('quiz-cfg').style.display = 'none'
  document.getElementById('trigger-date-wrap')?.classList.remove('show')
  const conds = document.getElementById('conds'); if (conds) conds.innerHTML = ''
  const ea = document.getElementById('end-actions')
  if (ea) ea.innerHTML = `<div class="act-row">
    <svg width="10" height="10" fill="none" stroke="#38bdf8" viewBox="0 0 24 24" stroke-width="1.5" style="flex-shrink:0"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg>
    <select class="inp" style="font-size:11px;padding:3px 6px;flex:0 0 120px"><option>Ajouter catégorie</option><option>Envoyer message</option><option>Notifier admin</option><option>Broadcast</option></select>
    <input class="inp" type="text" placeholder="valeur..." style="font-size:11px;padding:3px 6px;flex:1">
    <button class="icon-btn del" onclick="this.closest('.act-row').remove()"><svg width="8" height="8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
  </div>`
  fields = []; fCtr = 0; hist = []; hIdx = -1; updH()
  renderAll(); buildDots(); renderStep(0); updateMeta()
}

/* ══════════════════════════════════════════════════════════════════════
   UTILS
══════════════════════════════════════════════════════════════════════ */
function esc(s) {
  return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;')
}
function insertVar(id, v) {
  const el = document.getElementById(id); if (!el) return
  const p = el.selectionStart || el.value.length
  el.value = el.value.slice(0, p) + v + el.value.slice(p)
  el.focus(); el.setSelectionRange(p + v.length, p + v.length)
  scheduleSave()
}

/* ══════════════════════════════════════════════════════════════════════
   INIT
══════════════════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => goView(tab.dataset.view))
  })
  document.querySelectorAll('.overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('show') })
  })
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.overlay').forEach(m => m.classList.remove('show'))
      hidePal(); closeSidebar()
      document.getElementById('col-r')?.classList.remove('open')
    }
    if (curView !== 'builder') return
    const mod = e.metaKey || e.ctrlKey
    if (mod && e.key === 'z' && !e.shiftKey) { e.preventDefault(); undo() }
    if (mod && e.key === 'z' && e.shiftKey)  { e.preventDefault(); redo() }
    if (mod && e.key === 's')                { e.preventDefault(); publish() }
    if (mod && e.key === 'Enter')            { e.preventDefault(); togglePal() }
  })
  goView('list')
  setSave('saved')
})

/* ══════════════════════════════════════════════════════════════════════
   EXPOSITION GLOBALE
══════════════════════════════════════════════════════════════════════ */
Object.assign(window, {
  goView, openSidebar, closeSidebar, togglePreview,
  loadFormsList, editForm, deleteForm, activateForm,
  loadResponsesForForm, filterResponses, toggleAllChecks, exportCSV,
  openDetailForForm, openResponseDetail, filterUserList, _selectUser,
  newForm, loadTpl, publish, onTypeChange, onTriggerChange, sanitizeCmd, updateMeta, insertVar,
  addF, confirmDelField, delF, dupF, toggleFi, collapseAll,
  setFP, toggleReq, toggleQuiz, addOpt, delOpt, setOpt, toggleC,
  togglePal, hidePal, addCond, addAction,
  nextStep, prevStep, resetPrev, undo, redo,
  openModal, closeModal,
  renderResponsesTable,
})