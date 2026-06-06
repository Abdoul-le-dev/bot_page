<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TradingBot — Dashboard</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400,500" rel="stylesheet">

  <!-- Tailwind (utilitaires mineurs uniquement) -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Geist', 'sans-serif'],
            mono: ['Geist Mono', 'monospace']
          }
        }
      }
    }
  </script>

  <!-- Styles du dashboard -->
  
  <style>
    /* ═══════════════════════════════════════════════════════════
   dashboard.css — TradingBot Dashboard
   ═══════════════════════════════════════════════════════════ */

/* ── Reset & Base ── */
*, *::before, *::after { box-sizing: border-box; }

body {
  background: #09090b;
  font-family: 'Geist', sans-serif;
  color: #e4e4e7;
  margin: 0;
  padding: 0;
}

::-webkit-scrollbar { width: 3px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(255,255,255,.08); border-radius: 99px; }

/* ═══════════════════════════════════════════════════════════
   LAYOUT
   ═══════════════════════════════════════════════════════════ */

.app-shell {
  display: flex;
  height: 100vh;
  overflow: hidden;
}

/* ── Sidebar ── */
.sidebar {
  width: 200px;
  flex-shrink: 0;
  background: #0d0d0f;
  border-right: 1px solid rgba(255,255,255,.05);
  display: flex;
  flex-direction: column;
  height: 100vh;
  position: relative;
  z-index: 100;
  transition: transform .25s cubic-bezier(.4,0,.2,1);
}

.sidebar-header {
  padding: 16px;
  border-bottom: 1px solid rgba(255,255,255,.05);
  flex-shrink: 0;
}

.sidebar-brand {
  display: flex;
  align-items: center;
  gap: 8px;
}

.brand-icon {
  width: 24px;
  height: 24px;
  background: rgba(251,191,36,.15);
  border: 1px solid rgba(251,191,36,.3);
  border-radius: 6px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 11px;
  flex-shrink: 0;
}

.brand-name {
  font-size: 13px;
  font-weight: 500;
  color: #fafafa;
}

.sidebar-nav {
  flex: 1;
  padding: 8px;
  display: flex;
  flex-direction: column;
  gap: 2px;
  overflow-y: auto;
}

.sidebar-footer {
  padding: 12px;
  border-top: 1px solid rgba(255,255,255,.05);
  flex-shrink: 0;
}

.sidebar-user {
  display: flex;
  align-items: center;
  gap: 8px;
}

/* Bouton fermeture sidebar mobile */
.sidebar-close {
  display: none;
  position: absolute;
  top: 14px;
  right: 12px;
  background: rgba(255,255,255,.05);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 6px;
  color: #71717a;
  cursor: pointer;
  padding: 4px 6px;
  line-height: 1;
  font-size: 14px;
  transition: all .15s;
}
.sidebar-close:hover { background: rgba(255,255,255,.1); color: #e4e4e7; }

/* Overlay mobile */
.sidebar-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.6);
  backdrop-filter: blur(4px);
  z-index: 99;
  opacity: 0;
  transition: opacity .25s ease;
}
.sidebar-overlay.visible { opacity: 1; }

/* ── Nav items ── */
.nav-section {
  font-size: 10px;
  font-weight: 500;
  color: #3f3f46;
  letter-spacing: .07em;
  text-transform: uppercase;
  padding: 10px 10px 3px;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 6px 10px;
  border-radius: 7px;
  font-size: 13px;
  color: #52525b;
  cursor: pointer;
  transition: all .15s;
  border: none;
  background: none;
  width: 100%;
  text-align: left;
  text-decoration: none;
}
.nav-item:hover { color: #d4d4d8; background: rgba(255,255,255,.04); }
.nav-item.active { color: #fafafa; background: rgba(255,255,255,.07); }
.nav-item svg { width: 14px; height: 14px; flex-shrink: 0; opacity: .7; }
.nav-item.active svg { opacity: 1; }

/* ── Main area ── */
.main {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
  overflow: hidden;
}

/* ── Topbar ── */
.topbar {
  height: 52px;
  background: rgba(9,9,11,.9);
  backdrop-filter: blur(12px);
  border-bottom: 1px solid rgba(255,255,255,.05);
  flex-shrink: 0;
  display: flex;
  align-items: center;
  padding: 0 24px;
  gap: 16px;
}

.topbar-left {
  display: flex;
  align-items: center;
  gap: 8px;
  flex: 1;
  min-width: 0;
}

.topbar-right {
  display: flex;
  align-items: center;
  gap: 8px;
  flex-shrink: 0;
}

.topbar-title {
  font-size: 13px;
  font-weight: 500;
  color: #fafafa;
  white-space: nowrap;
}

.topbar-divider {
  color: #27272a;
}

/* Bouton hamburger (mobile uniquement) */
.btn-menu {
  display: none;
  align-items: center;
  justify-content: center;
  width: 32px;
  height: 32px;
  background: rgba(255,255,255,.05);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 7px;
  color: #a1a1aa;
  cursor: pointer;
  transition: all .15s;
  flex-shrink: 0;
}
.btn-menu:hover { background: rgba(255,255,255,.09); color: #e4e4e7; }

/* ── Content area ── */
.content {
  flex: 1;
  overflow-y: auto;
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 16px;
}

/* ═══════════════════════════════════════════════════════════
   CARDS
   ═══════════════════════════════════════════════════════════ */

.card {
  background: #111113;
  border: 1px solid rgba(255,255,255,.06);
  border-radius: 12px;
  padding: 20px;
}

.card-gold {
  background: rgba(251,191,36,.03);
  border: 1px solid rgba(251,191,36,.14);
  border-radius: 12px;
  padding: 20px;
}

.card-sm {
  background: #111113;
  border: 1px solid rgba(255,255,255,.06);
  border-radius: 10px;
  padding: 14px;
}

/* ═══════════════════════════════════════════════════════════
   BADGES
   ═══════════════════════════════════════════════════════════ */

.badge {
  display: inline-flex;
  align-items: center;
  padding: 2px 8px;
  border-radius: 99px;
  font-size: 10px;
  font-weight: 500;
  white-space: nowrap;
}

.badge-green  { background: rgba(52,211,153,.1);   color: #34d399; }
.badge-sky    { background: rgba(56,189,248,.1);   color: #38bdf8; }
.badge-amber  { background: rgba(251,191,36,.1);   color: #fbbf24; }
.badge-red    { background: rgba(248,113,113,.1);  color: #f87171; }
.badge-violet { background: rgba(167,139,250,.1);  color: #a78bfa; }
.badge-teal   { background: rgba(45,212,191,.1);   color: #2dd4bf; }
.badge-zinc   { background: rgba(255,255,255,.06); color: #71717a; }
.badge-gold   { background: rgba(251,191,36,.12);  color: #fbbf24; border: 1px solid rgba(251,191,36,.2); }

/* ═══════════════════════════════════════════════════════════
   AVATARS
   ═══════════════════════════════════════════════════════════ */

.av {
  width: 28px;
  height: 28px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 10px;
  font-weight: 600;
  flex-shrink: 0;
}

.av-green   { background: rgba(52,211,153,.15);  color: #34d399; }
.av-sky     { background: rgba(56,189,248,.15);  color: #38bdf8; }
.av-amber   { background: rgba(251,191,36,.15);  color: #fbbf24; }
.av-violet  { background: rgba(167,139,250,.15); color: #a78bfa; }
.av-teal    { background: rgba(45,212,191,.15);  color: #2dd4bf; }
.av-red     { background: rgba(248,113,113,.15); color: #f87171; }
.av-default { background: rgba(255,255,255,.07); color: #71717a; }

/* ═══════════════════════════════════════════════════════════
   BUTTONS
   ═══════════════════════════════════════════════════════════ */

.btn-primary {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 6px 13px;
  font-size: 12px;
  font-weight: 500;
  background: #38bdf8;
  color: #082f49;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  transition: background .15s;
  font-family: 'Geist', sans-serif;
  white-space: nowrap;
  text-decoration: none;
}
.btn-primary:hover { background: #7dd3fc; }

.btn-ghost {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  padding: 5px 11px;
  font-size: 11px;
  background: rgba(255,255,255,.05);
  color: #a1a1aa;
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 7px;
  cursor: pointer;
  transition: all .15s;
  font-family: 'Geist', sans-serif;
  white-space: nowrap;
}
.btn-ghost:hover { background: rgba(255,255,255,.09); color: #e4e4e7; }

/* ═══════════════════════════════════════════════════════════
   PROGRESS BARS
   ═══════════════════════════════════════════════════════════ */

.pbar {
  height: 3px;
  background: rgba(255,255,255,.06);
  border-radius: 99px;
  overflow: hidden;
}

.pbar-fill {
  height: 100%;
  border-radius: 99px;
  transition: width .6s cubic-bezier(.4,0,.2,1);
}

.pbar-md {
  height: 5px;
  background: rgba(255,255,255,.05);
  border-radius: 99px;
  overflow: hidden;
}

/* ═══════════════════════════════════════════════════════════
   GRIDS
   ═══════════════════════════════════════════════════════════ */

.metrics-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
}

.row2-grid {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 12px;
}

.row3-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

.row4-grid {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 12px;
}

/* ═══════════════════════════════════════════════════════════
   COMPONENTS
   ═══════════════════════════════════════════════════════════ */

/* Activity rows */
.t-row {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 9px 0;
  border-bottom: 1px solid rgba(255,255,255,.04);
}
.t-row:last-child { border-bottom: none; }

/* Alert rows */
.alert-row {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  background: rgba(255,255,255,.025);
  border-radius: 9px;
}

/* Stat minis */
.stat-mini {
  background: rgba(255,255,255,.03);
  border: 1px solid rgba(255,255,255,.05);
  border-radius: 9px;
  padding: 12px 14px;
}

.stat-gold {
  background: rgba(251,191,36,.04);
  border: 1px solid rgba(251,191,36,.12);
  border-radius: 9px;
  padding: 12px 14px;
}

/* Simulation card */
.sim-card {
  background: rgba(255,255,255,.03);
  border: 1px solid rgba(255,255,255,.06);
  border-radius: 9px;
  padding: 12px;
}

/* ═══════════════════════════════════════════════════════════
   LIVE DOT & ANIMATIONS
   ═══════════════════════════════════════════════════════════ */

.live-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: #34d399;
  display: inline-block;
  flex-shrink: 0;
}

@keyframes pulse {
  0%, 100% { opacity: 1; }
  50% { opacity: .3; }
}
.pulse { animation: pulse 2s ease infinite; }

/* Skeleton */
.skel {
  background: rgba(255,255,255,.05);
  border-radius: 4px;
  animation: skelPulse 1.4s ease infinite;
}
@keyframes skelPulse {
  0%, 100% { opacity: .4; }
  50% { opacity: .9; }
}

/* Fade in */
@keyframes fadein {
  from { opacity: 0; transform: translateY(6px); }
  to   { opacity: 1; transform: translateY(0); }
}
.fadein { animation: fadein .25s ease; }

/* ═══════════════════════════════════════════════════════════
   MISC
   ═══════════════════════════════════════════════════════════ */

.pnl-pos { color: #34d399; }
.pnl-neg { color: #f87171; }

.refresh-dot {
  width: 5px;
  height: 5px;
  border-radius: 50%;
  background: #34d399;
}

.last-refresh-label {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 11px;
  color: #52525b;
}

/* ── Toast ── */
#toast-container {
  position: fixed;
  bottom: 20px;
  right: 20px;
  z-index: 999;
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.toast-item {
  padding: 10px 16px;
  border-radius: 9px;
  font-size: 12px;
  font-weight: 500;
  border: 1px solid;
  animation: fadeUp .2s ease;
  max-width: 300px;
}
.toast-item.success { background: rgba(52,211,153,.1);  border-color: rgba(52,211,153,.3);  color: #34d399; }
.toast-item.error   { background: rgba(248,113,113,.1); border-color: rgba(248,113,113,.3); color: #f87171; }
.toast-item.info    { background: rgba(56,189,248,.1);  border-color: rgba(56,189,248,.3);  color: #38bdf8; }

@keyframes fadeUp {
  from { opacity: 0; transform: translateY(8px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ═══════════════════════════════════════════════════════════
   RESPONSIVE
   ═══════════════════════════════════════════════════════════ */

/* ── ≤1280px ── */
@media (max-width: 1280px) {
  .row2-grid  { grid-template-columns: 1fr 1fr; }
  .row4-grid  { grid-template-columns: 1fr 1fr; }
}

/* ── ≤1024px ── */
@media (max-width: 1024px) {
  .metrics-grid { grid-template-columns: 1fr 1fr; }
  .row2-grid    { grid-template-columns: 1fr; }
  .row3-grid    { grid-template-columns: 1fr; }
  .row4-grid    { grid-template-columns: 1fr 1fr; }
}

/* ── ≤768px — Mobile ── */
@media (max-width: 768px) {

  /* Sidebar : hors-écran par défaut */
  .sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 240px;
    transform: translateX(-100%);
    box-shadow: 4px 0 24px rgba(0,0,0,.5);
  }

  /* Sidebar ouverte */
  .sidebar.open {
    transform: translateX(0);
  }

  /* Overlay visible quand sidebar ouverte */
  .sidebar-overlay {
    display: block;
  }

  /* Bouton X dans la sidebar */
  .sidebar-close {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Bouton hamburger topbar */
  .btn-menu {
    display: flex;
  }

  /* Topbar padding réduit */
  .topbar {
    padding: 0 14px;
    gap: 8px;
  }

  /* Masquer "Nouveau trade" sur très petit écran, garder le refresh */
  .btn-primary-label { display: none; }

  /* Grids 1 colonne */
  .metrics-grid { grid-template-columns: 1fr 1fr; }
  .row4-grid    { grid-template-columns: 1fr; }
  .row3-grid    { grid-template-columns: 1fr; }

  .content { padding: 12px; gap: 12px; }
}

/* ── ≤480px ── */
@media (max-width: 480px) {
  .metrics-grid { grid-template-columns: 1fr; }

  .topbar-title, .topbar-divider { display: none; }

  .btn-primary {
    padding: 6px 10px;
    font-size: 11px;
  }
}</style>
</head>

<body>

<!-- ── Toast ──────────────────────────────────────────────────── -->
<div id="toast-container"></div>

<!-- ── Overlay mobile (ferme la sidebar) ──────────────────────── -->
<div id="sidebar-overlay" class="sidebar-overlay"></div>

<!-- ═══════════════════════════════════════════════════════════
     APP SHELL
     ═══════════════════════════════════════════════════════════ -->
<div class="app-shell">

  <!-- ─── SIDEBAR ──────────────────────────────────────────────── -->
  <aside class="sidebar" id="sidebar">

    <!-- Bouton fermeture (mobile) -->
    <button class="sidebar-close" id="sidebar-close" aria-label="Fermer le menu">✕</button>

    <!-- Logo / Brand -->
    <div class="sidebar-header">
      <div class="sidebar-brand">
        <div class="brand-icon">⚡</div>
        <span class="brand-name">TradingBot</span>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav">

      <div class="nav-section">Principal</div>

      <a href="/dashboard" class="nav-item active">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
          <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
        </svg>
        Dashboard
      </a>

      <a href="{{ route('categories') }}" class="nav-item {{ request()->is('categorie') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
          <line x1="7" y1="7" x2="7.01" y2="7"/>
        </svg>
        Catégories
      </a>

      <a href="/chat" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
        Chat direct
      </a>

      <a href="/message" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M22 2 11 13"/>
          <path d="m22 2-7 20-4-9-9-4 20-7z"/>
        </svg>
        Broadcast
      </a>

      <div class="nav-section" style="margin-top:8px;">Trading</div>

      <a href="/trade" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
        </svg>
        Trade
      </a>

      <div class="nav-section" style="margin-top:8px;">Outils</div>

      <a href="/form" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
        </svg>
        Formulaires
      </a>

      <a href="/ai" class="nav-item">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <circle cx="12" cy="12" r="3"/>
          <path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/>
        </svg>
        Agent IA
      </a>

    </nav><!-- /nav -->

    <!-- Footer utilisateur -->
    <div class="sidebar-footer">
      <div class="sidebar-user">
        <div class="av av-default" style="width:24px;height:24px;font-size:9px;">AD</div>
        <div>
          <p style="font-size:11px;color:#d4d4d8;font-weight:500;">Admin</p>
          <p style="font-size:10px;color:#52525b;">fdkvip.com</p>
        </div>
      </div>
    </div>

  </aside><!-- /sidebar -->

  <!-- ─── MAIN ───────────────────────────────────────────────── -->
  <div class="main">

    <!-- Topbar -->
    <header class="topbar">

      <!-- Gauche : hamburger + titre -->
      <div class="topbar-left">
        <!-- Hamburger (mobile uniquement) -->
        <button class="btn-menu" id="btn-menu" aria-label="Ouvrir le menu">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <line x1="3" y1="6"  x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </button>

        <h1 class="topbar-title">Dashboard</h1>
        <span class="topbar-divider">·</span>
        <div class="last-refresh-label" id="last-refresh-label">—</div>
      </div>

      <!-- Droite : live + actions -->
      <div class="topbar-right">
        <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#34d399;">
          <span class="live-dot pulse"></span>
          <span class="topbar-title" style="font-size:11px;color:#34d399;">Live</span>
        </div>

        <button class="btn-ghost" onclick="loadDashboard()" id="btn-refresh">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <polyline points="23 4 23 10 17 10"/>
            <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
          </svg>
          <span class="btn-primary-label">Actualiser</span>
        </button>

        <a href="/trade" class="btn-primary">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <line x1="12" y1="4" x2="12" y2="20"/>
            <line x1="4" y1="12" x2="20" y2="12"/>
          </svg>
          <span class="btn-primary-label">Nouveau trade</span>
        </a>
      </div>

    </header><!-- /topbar -->

    <!-- Content -->
    <main class="content" id="dashboard-content">

      <!-- ROW 1 : 4 métriques -->
      <div class="metrics-grid" id="metrics-grid">
        <div class="card skel" style="height:96px;"></div>
        <div class="card skel" style="height:96px;"></div>
        <div class="card skel" style="height:96px;"></div>
        <div class="card skel" style="height:96px;"></div>
      </div>

      <!-- ROW 2 : Segments · Alertes · Agent IA -->
      <div class="row2-grid" id="row2-grid">
        <div class="card skel" style="height:220px;"></div>
        <div class="card skel" style="height:220px;"></div>
        <div class="card skel" style="height:220px;"></div>
      </div>

      <!-- ROW 3 : Trading stats -->
      <div id="row3-trading"></div>

      <!-- ROW 4 : Gold -->
      <div id="row4-gold"></div>

      <!-- ROW 5 : Activité · Expirations -->
      <div class="row3-grid" id="row5-grid">
        <div class="card skel" style="height:240px;"></div>
        <div class="card skel" style="height:240px;"></div>
      </div>

    </main><!-- /content -->

  </div><!-- /main -->

</div><!-- /app-shell -->

<!-- Scripts -->

<script >
  /* ═══════════════════════════════════════════════════════════
   dashboard.js — TradingBot Dashboard
   ═══════════════════════════════════════════════════════════ */

const API_URL = 'https://fdkvip.com'

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
  const sidebar  = document.getElementById('sidebar')
  const overlay  = document.getElementById('sidebar-overlay')
  sidebar.classList.add('open')
  overlay.classList.add('visible')
  document.body.style.overflow = 'hidden'
}

function closeSidebar() {
  const sidebar  = document.getElementById('sidebar')
  const overlay  = document.getElementById('sidebar-overlay')
  sidebar.classList.remove('open')
  overlay.classList.remove('visible')
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
      <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px;">
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

  document.getElementById('row4-gold').innerHTML = `
    <div style="display:flex;flex-direction:column;gap:12px;" class="fadein">
      <div style="display:flex;align-items:center;gap:8px;">
        <span style="font-size:16px;">⚡</span>
        <p style="font-size:13px;font-weight:500;color:#fafafa;">Gold XAU/USD</p>
        ${saison ? `<span class="badge badge-gold">Saison : ${sais.nom || '—'}</span>` : ''}
      </div>
      <div style="display:grid;grid-template-columns:${saison ? '1fr 1fr' : '1fr'};gap:12px;align-items:start;">
        <div style="display:flex;flex-direction:column;gap:12px;">
          ${sessionBlock}
        </div>
        ${saison ? `
        <div style="display:flex;flex-direction:column;gap:12px;">
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
          </div>
          ${sims.length ? `
          <div class="card">
            <p style="font-size:11px;color:#52525b;margin-bottom:10px;">Comptes simulation</p>
            <div style="display:grid;grid-template-columns:repeat(${Math.min(3, sims.length)},1fr);gap:8px;">${simsHtml}</div>
          </div>` : ''}
        </div>` : (sims.length ? `
        <div class="card">
          <p style="font-size:11px;color:#52525b;margin-bottom:10px;">Comptes simulation</p>
          <div style="display:grid;grid-template-columns:repeat(${Math.min(3, sims.length)},1fr);gap:8px;">${simsHtml}</div>
        </div>` : '')}
      </div>
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
document.addEventListener('DOMContentLoaded', () => {
  // Sidebar mobile : bouton hamburger
  document.getElementById('btn-menu')?.addEventListener('click', openSidebar)
  document.getElementById('sidebar-close')?.addEventListener('click', closeSidebar)
  document.getElementById('sidebar-overlay')?.addEventListener('click', closeSidebar)

  // Fermer la sidebar si on clique sur un lien nav (mobile)
  document.querySelectorAll('.nav-item').forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 768) closeSidebar()
    })
  })

  // Charger le dashboard
  loadDashboard()

  // Auto-refresh toutes les 60s
  setInterval(loadDashboard, 60_000)
})
</script>

</body>
</html>