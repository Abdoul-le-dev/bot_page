/* ═══════════════════════════════════════════════════════════
   dashboard.js — TradingBot Dashboard
   ═══════════════════════════════════════════════════════════ */

const API_URL = 'http://52.90.21.131:8000/'

/* ─── API ─────────────────────────────────────────────────── */
async function apiFetch(path) {
  const res = await fetch(API_URL + path, {
    headers: { 'Content-Type': 'application/json' }
  })
  if (!res.ok) throw new Error(`HTTP ${res.status}`)
  return res.json()
}

/* ─── Toast ───────────────────────────────────────────────── */
function toast(msg, type = 'info') {
  const el = document.createElement('div')
  el.className = `toast-item ${type}`
  el.textContent = msg
  document.getElementById('toast-container').appendChild(el)
  setTimeout(() => el.remove(), 3500)
}

/* ─── Utils ───────────────────────────────────────────────── */
function initials(name) {
  if (!name) return '?'
  return name.trim().split(' ').map(w => w[0]).slice(0, 2).join('').toUpperCase()
}

function avColor(name) {
  const colors = ['green', 'sky', 'amber', 'violet', 'teal', 'red']
  let hash = 0
  for (const c of (name || '')) hash = c.charCodeAt(0) + ((hash << 5) - hash)
  return colors[Math.abs(hash) % colors.length]
}

function _fmt(n) {
  if (n == null) return '—'
  if (n >= 1000) return n.toLocaleString('fr-FR')
  return String(n)
}

/* ─── Sidebar mobile ──────────────────────────────────────── */
function openSidebar() {
  const sidebar = document.getElementById('sidebar')
  const overlay = document.getElementById('sidebar-overlay')
  // Ouvrir sidebar : retirer -translate-x-full, ajouter translate-x-0
  sidebar.classList.remove('-translate-x-full')
  sidebar.classList.add('translate-x-0')
  // Overlay
  overlay.classList.remove('hidden')
  requestAnimationFrame(() => overlay.classList.add('opacity-100'))
  document.body.style.overflow = 'hidden'
}

function closeSidebar() {
  const sidebar = document.getElementById('sidebar')
  const overlay = document.getElementById('sidebar-overlay')
  // Fermer sidebar
  sidebar.classList.add('-translate-x-full')
  sidebar.classList.remove('translate-x-0')
  // Overlay
  overlay.classList.remove('opacity-100')
  setTimeout(() => overlay.classList.add('hidden'), 200)
  document.body.style.overflow = ''
}

/* ═══════════════════════════════════════════════════════════
   RENDER — MÉTRIQUES
   ═══════════════════════════════════════════════════════════ */
function renderMetrics(d) {
  const m = d.membres || {}
  const a = d.abonnements || {}
  const t = d.trading || {}

  document.getElementById('metrics-grid').innerHTML = `
    <div class="card fadein">
      <p style="font-size:11px;color:#52525b;margin-bottom:10px;">Total membres</p>
      <p style="font-size:28px;font-weight:300;color:#fafafa;line-height:1;font-family:'Geist Mono',monospace;">${_fmt(m.total)}</p>
      <p style="font-size:11px;margin-top:8px;color:#34d399;">↑ +${_fmt(m.nouveaux_7j)} cette semaine</p>
    </div>

    <div class="card fadein" style="animation-delay:.05s">
      <p style="font-size:11px;color:#52525b;margin-bottom:10px;">Actifs (7 jours)</p>
      <p style="font-size:28px;font-weight:300;color:#fafafa;line-height:1;font-family:'Geist Mono',monospace;">${_fmt(m.actifs_7j)}</p>
      <p style="font-size:11px;margin-top:8px;color:#52525b;">${m.total > 0 ? Math.round((m.actifs_7j || 0) / m.total * 100) : 0}% du total</p>
      <div class="pbar mt-2">
        <div class="pbar-fill" style="width:${m.total > 0 ? Math.round((m.actifs_7j || 0) / m.total * 100) : 0}%;background:#38bdf8;"></div>
      </div>
    </div>

    <div class="card fadein" style="animation-delay:.1s">
      <p style="font-size:11px;color:#52525b;margin-bottom:10px;">Abonnements actifs</p>
      <p style="font-size:28px;font-weight:300;color:#fafafa;line-height:1;font-family:'Geist Mono',monospace;">${_fmt(a.actifs)}</p>
      <p style="font-size:11px;margin-top:8px;color:#f87171;">↓ ${_fmt(a.expiration_7j)} expirations proches</p>
    </div>

    <div class="card fadein" style="animation-delay:.15s">
      <p style="font-size:11px;color:#52525b;margin-bottom:10px;">Trades journalisés</p>
      <p style="font-size:28px;font-weight:300;color:#fafafa;line-height:1;font-family:'Geist Mono',monospace;">${_fmt(t.total_journaux)}</p>
      <p style="font-size:11px;margin-top:8px;color:#34d399;">↑ ${_fmt(t.trades_ouverts)} en cours</p>
    </div>
  `
}

/* ═══════════════════════════════════════════════════════════
   RENDER — ROW 2 : Segments · Alertes · Agent IA
   ═══════════════════════════════════════════════════════════ */
function renderRow2(d) {
  const m  = d.membres || {}
  const a  = d.abonnements || {}
  const ia = d.ia || {}

  const total = m.total || 1

  const segs = [
    { label: 'Actifs 7j',       val: m.actifs_7j || 0,   color: '#34d399' },
    { label: 'Abonnés actifs',  val: a.actifs || 0,      color: '#38bdf8' },
    { label: 'Inactifs 21j',    val: m.inactifs_21j || 0,color: '#fbbf24' },
    { label: 'Nouveaux 7j',     val: m.nouveaux_7j || 0, color: '#a78bfa' },
  ]

  const segsHtml = segs.map(s => `
    <div>
      <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
        <span style="font-size:12px;color:#a1a1aa;">${s.label}</span>
        <span style="font-size:11px;font-family:'Geist Mono',monospace;color:#52525b;">${_fmt(s.val)}</span>
      </div>
      <div class="pbar-md">
        <div class="pbar-fill" style="width:${Math.min(100, Math.round(s.val / total * 100))}%;background:${s.color};"></div>
      </div>
    </div>`).join('')

  const alerts = [
    { color: '#f87171', label: 'Expirations imminentes', sub: 'dans les 7 prochains jours',     val: a.expiration_7j || 0 },
    { color: '#fbbf24', label: 'Membres inactifs',       sub: "pas d'activité depuis 21j",       val: m.inactifs_21j || 0 },
    { color: '#2dd4bf', label: 'Escalades IA',           sub: 'conversations à reprendre',        val: ia.escalades_attente || 0 },
  ]

  const alertsHtml = alerts.map(al => `
    <div class="alert-row">
      <span style="width:7px;height:7px;border-radius:50%;background:${al.color};flex-shrink:0;display:block;"></span>
      <div style="flex:1;min-width:0;">
        <p style="font-size:12px;color:#e4e4e7;font-weight:500;">${al.label}</p>
        <p style="font-size:11px;margin-top:2px;color:#52525b;">${al.sub}</p>
      </div>
      <p style="font-size:20px;font-weight:300;color:${al.color};font-family:'Geist Mono',monospace;">${_fmt(al.val)}</p>
    </div>`).join('')

  document.getElementById('row2-grid').innerHTML = `
    <div class="card fadein">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <p style="font-size:13px;font-weight:500;color:#fafafa;">Segments</p>
        <span style="font-size:11px;color:#52525b;">${_fmt(total)} membres</span>
      </div>
      <div style="display:flex;flex-direction:column;gap:12px;">${segsHtml}</div>
    </div>

    <div class="card fadein" style="animation-delay:.05s">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <p style="font-size:13px;font-weight:500;color:#fafafa;">Alertes</p>
        <span class="badge badge-red">${alerts.filter(al => al.val > 0).length} actives</span>
      </div>
      <div style="display:flex;flex-direction:column;gap:8px;">${alertsHtml}</div>
    </div>

    <div class="card fadein" style="animation-delay:.1s">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <p style="font-size:13px;font-weight:500;color:#fafafa;">Agent IA</p>
        <span style="display:flex;align-items:center;gap:6px;font-size:11px;color:#34d399;">
          <span class="live-dot pulse"></span>Actif
        </span>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:16px;">
        <div class="stat-mini" style="text-align:center;">
          <p style="font-size:20px;font-weight:300;color:#fafafa;font-family:'Geist Mono',monospace;">${_fmt(ia.messages_traites)}</p>
          <p style="font-size:10px;margin-top:4px;color:#52525b;">Msgs traités</p>
        </div>
        <div class="stat-mini" style="text-align:center;">
          <p style="font-size:20px;font-weight:300;color:#fafafa;font-family:'Geist Mono',monospace;">${ia.taux_resolution || 0}%</p>
          <p style="font-size:10px;margin-top:4px;color:#52525b;">Résolution auto</p>
        </div>
      </div>
      ${(ia.escalades_attente || 0) > 0 ? `
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;background:rgba(251,191,36,.07);border:1px solid rgba(251,191,36,.18);border-radius:8px;">
          <span style="font-size:12px;color:#fbbf24;">${ia.escalades_attente} escalades en attente</span>
          <span style="color:#fbbf24;font-size:12px;">→</span>
        </div>` : `
        <div style="padding:10px 12px;background:rgba(52,211,153,.04);border:1px solid rgba(52,211,153,.12);border-radius:8px;text-align:center;">
          <span style="font-size:11px;color:#34d399;">✓ Aucune escalade</span>
        </div>`}
    </div>
  `
}

/* ═══════════════════════════════════════════════════════════
   RENDER — TRADING STATS
   ═══════════════════════════════════════════════════════════ */
function renderTrading(d) {
  const t     = d.trading || {}
  const best  = t.meilleur_trade
  const worst = t.pire_trade
  const wr    = t.win_rate_global

  const bestHtml = best ? `
    <div style="background:rgba(52,211,153,.06);border:1px solid rgba(52,211,153,.15);border-radius:8px;padding:10px 12px;">
      <p style="font-size:10px;color:#52525b;margin-bottom:4px;text-transform:uppercase;letter-spacing:.05em;">Meilleur trade</p>
      <div style="display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:12px;font-family:'Geist Mono',monospace;color:#e4e4e7;">${best.pair || '—'}</span>
        <span style="font-size:16px;font-weight:300;color:#34d399;">+${best.result_pct || best.result_percent || 0}%</span>
      </div>
    </div>` : ''

  const worstHtml = worst ? `
    <div style="background:rgba(248,113,113,.06);border:1px solid rgba(248,113,113,.15);border-radius:8px;padding:10px 12px;">
      <p style="font-size:10px;color:#52525b;margin-bottom:4px;text-transform:uppercase;letter-spacing:.05em;">Pire trade</p>
      <div style="display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:12px;font-family:'Geist Mono',monospace;color:#e4e4e7;">${worst.pair || '—'}</span>
        <span style="font-size:16px;font-weight:300;color:#f87171;">${worst.result_pct || worst.result_percent || 0}%</span>
      </div>
    </div>` : ''

  document.getElementById('row3-trading').innerHTML = `
    <div class="row4-grid fadein">

      <div class="card">
        <p style="font-size:11px;color:#52525b;margin-bottom:12px;">Win rate global</p>
        <div style="display:flex;align-items:flex-end;gap:12px;margin-bottom:12px;">
          <p style="font-size:36px;font-weight:300;line-height:1;font-family:'Geist Mono',monospace;" class="pnl-pos">${wr != null ? wr + '%' : '—'}</p>
          <div style="flex:1;padding-bottom:4px;">
            <p style="font-size:11px;color:#52525b;margin-bottom:4px;">Admin · tous trades</p>
            <div class="pbar-md"><div class="pbar-fill" style="width:${wr || 0}%;background:#34d399;"></div></div>
          </div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
          ${bestHtml}
          ${worstHtml}
        </div>
      </div>

      <div class="card">
        <p style="font-size:11px;color:#52525b;margin-bottom:12px;">Performance totale</p>
        <p style="font-size:36px;font-weight:300;line-height:1;font-family:'Geist Mono',monospace;"
           class="${(t.performance_totale_pct || 0) >= 0 ? 'pnl-pos' : 'pnl-neg'}">
          ${(t.performance_totale_pct || 0) > 0 ? '+' : ''}${t.performance_totale_pct || 0}%
        </p>
        <p style="font-size:11px;color:#52525b;margin-top:8px;">Cumulé depuis le début</p>
        <div style="margin-top:12px;padding-top:12px;border-top:1px solid rgba(255,255,255,.05);display:flex;gap:16px;">
          <div>
            <p style="font-size:18px;font-weight:300;color:#fafafa;font-family:'Geist Mono',monospace;">${_fmt(t.membres_actifs_trading)}</p>
            <p style="font-size:10px;color:#52525b;margin-top:2px;">Membres actifs</p>
          </div>
          <div>
            <p style="font-size:18px;font-weight:300;color:#fafafa;font-family:'Geist Mono',monospace;">${t.capital_moyen_membres ? Math.round(t.capital_moyen_membres) + '$' : '—'}</p>
            <p style="font-size:10px;color:#52525b;margin-top:2px;">Capital moyen</p>
          </div>
          <div>
            <p style="font-size:18px;font-weight:300;color:#fafafa;font-family:'Geist Mono',monospace;">${_fmt(t.total_journaux)}</p>
            <p style="font-size:10px;color:#52525b;margin-top:2px;">Journaux</p>
          </div>
        </div>
      </div>

      <div class="card">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
          <p style="font-size:11px;color:#52525b;">Trades actifs</p>
          ${(t.trades_ouverts || 0) > 0
            ? `<span style="display:flex;align-items:center;gap:6px;font-size:11px;color:#38bdf8;">
                 <span class="live-dot" style="background:#38bdf8;"></span> En cours
               </span>`
            : ''}
        </div>
        <p style="font-size:36px;font-weight:300;line-height:1;font-family:'Geist Mono',monospace;color:#38bdf8;">${_fmt(t.trades_ouverts)}</p>
        <p style="font-size:11px;color:#52525b;margin-top:8px;">trades ouverts en ce moment</p>
        <a href="/trade" style="display:inline-flex;align-items:center;gap:5px;margin-top:12px;font-size:11px;color:#38bdf8;text-decoration:none;">
          Voir le journal <span>→</span>
        </a>
      </div>

    </div>
  `
}

/* ═══════════════════════════════════════════════════════════
   RENDER — GOLD
   ═══════════════════════════════════════════════════════════ */
function renderGold(d) {
  const g    = d.gold || {}
  const sais = g.saison_active || {}
  const sess = g.session_active || {}
  const sims = g.simulations || []

  const phaseColors = {
    teaser: '#71717a', open: '#38bdf8', tp1_reached: '#34d399',
    tp2_reached: '#34d399', tp3_reached: '#a78bfa', sl_touched: '#f87171'
  }
  const phaseLabels = {
    teaser: 'Teaser', open: 'Ouvert ●', tp1_reached: 'TP1 ✓',
    tp2_reached: 'TP2 ✓', tp3_reached: 'TP3 ✓', sl_touched: 'SL ✗'
  }

  const phaseColor = phaseColors[sess.current_phase] || '#52525b'
  const phaseLabel = phaseLabels[sess.current_phase] || (sess.current_phase || '—')

  const sessionBlock = Object.keys(sess).length ? `
    <div class="card-gold">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px;">
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
          <span class="live-dot pulse" style="background:#fbbf24;"></span>
          <p style="font-size:13px;font-weight:500;color:#fafafa;">Session active — XAU/USD</p>
          <span style="padding:2px 8px;border-radius:99px;font-size:10px;font-weight:500;background:${phaseColor}18;color:${phaseColor};border:1px solid ${phaseColor}30;">${phaseLabel}</span>
        </div>
        <a href="/trade" style="font-size:11px;color:#52525b;text-decoration:none;display:flex;align-items:center;gap:4px;"
           onmouseover="this.style.color='#fbbf24'" onmouseout="this.style.color='#52525b'">Gérer →</a>
      </div>
      <div class="gold-stats-grid">
        <div class="stat-gold" style="text-align:center;">
          <p style="font-size:18px;font-weight:300;color:#fafafa;font-family:'Geist Mono',monospace;">${sess.membres_confirmes || 0}</p>
          <p style="font-size:9px;color:#52525b;margin-top:3px;">Confirmés</p>
        </div>
        <div class="stat-gold" style="text-align:center;">
          <p style="font-size:18px;font-weight:300;color:#fbbf24;font-family:'Geist Mono',monospace;">${(sess.lots_engages || 0).toFixed(2)}</p>
          <p style="font-size:9px;color:#52525b;margin-top:3px;">Lots</p>
        </div>
        <div style="background:rgba(248,113,113,.07);border:1px solid rgba(248,113,113,.12);border-radius:9px;padding:12px 14px;text-align:center;">
          <p style="font-size:18px;font-weight:300;color:#f87171;font-family:'Geist Mono',monospace;">-${Math.abs(sess.risque_sl || 0).toFixed(0)}$</p>
          <p style="font-size:9px;color:#52525b;margin-top:3px;">Risque SL</p>
        </div>
        <div style="background:rgba(52,211,153,.07);border:1px solid rgba(52,211,153,.12);border-radius:9px;padding:12px 14px;text-align:center;">
          <p style="font-size:18px;font-weight:300;color:#34d399;font-family:'Geist Mono',monospace;">+${(sess.gain_tp1 || 0).toFixed(0)}$</p>
          <p style="font-size:9px;color:#52525b;margin-top:3px;">Gain TP1</p>
        </div>
        <div style="background:rgba(52,211,153,.05);border:1px solid rgba(52,211,153,.1);border-radius:9px;padding:12px 14px;text-align:center;">
          <p style="font-size:18px;font-weight:300;color:#34d399;font-family:'Geist Mono',monospace;">+${(sess.gain_tp2 || 0).toFixed(0)}$</p>
          <p style="font-size:9px;color:#52525b;margin-top:3px;">Gain TP2</p>
        </div>
      </div>
    </div>` : `
    <div class="card" style="text-align:center;padding:24px;">
      <p style="font-size:12px;color:#52525b;">Aucune session Gold active</p>
      <a href="/trade" style="display:inline-flex;align-items:center;gap:5px;margin-top:10px;font-size:11px;color:#fbbf24;text-decoration:none;">Créer un trade Gold →</a>
    </div>`

  const simsHtml = sims.map(s => `
    <div class="sim-card">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
        <p style="font-size:11px;font-weight:500;color:#e4e4e7;">${s.name || s.nom}</p>
        <span style="font-size:9px;color:#52525b;">${s.initial_capital || s.initial}$</span>
      </div>
      <p style="font-size:20px;font-weight:300;font-family:'Geist Mono',monospace;"
         class="${(s.rendement_pct || 0) >= 0 ? 'pnl-pos' : 'pnl-neg'}">${(s.current_capital || s.capital_actuel || 0).toFixed(0)}$</p>
      <p style="font-size:10px;margin-top:3px;color:${(s.rendement_pct || 0) >= 0 ? '#34d399' : '#f87171'};">
        ${(s.rendement_pct || 0) > 0 ? '+' : ''}${(s.rendement_pct || 0).toFixed(2)}%
      </p>
    </div>`).join('')

  const saison = Object.keys(sais).length

  // Bloc saison stats (affiché seulement si saison active)
  const saisonBlock = saison ? `
    <div class="card">
      <p style="font-size:11px;color:#52525b;margin-bottom:12px;">Saison active</p>
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;">
        <div class="stat-mini" style="text-align:center;">
          <p style="font-size:18px;font-weight:300;color:#fafafa;font-family:'Geist Mono',monospace;">${sais.trades || 0}</p>
          <p style="font-size:9px;color:#52525b;margin-top:3px;">Trades</p>
        </div>
        <div class="stat-mini" style="text-align:center;">
          <p style="font-size:18px;font-weight:300;font-family:'Geist Mono',monospace;" class="pnl-pos">${sais.win_rate || 0}%</p>
          <p style="font-size:9px;color:#52525b;margin-top:3px;">Win rate</p>
        </div>
        <div class="stat-mini" style="text-align:center;">
          <p style="font-size:18px;font-weight:300;color:#34d399;font-family:'Geist Mono',monospace;">${sais.wins || 0}</p>
          <p style="font-size:9px;color:#34d399;margin-top:3px;">Wins</p>
        </div>
        <div class="stat-mini" style="text-align:center;">
          <p style="font-size:18px;font-weight:300;color:#f87171;font-family:'Geist Mono',monospace;">${sais.losses || 0}</p>
          <p style="font-size:9px;color:#f87171;margin-top:3px;">Losses</p>
        </div>
      </div>
      ${sais.rendement_pct != null ? `
        <div style="margin-top:12px;padding-top:12px;border-top:1px solid rgba(255,255,255,.05);display:flex;align-items:center;justify-content:space-between;">
          <span style="font-size:11px;color:#52525b;">Rendement saison membres</span>
          <span style="font-size:16px;font-weight:300;font-family:'Geist Mono',monospace;"
                class="${(sais.rendement_pct || 0) >= 0 ? 'pnl-pos' : 'pnl-neg'}">
            ${(sais.rendement_pct || 0) > 0 ? '+' : ''}${sais.rendement_pct || 0}%
          </span>
        </div>` : ''}
    </div>` : ''

  const simsBlock = sims.length ? `
    <div class="card">
      <p style="font-size:11px;color:#52525b;margin-bottom:10px;">Comptes simulation</p>
      <div style="display:grid;grid-template-columns:repeat(${Math.min(3, sims.length)},1fr);gap:8px;">${simsHtml}</div>
    </div>` : ''

  // Détermine si on affiche le sessionBlock (session active OU pas de session)
  const hasSession = Object.keys(sess).length > 0

  document.getElementById('row4-gold').innerHTML = `
    <div style="display:flex;flex-direction:column;gap:12px;" class="fadein">

      <!-- Titre -->
      <div style="display:flex;align-items:center;gap:8px;">
        <span style="font-size:16px;">⚡</span>
        <p style="font-size:13px;font-weight:500;color:#fafafa;">Gold XAU/USD</p>
        ${saison ? `<span class="badge badge-gold">Saison : ${sais.nom || '—'}</span>` : ''}
      </div>

      <!-- Layout : grille responsive
           - Pas de session + pas de saison  → 1 col (rien ou juste sims)
           - Session active                  → session pleine largeur, puis saison+sims en dessous
           - Saison sans session             → saison + sims côte à côte sur desktop
      -->
      ${hasSession ? `
        <!-- Session pleine largeur -->
        ${sessionBlock}
        <!-- Saison + sims en dessous, côte à côte sur desktop -->
        ${(saisonBlock || simsBlock) ? `
        <div class="gold-bottom-grid">
          ${saisonBlock}
          ${simsBlock}
        </div>` : ''}
      ` : `
        <!-- Pas de session : saison + sims côte à côte, ou juste l'un d'eux -->
        ${(saisonBlock || simsBlock) ? `
        <div class="gold-bottom-grid">
          ${saisonBlock}
          ${simsBlock}
        </div>` : `
        <div class="card" style="text-align:center;padding:24px;">
          <p style="font-size:12px;color:#52525b;">Aucune session Gold active</p>
          <a href="/trade" style="display:inline-flex;align-items:center;gap:5px;margin-top:10px;font-size:11px;color:#fbbf24;text-decoration:none;">Créer un trade Gold →</a>
        </div>`}
      `}

    </div>
  `
}

/* ═══════════════════════════════════════════════════════════
   RENDER — ACTIVITÉ & EXPIRATIONS
   ═══════════════════════════════════════════════════════════ */
function renderRow5(d) {
  const activites   = d.activite_recente || []
  const expirations = d.expirations_proches || []

  const badgeColor = {
    Trade: 'green', Auto: 'teal', Form: 'sky',
    Gold: 'amber', 'Expiré': 'red', Testi: 'violet'
  }

  const actHtml = activites.length
    ? activites.map(a => {
        const col   = a.couleur || avColor(a.nom)
        const badge = badgeColor[a.badge] || 'zinc'
        return `
          <div class="t-row">
            <div class="av av-${col}" style="font-size:9px;">${a.initiales || initials(a.nom)}</div>
            <div style="flex:1;min-width:0;">
              <p style="font-size:12px;color:#e4e4e7;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${a.nom} — ${a.description}</p>
              <p style="font-size:11px;margin-top:2px;color:#52525b;">${a.temps}</p>
            </div>
            <span class="badge badge-${badge}">${a.badge}</span>
          </div>`
      }).join('')
    : '<div style="padding:20px;text-align:center;font-size:12px;color:#3f3f46;">Aucune activité récente</div>'

  const expHtml = expirations.length
    ? expirations.map(e => {
        const urgent = (e.jours_restants || 0) <= 3
        const col    = urgent ? '#f87171' : '#fbbf24'
        return `
          <div class="t-row">
            <div class="av av-${avColor(e.nom)}" style="font-size:9px;">${initials(e.nom)}</div>
            <div style="flex:1;min-width:0;">
              <p style="font-size:12px;color:#e4e4e7;">${e.nom}</p>
              <p style="font-size:11px;margin-top:2px;color:#52525b;">${e.plan}</p>
            </div>
            <span style="font-size:12px;font-family:'Geist Mono',monospace;font-weight:${urgent ? '600' : '400'};color:${col};">${e.jours_restants}j</span>
          </div>`
      }).join('')
    : '<div style="padding:20px;text-align:center;font-size:12px;color:#3f3f46;">Aucune expiration prochaine</div>'

  document.getElementById('row5-grid').innerHTML = `
    <div class="card fadein">
      <p style="font-size:13px;font-weight:500;color:#fafafa;margin-bottom:16px;">Activité récente</p>
      ${actHtml}
    </div>
    <div class="card fadein" style="animation-delay:.05s">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <p style="font-size:13px;font-weight:500;color:#fafafa;">Expirations proches</p>
        <span style="font-size:11px;color:#52525b;">7 prochains jours</span>
      </div>
      ${expHtml}
    </div>
  `
}

/* ═══════════════════════════════════════════════════════════
   MAIN LOAD
   ═══════════════════════════════════════════════════════════ */
async function loadDashboard() {
  const btn = document.getElementById('btn-refresh')
  if (btn) btn.disabled = true
  try {
    const d = await apiFetch('/dashboard/stats')
    renderMetrics(d)
    renderRow2(d)
    renderTrading(d)
    renderGold(d)
    renderRow5(d)

    const now = new Date()
    const label = document.getElementById('last-refresh-label')
    if (label) {
      label.innerHTML = `
        <span class="refresh-dot pulse" style="display:inline-block;"></span>
        Mis à jour à ${now.toLocaleTimeString('fr', { hour: '2-digit', minute: '2-digit' })}`
    }
  } catch (e) {
    toast('Erreur chargement dashboard : ' + e.message, 'error')
  } finally {
    if (btn) btn.disabled = false
  }
}

/* ─── Init ────────────────────────────────────────────────── */
// Script chargé en fin de <body> → DOM déjà prêt, pas besoin de DOMContentLoaded

// Sidebar mobile
document.getElementById('btn-menu').addEventListener('click', openSidebar)
document.getElementById('sidebar-close').addEventListener('click', closeSidebar)
document.getElementById('sidebar-overlay').addEventListener('click', closeSidebar)

// Fermer la sidebar sur clic d'un lien nav (mobile)
document.querySelectorAll('.nav-item').forEach(link => {
  link.addEventListener('click', () => {
    if (window.innerWidth <= 768) closeSidebar()
  })
})

// Charger le dashboard
loadDashboard()

// Auto-refresh toutes les 60s
setInterval(loadDashboard, 60_000)