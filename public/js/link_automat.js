// ══════════════════════════════════════════════════════════════
// CONFIG API
// ══════════════════════════════════════════════════════════════
const API_G = 'https://fdkvip.com/growth'
const API_B = 'https://fdkvip.com'

async function apiFetch(url, opts = {}) {
  const res = await fetch(url, { headers: { 'Content-Type': 'application/json' }, ...opts })
  if (!res.ok) { const e = await res.json().catch(() => ({})); throw new Error(e.detail || `HTTP ${res.status}`) }
  return res.json()
}

// ══════════════════════════════════════════════════════════════
// STATE LOCAL
// ══════════════════════════════════════════════════════════════
const S = {
  categories: [], links: [], jobs: [], plans: [],
  subscribers: [], promos: [],
  scores: [], autoPromos: {}, iaTrigger: 'form',
  currentLinkId: null, currentJobId: null,
  execLogs: [],
}

let _idCnt = 1
const uid = () => _idCnt++

// ══════════════════════════════════════════════════════════════
// TOAST
// ══════════════════════════════════════════════════════════════
function toast(msg, type = 'info', dur = 3000) {
  const tc = document.getElementById('toast-container')
  const el = document.createElement('div')
  el.className = 'toast ' + (type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warn' ? 'warn' : '')
  el.textContent = msg
  tc.appendChild(el)
  setTimeout(() => el.remove(), dur)
}

// ══════════════════════════════════════════════════════════════
// NAVIGATION
// ══════════════════════════════════════════════════════════════
const VIEWS_CFG = {
  links:         { title: 'Liens & Onboarding',    sub: "Génération de liens trackés, séquences d'onboarding, activation IA",        cta: 'Créer un lien',         ctaFn: () => openM('m-new-link') },
  automations:   { title: 'Automations',           sub: 'Jobs planifiés et conditionnels — messages et actions bot automatiques',      cta: 'Créer une automation',  ctaFn: () => openM('m-new-job') },
  subscriptions: { title: 'Abonnements',           sub: 'Plans, cycle de vie, renouvellements, MRR',                                  cta: '+ Abonner un membre',   ctaFn: () => openAddSub() },
  promos:        { title: 'Promotions & Codes',    sub: 'Codes promo, offres flash, win-back automatique',                            cta: 'Créer un code',         ctaFn: () => openM('m-new-promo') },
}

async function sv(view, el) {
  Object.keys(VIEWS_CFG).forEach(v => { const e = document.getElementById('v-' + v); if (e) e.style.display = 'none' })
  const t = document.getElementById('v-' + view); if (t) t.style.display = 'block'
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'))
  if (el) el.classList.add('active')
  const cfg = VIEWS_CFG[view]
  if (cfg) {
    document.getElementById('page-title').textContent = cfg.title
    document.getElementById('page-sub').textContent   = cfg.sub
    const btn = document.getElementById('main-cta'), lbl = document.getElementById('main-cta-label')
    if (cfg.cta) { btn.style.display = 'inline-flex'; lbl.textContent = cfg.cta; btn.onclick = cfg.ctaFn }
    else btn.style.display = 'none'
  }
  if (view === 'links')         { await fillCategorySelects(); await renderLinks(); await renderFunnel(); await renderLinkStats(); await loadIATrigger() }
  if (view === 'automations')   { await fillJobTargets(); await renderJobs(); renderTimeline(); renderExecLog() }
  if (view === 'subscriptions') { await fillPlanSelects(); await renderPlans(); await renderSubscribers(); await renderSubStats() }
  if (view === 'promos')        { await fillPromoSelects(); await renderPromos(); renderPromoStats(); await loadAutoPromos() }
}

function openCTA() {
  const v = Object.keys(VIEWS_CFG).find(v => document.getElementById('v-' + v)?.style.display !== 'none')
  if (VIEWS_CFG[v]?.ctaFn) VIEWS_CFG[v].ctaFn()
}

// ══════════════════════════════════════════════════════════════
// FILL SELECTS
// ══════════════════════════════════════════════════════════════
async function getCatsFromAPI() {
  try {
    const data = await apiFetch(API_B + '/categorie')
    return (data || []).map(c => ({ name: c.name_categorie, color: c.color || '#38bdf8', count: c.member_count || 0 }))
  } catch { return S.categories }
}

async function fillCategorySelects() {
  const cats = await getCatsFromAPI()
  S.categories = cats
  const lc = document.getElementById('link-category')
  if (lc) lc.innerHTML = '<option value="">Aucune catégorie</option>' + cats.map(c => `<option value="${c.name}">${c.name} (${c.count})</option>`).join('')
  const pc = document.getElementById('plan-categories')
  if (pc) pc.innerHTML = cats.map(c => `<option value="${c.name}">${c.name}</option>`).join('')
}

async function fillPlanSelects() {
  const plans = await apiFetch(API_G + '/plans').catch(() => S.plans || [])
  S.plans = plans
  const sfp = document.getElementById('sub-filter-plan')
  if (sfp) sfp.innerHTML = '<option value="">Tous les plans</option>' + plans.map(p => `<option value="${p.id}">${p.name}</option>`).join('')
  const pp = document.getElementById('promo-plan')
  if (pp) pp.innerHTML = '<option value="">Tous les plans</option>' + plans.map(p => `<option value="${p.id}">${p.name}</option>`).join('')
  const sps = document.getElementById('sub-plan-sel')
  if (sps) sps.innerHTML = '<option value="">Choisir un plan...</option>' + plans.map(p => `<option value="${p.id}">${p.name} — $${p.price_usd}/mois</option>`).join('')
}

async function fillPromoSelects() { await fillPlanSelects() }

async function fillJobTargets() {
  const cats = await getCatsFromAPI()
  const sel  = document.getElementById('job-target'); if (!sel) return
  sel.innerHTML = '<option value="all">Tous les membres</option><option value="admin">Admin uniquement</option>'
    + cats.map(c => `<option value="cat:${c.name}">${c.name} (${c.count})</option>`).join('')
  ;(S.segments || []).forEach(sg => {
    const opt = document.createElement('option'); opt.value = 'seg:' + sg.id; opt.textContent = 'Segment: ' + sg.name; sel.appendChild(opt)
  })
  const jcs = document.getElementById('job-cat-sel')
  if (jcs) jcs.innerHTML = '<option value="">Choisir...</option>' + cats.map(c => `<option value="${c.name}">${c.name}</option>`).join('')
}

async function fillFormSelect() {
  const forms = await apiFetch(API_B + '/forms').catch(() => [])
  const sel = document.getElementById('link-form'); if (!sel) return
  sel.innerHTML = '<option value="">Aucun formulaire</option>'
    + forms.map(f => `<option value="${f.id}">${esc(f.name)}</option>`).join('')
}

async function fillForModal(id) {
  if (id === 'm-new-link')     { await fillCategorySelects(); await fillFormSelect() }
  if (id === 'm-new-job')      { await fillJobTargets(); await fillCategorySelects() }
  if (id === 'm-new-plan')     await fillCategorySelects()
  if (id === 'm-new-promo')    await fillPlanSelects()
  if (id === 'm-add-sub')      await fillPlanSelects()
  if (id === 'm-broadcast')    { await populateBcTarget(); updateBcPreview(); updateBcCount() }
}

async function populateBcTarget() {
  const cats = await getCatsFromAPI()
  const sel  = document.getElementById('bc-target'); if (!sel) return
  const existing = [...sel.options].map(o => o.value)
  cats.forEach(c => { if (!existing.includes(c.name)) { const o = document.createElement('option'); o.value = c.name; o.textContent = c.name + ' (' + c.count + ')'; sel.appendChild(o) } })
}

// ══════════════════════════════════════════════════════════════
// LIENS
// ══════════════════════════════════════════════════════════════
function updateLinkPreview() {
  const p = document.getElementById('link-param').value.trim().replace(/\s+/g, '_').toLowerCase()
  document.getElementById('link-preview-url').textContent = `t.me/TradingBot?start=${p || '...'}`
}

async function renderLinks() {
  try { S.links = await apiFetch(API_G + '/links') } catch (e) { toast('Erreur chargement liens: ' + e.message, 'error') }
  _renderLinksList()
}

function _renderLinksList() {
  const el = document.getElementById('links-list'); if (!el) return
  if (!S.links.length) { el.innerHTML = '<div class="text-center py-8" style="color:#3f3f46;font-size:12px;">Aucun lien créé.</div>'; return }
  el.innerHTML = S.links.map(l => {
    const cat = S.categories.find(c => c.name === l.auto_category)
    const clicks = l.clicks || 0, regs = l.registrations || 0, subs = l.subscribers || 0
    const conv = clicks > 0 ? Math.round(regs / clicks * 100) : 0
    const expired = l.expires_at && new Date(l.expires_at) < new Date()
    return `<div class="link-card fadein ${l.is_active && !expired ? 'featured' : ''}" onclick="openLinkDrawer(${l.id})">
      <div class="flex items-start justify-between mb-2">
        <div class="flex items-center gap-2">
          <span style="font-size:18px;">${sourceEmoji(l.source)}</span>
          <div><p class="text-xs font-medium text-white">${esc(l.name)}</p><p class="text-[10px] mono mt-0.5" style="color:#38bdf8;">?start=${esc(l.start_param)}</p></div>
        </div>
        <div class="flex items-center gap-1.5">
          ${expired ? '<span class="badge bdg-z" style="font-size:9px;">Expiré</span>' : l.is_active ? '<span class="badge bdg-g" style="font-size:9px;">Actif</span>' : '<span class="badge bdg-z" style="font-size:9px;">Inactif</span>'}
          ${l.quota_max ? `<span class="badge bdg-a" style="font-size:9px;">${l.quota_used}/${l.quota_max}</span>` : ''}
          <button class="btn-i" style="width:22px;height:22px;" onclick="event.stopPropagation();copyLink('${l.start_param}')"><svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button>
        </div>
      </div>
      <div class="grid grid-cols-5 gap-2 mb-2">
        <div class="text-center"><p class="text-sm font-light text-white tabular-nums">${clicks}</p><p class="text-[9px]" style="color:#52525b;">Clics</p></div>
        <div class="text-center"><p class="text-sm font-light text-white tabular-nums">${regs}</p><p class="text-[9px]" style="color:#52525b;">Inscrits</p></div>
        <div class="text-center"><p class="text-sm font-light tabular-nums pos">${conv}%</p><p class="text-[9px]" style="color:#52525b;">Conv.</p></div>
        <div class="text-center"><p class="text-sm font-light tabular-nums" style="color:#a78bfa;">${l.forms_done || 0}</p><p class="text-[9px]" style="color:#52525b;">Forms</p></div>
        <div class="text-center"><p class="text-sm font-light tabular-nums" style="color:#38bdf8;">${subs}</p><p class="text-[9px]" style="color:#52525b;">Payants</p></div>
      </div>
      <div class="flex items-center gap-2 flex-wrap">
        ${cat ? `<span class="text-[10px]" style="color:#52525b;">Cat:</span><span class="badge" style="font-size:9px;background:${cat.color}18;color:${cat.color};">${cat.name}</span>` : ''}
        ${l.promo_code ? `<span class="badge bdg-v" style="font-size:9px;">${l.promo_code}</span>` : ''}
        ${l.form_name ? `<span class="badge bdg-t" style="font-size:9px;">📋 ${esc(l.form_name)}</span>` : ''}
        ${l.expires_at ? `<span class="text-[10px]" style="color:${expired ? '#f87171' : '#52525b'};">Expire: ${l.expires_at.slice(0, 10)}</span>` : ''}
      </div>
    </div>`
  }).join('')
}

function openLinkDrawer(id) {
  const l = S.links.find(x => x.id === id); if (!l) return
  S.currentLinkId = id
  document.getElementById('dl-name').textContent  = l.name
  document.getElementById('dl-param').textContent = `?start=${l.start_param}`
  const cat  = S.categories.find(c => c.name === l.auto_category)
  const clicks = l.clicks || 0, regs = l.registrations || 0, subs = l.subscribers || 0
  const conv = clicks > 0 ? Math.round(regs / clicks * 100) : 0
  document.getElementById('dl-content').innerHTML = `
    <div class="grid grid-cols-2 gap-2">
      <div class="stat-m text-center"><p class="text-xl font-light text-white">${clicks}</p><p class="text-[10px] mt-1" style="color:#52525b;">Clics</p></div>
      <div class="stat-m text-center"><p class="text-xl font-light pos">${conv}%</p><p class="text-[10px] mt-1" style="color:#52525b;">Conv.</p></div>
      <div class="stat-m text-center"><p class="text-xl font-light text-white">${regs}</p><p class="text-[10px] mt-1" style="color:#52525b;">Inscrits</p></div>
      <div class="stat-m text-center"><p class="text-xl font-light" style="color:#38bdf8;">${subs}</p><p class="text-[10px] mt-1" style="color:#52525b;">Payants</p></div>
    </div>
    <div><p class="slbl">Lien</p><div style="padding:10px 12px;background:rgba(56,189,248,.06);border:1px solid rgba(56,189,248,.2);border-radius:8px;font-size:12px;font-family:'Geist Mono',monospace;color:#38bdf8;word-break:break-all;">https://t.me/TradingBot?start=${l.start_param}</div>
      <button class="btn-g w-full justify-center mt-2" style="font-size:11px;" onclick="copyLink('${l.start_param}')">Copier le lien</button></div>
    <div><p class="slbl">Config</p>
      <div class="flex flex-col gap-1 text-[11px]">
        <div class="flex justify-between py-1" style="border-bottom:1px solid rgba(255,255,255,.04);"><span style="color:#52525b;">Source</span><span>${sourceEmoji(l.source)} ${l.source}</span></div>
        <div class="flex justify-between py-1" style="border-bottom:1px solid rgba(255,255,255,.04);"><span style="color:#52525b;">Catégorie</span><span>${cat ? `<span style="color:${cat.color};">${cat.name}</span>` : '—'}</span></div>
        <div class="flex justify-between py-1" style="border-bottom:1px solid rgba(255,255,255,.04);"><span style="color:#52525b;">Promo</span><span class="mono">${l.promo_code || '—'}</span></div>
        <div class="flex justify-between py-1" style="border-bottom:1px solid rgba(255,255,255,.04);"><span style="color:#52525b;">Formulaire</span><span>${esc(l.form_name || '—')}</span></div>
        <div class="flex justify-between py-1"><span style="color:#52525b;">Quota</span><span>${l.quota_max ? `${l.quota_used}/${l.quota_max}` : 'Illimité'}</span></div>
      </div>
    </div>`
  openDrw('d-link')
}

async function saveLink() {
  const name = document.getElementById('link-name').value.trim()
  const param = document.getElementById('link-param').value.trim().replace(/\s+/g, '_').toLowerCase()
  const cat = document.getElementById('link-category').value
  const promo = document.getElementById('link-promo').value.trim()
  const quota = parseInt(document.getElementById('link-quota').value) || null
  const exp = document.getElementById('link-expires').value || null
  const source = document.getElementById('link-source').value
  const editId = document.getElementById('link-edit-id').value
  const form_id = parseInt(document.getElementById('link-form').value) || null
  if (!name || !param) { toast('Nom et paramètre requis', 'error'); return }
  const payload = { name, start_param: param, auto_category: cat || null, promo_code: promo || null, form_id, quota_max: quota, expires_at: exp, source }
  try {
    if (editId) { await apiFetch(API_G + '/links/' + editId, { method: 'PATCH', body: JSON.stringify(payload) }); toast('Lien mis à jour ✓', 'success') }
    else { await apiFetch(API_G + '/links', { method: 'POST', body: JSON.stringify(payload) }); toast('Lien créé ✓', 'success') }
    closeM('m-new-link'); resetLinkForm(); await renderLinks(); await renderFunnel(); await renderLinkStats()
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

function resetLinkForm() {
  ;['link-name', 'link-param', 'link-promo', 'link-quota', 'link-expires'].forEach(id => { const e = document.getElementById(id); if (e) e.value = '' })
  document.getElementById('link-edit-id').value = ''
  document.getElementById('link-modal-title').textContent = "Créer un lien d'invitation"
  document.getElementById('link-preview-url').textContent = 't.me/TradingBot?start='
  document.getElementById('link-form').value = ''
}

async function editCurrentLink() {
  const l = S.links.find(x => x.id === S.currentLinkId); if (!l) return
  closeAllDrw()
  setTimeout(async () => {
    await fillCategorySelects()
    document.getElementById('link-edit-id').value   = l.id
    document.getElementById('link-name').value      = l.name
    document.getElementById('link-param').value     = l.start_param
    document.getElementById('link-category').value  = l.auto_category || ''
    document.getElementById('link-promo').value     = l.promo_code || ''
    document.getElementById('link-form').value      = l.form_id || ''
    document.getElementById('link-quota').value     = l.quota_max || ''
    document.getElementById('link-expires').value   = l.expires_at ? l.expires_at.slice(0, 10) : ''
    document.getElementById('link-source').value    = l.source || 'direct'
    document.getElementById('link-modal-title').textContent = 'Modifier le lien'
    updateLinkPreview(); openM('m-new-link')
  }, 200)
}

async function deleteCurrentLink() {
  if (!confirm('Supprimer ce lien ?')) return
  try {
    await apiFetch(API_G + '/links/' + S.currentLinkId, { method: 'DELETE' })
    closeAllDrw(); await renderLinks(); await renderFunnel(); await renderLinkStats()
    toast('Lien supprimé', 'success')
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

function copyLink(param) {
  navigator.clipboard.writeText(`https://t.me/TradingBot?start=${param}`).then(() => toast('Lien copié ✓', 'success')).catch(() => toast('Copie impossible', 'error'))
}

async function renderLinkStats() {
  try {
    const src = await apiFetch(API_G + '/analytics/sources')
    const active = S.links.filter(l => l.is_active).length
    const totalReg = S.links.reduce((a, l) => a + (l.registrations || 0), 0)
    const totalClicks = S.links.reduce((a, l) => a + (l.clicks || 0), 0)
    const conv = totalClicks > 0 ? Math.round(totalReg / totalClicks * 100) : 0
    const top = src[0]
    document.getElementById('stat-links-active').textContent = active
    document.getElementById('stat-links-sub').textContent    = S.links.length + ' créés'
    document.getElementById('stat-links-reg').textContent    = totalReg
    document.getElementById('stat-links-conv').textContent   = conv + '% conv.'
    document.getElementById('stat-top-source').textContent   = top ? top.name : '—'
    document.getElementById('stat-top-source-n').textContent = top ? top.registrations + ' membres' : '—'
  } catch (e) { console.warn('[linkStats]', e) }
}

async function renderFunnel() {
  try {
    const d = await apiFetch(API_G + '/analytics/funnel')
    const mx = Math.max(d.clicks, 1)
    document.getElementById('f1').textContent = d.clicks
    document.getElementById('f2').textContent = d.registered + ' (' + (d.clicks > 0 ? Math.round(d.registered / d.clicks * 100) : 0) + '%)'
    document.getElementById('f3').textContent = d.forms_done
    document.getElementById('f4').textContent = d.paying
    document.getElementById('f1b').style.width = '100%'
    document.getElementById('f2b').style.width = (d.clicks > 0 ? d.registered / mx * 100 : 0) + '%'
    document.getElementById('f3b').style.width = (d.clicks > 0 ? d.forms_done / mx * 100 : 0) + '%'
    document.getElementById('f4b').style.width = (d.clicks > 0 ? d.paying / mx * 100 : 0) + '%'
  } catch (e) { console.warn('[funnel]', e) }
}

async function saveIATrigger() {
  const trigType = document.querySelector('input[name="ia-trig"]:checked')?.value || 'form'
  const numInput = document.querySelector('input[name="ia-trig"][value="messages"]')?.closest('label')?.querySelector('input[type="number"]')
  const msgCount = parseInt(numInput?.value) || 5
  try {
    await apiFetch(API_G + '/ia-trigger', { method: 'PATCH', body: JSON.stringify({ trigger_type: trigType, messages_count: msgCount }) })
    toast('Déclencheur IA sauvegardé ✓', 'success')
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

async function loadIATrigger() {
  try {
    const d = await apiFetch(API_G + '/ia-trigger')
    const radio = document.querySelector(`input[name="ia-trig"][value="${d.trigger_type}"]`)
    if (radio) radio.checked = true
    if (d.messages_count) {
      const numInput = document.querySelector('input[name="ia-trig"][value="messages"]')?.closest('label')?.querySelector('input[type="number"]')
      if (numInput) numInput.value = d.messages_count
    }
  } catch (e) { console.warn('[ia-trigger]', e) }
}

// ══════════════════════════════════════════════════════════════
// JOBS / AUTOMATIONS
// ══════════════════════════════════════════════════════════════
function selectTriggerType(type) {
  ;['time', 'cond', 'event'].forEach(t => {
    const btn = document.getElementById('tp-' + t), conf = document.getElementById('tconf-' + t)
    if (!btn || !conf) return
    const cls = { time: 'trigger-pill time', cond: 'trigger-pill cond', event: 'trigger-pill event' }
    btn.className = t === type ? cls[t] : 'trigger-pill inactive'
    conf.style.display = t === type ? 'block' : 'none'
  })
}

function toggleJobActionDetails() {
  const type = document.getElementById('job-action-type').value
  document.getElementById('job-action-msg').style.display     = ['send_message', 'send_ia_bilan', 'notify_admin'].includes(type) ? 'block' : 'none'
  document.getElementById('job-action-form').style.display    = type === 'send_form' ? 'block' : 'none'
  document.getElementById('job-action-cat').style.display     = ['add_to_category', 'remove_from_category'].includes(type) ? 'block' : 'none'
  document.getElementById('job-action-webhook').style.display = type === 'webhook' ? 'block' : 'none'
  updateJobTgPreview()
}

function updateJobTgPreview() {
  const txt = document.getElementById('job-action-content')?.value || ''
  const el  = document.getElementById('job-tg-preview')
  if (el) el.innerHTML = esc(txt).replace(/\n/g, '<br>') || '<span style="color:#3f3f46;">Aperçu...</span>'
}

async function renderJobs() {
  try { S.jobs = await apiFetch(API_G + '/jobs') } catch (e) { toast('Erreur chargement jobs: ' + e.message, 'error') }
  _renderJobsList(); renderJobStats(); renderTimeline()
  document.getElementById('nav-jobs-count').textContent = (S.jobs || []).filter(j => j.is_active).length
}

function _renderJobsList() {
  const el = document.getElementById('jobs-list'); if (!el) return
  if (!S.jobs.length) { el.innerHTML = '<div class="text-center py-8" style="color:#3f3f46;font-size:12px;">Aucune automation.</div>'; return }
  el.innerHTML = S.jobs.map(j => {
    const tc = j.trig_type === 'time' ? 'time' : j.trig_type === 'cond' ? 'cond' : 'event'
    const tLabel = j.trig_type === 'time' ? `⏰ ${j.freq} ${j.run_time}` : j.trig_type === 'cond' ? `⚡ SI ${j.cond_field} ${j.cond_value}` : `🎯 ${j.event_type}`
    return `<div class="job-card fadein" onclick="openJobDrawer(${j.id})">
      <div class="flex items-center justify-between mb-2">
        <div class="flex items-center gap-2 flex-wrap">
          <span class="trigger-pill ${tc}" style="padding:3px 9px;font-size:10px;">${tLabel}</span>
          <p class="text-xs font-medium text-white">${esc(j.name)}</p>
        </div>
        <button class="toggle ${j.is_active ? 'on' : ''}" onclick="event.stopPropagation();toggleJob(${j.id})"></button>
      </div>
      <div class="flex flex-col gap-0.5 text-[10px]" style="color:#71717a;">
        <p>→ Cible : ${esc(formatTarget(j.target))}</p>
        <p>→ Action : ${esc(formatAction(j.action_type))}</p>
        ${j.action_content ? `<p>→ "${esc(j.action_content.substring(0, 60))}${j.action_content.length > 60 ? '...' : ''}"</p>` : ''}
      </div>
      <div class="flex items-center gap-2 mt-2 flex-wrap">
        <span class="badge ${j.is_active ? 'bdg-g' : 'bdg-z'}" style="font-size:9px;">${j.is_active ? 'Actif' : 'Inactif'}</span>
        <span class="text-[10px]" style="color:#52525b;">Prochain: ${esc(j.next_run_at ? j.next_run_at.slice(0, 16).replace('T', ' ') : '—')}</span>
        ${j.exec_count > 0 ? `<span class="text-[10px]" style="color:#52525b;">${j.exec_count} exec.</span>` : ''}
      </div>
    </div>`
  }).join('')
}

function openJobDrawer(id) {
  const j = S.jobs.find(x => x.id === id); if (!j) return
  S.currentJobId = id
  document.getElementById('dj-name').textContent = j.name
  document.getElementById('dj-type').textContent = `Automation · ${j.trig_type === 'time' ? 'Temporel' : j.trig_type === 'cond' ? 'Conditionnel' : 'Événement'}`
  document.getElementById('dj-content').innerHTML = `
    <div class="grid grid-cols-3 gap-2">
      <div class="stat-m text-center"><p class="text-lg font-light text-white">${j.exec_count || 0}</p><p class="text-[9px] mt-1" style="color:#52525b;">Exécutions</p></div>
      <div class="stat-m text-center"><p class="text-lg font-light pos">${j.exec_count > 0 ? Math.round((j.exec_count - j.err_count) / j.exec_count * 100) : 0}%</p><p class="text-[9px] mt-1" style="color:#52525b;">Succès</p></div>
      <div class="stat-m text-center"><p class="text-lg font-light neg">${j.err_count || 0}</p><p class="text-[9px] mt-1" style="color:#52525b;">Erreurs</p></div>
    </div>
    <div><p class="slbl">Cible</p><p class="text-xs text-zinc-300">${esc(formatTarget(j.target))}</p></div>
    <div><p class="slbl">Action</p><p class="text-xs text-zinc-300">${esc(formatAction(j.action_type))}</p>
      ${j.action_content ? `<div style="padding:8px 10px;background:rgba(255,255,255,.025);border-radius:7px;font-size:11px;color:#a1a1aa;margin-top:6px;">${esc(j.action_content)}</div>` : ''}</div>
    <div><p class="slbl">Prochain run</p><p class="text-xs text-zinc-300">${j.next_run_at ? j.next_run_at.slice(0, 16).replace('T', ' ') : '—'}</p></div>`
  openDrw('d-job')
}

async function saveJob() {
  const name   = document.getElementById('job-name').value.trim()
  const editId = document.getElementById('job-edit-id').value
  const trigType = ['time', 'cond', 'event'].find(t => document.getElementById('tp-' + t)?.className.includes(t + ' ') || document.getElementById('tp-' + t)?.className.endsWith(t)) || 'time'
  const target  = document.getElementById('job-target').value
  const actType = document.getElementById('job-action-type').value
  let content = ''
  if (['send_message', 'send_ia_bilan', 'notify_admin'].includes(actType)) content = document.getElementById('job-action-content')?.value || ''
  else if (actType === 'send_form') content = document.getElementById('job-form-sel')?.value || ''
  else if (['add_to_category', 'remove_from_category'].includes(actType)) content = document.getElementById('job-cat-sel')?.value || ''
  else if (actType === 'webhook') content = document.getElementById('job-webhook-url')?.value || ''
  if (!name) { toast('Nom requis', 'error'); return }
  const payload = {
    name, trig_type: trigType, target, action_type: actType, action_content: content,
    ...(trigType === 'time' ? { freq: document.getElementById('job-freq').value, run_time: document.getElementById('job-time').value } : {}),
    ...(trigType === 'cond' ? { cond_field: document.getElementById('job-cond-field').value, cond_value: document.getElementById('job-cond-val').value, cond_extra: document.getElementById('job-cond-extra').value } : {}),
    ...(trigType === 'event' ? { event_type: document.getElementById('job-event-type').value } : {}),
  }
  try {
    if (editId) { await apiFetch(API_G + '/jobs/' + editId, { method: 'PATCH', body: JSON.stringify(payload) }); toast('Automation mise à jour ✓', 'success') }
    else { await apiFetch(API_G + '/jobs', { method: 'POST', body: JSON.stringify(payload) }); toast('Automation créée ✓', 'success') }
    closeM('m-new-job'); resetJobForm(); await renderJobs()
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

function resetJobForm() {
  ;['job-name', 'job-action-content'].forEach(id => { const e = document.getElementById(id); if (e) e.value = '' })
  document.getElementById('job-edit-id').value = ''
  document.getElementById('job-modal-title').textContent = 'Créer une automation'
  selectTriggerType('time'); toggleJobActionDetails()
}

async function toggleJob(id) {
  const j = (S.jobs || []).find(x => x.id === id); if (!j) return
  try {
    await apiFetch(API_G + '/jobs/' + id, { method: 'PATCH', body: JSON.stringify({ is_active: j.is_active ? 0 : 1 }) })
    toast(j.is_active ? 'Job désactivé' : 'Job activé ✓', 'success'); await renderJobs()
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

async function runJobNow() {
  if (!S.currentJobId) return
  try {
    const r = await apiFetch(API_G + '/jobs/' + S.currentJobId + '/run', { method: 'POST', body: '{}' })
    closeAllDrw(); toast(`Exécuté ✓ — ${r.sent} envois, ${r.errors} erreurs`, 'success'); await renderJobs()
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

async function deleteCurrentJob() {
  if (!confirm('Supprimer cette automation ?')) return
  try {
    await apiFetch(API_G + '/jobs/' + S.currentJobId, { method: 'DELETE' })
    closeAllDrw(); await renderJobs(); toast('Automation supprimée', 'success')
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

function renderJobStats() {
  const active = (S.jobs || []).filter(j => j.is_active).length
  const execs  = (S.jobs || []).reduce((a, j) => a + (j.exec_count || 0), 0)
  const errs   = (S.jobs || []).reduce((a, j) => a + (j.err_count || 0), 0)
  const rate   = execs > 0 ? Math.round((execs - errs) / execs * 100) : null
  const next   = (S.jobs || []).filter(j => j.is_active && j.next_run_at).sort((a, b) => a.next_run_at > b.next_run_at ? 1 : -1)[0]
  document.getElementById('stat-jobs-active').textContent  = active
  document.getElementById('stat-jobs-today').textContent   = (S.jobs || []).filter(j => j.is_active && j.trig_type === 'time').length + ' planifiés'
  document.getElementById('stat-exec').textContent         = execs
  document.getElementById('stat-success-rate').textContent = rate != null ? rate + '%' : '—'
  document.getElementById('stat-next-job').textContent     = next ? next.name : '—'
  document.getElementById('stat-next-time').textContent    = next ? (next.next_run_at || '').slice(0, 16).replace('T', ' ') : '—'
}

function renderTimeline() {
  const el = document.getElementById('schedule-timeline'); if (!el) return
  const active = (S.jobs || []).filter(j => j.is_active && j.next_run_at && j.trig_type === 'time')
  if (!active.length) { el.innerHTML = '<div style="color:#3f3f46;font-size:12px;">Aucun job planifié.</div>'; return }
  el.innerHTML = active.slice(0, 4).map(j => `
    <div class="flex items-center gap-3 py-2" style="border-bottom:1px solid rgba(255,255,255,.04);">
      <div style="width:46px;text-align:center;flex-shrink:0;"><p class="text-xs font-medium" style="color:#38bdf8;">${(j.run_time || '—')}</p></div>
      <div style="width:1px;height:26px;background:rgba(56,189,248,.3);flex-shrink:0;"></div>
      <div><p class="text-xs text-zinc-200">${esc(j.name)}</p><p class="text-[10px]" style="color:#52525b;">${esc(formatTarget(j.target))}</p></div>
    </div>`).join('')
}

function renderExecLog() {
  const el = document.getElementById('exec-log'); if (!el) return
  if (!S.execLogs.length) { el.innerHTML = '<div style="color:#3f3f46;font-size:12px;">Aucune exécution.</div>'; return }
  el.innerHTML = S.execLogs.slice(-5).reverse().map(l => `
    <div class="flex items-center gap-2 py-1.5" style="border-bottom:1px solid rgba(255,255,255,.04);">
      <span style="width:8px;height:8px;border-radius:50%;background:${l.ok ? '#34d399' : '#f87171'};flex-shrink:0;"></span>
      <p class="text-xs text-zinc-300 flex-1">${esc(l.msg)}</p>
      <span class="text-[10px]" style="color:#52525b;">${l.at}</span>
    </div>`).join('')
}

// ══════════════════════════════════════════════════════════════
// PLANS
// ══════════════════════════════════════════════════════════════
async function renderPlans() {
  try { S.plans = await apiFetch(API_G + '/plans') } catch (e) { console.warn('[plans]', e) }
  _renderPlansList()
}

function _renderPlansList() {
  const el = document.getElementById('plans-list'); if (!el) return
  if (!S.plans.length) { el.innerHTML = '<div class="text-center py-6" style="color:#3f3f46;font-size:12px;">Aucun plan créé.</div>'; return }
  const clrs = ['#38bdf8', '#a78bfa', '#fbbf24', '#34d399', '#f87171']
  el.innerHTML = S.plans.map((p, i) => {
    const clr = clrs[i % clrs.length]
    const dur = p.duration_days === 0 ? 'À vie' : p.duration_days === 30 ? 'Mensuel' : p.duration_days === 90 ? 'Trimestriel' : p.duration_days === 365 ? 'Annuel' : p.duration_days + 'j'
    return `<div style="padding:12px;background:${clr}08;border:1px solid ${clr}30;border-radius:9px;cursor:pointer;" onclick="editPlan(${p.id})">
      <div class="flex items-center justify-between mb-1">
        <p class="text-xs font-medium text-white">${esc(p.name)}</p>
        <span class="badge" style="font-size:10px;background:${clr}15;color:${clr};">$${p.price_usd}/${dur}</span>
      </div>
      ${p.description ? `<p class="text-[10px] mb-1" style="color:#52525b;">${esc(p.description)}</p>` : ''}
      <div class="flex gap-3 text-[10px]">
        <span style="color:#34d399;">${p.active_count || 0} actifs</span>
        ${p.trial_count ? `<span style="color:#38bdf8;">${p.trial_count} essais</span>` : ''}
        ${p.trial_days ? `<span style="color:#52525b;">${p.trial_days}j essai</span>` : ''}
      </div>
      <div class="flex gap-1.5 mt-2">
        <button class="btn-g" style="font-size:9px;padding:3px 7px;" onclick="event.stopPropagation();editPlan(${p.id})">Modifier</button>
        <button class="btn-danger" style="font-size:9px;padding:3px 7px;" onclick="event.stopPropagation();deletePlan(${p.id})">Supprimer</button>
      </div>
    </div>`
  }).join('')
}

async function savePlan() {
  const name = document.getElementById('plan-name').value.trim()
  const price = parseFloat(document.getElementById('plan-price').value) || 0
  const dur = parseInt(document.getElementById('plan-duration').value) || 30
  const trial = parseInt(document.getElementById('plan-trial').value) || 0
  const desc = document.getElementById('plan-desc').value.trim()
  const cats = [...document.getElementById('plan-categories').selectedOptions].map(o => o.value)
  const editId = document.getElementById('plan-edit-id').value
  if (!name || !price) { toast('Nom et prix requis', 'error'); return }
  const payload = { name, price_usd: price, duration_days: dur, trial_days: trial, description: desc, categories: cats }
  try {
    if (editId) { await apiFetch(API_G + '/plans/' + editId, { method: 'PATCH', body: JSON.stringify(payload) }); toast('Plan mis à jour ✓', 'success') }
    else { await apiFetch(API_G + '/plans', { method: 'POST', body: JSON.stringify(payload) }); toast('Plan créé ✓', 'success') }
    closeM('m-new-plan'); resetPlanForm(); await renderPlans(); await fillPlanSelects(); await renderSubStats()
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

function resetPlanForm() {
  ;['plan-name', 'plan-price', 'plan-desc'].forEach(id => { const e = document.getElementById(id); if (e) e.value = '' })
  document.getElementById('plan-edit-id').value = ''
  document.getElementById('plan-trial').value = '0'
  document.getElementById('plan-modal-title').textContent = "Créer un plan d'abonnement"
}

function editPlan(id) {
  const p = S.plans.find(x => x.id === id); if (!p) return
  fillCategorySelects().then(() => {
    document.getElementById('plan-edit-id').value  = p.id
    document.getElementById('plan-name').value     = p.name
    document.getElementById('plan-price').value    = p.price_usd
    document.getElementById('plan-duration').value = p.duration_days
    document.getElementById('plan-trial').value    = p.trial_days || 0
    document.getElementById('plan-desc').value     = p.description || ''
    document.getElementById('plan-modal-title').textContent = 'Modifier le plan'
    openM('m-new-plan')
  })
}

async function deletePlan(id) {
  if (!confirm('Désactiver ce plan ?')) return
  try {
    await apiFetch(API_G + '/plans/' + id, { method: 'DELETE' })
    await renderPlans(); await renderSubStats(); toast('Plan désactivé', 'success')
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

function openAddSub() { fillPlanSelects().then(() => openM('m-add-sub')) }

// ══════════════════════════════════════════════════════════════
// ABONNEMENTS
// ══════════════════════════════════════════════════════════════
async function renderSubscribers() {
  const pf = document.getElementById('sub-filter-plan')?.value || ''
  const sf = document.getElementById('sub-filter-status')?.value || ''
  const params = new URLSearchParams()
  if (pf) params.set('plan_id', pf)
  if (sf) params.set('status', sf)
  try { S.subscribers = await apiFetch(API_G + '/subscriptions?' + params.toString()) } catch (e) { console.warn('[subs]', e) }
  _renderSubsList()
}

function _renderSubsList() {
  const el = document.getElementById('subs-list'); if (!el) return
  if (!S.subscribers.length) { el.innerHTML = '<div class="text-center py-8" style="color:#3f3f46;font-size:12px;">Aucun abonné.</div>'; return }
  const sc = { active: { cls: 'bdg-g', lbl: 'Actif' }, trial: { cls: 'bdg-b', lbl: 'Essai' }, expiring: { cls: 'bdg-a', lbl: '⚠ Expire' }, expired: { cls: 'bdg-r', lbl: 'Expiré' }, cancelled: { cls: 'bdg-z', lbl: 'Annulé' } }
  el.innerHTML = S.subscribers.map(s => {
    const ss = sc[s.status] || { cls: 'bdg-z', lbl: s.status }
    return `<div class="mrow px-4">
      <div class="av" style="font-size:9px;">${initials(s.member_name || '?')}</div>
      <div class="flex-1 min-w-0"><p class="text-xs text-zinc-200 truncate">${esc(s.member_name || '—')}</p><p class="text-[10px] truncate" style="color:#52525b;">${esc(s.plan_name || '?')} · ${(s.started_at || '').slice(0, 10)}</p></div>
      <span class="badge ${ss.cls}" style="font-size:9px;flex-shrink:0;">${ss.lbl}</span>
      <span class="text-[10px] hidden sm:inline" style="color:#52525b;min-width:55px;text-align:right;">$${(s.price_paid || 0).toFixed(0)}</span>
      <span class="text-[10px] hidden sm:inline" style="color:#52525b;min-width:70px;text-align:right;">${(s.expires_at || '').slice(0, 10)}</span>
      <button class="btn-i" style="width:22px;height:22px;" onclick="deleteSubscriber(${s.id})"><svg width="9" height="9" fill="none" stroke="#f87171" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>`
  }).join('')
}

async function renderSubStats() {
  try {
    const d = await apiFetch(API_G + '/subscriptions/stats')
    document.getElementById('stat-mrr').textContent          = '$' + (d.mrr || 0).toFixed(0)
    document.getElementById('stat-subs-active').textContent  = d.actifs || 0
    document.getElementById('stat-trials').textContent       = d.essais || 0
    document.getElementById('stat-churn').textContent        = d.churn_rate != null ? d.churn_rate + '%' : '—'
    document.getElementById('stat-expiring').textContent     = d.expiring_soon || 0
    document.getElementById('stat-churn').className         = (d.churn_rate || 0) > 5 ? 'text-xl font-light neg' : 'text-xl font-light pos'
  } catch (e) { console.warn('[subStats]', e) }
}

async function addSubscriber() {
  const name   = document.getElementById('sub-member-name').value.trim()
  const planId = parseInt(document.getElementById('sub-plan-sel').value)
  const status = document.getElementById('sub-status-sel').value
  const promo  = document.getElementById('sub-promo-code').value.trim()
  if (!name)   { toast('Nom requis', 'error'); return }
  if (!planId) { toast('Choisir un plan', 'error'); return }
  try {
    await apiFetch(API_G + '/subscriptions', { method: 'POST', body: JSON.stringify({ member_name: name, plan_id: planId, status, promo_code: promo || null }) })
    closeM('m-add-sub')
    document.getElementById('sub-member-name').value = ''
    document.getElementById('sub-promo-code').value  = ''
    await renderSubscribers(); await renderSubStats(); await renderPlans()
    toast(name + ' ajouté ✓', 'success')
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

async function deleteSubscriber(id) {
  if (!confirm('Supprimer cet abonné ?')) return
  try {
    await apiFetch(API_G + '/subscriptions/' + id, { method: 'DELETE' })
    await renderSubscribers(); await renderSubStats(); await renderPlans()
    toast('Abonné supprimé', 'success')
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

// ══════════════════════════════════════════════════════════════
// PROMOS
// ══════════════════════════════════════════════════════════════
async function renderPromos() {
  try { S.promos = await apiFetch(API_G + '/promos') } catch (e) { console.warn('[promos]', e) }
  _renderPromosList(); renderPromoStats()
}

function _renderPromosList() {
  const el = document.getElementById('promos-list'); if (!el) return
  if (!S.promos.length) { el.innerHTML = '<div class="text-center py-8" style="color:#3f3f46;font-size:12px;">Aucun code promo.</div>'; return }
  el.innerHTML = S.promos.map(p => {
    const expired = p.expires_at && p.expires_at < new Date().toISOString()
    const disc = p.discount_type === 'percent' ? `-${p.discount_value}%` : `-$${p.discount_value}`
    return `<div class="promo-card ${p.is_active && !expired ? 'active-p' : ''} fadein">
      <div class="flex items-start justify-between mb-2">
        <div><p class="text-base font-semibold text-white mono">${esc(p.code)}</p><p class="text-[10px]" style="color:#52525b;">${p.plan_name || 'Tous les plans'}${p.first_time_only ? ' · 1ère sous.' : ''}</p></div>
        <div class="text-right">
          <p class="text-xl font-light pos">${disc}</p>
          <div class="flex gap-1.5 mt-1">
            ${expired ? '<span class="badge bdg-z" style="font-size:9px;">Expiré</span>' : p.is_active ? '<span class="badge bdg-g" style="font-size:9px;">Actif</span>' : '<span class="badge bdg-z" style="font-size:9px;">Inactif</span>'}
            ${p.quota_max ? `<span class="badge bdg-a" style="font-size:9px;">${p.current_uses}/${p.quota_max}</span>` : ''}
          </div>
        </div>
      </div>
      <div class="flex gap-1.5 mt-2 flex-wrap">
        <button class="btn-g" style="font-size:9px;padding:3px 8px;" onclick="copyCode('${p.code}')">Copier</button>
        <button class="btn-g" style="font-size:9px;padding:3px 8px;" onclick="editPromo(${p.id})">Modifier</button>
        <button class="btn-i" style="width:22px;height:22px;" onclick="togglePromo(${p.id})"><svg width="10" height="10" fill="none" stroke="${p.is_active ? '#fbbf24' : '#34d399'}" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg></button>
        <button class="del-row-btn" onclick="deletePromo(${p.id})"><svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
      </div>
    </div>`
  }).join('')
}

function renderPromoStats() {
  const active = (S.promos || []).filter(p => p.is_active).length
  const uses   = (S.promos || []).reduce((a, p) => a + (p.current_uses || 0), 0)
  document.getElementById('stat-promos-active').textContent = active
  document.getElementById('stat-promo-uses').textContent    = uses
}

async function savePromo() {
  const code   = document.getElementById('promo-code').value.trim().toUpperCase()
  const value  = parseFloat(document.getElementById('promo-value').value) || 0
  const type   = document.getElementById('promo-type').value
  const planId = parseInt(document.getElementById('promo-plan').value) || null
  const quota  = parseInt(document.getElementById('promo-quota').value) || null
  const exp    = document.getElementById('promo-expires').value || null
  const first  = document.getElementById('promo-first-only').checked ? 1 : 0
  const editId = document.getElementById('promo-edit-id').value
  if (!code || !value) { toast('Code et réduction requis', 'error'); return }
  const payload = { code, discount_type: type, discount_value: value, plan_id: planId, quota_max: quota, first_time_only: first, expires_at: exp }
  try {
    if (editId) { await apiFetch(API_G + '/promos/' + editId, { method: 'PATCH', body: JSON.stringify(payload) }); toast('Code mis à jour ✓', 'success') }
    else { await apiFetch(API_G + '/promos', { method: 'POST', body: JSON.stringify(payload) }); toast('Code créé ✓', 'success') }
    closeM('m-new-promo'); resetPromoForm(); await renderPromos(); await fillPromoSelects()
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

function resetPromoForm() {
  ;['promo-code', 'promo-value', 'promo-quota', 'promo-expires'].forEach(id => { const e = document.getElementById(id); if (e) e.value = '' })
  document.getElementById('promo-edit-id').value = ''
  document.getElementById('promo-modal-title').textContent = 'Créer un code promo'
}

function editPromo(id) {
  const p = S.promos.find(x => x.id === id); if (!p) return
  fillPromoSelects().then(() => {
    document.getElementById('promo-edit-id').value     = p.id
    document.getElementById('promo-code').value        = p.code
    document.getElementById('promo-value').value       = p.discount_value
    document.getElementById('promo-type').value        = p.discount_type
    document.getElementById('promo-plan').value        = p.plan_id || ''
    document.getElementById('promo-quota').value       = p.quota_max || ''
    document.getElementById('promo-expires').value     = p.expires_at ? p.expires_at.slice(0, 10) : ''
    document.getElementById('promo-first-only').checked = !!p.first_time_only
    document.getElementById('promo-modal-title').textContent = 'Modifier le code'
    openM('m-new-promo')
  })
}

function copyCode(code) { navigator.clipboard.writeText(code).then(() => toast('Code copié ✓', 'success')) }

async function togglePromo(id) {
  const p = (S.promos || []).find(x => x.id === id); if (!p) return
  try {
    await apiFetch(API_G + '/promos/' + id, { method: 'PATCH', body: JSON.stringify({ is_active: p.is_active ? 0 : 1 }) })
    toast(p.is_active ? 'Code désactivé' : 'Code activé ✓', 'success'); await renderPromos()
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

async function deletePromo(id) {
  if (!confirm('Supprimer ce code promo ?')) return
  try {
    await apiFetch(API_G + '/promos/' + id, { method: 'DELETE' })
    await renderPromos(); await fillPromoSelects(); toast('Code supprimé', 'success')
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

async function testPromoCode() {
  const code = document.getElementById('test-promo-input').value.trim().toUpperCase()
  const el   = document.getElementById('test-promo-result')
  if (!code) { el.innerHTML = ''; return }
  try {
    const r = await apiFetch(API_G + '/promos/validate', { method: 'POST', body: JSON.stringify({ code }) })
    if (r.valid) {
      const disc = r.discount_type === 'percent' ? `-${r.discount_value}%` : `-$${r.discount_value}`
      el.innerHTML = `<span style="color:#34d399;">✅ Valide — ${disc}${r.plan_name ? ' · ' + r.plan_name : ''}</span>`
    } else {
      el.innerHTML = `<span style="color:#f87171;">❌ ${r.error}</span>`
    }
  } catch (e) { el.innerHTML = `<span style="color:#f87171;">Erreur: ${e.message}</span>` }
}

async function saveAutoPromos() {
  const payload = {
    anniversary_active: document.getElementById('tog-anniversary').classList.contains('on') ? 1 : 0,
    anniversary_pct: parseFloat(document.getElementById('ap-anniversary-pct').value) || 15,
    winback_active: document.getElementById('tog-winback').classList.contains('on') ? 1 : 0,
    winback_pct: parseFloat(document.getElementById('ap-winback-pct').value) || 20,
    upgrade_active: document.getElementById('tog-upgrade').classList.contains('on') ? 1 : 0,
    upgrade_pct: parseFloat(document.getElementById('ap-upgrade-pct').value) || 30,
  }
  try { await apiFetch(API_G + '/promos/auto-config', { method: 'PATCH', body: JSON.stringify(payload) }) }
  catch (e) { console.warn('[autoPromos save]', e) }
}

async function loadAutoPromos() {
  try {
    const d = await apiFetch(API_G + '/promos/auto-config')
    if (d.anniversary_active) document.getElementById('tog-anniversary').classList.add('on')
    document.getElementById('ap-anniversary-pct').value = d.anniversary_pct || 15
    if (d.winback_active) document.getElementById('tog-winback').classList.add('on')
    document.getElementById('ap-winback-pct').value = d.winback_pct || 20
    if (d.upgrade_active) document.getElementById('tog-upgrade').classList.add('on')
    document.getElementById('ap-upgrade-pct').value = d.upgrade_pct || 30
  } catch (e) { console.warn('[autoPromos load]', e) }
}

// ══════════════════════════════════════════════════════════════
// BROADCAST
// ══════════════════════════════════════════════════════════════
function updateBcPreview() {
  const txt = document.getElementById('bc-message')?.value || ''
  const el  = document.getElementById('bc-preview')
  if (el) el.innerHTML = esc(txt).replace(/\n/g, '<br>') || '<span style="color:#3f3f46;">Votre message...</span>'
}

function updateBcCount() {
  const t = document.getElementById('bc-target')?.value
  const el = document.getElementById('bc-count-label')
  const cat = S.categories.find(c => c.name === t)
  if (cat) el.textContent = cat.count + ' membres recevront ce message'
  else if (t === 'admin') el.textContent = 'Admin uniquement (test)'
  else el.textContent = 'Tous les membres'
}

async function sendBroadcast() {
  const msg = document.getElementById('bc-message').value.trim()
  const t   = document.getElementById('bc-target').value
  if (!msg) { toast('Message requis', 'error'); return }
  const payload = { message: msg, format: 'text', tag: 'broadcast_rapide_' + Date.now() }
  if (t === 'all') payload.category = 'all'
  else if (t === 'admin') payload.user_ids = [571718066]
  else payload.category = t
  try {
    await apiFetch(API_B + '/broadcast', { method: 'POST', body: JSON.stringify(payload) })
    closeM('m-broadcast'); document.getElementById('bc-message').value = ''; updateBcPreview()
    toast('Broadcast envoyé ✓', 'success')
  } catch (e) { toast('Erreur broadcast: ' + e.message, 'error') }
}

// ══════════════════════════════════════════════════════════════
// MODALS / DRAWERS
// ══════════════════════════════════════════════════════════════
async function openM(id) { await fillForModal(id); document.getElementById(id)?.classList.add('open') }
function closeM(id) { document.getElementById(id)?.classList.remove('open') }
function openDrw(id) { document.getElementById(id)?.classList.add('open'); document.getElementById('dov')?.classList.add('open') }
function closeAllDrw() { document.querySelectorAll('.drawer').forEach(d => d.classList.remove('open')); document.getElementById('dov')?.classList.remove('open') }

document.querySelectorAll('.overlay').forEach(o => o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open') }))
document.addEventListener('keydown', e => {
  if (e.key !== 'Escape') return
  closeAllDrw(); document.querySelectorAll('.overlay').forEach(m => m.classList.remove('open'))
})
document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('job-action-content')?.addEventListener('input', updateJobTgPreview)
  document.getElementById('bc-message')?.addEventListener('input', updateBcPreview)
})

// ══════════════════════════════════════════════════════════════
// UTILS
// ══════════════════════════════════════════════════════════════
function esc(s) { return String(s || '').replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;') }
function initials(n) { return (n || '?').trim().split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase() }
function sourceEmoji(s) { return { instagram: '📸', youtube: '🎬', partenaire: '🤝', webinaire: '💻', tiktok: '🎵', direct: '🔗' }[s] || '🔗' }
function formatTarget(t) {
  if (!t || t === 'all') return 'Tous les membres'
  if (t === 'admin') return 'Admin uniquement'
  if (t.startsWith('cat:')) return t.replace('cat:', 'Catégorie: ')
  if (t.startsWith('seg:')) { const sg = (S.segments||[]).find(s => s.id == t.replace('seg:', '')); return sg ? 'Segment: ' + sg.name : t }
  return t
}
function formatAction(a) {
  return { send_message: 'Envoyer message', send_form: 'Envoyer formulaire', send_ia_bilan: 'Bilan IA', add_to_category: 'Ajouter catégorie', remove_from_category: 'Retirer catégorie', notify_admin: 'Notifier admin', webhook: 'Webhook' }[a] || a
}

// ══════════════════════════════════════════════════════════════
// INIT
// ══════════════════════════════════════════════════════════════
async function init() {
  await sv('links', document.getElementById('nav-links'))
}

init()