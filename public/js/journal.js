// ══════════════════════════════════════════════════════════════
// CONFIG API
// ══════════════════════════════════════════════════════════════
const API      = 'https://fdkvip.com/trading'
const GOLD_API = 'https://fdkvip.com/gold'
const API_URL  = window.API_URL || 'https://fdkvip.com'

let _uploadedMediaUrl     = null
let _goldUploadedMediaUrl = null

function getUploadedMediaUrl()    { return _uploadedMediaUrl }
function setUploadedMediaUrl(url) { _uploadedMediaUrl = url }
function clearUploadedMediaUrl()  { _uploadedMediaUrl = null }

function _humanSize(b) {
  return b < 1048576 ? `${(b/1024).toFixed(0)} Ko` : `${(b/1048576).toFixed(1)} Mo`
}

async function apiFetch(path, opts = {}, base = API) {
  try {
    const res = await fetch(base + path, {
      headers: { 'Content-Type': 'application/json' }, ...opts,
    })
    if (!res.ok) {
      const err = await res.json().catch(() => ({}))
      throw new Error(err.detail || `HTTP ${res.status}`)
    }
    return await res.json()
  } catch (e) { console.error('API Error:', path, e); throw e }
}

async function goldFetch(path, opts = {}) {
  return apiFetch(path, opts, GOLD_API)
}

// ══════════════════════════════════════════════════════════════
// TOAST
// ══════════════════════════════════════════════════════════════
function toast(msg, type = 'info') {
  const el = document.createElement('div')
  el.className = `toast-item ${type}`
  el.textContent = msg
  document.getElementById('toast').appendChild(el)
  setTimeout(() => el.remove(), 3500)
}

// ══════════════════════════════════════════════════════════════
// NAVIGATION
// ══════════════════════════════════════════════════════════════
const ALL_VIEWS = ['journal','paires','formules','gold-dashboard','gold-sessions','gold-saisons','gold-simulations','gold-regles']

function showMainView(view, btn) {
  ALL_VIEWS.forEach(v => {
    const el = document.getElementById('main-' + v)
    if (!el) return
    el.style.cssText = v === view
      ? 'display:flex;flex-direction:column;height:100%;'
      : 'display:none;'
  })
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'))
  if (btn) btn.classList.add('active')

  if (view === 'paires')           loadPairs()
  if (view === 'formules')         { loadFormStats(); loadForms(); loadFormsSummary() }
  if (view === 'gold-dashboard')   loadGoldDashboard()
  if (view === 'gold-sessions')    { loadGoldSessions(); loadSeasonsForFilter() }
  if (view === 'gold-saisons')     loadGoldSaisons()
  if (view === 'gold-simulations') loadGoldSimulations()
  if (view === 'gold-regles')      loadGoldRegles()
}

function switchView(view, el) {
  ;['journal','history','members','leaderboard','ia'].forEach(v => {
    const e = document.getElementById('view-' + v)
    if (e) e.style.display = 'none'
  })
  const target = document.getElementById('view-' + view)
  if (target) target.style.display = 'block'
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'))
  if (el) el.classList.add('active')

  if (view === 'history')     { loadHistory(); loadCrossedPerf() }
  if (view === 'journal')     { loadSignals(); loadDashboardStats() }
  if (view === 'members')     loadPerformances()
  if (view === 'leaderboard') loadLeaderboard()
  if (view === 'ia')          { buildWeekOptions(); loadBilanHistory() }
}

// ══════════════════════════════════════════════════════════════
// DIRECTION BUY / SELL
// ══════════════════════════════════════════════════════════════
let tradeDir = 'buy'
let goldDir  = 'buy'

function dirLabel(d) {
  return d === 'buy' ? '📈 Achat (Buy)' : '📉 Vente (Sell)'
}
function dirBadge(d) {
  return d === 'buy'
    ? '<span class="dir-buy">Achat (Buy)</span>'
    : '<span class="dir-sell">Vente (Sell)</span>'
}

function setDir(d) {
  tradeDir = d
  const base = 'flex:1;padding:7px;border-radius:8px;cursor:pointer;font-size:12px;font-family:Geist,sans-serif;'
  document.getElementById('dir-buy').style.cssText  = base + (d==='buy'  ? 'border:1px solid rgba(52,211,153,.3);background:rgba(52,211,153,.1);color:#34d399;font-weight:500;' : 'border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:#71717a;')
  document.getElementById('dir-sell').style.cssText = base + (d==='sell' ? 'border:1px solid rgba(248,113,113,.3);background:rgba(248,113,113,.1);color:#f87171;font-weight:500;' : 'border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:#71717a;')
  updatePreview()
}

function setGoldDir(d) {
  goldDir = d
  const base = 'flex:1;padding:10px;border-radius:8px;cursor:pointer;font-size:13px;font-family:Geist,sans-serif;font-weight:500;text-align:center;'
  document.getElementById('gold-dir-buy').style.cssText  = base + (d==='buy'  ? 'border:1px solid rgba(52,211,153,.3);background:rgba(52,211,153,.1);color:#34d399;' : 'border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:#71717a;')
  document.getElementById('gold-dir-sell').style.cssText = base + (d==='sell' ? 'border:1px solid rgba(248,113,113,.3);background:rgba(248,113,113,.1);color:#f87171;' : 'border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:#71717a;')
  updateGoldCalcs()
}

// ══════════════════════════════════════════════════════════════
// PHASE BADGE (Gold)
// ══════════════════════════════════════════════════════════════
function phaseBadge(phase) {
  const map = {
    teaser:      ['phase-teaser', 'Teaser'],
    open:        ['phase-open',   'Ouvert ●'],
    tp1_reached: ['phase-tp1',    'TP1 ✓'],
    tp2_reached: ['phase-tp2',    'TP2 ✓'],
    tp3_reached: ['phase-tp3',    'TP3 ✓'],
    sl_touched:  ['phase-sl',     'SL ✗'],
    closed:      ['phase-closed', 'Clôturé'],
    cancelled:   ['phase-closed', 'Annulé'],
  }
  const [cls, label] = map[phase] || ['phase-closed', phase || '—']
  return `<span class="badge ${cls}" style="font-size:10px;">${label}</span>`
}

// ══════════════════════════════════════════════════════════════
// CONFIANCE (Gold)
// ══════════════════════════════════════════════════════════════
let goldConfidence = 3

function setConfidence(v) {
  goldConfidence = v
  document.querySelectorAll('.conf-btn').forEach(btn => {
    const val = parseInt(btn.dataset.val)
    const on  = val === v
    btn.style.cssText = `flex:1;padding:6px;border-radius:7px;cursor:pointer;font-size:13px;border:${on?'1px solid rgba(251,191,36,.3)':'1px solid rgba(255,255,255,.08)'};background:${on?'rgba(251,191,36,.1)':'rgba(255,255,255,.04)'};color:${on?'#fbbf24':'#71717a'};${on?'font-weight:500;':''}`
  })
  updateGoldCalcs()
}

// ══════════════════════════════════════════════════════════════
// CALCULS GOLD TEMPS RÉEL
// ══════════════════════════════════════════════════════════════
function updateGoldCalcs() {
  const entry = parseFloat(document.getElementById('gold-entry')?.value) || 0
  const sl    = parseFloat(document.getElementById('gold-sl')?.value)    || 0
  const tp1   = parseFloat(document.getElementById('gold-tp1')?.value)   || 0
  const tp2   = parseFloat(document.getElementById('gold-tp2')?.value)   || 0
  const tp3   = parseFloat(document.getElementById('gold-tp3')?.value)   || 0
  if (!entry || !sl) return

  const mult   = 10
  const slPips = Math.abs(entry - sl) * mult
  const t1Pips = tp1 ? Math.abs(tp1 - entry) * mult : 0
  const t2Pips = tp2 ? Math.abs(tp2 - entry) * mult : 0
  const t3Pips = tp3 ? Math.abs(tp3 - entry) * mult : 0
  const pipVal = 1.0
  const confMap = { 1:0.5, 2:0.75, 3:1.0, 4:1.5, 5:2.0 }
  const riskPct = confMap[goldConfidence] || 1.0

  function calcForCap(cap, targetPips) {
    const risk = cap * riskPct / 100
    const lot  = slPips > 0 ? (risk / (slPips * pipVal)).toFixed(2) : 0
    const gain = (parseFloat(lot) * targetPips * pipVal).toFixed(2)
    return { lot, gain, loss: (risk).toFixed(2) }
  }

  const c1 = calcForCap(300,  t1Pips)
  const c2 = calcForCap(1000, t2Pips || t1Pips)
  const c3 = calcForCap(3000, t3Pips || t2Pips || t1Pips)

  ;[['gold-calc-tp1', c1], ['gold-calc-tp2', c2], ['gold-calc-tp3', c3]].forEach(([id, c]) => {
    const el = document.getElementById(id)
    if (el) el.innerHTML = `Lot: <b>${c.lot}</b><br><span style="color:#f87171;">SL: -${c.loss}$</span><br><span style="color:#34d399;">TP: +${c.gain}$</span>`
  })

  const slEl = document.getElementById('gold-sl-pips')
  const t1El = document.getElementById('gold-tp1-pips')
  const rrEl = document.getElementById('gold-rr')
  if (slEl) slEl.textContent = slPips.toFixed(1)
  if (t1El) t1El.textContent = t1Pips.toFixed(1)
  if (rrEl) rrEl.textContent = slPips > 0 && t1Pips > 0 ? `1:${(t1Pips/slPips).toFixed(1)}` : '—'
}

// ══════════════════════════════════════════════════════════════
// DASHBOARD JOURNAL
// ══════════════════════════════════════════════════════════════
const DAYS_FR = ['Dim','Lun','Mar','Mer','Jeu','Ven','Sam']
let _avgCapital = 1000

async function loadDashboardStats() {
  const period = document.getElementById('period-select').value
  try {
    const d = await apiFetch(`/stats?period=${period}`)
    _avgCapital = d.avg_member_capital || 1000
    document.getElementById('stats-grid').innerHTML = `
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Trades publiés</p><p class="text-xl font-light text-white tabular-nums">${d.trades_published??0}</p><p class="text-[10px] mt-1" style="color:#52525b;">${period==='week'?'cette semaine':period==='month'?'ce mois':'total'}</p></div>
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Win rate admin</p><p class="text-xl font-light tabular-nums pnl-pos">${d.win_rate_admin??'—'}%</p><div class="pbar mt-2"><div class="pbar-fill" style="width:${d.win_rate_admin??0}%;background:#34d399;"></div></div></div>
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Taux engagement</p><p class="text-xl font-light tabular-nums" style="color:#38bdf8;">${d.engagement_rate??'—'}%</p><p class="text-[10px] mt-1" style="color:#52525b;">répondent "Je suis dedans"</p></div>
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Journaux collectés</p><p class="text-xl font-light text-white tabular-nums">${d.journals_collected??0}</p></div>
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Trades ouverts</p><p class="text-xl font-light tabular-nums" style="color:#38bdf8;">${d.open_trades_count??0}</p><span class="flex items-center gap-1.5 mt-1" style="font-size:10px;color:#38bdf8;"><span class="live-dot pulse" style="width:5px;height:5px;animation:pulse 2s infinite;"></span>En cours</span></div>
    `
    renderWeeklyChart(d.weekly_performance || [])
  } catch (e) { toast('Erreur stats', 'error') }
}

function renderWeeklyChart(data) {
  if (!data.length) { document.getElementById('weekly-chart').innerHTML = '<div class="text-center text-xs pt-6" style="color:#3f3f46;">Aucun trade cette semaine</div>'; return }
  const maxT = Math.max(...data.map(d => d.trades||1), 1)
  document.getElementById('weekly-chart').innerHTML = data.map(d => {
    const h   = Math.max(10, Math.round((d.trades/maxT)*100))
    const cls = (d.wins||0) >= (d.losses||0) ? 'win-bar' : 'loss-bar'
    const day = d.day ? DAYS_FR[new Date(d.day).getDay()] : '—'
    return `<div class="flex flex-col items-center gap-1 flex-1"><div class="chart-bar ${cls} w-full" style="height:${h}%;"></div><span class="text-[9px]" style="color:#52525b;">${day}</span></div>`
  }).join('')
}

// ══════════════════════════════════════════════════════════════
// SIGNAUX
// ══════════════════════════════════════════════════════════════
async function loadSignals() {
  try {
    const d       = await apiFetch('/signals?status=all&limit=20')
    const signals = d.signals || []
    if (!signals.length) {
      document.getElementById('signals-grid').innerHTML = '<div class="col-span-2 text-center text-xs py-8" style="color:#3f3f46;">Aucun signal</div>'
      return
    }
    document.getElementById('signals-grid').innerHTML = signals.map(renderSignalCard).join('')

    // Vérifier les comptes cramés pour les trades ouverts
    const openSignal = signals.find(s => s.status === 'open')
    if (openSignal) checkCramedAlerts()
  } catch (e) { toast('Erreur signaux', 'error') }
}

function renderSignalCard(s) {
  const isOpen = s.status === 'open'
  const isWin  = s.close_result === 'tp'
  const isLoss = s.close_result === 'sl'
  const cls    = isOpen ? 'open-sig' : isWin ? 'win' : isLoss ? 'loss' : ''
  const accent = isOpen ? '#38bdf8' : isWin ? '#34d399' : isLoss ? '#f87171' : '#fbbf24'
  const dBadge = dirBadge(s.direction)

  let badge = ''
  if (isOpen)                          badge = `<span class="flex items-center gap-1 text-[10px]" style="color:#38bdf8;"><span class="live-dot pulse" style="width:5px;height:5px;animation:pulse 2s infinite;"></span>En cours</span>`
  else if (s.close_result === 'tp')    badge = `<span class="badge badge-green" style="font-size:10px;">TP ✓</span>`
  else if (s.close_result === 'sl')    badge = `<span class="badge badge-red" style="font-size:10px;">SL ✗</span>`
  else if (s.close_result === 'partial') badge = `<span class="badge badge-amber" style="font-size:10px;">Partiel</span>`

  const pctDisplay = s.result_percent != null
    ? `<p class="text-lg font-light tabular-nums ${s.result_percent>=0?'pnl-pos':'pnl-neg'}">${s.result_percent>0?'+':''}${s.result_percent}%</p>` : ''

  const actionBtns = isOpen ? `
    <button class="btn-icon" onclick="event.stopPropagation();openFollowupModal(${s.id})" title="Commentaire" style="color:#38bdf8;background:rgba(56,189,248,.06);border:1px solid rgba(56,189,248,.15);">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
    </button>
    <button class="btn-icon" onclick="event.stopPropagation();openCloseModal(${s.id})" title="Clôturer" style="color:#fbbf24;background:rgba(251,191,36,.06);border:1px solid rgba(251,191,36,.2);">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
    </button>` : ''

  return `
  <div class="signal-card ${cls} fadein" onclick="openSignalDetail(${s.id})">
    <div class="signal-accent" style="background:${accent};"></div>
    <div class="p-4">
      <div class="flex items-start justify-between mb-3">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <span class="text-base font-medium text-white" style="font-family:'Geist Mono',monospace;">${s.pair}</span>
            ${dBadge} ${badge}
          </div>
          <p class="text-[11px]" style="color:#52525b;">${s.timeframe||'H4'} · ${fmtDate(s.published_at)} · ${s.category||'—'}</p>
        </div>
        <div class="flex items-center gap-1.5">${pctDisplay}${actionBtns}</div>
      </div>
      <div class="grid grid-cols-4 gap-2 mb-3">
        <div class="stat-mini text-center" style="padding:7px 5px;"><p class="text-[9px] mb-1" style="color:#52525b;">Entrée</p><p class="text-xs tabular-nums text-white" style="font-family:'Geist Mono',monospace;">${s.entry_price}</p></div>
        ${s.tp1?`<div class="stat-mini text-center" style="padding:7px 5px;"><p class="text-[9px] mb-1" style="color:#34d399;">TP1</p><p class="text-xs tabular-nums pnl-pos" style="font-family:'Geist Mono',monospace;">${s.tp1}</p></div>`:'<div></div>'}
        ${s.close_price?`<div class="stat-mini text-center" style="padding:7px 5px;"><p class="text-[9px] mb-1" style="color:#52525b;">Clôture</p><p class="text-xs tabular-nums ${s.result_percent>=0?'pnl-pos':'pnl-neg'}" style="font-family:'Geist Mono',monospace;">${s.close_price}</p></div>`:'<div></div>'}
        ${s.sl?`<div class="stat-mini text-center" style="padding:7px 5px;"><p class="text-[9px] mb-1" style="color:#f87171;">SL</p><p class="text-xs tabular-nums pnl-neg" style="font-family:'Geist Mono',monospace;">${s.sl}</p></div>`:'<div></div>'}
      </div>
      <div style="background:rgba(255,255,255,.025);border-radius:8px;padding:10px 12px;">
        <div class="flex items-center justify-between mb-2">
          <span class="text-[11px] font-medium text-zinc-300">Participation</span>
          <span class="text-[10px]" style="color:#52525b;">${s.count_in||0} in / ${s.total_participants||0}</span>
        </div>
        <div class="part-bar mb-2">
          <div class="part-seg" style="width:${s.total_participants?Math.round((s.count_in||0)/s.total_participants*100):0}%;background:#34d399;border-radius:99px 0 0 99px;"></div>
          <div class="part-seg" style="width:${s.total_participants?Math.round((s.count_out||0)/s.total_participants*100):0}%;background:#f87171;"></div>
          <div class="part-seg" style="flex:1;background:rgba(255,255,255,.06);border-radius:0 99px 99px 0;"></div>
        </div>
        <div class="eng-grid">
          <div class="eng-cell" style="background:rgba(52,211,153,.07);border:1px solid rgba(52,211,153,.15);"><p class="text-sm font-light tabular-nums" style="color:#34d399;">${s.count_in||0}</p><p class="text-[9px] mt-0.5" style="color:#34d399;opacity:.8;">✅ Dedans</p></div>
          <div class="eng-cell" style="background:rgba(248,113,113,.07);border:1px solid rgba(248,113,113,.15);"><p class="text-sm font-light tabular-nums" style="color:#f87171;">${s.count_out||0}</p><p class="text-[9px] mt-0.5" style="color:#f87171;opacity:.8;">❌ Pas pris</p></div>
          <div class="eng-cell" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);"><p class="text-sm font-light tabular-nums" style="color:#71717a;">${s.journals_submitted||0}</p><p class="text-[9px] mt-0.5" style="color:#52525b;">📋 Journal</p></div>
        </div>
      </div>
    </div>
  </div>`
}

// ══════════════════════════════════════════════════════════════
// ALERTE COMPTES CRAMÉS
// ══════════════════════════════════════════════════════════════
async function checkCramedAlerts() {
  try {
    const session = await goldFetch('/sessions/active').catch(() => null)
    if (!session) return
    const d = await goldFetch(`/sessions/${session.id}/danger-check`)
    if (!d || d.total_danger === 0) return
    const banner  = document.getElementById('alert-cramed-banner')
    const content = document.getElementById('alert-cramed-content')
    if (!banner || !content) return
    let msg = `<b>${d.total_danger} compte(s) en danger</b> si le SL est touché sur le trade actif.`
    if (d.already_cramed?.length) msg += ` <b style="color:#f87171;">${d.already_cramed.length} compte(s) se crament.</b>`
    if (d.simulation_danger?.length) msg += ` ${d.simulation_danger.length} compte(s) simulation affectés.`
    content.innerHTML = `<span class="text-xs" style="color:#fbbf24;">${msg}</span>`
    banner.style.display = 'flex'
  } catch(e) {}
}

// ══════════════════════════════════════════════════════════════
// SIGNAL DRAWER
// ══════════════════════════════════════════════════════════════
let _currentSignalId = null
let _drawerWS        = null

function closeAllDrawers() {
  if (_drawerWS) { _drawerWS.close(); _drawerWS = null }
  ;['signal-drawer','member-drawer','gold-session-drawer'].forEach(id =>
    document.getElementById(id)?.classList.remove('open')
  )
  document.getElementById('drawer-overlay')?.classList.remove('open')
}

async function openSignalDetail(signalId) {
  _currentSignalId = signalId
  document.getElementById('signal-drawer').classList.add('open')
  document.getElementById('drawer-overlay').classList.add('open')
  document.getElementById('signal-drawer-content').innerHTML = '<div class="text-center text-xs pt-16" style="color:#3f3f46;">Chargement...</div>'
  try {
    const s  = await apiFetch(`/signals/${signalId}`)
    const js = s.journal_stats || {}
    const isOpen = s.status === 'open'
    const countIn   = s.count_in || 0
    const countOut  = s.count_out || 0
    const totalPart = s.participations?.length || 0
    const pctIn     = totalPart > 0 ? Math.round(countIn/totalPart*100) : 0
    const pctOut    = totalPart > 0 ? Math.round(countOut/totalPart*100) : 0

    document.getElementById('signal-drawer-header').innerHTML = `
      <div>
        <div class="flex items-center gap-2">
          <p class="text-sm font-medium text-white">${s.pair} ${dirLabel(s.direction)}</p>
          ${isOpen?'<span class="live-dot pulse" style="width:6px;height:6px;border-radius:50%;background:#38bdf8;animation:pulse 2s infinite;"></span>':''}
        </div>
        <p class="text-[11px] mt-0.5" style="color:#52525b;">${fmtDate(s.published_at)} · ${s.timeframe} · ${s.result_percent!=null?(s.result_percent>0?'+':'')+s.result_percent+'%':'En cours'}</p>
      </div>`

    const participantsList = (s.participations||[]).slice(0,8).map(p => `
      <div class="flex items-center justify-between py-1.5" style="border-bottom:1px solid rgba(255,255,255,.04);">
        <div class="flex items-center gap-2">
          <div class="av av-default" style="font-size:9px;width:22px;height:22px;">${initials(p.name)}</div>
          <span class="text-xs text-zinc-300">${p.name||'#'+p.user_id}</span>
        </div>
        <span class="${p.response==='in'?'badge-green':'badge-red'} badge" style="font-size:9px;">${p.response==='in'?'✅ Dedans':'❌ Pas pris'}</span>
      </div>`).join('')

    const followups = (s.followup_comments||[]).map(fc => `
      <div style="font-size:11px;padding:8px 10px;background:rgba(255,255,255,.025);border-radius:7px;margin-bottom:6px;border:1px solid rgba(255,255,255,.06);">
        <div class="flex items-center justify-between mb-1">
          <span style="color:#a78bfa;font-weight:500;">${fc.type}</span>
          <span style="color:#3f3f46;">${fmtDate(fc.sent_at)}</span>
        </div>
        <p style="color:#a1a1aa;">${fc.message}</p>
      </div>`).join('')

    const journalBlock = js.total_journals ? `
      <div>
        <p class="slabel">Résultats membres (${js.total_journals} réponses)</p>
        <div class="exit-bar mb-3">
          <div class="exit-row"><span style="color:#34d399;min-width:110px;">Discipliné TP</span><div class="exit-track"><div class="exit-fill" style="width:${Math.round((js.disciplined||0)/js.total_journals*100)}%;background:#34d399;"></div></div><span style="color:#34d399;">${js.disciplined||0}</span></div>
          <div class="exit-row"><span style="color:#fbbf24;min-width:110px;">Sortie anticipée</span><div class="exit-track"><div class="exit-fill" style="width:${Math.round((js.early_exits||0)/js.total_journals*100)}%;background:#fbbf24;"></div></div><span style="color:#fbbf24;">${js.early_exits||0}</span></div>
          <div class="exit-row"><span style="color:#f87171;min-width:110px;">Ignore SL</span><div class="exit-track"><div class="exit-fill" style="width:${Math.round((js.sl_skips||0)/js.total_journals*100)}%;background:#f87171;"></div></div><span style="color:#f87171;">${js.sl_skips||0}</span></div>
        </div>
        <div class="flex gap-4 text-[11px]">
          <span style="color:#34d399;">Win : ${js.wins||0}</span>
          <span style="color:#f87171;">Loss : ${js.losses||0}</span>
          <span style="color:#38bdf8;">Moy. : ${js.avg_result_percent!=null?(js.avg_result_percent>0?'+':'')+js.avg_result_percent+'%':'—'}</span>
        </div>
      </div>` : `<div style="padding:12px;background:rgba(255,255,255,.02);border-radius:8px;text-align:center;"><p class="text-xs" style="color:#3f3f46;">Aucun journal soumis</p></div>`

    const resultBlock = !isOpen ? `
      <div style="background:${s.close_result==='tp'?'rgba(52,211,153,.07)':'rgba(248,113,113,.07)'};border:1px solid ${s.close_result==='tp'?'rgba(52,211,153,.25)':'rgba(248,113,113,.25)'};border-radius:9px;padding:14px;">
        <div class="flex items-center justify-between">
          <span class="text-xs font-medium" style="color:${s.close_result==='tp'?'#34d399':'#f87171'};">${s.close_result==='tp'?'✅ TP atteint':s.close_result==='sl'?'❌ SL touché':'⚡ Partiel'}</span>
          <span class="text-lg font-light tabular-nums ${s.result_percent>=0?'pnl-pos':'pnl-neg'}">${s.result_percent>0?'+':''}${s.result_percent}%</span>
        </div>
        <div class="flex gap-4 mt-2 text-[11px]">
          <span style="color:#52525b;">Entrée : <span style="font-family:'Geist Mono',monospace;color:#e4e4e7;">${s.entry_price}</span></span>
          <span style="color:#52525b;">Clôture : <span style="font-family:'Geist Mono',monospace;color:#e4e4e7;">${s.close_price}</span></span>
          <span style="color:#52525b;">Pips : <span class="${s.result_pips>=0?'pnl-pos':'pnl-neg'}">${s.result_pips>0?'+':''}${s.result_pips}</span></span>
        </div>
      </div>` : ''

    const actionBlock = isOpen ? `
      <div class="flex gap-2">
        <button class="btn-ghost flex-1 justify-center" style="font-size:11px;" onclick="openFollowupModal(${s.id});closeAllDrawers()">💬 Commentaire suivi</button>
        <button class="btn-primary flex-1 justify-center" style="font-size:11px;" onclick="openCloseModal(${s.id});closeAllDrawers()">✅ Clôturer le trade</button>
      </div>` : ''

    document.getElementById('signal-drawer-content').innerHTML = `
      ${resultBlock}
      <div>
        <p class="slabel">Niveaux</p>
        <div class="grid grid-cols-4 gap-2">
          <div class="stat-mini text-center"><p class="text-[9px] mb-1" style="color:#52525b;">Entrée</p><p class="text-sm tabular-nums text-white" style="font-family:'Geist Mono',monospace;">${s.entry_price}</p></div>
          ${s.tp1?`<div class="stat-mini text-center"><p class="text-[9px] mb-1" style="color:#34d399;">TP1</p><p class="text-sm tabular-nums pnl-pos" style="font-family:'Geist Mono',monospace;">${s.tp1}</p></div>`:'<div></div>'}
          ${s.sl?`<div class="stat-mini text-center"><p class="text-[9px] mb-1" style="color:#f87171;">SL</p><p class="text-sm tabular-nums pnl-neg" style="font-family:'Geist Mono',monospace;">${s.sl}</p></div>`:'<div></div>'}
          <div class="stat-mini text-center"><p class="text-[9px] mb-1" style="color:#52525b;">R:R</p><p class="text-sm tabular-nums text-white">1:${s.rr_ratio??'—'}</p></div>
        </div>
      </div>
      <div>
        <p class="slabel">Participation</p>
        <div class="part-bar mb-3" style="height:8px;">
          <div class="part-seg" style="width:${pctIn}%;background:#34d399;border-radius:99px 0 0 99px;"></div>
          <div class="part-seg" style="width:${pctOut}%;background:#f87171;"></div>
          <div class="part-seg" style="flex:1;background:rgba(255,255,255,.06);border-radius:0 99px 99px 0;"></div>
        </div>
        ${participantsList}
        ${totalPart>8?`<p class="text-[10px] mt-2" style="color:#3f3f46;">+${totalPart-8} autres</p>`:''}
      </div>
      ${journalBlock}
      ${followups?`<div><p class="slabel">Commentaires de suivi</p>${followups}</div>`:''}
      ${actionBlock}`
  } catch (e) { toast('Erreur signal','error') }
}

// ══════════════════════════════════════════════════════════════
// MODAL PUBLIER — SIGNAUX CLASSIQUES
// ══════════════════════════════════════════════════════════════
let pubStep = 1

function calcRR() {
  const e  = parseFloat(document.getElementById('sig-entry')?.value)
  const t  = parseFloat(document.getElementById('sig-tp1')?.value)
  const s  = parseFloat(document.getElementById('sig-sl')?.value)
  const el = document.getElementById('rr-display')
  if (!isNaN(e) && !isNaN(t) && !isNaN(s) && Math.abs(e-s) > 0) {
    el.textContent = `R:R 1:${(Math.abs(t-e)/Math.abs(e-s)).toFixed(1)}`
    el.style.color = '#38bdf8'
  } else { el.textContent = 'R:R —'; el.style.color = '#52525b' }
}

function updatePreview() {
  const pair  = document.getElementById('sig-pair')?.value||'—'
  const entry = document.getElementById('sig-entry')?.value||'—'
  const tp1   = document.getElementById('sig-tp1')?.value||'—'
  const sl    = document.getElementById('sig-sl')?.value||'—'
  const note  = document.getElementById('sig-note')?.value||''
  const el    = document.getElementById('tg-preview-msg')
  if (el) el.innerHTML = `📊 <b>Signal de Trading</b><br><br>🔷 Paire : <b>${pair}</b><br>Direction : <b>${dirLabel(tradeDir)}</b><br>🎯 Entrée : <b>${entry}</b><br>✅ TP1 : <b>${tp1}</b><br>❌ SL : <b>${sl}</b>${note?`<br><br><i>${note}</i>`:''}`
}

function nextStep() {
  if (pubStep === 1) {
    if (!document.getElementById('sig-entry').value.trim()) { toast("Prix d'entrée requis",'error'); return }
    goPubStep(2)
  } else if (pubStep === 2) {
    const cat = document.querySelector('#dest-block-category select')?.value
    if (!cat || cat === '') { toast('Sélectionnez une catégorie','error'); return }
    updatePreview(); goPubStep(3)
    const btn = document.getElementById('btn-next')
    btn.textContent = '📡 Publier le signal'
    btn.style.cssText += ';background:#34d399;color:#052e16;'
  } else { publishSignal() }
}

function prevStep() {
  if (pubStep > 1) {
    goPubStep(pubStep-1)
    const btn = document.getElementById('btn-next')
    btn.textContent = 'Continuer →'; btn.style.background=''; btn.style.color=''
  }
}

function goPubStep(n) {
  pubStep = n
  ;[1,2,3].forEach(i => {
    document.getElementById('pub-s'+i).style.display = i===n ? 'block' : 'none'
    const dot = document.getElementById('sdot-'+i)
    const lbl = document.getElementById('slbl-'+i)
    if (dot) dot.className = 'step-dot'+(i<n?' done':i===n?' active':'')
    if (lbl) lbl.style.color = i===n ? '#38bdf8' : i<n ? '#34d399' : '#3f3f46'
  })
  document.getElementById('btn-prev').style.display = n > 1 ? 'inline-flex' : 'none'
}

async function publishSignal() {
  const btn      = document.getElementById('btn-next')
  btn.disabled   = true; btn.textContent = 'Publication...'
  const category = document.querySelector('#dest-block-category select')?.value
  if (!category) { toast('Catégorie requise','error'); btn.disabled=false; btn.textContent='Continuer →'; return }
  const payload = {
    pair:        document.getElementById('sig-pair').value,
    direction:   tradeDir,
    timeframe:   document.getElementById('sig-tf').value,
    entry_price: parseFloat(document.getElementById('sig-entry').value),
    tp1:         parseFloat(document.getElementById('sig-tp1').value)||null,
    tp2:         parseFloat(document.getElementById('sig-tp2').value)||null,
    sl:          parseFloat(document.getElementById('sig-sl').value)||null,
    note:        document.getElementById('sig-note').value||null,
    category,
    ...(_uploadedMediaUrl ? {media_url: _uploadedMediaUrl} : {}),
  }
  try {
    await apiFetch('/signals', {method:'POST', body:JSON.stringify(payload)})
    toast('Signal publié ✓','success')
    closeModal('modal-publish'); resetPubModal()
    loadSignals(); loadDashboardStats()
  } catch(e) { toast('Erreur: '+e.message,'error') }
  finally { btn.disabled=false; btn.textContent='Continuer →'; btn.style.background=''; btn.style.color='' }
}

function resetPubModal() {
  pubStep = 1; goPubStep(1)
  document.getElementById('btn-next').textContent = 'Continuer →'
  document.getElementById('btn-next').style.background = ''
  document.getElementById('btn-next').style.color = ''
  ;['sig-entry','sig-tp1','sig-tp2','sig-sl','sig-note'].forEach(id => { const e=document.getElementById(id); if(e)e.value='' })
  setDir('buy'); _removeUpload()
}

// ══════════════════════════════════════════════════════════════
// PUBLIER TRADE GOLD
// ══════════════════════════════════════════════════════════════
async function publishGoldSession() {
  const entry = document.getElementById('gold-entry')?.value
  const sl    = document.getElementById('gold-sl')?.value
  const tp1   = document.getElementById('gold-tp1')?.value
  if (!entry || !sl || !tp1) { toast('Entrée, SL et TP1 requis','error'); return }
  const payload = {
    direction:        goldDir,
    entry_price:      parseFloat(entry),
    sl:               parseFloat(sl),
    tp1:              parseFloat(tp1),
    tp2:              parseFloat(document.getElementById('gold-tp2')?.value)||null,
    tp3:              parseFloat(document.getElementById('gold-tp3')?.value)||null,
    timeframe:        document.getElementById('gold-tf')?.value||'M15',
    confidence_level: goldConfidence,
    note:             document.getElementById('gold-note')?.value||null,
    category:         document.getElementById('gold-category')?.value||'clients_actifs',
    send_teaser:      true,
    screenshot_url:   _goldUploadedMediaUrl||null,
  }
  try {
    await goldFetch('/sessions', {method:'POST', body:JSON.stringify(payload)})
    toast('Session Gold créée — teaser en cours d\'envoi ✓','success')
    closeModal('modal-gold-publish')
    ;['gold-entry','gold-sl','gold-tp1','gold-tp2','gold-tp3','gold-note'].forEach(id => { const e=document.getElementById(id); if(e)e.value='' })
    setGoldDir('buy'); setConfidence(3); updateGoldCalcs()
    loadGoldDashboard()
  } catch(e) { toast('Erreur Gold: '+e.message,'error') }
}

// ══════════════════════════════════════════════════════════════
// GOLD DASHBOARD
// ══════════════════════════════════════════════════════════════
let _goldPriceInterval = null

async function loadGoldDashboard() {
  const main = document.getElementById('gold-dashboard-main')
  main.innerHTML = '<div class="text-center text-xs pt-16" style="color:#3f3f46;">Chargement...</div>'
  try {
    const d       = await goldFetch('/dashboard')
    const session = d.active_session
    const season  = d.active_season
    const price   = d.live_price
    const sims    = d.simulation_accounts || []
    const recent  = d.recent_sessions || []
    const stats   = d.season_stats

    // Prix dans le header
    const hTicker = document.getElementById('gold-live-price-header')
    if (hTicker && price) { hTicker.textContent = parseFloat(price).toFixed(2); hTicker.className='ticker-live up' }

    // Session active
    const sessionHtml = session ? (() => {
      const isActive = ['teaser','open','tp1_reached','tp2_reached'].includes(session.current_phase)
      return `
        <div class="card-gold p-5">
          <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
              <div class="live-dot gold pulse" style="width:8px;height:8px;animation:pulse 2s infinite;"></div>
              <p class="text-sm font-medium text-white">Session active — XAU/USD</p>
              ${phaseBadge(session.current_phase)}
            </div>
            <div class="flex items-center gap-2">
              <button class="btn-ghost" style="font-size:11px;" onclick="openGoldSessionDrawer(${session.id})">Détail →</button>
              ${isActive?`<button class="btn-gold" style="font-size:11px;" onclick="openGoldCloseModal(${session.id})">Clôturer</button>`:''}
            </div>
          </div>
          <div class="flex items-center flex-wrap gap-4 mb-4">
            ${dirBadge(session.direction)}
            <span class="text-sm font-mono text-white">Entrée : ${session.entry_price}</span>
            ${session.tp1?`<span style="color:#34d399;font-family:'Geist Mono',monospace;font-size:12px;">TP1: ${session.tp1}</span>`:''}
            ${session.tp2?`<span style="color:#38bdf8;font-family:'Geist Mono',monospace;font-size:12px;">TP2: ${session.tp2}</span>`:''}
            ${session.tp3?`<span style="color:#a78bfa;font-family:'Geist Mono',monospace;font-size:12px;">TP3: ${session.tp3}</span>`:''}
            <span style="color:#f87171;font-family:'Geist Mono',monospace;font-size:12px;">SL: ${session.sl}</span>
          </div>
          <div class="grid grid-cols-5 gap-3 mb-4">
            <div class="agg-card neutral text-center"><p class="text-[10px] mb-1" style="color:#52525b;">Confirmés</p><p class="text-xl font-light text-white">${session.total_members_in||0}</p></div>
            <div class="agg-card neutral text-center"><p class="text-[10px] mb-1" style="color:#fbbf24;">Lots engagés</p><p class="text-xl font-light tabular-nums" style="color:#fbbf24;">${(session.total_lots_engaged||0).toFixed(2)}</p></div>
            <div class="agg-card loss text-center"><p class="text-[10px] mb-1" style="color:#f87171;">Risque SL</p><p class="text-xl font-light tabular-nums pnl-neg">-${(session.estimated_loss_sl||0).toFixed(0)}$</p></div>
            <div class="agg-card gain text-center"><p class="text-[10px] mb-1" style="color:#34d399;">Gain TP1</p><p class="text-xl font-light tabular-nums pnl-pos">+${(session.estimated_gain_tp1||0).toFixed(0)}$</p></div>
            <div class="agg-card gain text-center"><p class="text-[10px] mb-1" style="color:#34d399;">Gain TP2</p><p class="text-xl font-light tabular-nums pnl-pos">+${(session.estimated_gain_tp2||0).toFixed(0)}$</p></div>
          </div>
          ${isActive?`
          <div class="pt-4" style="border-top:1px solid rgba(251,191,36,.15);">
            <p class="text-[10px] mb-2" style="color:#52525b;">Déclencher manuellement</p>
            <div class="flex gap-2 flex-wrap">
              <button class="btn-ghost" style="font-size:11px;color:#34d399;border-color:rgba(52,211,153,.3);" onclick="triggerGoldTP(${session.id},1)">✅ TP1</button>
              ${session.tp2?`<button class="btn-ghost" style="font-size:11px;color:#38bdf8;border-color:rgba(56,189,248,.3);" onclick="triggerGoldTP(${session.id},2)">🎯 TP2</button>`:''}
              ${session.tp3?`<button class="btn-ghost" style="font-size:11px;color:#a78bfa;border-color:rgba(167,139,250,.3);" onclick="triggerGoldTP(${session.id},3)">🏆 TP3</button>`:''}
              <button class="btn-ghost" style="font-size:11px;color:#f87171;border-color:rgba(248,113,113,.3);" onclick="triggerGoldSL(${session.id})">❌ SL touché</button>
            </div>
          </div>`:''}
        </div>`
    })() : `
      <div class="card p-8 text-center">
        <p class="text-sm font-medium text-zinc-300 mb-2">Aucune session Gold active</p>
        <p class="text-xs mb-4" style="color:#52525b;">Créez un nouveau trade Gold pour démarrer le flux</p>
        <button class="btn-gold" onclick="openModal('modal-gold-publish')">Nouveau trade Gold →</button>
      </div>`

    // Saison + prix
    const ss       = stats?.session_stats || {}
    const winRate  = ss.total_trades > 0 ? Math.round((ss.wins||0)/ss.total_trades*100) : 0
    const seasonHtml = season ? `
      <div class="card p-5">
        <div class="flex items-center justify-between mb-4">
          <div>
            <p class="text-sm font-medium text-white">${season.name}</p>
            <p class="text-[11px] mt-0.5" style="color:#52525b;">${fmtDate(season.start_date)} · Saison active</p>
          </div>
          <span class="badge badge-gold">Active</span>
        </div>
        <div class="grid grid-cols-4 gap-3">
          <div class="stat-mini text-center"><p class="text-sm font-light text-white">${ss.total_trades||0}</p><p class="text-[9px] mt-1" style="color:#52525b;">Trades</p></div>
          <div class="stat-mini text-center"><p class="text-sm font-light pnl-pos">${winRate}%</p><p class="text-[9px] mt-1" style="color:#52525b;">Win rate</p></div>
          <div class="stat-mini text-center"><p class="text-sm font-light" style="color:#34d399;">${ss.wins||0}</p><p class="text-[9px] mt-1" style="color:#34d399;">Wins</p></div>
          <div class="stat-mini text-center"><p class="text-sm font-light" style="color:#f87171;">${ss.losses||0}</p><p class="text-[9px] mt-1" style="color:#f87171;">Losses</p></div>
        </div>
      </div>` : `<div class="card p-5 text-center"><p class="text-xs" style="color:#3f3f46;">Aucune saison active</p><button class="btn-gold mt-3" onclick="showMainView('gold-saisons',document.getElementById('nav-gold-saisons'))">Créer une saison →</button></div>`

    // Comptes sim
    const simHtml = sims.length ? `
      <div>
        <div class="flex items-center justify-between mb-3">
          <p class="text-xs font-medium text-zinc-300">Comptes simulation</p>
          <button class="btn-ghost" style="font-size:10px;" onclick="showMainView('gold-simulations',document.getElementById('nav-gold-simulations'))">Voir tout →</button>
        </div>
        <div class="grid grid-cols-3 gap-3">
          ${sims.map(acc => `
            <div class="sim-card" onclick="showMainView('gold-simulations',document.getElementById('nav-gold-simulations'))">
              <p class="text-xs font-medium text-zinc-200 mb-1">${acc.name}</p>
              <p class="text-lg font-light tabular-nums ${(acc.rendement_pct||0)>=0?'pnl-pos':'pnl-neg'}">${(acc.current_capital||0).toFixed(0)}$</p>
              <p class="text-[10px] mt-1" style="color:${(acc.rendement_pct||0)>=0?'#34d399':'#f87171'};">${(acc.rendement_pct||0)>0?'+':''}${(acc.rendement_pct||0).toFixed(2)}%</p>
              <p class="text-[9px] mt-1" style="color:#52525b;">Initial: ${acc.initial_capital}$ · ${acc.total_trades||0} trades</p>
            </div>`).join('')}
        </div>
      </div>` : ''

    // Sessions récentes
    const recentHtml = recent.length ? `
      <div>
        <p class="text-xs font-medium text-zinc-300 mb-3">Sessions récentes</p>
        <div class="card overflow-hidden">
          ${recent.map(s => `
            <div class="gold-session-row" onclick="openGoldSessionDrawer(${s.id})">
              <div style="width:100px;">${dirBadge(s.direction)}</div>
              <span class="text-xs font-mono text-white" style="width:80px;">${s.entry_price}</span>
              <div style="width:100px;">${phaseBadge(s.current_phase)}</div>
              <span class="text-[11px] flex-1" style="color:#52525b;">${s.confirmed_members||0} membres</span>
              <span class="text-[10px]" style="color:#3f3f46;">${fmtDate(s.created_at)}</span>
            </div>`).join('')}
        </div>
      </div>` : ''

    main.innerHTML = `
      <div class="flex flex-col gap-4">
        ${sessionHtml}
        <div class="grid grid-cols-2 gap-4">
          ${seasonHtml}
          <div class="card p-5">
            <div class="flex items-center justify-between mb-3">
              <p class="text-xs font-medium text-zinc-300">Prix XAU/USD live</p>
              <span class="flex items-center gap-1.5 text-[10px]" style="color:#fbbf24;"><span class="live-dot gold pulse" style="width:5px;height:5px;animation:pulse 2s infinite;"></span>Binance</span>
            </div>
            <p class="text-3xl font-light tabular-nums" style="color:#fbbf24;font-family:'Geist Mono',monospace;" id="gold-live-price-main">${price?parseFloat(price).toFixed(2):'—'}</p>
            <p class="text-[10px] mt-2" style="color:#52525b;">Mise à jour toutes les 30 secondes</p>
          </div>
        </div>
        ${simHtml}
        ${recentHtml}
      </div>`

    startGoldPriceTicker()
  } catch(e) {
    main.innerHTML = `<div class="text-center text-xs pt-16" style="color:#f87171;">Erreur: ${e.message}</div>`
  }
}

function startGoldPriceTicker() {
  if (_goldPriceInterval) clearInterval(_goldPriceInterval)
  _goldPriceInterval = setInterval(async () => {
    try {
      const d = await goldFetch('/price/live')
      const p = parseFloat(d.price).toFixed(2)
      ;['gold-live-price-header','gold-live-price-main'].forEach(id => {
        const el = document.getElementById(id)
        if (el) { el.textContent = p; el.className = 'ticker-live up' }
      })
    } catch(e) {}
  }, 30000)
}

// ══════════════════════════════════════════════════════════════
// GOLD SESSIONS — LISTE
// ══════════════════════════════════════════════════════════════
async function loadSeasonsForFilter() {
  try {
    const seasons = await goldFetch('/seasons')
    const sel = document.getElementById('gold-sessions-season-filter')
    if (!sel) return
    sel.innerHTML = '<option value="">Toutes les saisons</option>'
    seasons.forEach(s => {
      const opt = document.createElement('option')
      opt.value = s.id; opt.textContent = s.name; sel.appendChild(opt)
    })
  } catch(e) {}
}

async function loadGoldSessions() {
  const body     = document.getElementById('gold-sessions-body')
  const seasonId = document.getElementById('gold-sessions-season-filter')?.value || ''
  const phase    = document.getElementById('gold-sessions-phase-filter')?.value  || ''
  body.innerHTML = '<div class="p-8 text-center text-xs" style="color:#3f3f46;">Chargement...</div>'
  try {
    const qs       = `?limit=30&offset=0${seasonId?'&season_id='+seasonId:''}${phase?'&phase='+phase:''}`
    const d        = await goldFetch('/sessions'+qs)
    const sessions = d.sessions || []
    if (!sessions.length) { body.innerHTML = '<div class="p-8 text-center text-xs" style="color:#3f3f46;">Aucune session</div>'; return }
    body.innerHTML = sessions.map(s => `
      <div class="gold-session-row" onclick="openGoldSessionDrawer(${s.id})">
        <div style="width:80px;">${dirBadge(s.direction)}</div>
        <span class="text-xs font-mono text-white" style="width:90px;">${s.entry_price}</span>
        <div class="flex items-center gap-2 flex-1" style="font-size:11px;font-family:'Geist Mono',monospace;">
          ${s.tp1?`<span style="color:#34d399;">TP1:${s.tp1}</span>`:''}
          ${s.tp2?`<span style="color:#38bdf8;">TP2:${s.tp2}</span>`:''}
          ${s.tp3?`<span style="color:#a78bfa;">TP3:${s.tp3}</span>`:''}
          <span style="color:#f87171;">SL:${s.sl}</span>
        </div>
        <div style="width:90px;">${phaseBadge(s.current_phase)}</div>
        <span class="text-xs text-zinc-300" style="width:70px;">${s.total_members_in||0}</span>
        <span class="text-xs tabular-nums" style="color:#fbbf24;width:90px;font-family:'Geist Mono',monospace;">${(s.total_lots_engaged||0).toFixed(2)} lots</span>
        <span class="text-xs tabular-nums pnl-neg" style="width:90px;">-${(s.estimated_loss_sl||0).toFixed(0)}$</span>
        <span class="text-[10px]" style="color:#3f3f46;width:70px;">${fmtDate(s.created_at)}</span>
        <button class="btn-icon" style="width:22px;height:22px;" onclick="event.stopPropagation();openGoldSessionDrawer(${s.id})">
          <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
      </div>`).join('')
  } catch(e) { body.innerHTML = `<div class="p-8 text-center text-xs" style="color:#f87171;">Erreur: ${e.message}</div>` }
}

// ══════════════════════════════════════════════════════════════
// GOLD SESSION DRAWER
// ══════════════════════════════════════════════════════════════
let _currentGoldSessionId = null

async function openGoldSessionDrawer(sessionId) {
  _currentGoldSessionId = sessionId
  document.getElementById('gold-session-drawer').classList.add('open')
  document.getElementById('drawer-overlay').classList.add('open')
  document.getElementById('gold-session-drawer-content').innerHTML = '<div class="text-center text-xs pt-16" style="color:#3f3f46;">Chargement...</div>'
  document.getElementById('gold-session-drawer-actions').innerHTML = ''
  try {
    const s        = await goldFetch(`/sessions/${sessionId}`)
    const isActive = ['teaser','open','tp1_reached','tp2_reached'].includes(s.current_phase)

    document.getElementById('gold-session-drawer-header').innerHTML = `
      <div>
        <div class="flex items-center gap-2">
          <p class="text-sm font-medium text-white">XAU/USD ${dirLabel(s.direction)}</p>
          ${phaseBadge(s.current_phase)}
        </div>
        <p class="text-[11px] mt-0.5" style="color:#52525b;">${fmtDate(s.created_at)} · Confiance ⭐×${s.confidence_level||3}</p>
      </div>`

    const aggHtml = `
      <div>
        <p class="slabel">Agrégats temps réel</p>
        <div class="grid grid-cols-3 gap-2 mb-2">
          <div class="agg-card neutral text-center"><p class="text-[10px] mb-1" style="color:#52525b;">Confirmés</p><p class="text-xl font-light text-white">${s.total_members_in||0}</p></div>
          <div class="agg-card neutral text-center"><p class="text-[10px] mb-1" style="color:#fbbf24;">Lots</p><p class="text-xl font-light" style="color:#fbbf24;">${(s.total_lots_engaged||0).toFixed(2)}</p></div>
          <div class="agg-card loss text-center"><p class="text-[10px] mb-1" style="color:#f87171;">Risque SL</p><p class="text-xl font-light pnl-neg">-${(s.estimated_loss_sl||0).toFixed(0)}$</p></div>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <div class="agg-card gain text-center"><p class="text-[10px] mb-1" style="color:#34d399;">Gain TP1</p><p class="text-base font-light pnl-pos">+${(s.estimated_gain_tp1||0).toFixed(0)}$</p></div>
          <div class="agg-card gain text-center"><p class="text-[10px] mb-1" style="color:#34d399;">Gain TP2</p><p class="text-base font-light pnl-pos">+${(s.estimated_gain_tp2||0).toFixed(0)}$</p></div>
          <div class="agg-card gain text-center"><p class="text-[10px] mb-1" style="color:#a78bfa;">Gain TP3</p><p class="text-base font-light" style="color:#a78bfa;">+${(s.estimated_gain_tp3||0).toFixed(0)}$</p></div>
        </div>
      </div>`

    const tpDistHtml = (s.tp_distribution||[]).length ? `
      <div>
        <p class="slabel">Répartition par niveau</p>
        ${(s.tp_distribution||[]).map(tp => `
          <div style="padding:8px 10px;background:rgba(255,255,255,.025);border-radius:7px;margin-bottom:6px;">
            <div class="flex items-center justify-between mb-1">
              <span class="text-xs font-medium text-zinc-200">Niveau TP${tp.tp_level_assigned} — ${tp.members} membres</span>
              <span class="text-xs tabular-nums" style="color:#fbbf24;">${(tp.total_lots||0).toFixed(2)} lots</span>
            </div>
            <div class="flex gap-4 text-[10px]">
              <span style="color:#f87171;">Risque: -${(tp.total_risk||0).toFixed(0)}$</span>
              <span style="color:#34d399;">TP1: +${(tp.total_gain_tp1||0).toFixed(0)}$</span>
              ${tp.total_gain_tp2?`<span style="color:#38bdf8;">TP2: +${(tp.total_gain_tp2).toFixed(0)}$</span>`:''}
              ${tp.total_gain_tp3?`<span style="color:#a78bfa;">TP3: +${(tp.total_gain_tp3).toFixed(0)}$</span>`:''}
            </div>
          </div>`).join('')}
      </div>` : ''

    const entriesHtml = (s.entries||[]).length ? `
      <div>
        <p class="slabel">Membres confirmés (${(s.entries||[]).length})</p>
        ${(s.entries||[]).slice(0,10).map(e => `
          <div class="flex items-center gap-2 py-1.5" style="border-bottom:1px solid rgba(255,255,255,.04);">
            <div class="av av-gold" style="font-size:9px;width:22px;height:22px;">${initials(e.name)}</div>
            <span class="text-xs text-zinc-200 flex-1">${e.name||'#'+e.user_id}</span>
            <span class="text-[10px]" style="color:#fbbf24;">TP${e.tp_level_assigned}</span>
            <span class="text-[10px] font-mono" style="color:#52525b;">Lot: ${e.lot_calculated}</span>
            <span class="text-[10px] font-mono pnl-neg">${e.perte_sl}$</span>
            <span class="text-[10px] font-mono pnl-pos">+${e.gain_tp1}$</span>
          </div>`).join('')}
        ${(s.entries||[]).length>10?`<p class="text-[9px] mt-2" style="color:#3f3f46;">+${(s.entries||[]).length-10} autres</p>`:''}
      </div>` : ''

    const simHtml = (s.simulation_trades||[]).length ? `
      <div>
        <p class="slabel">Comptes simulation</p>
        ${(s.simulation_trades||[]).map(st => `
          <div class="flex items-center justify-between py-1.5" style="border-bottom:1px solid rgba(255,255,255,.04);">
            <span class="text-xs text-zinc-300">${st.account_name}</span>
            <span class="text-[10px] font-mono" style="color:#52525b;">${st.capital_before}$</span>
            <span class="text-[10px] font-mono" style="color:#fbbf24;">TP${st.tp_level_target}</span>
            <span class="text-[10px] font-mono pnl-neg">${st.perte_sl}$</span>
            <span class="badge ${st.status==='open'?'badge-sky':st.result_usd>0?'badge-green':'badge-red'}" style="font-size:9px;">${st.status==='open'?'Ouvert':st.result_usd>=0?'+'+st.result_usd+'$':st.result_usd+'$'}</span>
          </div>`).join('')}
      </div>` : ''

    document.getElementById('gold-session-drawer-content').innerHTML = `
      <div>
        <p class="slabel">Niveaux</p>
        <div class="flex items-center gap-3 flex-wrap text-[12px] font-mono">
          <span class="text-white">Entrée: ${s.entry_price}</span>
          ${s.tp1?`<span style="color:#34d399;">TP1: ${s.tp1}</span>`:''}
          ${s.tp2?`<span style="color:#38bdf8;">TP2: ${s.tp2}</span>`:''}
          ${s.tp3?`<span style="color:#a78bfa;">TP3: ${s.tp3}</span>`:''}
          <span style="color:#f87171;">SL: ${s.sl}</span>
          ${s.sl_pips?`<span style="color:#52525b;">(${s.sl_pips} pips SL)</span>`:''}
        </div>
      </div>
      ${aggHtml}
      ${tpDistHtml}
      ${entriesHtml}
      ${simHtml}`

    if (isActive) {
      document.getElementById('gold-session-drawer-actions').innerHTML = `
        <button class="btn-ghost flex-1 justify-center" style="font-size:11px;" onclick="triggerGoldTP(${s.id},1)">✅ TP1</button>
        ${s.tp2?`<button class="btn-ghost flex-1 justify-center" style="font-size:11px;color:#38bdf8;" onclick="triggerGoldTP(${s.id},2)">🎯 TP2</button>`:''}
        ${s.tp3?`<button class="btn-ghost flex-1 justify-center" style="font-size:11px;color:#a78bfa;" onclick="triggerGoldTP(${s.id},3)">🏆 TP3</button>`:''}
        <button class="btn-ghost flex-1 justify-center" style="font-size:11px;color:#f87171;" onclick="triggerGoldSL(${s.id})">❌ SL</button>
        <button class="btn-gold flex-1 justify-center" style="font-size:11px;" onclick="openGoldCloseModal(${s.id})">Clôturer →</button>`
    }
  } catch(e) { toast('Erreur session Gold', 'error') }
}

// ══════════════════════════════════════════════════════════════
// DÉCLENCHER TP / SL
// ══════════════════════════════════════════════════════════════
async function triggerGoldTP(sessionId, tpLevel) {
  if (!confirm(`Déclencher TP${tpLevel} — session #${sessionId} ?`)) return
  try {
    const r = await goldFetch(`/sessions/${sessionId}/tp/${tpLevel}`, {method:'POST'})
    toast(`TP${tpLevel} déclenché — ${(r.sent_exit||0)+(r.sent_continue||0)} membres notifiés ✓`, 'success')
    loadGoldDashboard(); closeAllDrawers()
  } catch(e) { toast('Erreur: '+e.message, 'error') }
}

async function triggerGoldSL(sessionId) {
  if (!confirm(`Déclencher le SL — session #${sessionId} ?`)) return
  try {
    const r = await goldFetch(`/sessions/${sessionId}/sl`, {method:'POST'})
    toast(`SL déclenché — ${r.notified||0} membres notifiés`, 'info')
    loadGoldDashboard(); closeAllDrawers()
  } catch(e) { toast('Erreur: '+e.message, 'error') }
}

// ══════════════════════════════════════════════════════════════
// GOLD CLOSE MODAL
// ══════════════════════════════════════════════════════════════
let _goldCloseType = 'tp1'

function openGoldCloseModal(sessionId) {
  _currentGoldSessionId = sessionId
  document.getElementById('gold-close-subtitle').textContent = `Session #${sessionId}`
  setGoldCloseType('tp1')
  openModal('modal-gold-close')
}

function setGoldCloseType(t) {
  _goldCloseType = t
  document.querySelectorAll('.gold-close-btn').forEach(btn => {
    const on = btn.dataset.t === t
    btn.style.borderWidth = on ? '2px' : '1px'
    btn.style.fontWeight  = on ? '600' : '400'
  })
}

async function confirmGoldClose() {
  if (!_currentGoldSessionId) return
  const btn = document.getElementById('btn-gold-close-confirm')
  btn.disabled = true; btn.textContent = 'Clôture...'
  try {
    await goldFetch(`/sessions/${_currentGoldSessionId}/close`, {
      method: 'POST', body: JSON.stringify({close_type: _goldCloseType})
    })
    toast(`Session clôturée (${_goldCloseType.toUpperCase()}) ✓`, 'success')
    closeModal('modal-gold-close'); closeAllDrawers()
    loadGoldDashboard(); loadGoldSessions()
  } catch(e) { toast('Erreur: '+e.message, 'error') }
  finally { btn.disabled=false; btn.textContent='Clôturer & notifier →' }
}

// ══════════════════════════════════════════════════════════════
// GOLD SAISONS
// ══════════════════════════════════════════════════════════════
let _resetSeasonId = null

async function loadGoldSaisons() {
  const main = document.getElementById('gold-saisons-main')
  main.innerHTML = '<div class="text-center text-xs pt-16" style="color:#3f3f46;">Chargement...</div>'
  try {
    const seasons = await goldFetch('/seasons')
    if (!seasons.length) {
      main.innerHTML = `<div class="text-center text-xs pt-16" style="color:#3f3f46;">Aucune saison — créez-en une !</div>`
      return
    }
    main.innerHTML = `<div class="flex flex-col gap-4">${seasons.map(s => {
      const isActive = s.status === 'active'
      const winRate  = s.trades_count > 0 ? Math.round((s.wins_count||0)/s.trades_count*100) : 0
      return `
        <div class="${isActive?'card-gold':'card'} p-5">
          <div class="flex items-center justify-between mb-4">
            <div>
              <div class="flex items-center gap-2 mb-1">
                <p class="text-sm font-medium text-white">${s.name}</p>
                <span class="badge ${isActive?'badge-gold':s.status==='reset'?'badge-amber':'badge-zinc'}" style="font-size:10px;">${isActive?'Active':s.status==='reset'?'Réinitialisée':'Clôturée'}</span>
              </div>
              <p class="text-[11px]" style="color:#52525b;">${fmtDate(s.start_date)}${s.closed_at?' → '+fmtDate(s.closed_at):''} · ${s.members_participated||0} membres</p>
            </div>
            ${isActive?`
              <div class="flex items-center gap-2">
                <button class="btn-ghost" style="font-size:11px;" onclick="loadSeasonDetail(${s.id})">Stats détaillées</button>
                <button style="padding:6px 12px;border-radius:8px;cursor:pointer;font-size:11px;background:rgba(248,113,113,.1);color:#f87171;border:1px solid rgba(248,113,113,.2);font-family:'Geist',sans-serif;" onclick="openResetSeason(${s.id})">Réinitialiser</button>
              </div>` : `<button class="btn-ghost" style="font-size:11px;" onclick="loadSeasonDetail(${s.id})">Stats →</button>`}
          </div>
          <div class="grid grid-cols-4 gap-3">
            <div class="stat-mini text-center"><p class="text-base font-light text-white">${s.trades_count||0}</p><p class="text-[9px] mt-1" style="color:#52525b;">Trades</p></div>
            <div class="stat-mini text-center"><p class="text-base font-light pnl-pos">${winRate}%</p><p class="text-[9px] mt-1" style="color:#52525b;">Win rate</p></div>
            <div class="stat-mini text-center"><p class="text-base font-light" style="color:#34d399;">${s.wins_count||0}</p><p class="text-[9px] mt-1" style="color:#34d399;">Wins</p></div>
            <div class="stat-mini text-center"><p class="text-base font-light" style="color:#f87171;">${s.losses_count||0}</p><p class="text-[9px] mt-1" style="color:#f87171;">Losses</p></div>
          </div>
          <div id="season-detail-${s.id}"></div>
        </div>`
    }).join('')}</div>`
  } catch(e) { main.innerHTML = `<div class="text-center text-xs pt-16" style="color:#f87171;">Erreur: ${e.message}</div>` }
}

async function loadSeasonDetail(seasonId) {
  const el = document.getElementById('season-detail-'+seasonId)
  if (!el) return
  el.innerHTML = '<div class="text-center text-xs py-4" style="color:#3f3f46;">Chargement stats...</div>'
  try {
    const d   = await goldFetch(`/seasons/${seasonId}/stats`)
    const ms  = d.member_stats || {}
    const sim = d.simulation_accounts || []
    const top = d.top_members || []
    el.innerHTML = `
      <div class="mt-4 pt-4" style="border-top:1px solid rgba(251,191,36,.15);">
        <div class="grid grid-cols-3 gap-3 mb-4">
          <div class="stat-mini"><p class="text-[10px] mb-1" style="color:#52525b;">Membres uniques</p><p class="text-lg font-light text-white">${ms.unique_members||0}</p></div>
          <div class="stat-mini"><p class="text-[10px] mb-1" style="color:#52525b;">Gains membres</p><p class="text-lg font-light ${(ms.total_gains_members||0)>=0?'pnl-pos':'pnl-neg'}">${(ms.total_gains_members||0)>0?'+':''}${(ms.total_gains_members||0).toFixed(0)}$</p></div>
          <div class="stat-mini"><p class="text-[10px] mb-1" style="color:#52525b;">Suivi instructions</p><p class="text-lg font-light" style="color:#38bdf8;">${(ms.instruction_follow_rate||0).toFixed(0)}%</p></div>
        </div>
        ${sim.length?`
          <p class="slabel mb-2">Comptes simulation</p>
          <div class="grid grid-cols-3 gap-2 mb-4">
            ${sim.map(a=>`
              <div class="sim-card text-center">
                <p class="text-xs font-medium text-zinc-200">${a.name}</p>
                <p class="text-base font-light ${(a.rendement_pct||0)>=0?'pnl-pos':'pnl-neg'}">${(a.rendement_pct||0)>0?'+':''}${(a.rendement_pct||0).toFixed(2)}%</p>
                <p class="text-[10px]" style="color:#52525b;">${a.initial_capital}$ → ${(a.current_capital||0).toFixed(0)}$</p>
              </div>`).join('')}
          </div>`:''}
        ${top.length?`
          <p class="slabel mb-2">Top membres</p>
          ${top.slice(0,5).map((m,i)=>`
            <div class="flex items-center justify-between py-1.5" style="border-bottom:1px solid rgba(255,255,255,.04);">
              <div class="flex items-center gap-2">
                <span style="font-size:12px;">${['🥇','🥈','🥉','4.','5.'][i]}</span>
                <span class="text-xs text-zinc-200">${m.name||'—'}</span>
              </div>
              <div class="flex items-center gap-3 text-[11px]">
                <span style="color:#52525b;">${m.trades} trades</span>
                <span class="${(m.total_usd||0)>=0?'pnl-pos':'pnl-neg'}">${(m.total_usd||0)>0?'+':''}${(m.total_usd||0).toFixed(0)}$</span>
              </div>
            </div>`).join('')}`:''}
      </div>`
  } catch(e) { if(el) el.innerHTML='' }
}

function openResetSeason(seasonId) {
  _resetSeasonId = seasonId; openModal('modal-reset-season')
}

async function createSeason() {
  const name    = document.getElementById('new-season-name')?.value.trim()
  const desc    = document.getElementById('new-season-desc')?.value.trim()
  const capital = parseFloat(document.getElementById('new-season-capital')?.value)||null
  if (!name) { toast('Nom requis','error'); return }
  try {
    await goldFetch('/seasons', {method:'POST', body:JSON.stringify({name, description:desc, initial_capital_ref:capital})})
    toast('Saison créée ✓','success'); closeModal('modal-new-season'); loadGoldSaisons()
  } catch(e) { toast('Erreur: '+e.message,'error') }
}

async function confirmResetSeason() {
  const name    = document.getElementById('reset-season-name')?.value.trim()
  const capital = parseFloat(document.getElementById('reset-season-capital')?.value)||null
  if (!name || !_resetSeasonId) { toast('Nom requis','error'); return }
  try {
    const r = await goldFetch(`/seasons/${_resetSeasonId}/reset`, {method:'POST', body:JSON.stringify({new_season_name:name, new_initial_capital:capital})})
    toast(`Réinitialisée — ${r.accounts_reset} comptes remis à zéro ✓`,'success')
    closeModal('modal-reset-season'); loadGoldSaisons(); loadGoldSimulations()
  } catch(e) { toast('Erreur: '+e.message,'error') }
}

// ══════════════════════════════════════════════════════════════
// GOLD SIMULATIONS
// ══════════════════════════════════════════════════════════════
async function loadGoldSimulations() {
  const main = document.getElementById('gold-simulations-main')
  main.innerHTML = '<div class="text-center text-xs pt-16" style="color:#3f3f46;">Chargement...</div>'
  try {
    const accounts = await goldFetch('/simulations')
    if (!accounts.length) {
      main.innerHTML = `<div class="text-center text-xs pt-16" style="color:#3f3f46;">Aucun compte simulation<br><button class="btn-gold mt-4" onclick="openModal('modal-new-sim')">Créer un compte →</button></div>`
      return
    }
    main.innerHTML = `
      <div class="grid grid-cols-3 gap-4 mb-6">
        ${accounts.map(acc => `
          <div class="card p-5 cursor-pointer" style="transition:border-color .15s;" onmouseover="this.style.borderColor='rgba(251,191,36,.3)'" onmouseout="this.style.borderColor='rgba(255,255,255,.06)'" onclick="loadSimDetail(${acc.id})">
            <div class="flex items-center justify-between mb-3">
              <p class="text-sm font-medium text-white">${acc.name}</p>
              <span class="badge ${acc.is_active?'badge-gold':'badge-zinc'}" style="font-size:9px;">${acc.is_active?'Actif':'Inactif'}</span>
            </div>
            <p class="text-2xl font-light tabular-nums ${(acc.rendement_pct||0)>=0?'pnl-pos':'pnl-neg'}">${(acc.current_capital||0).toFixed(0)}$</p>
            <p class="text-[11px] mt-1" style="color:${(acc.rendement_pct||0)>=0?'#34d399':'#f87171'};">${(acc.rendement_pct||0)>0?'+':''}${(acc.rendement_pct||0).toFixed(2)}% depuis le départ</p>
            <p class="text-[10px] mt-2" style="color:#52525b;">Initial: ${acc.initial_capital}$ · ${acc.total_trades||0} trades</p>
            <div class="flex gap-3 mt-1 text-[10px]" style="color:#52525b;">
              <span style="color:#34d399;">W:${acc.wins||0}</span>
              <span style="color:#f87171;">L:${acc.losses||0}</span>
              <span>DD: ${(acc.max_drawdown_pct||0).toFixed(1)}%</span>
            </div>
            <p class="text-[9px] mt-1" style="color:#3f3f46;">${acc.season_name||'—'}</p>
          </div>`).join('')}
      </div>
      <div id="sim-detail-panel"></div>`
  } catch(e) { main.innerHTML = `<div class="text-center text-xs pt-16" style="color:#f87171;">Erreur: ${e.message}</div>` }
}

async function loadSimDetail(accountId) {
  const el = document.getElementById('sim-detail-panel')
  if (!el) return
  el.innerHTML = '<div class="text-center text-xs py-6" style="color:#3f3f46;">Chargement...</div>'
  try {
    const d      = await goldFetch(`/simulations/${accountId}`)
    const curve  = d.capital_curve || []
    const trades = d.trades || []

    let curveHtml = ''
    if (curve.length > 1) {
      const caps   = curve.map(c => c.capital)
      const minC   = Math.min(...caps)
      const maxC   = Math.max(...caps)
      const rangeC = maxC - minC || 1
      const pts    = curve.map((c,i) => `${i/(curve.length-1)*400},${80-((c.capital-minC)/rangeC*70)}`).join(' ')
      const strokeColor = (d.rendement_pct||0) >= 0 ? '#34d399' : '#f87171'
      curveHtml = `
        <div class="card p-4 mb-4">
          <p class="text-xs font-medium text-zinc-300 mb-3">Courbe de capital — ${d.name}</p>
          <svg width="100%" height="80" viewBox="0 0 400 80" preserveAspectRatio="none" style="display:block;">
            <polyline points="${pts}" style="fill:none;stroke:${strokeColor};stroke-width:2;"/>
            ${curve.map((c,i) => `<circle cx="${i/(curve.length-1)*400}" cy="${80-((c.capital-minC)/rangeC*70)}" r="3" fill="${(c.result_usd||0)>=0?'#34d399':'#f87171'}"/>`).join('')}
          </svg>
          <div class="flex justify-between mt-2">
            <span class="text-[10px]" style="color:#52525b;">Départ: ${d.initial_capital}$</span>
            <span class="text-[10px] ${(d.rendement_pct||0)>=0?'pnl-pos':'pnl-neg'}">${(d.rendement_pct||0)>0?'+':''}${(d.rendement_pct||0).toFixed(2)}% → ${(d.current_capital||0).toFixed(0)}$</span>
          </div>
        </div>`
    }

    el.innerHTML = `
      ${curveHtml}
      <div class="card overflow-hidden">
        <div class="px-4 py-3 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,.05);">
          <p class="text-xs font-medium text-zinc-300">Historique — ${d.name}</p>
          <span class="badge badge-zinc" style="font-size:9px;">${trades.length} trades</span>
        </div>
        ${trades.map(t => `
          <div class="flex items-center gap-3 px-4 py-2.5" style="border-bottom:1px solid rgba(255,255,255,.04);font-size:11px;">
            <span style="color:#52525b;width:70px;font-family:'Geist Mono',monospace;">${t.entry_price}</span>
            <div style="width:80px;">${dirBadge(t.direction)}</div>
            <span style="flex:1;color:#52525b;">TP${t.tp_level_target}</span>
            <span class="font-mono ${t.result_usd!=null?((t.result_usd||0)>=0?'pnl-pos':'pnl-neg'):'text-zinc-500'}">${t.result_usd!=null?((t.result_usd||0)>=0?'+':'')+t.result_usd.toFixed(2)+'$':'En cours'}</span>
            <span style="color:#52525b;width:70px;text-align:right;font-family:'Geist Mono',monospace;">${t.capital_after!=null?(t.capital_after).toFixed(0)+'$':'—'}</span>
            <span class="badge ${t.status==='open'?'badge-sky':(t.result_usd||0)>0?'badge-green':'badge-red'}" style="font-size:9px;">${t.status==='open'?'Ouvert':t.exit_tp_level?'TP'+t.exit_tp_level:'SL'}</span>
          </div>`).join('')}
        ${!trades.length?'<div class="p-6 text-center text-xs" style="color:#3f3f46;">Aucun trade joué sur ce compte</div>':''}
      </div>`
  } catch(e) { el.innerHTML = `<div class="text-xs" style="color:#f87171;">Erreur: ${e.message}</div>` }
}

async function createSimAccount() {
  const name    = document.getElementById('new-sim-name')?.value.trim()
  const capital = parseFloat(document.getElementById('new-sim-capital')?.value)
  const desc    = document.getElementById('new-sim-desc')?.value.trim()
  const risk    = parseFloat(document.getElementById('new-sim-risk')?.value)||1
  if (!name || !capital) { toast('Nom et capital requis','error'); return }
  try {
    await goldFetch('/simulations', {method:'POST', body:JSON.stringify({name, initial_capital:capital, description:desc, risk_pct_default:risk})})
    toast('Compte simulation créé ✓','success'); closeModal('modal-new-sim'); loadGoldSimulations()
  } catch(e) { toast('Erreur: '+e.message,'error') }
}

// ══════════════════════════════════════════════════════════════
// GOLD RÈGLES & MESSAGES
// ══════════════════════════════════════════════════════════════
async function loadGoldRegles() {
  const main = document.getElementById('gold-regles-main')
  main.innerHTML = '<div class="text-center text-xs pt-16" style="color:#3f3f46;">Chargement...</div>'
  try {
    const rules = await goldFetch('/rules')
    if (!rules.length) { main.innerHTML = '<div class="text-center text-xs pt-16" style="color:#3f3f46;">Aucune règle — créez-en une !</div>'; return }
    main.innerHTML = `
      <div style="background:rgba(251,191,36,.04);border:1px solid rgba(251,191,36,.12);border-radius:10px;padding:14px 16px;margin-bottom:16px;">
        <p class="text-xs font-medium mb-1" style="color:#fbbf24;">💡 Comment ça marche</p>
        <p class="text-[11px]" style="color:#71717a;">Chaque règle définit les messages envoyés automatiquement selon le capital du membre (seuils) et le niveau TP atteint. Modifiez directement les messages pour personnaliser l'expérience.</p>
      </div>
      <div class="flex flex-col gap-4">
        ${rules.map(r => {
          const tpColor = r.tp_level===1?'#34d399':r.tp_level===2?'#38bdf8':'#a78bfa'
          const tpBg    = r.tp_level===1?'rgba(52,211,153,.1)':r.tp_level===2?'rgba(56,189,248,.1)':'rgba(167,139,250,.1)'
          const tpBd    = r.tp_level===1?'rgba(52,211,153,.2)':r.tp_level===2?'rgba(56,189,248,.2)':'rgba(167,139,250,.2)'
          const msgs = [
            ['Teaser',       r.message_teaser],
            ['TP1 atteint',  r.message_tp1_reached],
            ['TP2 atteint',  r.message_tp2_reached],
            ['TP3 atteint',  r.message_tp3_reached],
            ['SL touché',    r.message_sl_touched],
            ['Break even',   r.message_breakeven],
            ['Confirmation', r.message_confirmation],
          ].filter(([,msg]) => msg)
          return `
            <div class="card p-5">
              <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                  <div style="padding:4px 12px;border-radius:6px;font-size:11px;font-weight:600;background:${tpBg};color:${tpColor};border:1px solid ${tpBd};">TP${r.tp_level}</div>
                  <p class="text-sm font-medium text-white">${r.rule_name}</p>
                  <span class="badge badge-zinc" style="font-size:9px;">${r.min_capital}$${r.max_capital?' – '+r.max_capital+'$':' +'}</span>
                  <span class="badge badge-amber" style="font-size:9px;">Risque ${r.risk_pct}%</span>
                </div>
                <div class="flex items-center gap-2">
                  <span class="badge ${r.is_active?'badge-green':'badge-zinc'}" style="font-size:9px;">${r.is_active?'Actif':'Inactif'}</span>
                  <button class="btn-ghost" style="font-size:11px;" onclick="editRule(${r.id})">✏️ Modifier</button>
                </div>
              </div>
              ${msgs.length ? `
                <div class="grid grid-cols-2 gap-3">
                  ${msgs.map(([label, msg]) => `
                    <div style="background:rgba(255,255,255,.025);border:1px solid rgba(255,255,255,.06);border-radius:8px;padding:10px 12px;">
                      <p class="text-[9px] mb-1.5" style="color:#52525b;text-transform:uppercase;letter-spacing:.05em;">${label}</p>
                      <p class="text-[11px]" style="color:#a1a1aa;line-height:1.5;">${msg.replace(/\n/g,'<br>').replace(/\*(.*?)\*/g,'<b style="color:#e4e4e7;">$1</b>').substring(0,120)}${msg.length>120?'…':''}</p>
                    </div>`).join('')}
                </div>` : `<p class="text-xs" style="color:#3f3f46;">Aucun message configuré pour cette règle.</p>`}
            </div>`
        }).join('')}
      </div>`
  } catch(e) { main.innerHTML = `<div class="text-center text-xs pt-16" style="color:#f87171;">Erreur: ${e.message}</div>` }
}

async function editRule(ruleId) {
  try {
    const rules = await goldFetch('/rules')
    const r = rules.find(x => x.id === ruleId)
    if (!r) return
    document.getElementById('rule-name').value        = r.rule_name||''
    document.getElementById('rule-tp-level').value    = r.tp_level||1
    document.getElementById('rule-risk').value        = r.risk_pct||1
    document.getElementById('rule-cap-min').value     = r.min_capital||0
    document.getElementById('rule-cap-max').value     = r.max_capital||''
    document.getElementById('rule-msg-teaser').value  = r.message_teaser||''
    document.getElementById('rule-msg-tp1').value     = r.message_tp1_reached||''
    document.getElementById('rule-msg-tp2').value     = r.message_tp2_reached||''
    document.getElementById('rule-msg-tp3').value     = r.message_tp3_reached||''
    document.getElementById('rule-msg-sl').value      = r.message_sl_touched||''
    document.getElementById('rule-msg-be').value      = r.message_breakeven||''
    document.getElementById('rule-msg-confirm').value = r.message_confirmation||''
    document.getElementById('modal-new-rule').dataset.editId = ruleId
    openModal('modal-new-rule')
  } catch(e) { toast('Erreur chargement règle','error') }
}

async function saveRule() {
  const editId  = document.getElementById('modal-new-rule').dataset.editId
  const payload = {
    rule_name:            document.getElementById('rule-name').value.trim(),
    tp_level:             parseInt(document.getElementById('rule-tp-level').value),
    min_capital:          parseFloat(document.getElementById('rule-cap-min').value)||0,
    max_capital:          parseFloat(document.getElementById('rule-cap-max').value)||null,
    risk_pct:             parseFloat(document.getElementById('rule-risk').value)||1,
    message_teaser:       document.getElementById('rule-msg-teaser').value||null,
    message_tp1_reached:  document.getElementById('rule-msg-tp1').value||null,
    message_tp2_reached:  document.getElementById('rule-msg-tp2').value||null,
    message_tp3_reached:  document.getElementById('rule-msg-tp3').value||null,
    message_sl_touched:   document.getElementById('rule-msg-sl').value||null,
    message_breakeven:    document.getElementById('rule-msg-be').value||null,
    message_confirmation: document.getElementById('rule-msg-confirm').value||null,
  }
  if (!payload.rule_name) { toast('Nom requis','error'); return }
  try {
    if (editId) {
      await goldFetch(`/rules/${editId}`, {method:'PATCH', body:JSON.stringify(payload)})
      toast('Règle mise à jour ✓','success')
    } else {
      await goldFetch('/rules', {method:'POST', body:JSON.stringify(payload)})
      toast('Règle créée ✓','success')
    }
    delete document.getElementById('modal-new-rule').dataset.editId
    closeModal('modal-new-rule'); loadGoldRegles()
  } catch(e) { toast('Erreur: '+e.message,'error') }
}

// ══════════════════════════════════════════════════════════════
// FOLLOWUP MODAL
// ══════════════════════════════════════════════════════════════
let _followupSignalId = null
let followupType      = 'update'
const FU_LABELS = { update:'🔔 Mise à jour', invalidation:'⚠️ Invalidation', secure:'🔒 Sécurisation', encourage:'💪 Encouragement' }

function openFollowupModal(signalId) {
  _followupSignalId = signalId
  document.getElementById('followup-text').value = ''
  updateFollowupPreview()
  openModal('modal-followup')
}

function setFollowupType(t) {
  followupType = t
  const colors = { update:'#38bdf8', invalidation:'#fbbf24', secure:'#34d399', encourage:'#a78bfa' }
  ;['update','invalidation','secure','encourage'].forEach(id => {
    const btn = document.getElementById('fu-'+id)
    if (!btn) return
    btn.style.cssText = id===t
      ? `padding:6px 12px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid ${colors[id]}40;background:${colors[id]}18;color:${colors[id]};font-weight:500;`
      : `padding:6px 12px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:#71717a;`
  })
  updateFollowupPreview()
}

function updateFollowupPreview() {
  const text = document.getElementById('followup-text')?.value||''
  const el   = document.getElementById('fu-preview')
  if (el) el.innerHTML = `${FU_LABELS[followupType]}<br><br>${text||'<span style="color:#3f3f46;">Saisissez votre message...</span>'}`
}

async function sendFollowup() {
  const msg = document.getElementById('followup-text').value.trim()
  if (!msg) { toast('Message requis','error'); return }
  const btn = document.getElementById('btn-send-followup')
  btn.disabled = true; btn.textContent = 'Envoi...'
  try {
    const r = await apiFetch(`/signals/${_followupSignalId}/followup`, {method:'POST', body:JSON.stringify({type:followupType, message:msg})})
    toast(`Commentaire envoyé à ${r.sent_to} membres ✓`,'success')
    closeModal('modal-followup'); loadSignals()
  } catch(e) { toast('Erreur: '+e.message,'error') }
  finally { btn.disabled=false; btn.textContent='Envoyer →' }
}

// ══════════════════════════════════════════════════════════════
// CLOSE TRADE MODAL
// ══════════════════════════════════════════════════════════════
let _closeSignalId = null
let closeStatus    = 'tp'

function openCloseModal(signalId) {
  _closeSignalId = signalId
  document.getElementById('close-modal-subtitle').textContent = `Signal #${signalId}`
  document.getElementById('close-price').value = ''
  document.getElementById('calc-pnl').textContent = '—'
  setCloseStatus('tp')
  loadFormsForSelect()
  openModal('modal-close-trade')
}

async function loadFormsForSelect() {
  try {
    const forms = await apiFetch('/forms')
    const sel   = document.getElementById('close-form-select')
    if (!sel) return
    sel.innerHTML = '<option value="">Pas de formulaire</option>' +
      (forms||[]).map(f => `<option value="${f.id}">${f.name}</option>`).join('')
  } catch(e) {}
}

function setCloseStatus(s) {
  closeStatus = s
  const cfgs = {
    tp:        {border:'rgba(52,211,153,.3)', bg:'rgba(52,211,153,.1)',  color:'#34d399'},
    sl:        {border:'rgba(248,113,113,.3)',bg:'rgba(248,113,113,.1)', color:'#f87171'},
    partial:   {border:'rgba(251,191,36,.3)', bg:'rgba(251,191,36,.1)', color:'#fbbf24'},
    cancelled: {border:'rgba(113,113,122,.3)',bg:'rgba(113,113,122,.1)', color:'#a1a1aa'},
  }
  ;['tp','sl','partial','cancelled'].forEach(id => {
    const btn = document.getElementById('close-'+id)
    if (!btn) return
    if (id === s) {
      const c = cfgs[s]
      btn.style.cssText = `flex:1;padding:9px 6px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid ${c.border};background:${c.bg};color:${c.color};font-weight:500;`
    } else {
      btn.style.cssText = `flex:1;padding:9px 6px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid rgba(255,255,255,.07);background:rgba(255,255,255,.03);color:#71717a;`
    }
  })
  const pb = document.getElementById('price-block')
  if (pb) pb.style.display = s === 'cancelled' ? 'none' : 'grid'
}

function calcPnL() {
  const price = parseFloat(document.getElementById('close-price')?.value)
  const pnl   = document.getElementById('calc-pnl')
  if (!isNaN(price) && price > 0) { pnl.textContent = 'Prix saisi ✓'; pnl.style.color = '#38bdf8' }
}

async function confirmClose() {
  const price    = document.getElementById('close-price')?.value
  const formId   = document.getElementById('close-form-select')?.value
  const sendForm = document.getElementById('form-toggle')?.classList.contains('on')
  const btn      = document.getElementById('btn-close-confirm')
  btn.disabled = true; btn.textContent = 'Clôture...'
  if (closeStatus !== 'cancelled' && !price) {
    toast('Prix de clôture requis','error'); btn.disabled=false; btn.textContent='Clôturer & envoyer formulaire'; return
  }
  try {
    const payload = { close_price: parseFloat(price)||0, close_result: closeStatus }
    if (formId && sendForm) { payload.form_id = parseInt(formId); payload.send_form_to = 'participated' }
    await apiFetch(`/signals/${_closeSignalId}/close`, {method:'PATCH', body:JSON.stringify(payload)})
    toast('Trade clôturé ✓'+(formId&&sendForm?' — Formulaire envoyé':''), 'success')
    closeModal('modal-close-trade')
    setTimeout(loadSignals, 800)
    loadDashboardStats()
  } catch(e) { toast('Erreur: '+e.message,'error') }
  finally { btn.disabled=false; btn.textContent='Clôturer & envoyer formulaire' }
}

// ══════════════════════════════════════════════════════════════
// HISTORIQUE
// ══════════════════════════════════════════════════════════════
let _histPage = 0; const HIST_LIMIT = 20; let _histStatus = 'all'
let _histSearchTimer = null

function debounceSearch() { clearTimeout(_histSearchTimer); _histSearchTimer = setTimeout(loadHistory, 400) }
function setHistoryTab(status, el) {
  _histStatus = status; _histPage = 0
  document.querySelectorAll('#view-history .htab').forEach(e => e.classList.remove('active'))
  if (el) el.classList.add('active')
  loadHistory()
}
function setChartPeriod(p, el) {
  document.querySelectorAll('#view-history .htab').forEach(e => e.classList.remove('active'))
  if (el) el.classList.add('active')
  loadCrossedPerf(p)
}
function histPage(dir) { _histPage = Math.max(0, _histPage+dir); loadHistory() }

async function loadHistory() {
  const search = document.getElementById('hist-search')?.value||''
  const pair   = document.getElementById('hist-pair-filter')?.value||''
  const offset = _histPage * HIST_LIMIT
  const qs     = `?status=${_histStatus}&limit=${HIST_LIMIT}&offset=${offset}${search?'&search='+encodeURIComponent(search):''}${pair?'&pair='+encodeURIComponent(pair):''}`
  const body   = document.getElementById('history-table-body')
  body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#3f3f46;">Chargement...</div>'
  try {
    const d    = await apiFetch('/history'+qs)
    const rows = d.history||[]
    if (!rows.length) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#3f3f46;">Aucun résultat</div>'; return }
    body.innerHTML = rows.map(r => `
      <div class="tbl-row ${r.took_trade?'':'not-taken'}" onclick="openMemberDrawer(${r.member_id})">
        <div class="flex items-center gap-2" style="flex:1;">
          <div class="av av-default" style="font-size:9px;">${initials(r.member_name)}</div>
          <div><p class="text-xs text-zinc-200">${r.member_name||'—'}</p><p class="text-[9px]" style="color:#52525b;">#${r.member_id}</p></div>
        </div>
        <div style="width:90px;"><p class="text-xs font-mono">${r.pair}</p>${r.took_trade?resultBadge(r.signal_result):'<span class="badge badge-zinc" style="font-size:9px;">Pas pris</span>'}</div>
        <span class="text-xs tabular-nums font-mono" style="color:#71717a;width:70px;">${r.entry_price??'—'}</span>
        <span class="text-xs tabular-nums font-mono" style="color:#71717a;width:70px;">${r.exit_price??'—'}</span>
        <span class="text-xs tabular-nums" style="width:55px;${(r.result_pips||0)>0?'color:#34d399;':(r.result_pips||0)<0?'color:#f87171;':''}">${r.result_pips!=null?(r.result_pips>0?'+':'')+r.result_pips:'—'}</span>
        <span class="text-xs tabular-nums" style="width:70px;${(r.gain_usd||0)>0?'color:#34d399;':(r.gain_usd||0)<0?'color:#f87171;':'color:#71717a;'}">${r.gain_usd!=null?(r.gain_usd>0?'+':'')+r.gain_usd.toFixed(2)+'$':'—'}</span>
        <span class="text-xs tabular-nums" style="color:#e4e4e7;width:80px;">${r.capital_after?(r.capital_after).toFixed(0)+'$':'—'}</span>
        <div style="width:100px;">${r.behavior?`<span class="beh-tag ${r.behavior}" style="font-size:9px;">${behaviorLabel(r.behavior)}</span>`:''}</div>
        <div style="width:36px;">${r.capture_url?`<div style="width:28px;height:20px;background:rgba(52,211,153,.1);border-radius:4px;border:1px solid rgba(52,211,153,.2);display:flex;align-items:center;justify-content:center;cursor:pointer;" onclick="event.stopPropagation();window.open('${r.capture_url}')"><svg width="10" height="10" fill="none" stroke="#34d399" viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></div>`:''}</div>
      </div>`).join('')
    const total = d.total||0
    document.getElementById('hist-pagination-label').textContent = `${offset+1}–${Math.min(offset+HIST_LIMIT,total)} sur ${total}`
    document.getElementById('hist-page-label').textContent       = `${_histPage+1} / ${Math.max(1,Math.ceil(total/HIST_LIMIT))}`
    document.getElementById('hist-prev').disabled = _histPage === 0
    document.getElementById('hist-next').disabled = offset+HIST_LIMIT >= total
  } catch(e) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#f87171;">Erreur chargement</div>' }
}

async function loadCrossedPerf(period = 'day') {
  const container = document.getElementById('perf-chart-container')
  container.innerHTML = '<div class="text-center text-xs pt-16" style="color:#3f3f46;">Chargement...</div>'
  try {
    const d       = await apiFetch(`/history/performance-chart?period=${period}`)
    const admin   = d.admin_curve   || []
    const members = d.members_curve || []
    if (!admin.length && !members.length) { container.innerHTML = '<div class="text-center text-xs pt-16" style="color:#3f3f46;">Pas encore de données</div>'; return }

    const allPcts = [...admin.map(p=>p.cumulative_pct), ...members.map(p=>p.cumulative_pct), 0]
    const minV = Math.min(...allPcts), maxV = Math.max(...allPcts), range = maxV-minV||1
    const W=800, H=160, PAD=30
    const toY = v => PAD + (1-(v-minV)/range) * (H-PAD*2)
    const toX = (i,len) => PAD + (i/Math.max(len-1,1)) * (W-PAD*2)
    const makePath = (pts,fn) => pts.map((p,i)=>`${i===0?'M':'L'}${fn(i,pts.length).toFixed(1)},${toY(p.cumulative_pct).toFixed(1)}`).join(' ')
    const makeArea = (pts,fn) => {
      if (pts.length < 2) return ''
      const l = pts.length-1
      return `${makePath(pts,fn)} L${fn(l,pts.length).toFixed(1)},${H-PAD} L${PAD},${H-PAD} Z`
    }
    const gridLines = []
    for (let i=0; i<=4; i++) {
      const val = minV + (range/4)*i, y = toY(val).toFixed(1)
      gridLines.push(`<line x1="${PAD}" y1="${y}" x2="${W-PAD}" y2="${y}" stroke="rgba(255,255,255,.05)" stroke-width="1"/>
        <text x="${PAD-4}" y="${parseFloat(y)+4}" fill="#3f3f46" font-size="9" text-anchor="end">${val>=0?'+':''}${val.toFixed(1)}%</text>`)
    }
    const adminDots  = admin.map((p,i) => `<circle cx="${toX(i,admin.length).toFixed(1)}" cy="${toY(p.cumulative_pct).toFixed(1)}" r="3" fill="#38bdf8" opacity=".8"><title>${p.period}: ${p.cumulative_pct>0?'+':''}${p.cumulative_pct}%</title></circle>`).join('')
    const memberDots = members.map((p,i) => `<circle cx="${toX(i,members.length).toFixed(1)}" cy="${toY(p.cumulative_pct).toFixed(1)}" r="3" fill="#34d399" opacity=".8"><title>${p.period}: ${p.cumulative_pct>0?'+':''}${p.cumulative_pct}%</title></circle>`).join('')
    const fn = (i,len) => toX(i,len)

    container.innerHTML = `
      <svg width="100%" height="100%" viewBox="0 0 ${W} ${H}" preserveAspectRatio="xMidYMid meet" style="display:block;">
        <defs>
          <linearGradient id="gA" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#38bdf8" stop-opacity=".15"/><stop offset="100%" stop-color="#38bdf8" stop-opacity="0"/></linearGradient>
          <linearGradient id="gM" x1="0" y1="0" x2="0" y2="1"><stop offset="0%" stop-color="#34d399" stop-opacity=".2"/><stop offset="100%" stop-color="#34d399" stop-opacity="0"/></linearGradient>
        </defs>
        ${gridLines.join('')}
        <line x1="${PAD}" y1="${toY(0).toFixed(1)}" x2="${W-PAD}" y2="${toY(0).toFixed(1)}" stroke="rgba(255,255,255,.12)" stroke-width="1" stroke-dasharray="4 3"/>
        ${admin.length>1?`<path d="${makeArea(admin,fn)}" fill="url(#gA)"/>`:''}
        ${members.length>1?`<path d="${makeArea(members,fn)}" fill="url(#gM)"/>`:''}
        ${admin.length>1?`<path d="${makePath(admin,fn)}" fill="none" stroke="#38bdf8" stroke-width="1.5" stroke-dasharray="5 3"/>`:''}
        ${members.length>1?`<path d="${makePath(members,fn)}" fill="none" stroke="#34d399" stroke-width="2"/>`:''}
        ${adminDots}${memberDots}
      </svg>`
  } catch(e) { container.innerHTML = '<div class="text-center text-xs pt-16" style="color:#3f3f46;">Erreur graphique</div>' }
}

// ══════════════════════════════════════════════════════════════
// PERFORMANCES
// ══════════════════════════════════════════════════════════════
let _perfPage = 0; const PERF_LIMIT = 20
let _perfSearchTimer = null
function debouncePerf() { clearTimeout(_perfSearchTimer); _perfSearchTimer = setTimeout(()=>{_perfPage=0;loadPerformances()}, 400) }
function perfPage(dir) { _perfPage = Math.max(0,_perfPage+dir); loadPerformances() }

async function loadPerformances() {
  const search  = document.getElementById('perf-search')?.value||''
  const sort_by = document.getElementById('perf-sort')?.value||'win_rate'
  const offset  = _perfPage * PERF_LIMIT
  const qs      = `?sort_by=${sort_by}&limit=${PERF_LIMIT}&offset=${offset}${search?'&search='+encodeURIComponent(search):''}`
  const body    = document.getElementById('performances-body')
  body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#3f3f46;">Chargement...</div>'
  try {
    const d       = await apiFetch('/performances'+qs)
    const members = d.members||[]
    if (!members.length) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#3f3f46;">Aucun membre journalisé</div>'; return }
    body.innerHTML = members.map(m => `
      <div class="member-row px-4" style="cursor:pointer;" onclick="openMemberDrawer(${m.user_id})">
        <div class="flex items-center gap-2 flex-1"><div class="av av-default">${initials(m.name)}</div><div><p class="text-xs font-medium text-zinc-200">${m.name||'—'}</p><p class="text-[10px]" style="color:#52525b;">#${m.user_id}</p></div></div>
        <div style="width:80px;"><p class="text-xs tabular-nums text-zinc-200">${m.capital_actuel?(m.capital_actuel).toFixed(0)+'$':'—'}</p>${m.capital_evolution_pct!=null?`<p class="text-[9px] ${(m.capital_evolution_pct||0)>=0?'pnl-pos':'pnl-neg'}">${(m.capital_evolution_pct||0)>0?'↑':'↓'} ${Math.abs(m.capital_evolution_pct||0)}%</p>`:''}</div>
        <span class="text-xs tabular-nums text-zinc-300" style="width:60px;">${m.total_trades??0}</span>
        <div style="width:70px;"><p class="text-xs tabular-nums" style="color:#38bdf8;">${m.engagement_rate??'—'}%</p><div class="pbar mt-1"><div class="pbar-fill" style="width:${m.engagement_rate??0}%;background:#38bdf8;"></div></div></div>
        <div style="width:70px;"><p class="text-xs tabular-nums pnl-pos">${m.win_rate??'—'}%</p><div class="pbar mt-1"><div class="pbar-fill" style="width:${m.win_rate??0}%;background:#34d399;"></div></div></div>
        <span class="text-xs tabular-nums ${(m.perf_totale||0)>=0?'pnl-pos':'pnl-neg'}" style="width:80px;">${m.perf_totale!=null?(m.perf_totale>0?'+':'')+m.perf_totale+'%':'—'}</span>
        <div style="width:110px;">${m.disciplined_count>0?'<span class="beh-tag disciplined" style="font-size:9px;">Discipliné ✓</span>':''}</div>
        <div style="width:60px;"><span class="badge ${m.suivi_status==='active'?'badge-green':'badge-zinc'}" style="font-size:9px;">${m.suivi_status==='active'?'Actif':'Suivi off'}</span></div>
        <button class="btn-icon" style="width:24px;height:24px;" onclick="event.stopPropagation();openMemberDrawer(${m.user_id})"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
      </div>`).join('')
    const total = d.total||0
    document.getElementById('perf-pagination-label').textContent = `${offset+1}–${Math.min(offset+PERF_LIMIT,total)} sur ${total}`
    document.getElementById('perf-page-label').textContent       = `${_perfPage+1} / ${Math.max(1,Math.ceil(total/PERF_LIMIT))}`
    document.getElementById('perf-prev').disabled = _perfPage === 0
    document.getElementById('perf-next').disabled = offset+PERF_LIMIT >= total
  } catch(e) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#f87171;">Erreur</div>' }
}

// MEMBER DRAWER
let _currentMemberId = null
async function openMemberDrawer(userId) {
  _currentMemberId = userId
  document.getElementById('member-drawer').classList.add('open')
  document.getElementById('drawer-overlay').classList.add('open')
  document.getElementById('member-drawer-name').textContent = '—'
  document.getElementById('member-drawer-sub').textContent  = `#${userId}`
  document.getElementById('member-drawer-content').innerHTML = '<div class="text-center text-xs pt-16" style="color:#3f3f46;">Chargement...</div>'
  try {
    const d   = await apiFetch(`/performances/${userId}`)
    const s   = d.stats||{}
    const cap = d
    document.getElementById('member-av').textContent          = initials(d.name)
    document.getElementById('member-drawer-name').textContent = d.name||`Membre #${userId}`
    document.getElementById('member-drawer-sub').textContent  = `#${userId} · ${s.total_trades??0} trades`

    const capBars = (d.capital_21j||[]).map(c => {
      const h = Math.min(100, Math.max(10, (c.capital/(d.capital_actuel||1000))*100))
      return `<div class="cap-bar ${c.type}" style="flex:1;height:${h}%;"></div>`
    }).join('')

    const curve = d.performance_curve||[]
    let perfSVG = ''
    if (curve.length > 1) {
      const allC  = curve.map(p=>p.cumulative_pct)
      const minC  = Math.min(0,...allC), maxC = Math.max(1,...allC), rangeC = maxC-minC||1
      const pts   = curve.map((p,i) => `${i/(curve.length-1)*400},${80-((p.cumulative_pct-minC)/rangeC*70)}`).join(' ')
      perfSVG = `
        <div><p class="slabel">Courbe de performance</p>
        <div style="background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.05);border-radius:8px;overflow:hidden;">
          <svg width="100%" height="80" viewBox="0 0 400 80" preserveAspectRatio="none" style="display:block;">
            <polyline points="${pts}" style="fill:none;stroke:#34d399;stroke-width:1.5;"/>
            ${curve.map((p,i)=>`<circle cx="${i/(curve.length-1)*400}" cy="${80-((p.cumulative_pct-minC)/rangeC*70)}" r="3" fill="${p.result_pct>0?'#34d399':p.result_pct<0?'#f87171':'#fbbf24'}"/>`).join('')}
          </svg>
        </div></div>`
    }

    document.getElementById('member-drawer-content').innerHTML = `
      <div class="grid grid-cols-4 gap-2">
        <div class="stat-mini text-center"><p class="text-base font-light text-white tabular-nums">${s.total_trades??0}</p><p class="text-[9px] mt-1" style="color:#52525b;">Trades</p></div>
        <div class="stat-mini text-center"><p class="text-base font-light tabular-nums pnl-pos">${s.win_rate??'—'}%</p><p class="text-[9px] mt-1" style="color:#52525b;">Win</p></div>
        <div class="stat-mini text-center"><p class="text-base font-light tabular-nums" style="color:#38bdf8;">${s.engagement_rate??'—'}%</p><p class="text-[9px] mt-1" style="color:#52525b;">Engag.</p></div>
        <div class="stat-mini text-center"><p class="text-base font-light tabular-nums pnl-pos">${s.perf_totale!=null?(s.perf_totale>0?'+':'')+s.perf_totale+'%':'—'}</p><p class="text-[9px] mt-1" style="color:#52525b;">Total</p></div>
      </div>
      <div>
        <p class="slabel">Capital</p>
        <div class="flex items-center justify-between mb-2">
          <div><p class="text-[10px]" style="color:#52525b;">Initial</p><p class="text-sm tabular-nums text-zinc-300 font-mono">${cap.capital_initial?(cap.capital_initial).toFixed(0)+'$':'—'}</p></div>
          <div style="flex:1;height:1px;background:rgba(255,255,255,.06);margin:0 12px;"></div>
          <div style="text-align:right;"><p class="text-[10px]" style="color:#52525b;">Actuel</p><p class="text-sm tabular-nums pnl-pos font-mono">${cap.capital_actuel?(cap.capital_actuel).toFixed(0)+'$':'—'}</p></div>
          ${cap.evolution_pct!=null?`<div class="badge badge-green ml-3">↑ ${cap.evolution_pct}%</div>`:''}
        </div>
        ${capBars?`<div class="flex items-end gap-0.5" style="height:48px;">${capBars}</div>`:'' }
        ${cap.capital_theorique?`
          <div class="mt-3 p-2.5" style="background:rgba(255,255,255,.025);border-radius:7px;">
            <div class="flex justify-between mb-1"><span class="text-[10px]" style="color:#52525b;">Théorique (TP systématique)</span><span class="text-[10px] pnl-pos">${cap.capital_theorique.toFixed(0)}$</span></div>
            <div class="flex justify-between mb-1"><span class="text-[10px]" style="color:#52525b;">Réel actuel</span><span class="text-[10px] pnl-pos">${(cap.capital_actuel||0).toFixed(0)}$</span></div>
            ${cap.manque_a_gagner?`<div class="flex justify-between"><span class="text-[10px]" style="color:#fbbf24;">Manque à gagner</span><span class="text-[10px]" style="color:#fbbf24;">-${cap.manque_a_gagner.toFixed(0)}$</span></div>`:''}
          </div>`:''}
      </div>
      ${perfSVG}
      <div>
        <p class="slabel">Comportements</p>
        <div class="exit-bar">
          ${(d.behaviors||[]).map(b=>`
            <div class="exit-row">
              <span style="min-width:110px;font-size:11px;color:${b.behavior==='disciplined'?'#34d399':b.behavior==='early_exit'?'#fbbf24':'#f87171'}">${behaviorLabel(b.behavior)}</span>
              <div class="exit-track"><div class="exit-fill" style="width:${Math.min(100,(b.count/Math.max(s.total_trades||1,1))*100)}%;background:${b.behavior==='disciplined'?'#34d399':b.behavior==='early_exit'?'#fbbf24':'#f87171'};"></div></div>
              <span class="text-xs" style="color:#71717a;">${b.count}</span>
            </div>`).join('')}
        </div>
        ${d.lot_respect_rate!=null?`<div class="mt-2"><span class="text-[10px]" style="color:#52525b;">Respect des lots : </span><span class="text-[10px] pnl-pos">${d.lot_respect_rate}%</span></div>`:''}
      </div>
      <div style="background:rgba(255,255,255,.025);border:1px solid rgba(255,255,255,.06);border-radius:8px;padding:12px 14px;">
        <div class="flex items-center justify-between">
          <div><p class="text-xs font-medium text-zinc-300">Suivi capital actif</p></div>
          <span class="badge ${d.suivi_capital_actif?'badge-green':'badge-zinc'}">${d.suivi_capital_actif?'Actif':'Inactif'}</span>
        </div>
      </div>`
  } catch(e) { document.getElementById('member-drawer-content').innerHTML = '<div class="text-center text-xs pt-16" style="color:#f87171;">Erreur chargement</div>' }
}

// ══════════════════════════════════════════════════════════════
// CLASSEMENT
// ══════════════════════════════════════════════════════════════
let _leaderPeriod = 'all'
function setLeaderPeriod(p, el) {
  _leaderPeriod = p
  document.querySelectorAll('#view-leaderboard .htab').forEach(e => e.classList.remove('active'))
  if (el) el.classList.add('active')
  loadLeaderboard()
}

async function loadLeaderboard() {
  const body = document.getElementById('leaderboard-body')
  body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#3f3f46;">Chargement...</div>'
  try {
    const d   = await apiFetch(`/leaderboard?period=${_leaderPeriod}&min_trades=3&limit=20`)
    const all = d.leaderboard||[]
    if (!all.length) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#3f3f46;">Aucun résultat (min. 3 trades)</div>'; return }

    const top3  = all.slice(0,3)
    const order = [top3[1],top3[0],top3[2]].filter(Boolean)
    const emjs  = ['🥈','🥇','🥉']
    const sizes = [32,40,30]
    document.getElementById('podium-row').innerHTML = order.map((m,i) => `
      <div class="card p-5 text-center" style="${i===1?'border-color:rgba(251,191,36,.25);background:rgba(251,191,36,.03);':'margin-top:'+(i===0?16:28)+'px;'}">
        <div style="font-size:${sizes[i]}px;margin-bottom:8px;">${emjs[i]}</div>
        <div class="av av-default mx-auto mb-2" style="width:${i===1?44:38}px;height:${i===1?44:38}px;font-size:${i===1?14:12}px;">${initials(m.name)}</div>
        <p class="text-sm font-medium text-white">${m.name||'—'}</p>
        <p class="text-${i===1?'2xl':'xl'} font-light mt-1 pnl-pos">${(m.perf_totale||0)>0?'+':''}${m.perf_totale??0}%</p>
        <p class="text-[10px] mt-1" style="color:#52525b;">${m.total_trades} trades · ${m.win_rate??'—'}% win</p>
      </div>`).join('')

    body.innerHTML = all.slice(3).map(m => `
      <div class="member-row px-4" style="cursor:pointer;" onclick="openMemberDrawer(${m.user_id})">
        <span style="font-size:13px;font-weight:600;color:#71717a;min-width:30px;text-align:center;">${m.rank}</span>
        <div class="flex items-center gap-2 flex-1">
          <div class="av av-default" style="font-size:10px;">${initials(m.name)}</div>
          <p class="text-xs text-zinc-200">${m.name||'—'}</p>
        </div>
        <span class="text-xs tabular-nums" style="color:#e4e4e7;width:70px;">${m.capital_actuel?(m.capital_actuel).toFixed(0)+'$':'—'}</span>
        <span class="text-xs tabular-nums text-zinc-400" style="width:60px;">${m.total_trades}</span>
        <span class="text-xs tabular-nums" style="color:#38bdf8;width:70px;">${m.engagement_rate??'—'}%</span>
        <span class="text-xs tabular-nums" style="color:${(m.win_rate||0)>=60?'#34d399':(m.win_rate||0)>=40?'#fbbf24':'#f87171'};width:70px;">${m.win_rate??'—'}%</span>
        <span class="text-xs tabular-nums ${(m.perf_totale||0)>=0?'pnl-pos':'pnl-neg'}" style="width:90px;">${m.perf_totale!=null?(m.perf_totale>0?'+':'')+m.perf_totale+'%':'—'}</span>
      </div>`).join('')
    if (all.length <= 3) body.innerHTML = ''
    body.innerHTML += `<p class="text-xs text-center py-3" style="color:#3f3f46;">${d.total} membres · min. 3 trades journalisés</p>`
  } catch(e) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#f87171;">Erreur</div>' }
}

// ══════════════════════════════════════════════════════════════
// BILAN IA
// ══════════════════════════════════════════════════════════════
let _bilanWeekStart = '', _bilanWeekEnd = ''

function buildWeekOptions() {
  const sel = document.getElementById('ia-week')
  if (!sel || sel.options.length > 0) return
  const opts = []
  for (let i = 0; i < 8; i++) {
    const mon = new Date(); mon.setDate(mon.getDate() - mon.getDay() + 1 - i*7)
    const sun = new Date(mon); sun.setDate(sun.getDate()+6)
    const label = `Semaine du ${fmtShort(mon)} au ${fmtShort(sun)} ${sun.getFullYear()}`
    opts.push({ label, start: mon.toISOString().split('T')[0]+'T00:00:00', end: sun.toISOString().split('T')[0]+'T23:59:59' })
  }
  sel.innerHTML = opts.map((o,i) => `<option value="${i}" data-start="${o.start}" data-end="${o.end}">${o.label}</option>`).join('')
  updateWeekDates()
}

function updateWeekDates() {
  const sel = document.getElementById('ia-week')
  if (!sel) return
  const opt = sel.options[sel.selectedIndex]
  _bilanWeekStart = opt?.dataset.start||''
  _bilanWeekEnd   = opt?.dataset.end||''
}

async function generateBilanPreview() {
  updateWeekDates()
  const btn = document.getElementById('btn-generate-bilan')
  btn.disabled = true; btn.textContent = 'Génération...'
  document.getElementById('ia-status').textContent     = 'Génération...'
  document.getElementById('ia-preview-box').textContent = "L'IA génère le bilan..."
  const adminConfig = {
    include_perf:            document.getElementById('ia-cfg-perf').checked,
    include_behavior:        document.getElementById('ia-cfg-beh').checked,
    include_recommendations: document.getElementById('ia-cfg-reco').checked,
    include_comparison:      document.getElementById('ia-cfg-comp').checked,
  }
  try {
    const perfData    = await apiFetch('/performances?limit=1&offset=0')
    const firstMember = (perfData.members||[])[0]
    if (!firstMember) { toast('Aucun membre journalisé cette semaine','info'); return }
    const r = await apiFetch('/ia/bilans/preview', {
      method: 'POST',
      body: JSON.stringify({
        user_id:      firstMember.user_id,
        week_start:   _bilanWeekStart,
        week_end:     _bilanWeekEnd,
        week_label:   document.getElementById('ia-week').options[document.getElementById('ia-week').selectedIndex]?.text||'Cette semaine',
        admin_config: adminConfig,
      }),
    })
    document.getElementById('ia-status').textContent         = 'Généré ✓'
    document.getElementById('ia-preview-box').style.color     = '#a1a1aa'
    document.getElementById('ia-preview-box').style.fontStyle = 'normal'
    document.getElementById('ia-preview-box').innerHTML        = r.message
      .replace(/\*(.*?)\*/g,'<b style="color:#e4e4e7;">$1</b>')
      .replace(/_(.*?)_/g,'<i>$1</i>')
      .replace(/\n/g,'<br>')
  } catch(e) {
    toast('Erreur: '+e.message,'error')
    document.getElementById('ia-status').textContent = 'Erreur'
  } finally { btn.disabled=false; btn.textContent='Générer un aperçu' }
}

async function sendBilanToAll() {
  updateWeekDates()
  const weekLabel   = document.getElementById('ia-week').options[document.getElementById('ia-week').selectedIndex]?.text||'Cette semaine'
  const adminConfig = {
    include_perf:            document.getElementById('ia-cfg-perf').checked,
    include_behavior:        document.getElementById('ia-cfg-beh').checked,
    include_recommendations: document.getElementById('ia-cfg-reco').checked,
    include_comparison:      document.getElementById('ia-cfg-comp').checked,
  }
  if (!confirm(`Envoyer les bilans pour "${weekLabel}" ?`)) return
  try {
    const r = await apiFetch('/ia/bilans/generate', {
      method: 'POST',
      body: JSON.stringify({
        week_start:   _bilanWeekStart,
        week_end:     _bilanWeekEnd,
        week_label:   weekLabel,
        target:       document.getElementById('ia-target').value,
        send:         true,
        admin_config: adminConfig,
      }),
    })
    toast(`${r.sent} bilans envoyés · ${r.errors} erreurs`, r.errors>0?'info':'success')
    loadBilanHistory()
  } catch(e) { toast('Erreur: '+e.message,'error') }
}

async function sendBilanToMember() {
  if (!_currentMemberId) return
  updateWeekDates()
  const weekLabel = document.getElementById('ia-week')?.options[document.getElementById('ia-week')?.selectedIndex]?.text||'Cette semaine'
  try {
    await apiFetch('/ia/bilans/preview', {
      method: 'POST',
      body: JSON.stringify({
        user_id:      _currentMemberId,
        week_start:   _bilanWeekStart||new Date(Date.now()-7*86400000).toISOString(),
        week_end:     _bilanWeekEnd||new Date().toISOString(),
        week_label:   weekLabel,
        admin_config: {include_perf:true, include_behavior:true, include_recommendations:true, include_comparison:false},
      }),
    })
    toast('Bilan envoyé ✓','success')
  } catch(e) { toast('Erreur: '+e.message,'error') }
}

async function loadBilanHistory() {
  const el = document.getElementById('bilan-history-list')
  try {
    const data = await apiFetch('/ia/bilans/history')
    if (!data.length) { el.innerHTML = '<div class="text-xs" style="color:#3f3f46;">Aucun bilan envoyé</div>'; return }
    el.innerHTML = data.slice(0,5).map(b => `
      <div style="padding:10px 12px;background:rgba(255,255,255,.025);border-radius:8px;border:1px solid rgba(255,255,255,.05);">
        <div class="flex justify-between mb-1"><p class="text-xs font-medium text-zinc-200">${b.week_label}</p><span class="badge badge-green" style="font-size:10px;">Envoyé</span></div>
        <p class="text-[11px]" style="color:#52525b;">${b.target} · ${b.total_sent} envoyés · ${fmtDate(b.generated_at)}</p>
      </div>`).join('')
  } catch(e) { el.innerHTML = '<div class="text-xs" style="color:#3f3f46;">—</div>' }
}

// ══════════════════════════════════════════════════════════════
// PAIRES & CALCULATEUR
// ══════════════════════════════════════════════════════════════
async function loadPairs() {
  const body = document.getElementById('pairs-table-body')
  body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#3f3f46;">Chargement...</div>'
  try {
    const pairs = await apiFetch('/pairs')
    if (!pairs.length) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#3f3f46;">Aucune paire configurée</div>'; return }
    const catColor = { forex:'#34d399', crypto:'#a78bfa', indices:'#38bdf8', commodities:'#fbbf24' }
    const catLabel = { forex:'FX', crypto:'CR', indices:'IN', commodities:'XM' }
    body.innerHTML = pairs.map(p => `
      <div class="pair-row">
        <div class="flex items-center gap-2 flex-1">
          <div style="width:32px;height:22px;border-radius:5px;background:${catColor[p.category]||'#71717a'}18;border:1px solid ${catColor[p.category]||'#71717a'}30;display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:600;color:${catColor[p.category]||'#71717a'};font-family:'Geist Mono',monospace;">${catLabel[p.category]||'??'}</div>
          <p class="text-xs font-medium font-mono">${p.symbol}</p>
        </div>
        <span class="text-xs" style="color:#71717a;width:90px;">${p.category}</span>
        <span class="text-xs tabular-nums font-mono" style="color:#38bdf8;width:110px;">${p.pip_value.toFixed(2)}$</span>
        <span class="text-xs tabular-nums" style="color:#71717a;width:80px;">${p.decimals}</span>
        <span class="text-xs font-mono" style="color:#52525b;width:120px;">${p.binance_symbol||'—'}</span>
        <div style="width:80px;"><span class="badge ${p.is_active?'badge-green':'badge-zinc'}" style="font-size:9px;">${p.is_active?'Actif':'Inactif'}</span></div>
        <div class="flex gap-1" style="width:60px;">
          <button class="btn-icon" style="width:22px;height:22px;" onclick="togglePairActive(${p.id},${p.is_active})">
            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">${p.is_active?'<path d="M18 6 6 18M6 6l12 12"/>':'<path d="M12 22c5.5 0 10-4.5 10-10S17.5 2 12 2 2 6.5 2 12s4.5 10 10 10z"/><path d="m9 12 2 2 4-4"/>'}</svg>
          </button>
          <button class="btn-icon" style="width:22px;height:22px;" onclick="deletePair(${p.id})">
            <svg width="10" height="10" fill="none" stroke="#f87171" viewBox="0 0 24 24" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="m19 6-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
          </button>
        </div>
      </div>`).join('')
    const sel = document.getElementById('calc-pair-sel')
    sel.innerHTML = pairs.filter(p=>p.is_active).map(p => `<option value="${p.pip_value}" data-sym="${p.symbol}">${p.symbol} (${p.pip_value}$/pip)</option>`).join('')
    calcLot()
  } catch(e) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#f87171;">Erreur</div>' }
}

function calcLot() {
  const cap  = parseFloat(document.getElementById('calc-capital')?.value)||0
  const risk = parseFloat(document.getElementById('calc-risk')?.value)||0
  const slP  = parseFloat(document.getElementById('calc-sl-pips')?.value)||1
  const tpP  = parseFloat(document.getElementById('calc-tp-pips')?.value)||0
  const pipV = parseFloat(document.getElementById('calc-pair-sel')?.value)||10
  const riskUsd = cap*risk/100
  const lot     = riskUsd/(slP*pipV)
  const gain    = lot*tpP*pipV
  document.getElementById('res-risk-usd').textContent = riskUsd.toFixed(2)+' $'
  document.getElementById('res-lot').textContent      = lot.toFixed(4)
  document.getElementById('res-loss').textContent     = '-'+riskUsd.toFixed(2)+' $'
  document.getElementById('res-gain').textContent     = '+'+gain.toFixed(2)+' $'
}

async function savePair() {
  const symbol = document.getElementById('pair-symbol').value.trim().toUpperCase()
  const pip    = parseFloat(document.getElementById('pair-pip').value)
  if (!symbol || !pip) { toast('Symbole et pip requis','error'); return }
  try {
    await apiFetch('/pairs', {method:'POST', body:JSON.stringify({
      symbol, pip_value:pip,
      category:       document.getElementById('pair-category').value,
      decimals:       parseInt(document.getElementById('pair-dec').value)||5,
      binance_symbol: document.getElementById('pair-binance').value||null,
      is_active:      document.getElementById('pair-active-toggle').classList.contains('on')?1:0,
    })})
    toast(`Paire ${symbol} ajoutée ✓`,'success'); closeModal('modal-add-pair'); loadPairs()
  } catch(e) { toast('Erreur: '+e.message,'error') }
}

async function togglePairActive(id, current) {
  try { await apiFetch(`/pairs/${id}`, {method:'PATCH', body:JSON.stringify({is_active:current?0:1})}); loadPairs() }
  catch(e) { toast('Erreur','error') }
}

async function deletePair(id) {
  if (!confirm('Désactiver cette paire ?')) return
  try { await apiFetch(`/pairs/${id}`, {method:'DELETE'}); toast('Paire désactivée','success'); loadPairs() }
  catch(e) { toast('Erreur','error') }
}

async function loadCategories() {
  try {
    const res  = await fetch(`${API_URL}/categorie`)
    const data = await res.json()
    const sel  = document.querySelector('#dest-block-category select')
    const sel2 = document.getElementById('gold-category')
    if (sel) {
      sel.innerHTML = '<option value="">Sélectionner une catégorie...</option>'
      data.forEach(cat => {
        const opt = document.createElement('option')
        opt.value = cat.name_categorie
        opt.textContent = `${cat.name_categorie} (${cat.member_count??0})`
        sel.appendChild(opt)
      })
    }
    if (sel2) {
      sel2.innerHTML = ''
      data.forEach(cat => {
        const opt = document.createElement('option')
        opt.value = cat.name_categorie
        opt.textContent = cat.name_categorie
        sel2.appendChild(opt)
      })
    }
  } catch(e) {}
}

// ══════════════════════════════════════════════════════════════
// FORMULAIRES
// ══════════════════════════════════════════════════════════════
async function loadFormStats() {
  try {
    const d = await apiFetch('/forms/stats')
    document.getElementById('forms-stats-grid').innerHTML = `
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Formulaires actifs</p><p class="text-xl font-light text-white">${d.total_forms??0}</p><p class="text-[10px] mt-1" style="color:#52525b;">${d.system_forms??0} système · ${d.custom_forms??0} personnalisés</p></div>
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Réponses collectées</p><p class="text-xl font-light text-white tabular-nums">${d.total_responses??0}</p><p class="text-[10px] mt-1" style="color:#52525b;">${d.unique_respondents??0} membres uniques</p></div>
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Taux de complétion</p><p class="text-xl font-light tabular-nums pnl-pos">${d.completion_rate??0}%</p><div class="pbar mt-2"><div class="pbar-fill" style="width:${d.completion_rate??0}%;background:#34d399;"></div></div></div>`
  } catch(e) {}
}

let _selectedFormId = null
async function loadForms() {
  try {
    const forms   = await apiFetch('/forms')
    const system  = forms.filter(f => f.type==='system'||f.form_type==='system')
    const custom  = forms.filter(f => f.type!=='system'&&f.form_type!=='system')
    function renderFormCard(f) {
      return `<div class="form-card ${_selectedFormId===f.id?'selected':''}" onclick="selectFormCard(${f.id})">
        <div class="flex items-center justify-between mb-2">
          <div class="flex items-center gap-2"><span class="badge ${f.type==='system'||f.form_type==='system'?'badge-sky':'badge-violet'}" style="font-size:9px;">${f.type==='system'||f.form_type==='system'?'Système':'Personnalisé'}</span><p class="text-xs font-medium text-zinc-200">${f.name}</p></div>
          <span class="badge ${f.is_active?'badge-green':'badge-zinc'}" style="font-size:9px;">${f.is_active?'Actif':'Inactif'}</span>
        </div>
        <p class="text-[10px] mt-2" style="color:#52525b;">${f.total_responses??0} réponses · ${f.respondents??0} membres</p>
        ${f.last_response_at?`<p class="text-[10px]" style="color:#3f3f46;">Dernière : ${fmtDate(f.last_response_at)}</p>`:''}
      </div>`
    }
    const html = []
    if (system.length) { html.push('<p class="text-xs font-medium text-zinc-400 mb-1">Formulaires système</p>'); html.push(...system.map(renderFormCard)) }
    if (custom.length) { html.push('<p class="text-xs font-medium text-zinc-400 mb-1 mt-3">Personnalisés</p>'); html.push(...custom.map(renderFormCard)) }
    document.getElementById('forms-list').innerHTML = html.join('')
  } catch(e) { document.getElementById('forms-list').innerHTML = '<div class="text-xs" style="color:#3f3f46;">Erreur</div>' }
}

async function selectFormCard(formId) {
  _selectedFormId = formId; loadForms()
  try {
    const d      = await apiFetch(`/forms/${formId}/mapping`)
    const fields = d.fields||[]
    const STATS  = ['capital_evolution','win_rate_reel','behavior_tag','engagement_rate','lot_respect','custom']
    document.getElementById('mapping-panel').innerHTML = `
      <div class="flex items-center justify-between mb-3">
        <p class="text-xs font-medium text-zinc-300">Mapping — ${d.form_name}</p>
        <button class="btn-ghost" style="font-size:10px;padding:4px 8px;" onclick="saveMapping(${formId})">Sauvegarder</button>
      </div>
      ${fields.map(f => `
        <div class="map-row" data-field-id="${f.field_id}">
          <span class="text-[10px] font-mono" style="color:#38bdf8;min-width:120px;">${f.field_label||f.field_id}</span>
          <span style="color:#3f3f46;font-size:11px;">→</span>
          <select class="input" style="font-size:10px;padding:4px 7px;flex:1;" data-fid="${f.field_id}">
            ${STATS.map(s=>`<option value="${s}" ${f.maps_to_stat===s?'selected':''}>${s}</option>`).join('')}
          </select>
          <select class="input" style="font-size:10px;padding:4px 7px;width:80px;">
            ${['text','number','boolean','image'].map(t=>`<option ${f.data_type===t?'selected':''}>${t}</option>`).join('')}
          </select>
          <select class="input" style="font-size:10px;padding:4px 7px;width:70px;">
            ${['last','average','sum','count'].map(a=>`<option ${f.aggregation===a?'selected':''}>${a}</option>`).join('')}
          </select>
        </div>
        ${(f.sample_values||[]).length?`<div style="font-size:9px;color:#3f3f46;margin-bottom:6px;padding-left:8px;">Ex: ${f.sample_values.map(v=>v.value).join(', ')}</div>`:''}`
      ).join('')}`
  } catch(e) { document.getElementById('mapping-panel').innerHTML = '<p class="text-xs" style="color:#3f3f46;">Erreur chargement mapping</p>' }
}

async function saveMapping(formId) {
  const rows   = document.querySelectorAll('#mapping-panel .map-row')
  const fields = []
  rows.forEach(row => {
    const fid = row.dataset.fieldId
    const sels = row.querySelectorAll('select')
    if (fid && sels.length >= 3) fields.push({field_id:fid, maps_to_stat:sels[0].value, data_type:sels[1].value, aggregation:sels[2].value})
  })
  try {
    await apiFetch(`/forms/${formId}/mapping`, {method:'PATCH', body:JSON.stringify({fields})})
    toast('Mapping sauvegardé ✓','success')
  } catch(e) { toast('Erreur sauvegarde','error') }
}

async function loadFormsSummary() {
  try {
    const data = await apiFetch('/forms/summary')
    if (!data.length) { document.getElementById('forms-summary-table').innerHTML = '<div class="text-xs" style="color:#3f3f46;">Aucun formulaire</div>'; return }
    document.getElementById('forms-summary-table').innerHTML = `
      <table style="width:100%;border-collapse:collapse;font-size:11px;">
        <thead><tr style="border-bottom:1px solid rgba(255,255,255,.05);">
          <th style="text-align:left;padding:6px 8px;color:#3f3f46;font-weight:500;font-size:10px;">Formulaire</th>
          <th style="text-align:left;padding:6px 8px;color:#3f3f46;font-weight:500;font-size:10px;">Données</th>
          <th style="text-align:left;padding:6px 8px;color:#3f3f46;font-weight:500;font-size:10px;">Stats</th>
        </tr></thead>
        <tbody>${data.map(f=>`
          <tr style="border-bottom:1px solid rgba(255,255,255,.04);">
            <td style="padding:7px 8px;color:#e4e4e7;">${f.form_name}</td>
            <td style="padding:7px 8px;color:#38bdf8;font-family:'Geist Mono',monospace;font-size:10px;">${(f.fields_collected||[]).slice(0,3).join(', ')}</td>
            <td style="padding:7px 8px;color:#71717a;">${(f.stats_produced||[]).join(', ')||'—'}</td>
          </tr>`).join('')}</tbody>
      </table>`
  } catch(e) { document.getElementById('forms-summary-table').innerHTML = '<div class="text-xs" style="color:#3f3f46;">Erreur</div>' }
}

// ══════════════════════════════════════════════════════════════
// UPLOAD MEDIA (Signal classique)
// ══════════════════════════════════════════════════════════════
function _ensureSpinKeyframe() {
  if (document.getElementById('_spin-style')) return
  const s = document.createElement('style')
  s.id = '_spin-style'; s.textContent = '@keyframes spin{to{transform:rotate(360deg)}}'
  document.head.appendChild(s)
}

function resetUploadZone() {
  const zone = document.querySelector('#media-upload .upload-zone')
  if (!zone) return
  zone.style.borderColor = ''; zone.style.background = ''
  zone.innerHTML = `
    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="margin:0 auto 6px;color:#52525b;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
    <p class="text-xs" style="color:#71717a;">Glisser un fichier ou <span style="color:#38bdf8;cursor:pointer;">parcourir</span></p>
    <p class="text-[10px] mt-1" style="color:#3f3f46;">Image (max 10 MB) · Vidéo (max 50 MB)</p>`
  zone.onclick = () => triggerUpload()
  _initDropZone(zone)
}

function _initDropZone(zone) {
  if (!zone) return
  zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor='rgba(56,189,248,.5)'; zone.style.background='rgba(56,189,248,.06)' }, {passive:false})
  zone.addEventListener('dragleave', e => { if(!zone.contains(e.relatedTarget)){zone.style.borderColor='';zone.style.background=''} })
  zone.addEventListener('drop', e => { e.preventDefault(); zone.style.borderColor='';zone.style.background=''; const f=e.dataTransfer?.files?.[0]; if(f)_handleFileSelected(f,'signal') })
}

function triggerUpload() { _getFileInput('signal').click() }
function triggerGoldUpload() { _getFileInput('gold').click() }

function _getFileInput(type) {
  const id = `_file-input-${type}`
  let input = document.getElementById(id)
  if (!input) {
    input = document.createElement('input'); input.type='file'; input.id=id; input.accept='image/*,video/*'; input.style.display='none'
    document.body.appendChild(input)
    input.addEventListener('change', () => { if(input.files?.[0]) _handleFileSelected(input.files[0], type); input.value='' })
  }
  return input
}

async function _handleFileSelected(file, type) {
  const isImage = file.type.startsWith('image/')
  const isVideo = file.type.startsWith('video/')
  if (!isImage && !isVideo) { _showUploadError(type, 'Format non supporté.'); return }
  if (isImage && file.size > 10*1024*1024) { _showUploadError(type, 'Image trop lourde (max 10 MB)'); return }
  if (isVideo && file.size > 50*1024*1024) { _showUploadError(type, 'Vidéo trop lourde (max 50 MB)'); return }
  _showUploadLoading(type, file.name, file.size)
  if (isImage) { const r=new FileReader(); r.onload=e=>_showImagePreview(type,e.target.result,file.name,file.size); r.readAsDataURL(file) }
  else { _showVideoPreview(type, URL.createObjectURL(file), file.name, file.size) }
  try {
    const url = await _uploadToServer(file)
    if (type === 'signal') setUploadedMediaUrl(url)
    else _goldUploadedMediaUrl = url
    _markUploadSuccess(type, url)
  } catch(err) {
    if (type === 'signal') clearUploadedMediaUrl()
    else _goldUploadedMediaUrl = null
    _showUploadError(type, `Erreur: ${err.message}`)
  }
}

function _getZone(type) {
  return type === 'signal'
    ? document.querySelector('#media-upload .upload-zone')
    : document.querySelector('#gold-media-upload .upload-zone')
}

function _showUploadLoading(type, name, size) {
  _ensureSpinKeyframe()
  const z = _getZone(type); if(!z) return
  z.innerHTML = `<div style="display:flex;flex-direction:column;align-items:center;gap:8px;"><div style="width:24px;height:24px;border:2px solid rgba(56,189,248,.2);border-top-color:#38bdf8;border-radius:50%;animation:spin .7s linear infinite;"></div><p class="text-xs" style="color:#38bdf8;">Upload en cours…</p><p class="text-[10px]" style="color:#3f3f46;">${name} · ${_humanSize(size)}</p></div>`
}

function _showImagePreview(type, dataUrl, name, size) {
  _ensureSpinKeyframe()
  const z = _getZone(type); if(!z) return
  z.innerHTML = `<div style="position:relative;display:inline-block;"><img src="${dataUrl}" style="max-height:100px;max-width:100%;border-radius:8px;opacity:.5;display:block;"><div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;"><div style="width:20px;height:20px;border:2px solid rgba(56,189,248,.3);border-top-color:#38bdf8;border-radius:50%;animation:spin .7s linear infinite;"></div></div></div><p class="text-[10px] mt-2" style="color:#52525b;">${name}</p>`
}

function _showVideoPreview(type, objUrl, name, size) {
  _ensureSpinKeyframe()
  const z = _getZone(type); if(!z) return
  z.innerHTML = `<div style="position:relative;display:inline-block;"><video src="${objUrl}" muted style="max-height:100px;max-width:100%;border-radius:8px;opacity:.5;display:block;"></video><div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;"><div style="width:20px;height:20px;border:2px solid rgba(56,189,248,.3);border-top-color:#38bdf8;border-radius:50%;animation:spin .7s linear infinite;"></div></div></div><p class="text-[10px] mt-2" style="color:#52525b;">${name}</p>`
}

async function _uploadToServer(file) {
  const formData = new FormData(); formData.append('user_id','0'); formData.append('file',file)
  const res = await fetch(`${API_URL}/chat/media/upload`, {method:'POST', body:formData})
  if (!res.ok) { const e=await res.json().catch(()=>({})); throw new Error(e.detail||`HTTP ${res.status}`) }
  const data = await res.json()
  const url  = data.url||data.media_url||data.file_url||data.path
  if (!url) throw new Error('URL absente dans la réponse')
  return url
}

function _markUploadSuccess(type, url) {
  const z = _getZone(type); if(!z) return
  const media = z.querySelector('img,video'); if(media) media.style.opacity='1'
  z.querySelectorAll('div[style*="spin"]').forEach(s=>s.remove())
  const filename = url.split('/').pop().split('?')[0]
  const banner   = document.createElement('div')
  banner.style.cssText = 'display:flex;align-items:center;justify-content:space-between;margin-top:8px;padding:6px 10px;background:rgba(52,211,153,.08);border:1px solid rgba(52,211,153,.2);border-radius:8px;gap:8px;'
  banner.innerHTML = `<div style="display:flex;align-items:center;gap:6px;min-width:0;"><svg width="12" height="12" fill="none" stroke="#34d399" viewBox="0 0 24 24" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg><span style="font-size:11px;color:#34d399;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:180px;">${filename}</span></div><button onclick="_removeUpload('${type}')" style="background:none;border:none;cursor:pointer;color:#52525b;padding:0;display:flex;"><svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>`
  z.appendChild(banner)
  z.style.borderColor='rgba(52,211,153,.3)'; z.style.background='rgba(52,211,153,.03)'; z.style.cursor='default'; z.onclick=null
}

function _showUploadError(type, msg) {
  const z = _getZone(type); if(!z) return
  z.innerHTML = `<div style="display:flex;flex-direction:column;align-items:center;gap:6px;"><svg width="20" height="20" fill="none" stroke="#f87171" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg><p class="text-xs" style="color:#f87171;">${msg}</p><button onclick="_removeUpload('${type}')" style="font-size:11px;color:#38bdf8;background:none;border:none;cursor:pointer;">Réessayer</button></div>`
  z.style.borderColor='rgba(248,113,113,.3)'; z.style.background='rgba(248,113,113,.03)'; z.onclick=null
}

function _removeUpload(type = 'signal') {
  if (type === 'signal') { clearUploadedMediaUrl(); resetUploadZone() }
  else { _goldUploadedMediaUrl = null; const z=_getZone('gold'); if(z){z.innerHTML=`<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="margin:0 auto 5px;color:#52525b;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg><p class="text-xs" style="color:#71717a;">Glisser ou <span style="color:#fbbf24;cursor:pointer;">parcourir</span></p>`;z.style.borderColor='';z.style.background='';z.style.cursor='pointer';z.onclick=()=>triggerGoldUpload()} }
  const input = document.getElementById(`_file-input-${type}`); if(input) input.value=''
}

// ══════════════════════════════════════════════════════════════
// DRAWERS & MODALS
// ══════════════════════════════════════════════════════════════
function openModal(id)  { document.getElementById(id)?.classList.add('open') }
function closeModal(id) { document.getElementById(id)?.classList.remove('open') }

document.querySelectorAll('.modal-overlay').forEach(o =>
  o.addEventListener('click', e => { if(e.target===o) o.classList.remove('open') })
)
document.addEventListener('keydown', e => {
  if (e.key !== 'Escape') return
  closeAllDrawers()
  document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('open'))
})

// ══════════════════════════════════════════════════════════════
// UTILITAIRES
// ══════════════════════════════════════════════════════════════
function initials(name) {
  if (!name) return '?'
  return name.trim().split(' ').map(w=>w[0]).slice(0,2).join('').toUpperCase()
}

function fmtDate(iso) {
  if (!iso) return '—'
  try {
    const d    = new Date(iso)
    const now  = new Date()
    const diff = (now-d)/1000
    if (diff < 60)    return "À l'instant"
    if (diff < 3600)  return Math.floor(diff/60)+' min'
    if (diff < 86400) return d.toLocaleTimeString('fr',{hour:'2-digit',minute:'2-digit'})
    return d.toLocaleDateString('fr',{day:'2-digit',month:'2-digit'})
  } catch { return '—' }
}

function fmtShort(date) {
  return date.toLocaleDateString('fr',{day:'numeric',month:'long'})
}

function resultBadge(result) {
  const m = {tp:'badge-green',sl:'badge-red',partial:'badge-amber',cancelled:'badge-zinc',open:'badge-sky'}
  const l = {tp:'TP ✓',sl:'SL ✗',partial:'Partiel',cancelled:'Annulé',open:'Ouvert'}
  return `<span class="badge ${m[result]||'badge-zinc'}" style="font-size:9px;">${l[result]||result}</span>`
}

function behaviorLabel(b) {
  return {disciplined:'Discipliné ✓',early_exit:'Sortie tôt ⚡',sl_skip:'Ignore SL ⚠️',passive:'Passif'}[b]||b
}

// ══════════════════════════════════════════════════════════════
// INIT
// ══════════════════════════════════════════════════════════════
async function init() {
  resetUploadZone()
  setDir('buy')
  setGoldDir('buy')
  setConfidence(3)
  setCloseStatus('tp')
  calcLot()
  await loadDashboardStats()
  await loadSignals()
  await loadCategories()

  // Rafraîchissement auto toutes les 30s
  setInterval(() => {
    const journalView = document.getElementById('view-journal')
    if (journalView && journalView.style.display !== 'none') {
      loadSignals(); loadDashboardStats()
    }
    // Rafraîchir le prix Gold si on est sur le dashboard Gold
    const goldMain = document.getElementById('main-gold-dashboard')
    if (goldMain && goldMain.style.display !== 'none') {
      goldFetch('/price/live').then(d => {
        const p = parseFloat(d.price).toFixed(2)
        ;['gold-live-price-header','gold-live-price-main'].forEach(id => {
          const el = document.getElementById(id); if(el){el.textContent=p;el.className='ticker-live up'}
        })
      }).catch(()=>{})
    }
  }, 30000)
}
init()