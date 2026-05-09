// ══════════════════════════════════════════════════════════════
// CONFIGURATION API
// ══════════════════════════════════════════════════════════════
const API = 'http://54.226.165.244:8000/trading'
const API_URL = window.API_URL || 'http://54.226.165.244:8000'
let _uploadedMediaUrl = null

function getUploadedMediaUrl()    { return _uploadedMediaUrl }
function setUploadedMediaUrl(url) { _uploadedMediaUrl = url  }
function clearUploadedMediaUrl()  { _uploadedMediaUrl = null }

/** Taille lisible en Ko / Mo */
function _humanSize(bytes) {
  if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(0)} Ko`
  return `${(bytes / 1024 / 1024).toFixed(1)} Mo`
}

async function apiFetch(path, opts = {}) {
  try {
    const res = await fetch(API + path, {
      headers: { 'Content-Type': 'application/json' },
      ...opts,
    })
    if (!res.ok) {
      const err = await res.json().catch(() => ({}))
      throw new Error(err.detail || `HTTP ${res.status}`)
    }
    return await res.json()
  } catch (e) {
    console.error('API Error:', e)
    throw e
  }
}

// ══════════════════════════════════════════════════════════════
// TOAST NOTIFICATIONS
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
function showMainView(view, btn) {
  // Retire display:none sur toutes les sections principales
  ;['journal', 'paires', 'formules'].forEach(v => {
    const el = document.getElementById('main-' + v)
    if (!el) return
    el.style.cssText = v === view
      ? 'display:flex;flex-direction:column;height:100%;'
      : 'display:none;'
  })
  document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'))
  if (btn) btn.classList.add('active')

  if (view === 'paires')   loadPairs()
  if (view === 'formules') { loadFormStats(); loadForms(); loadFormsSummary() }
}

function switchView(view, el) {
  ;['journal', 'history', 'members', 'leaderboard', 'ia'].forEach(v => {
    const e = document.getElementById('view-' + v)
    if (e) e.style.display = 'none'
  })
  const target = document.getElementById('view-' + view)
  if (target) target.style.display = 'block'
  document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'))
  if (el) el.classList.add('active')

  if (view === 'history')     { loadHistory(); loadCrossedPerf() }
  if (view === 'journal') { loadSignals(); loadDashboardStats() }
  if (view === 'members')     loadPerformances()
  if (view === 'leaderboard') loadLeaderboard()
  if (view === 'ia')          { buildWeekOptions(); loadBilanHistory() }
}

// ══════════════════════════════════════════════════════════════
// DASHBOARD — VUE SIGNAUX
// ══════════════════════════════════════════════════════════════
const DAYS_FR = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam']

async function loadDashboardStats() {
  const period = document.getElementById('period-select').value
  try {
    const d = await apiFetch(`/stats?period=${period}`)
    document.getElementById('stats-grid').innerHTML = `
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Trades publiés</p><p class="text-xl font-light text-white tabular-nums">${d.trades_published ?? 0}</p><p class="text-[10px] mt-1" style="color:#52525b;">${period === 'week' ? 'cette semaine' : period === 'month' ? 'ce mois' : 'total'}</p></div>
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Win rate admin</p><p class="text-xl font-light tabular-nums pnl-pos">${d.win_rate_admin ?? '—'}%</p><div class="pbar mt-2"><div class="pbar-fill" style="width:${d.win_rate_admin ?? 0}%;background:#34d399;"></div></div></div>
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Taux engagement</p><p class="text-xl font-light tabular-nums" style="color:#38bdf8;">${d.engagement_rate ?? '—'}%</p><p class="text-[10px] mt-1" style="color:#52525b;">répondent "Je suis dedans"</p></div>
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Journaux collectés</p><p class="text-xl font-light text-white tabular-nums">${d.journals_collected ?? 0}</p><p class="text-[10px] mt-1" style="color:#52525b;">résultats membres</p></div>
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Trades ouverts</p><p class="text-xl font-light tabular-nums" style="color:#38bdf8;">${d.open_trades_count ?? 0}</p><span class="flex items-center gap-1.5 mt-1" style="font-size:10px;color:#38bdf8;"><span class="live-dot pulse" style="width:5px;height:5px;animation:pulse 2s infinite;"></span>En cours</span></div>
    `
    _avgCapital = d.avg_member_capital || 1000
    renderWeeklyChart(d.weekly_performance || [])
  } catch (e) { toast('Erreur chargement stats', 'error') }
}

let _avgCapital = 1000

function renderWeeklyChart(data) {
  if (!data.length) {
    document.getElementById('weekly-chart').innerHTML = '<div class="text-center text-xs pt-6" style="color:#3f3f46;">Aucun trade cette semaine</div>'
    return
  }
  const maxT = Math.max(...data.map(d => d.trades || 1), 1)
  const bars  = data.map(d => {
    const h   = Math.max(10, Math.round((d.trades / maxT) * 100))
    const cls = (d.wins || 0) >= (d.losses || 0) ? 'win-bar' : 'loss-bar'
    const day = d.day ? DAYS_FR[new Date(d.day).getDay()] : '—'
    return `<div class="flex flex-col items-center gap-1 flex-1"><div class="chart-bar ${cls} w-full" style="height:${h}%;" title="${d.trades} trade(s)"></div><span class="text-[9px]" style="color:#52525b;">${day}</span></div>`
  }).join('')
  document.getElementById('weekly-chart').innerHTML = bars
}

async function loadSignals() {
  try {
    const d       = await apiFetch('/signals?status=all&limit=20')
    const signals = d.signals || []
    if (!signals.length) {
      document.getElementById('signals-grid').innerHTML = '<div class="col-span-2 text-center text-xs py-8" style="color:#3f3f46;">Aucun signal pour le moment</div>'
      return
    }
    document.getElementById('signals-grid').innerHTML = signals.map(s => renderSignalCard(s)).join('')
  } catch (e) { toast('Erreur chargement signaux', 'error') }
}

function renderSignalCard(s) {
  const isOpen   = s.status === 'open'
  const isWin    = s.close_result === 'tp'
  const isLoss   = s.close_result === 'sl'
  const cls      = isOpen ? 'open-sig' : isWin ? 'win' : isLoss ? 'loss' : ''
  const accent   = isOpen ? '#38bdf8' : isWin ? '#34d399' : isLoss ? '#f87171' : '#fbbf24'
  const dirCls   = s.direction === 'long' ? 'dir-long' : 'dir-short'
  const dirLabel = s.direction === 'long' ? 'LONG' : 'SHORT'

  let badge = ''
  if (isOpen)                    badge = `<span class="flex items-center gap-1 text-[10px]" style="color:#38bdf8;"><span class="live-dot pulse" style="width:5px;height:5px;animation:pulse 2s infinite;"></span>En cours</span>`
  else if (s.close_result === 'tp')      badge = `<span class="badge badge-green" style="font-size:10px;">TP ✓</span>`
  else if (s.close_result === 'sl')      badge = `<span class="badge badge-red" style="font-size:10px;">SL ✗</span>`
  else if (s.close_result === 'partial') badge = `<span class="badge badge-amber" style="font-size:10px;">Partiel</span>`

  const pctDisplay  = s.result_percent != null ? `<p class="text-lg font-light tabular-nums ${s.result_percent >= 0 ? 'pnl-pos' : 'pnl-neg'}">${s.result_percent > 0 ? '+' : ''}${s.result_percent}%</p>` : ''
  const pipsDisplay = s.result_pips != null ? `<p class="text-[10px]" style="color:#52525b;">${s.result_pips > 0 ? '+' : ''}${s.result_pips} pips</p>` : ''

  const actionBtns = isOpen ? `
    <button class="btn-icon" onclick="event.stopPropagation();openFollowupModal(${s.id})" title="Commentaire de suivi" style="color:#38bdf8;background:rgba(56,189,248,.06);border:1px solid rgba(56,189,248,.15);">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
    </button>
    <button class="btn-icon" onclick="event.stopPropagation();openCloseModal(${s.id})" title="Clôturer" style="color:#fbbf24;background:rgba(251,191,36,.06);border:1px solid rgba(251,191,36,.2);">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
    </button>` : ''

  const participationBlock = `
    <div style="background:rgba(255,255,255,.025);border-radius:8px;padding:10px 12px;">
      <div class="flex items-center justify-between mb-2">
        <span class="text-[11px] font-medium text-zinc-300">Participation</span>
        <span class="text-[10px]" style="color:#52525b;">${s.count_in || 0} / ${s.total_participants || 0} répondus</span>
      </div>
      <div class="part-bar mb-2">
        <div class="part-seg" style="width:${s.total_participants ? Math.round((s.count_in || 0) / s.total_participants * 100) : 0}%;background:#34d399;border-radius:99px 0 0 99px;"></div>
        <div class="part-seg" style="width:${s.total_participants ? Math.round((s.count_out || 0) / s.total_participants * 100) : 0}%;background:#f87171;"></div>
        <div class="part-seg" style="flex:1;background:rgba(255,255,255,.06);border-radius:0 99px 99px 0;"></div>
      </div>
      <div class="eng-grid">
        <div class="eng-cell" style="background:rgba(52,211,153,.07);border:1px solid rgba(52,211,153,.15);"><p class="text-sm font-light tabular-nums" style="color:#34d399;">${s.count_in || 0}</p><p class="text-[9px] mt-0.5" style="color:#34d399;opacity:.8;">✅ Suis le trade</p></div>
        <div class="eng-cell" style="background:rgba(248,113,113,.07);border:1px solid rgba(248,113,113,.15);"><p class="text-sm font-light tabular-nums" style="color:#f87171;">${s.count_out || 0}</p><p class="text-[9px] mt-0.5" style="color:#f87171;opacity:.8;">❌ Ne prend pas</p></div>
        <div class="eng-cell" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);"><p class="text-sm font-light tabular-nums" style="color:#71717a;">${s.journals_submitted || 0}</p><p class="text-[9px] mt-0.5" style="color:#52525b;">📋 Journalisé</p></div>
      </div>
    </div>`

  return `
    <div class="signal-card ${cls} fadein" onclick="openSignalDetail(${s.id})">
      <div class="signal-accent" style="background:${accent};"></div>
      <div class="p-4">
        <div class="flex items-start justify-between mb-3">
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="text-base font-medium text-white" style="font-family:'Geist Mono',monospace;">${s.pair}</span>
              <span class="${dirCls}">${dirLabel}</span>
              ${badge}
            </div>
            <p class="text-[11px]" style="color:#52525b;">${s.timeframe || 'H4'} · ${fmtDate(s.published_at)} · ${s.category || '—'}</p>
          </div>
          <div class="flex items-center gap-1.5">
            ${pctDisplay || ''}
            ${actionBtns}
          </div>
        </div>
        ${pipsDisplay}
        <div class="grid grid-cols-4 gap-2 mb-3">
          <div class="stat-mini text-center" style="padding:7px 5px;"><p class="text-[9px] mb-1" style="color:#52525b;">Entrée</p><p class="text-xs tabular-nums text-white" style="font-family:'Geist Mono',monospace;">${s.entry_price}</p></div>
          ${s.tp1 ? `<div class="stat-mini text-center" style="padding:7px 5px;"><p class="text-[9px] mb-1" style="color:#34d399;">TP1</p><p class="text-xs tabular-nums" style="color:#34d399;font-family:'Geist Mono',monospace;">${s.tp1}</p></div>` : '<div></div>'}
          ${s.close_price ? `<div class="stat-mini text-center" style="padding:7px 5px;"><p class="text-[9px] mb-1" style="color:#52525b;">Clôture</p><p class="text-xs tabular-nums ${s.result_percent >= 0 ? 'pnl-pos' : 'pnl-neg'}" style="font-family:'Geist Mono',monospace;">${s.close_price}</p></div>` : '<div></div>'}
          ${s.sl ? `<div class="stat-mini text-center" style="padding:7px 5px;"><p class="text-[9px] mb-1" style="color:#f87171;">SL</p><p class="text-xs tabular-nums" style="color:#f87171;font-family:'Geist Mono',monospace;">${s.sl}</p></div>` : '<div></div>'}
        </div>
        ${participationBlock}
      </div>
    </div>`
}

// ══════════════════════════════════════════════════════════════
// SIGNAL DRAWER
// ══════════════════════════════════════════════════════════════
let _currentSignalId = null
let _drawerWS = null

function startDrawerTicker(binSymbol, signal) {
  if (_drawerWS) { _drawerWS.close(); _drawerWS = null }
  try {
    _drawerWS = new WebSocket(`wss://stream.binance.com:9443/ws/${binSymbol.toLowerCase()}@trade`)
    _drawerWS.onmessage = e => {
      const price = parseFloat(JSON.parse(e.data).p)
      const el    = document.getElementById('drawer-ticker')
      const dist  = document.getElementById('drawer-pips-dist')
      if (!el) { _drawerWS.close(); return }

      const decimals = signal.pair?.includes('JPY') || signal.pair?.includes('XAU') || signal.pair?.includes('NAS') ? 2 : 4
      el.textContent = price.toFixed(decimals)
      el.className   = 'ticker-live ' + (price >= signal.entry_price ? 'up' : 'down')

      if (dist && signal.tp1 && signal.sl) {
        const mult  = signal.pair?.includes('JPY') ? 100 : signal.pair?.includes('XAU') || signal.pair?.includes('NAS') ? 10 : 10000
        const toTP  = Math.round((signal.tp1 - price) * mult * (signal.direction === 'long' ? 1 : -1))
        const toSL  = Math.round((price - signal.sl)  * mult * (signal.direction === 'long' ? 1 : -1))
        dist.innerHTML = `
          <span style="color:${toTP > 0 ? '#34d399' : '#f87171'};">↑ TP1 : ${toTP > 0 ? '+' : ''}${toTP} pips</span>
          <span style="color:${toSL > 0 ? '#52525b' : '#f87171'};">↓ SL : ${toSL > 0 ? '-' : '+'}${Math.abs(toSL)} pips</span>
        `
      }
    }
    _drawerWS.onerror = () => {}
  } catch(e) {}
}

// Ferme le WS du drawer quand on ferme
function closeAllDrawers() {
  if (_drawerWS) { _drawerWS.close(); _drawerWS = null }
  ;['signal-drawer', 'member-drawer'].forEach(id => document.getElementById(id)?.classList.remove('open'))
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

    document.getElementById('signal-drawer-header').innerHTML = `
      <div>
        <p class="text-sm font-medium text-white">${s.pair} ${s.direction?.toUpperCase()} ${isOpen ? '<span class="live-dot pulse" style="display:inline-block;width:6px;height:6px;border-radius:50%;background:#38bdf8;margin-left:6px;animation:pulse 2s infinite;"></span>' : ''}</p>
        <p class="text-[11px] mt-0.5" style="color:#52525b;">${fmtDate(s.published_at)} · ${s.timeframe} · ${s.result_percent != null ? (s.result_percent > 0 ? '+' : '') + s.result_percent + '%' : 'En cours'}</p>
      </div>`

    // Ticker live si trade ouvert
    let tickerHtml = ''
    if (isOpen && s.pair) {
      const pairMap = {
        'EUR/USD':'EURUSDT','GBP/USD':'GBPUSDT','XAU/USD':'XAUUSDT',
        'BTC/USD':'BTCUSDT','GBP/JPY':'GBPJPY','NAS100':'NASUSDT'
      }
      const binSym = pairMap[s.pair]
      if (binSym) {
        tickerHtml = `
          <div style="background:rgba(56,189,248,.06);border:1px solid rgba(56,189,248,.15);border-radius:9px;padding:12px 14px;">
            <div class="flex items-center justify-between mb-2">
              <span class="text-[10px] font-medium" style="color:#52525b;">Prix actuel</span>
              <span class="flex items-center gap-1.5 text-[10px]" style="color:#38bdf8;"><span class="live-dot pulse" style="width:5px;height:5px;animation:pulse 2s infinite;"></span>Live</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="ticker-live up" id="drawer-ticker">—</span>
              <div class="text-right text-[10px]" style="color:#52525b;">
                <p>Entrée : <span style="font-family:'Geist Mono',monospace;color:#e4e4e7;">${s.entry_price}</span></p>
                ${s.tp1 ? `<p style="color:#34d399;">TP1 : ${s.tp1}</p>` : ''}
                ${s.sl  ? `<p style="color:#f87171;">SL : ${s.sl}</p>`   : ''}
              </div>
            </div>
            <div class="flex gap-4 mt-2 text-[10px]" id="drawer-pips-dist">
              <span style="color:#52525b;">Calcul en cours...</span>
            </div>
          </div>`
        // Démarre le WebSocket pour ce ticker
        setTimeout(() => startDrawerTicker(binSym, s), 100)
      }
    }

    // Participation
    const totalPart = s.participations?.length || 0
    const countIn   = s.count_in  || 0
    const countOut  = s.count_out || 0
    const pctIn     = totalPart > 0 ? Math.round(countIn  / totalPart * 100) : 0
    const pctOut    = totalPart > 0 ? Math.round(countOut / totalPart * 100) : 0

    const participantsList = (s.participations || []).slice(0, 8).map(p => `
      <div class="flex items-center justify-between py-1.5" style="border-bottom:1px solid rgba(255,255,255,.04);">
        <div class="flex items-center gap-2">
          <div class="av av-default" style="font-size:9px;width:22px;height:22px;">${initials(p.name)}</div>
          <span class="text-xs text-zinc-300">${p.name || '#' + p.user_id}</span>
        </div>
        <span class="${p.response === 'in' ? 'badge-green' : 'badge-red'} badge" style="font-size:9px;">
          ${p.response === 'in' ? '✅ Dedans' : '❌ Pas pris'}
        </span>
      </div>`).join('')

    // Commentaires de suivi
    const followups = (s.followup_comments || []).map(fc => `
      <div style="font-size:11px;padding:8px 10px;background:rgba(255,255,255,.025);border-radius:7px;margin-bottom:6px;border:1px solid rgba(255,255,255,.06);">
        <div class="flex items-center justify-between mb-1">
          <span style="color:#a78bfa;font-weight:500;">${fc.type}</span>
          <span style="color:#3f3f46;">${fmtDate(fc.sent_at)}</span>
        </div>
        <p style="color:#a1a1aa;">${fc.message}</p>
        ${fc.broadcast_id ? `<p style="font-size:10px;color:#3f3f46;margin-top:4px;">Envoyé à ${countIn} membres ✓</p>` : ''}
      </div>`).join('')

    // Résultats journal
    const journalBlock = js.total_journals ? `
      <div>
        <p class="slabel">Résultats membres (${js.total_journals} / ${countIn} réponses)</p>
        <div class="exit-bar mb-3">
          <div class="exit-row"><span style="color:#34d399;min-width:110px;">Discipliné TP</span><div class="exit-track"><div class="exit-fill" style="width:${Math.round((js.disciplined||0)/js.total_journals*100)}%;background:#34d399;"></div></div><span style="color:#34d399;">${js.disciplined||0}</span></div>
          <div class="exit-row"><span style="color:#fbbf24;min-width:110px;">Sortie anticipée</span><div class="exit-track"><div class="exit-fill" style="width:${Math.round((js.early_exits||0)/js.total_journals*100)}%;background:#fbbf24;"></div></div><span style="color:#fbbf24;">${js.early_exits||0}</span></div>
          <div class="exit-row"><span style="color:#f87171;min-width:110px;">Ignore SL</span><div class="exit-track"><div class="exit-fill" style="width:${Math.round((js.sl_skips||0)/js.total_journals*100)}%;background:#f87171;"></div></div><span style="color:#f87171;">${js.sl_skips||0}</span></div>
        </div>
        <div class="flex gap-4 text-[11px]">
          <span style="color:#34d399;">Win : ${js.wins||0}</span>
          <span style="color:#f87171;">Loss : ${js.losses||0}</span>
          <span style="color:#38bdf8;">Moy. réelle : ${js.avg_result_percent > 0 ? '+' : ''}${js.avg_result_percent ?? '—'}%</span>
          <span style="color:#38bdf8;">Moy. pips : ${js.avg_pips ?? '—'}</span>
        </div>
      </div>` : `
      <div style="padding:12px;background:rgba(255,255,255,.02);border-radius:8px;text-align:center;">
        <p class="text-xs" style="color:#3f3f46;">Aucun journal soumis pour ce signal</p>
      </div>`

    // Boutons d'action si trade ouvert
    const actionBlock = isOpen ? `
      <div class="flex gap-2">
        <button class="btn-ghost flex-1 justify-center" style="font-size:11px;" onclick="openFollowupModal(${s.id});closeAllDrawers()">
          💬 Commentaire suivi
        </button>
        <button class="btn-primary flex-1 justify-center" style="font-size:11px;" onclick="openCloseModal(${s.id});closeAllDrawers()">
          ✅ Clôturer le trade
        </button>
      </div>` : ''

    // Résultat final si clôturé
    const resultBlock = !isOpen ? `
      <div style="background:${s.close_result==='tp'?'rgba(52,211,153,.07)':'rgba(248,113,113,.07)'};border:1px solid ${s.close_result==='tp'?'rgba(52,211,153,.25)':'rgba(248,113,113,.25)'};border-radius:9px;padding:14px;">
        <div class="flex items-center justify-between mb-2">
          <span class="text-xs font-medium" style="color:${s.close_result==='tp'?'#34d399':'#f87171'};">${s.close_result==='tp'?'✅ TP atteint':s.close_result==='sl'?'❌ SL touché':'⚡ Clôture partielle'}</span>
          <span class="text-lg font-light tabular-nums ${s.result_percent>=0?'pnl-pos':'pnl-neg'}">${s.result_percent>0?'+':''}${s.result_percent}%</span>
        </div>
        <div class="flex gap-4 text-[11px]">
          <span style="color:#52525b;">Entrée : <span style="font-family:'Geist Mono',monospace;color:#e4e4e7;">${s.entry_price}</span></span>
          <span style="color:#52525b;">Clôture : <span style="font-family:'Geist Mono',monospace;color:#e4e4e7;">${s.close_price}</span></span>
          <span style="color:#52525b;">Pips : <span class="${s.result_pips>=0?'pnl-pos':'pnl-neg'}">${s.result_pips>0?'+':''}${s.result_pips}</span></span>
        </div>
      </div>` : ''

    document.getElementById('signal-drawer-content').innerHTML = `
      ${tickerHtml}
      ${resultBlock}
      <div>
        <p class="slabel">Niveaux</p>
        <div class="grid grid-cols-4 gap-2">
          <div class="stat-mini text-center"><p class="text-[9px] mb-1" style="color:#52525b;">Entrée</p><p class="text-sm tabular-nums text-white" style="font-family:'Geist Mono',monospace;">${s.entry_price}</p></div>
          ${s.tp1 ? `<div class="stat-mini text-center"><p class="text-[9px] mb-1" style="color:#34d399;">TP1</p><p class="text-sm tabular-nums pnl-pos" style="font-family:'Geist Mono',monospace;">${s.tp1}</p></div>` : '<div></div>'}
          ${s.sl  ? `<div class="stat-mini text-center"><p class="text-[9px] mb-1" style="color:#f87171;">SL</p><p class="text-sm tabular-nums pnl-neg" style="font-family:'Geist Mono',monospace;">${s.sl}</p></div>` : '<div></div>'}
          <div class="stat-mini text-center"><p class="text-[9px] mb-1" style="color:#52525b;">R:R</p><p class="text-sm tabular-nums text-white">1:${s.rr_ratio ?? '—'}</p></div>
        </div>
      </div>
      <div>
        <p class="slabel">Participation (${countIn} dedans · ${countOut} pas pris · ${totalPart - countIn - countOut} sans réponse)</p>
        <div class="part-bar mb-3" style="height:8px;">
          <div class="part-seg" style="width:${pctIn}%;background:#34d399;border-radius:99px 0 0 99px;"></div>
          <div class="part-seg" style="width:${pctOut}%;background:#f87171;"></div>
          <div class="part-seg" style="flex:1;background:rgba(255,255,255,.06);border-radius:0 99px 99px 0;"></div>
        </div>
        ${participantsList}
        ${totalPart > 8 ? `<p class="text-[10px] mt-2" style="color:#3f3f46;">+ ${totalPart - 8} autres membres</p>` : ''}
      </div>
      ${journalBlock}
      ${followups ? `<div><p class="slabel">Commentaires de suivi (${s.followup_comments.length})</p>${followups}</div>` : ''}
      ${actionBlock}
    `
  } catch (e) { toast('Erreur chargement signal', 'error') }
}

// ══════════════════════════════════════════════════════════════
// FOLLOWUP MODAL
// ══════════════════════════════════════════════════════════════
let _followupSignalId = null
let followupType = 'update'
const FU_LABELS = { update: '🔔 Mise à jour', invalidation: '⚠️ Invalidation', secure: '🔒 Sécurisation', encourage: '💪 Encouragement' }

function openFollowupModal(signalId) {
  _followupSignalId = signalId
  document.getElementById('followup-text').value = ''
  updateFollowupPreview()
  openModal('modal-followup')
}

function setFollowupType(t) {
  followupType = t
  const colors = { update: '#38bdf8', invalidation: '#fbbf24', secure: '#34d399', encourage: '#a78bfa' }
  ;['update', 'invalidation', 'secure', 'encourage'].forEach(id => {
    const btn = document.getElementById('fu-' + id)
    if (!btn) return
    btn.style.cssText = id === t
      ? `padding:6px 12px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid ${colors[id]}40;background:${colors[id]}18;color:${colors[id]};font-weight:500;`
      : `padding:6px 12px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:#71717a;`
  })
  updateFollowupPreview()
}

function updateFollowupPreview() {
  const text = document.getElementById('followup-text')?.value || ''
  const el   = document.getElementById('fu-preview')
  if (el) el.innerHTML = `${FU_LABELS[followupType]}<br><br>${text || '<span style="color:#3f3f46;">Saisissez votre message...</span>'}`
}

async function sendFollowup() {
  const msg = document.getElementById('followup-text').value.trim()
  if (!msg) { toast('Message requis', 'error'); return }
  const btn = document.getElementById('btn-send-followup')
  btn.disabled = true; btn.textContent = 'Envoi...'
  try {
    const r = await apiFetch(`/signals/${_followupSignalId}/followup`, {
      method: 'POST',
      body:   JSON.stringify({ type: followupType, message: msg }),
    })
    toast(`Commentaire envoyé à ${r.sent_to} membres ✓`, 'success')
    closeModal('modal-followup')
    loadSignals()
  } catch (e) { toast('Erreur envoi: ' + e.message, 'error') }
  finally { btn.disabled = false; btn.textContent = 'Envoyer →' }
}

// ══════════════════════════════════════════════════════════════
// CLOSE TRADE MODAL
// ══════════════════════════════════════════════════════════════
let _closeSignalId   = null
let _closeSignalData = null
let closeStatus = 'tp'

const FORM_TEMPLATES = {
  tp:        { msg: '🎉 <b>TP1 atteint !</b><br><br>As-tu clôturé ce trade ?',       btns: [{ text: "✅ Oui, j'ai pris le TP", cls: 'green' }, { text: '🔄 Encore dedans', cls: '' }, { text: '❌ Coupé en perte', cls: 'red' }] },
  sl:        { msg: '⚠️ <b>SL touché</b><br><br>As-tu respecté le stop ?',            btns: [{ text: '✅ Oui, coupé au SL', cls: 'green' }, { text: '📈 Gardé la position', cls: 'amber' }, { text: '🚫 Pas dedans', cls: 'gray' }] },
  partial:   { msg: '⚡ <b>Clôture partielle</b><br><br>Où en es-tu ?',               btns: [{ text: '✅ TP partiel pris', cls: 'green' }, { text: '🔄 Reste en position', cls: '' }, { text: '❌ Tout coupé', cls: 'red' }] },
  cancelled: { msg: "ℹ️ <b>Signal annulé</b><br><br>Le trade n'a pas été déclenché.", btns: [{ text: "✅ Je n'avais pas pris", cls: 'green' }, { text: "⚠️ J'avais ouvert", cls: 'amber' }] },
}

function openCloseModal(signalId) {
  _closeSignalId = signalId
  document.getElementById('close-modal-subtitle').textContent = `Signal #${signalId}`
  document.getElementById('close-price').value = ''
  document.getElementById('calc-pnl').textContent = '—'
  document.getElementById('calc-pct').textContent = ''
  setCloseStatus('tp')
  loadFormsForSelect()
  openModal('modal-close-trade')
}

async function loadFormsForSelect() {
  try {
    const forms = await apiFetch('/forms')
    const sel   = document.getElementById('close-form-select')
    sel.innerHTML = '<option value="">Pas de formulaire</option>' +
      (forms || []).map(f => `<option value="${f.id}">${f.name} (${f.form_type || f.type})</option>`).join('')
  } catch (e) {}
}

function setCloseStatus(s) {
  closeStatus = s
  const configs = {
    tp:        { border: 'rgba(52,211,153,.3)',  bg: 'rgba(52,211,153,.1)',  color: '#34d399' },
    sl:        { border: 'rgba(248,113,113,.3)', bg: 'rgba(248,113,113,.1)', color: '#f87171' },
    partial:   { border: 'rgba(251,191,36,.3)',  bg: 'rgba(251,191,36,.1)',  color: '#fbbf24' },
    cancelled: { border: 'rgba(113,113,122,.3)', bg: 'rgba(113,113,122,.1)', color: '#a1a1aa' },
  }
  // BUG FIX : id="close-cancelled" (pas close-cancel)
  ;['tp', 'sl', 'partial', 'cancelled'].forEach(id => {
    const btn = document.getElementById('close-' + id)
    if (!btn) return
    if (id === s) {
      const c = configs[s]
      btn.style.cssText = `flex:1;padding:9px 6px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid ${c.border};background:${c.bg};color:${c.color};font-weight:500;`
    } else {
      btn.style.cssText = `flex:1;padding:9px 6px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid rgba(255,255,255,.07);background:rgba(255,255,255,.03);color:#71717a;`
    }
  })
  document.getElementById('price-block').style.display = s === 'cancelled' ? 'none' : 'grid'
  const tpl = FORM_TEMPLATES[s]
  if (tpl) {
    document.getElementById('form-bbl').innerHTML = tpl.msg
    const btnsEl = document.getElementById('form-btns')
    btnsEl.innerHTML = ''
    tpl.btns.forEach(b => {
      const el = document.createElement('div')
      el.className = `tg-btn ${b.cls}`; el.style.fontSize = '11px'; el.textContent = b.text
      btnsEl.appendChild(el)
    })
  }
  calcPnL()
}

function calcPnL() {
  if (!_closeSignalId) return
  const price = parseFloat(document.getElementById('close-price')?.value)
  if (!isNaN(price) && price > 0) {
    const pnl = document.getElementById('calc-pnl')
    pnl.textContent  = 'Prix saisi ✓'
    pnl.style.color  = '#38bdf8'
  }
}

async function confirmClose() {
  const price    = document.getElementById('close-price')?.value
  const formId   = document.getElementById('close-form-select')?.value
  const sendForm = document.getElementById('form-toggle')?.classList.contains('on')
  const btn = document.getElementById('btn-close-confirm')
  btn.disabled = true; btn.textContent = 'Clôture...'

  if (closeStatus !== 'cancelled' && !price) {
    toast('Prix de clôture requis', 'error')
    btn.disabled = false; btn.textContent = 'Clôturer & envoyer formulaire'
    return
  }

  try {
    const payload = {
      close_price:  parseFloat(price) || 0,
      close_result: closeStatus,
    }
    if (formId && sendForm) {
      payload.form_id      = parseInt(formId)
      payload.send_form_to = 'participated'
    }
    await apiFetch(`/signals/${_closeSignalId}/close`, { method: 'PATCH', body: JSON.stringify(payload) })
    toast('Trade clôturé ✓' + (formId && sendForm ? ' — Formulaire envoyé' : ''), 'success')
    setTimeout(loadSignals, 1000) // laisse le temps à l'API de traiter
    closeModal('modal-close-trade')
    loadSignals()
    loadDashboardStats()
  } catch (e) { toast('Erreur clôture: ' + e.message, 'error') }
  finally { btn.disabled = false; btn.textContent = 'Clôturer & envoyer formulaire' }
}

// ══════════════════════════════════════════════════════════════
// UPLOAD — INTÉGRATION COMPLÈTE
// ══════════════════════════════════════════════════════════════

/** Injecte @keyframes spin une seule fois */
function _ensureSpinKeyframe() {
  if (document.getElementById('_spin-style')) return
  const s = document.createElement('style')
  s.id          = '_spin-style'
  s.textContent = '@keyframes spin{to{transform:rotate(360deg)}}'
  document.head.appendChild(s)
}

/** Remet la zone upload à son état initial */
function resetUploadZone() {
  const zone = document.querySelector('.upload-zone')
  if (!zone) return
  zone.style.borderColor = ''
  zone.style.background  = ''
  zone.innerHTML = `
    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
         stroke-width="1.5" style="margin:0 auto 6px;color:#52525b;">
      <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
      <polyline points="17 8 12 3 7 8"/>
      <line x1="12" y1="3" x2="12" y2="15"/>
    </svg>
    <p class="text-xs" style="color:#71717a;">Glisser un fichier ou <span style="color:#38bdf8;cursor:pointer;">parcourir</span></p>
    <p class="text-[10px] mt-1" style="color:#3f3f46;">Image (max 10 MB) · Vidéo (max 50 MB)</p>
  `
  // Réattache le click handler après reset du innerHTML
  zone.onclick = () => triggerUpload()
  // Réattache le drag & drop
  _initDropZone(zone)
}

/** Initialise le drag & drop sur la zone */
function _initDropZone(zone) {
  if (!zone) zone = document.querySelector('.upload-zone')
  if (!zone) return

  zone.addEventListener('dragover', e => {
    e.preventDefault()
    zone.style.borderColor = 'rgba(56,189,248,.5)'
    zone.style.background  = 'rgba(56,189,248,.06)'
  }, { passive: false })

  zone.addEventListener('dragleave', e => {
    if (!zone.contains(e.relatedTarget)) {
      zone.style.borderColor = ''
      zone.style.background  = ''
    }
  })

  zone.addEventListener('drop', e => {
    e.preventDefault()
    zone.style.borderColor = ''
    zone.style.background  = ''
    const file = e.dataTransfer?.files?.[0]
    if (file) _handleFileSelected(file)
  })
}

/** Ouvre le sélecteur de fichier natif */
function triggerUpload() {
  _getFileInput().click()
}

/** Crée (ou récupère) l'input file caché */
function _getFileInput() {
  let input = document.getElementById('_hidden-file-input')
  if (!input) {
    input             = document.createElement('input')
    input.type        = 'file'
    input.id          = '_hidden-file-input'
    input.accept      = 'image/*,video/*'
    input.style.display = 'none'
    document.body.appendChild(input)

    input.addEventListener('change', () => {
      if (input.files && input.files[0]) _handleFileSelected(input.files[0])
      // reset pour permettre de re-sélectionner le même fichier
      input.value = ''
    })
  }
  return input
}

/** Gère le fichier sélectionné : validation → preview → upload */
async function _handleFileSelected(file) {
  const isImage = file.type.startsWith('image/')
  const isVideo = file.type.startsWith('video/')

  // ── Validation type ──────────────────────────────────────────
  if (!isImage && !isVideo) {
    _showUploadError('Format non supporté. Utilisez une image ou une vidéo.')
    return
  }

  // ── Validation taille ────────────────────────────────────────
  const MAX_IMAGE = 10 * 1024 * 1024  // 10 MB
  const MAX_VIDEO = 50 * 1024 * 1024  // 50 MB
  if (isImage && file.size > MAX_IMAGE) {
    _showUploadError(`Image trop lourde (max 10 MB · actuel : ${(file.size / 1024 / 1024).toFixed(1)} MB)`)
    return
  }
  if (isVideo && file.size > MAX_VIDEO) {
    _showUploadError(`Vidéo trop lourde (max 50 MB · actuel : ${(file.size / 1024 / 1024).toFixed(1)} MB)`)
    return
  }

  // ── Preview locale immédiate ─────────────────────────────────
  _showUploadLoading(file.name, file.size)

  if (isImage) {
    const reader = new FileReader()
    reader.onload = e => _showImagePreview(e.target.result, file.name, file.size)
    reader.readAsDataURL(file)
  } else {
    const objUrl = URL.createObjectURL(file)
    _showVideoPreview(objUrl, file.name, file.size)
  }

  // ── Upload vers le backend ───────────────────────────────────
  try {
    const url = await _uploadToServer(file)
    setUploadedMediaUrl(url)
    _markUploadSuccess(url)
  } catch (err) {
    clearUploadedMediaUrl()
    _showUploadError(`Erreur upload : ${err.message}`)
  }
}

/** Spinner pendant l'upload */
function _showUploadLoading(name, size) {
  _ensureSpinKeyframe()
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
}

/** Preview image dans la zone */
function _showImagePreview(dataUrl, name, size) {
  _ensureSpinKeyframe()
  const zone = document.querySelector('.upload-zone')
  if (!zone) return
  zone.innerHTML = `
    <div style="position:relative;display:inline-block;">
      <img src="${dataUrl}" alt="preview"
           style="max-height:120px;max-width:100%;border-radius:8px;object-fit:cover;opacity:.5;display:block;">
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
  _ensureSpinKeyframe()
  const zone = document.querySelector('.upload-zone')
  if (!zone) return
  zone.innerHTML = `
    <div style="position:relative;display:inline-block;">
      <video src="${objUrl}" muted
             style="max-height:120px;max-width:100%;border-radius:8px;object-fit:cover;opacity:.5;display:block;"></video>
      <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">
        <div style="width:20px;height:20px;border:2px solid rgba(56,189,248,.3);
                    border-top-color:#38bdf8;border-radius:50%;animation:spin .7s linear infinite;"></div>
      </div>
    </div>
    <p class="text-[10px] mt-2" style="color:#52525b;">${name} · ${_humanSize(size)}</p>
  `
}

/** Upload multipart vers POST /media/upload */
async function _uploadToServer(file) {
  const formData = new FormData()
  formData.append('user_id', '0')
  formData.append('file', file)

  const res = await fetch(`${API_URL}/chat/media/upload`, {
    method: 'POST',
    body:   formData,
    // Ne pas setter Content-Type : le browser gère le boundary multipart
  })

  if (!res.ok) {
    const err = await res.json().catch(() => ({}))
    throw new Error(err.detail || `HTTP ${res.status}`)
  }

  const data = await res.json()
  // Compatibilité avec plusieurs formats de réponse backend
  const url = data.url || data.media_url || data.file_url || data.path
  if (!url) throw new Error('URL de fichier absente dans la réponse serveur')
  return url
}

/** ✅ Upload réussi — affiche le bandeau de succès */
function _markUploadSuccess(url) {
  const zone = document.querySelector('.upload-zone')
  if (!zone) return

  // Rend le média visible (enlève l'opacité)
  const media = zone.querySelector('img, video')
  if (media) media.style.opacity = '1'

  // Retire le spinner
  const spinners = zone.querySelectorAll('div[style*="spin"]')
  spinners.forEach(s => s.remove())

  // Bandeau succès + bouton supprimer
  const filename = url.split('/').pop().split('?')[0]
  const banner   = document.createElement('div')
  banner.style.cssText = `
    display:flex;align-items:center;justify-content:space-between;
    margin-top:8px;padding:6px 10px;
    background:rgba(52,211,153,.08);border:1px solid rgba(52,211,153,.2);
    border-radius:8px;gap:8px;
  `
  banner.innerHTML = `
    <div style="display:flex;align-items:center;gap:6px;min-width:0;">
      <svg width="12" height="12" fill="none" stroke="#34d399" viewBox="0 0 24 24" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
      <span style="font-size:11px;color:#34d399;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;max-width:200px;" title="${url}">${filename}</span>
    </div>
    <button onclick="_removeUpload()" style="background:none;border:none;cursor:pointer;color:#52525b;padding:0;display:flex;align-items:center;flex-shrink:0;" title="Supprimer">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
  `
  zone.appendChild(banner)
  zone.style.borderColor = 'rgba(52,211,153,.3)'
  zone.style.background  = 'rgba(52,211,153,.03)'
  zone.style.cursor      = 'default'
  zone.onclick           = null // désactive le click upload quand succès
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
      <button onclick="resetUploadZone()" style="font-size:11px;color:#38bdf8;background:none;border:none;cursor:pointer;margin-top:2px;">Réessayer</button>
    </div>
  `
  zone.style.borderColor = 'rgba(248,113,113,.3)'
  zone.style.background  = 'rgba(248,113,113,.03)'
  zone.onclick           = null
}

/** Supprime le fichier uploadé et remet la zone à zéro */
function _removeUpload() {
  clearUploadedMediaUrl()
  // Révoque l'éventuel object URL vidéo pour libérer la mémoire
  const video = document.querySelector('.upload-zone video')
  if (video && video.src.startsWith('blob:')) URL.revokeObjectURL(video.src)
  // Reset de l'input file
  const input = document.getElementById('_hidden-file-input')
  if (input) input.value = ''
  resetUploadZone()
}

// ══════════════════════════════════════════════════════════════
// MODAL PUBLIER
// ══════════════════════════════════════════════════════════════
let pubStep  = 1
let tradeDir = 'long'

function setDir(d) {
  tradeDir = d
  const base = 'flex:1;padding:7px;border-radius:8px;cursor:pointer;font-size:12px;font-family:Geist,sans-serif;'
  document.getElementById('dir-long').style.cssText  = base + (d === 'long'  ? 'border:1px solid rgba(52,211,153,.3);background:rgba(52,211,153,.1);color:#34d399;font-weight:500;' : 'border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:#71717a;')
  document.getElementById('dir-short').style.cssText = base + (d === 'short' ? 'border:1px solid rgba(248,113,113,.3);background:rgba(248,113,113,.1);color:#f87171;font-weight:500;' : 'border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:#71717a;')
  updatePreview()
}

function calcRR() {
  const e  = parseFloat(document.getElementById('sig-entry')?.value)
  const t  = parseFloat(document.getElementById('sig-tp1')?.value)
  const s  = parseFloat(document.getElementById('sig-sl')?.value)
  const el = document.getElementById('rr-display')
  if (!isNaN(e) && !isNaN(t) && !isNaN(s) && Math.abs(e - s) > 0) {
    const rr = Math.abs(t - e) / Math.abs(e - s)
    el.textContent = `R:R 1:${rr.toFixed(1)}`
    el.style.color = '#38bdf8'
    const slPips  = Math.abs(e - s) * 10000
    const pip     = parseFloat(document.getElementById('calc-pair-sel')?.value) || 10
    const lot     = (_avgCapital * 0.02) / (slPips * pip)
    const riskUsd = _avgCapital * 0.02
    document.getElementById('avg-cap-display').textContent  = `${Math.round(_avgCapital)} $`
    document.getElementById('risk-usd-display').textContent = `${riskUsd.toFixed(2)} $`
    document.getElementById('lot-sl-display').textContent   = `${Math.round(slPips)} pips`
    document.getElementById('lot-suggested').textContent    = lot.toFixed(2)
  } else {
    el.textContent = 'R:R —'; el.style.color = '#52525b'
  }
}

function updatePreview() {
  const pair  = document.getElementById('sig-pair')?.value  || '—'
  const entry = document.getElementById('sig-entry')?.value || '—'
  const tp1   = document.getElementById('sig-tp1')?.value   || '—'
  const sl    = document.getElementById('sig-sl')?.value    || '—'
  const note  = document.getElementById('sig-note')?.value  || ''
  const dir   = tradeDir === 'long' ? '📈 LONG' : '📉 SHORT'
  const msg   = document.getElementById('tg-preview-msg')
  if (msg) msg.innerHTML = `📊 <b>Signal de Trading</b><br><br>🔷 Paire : <b>${pair}</b><br>${tradeDir === 'long' ? '📈' : '📉'} Direction : <b>${dir}</b><br>🎯 Entrée : <b>${entry}</b><br>✅ TP1 : <b>${tp1}</b><br>❌ SL : <b>${sl}</b>${note ? `<br><br><i>${note}</i>` : ''}`
}

function nextStep() {
  if (pubStep === 1) {
    if (!document.getElementById('sig-entry').value.trim()) {
      toast("Prix d'entrée requis", 'error')
      return
    }
    goPubStep(2)
  } else if (pubStep === 2) {
    const cat = document.querySelector('#dest-block-category select')?.value
    if (!cat || cat === '') {
      toast('Sélectionnez une catégorie destinataire', 'error')
      return
    }
    updatePreview()
    goPubStep(3)
    document.getElementById('btn-next').textContent = '📡 Publier le signal'
    document.getElementById('btn-next').style.cssText += ';background:#34d399;color:#052e16;'
  } else {
    publishSignal()
  }
}

function prevStep() {
  if (pubStep > 1) {
    goPubStep(pubStep - 1)
    document.getElementById('btn-next').textContent   = 'Continuer →'
    document.getElementById('btn-next').style.background = ''
    document.getElementById('btn-next').style.color   = ''
  }
}

function goPubStep(n) {
  pubStep = n
  ;[1, 2, 3].forEach(i => {
    document.getElementById('pub-s' + i).style.display = i === n ? 'block' : 'none'
    const dot = document.getElementById('sdot-' + i)
    const lbl = document.getElementById('slbl-' + i)
    dot.className  = 'step-dot' + (i < n ? ' done' : i === n ? ' active' : '')
    lbl.style.color = i === n ? '#38bdf8' : i < n ? '#34d399' : '#3f3f46'
  })
  document.getElementById('btn-prev').style.display = n > 1 ? 'inline-flex' : 'none'
}

async function publishSignal() {
  const btn = document.getElementById('btn-next')
  btn.disabled = true; btn.textContent = 'Publication...'

  // BUG FIX : lire la catégorie depuis le <select> (pas des radios inexistants)
  const categorySelect = document.querySelector('#dest-block-category select')
  const category = categorySelect?.value && categorySelect.value !== ''
  ? categorySelect.value
  : null

  if (!category) {
    toast('Veuillez sélectionner une catégorie destinataire', 'error')
    btn.disabled = false; btn.textContent = 'Continuer →'
    return
 }

  // BUG FIX : inclure media_url dans le payload
  const uploadedUrl  = getUploadedMediaUrl()
  const manualFileId = document.querySelector('#media-upload input[type="text"]')?.value?.trim() || null
  const media_url    = uploadedUrl || manualFileId || null

  const payload = {
    pair:          document.getElementById('sig-pair').value,
    direction:     tradeDir,
    timeframe:     document.getElementById('sig-tf').value,
    entry_price:   parseFloat(document.getElementById('sig-entry').value),
    tp1:           parseFloat(document.getElementById('sig-tp1').value)  || null,
    tp2:           parseFloat(document.getElementById('sig-tp2').value)  || null,
    sl:            parseFloat(document.getElementById('sig-sl').value)   || null,
    note:          document.getElementById('sig-note').value || null,
    category,
    lot_suggested: parseFloat(document.getElementById('lot-suggested').textContent) || null,
    // BUG FIX : media_url inclus conditionnellement
    ...(media_url ? { media_url } : {}),
  }

  try {
    await apiFetch('/signals', { method: 'POST', body: JSON.stringify(payload) })
    toast('Signal publié et broadcast lancé ✓', 'success')
    closeModal('modal-publish')
    resetPubModal()
    loadSignals()
    loadDashboardStats()
  } catch (e) { toast('Erreur publication: ' + e.message, 'error') }
  finally {
    btn.disabled        = false
    btn.textContent     = 'Continuer →'
    btn.style.background = ''
    btn.style.color     = ''
  }
}

function resetPubModal() {
  pubStep = 1; goPubStep(1)
  document.getElementById('btn-next').textContent    = 'Continuer →'
  document.getElementById('btn-next').style.background = ''
  document.getElementById('btn-next').style.color    = ''
  ;['sig-entry', 'sig-tp1', 'sig-tp2', 'sig-sl', 'sig-note'].forEach(id => {
    const el = document.getElementById(id); if (el) el.value = ''
  })
  setDir('long')
  // Reset zone upload + media url
  _removeUpload()
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

function histPage(dir) {
  _histPage = Math.max(0, _histPage + dir)
  loadHistory()
}

async function loadHistory() {
  const search = document.getElementById('hist-search')?.value || ''
  const pair   = document.getElementById('hist-pair-filter')?.value || ''
  const offset = _histPage * HIST_LIMIT
  const qs     = `?status=${_histStatus}&limit=${HIST_LIMIT}&offset=${offset}${search ? '&search=' + encodeURIComponent(search) : ''}${pair ? '&pair=' + encodeURIComponent(pair) : ''}`
  const body   = document.getElementById('history-table-body')
  body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#3f3f46;">Chargement...</div>'
  try {
    const d    = await apiFetch('/history' + qs)
    const rows = d.history || []
    if (!rows.length) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#3f3f46;">Aucun résultat</div>'; return }
    body.innerHTML = rows.map(r => `
      <div class="tbl-row ${r.took_trade ? '' : 'not-taken'}" onclick="openMemberDrawer(${r.member_id})">
        <div class="flex items-center gap-2" style="flex:1;"><div class="av av-default" style="font-size:9px;">${initials(r.member_name)}</div><div><p class="text-xs text-zinc-200">${r.member_name || '—'}</p><p class="text-[9px]" style="color:#52525b;">#${r.member_id}</p></div></div>
        <div style="width:90px;"><p class="text-xs" style="font-family:'Geist Mono',monospace;color:#e4e4e7;">${r.pair}</p>${r.took_trade ? resultBadge(r.signal_result) : '<span class="badge badge-zinc" style="font-size:9px;">Pas pris</span>'}</div>
        <span class="text-xs tabular-nums" style="color:#71717a;width:70px;font-family:'Geist Mono',monospace;">${r.entry_price ?? '—'}</span>
        <span class="text-xs tabular-nums" style="color:#71717a;width:70px;font-family:'Geist Mono',monospace;">${r.exit_price ?? '—'}</span>
        <span class="text-xs tabular-nums" style="width:55px;${r.result_pips > 0 ? 'color:#34d399;' : r.result_pips < 0 ? 'color:#f87171;' : ''}">${r.result_pips != null ? (r.result_pips > 0 ? '+' : '') + r.result_pips : '—'}</span>
        <span class="text-xs tabular-nums" style="width:70px;${r.gain_usd > 0 ? 'color:#34d399;' : r.gain_usd < 0 ? 'color:#f87171;' : 'color:#71717a;'}">${r.gain_usd != null ? (r.gain_usd > 0 ? '+' : '') + r.gain_usd.toFixed(2) + ' $' : '—'}</span>
        <span class="text-xs tabular-nums" style="color:#e4e4e7;width:80px;">${r.capital_after ? r.capital_after.toFixed(0) + ' $' : '—'}</span>
        <div style="width:100px;">${r.behavior ? `<span class="beh-tag ${r.behavior}" style="font-size:9px;">${behaviorLabel(r.behavior)}</span>` : ''}</div>
        <div style="width:36px;">${r.capture_url ? `<div style="width:28px;height:20px;background:rgba(52,211,153,.1);border-radius:4px;border:1px solid rgba(52,211,153,.2);display:flex;align-items:center;justify-content:center;cursor:pointer;" onclick="event.stopPropagation();window.open('${r.capture_url}')"><svg width="10" height="10" fill="none" stroke="#34d399" viewBox="0 0 24 24" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg></div>` : ''}</div>
      </div>`).join('')
    const total = d.total || 0
    document.getElementById('hist-pagination-label').textContent = `${offset + 1}–${Math.min(offset + HIST_LIMIT, total)} sur ${total}`
    document.getElementById('hist-page-label').textContent       = `${_histPage + 1} / ${Math.max(1, Math.ceil(total / HIST_LIMIT))}`
    document.getElementById('hist-prev').disabled = _histPage === 0
    document.getElementById('hist-next').disabled = offset + HIST_LIMIT >= total
  } catch (e) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#f87171;">Erreur chargement</div>' }
}

async function loadCrossedPerf(period = 'day') {
  const container = document.getElementById('perf-chart-container')
  container.innerHTML = '<div class="text-center text-xs pt-16" style="color:#3f3f46;">Chargement...</div>'
  try {
    const d       = await apiFetch(`/history/performance-chart?period=${period}`)
    const admin   = d.admin_curve   || []
    const members = d.members_curve || []

    if (!admin.length && !members.length) {
      container.innerHTML = '<div class="text-center text-xs pt-16" style="color:#3f3f46;">Pas encore de données</div>'
      return
    }

    const allPcts = [...admin.map(p => p.cumulative_pct), ...members.map(p => p.cumulative_pct), 0]
    const minV    = Math.min(...allPcts)
    const maxV    = Math.max(...allPcts)
    const range   = maxV - minV || 1
    const W = 800, H = 160, PAD = 30

    function toY(v) { return PAD + (1 - (v - minV) / range) * (H - PAD * 2) }
    function toX(i, len) { return PAD + (i / Math.max(len - 1, 1)) * (W - PAD * 2) }

    // Génère le path SVG
    function makePath(points, toXFn) {
      return points.map((p, i) => `${i === 0 ? 'M' : 'L'}${toXFn(i, points.length).toFixed(1)},${toY(p.cumulative_pct).toFixed(1)}`).join(' ')
    }

    function makeArea(points, toXFn) {
      if (points.length < 2) return ''
      const last = points.length - 1
      return `${makePath(points, toXFn)} L${toXFn(last, points.length).toFixed(1)},${H - PAD} L${PAD},${H - PAD} Z`
    }

    // Lignes de grille + labels Y
    const gridLines = []
    const steps     = 4
    for (let i = 0; i <= steps; i++) {
      const val = minV + (range / steps) * i
      const y   = toY(val).toFixed(1)
      gridLines.push(`
        <line x1="${PAD}" y1="${y}" x2="${W - PAD}" y2="${y}" stroke="rgba(255,255,255,.05)" stroke-width="1"/>
        <text x="${PAD - 4}" y="${parseFloat(y) + 4}" fill="#3f3f46" font-size="9" text-anchor="end">${val >= 0 ? '+' : ''}${val.toFixed(1)}%</text>
      `)
    }

    // Labels X
    const xLabels = []
    const labelSrc = admin.length >= members.length ? admin : members
    const step     = Math.max(1, Math.floor(labelSrc.length / 5))
    labelSrc.forEach((p, i) => {
      if (i % step !== 0 && i !== labelSrc.length - 1) return
      const x = toXFn(i, labelSrc.length)
      xLabels.push(`<text x="${x.toFixed(1)}" y="${H - 4}" fill="#3f3f46" font-size="9" text-anchor="middle">${p.period || ''}</text>`)
    })

    function toXFn(i, len) { return toX(i, len) }

    // Points interactifs
    const adminDots   = admin.map((p, i)   => `<circle cx="${toX(i, admin.length).toFixed(1)}"   cy="${toY(p.cumulative_pct).toFixed(1)}"   r="3" fill="#38bdf8" opacity="0.8"><title>${p.period} : ${p.cumulative_pct > 0 ? '+' : ''}${p.cumulative_pct}% (${p.trades} trades)</title></circle>`).join('')
    const memberDots  = members.map((p, i) => `<circle cx="${toX(i, members.length).toFixed(1)}" cy="${toY(p.cumulative_pct).toFixed(1)}" r="3" fill="#34d399" opacity="0.8"><title>${p.period} : ${p.cumulative_pct > 0 ? '+' : ''}${p.cumulative_pct}% (${p.journals} journaux)</title></circle>`).join('')

    // Ligne zéro
    const zeroY = toY(0).toFixed(1)

    container.style.position = 'relative'
    container.innerHTML = `
      <svg width="100%" height="100%" viewBox="0 0 ${W} ${H}" preserveAspectRatio="xMidYMid meet" style="display:block;">
        <defs>
          <linearGradient id="gradAdmin" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#38bdf8" stop-opacity=".15"/>
            <stop offset="100%" stop-color="#38bdf8" stop-opacity="0"/>
          </linearGradient>
          <linearGradient id="gradMember" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#34d399" stop-opacity=".2"/>
            <stop offset="100%" stop-color="#34d399" stop-opacity="0"/>
          </linearGradient>
        </defs>

        <!-- Grille -->
        ${gridLines.join('')}
        ${xLabels.join('')}

        <!-- Ligne zéro -->
        <line x1="${PAD}" y1="${zeroY}" x2="${W - PAD}" y2="${zeroY}" stroke="rgba(255,255,255,.12)" stroke-width="1" stroke-dasharray="4 3"/>

        <!-- Area admin -->
        ${admin.length > 1 ? `<path d="${makeArea(admin, toXFn)}" fill="url(#gradAdmin)"/>` : ''}

        <!-- Area membres -->
        ${members.length > 1 ? `<path d="${makeArea(members, toXFn)}" fill="url(#gradMember)"/>` : ''}

        <!-- Courbe admin -->
        ${admin.length > 1 ? `<path d="${makePath(admin, toXFn)}" fill="none" stroke="#38bdf8" stroke-width="1.5" stroke-dasharray="5 3"/>` : ''}

        <!-- Courbe membres -->
        ${members.length > 1 ? `<path d="${makePath(members, toXFn)}" fill="none" stroke="#34d399" stroke-width="2"/>` : ''}

        <!-- Points -->
        ${adminDots}
        ${memberDots}
      </svg>

      <!-- Légende -->
      <div style="position:absolute;top:8px;right:${PAD}px;display:flex;gap:12px;">
        <span style="font-size:10px;color:#38bdf8;display:flex;align-items:center;gap:4px;">
          <svg width="16" height="2"><line x1="0" y1="1" x2="16" y2="1" stroke="#38bdf8" stroke-width="1.5" stroke-dasharray="4 2"/></svg>
          Admin
        </span>
        <span style="font-size:10px;color:#34d399;display:flex;align-items:center;gap:4px;">
          <svg width="16" height="2"><line x1="0" y1="1" x2="16" y2="1" stroke="#34d399" stroke-width="2"/></svg>
          Membres
        </span>
      </div>
    `
  } catch (e) {
    container.innerHTML = '<div class="text-center text-xs pt-16" style="color:#3f3f46;">Erreur graphique</div>'
  }
}
// ══════════════════════════════════════════════════════════════
// PERFORMANCES
// ══════════════════════════════════════════════════════════════
let _perfPage = 0; const PERF_LIMIT = 20
let _perfSearchTimer = null
function debouncePerf() { clearTimeout(_perfSearchTimer); _perfSearchTimer = setTimeout(() => { _perfPage = 0; loadPerformances() }, 400) }
function perfPage(dir) { _perfPage = Math.max(0, _perfPage + dir); loadPerformances() }

async function loadPerformances() {
  const search  = document.getElementById('perf-search')?.value || ''
  const sort_by = document.getElementById('perf-sort')?.value   || 'win_rate'
  const offset  = _perfPage * PERF_LIMIT
  const qs      = `?sort_by=${sort_by}&limit=${PERF_LIMIT}&offset=${offset}${search ? '&search=' + encodeURIComponent(search) : ''}`
  const body    = document.getElementById('performances-body')
  body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#3f3f46;">Chargement...</div>'
  try {
    const d       = await apiFetch('/performances' + qs)
    const members = d.members || []
    if (!members.length) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#3f3f46;">Aucun membre journalisé</div>'; return }
    body.innerHTML = members.map(m => `
      <div class="member-row px-4" style="cursor:pointer;" onclick="openMemberDrawer(${m.user_id})">
        <div class="flex items-center gap-2 flex-1"><div class="av av-default">${initials(m.name)}</div><div><p class="text-xs font-medium text-zinc-200">${m.name || '—'}</p><p class="text-[10px]" style="color:#52525b;">#${m.user_id}</p></div></div>
        <div style="width:80px;"><p class="text-xs tabular-nums text-zinc-200">${m.capital_actuel ? m.capital_actuel.toFixed(0) + ' $' : '—'}</p>${m.capital_evolution_pct != null ? `<p class="text-[9px] ${m.capital_evolution_pct >= 0 ? 'pnl-pos' : 'pnl-neg'}">${m.capital_evolution_pct > 0 ? '↑' : '↓'} ${Math.abs(m.capital_evolution_pct)}%</p>` : ''}</div>
        <span class="text-xs tabular-nums text-zinc-300" style="width:60px;">${m.total_trades ?? 0}</span>
        <div style="width:70px;"><p class="text-xs tabular-nums" style="color:#38bdf8;">${m.engagement_rate ?? '—'}%</p><div class="pbar mt-1"><div class="pbar-fill" style="width:${m.engagement_rate ?? 0}%;background:#38bdf8;"></div></div></div>
        <div style="width:70px;"><p class="text-xs tabular-nums pnl-pos">${m.win_rate ?? '—'}%</p><div class="pbar mt-1"><div class="pbar-fill" style="width:${m.win_rate ?? 0}%;background:#34d399;"></div></div></div>
        <span class="text-xs tabular-nums ${(m.perf_totale || 0) >= 0 ? 'pnl-pos' : 'pnl-neg'}" style="width:80px;">${m.perf_totale != null ? (m.perf_totale > 0 ? '+' : '') + m.perf_totale + '%' : '—'}</span>
        <div style="width:110px;">${m.disciplined_count > 0 ? '<span class="beh-tag disciplined" style="font-size:9px;">Discipliné ✓</span>' : ''}</div>
        <div style="width:60px;"><span class="badge ${m.suivi_status === 'active' ? 'badge-green' : 'badge-zinc'}" style="font-size:9px;">${m.suivi_status === 'active' ? 'Actif' : 'Suivi off'}</span></div>
        <button class="btn-icon" style="width:24px;height:24px;" onclick="event.stopPropagation();openMemberDrawer(${m.user_id})"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
      </div>`).join('')
    const total = d.total || 0
    document.getElementById('perf-pagination-label').textContent = `${offset + 1}–${Math.min(offset + PERF_LIMIT, total)} sur ${total}`
    document.getElementById('perf-page-label').textContent       = `${_perfPage + 1} / ${Math.max(1, Math.ceil(total / PERF_LIMIT))}`
    document.getElementById('perf-prev').disabled = _perfPage === 0
    document.getElementById('perf-next').disabled = offset + PERF_LIMIT >= total
  } catch (e) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#f87171;">Erreur chargement</div>' }
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
    const d = await apiFetch(`/performances/${userId}`)
    document.getElementById('member-av').textContent           = initials(d.name)
    document.getElementById('member-drawer-name').textContent  = d.name || `Membre #${userId}`
    document.getElementById('member-drawer-sub').textContent   = `#${userId} · ${d.stats?.total_trades ?? 0} trades`

    const s   = d.stats || {}
    const cap = d

    const capBars = (d.capital_21j || []).map(c =>
      `<div class="cap-bar ${c.type}" style="flex:1;height:${Math.min(100, Math.max(10, (c.capital / (d.capital_actuel || 1000)) * 100))}%;"></div>`
    ).join('')

    const curve = d.performance_curve || []
    let perfSVG = ''
    if (curve.length > 1) {
      const allC  = curve.map(p => p.cumulative_pct)
      const minC  = Math.min(0, ...allC), maxC = Math.max(1, ...allC), rangeC = maxC - minC || 1
      const pts   = curve.map((p, i) => `${i / (curve.length - 1) * 400},${80 - ((p.cumulative_pct - minC) / rangeC * 70)}`).join(' ')
      perfSVG = `<svg width="100%" height="80" viewBox="0 0 400 80" preserveAspectRatio="none">
        <polyline points="${pts}" style="fill:none;stroke:#34d399;stroke-width:1.5;"/>
        ${curve.map((p, i) => `<circle cx="${i / (curve.length - 1) * 400}" cy="${80 - ((p.cumulative_pct - minC) / rangeC * 70)}" r="3" fill="${p.result_pct > 0 ? '#34d399' : p.result_pct < 0 ? '#f87171' : '#fbbf24'}"/>`).join('')}
      </svg>`
    }

    document.getElementById('member-drawer-content').innerHTML = `
      <div class="grid grid-cols-4 gap-2">
        <div class="stat-mini text-center"><p class="text-base font-light text-white tabular-nums">${s.total_trades ?? 0}</p><p class="text-[9px] mt-1" style="color:#52525b;">Trades</p></div>
        <div class="stat-mini text-center"><p class="text-base font-light tabular-nums pnl-pos">${s.win_rate ?? '—'}%</p><p class="text-[9px] mt-1" style="color:#52525b;">Win</p></div>
        <div class="stat-mini text-center"><p class="text-base font-light tabular-nums" style="color:#38bdf8;">${s.engagement_rate ?? '—'}%</p><p class="text-[9px] mt-1" style="color:#52525b;">Engag.</p></div>
        <div class="stat-mini text-center"><p class="text-base font-light tabular-nums pnl-pos">${s.perf_totale != null ? (s.perf_totale > 0 ? '+' : '') + s.perf_totale + '%' : '—'}</p><p class="text-[9px] mt-1" style="color:#52525b;">Total</p></div>
      </div>
      <div>
        <p class="slabel">Évolution du capital</p>
        <div class="flex items-center justify-between mb-2">
          <div><p class="text-[10px]" style="color:#52525b;">Initial</p><p class="text-sm tabular-nums text-zinc-300" style="font-family:'Geist Mono',monospace;">${cap.capital_initial ? cap.capital_initial.toFixed(0) + ' $' : '—'}</p></div>
          <div style="flex:1;height:1px;background:rgba(255,255,255,.06);margin:0 12px;"></div>
          <div style="text-align:right;"><p class="text-[10px]" style="color:#52525b;">Actuel</p><p class="text-sm tabular-nums pnl-pos" style="font-family:'Geist Mono',monospace;">${cap.capital_actuel ? cap.capital_actuel.toFixed(0) + ' $' : '—'}</p></div>
          ${cap.evolution_pct != null ? `<div class="badge badge-green ml-3">↑ ${cap.evolution_pct}%</div>` : ''}
        </div>
        ${capBars ? `
        <p class="text-[10px] mb-1" style="color:#52525b;">Capital 21 jours</p>
        <div class="flex items-end gap-0.5" style="height:48px;">${capBars}</div>
        <div class="flex justify-between mt-1"><span class="text-[9px]" style="color:#3f3f46;">J -21</span><span class="text-[9px]" style="color:#3f3f46;">Aujourd'hui</span></div>` : ''}
        ${cap.capital_theorique ? `
        <div class="mt-3 p-2.5" style="background:rgba(255,255,255,.025);border-radius:7px;">
          <div class="flex justify-between mb-1"><span class="text-[10px]" style="color:#52525b;">Théorique (TP systématique)</span><span class="text-[10px] pnl-pos">${cap.capital_theorique.toFixed(0)} $</span></div>
          <div class="flex justify-between mb-1"><span class="text-[10px]" style="color:#52525b;">Réel actuel</span><span class="text-[10px] pnl-pos">${(cap.capital_actuel || 0).toFixed(0)} $</span></div>
          ${cap.manque_a_gagner ? `<div class="flex justify-between"><span class="text-[10px]" style="color:#fbbf24;">Manque à gagner</span><span class="text-[10px]" style="color:#fbbf24;">-${cap.manque_a_gagner.toFixed(0)} $</span></div>` : ''}
        </div>` : ''}
      </div>
      ${perfSVG ? `
      <div>
        <p class="slabel">Courbe de performance</p>
        <div style="background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.05);border-radius:8px;overflow:hidden;">${perfSVG}</div>
      </div>` : ''}
      <div>
        <p class="slabel">Profil comportemental</p>
        <div class="exit-bar">
          ${(d.behaviors || []).map(b => `
          <div class="exit-row">
            <span style="min-width:110px;font-size:11px;color:${b.behavior === 'disciplined' ? '#34d399' : b.behavior === 'early_exit' ? '#fbbf24' : '#f87171'}">${behaviorLabel(b.behavior)}</span>
            <div class="exit-track"><div class="exit-fill" style="width:${Math.min(100, (b.count / ((s.total_trades) || 1)) * 100)}%;background:${b.behavior === 'disciplined' ? '#34d399' : b.behavior === 'early_exit' ? '#fbbf24' : '#f87171'};"></div></div>
            <span class="text-xs" style="color:#71717a;">${b.count}</span>
          </div>`).join('')}
        </div>
        ${d.lot_respect_rate != null ? `<div class="mt-2"><span class="text-[10px]" style="color:#52525b;">Respect des lots : </span><span class="text-[10px] pnl-pos">${d.lot_respect_rate}%</span></div>` : ''}
      </div>
      <div style="background:rgba(255,255,255,.025);border:1px solid rgba(255,255,255,.06);border-radius:8px;padding:12px 14px;">
        <div class="flex items-center justify-between">
          <div><p class="text-xs font-medium text-zinc-300">Suivi capital actif</p><p class="text-[10px] mt-0.5" style="color:#52525b;">Collecte automatique tous les 3 jours</p></div>
          <span class="badge ${d.suivi_capital_actif ? 'badge-green' : 'badge-zinc'}">${d.suivi_capital_actif ? 'Actif' : 'Inactif'}</span>
        </div>
      </div>
    `
  } catch (e) { document.getElementById('member-drawer-content').innerHTML = '<div class="text-center text-xs pt-16" style="color:#f87171;">Erreur chargement</div>' }
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
    const all = d.leaderboard || []
    if (!all.length) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#3f3f46;">Aucun résultat (min. 3 trades requis)</div>'; return }

    const top3         = all.slice(0, 3)
    const podiumOrder  = [top3[1], top3[0], top3[2]].filter(Boolean)
    const podiumEmojis = ['🥈', '🥇', '🥉']
    const podiumSizes  = [32, 40, 30]
    document.getElementById('podium-row').innerHTML = podiumOrder.map((m, i) => `
      <div class="card p-5 text-center" style="${i === 1 ? 'border-color:rgba(251,191,36,.25);background:rgba(251,191,36,.03);' : 'margin-top:' + (i === 0 ? 16 : 28) + 'px;'}">
        <div style="font-size:${podiumSizes[i]}px;margin-bottom:8px;">${podiumEmojis[i]}</div>
        <div class="av av-default mx-auto mb-2" style="width:${i === 1 ? 44 : 38}px;height:${i === 1 ? 44 : 38}px;font-size:${i === 1 ? 14 : 12}px;">${initials(m.name)}</div>
        <p class="text-sm font-medium text-white">${m.name || '—'}</p>
        <p class="text-${i === 1 ? '2xl' : 'xl'} font-light mt-1 pnl-pos">${m.perf_totale > 0 ? '+' : ''}${m.perf_totale ?? 0}%</p>
        <p class="text-[10px] mt-1" style="color:#52525b;">${m.total_trades} trades · ${m.win_rate ?? '—'}% win</p>
        ${m.suivi_off ? '<span class="badge badge-zinc" style="font-size:9px;margin-top:4px;">suivi off</span>' : ''}
      </div>`).join('')

    body.innerHTML = all.slice(3).map(m => `
      <div class="member-row px-4" style="cursor:pointer;" onclick="openMemberDrawer(${m.user_id})">
        <span style="font-size:13px;font-weight:600;color:#71717a;min-width:30px;text-align:center;">${m.rank}</span>
        <div class="flex items-center gap-2 flex-1">
          <div class="av av-default" style="font-size:10px;">${initials(m.name)}</div>
          <div><p class="text-xs text-zinc-200">${m.name || '—'}</p>${m.suivi_off ? '<span class="badge badge-zinc" style="font-size:9px;margin-left:2px;">suivi off</span>' : ''}</div>
        </div>
        <span class="text-xs tabular-nums" style="color:#e4e4e7;width:70px;">${m.capital_actuel ? m.capital_actuel.toFixed(0) + ' $' : '—'}</span>
        <span class="text-xs tabular-nums text-zinc-400" style="width:60px;">${m.total_trades}</span>
        <span class="text-xs tabular-nums" style="color:#38bdf8;width:70px;">${m.engagement_rate ?? '—'}%</span>
        <span class="text-xs tabular-nums" style="color:${(m.win_rate || 0) >= 60 ? '#34d399' : (m.win_rate || 0) >= 40 ? '#fbbf24' : '#f87171'};width:70px;">${m.win_rate ?? '—'}%</span>
        <span class="text-xs tabular-nums ${(m.perf_totale || 0) >= 0 ? 'pnl-pos' : 'pnl-neg'}" style="width:90px;">${m.perf_totale != null ? (m.perf_totale > 0 ? '+' : '') + m.perf_totale + '%' : '—'}</span>
      </div>`).join('')
    if (all.length <= 3) body.innerHTML = ''
    if (all.length > 0) body.innerHTML += `<p class="text-xs text-center py-3" style="color:#3f3f46;">${d.total} membres · min. 3 trades journalisés</p>`
  } catch (e) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#f87171;">Erreur chargement</div>' }
}

// ══════════════════════════════════════════════════════════════
// BILAN IA
// ══════════════════════════════════════════════════════════════
let _bilanWeekStart = '', _bilanWeekEnd = ''

function buildWeekOptions() {
  const sel = document.getElementById('ia-week')
  if (sel.options.length > 0) return
  const opts = []
  for (let i = 0; i < 8; i++) {
    const mon = new Date(); mon.setDate(mon.getDate() - mon.getDay() + 1 - i * 7)
    const sun = new Date(mon); sun.setDate(sun.getDate() + 6)
    const label = `Semaine du ${fmtShort(mon)} au ${fmtShort(sun)} ${sun.getFullYear()}`
    opts.push({ label, start: mon.toISOString().split('T')[0] + 'T00:00:00', end: sun.toISOString().split('T')[0] + 'T23:59:59' })
  }
  sel.innerHTML = opts.map((o, i) => `<option value="${i}" data-start="${o.start}" data-end="${o.end}">${o.label}</option>`).join('')
  updateWeekDates()
}

function updateWeekDates() {
  const sel = document.getElementById('ia-week')
  const opt = sel.options[sel.selectedIndex]
  _bilanWeekStart = opt?.dataset.start || ''
  _bilanWeekEnd   = opt?.dataset.end   || ''
}

async function generateBilanPreview() {
  updateWeekDates()
  const btn = document.getElementById('btn-generate-bilan')
  btn.disabled = true; btn.textContent = 'Génération...'
  document.getElementById('ia-status').textContent   = 'Génération...'
  document.getElementById('ia-preview-box').textContent = "L'IA génère le bilan..."

  const adminConfig = {
    include_perf:            document.getElementById('ia-cfg-perf').checked,
    include_behavior:        document.getElementById('ia-cfg-beh').checked,
    include_recommendations: document.getElementById('ia-cfg-reco').checked,
    include_comparison:      document.getElementById('ia-cfg-comp').checked,
  }

  try {
    const perfData    = await apiFetch('/performances?limit=1&offset=0')
    const firstMember = (perfData.members || [])[0]
    if (!firstMember) { toast('Aucun membre journalisé cette semaine', 'info'); return }

    const r = await apiFetch('/ia/bilans/preview', {
      method: 'POST',
      body:   JSON.stringify({
        user_id:      firstMember.user_id,
        week_start:   _bilanWeekStart,
        week_end:     _bilanWeekEnd,
        week_label:   document.getElementById('ia-week').options[document.getElementById('ia-week').selectedIndex]?.text || 'Cette semaine',
        admin_config: adminConfig,
      }),
    })
    document.getElementById('ia-status').textContent        = 'Généré ✓'
    document.getElementById('ia-preview-box').style.color    = '#a1a1aa'
    document.getElementById('ia-preview-box').style.fontStyle = 'normal'
    document.getElementById('ia-preview-box').innerHTML = r.message
      .replace(/\*(.*?)\*/g, '<b style="color:#e4e4e7;">$1</b>')
      .replace(/_(.*?)_/g, '<i>$1</i>')
      .replace(/\n/g, '<br>')
  } catch (e) {
    toast('Erreur génération: ' + e.message, 'error')
    document.getElementById('ia-status').textContent = 'Erreur'
  } finally { btn.disabled = false; btn.textContent = 'Générer un aperçu' }
}

async function sendBilanToAll() {
  updateWeekDates()
  const weekLabel   = document.getElementById('ia-week').options[document.getElementById('ia-week').selectedIndex]?.text || 'Cette semaine'
  const adminConfig = {
    include_perf:            document.getElementById('ia-cfg-perf').checked,
    include_behavior:        document.getElementById('ia-cfg-beh').checked,
    include_recommendations: document.getElementById('ia-cfg-reco').checked,
    include_comparison:      document.getElementById('ia-cfg-comp').checked,
  }
  if (!confirm(`Envoyer les bilans à tous les membres journalisés pour "${weekLabel}" ?`)) return
  try {
    const r = await apiFetch('/ia/bilans/generate', {
      method: 'POST',
      body:   JSON.stringify({
        week_start:   _bilanWeekStart,
        week_end:     _bilanWeekEnd,
        week_label:   weekLabel,
        target:       document.getElementById('ia-target').value,
        send:         true,
        admin_config: adminConfig,
      }),
    })
    toast(`✓ ${r.sent} bilans envoyés · ${r.errors} erreurs`, r.errors > 0 ? 'info' : 'success')
    loadBilanHistory()
  } catch (e) { toast('Erreur envoi: ' + e.message, 'error') }
}

async function sendBilanToMember() {
  if (!_currentMemberId) return
  updateWeekDates()
  const weekLabel = document.getElementById('ia-week')?.options[document.getElementById('ia-week')?.selectedIndex]?.text || 'Cette semaine'
  try {
    const r = await apiFetch('/ia/bilans/preview', {
      method: 'POST',
      body:   JSON.stringify({
        user_id:      _currentMemberId,
        week_start:   _bilanWeekStart || new Date(Date.now() - 7 * 86400000).toISOString(),
        week_end:     _bilanWeekEnd   || new Date().toISOString(),
        week_label:   weekLabel,
        admin_config: { include_perf: true, include_behavior: true, include_recommendations: true, include_comparison: false },
      }),
    })
    toast(`Bilan envoyé à ${r.name} ✓`, 'success')
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

async function loadBilanHistory() {
  const el = document.getElementById('bilan-history-list')
  try {
    const data = await apiFetch('/ia/bilans/history')
    if (!data.length) { el.innerHTML = '<div class="text-xs" style="color:#3f3f46;">Aucun bilan envoyé</div>'; return }
    el.innerHTML = data.slice(0, 5).map(b => `
      <div style="padding:10px 12px;background:rgba(255,255,255,.025);border-radius:8px;border:1px solid rgba(255,255,255,.05);">
        <div class="flex justify-between mb-1"><p class="text-xs font-medium text-zinc-200">${b.week_label}</p><span class="badge badge-green" style="font-size:10px;">Envoyé</span></div>
        <p class="text-[11px]" style="color:#52525b;">${b.target} · ${b.total_sent} envoyés · ${fmtDate(b.generated_at)}</p>
      </div>`).join('')
  } catch (e) { el.innerHTML = '<div class="text-xs" style="color:#3f3f46;">—</div>' }
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

    const catColors  = { forex: 'rgba(52,211,153,.1)',   crypto: 'rgba(167,139,250,.1)', indices: 'rgba(56,189,248,.1)',   commodities: 'rgba(251,191,36,.1)' }
    const catColors2 = { forex: 'rgba(52,211,153,.2)',   crypto: 'rgba(167,139,250,.2)', indices: 'rgba(56,189,248,.2)',   commodities: 'rgba(251,191,36,.2)' }
    const catText    = { forex: '#34d399',               crypto: '#a78bfa',              indices: '#38bdf8',               commodities: '#fbbf24' }
    const catLabel   = { forex: 'FX',                   crypto: 'CR',                   indices: 'IN',                   commodities: 'XM' }

    body.innerHTML = pairs.map(p => `
      <div class="pair-row">
        <div class="flex items-center gap-2 flex-1">
          <div style="width:32px;height:22px;border-radius:5px;background:${catColors[p.category] || 'rgba(255,255,255,.05)'};border:1px solid ${catColors2[p.category] || 'rgba(255,255,255,.1)'};display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:600;color:${catText[p.category] || '#71717a'};font-family:'Geist Mono',monospace;">${catLabel[p.category] || '??'}</div>
          <p class="text-xs font-medium" style="font-family:'Geist Mono',monospace;">${p.symbol}</p>
        </div>
        <span class="text-xs" style="color:#71717a;width:90px;">${p.category}</span>
        <span class="text-xs tabular-nums" style="color:#38bdf8;width:110px;font-family:'Geist Mono',monospace;">${p.pip_value.toFixed(2)} $</span>
        <span class="text-xs tabular-nums" style="color:#71717a;width:80px;">${p.decimals}</span>
        <span class="text-xs" style="color:#52525b;width:120px;font-family:'Geist Mono',monospace;">${p.binance_symbol || '—'}</span>
        <div style="width:80px;"><span class="badge ${p.is_active ? 'badge-green' : 'badge-zinc'}" style="font-size:9px;">${p.is_active ? 'Actif' : 'Inactif'}</span></div>
        <div class="flex gap-1" style="width:60px;">
          <button class="btn-icon" style="width:22px;height:22px;" onclick="togglePairActive(${p.id},${p.is_active})">
            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">${p.is_active ? '<path d="M18 6 6 18M6 6l12 12"/>' : '<path d="M12 22c5.5 0 10-4.5 10-10S17.5 2 12 2 2 6.5 2 12s4.5 10 10 10z"/><path d="m9 12 2 2 4-4"/>'}</svg>
          </button>
          <button class="btn-icon" style="width:22px;height:22px;" onclick="deletePair(${p.id})">
            <svg width="10" height="10" fill="none" stroke="#f87171" viewBox="0 0 24 24" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="m19 6-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></svg>
          </button>
        </div>
      </div>`).join('')

    const sel = document.getElementById('calc-pair-sel')
    sel.innerHTML = pairs.filter(p => p.is_active).map(p => `<option value="${p.pip_value}" data-sym="${p.symbol}">${p.symbol} (${p.pip_value}$/pip)</option>`).join('')
    calcLot()
  } catch (e) { body.innerHTML = '<div class="p-6 text-center text-xs" style="color:#f87171;">Erreur</div>' }
}

function calcLot() {
  const cap   = parseFloat(document.getElementById('calc-capital')?.value)  || 0
  const risk  = parseFloat(document.getElementById('calc-risk')?.value)     || 0
  const slP   = parseFloat(document.getElementById('calc-sl-pips')?.value)  || 1
  const tpP   = parseFloat(document.getElementById('calc-tp-pips')?.value)  || 0
  const pipV  = parseFloat(document.getElementById('calc-pair-sel')?.value) || 10
  const riskUsd = cap * risk / 100
  const lot     = riskUsd / (slP * pipV)
  const gain    = lot * tpP * pipV
  document.getElementById('res-risk-usd').textContent = riskUsd.toFixed(2) + ' $'
  document.getElementById('res-lot').textContent      = lot.toFixed(4)
  document.getElementById('res-loss').textContent     = '-' + riskUsd.toFixed(2) + ' $'
  document.getElementById('res-gain').textContent     = '+' + gain.toFixed(2) + ' $'
}

async function savePair() {
  const symbol = document.getElementById('pair-symbol').value.trim().toUpperCase()
  const pip    = parseFloat(document.getElementById('pair-pip').value)
  if (!symbol || !pip) { toast('Symbole et valeur pip requis', 'error'); return }
  try {
    await apiFetch('/pairs', {
      method: 'POST',
      body:   JSON.stringify({
        symbol,
        pip_value:      pip,
        category:       document.getElementById('pair-category').value,
        decimals:       parseInt(document.getElementById('pair-dec').value) || 5,
        binance_symbol: document.getElementById('pair-binance').value || null,
        is_active:      document.getElementById('pair-active-toggle').classList.contains('on') ? 1 : 0,
      }),
    })
    toast(`Paire ${symbol} ajoutée ✓`, 'success')
    closeModal('modal-add-pair')
    loadPairs()
  } catch (e) { toast('Erreur: ' + e.message, 'error') }
}

async function togglePairActive(id, current) {
  try {
    await apiFetch(`/pairs/${id}`, { method: 'PATCH', body: JSON.stringify({ is_active: current ? 0 : 1 }) })
    loadPairs()
  } catch (e) { toast('Erreur', 'error') }
}

async function deletePair(id) {
  if (!confirm('Désactiver cette paire ?')) return
  try {
    await apiFetch(`/pairs/${id}`, { method: 'DELETE' })
    toast('Paire désactivée', 'success')
    loadPairs()
  } catch (e) { toast('Erreur', 'error') }
}

// BUG FIX : sélecteur corrigé — lit uniquement #dest-block-category select
async function loadCategories() {
  try {
    const res  = await fetch(`${API_URL}/categorie`)
    const data = await res.json()
    const select = document.querySelector('#dest-block-category select')
    if (!select) return
    select.innerHTML = '<option value="">Toutes catégories</option>'
    data.forEach(cat => {
      const opt       = document.createElement('option')
      opt.value       = cat.name_categorie
      opt.textContent = `${cat.name_categorie} (${cat.member_count ?? 0})`
      select.appendChild(opt)
    })
  } catch (err) {
    console.error('Erreur chargement catégories :', err)
  }
}

// ══════════════════════════════════════════════════════════════
// FORMULAIRES & COLLECTE
// ══════════════════════════════════════════════════════════════
async function loadFormStats() {
  try {
    const d = await apiFetch('/forms/stats')
    document.getElementById('forms-stats-grid').innerHTML = `
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Formulaires actifs</p><p class="text-xl font-light text-white">${d.total_forms ?? 0}</p><p class="text-[10px] mt-1" style="color:#52525b;">${d.system_forms ?? 0} système · ${d.custom_forms ?? 0} personnalisés</p></div>
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Réponses collectées</p><p class="text-xl font-light text-white tabular-nums">${d.total_responses ?? 0}</p><p class="text-[10px] mt-1" style="color:#52525b;">${d.unique_respondents ?? 0} membres uniques</p></div>
      <div class="stat-mini"><p class="text-[10px] mb-2" style="color:#52525b;">Taux de complétion</p><p class="text-xl font-light tabular-nums" style="color:#34d399;">${d.completion_rate ?? 0}%</p><div class="pbar mt-2"><div class="pbar-fill" style="width:${d.completion_rate ?? 0}%;background:#34d399;"></div></div></div>
    `
  } catch (e) {}
}

let _selectedFormId = null
async function loadForms() {
  try {
    const forms       = await apiFetch('/forms')
    const systemForms = forms.filter(f => f.type === 'system' || f.form_type === 'system')
    const customForms = forms.filter(f => f.type !== 'system' && f.form_type !== 'system')

    function renderFormCard(f) {
      return `<div class="form-card ${_selectedFormId === f.id ? 'selected' : ''}" onclick="selectFormCard(${f.id})">
        <div class="flex items-center justify-between mb-2">
          <div class="flex items-center gap-2"><span class="badge ${f.type === 'system' || f.form_type === 'system' ? 'badge-sky' : 'badge-violet'}" style="font-size:9px;">${f.type === 'system' || f.form_type === 'system' ? 'Système' : 'Personnalisé'}</span><p class="text-xs font-medium text-zinc-200">${f.name}</p></div>
          <span class="badge ${f.is_active ? 'badge-green' : 'badge-zinc'}" style="font-size:9px;">${f.is_active ? 'Actif' : 'Inactif'}</span>
        </div>
        <p class="text-[10px] mt-2" style="color:#52525b;">${f.total_responses ?? 0} réponses · ${f.respondents ?? 0} membres</p>
        ${f.last_response_at ? `<p class="text-[10px]" style="color:#3f3f46;">Dernière réponse : ${fmtDate(f.last_response_at)}</p>` : ''}
      </div>`
    }

    const html = []
    if (systemForms.length) { html.push('<p class="text-xs font-medium text-zinc-400 mb-1">Formulaires système</p>'); html.push(...systemForms.map(renderFormCard)) }
    if (customForms.length) { html.push('<p class="text-xs font-medium text-zinc-400 mb-1 mt-3">Personnalisés</p>'); html.push(...customForms.map(renderFormCard)) }
    document.getElementById('forms-list').innerHTML = html.join('')
  } catch (e) { document.getElementById('forms-list').innerHTML = '<div class="text-xs" style="color:#3f3f46;">Erreur chargement</div>' }
}

async function selectFormCard(formId) {
  _selectedFormId = formId
  loadForms()
  try {
    const d      = await apiFetch(`/forms/${formId}/mapping`)
    const fields = d.fields || []
    const STATS_OPTIONS = ['capital_evolution', 'win_rate_reel', 'behavior_tag', 'engagement_rate', 'lot_respect', 'custom']
    document.getElementById('mapping-panel').innerHTML = `
      <div class="flex items-center justify-between mb-3">
        <p class="text-xs font-medium text-zinc-300">Mapping — ${d.form_name}</p>
        <button class="btn-ghost" style="font-size:10px;padding:4px 8px;" onclick="saveMapping(${formId})">Sauvegarder</button>
      </div>
      ${fields.map(f => `
        <div class="map-row" data-field-id="${f.field_id}">
          <span class="text-[10px] font-mono" style="color:#38bdf8;min-width:120px;">${f.field_label || f.field_id}</span>
          <span style="color:#3f3f46;font-size:11px;">→</span>
          <select class="input map-stat-sel" style="font-size:10px;padding:4px 7px;flex:1;" data-fid="${f.field_id}">
            ${STATS_OPTIONS.map(s => `<option value="${s}" ${f.maps_to_stat === s ? 'selected' : ''}>${s}</option>`).join('')}
          </select>
          <select class="input" style="font-size:10px;padding:4px 7px;width:80px;">
            ${['text', 'number', 'boolean', 'image'].map(t => `<option ${f.data_type === t ? 'selected' : ''}>${t}</option>`).join('')}
          </select>
          <select class="input" style="font-size:10px;padding:4px 7px;width:70px;">
            ${['last', 'average', 'sum', 'count'].map(a => `<option ${f.aggregation === a ? 'selected' : ''}>${a}</option>`).join('')}
          </select>
        </div>
        ${(f.sample_values || []).length ? `<div style="font-size:9px;color:#3f3f46;margin-bottom:6px;padding-left:8px;">Ex: ${f.sample_values.map(v => v.value).join(', ')}</div>` : ''}`
      ).join('')}
    `
  } catch (e) { document.getElementById('mapping-panel').innerHTML = '<p class="text-xs" style="color:#3f3f46;">Erreur chargement mapping</p>' }
}

async function saveMapping(formId) {
  const rows   = document.querySelectorAll('#mapping-panel .map-row')
  const fields = []
  rows.forEach(row => {
    const fid     = row.dataset.fieldId
    const selects = row.querySelectorAll('select')
    if (fid && selects.length >= 3) {
      fields.push({ field_id: fid, maps_to_stat: selects[0].value, data_type: selects[1].value, aggregation: selects[2].value })
    }
  })
  try {
    await apiFetch(`/forms/${formId}/mapping`, { method: 'PATCH', body: JSON.stringify({ fields }) })
    toast('Mapping sauvegardé ✓', 'success')
  } catch (e) { toast('Erreur sauvegarde', 'error') }
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
          <th style="text-align:left;padding:6px 8px;color:#3f3f46;font-weight:500;font-size:10px;">Stats produites</th>
        </tr></thead>
        <tbody>
          ${data.map(f => `
          <tr style="border-bottom:1px solid rgba(255,255,255,.04);">
            <td style="padding:7px 8px;color:#e4e4e7;">${f.form_name}</td>
            <td style="padding:7px 8px;color:#38bdf8;font-family:'Geist Mono',monospace;font-size:10px;">${(f.fields_collected || []).slice(0, 3).join(', ')}</td>
            <td style="padding:7px 8px;color:#71717a;">${(f.stats_produced || []).join(', ') || '—'}</td>
          </tr>`).join('')}
        </tbody>
      </table>`
  } catch (e) { document.getElementById('forms-summary-table').innerHTML = '<div class="text-xs" style="color:#3f3f46;">Erreur</div>' }
}

// ══════════════════════════════════════════════════════════════
// DRAWERS & MODALS
// ══════════════════════════════════════════════════════════════
function openModal(id)  { document.getElementById(id)?.classList.add('open') }
function closeModal(id) { document.getElementById(id)?.classList.remove('open') }
function closeAllDrawers() {
  ;['signal-drawer', 'member-drawer'].forEach(id => document.getElementById(id)?.classList.remove('open'))
  document.getElementById('drawer-overlay')?.classList.remove('open')
}
document.querySelectorAll('.modal-overlay').forEach(o => o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open') }))
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
  return name.trim().split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase()
}

function fmtDate(iso) {
  if (!iso) return '—'
  try {
    const d    = new Date(iso)
    const now  = new Date()
    const diff = (now - d) / 1000
    if (diff < 60)    return "À l'instant"
    if (diff < 3600)  return Math.floor(diff / 60) + ' min'
    if (diff < 86400) return d.toLocaleTimeString('fr', { hour: '2-digit', minute: '2-digit' })
    return d.toLocaleDateString('fr', { day: '2-digit', month: '2-digit' })
  } catch { return '—' }
}

function fmtShort(date) {
  return date.toLocaleDateString('fr', { day: 'numeric', month: 'long' })
}

function resultBadge(result) {
  const map = { tp: 'badge-green', sl: 'badge-red', partial: 'badge-amber', cancelled: 'badge-zinc', open: 'badge-sky' }
  const lbl = { tp: 'TP ✓', sl: 'SL ✗', partial: 'Partiel', cancelled: 'Annulé', open: 'Ouvert' }
  return `<span class="badge ${map[result] || 'badge-zinc'}" style="font-size:9px;">${lbl[result] || result}</span>`
}

function behaviorLabel(b) {
  return { disciplined: 'Discipliné ✓', early_exit: 'Sortie tôt ⚡', sl_skip: 'Ignore SL ⚠️', passive: 'Passif' }[b] || b
}

// ══════════════════════════════════════════════════════════════
// TICKER LIVE (Binance WebSocket)
// ══════════════════════════════════════════════════════════════
let _tickerWS = null, _tickerEl = null, _lastPrice = null

function startTickerWS(binanceSymbol, tickerElementId) {
  if (_tickerWS) { _tickerWS.close(); _tickerWS = null }
  _tickerEl = document.getElementById(tickerElementId)
  if (!_tickerEl || !binanceSymbol) return
  try {
    _tickerWS = new WebSocket(`wss://stream.binance.com:9443/ws/${binanceSymbol.toLowerCase()}@trade`)
    _tickerWS.onmessage = e => {
      const data  = JSON.parse(e.data)
      const price = parseFloat(data.p)
      if (_tickerEl) {
        _tickerEl.textContent = price.toFixed(binanceSymbol.includes('XAUUSDT') ? 2 : 4)
        _tickerEl.className   = 'ticker-live ' + (_lastPrice == null || price >= _lastPrice ? 'up' : 'down')
        _lastPrice = price
      }
    }
    _tickerWS.onerror = () => {}
  } catch (e) {}
}

// ══════════════════════════════════════════════════════════════
// INIT
// ══════════════════════════════════════════════════════════════
async function init() {
  resetUploadZone()
  await loadDashboardStats()
  await loadSignals()
  setCloseStatus('tp')
  calcLot()
  loadCategories()

  // Rafraîchissement auto toutes les 30s
  setInterval(() => {
    if (document.getElementById('view-journal').style.display !== 'none') {
      loadSignals()
      loadDashboardStats()
    }
  }, 30000)
}
init()