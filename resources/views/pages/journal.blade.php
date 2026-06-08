<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title>Felipe Gagne</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:400,500,600&family=geist-mono:400,500" rel="stylesheet">
<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: { sans: ['Geist','sans-serif'], mono: ['Geist Mono','monospace'] },
      colors: {
        zinc: { 950: '#09090b' },
        gold: { DEFAULT: '#f59e0b', light: '#fcd34d', dark: '#b45309', bg: 'rgba(245,158,11,0.08)', border: 'rgba(245,158,11,0.25)' }
      }
    }
  }
}
</script>
<style>
* { box-sizing: border-box; -webkit-tap-highlight-color: transparent; }
body { background: #09090b; font-family: 'Geist', sans-serif; overscroll-behavior: none; }
::-webkit-scrollbar { width: 2px; height: 2px; }
::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.06); border-radius: 99px; }

/* Sidebar */
#sidebar { transition: transform 0.25s cubic-bezier(0.4,0,0.2,1); }
#sidebar-overlay { transition: opacity 0.25s; }

/* Nav */
.nav-link { display: flex; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 8px; font-size: 13px; color: #71717a; cursor: pointer; transition: all 0.15s; border: none; background: none; width: 100%; text-align: left; white-space: nowrap; }
.nav-link:hover { color: #e4e4e7; background: rgba(255,255,255,0.05); }
.nav-link.active { color: #fafafa; background: rgba(255,255,255,0.08); }
.nav-link svg { width: 16px; height: 16px; flex-shrink: 0; }
.nav-section { font-size: 10px; font-weight: 500; color: #3f3f46; letter-spacing: 0.07em; text-transform: uppercase; padding: 14px 12px 4px; }

/* Cards */
.card { background: #111113; border: 1px solid rgba(255,255,255,0.06); border-radius: 12px; }
.card-gold { background: rgba(245,158,11,0.04); border: 1px solid rgba(245,158,11,0.18); border-radius: 12px; }
.stat-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.05); border-radius: 10px; padding: 12px 14px; }
.stat-gold { background: rgba(245,158,11,0.05); border: 1px solid rgba(245,158,11,0.15); border-radius: 10px; padding: 12px 14px; }
.agg-loss { background: rgba(239,68,68,0.07); border: 1px solid rgba(239,68,68,0.18); border-radius: 8px; padding: 10px 12px; text-align: center; }
.agg-gain { background: rgba(34,197,94,0.07); border: 1px solid rgba(34,197,94,0.18); border-radius: 8px; padding: 10px 12px; text-align: center; }
.agg-neu { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); border-radius: 8px; padding: 10px 12px; text-align: center; }

/* Badges */
.badge { display: inline-flex; align-items: center; padding: 2px 8px; border-radius: 99px; font-size: 11px; font-weight: 500; white-space: nowrap; }
.badge-gold { background: rgba(245,158,11,0.12); color: #f59e0b; border: 1px solid rgba(245,158,11,0.25); }
.badge-green { background: rgba(34,197,94,0.1); color: #22c55e; border: 1px solid rgba(34,197,94,0.2); }
.badge-red { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }
.badge-sky { background: rgba(56,189,248,0.1); color: #38bdf8; border: 1px solid rgba(56,189,248,0.2); }
.badge-violet { background: rgba(167,139,250,0.1); color: #a78bfa; border: 1px solid rgba(167,139,250,0.2); }
.badge-zinc { background: rgba(255,255,255,0.05); color: #71717a; border: 1px solid rgba(255,255,255,0.08); }
.badge-amber { background: rgba(251,191,36,0.1); color: #fbbf24; border: 1px solid rgba(251,191,36,0.2); }

/* Direction */
.dir-buy { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 500; background: rgba(34,197,94,0.1); color: #22c55e; border: 1px solid rgba(34,197,94,0.2); }
.dir-sell { display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 6px; font-size: 11px; font-weight: 500; background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }

/* Phase */
.phase-teaser { background: rgba(113,113,122,0.1); color: #a1a1aa; border: 1px solid rgba(113,113,122,0.2); }
.phase-open { background: rgba(34,197,94,0.1); color: #22c55e; border: 1px solid rgba(34,197,94,0.2); }
.phase-tp1 { background: rgba(56,189,248,0.1); color: #38bdf8; border: 1px solid rgba(56,189,248,0.2); }
.phase-tp2 { background: rgba(56,189,248,0.15); color: #7dd3fc; border: 1px solid rgba(56,189,248,0.3); }
.phase-tp3 { background: rgba(167,139,250,0.1); color: #a78bfa; border: 1px solid rgba(167,139,250,0.2); }
.phase-sl { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.2); }
.phase-closed { background: rgba(255,255,255,0.04); color: #71717a; border: 1px solid rgba(255,255,255,0.07); }

/* Buttons */
.btn-primary { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; font-size: 13px; font-weight: 500; background: #38bdf8; color: #082f49; border: none; border-radius: 8px; cursor: pointer; transition: background 0.15s; font-family: 'Geist', sans-serif; white-space: nowrap; }
.btn-primary:hover { background: #7dd3fc; }
.btn-primary:active { background: #0ea5e9; }
.btn-gold { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; font-size: 13px; font-weight: 500; background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.35); border-radius: 8px; cursor: pointer; transition: all 0.15s; font-family: 'Geist', sans-serif; white-space: nowrap; }
.btn-gold:hover { background: rgba(245,158,11,0.25); }
.btn-ghost { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; font-size: 12px; background: rgba(255,255,255,0.04); color: #a1a1aa; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; cursor: pointer; transition: all 0.15s; font-family: 'Geist', sans-serif; white-space: nowrap; }
.btn-ghost:hover { background: rgba(255,255,255,0.08); color: #e4e4e7; }
.btn-icon { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; color: #52525b; border: none; background: rgba(255,255,255,0.04); border-radius: 8px; cursor: pointer; transition: all 0.15s; flex-shrink: 0; }
.btn-icon:hover { background: rgba(255,255,255,0.08); color: #d4d4d8; }
.btn-danger { display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; font-size: 12px; font-weight: 500; background: rgba(239,68,68,0.12); color: #ef4444; border: 1px solid rgba(239,68,68,0.25); border-radius: 8px; cursor: pointer; transition: all 0.15s; font-family: 'Geist', sans-serif; }
.btn-danger:hover { background: rgba(239,68,68,0.2); }

/* Inputs */
.input { width: 100%; padding: 9px 12px; font-size: 13px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #e4e4e7; font-family: 'Geist', sans-serif; outline: none; transition: border-color 0.15s; }
.input:focus { border-color: rgba(245,158,11,0.5); }
.input::placeholder { color: #3f3f46; }
select.input { cursor: pointer; }
textarea.input { resize: vertical; min-height: 60px; }
.msg-editor { width: 100%; min-height: 70px; padding: 9px 12px; font-size: 12px; line-height: 1.6; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; color: #e4e4e7; font-family: 'Geist', sans-serif; outline: none; resize: vertical; transition: border-color 0.15s; }
.msg-editor:focus { border-color: rgba(245,158,11,0.4); }

/* Modal */
.modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 60; display: flex; align-items: flex-end; justify-content: center; opacity: 0; pointer-events: none; transition: opacity 0.2s; }
@media (min-width: 640px) { .modal-overlay { align-items: center; } }
.modal-overlay.open { opacity: 1; pointer-events: auto; }
.modal { background: #111113; border: 1px solid rgba(255,255,255,0.1); border-radius: 16px 16px 0 0; width: 100%; max-height: 92vh; display: flex; flex-direction: column; overflow: hidden; transform: translateY(20px); transition: transform 0.2s; }
@media (min-width: 640px) { .modal { border-radius: 14px; max-width: 640px; transform: scale(0.97); } }
.modal-overlay.open .modal { transform: translateY(0) scale(1); }

/* Drawer */
.drawer-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.65); z-index: 40; opacity: 0; pointer-events: none; transition: opacity 0.2s; }
.drawer-overlay.open { opacity: 1; pointer-events: auto; }
.drawer { position: fixed; bottom: 0; left: 0; right: 0; background: #111113; border-top: 1px solid rgba(255,255,255,0.08); z-index: 50; transform: translateY(100%); transition: transform 0.25s cubic-bezier(0.4,0,0.2,1); display: flex; flex-direction: column; max-height: 90vh; border-radius: 16px 16px 0 0; overflow: hidden; }
@media (min-width: 768px) { .drawer { top: 0; bottom: 0; right: 0; left: auto; width: 520px; border-radius: 0; border-left: 1px solid rgba(255,255,255,0.08); border-top: none; transform: translateX(100%); max-height: 100vh; } }
.drawer.open { transform: translateY(0) translateX(0); }

/* Ticker */
.ticker { font-family: 'Geist Mono', monospace; font-size: 14px; font-weight: 500; padding: 3px 8px; border-radius: 6px; }
.ticker-up { color: #22c55e; background: rgba(34,197,94,0.1); }
.ticker-down { color: #ef4444; background: rgba(239,68,68,0.1); }
.ticker-neutral { color: #f59e0b; background: rgba(245,158,11,0.1); }

/* Live dot */
.live-dot { width: 7px; height: 7px; border-radius: 50%; background: #22c55e; flex-shrink: 0; }
.live-dot-gold { background: #f59e0b; }
@keyframes pulse { 0%,100%{opacity:1}50%{opacity:0.3} }
.pulse { animation: pulse 2s ease infinite; }

/* Session row */
.session-row { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-bottom: 1px solid rgba(255,255,255,0.04); cursor: pointer; transition: background 0.1s; }
.session-row:hover { background: rgba(245,158,11,0.03); }
.session-row:last-child { border-bottom: none; }

/* Sim card */
.sim-card { background: #111113; border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 14px; transition: border-color 0.15s; cursor: pointer; }
.sim-card:hover { border-color: rgba(245,158,11,0.25); }

/* Gold close buttons */
.gold-close-btn { padding: 10px; border-radius: 8px; cursor: pointer; font-size: 12px; font-family: 'Geist', sans-serif; text-align: center; transition: all 0.15s; border-width: 1px; border-style: solid; }

/* Perf */
.perf-row { display: flex; align-items: center; gap: 10px; padding: 10px 16px; border-bottom: 1px solid rgba(255,255,255,0.04); cursor: pointer; transition: background 0.1s; }
.perf-row:hover { background: rgba(255,255,255,0.02); }
.pbar { height: 3px; background: rgba(255,255,255,0.06); border-radius: 99px; overflow: hidden; margin-top: 4px; }
.pbar-fill { height: 100%; border-radius: 99px; }

/* Rule card */
.rule-card { background: #111113; border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; padding: 16px; }
.rule-field label { font-size: 10px; color: #52525b; display: block; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.05em; }

/* Upload */
.upload-zone { border: 1px dashed rgba(255,255,255,0.12); border-radius: 8px; padding: 16px; text-align: center; cursor: pointer; transition: all 0.15s; }
.upload-zone:hover { border-color: rgba(245,158,11,0.4); background: rgba(245,158,11,0.03); }

/* Confidence */
.conf-btn { flex: 1; padding: 7px 4px; border-radius: 7px; cursor: pointer; font-size: 12px; font-family: 'Geist', sans-serif; border: 1px solid rgba(255,255,255,0.08); background: rgba(255,255,255,0.04); color: #71717a; transition: all 0.15s; }
.conf-btn.active { border-color: rgba(245,158,11,0.4); background: rgba(245,158,11,0.12); color: #f59e0b; font-weight: 500; }

/* Toggle */
.toggle { width: 32px; height: 18px; background: rgba(255,255,255,0.1); border-radius: 99px; position: relative; cursor: pointer; transition: background 0.2s; border: none; padding: 0; flex-shrink: 0; }
.toggle.on { background: #f59e0b; }
.toggle::after { content: ''; position: absolute; width: 12px; height: 12px; background: white; border-radius: 50%; top: 3px; left: 3px; transition: transform 0.2s; }
.toggle.on::after { transform: translateX(14px); }

/* Toast */
#toast { position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); z-index: 999; display: flex; flex-direction: column; gap: 8px; align-items: center; pointer-events: none; }
.toast-item { padding: 10px 18px; border-radius: 10px; font-size: 12px; font-weight: 500; border: 1px solid; animation: fadeUp 0.2s ease; white-space: nowrap; }
.toast-success { background: rgba(34,197,94,0.1); border-color: rgba(34,197,94,0.3); color: #22c55e; }
.toast-error { background: rgba(239,68,68,0.1); border-color: rgba(239,68,68,0.3); color: #ef4444; }
.toast-info { background: rgba(56,189,248,0.1); border-color: rgba(56,189,248,0.3); color: #38bdf8; }
.toast-warning { background: rgba(245,158,11,0.1); border-color: rgba(245,158,11,0.3); color: #f59e0b; }
@keyframes fadeUp { from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)} }

/* Mono numbers */
.mono { font-family: 'Geist Mono', monospace; }
.pnl-pos { color: #22c55e; }
.pnl-neg { color: #ef4444; }

/* Skeleton */
.skel { background: rgba(255,255,255,0.05); border-radius: 4px; }
@keyframes skel { 0%,100%{opacity:0.5}50%{opacity:1} }
.skel-anim { animation: skel 1.4s ease infinite; }

/* Step dots */
.step-dot { width: 7px; height: 7px; border-radius: 50%; background: rgba(255,255,255,0.15); }
.step-dot.active { background: #38bdf8; }
.step-dot.done { background: #22c55e; }

@keyframes fadein { from{opacity:0;transform:translateY(4px)}to{opacity:1;transform:translateY(0)} }
.fadein { animation: fadein 0.2s ease; }
</style>
</head>
<body class="h-screen overflow-hidden text-zinc-200">

<div id="toast"></div>

<div class="flex h-full">

<!-- SIDEBAR OVERLAY (mobile) -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-30 opacity-0 pointer-events-none lg:hidden" onclick="closeSidebar()"></div>

<!-- SIDEBAR -->
<aside id="sidebar" class="fixed lg:relative top-0 left-0 h-full z-40 flex flex-col -translate-x-full lg:translate-x-0 lg:flex-shrink-0" style="width:220px;background:#0d0d0f;border-right:1px solid rgba(255,255,255,0.05);">
  <!-- Logo -->
  <div class="flex items-center gap-2.5 px-4 py-4" style="border-bottom:1px solid rgba(255,255,255,0.05);">
    <div style="width:26px;height:26px;background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);border-radius:7px;display:flex;align-items:center;justify-content:center;font-size:13px;">⚡</div>
    <span class="text-sm font-medium text-white">Gold Trading</span>
  </div>
  <!-- Nav -->
  <nav class="flex-1 px-2 py-3 overflow-y-auto flex flex-col gap-0.5">
    <div class="nav-section">Trading</div>
    <button class="nav-link active" id="nav-live" onclick="showView('live',this)">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
      Live Gold
    </button>
    <button class="nav-link" id="nav-sessions" onclick="showView('sessions',this)">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
      Sessions
    </button>
    <button class="nav-link" id="nav-saisons" onclick="showView('saisons',this)">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/></svg>
      Saisons
    </button>
    <div class="nav-section">Comptes</div>
    <button class="nav-link" id="nav-simulations" onclick="showView('simulations',this)">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
      Simulations
    </button>
    <button class="nav-link" id="nav-performances" onclick="showView('performances',this)">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Performances
    </button>
    <div class="nav-section">Config</div>
    <button class="nav-link" id="nav-regles" onclick="showView('regles',this)">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
      Règles & Messages
    </button>
  </nav>
  <!-- Close sidebar (mobile) -->
  <button class="lg:hidden m-3 btn-ghost justify-center" onclick="closeSidebar()">
    <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
    Fermer
  </button>
</aside>

<!-- MAIN -->
<div class="flex-1 flex flex-col min-w-0 overflow-hidden">

<!-- ═══════════════ LIVE GOLD ═══════════════ -->
<div id="view-live" class="flex flex-col h-full">
  <!-- Topbar -->
  <header class="flex-shrink-0 flex items-center justify-between px-4 sm:px-6 gap-3" style="height:56px;background:rgba(9,9,11,0.9);backdrop-filter:blur(12px);border-bottom:1px solid rgba(255,255,255,0.05);">
    <div class="flex items-center gap-3">
      <button class="btn-icon lg:hidden" onclick="openSidebar()">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <div class="flex items-center gap-2">
        <span class="live-dot live-dot-gold pulse"></span>
        <span class="text-sm font-medium text-white hidden sm:block">Live Gold</span>
        <span class="ticker ticker-neutral" id="live-price-header">—</span>
      </div>
    </div>
    <button class="btn-gold text-xs sm:text-sm" onclick="openModal('modal-gold-publish')">
      <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      <span class="hidden sm:inline">Nouveau trade</span>
      <span class="sm:hidden">+</span>
    </button>
  </header>
  <!-- Content -->
  <main class="flex-1 overflow-y-auto p-4 sm:p-5 flex flex-col gap-4" id="live-main">
    <div class="text-center py-16 text-sm" style="color:#3f3f46;">Chargement...</div>
  </main>
</div>

<!-- ═══════════════ SESSIONS ═══════════════ -->
<div id="view-sessions" class="flex flex-col h-full" style="display:none!important;">
  <header class="flex-shrink-0 flex items-center justify-between px-4 sm:px-6 gap-3" style="height:56px;background:rgba(9,9,11,0.9);backdrop-filter:blur(12px);border-bottom:1px solid rgba(255,255,255,0.05);">
    <div class="flex items-center gap-3">
      <button class="btn-icon lg:hidden" onclick="openSidebar()">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <h1 class="text-sm font-medium text-white">Sessions</h1>
    </div>
    <div class="flex items-center gap-2">
      <select class="input" id="sess-phase-filter" style="width:130px;font-size:11px;padding:6px 9px;" onchange="loadSessions()">
        <option value="">Toutes phases</option>
        <option value="open">Ouvert</option>
        <option value="tp1_reached">TP1</option>
        <option value="tp2_reached">TP2</option>
        <option value="tp3_reached">TP3</option>
        <option value="sl_touched">SL</option>
        <option value="closed">Clôturé</option>
      </select>
    </div>
  </header>
  <main class="flex-1 overflow-y-auto p-4 sm:p-5">
    <div class="card overflow-hidden">
      <div id="sessions-body"><div class="p-8 text-center text-sm" style="color:#3f3f46;">Chargement...</div></div>
    </div>
  </main>
</div>

<!-- ═══════════════ SAISONS ═══════════════ -->
<div id="view-saisons" class="flex flex-col h-full" style="display:none!important;">
  <header class="flex-shrink-0 flex items-center justify-between px-4 sm:px-6 gap-3" style="height:56px;background:rgba(9,9,11,0.9);backdrop-filter:blur(12px);border-bottom:1px solid rgba(255,255,255,0.05);">
    <div class="flex items-center gap-3">
      <button class="btn-icon lg:hidden" onclick="openSidebar()">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <h1 class="text-sm font-medium text-white">Saisons</h1>
    </div>
    <button class="btn-gold text-xs" onclick="openModal('modal-new-season')">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      Nouvelle
    </button>
  </header>
  <main class="flex-1 overflow-y-auto p-4 sm:p-5" id="saisons-main">
    <div class="text-center py-16 text-sm" style="color:#3f3f46;">Chargement...</div>
  </main>
</div>

<!-- ═══════════════ SIMULATIONS ═══════════════ -->
<div id="view-simulations" class="flex flex-col h-full" style="display:none!important;">
  <header class="flex-shrink-0 flex items-center justify-between px-4 sm:px-6 gap-3" style="height:56px;background:rgba(9,9,11,0.9);backdrop-filter:blur(12px);border-bottom:1px solid rgba(255,255,255,0.05);">
    <div class="flex items-center gap-3">
      <button class="btn-icon lg:hidden" onclick="openSidebar()">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <h1 class="text-sm font-medium text-white">Simulations</h1>
    </div>
    <button class="btn-gold text-xs" onclick="openModal('modal-new-sim')">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      Nouveau compte
    </button>
  </header>
  <main class="flex-1 overflow-y-auto p-4 sm:p-5 flex flex-col gap-4" id="simulations-main">
    <div class="text-center py-16 text-sm" style="color:#3f3f46;">Chargement...</div>
  </main>
</div>

<!-- ═══════════════ PERFORMANCES ═══════════════ -->
<div id="view-performances" class="flex flex-col h-full" style="display:none!important;">
  <header class="flex-shrink-0 flex items-center justify-between px-4 sm:px-6 gap-3" style="height:56px;background:rgba(9,9,11,0.9);backdrop-filter:blur(12px);border-bottom:1px solid rgba(255,255,255,0.05);">
    <div class="flex items-center gap-3">
      <button class="btn-icon lg:hidden" onclick="openSidebar()">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <h1 class="text-sm font-medium text-white">Performances</h1>
    </div>
    <div class="flex items-center gap-2">
      <button class="btn-ghost text-xs active-period" onclick="setPerfPeriod('season',this)">Saison</button>
      <button class="btn-ghost text-xs" onclick="setPerfPeriod('month',this)">Mois</button>
      <button class="btn-ghost text-xs" onclick="setPerfPeriod('all',this)">Tout</button>
    </div>
  </header>
  <main class="flex-1 overflow-y-auto p-4 sm:p-5 flex flex-col gap-4" id="perf-main">
    <div class="text-center py-16 text-sm" style="color:#3f3f46;">Chargement...</div>
  </main>
</div>

<!-- ═══════════════ RÈGLES ═══════════════ -->
<div id="view-regles" class="flex flex-col h-full" style="display:none!important;">
  <header class="flex-shrink-0 flex items-center justify-between px-4 sm:px-6 gap-3" style="height:56px;background:rgba(9,9,11,0.9);backdrop-filter:blur(12px);border-bottom:1px solid rgba(255,255,255,0.05);">
    <div class="flex items-center gap-3">
      <button class="btn-icon lg:hidden" onclick="openSidebar()">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <h1 class="text-sm font-medium text-white">Règles & Messages</h1>
    </div>
    <button class="btn-gold text-xs" onclick="openModal('modal-new-rule')">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      Nouvelle règle
    </button>
  </header>
  <main class="flex-1 overflow-y-auto p-4 sm:p-5 flex flex-col gap-4" id="regles-main">
    <div class="text-center py-16 text-sm" style="color:#3f3f46;">Chargement...</div>
  </main>
</div>

</div><!-- end main -->
</div><!-- end flex -->

<!-- ═══════════════════════════════════════════
     MODALS
═══════════════════════════════════════════ -->

<!-- MODAL NOUVEAU TRADE GOLD -->
<div class="modal-overlay" id="modal-gold-publish">
  <div class="modal" style="max-width:640px;">
    <div class="flex items-center justify-between px-5 py-4 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.06);">
      <div>
        <div class="flex items-center gap-2">
          <span style="font-size:15px;">⚡</span>
          <p class="text-sm font-medium text-white">Nouveau trade Gold</p>
        </div>
        <p class="text-xs mt-0.5" style="color:#52525b;">XAU/USD · Flux automatique Telegram</p>
      </div>
      <button class="btn-icon" onclick="closeModal('modal-gold-publish')">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="overflow-y-auto px-5 py-5 flex flex-col gap-4" style="max-height:72vh;">
      <!-- Direction -->
      <div>
        <p class="text-xs mb-2" style="color:#52525b;">Direction</p>
        <div class="flex gap-2">
          <button id="gold-dir-buy" onclick="setGoldDir('buy')" class="flex-1 py-3 rounded-lg text-sm font-medium cursor-pointer transition-all" style="border:1px solid rgba(34,197,94,0.35);background:rgba(34,197,94,0.1);color:#22c55e;font-family:'Geist',sans-serif;">
            ↑ Achat (Buy)
          </button>
          <button id="gold-dir-sell" onclick="setGoldDir('sell')" class="flex-1 py-3 rounded-lg text-sm cursor-pointer transition-all" style="border:1px solid rgba(255,255,255,0.08);background:rgba(255,255,255,0.04);color:#71717a;font-family:'Geist',sans-serif;">
            ↓ Vente (Sell)
          </button>
        </div>
      </div>
      <!-- Niveaux -->
      <div class="grid grid-cols-2 gap-3">
        <div>
          <p class="text-xs mb-1.5" style="color:#52525b;">Prix d'entrée *</p>
          <input class="input mono" type="number" step="0.01" id="gold-entry" placeholder="ex: 2650.00" oninput="updateGoldCalcs()">
        </div>
        <div>
          <p class="text-xs mb-1.5" style="color:#ef4444;">Stop Loss *</p>
          <input class="input mono" type="number" step="0.01" id="gold-sl" placeholder="ex: 2644.00" style="border-color:rgba(239,68,68,0.25);" oninput="updateGoldCalcs()">
        </div>
      </div>
      <div class="grid grid-cols-3 gap-3">
        <div>
          <p class="text-xs mb-1.5" style="color:#22c55e;">TP1 *</p>
          <input class="input mono" type="number" step="0.01" id="gold-tp1" placeholder="2658.00" style="border-color:rgba(34,197,94,0.25);" oninput="updateGoldCalcs()">
        </div>
        <div>
          <p class="text-xs mb-1.5" style="color:#22c55e;opacity:.7;">TP2</p>
          <input class="input mono" type="number" step="0.01" id="gold-tp2" placeholder="2665.00">
        </div>
        <div>
          <p class="text-xs mb-1.5" style="color:#a78bfa;">TP3</p>
          <input class="input mono" type="number" step="0.01" id="gold-tp3" placeholder="2675.00">
        </div>
      </div>
      <!-- Confiance + Timeframe -->
      <div class="grid grid-cols-2 gap-3">
        <div>
          <p class="text-xs mb-2" style="color:#52525b;">Confiance</p>
          <div class="flex gap-1.5" id="conf-stars">
            <button onclick="setConfidence(1)" class="conf-btn" data-v="1">⭐</button>
            <button onclick="setConfidence(2)" class="conf-btn" data-v="2">⭐⭐</button>
            <button onclick="setConfidence(3)" class="conf-btn active" data-v="3">⭐⭐⭐</button>
            <button onclick="setConfidence(4)" class="conf-btn" data-v="4">4⭐</button>
            <button onclick="setConfidence(5)" class="conf-btn" data-v="5">5⭐</button>
          </div>
        </div>
        <div>
          <p class="text-xs mb-1.5" style="color:#52525b;">Timeframe</p>
          <select class="input" id="gold-tf">
            <option>M1</option><option>M5</option><option selected>M15</option>
            <option>M30</option><option>H1</option><option>H4</option>
          </select>
        </div>
      </div>
      <!-- Note -->
      <div>
        <p class="text-xs mb-1.5" style="color:#52525b;">Note / Analyse</p>
        <textarea class="input" id="gold-note" placeholder="Setup, contexte, invalidation..."></textarea>
      </div>
      <!-- Screenshot -->
      <div>
        <p class="text-xs mb-1.5" style="color:#52525b;">Screenshot (optionnel)</p>
        <div class="upload-zone" id="gold-upload-zone" onclick="triggerGoldUpload()">
          <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="margin:0 auto 5px;color:#52525b;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          <p class="text-xs" style="color:#71717a;">Glisser ou <span style="color:#f59e0b;cursor:pointer;">parcourir</span></p>
        </div>
      </div>
      <!-- Calculs temps réel — CORRECTION v3 : formule brute abs(entry-sl) -->
      <div style="background:rgba(245,158,11,0.04);border:1px solid rgba(245,158,11,0.15);border-radius:10px;padding:14px;">
        <div class="flex items-center justify-between mb-3">
          <p class="text-xs font-medium" style="color:#f59e0b;">Calculs par niveau de compte</p>
          <div class="flex gap-3 text-xs" style="color:#52525b;">
            <span>SL: <span class="mono pnl-neg" id="gold-sl-pips">—</span></span>
            <span>TP1: <span class="mono pnl-pos" id="gold-tp1-pips">—</span></span>
            <span>R:R: <span class="mono" style="color:#38bdf8;" id="gold-rr">—</span></span>
          </div>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <div style="background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.15);border-radius:8px;padding:10px;text-align:center;">
            <p class="text-xs mb-1" style="color:#f59e0b;">Fixe — &lt;250$</p>
            <p class="text-xs mono" id="gold-calc-tp1" style="color:#52525b;">—</p>
          </div>
          <div style="background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.15);border-radius:8px;padding:10px;text-align:center;">
            <p class="text-xs mb-1" style="color:#f59e0b;">Calculé — 500$</p>
            <p class="text-xs mono" id="gold-calc-tp2" style="color:#52525b;">—</p>
          </div>
          <div style="background:rgba(245,158,11,0.06);border:1px solid rgba(245,158,11,0.15);border-radius:8px;padding:10px;text-align:center;">
            <p class="text-xs mb-1" style="color:#a78bfa;">Calculé — 2000$</p>
            <p class="text-xs mono" id="gold-calc-tp3" style="color:#52525b;">—</p>
          </div>
        </div>
      </div>
      <!-- Catégorie -->
      <div>
        <p class="text-xs mb-1.5" style="color:#52525b;">Catégorie destinataires</p>
        <select class="input" id="gold-category"><option value="clients_actifs">clients_actifs</option></select>
      </div>
    </div>
    <div class="flex items-center justify-end gap-2 px-5 py-4 flex-shrink-0" style="border-top:1px solid rgba(255,255,255,0.06);">
      <button class="btn-ghost" onclick="closeModal('modal-gold-publish')">Annuler</button>
      <button class="btn-gold" id="btn-publish" onclick="publishGoldSession()">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
        Publier →
      </button>
    </div>
  </div>
</div>

<!-- MODAL CLÔTURER SESSION GOLD -->
<div class="modal-overlay" id="modal-gold-close">
  <div class="modal" style="max-width:460px;">
    <div class="flex items-center justify-between px-5 py-4 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.06);">
      <div>
        <p class="text-sm font-medium text-white">Clôturer la session Gold</p>
        <p class="text-xs mt-0.5" style="color:#52525b;" id="gold-close-subtitle">Session #—</p>
      </div>
      <button class="btn-icon" onclick="closeModal('modal-gold-close')">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="px-5 py-5 flex flex-col gap-4">
      <div>
        <p class="text-xs mb-3" style="color:#52525b;">Type de clôture</p>
        <div class="grid grid-cols-2 gap-2">
          <button onclick="setGoldCloseType('tp1')" class="gold-close-btn" data-t="tp1" style="border-color:rgba(34,197,94,0.3);background:rgba(34,197,94,0.07);color:#22c55e;">✅ TP1 atteint</button>
          <button onclick="setGoldCloseType('tp2')" class="gold-close-btn" data-t="tp2" style="border-color:rgba(56,189,248,0.25);background:rgba(56,189,248,0.06);color:#38bdf8;">🎯 TP2 atteint</button>
          <button onclick="setGoldCloseType('tp3')" class="gold-close-btn" data-t="tp3" style="border-color:rgba(167,139,250,0.25);background:rgba(167,139,250,0.06);color:#a78bfa;">🏆 TP3 atteint</button>
          <button onclick="setGoldCloseType('sl')" class="gold-close-btn" data-t="sl" style="border-color:rgba(239,68,68,0.25);background:rgba(239,68,68,0.06);color:#ef4444;">❌ SL touché</button>
        </div>
      </div>
    </div>
    <div class="flex items-center justify-end gap-2 px-5 py-4 flex-shrink-0" style="border-top:1px solid rgba(255,255,255,0.06);">
      <button class="btn-ghost" onclick="closeModal('modal-gold-close')">Annuler</button>
      <button class="btn-gold" id="btn-gold-close-confirm" onclick="confirmGoldClose()">Clôturer & notifier →</button>
    </div>
  </div>
</div>

<!-- MODAL NOUVELLE SAISON -->
<div class="modal-overlay" id="modal-new-season">
  <div class="modal" style="max-width:440px;">
    <div class="flex items-center justify-between px-5 py-4 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.06);">
      <p class="text-sm font-medium text-white">Nouvelle saison Gold</p>
      <button class="btn-icon" onclick="closeModal('modal-new-season')">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="px-5 py-5 flex flex-col gap-3">
      <div><p class="text-xs mb-1.5" style="color:#52525b;">Nom *</p><input class="input" id="new-season-name" placeholder="ex: Saison 2 — Juin 2026"></div>
      <div><p class="text-xs mb-1.5" style="color:#52525b;">Description</p><input class="input" id="new-season-desc" placeholder="Objectif, contexte..."></div>
      <div><p class="text-xs mb-1.5" style="color:#52525b;">Capital de référence ($)</p><input class="input mono" id="new-season-capital" type="number" placeholder="1000"></div>
      <div style="background:rgba(245,158,11,0.05);border:1px solid rgba(245,158,11,0.15);border-radius:8px;padding:10px 12px;">
        <p class="text-xs" style="color:#f59e0b;">⚠️ La saison active sera clôturée automatiquement.</p>
      </div>
    </div>
    <div class="flex items-center justify-end gap-2 px-5 py-4 flex-shrink-0" style="border-top:1px solid rgba(255,255,255,0.06);">
      <button class="btn-ghost" onclick="closeModal('modal-new-season')">Annuler</button>
      <button class="btn-gold" onclick="createSeason()">Créer →</button>
    </div>
  </div>
</div>

<!-- MODAL RESET SAISON -->
<div class="modal-overlay" id="modal-reset-season">
  <div class="modal" style="max-width:440px;">
    <div class="flex items-center justify-between px-5 py-4 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.06);">
      <p class="text-sm font-medium text-white">Réinitialiser la saison</p>
      <button class="btn-icon" onclick="closeModal('modal-reset-season')">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="px-5 py-5 flex flex-col gap-3">
      <div style="background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:12px;">
        <p class="text-xs" style="color:#ef4444;">⚠️ Archive la saison actuelle et remet les comptes simulation à zéro. Les données sont conservées.</p>
      </div>
      <div><p class="text-xs mb-1.5" style="color:#52525b;">Nom de la nouvelle saison *</p><input class="input" id="reset-season-name" placeholder="ex: Saison 3 — Juillet 2026"></div>
      <div><p class="text-xs mb-1.5" style="color:#52525b;">Nouveau capital de référence</p><input class="input mono" id="reset-season-capital" type="number" placeholder="1000"></div>
    </div>
    <div class="flex items-center justify-end gap-2 px-5 py-4 flex-shrink-0" style="border-top:1px solid rgba(255,255,255,0.06);">
      <button class="btn-ghost" onclick="closeModal('modal-reset-season')">Annuler</button>
      <button class="btn-danger" onclick="confirmResetSeason()">Réinitialiser →</button>
    </div>
  </div>
</div>

<!-- MODAL NOUVEAU COMPTE SIM -->
<div class="modal-overlay" id="modal-new-sim">
  <div class="modal" style="max-width:420px;">
    <div class="flex items-center justify-between px-5 py-4 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.06);">
      <p class="text-sm font-medium text-white">Nouveau compte simulation</p>
      <button class="btn-icon" onclick="closeModal('modal-new-sim')">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="px-5 py-5 flex flex-col gap-3">
      <div><p class="text-xs mb-1.5" style="color:#52525b;">Nom *</p><input class="input" id="new-sim-name" placeholder="ex: Compte 500$"></div>
      <div><p class="text-xs mb-1.5" style="color:#52525b;">Capital initial ($) *</p><input class="input mono" id="new-sim-capital" type="number" placeholder="500"></div>
      <div><p class="text-xs mb-1.5" style="color:#52525b;">Description</p><input class="input" id="new-sim-desc" placeholder="ex: Profil débutant"></div>
      <div><p class="text-xs mb-1.5" style="color:#52525b;">Risque par défaut %</p><input class="input mono" id="new-sim-risk" type="number" value="1"></div>
    </div>
    <div class="flex items-center justify-end gap-2 px-5 py-4 flex-shrink-0" style="border-top:1px solid rgba(255,255,255,0.06);">
      <button class="btn-ghost" onclick="closeModal('modal-new-sim')">Annuler</button>
      <button class="btn-gold" onclick="createSimAccount()">Créer →</button>
    </div>
  </div>
</div>

<!-- MODAL SUPPRIMER SIM -->
<div class="modal-overlay" id="modal-delete-sim">
  <div class="modal" style="max-width:400px;">
    <div class="flex items-center justify-between px-5 py-4 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.06);">
      <p class="text-sm font-medium text-white">Supprimer le compte</p>
      <button class="btn-icon" onclick="closeModal('modal-delete-sim')"><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="px-5 py-5">
      <div style="background:rgba(239,68,68,0.06);border:1px solid rgba(239,68,68,0.2);border-radius:8px;padding:14px;">
        <p class="text-sm font-medium pnl-neg mb-2">Suppression définitive</p>
        <p class="text-xs" style="color:#a1a1aa;">Le compte <strong id="delete-sim-name" class="text-white"></strong> et tous ses trades seront supprimés définitivement. Cette action est irréversible.</p>
      </div>
    </div>
    <div class="flex items-center justify-end gap-2 px-5 py-4 flex-shrink-0" style="border-top:1px solid rgba(255,255,255,0.06);">
      <button class="btn-ghost" onclick="closeModal('modal-delete-sim')">Annuler</button>
      <button class="btn-danger" id="btn-delete-sim-confirm" onclick="confirmDeleteSim()">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="m19 6-1 14H6L5 6"/></svg>Supprimer définitivement
      </button>
    </div>
  </div>
</div>

<!-- MODAL NOUVELLE RÈGLE TP -->
<div class="modal-overlay" id="modal-new-rule">
  <div class="modal" style="max-width:620px;">
    <div class="flex items-center justify-between px-5 py-4 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.06);">
      <p class="text-sm font-medium text-white">Règle TP — Messages configurables</p>
      <button class="btn-icon" onclick="closeModal('modal-new-rule')">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="overflow-y-auto px-5 py-5 flex flex-col gap-4" style="max-height:70vh;">
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
        <div><p class="text-xs mb-1.5" style="color:#52525b;">Nom *</p><input class="input" id="rule-name" placeholder="ex: Petit compte TP1"></div>
        <div><p class="text-xs mb-1.5" style="color:#52525b;">Niveau TP *</p>
          <select class="input" id="rule-tp-level"><option value="1">TP1 seulement</option><option value="2">TP1 + TP2</option><option value="3">TP1 + TP2 + TP3</option></select>
        </div>
        <div><p class="text-xs mb-1.5" style="color:#52525b;">Risque %</p><input class="input mono" id="rule-risk" type="number" value="1"></div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div><p class="text-xs mb-1.5" style="color:#52525b;">Capital min ($)</p><input class="input mono" id="rule-cap-min" type="number" value="0"></div>
        <div><p class="text-xs mb-1.5" style="color:#52525b;">Capital max ($) — vide = illimité</p><input class="input mono" id="rule-cap-max" type="number" placeholder="499.99"></div>
      </div>
      <div style="background:rgba(255,255,255,0.02);border:1px solid rgba(255,255,255,0.05);border-radius:8px;padding:12px;">
        <p class="text-xs font-medium text-zinc-300 mb-3">Messages Telegram (format Markdown)</p>
        <div class="flex flex-col gap-3">
          <div><label class="text-xs block mb-1.5" style="color:#52525b;text-transform:uppercase;letter-spacing:.05em;">Teaser</label><textarea class="msg-editor" id="rule-msg-teaser" placeholder="🔔 *Le trade du jour est disponible !*"></textarea></div>
          <div><label class="text-xs block mb-1.5" style="color:#22c55e;text-transform:uppercase;letter-spacing:.05em;">TP1 atteint</label><textarea class="msg-editor" id="rule-msg-tp1" placeholder="✅ *TP1 atteint sur XAU/USD !*"></textarea></div>
          <div><label class="text-xs block mb-1.5" style="color:#38bdf8;text-transform:uppercase;letter-spacing:.05em;">TP2 atteint</label><textarea class="msg-editor" id="rule-msg-tp2" placeholder="🎯 *TP2 atteint !*"></textarea></div>
          <div><label class="text-xs block mb-1.5" style="color:#a78bfa;text-transform:uppercase;letter-spacing:.05em;">TP3 atteint</label><textarea class="msg-editor" id="rule-msg-tp3" placeholder="🏆 *TP3 atteint !*"></textarea></div>
          <div><label class="text-xs block mb-1.5" style="color:#ef4444;text-transform:uppercase;letter-spacing:.05em;">SL touché</label><textarea class="msg-editor" id="rule-msg-sl" placeholder="❌ *SL touché sur XAU/USD*"></textarea></div>
          <div><label class="text-xs block mb-1.5" style="color:#52525b;text-transform:uppercase;letter-spacing:.05em;">Break even</label><textarea class="msg-editor" id="rule-msg-be" placeholder="🔒 Passez en break even..."></textarea></div>
          <div><label class="text-xs block mb-1.5" style="color:#52525b;text-transform:uppercase;letter-spacing:.05em;">Confirmation membre</label><textarea class="msg-editor" id="rule-msg-confirm" placeholder="✅ Trade enregistré !"></textarea></div>
        </div>
      </div>
    </div>
    <div class="flex items-center justify-end gap-2 px-5 py-4 flex-shrink-0" style="border-top:1px solid rgba(255,255,255,0.06);">
      <button class="btn-ghost" onclick="closeModal('modal-new-rule')">Annuler</button>
      <button class="btn-gold" onclick="saveRule()">Sauvegarder →</button>
    </div>
  </div>
</div>

<!-- DRAWER OVERLAY -->
<div class="drawer-overlay" id="drawer-overlay" onclick="closeDrawer()"></div>

<!-- DRAWER SESSION GOLD -->
<div class="drawer" id="session-drawer">
  <div class="flex items-center justify-between px-5 py-4 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.06);">
    <div id="session-drawer-header">
      <p class="text-sm font-medium text-white">Session Gold</p>
    </div>
    <button class="btn-icon" onclick="closeDrawer()">
      <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
  </div>
  <div class="flex-1 overflow-y-auto px-5 py-4 flex flex-col gap-4" id="session-drawer-content">
    <div class="text-center py-16 text-sm" style="color:#3f3f46;">Chargement...</div>
  </div>
  <div class="flex items-center gap-2 px-5 py-4 flex-shrink-0 flex-wrap" style="border-top:1px solid rgba(255,255,255,0.06);" id="session-drawer-actions"></div>
</div>

<!-- DRAWER MEMBRE PERFORMANCE -->
<div class="drawer" id="member-drawer">
  <div class="flex items-center justify-between px-5 py-4 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.06);">
    <div class="flex items-center gap-3" id="member-drawer-header">
      <div style="width:36px;height:36px;border-radius:50%;background:rgba(245,158,11,0.15);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:500;color:#f59e0b;" id="member-av">—</div>
      <div>
        <p class="text-sm font-medium text-white" id="member-name">—</p>
        <p class="text-xs" style="color:#52525b;" id="member-sub">—</p>
      </div>
    </div>
    <button class="btn-icon" onclick="closeDrawer()">
      <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
  </div>
  <div class="flex-1 overflow-y-auto px-5 py-4 flex flex-col gap-4" id="member-drawer-content">
    <div class="text-center py-16 text-sm" style="color:#3f3f46;">Chargement...</div>
  </div>
</div>

<script>
const API      = 'https://fdkvip.com/trading'
const GOLD_API = 'https://fdkvip.com/gold'
const API_URL  = 'https://fdkvip.com'

let goldDir        = 'buy'
let goldConfidence = 3
let goldCloseType  = 'tp1'
let currentGoldSessionId = null
let currentMemberId      = null
let perfPeriod     = 'season'
let resetSeasonId  = null
let deleteSimId    = null
let goldUploadUrl  = null
let priceInterval  = null

// ─── API ─────────────────────────────────────────────────────
async function api(path, opts = {}, base = GOLD_API) {
  const res = await fetch(base + path, { headers: {'Content-Type':'application/json'}, ...opts })
  if (!res.ok) { const e = await res.json().catch(()=>({})); throw new Error(e.detail || `HTTP ${res.status}`) }
  return res.json()
}
const gold = (path, opts) => api(path, opts, GOLD_API)

// ─── TOAST ───────────────────────────────────────────────────
function toast(msg, type='info') {
  const el = document.createElement('div')
  el.className = `toast-item toast-${type}`
  el.textContent = msg
  document.getElementById('toast').appendChild(el)
  setTimeout(() => el.remove(), 3500)
}

// ─── SIDEBAR ─────────────────────────────────────────────────
function openSidebar() {
  document.getElementById('sidebar').style.transform = 'translateX(0)'
  const ov = document.getElementById('sidebar-overlay')
  ov.style.opacity = '1'; ov.style.pointerEvents = 'auto'
}
function closeSidebar() {
  document.getElementById('sidebar').style.transform = ''
  const ov = document.getElementById('sidebar-overlay')
  ov.style.opacity = '0'; ov.style.pointerEvents = 'none'
}

// ─── NAV ─────────────────────────────────────────────────────
const VIEWS = ['live','sessions','saisons','simulations','performances','regles']
function showView(view, btn) {
  VIEWS.forEach(v => {
    const el = document.getElementById('view-'+v)
    if (el) el.style.cssText = v === view ? 'display:flex;flex-direction:column;height:100%;' : 'display:none!important;'
  })
  document.querySelectorAll('.nav-link').forEach(n => n.classList.remove('active'))
  if (btn) btn.classList.add('active')
  closeSidebar()
  if (view === 'live')         loadLiveDashboard()
  if (view === 'sessions')     loadSessions()
  if (view === 'saisons')      loadSaisons()
  if (view === 'simulations')  loadSimulations()
  if (view === 'performances') loadPerformances()
  if (view === 'regles')       loadRegles()
}

// ─── MODALS ──────────────────────────────────────────────────
function openModal(id)  { document.getElementById(id)?.classList.add('open') }
function closeModal(id) {
  document.getElementById(id)?.classList.remove('open')
  if (id === 'modal-gold-publish') { goldUploadUrl = null; resetGoldUpload() }
}
document.querySelectorAll('.modal-overlay').forEach(o =>
  o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open') })
)

// ─── DRAWERS ─────────────────────────────────────────────────
function openDrawer(id) {
  document.getElementById(id)?.classList.add('open')
  document.getElementById('drawer-overlay')?.classList.add('open')
}
function closeDrawer() {
  document.querySelectorAll('.drawer').forEach(d => d.classList.remove('open'))
  document.getElementById('drawer-overlay')?.classList.remove('open')
}

document.addEventListener('keydown', e => {
  if (e.key !== 'Escape') return
  closeDrawer()
  document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('open'))
})

// ─── DIRECTION ───────────────────────────────────────────────
function setGoldDir(d) {
  goldDir = d
  const buy  = document.getElementById('gold-dir-buy')
  const sell = document.getElementById('gold-dir-sell')
  buy.style.cssText  = `flex:1;padding:12px;border-radius:8px;cursor:pointer;font-size:14px;font-weight:500;font-family:'Geist',sans-serif;transition:all .15s;border:1px solid ${d==='buy'?'rgba(34,197,94,0.35)':'rgba(255,255,255,0.08)'};background:${d==='buy'?'rgba(34,197,94,0.1)':'rgba(255,255,255,0.04)'};color:${d==='buy'?'#22c55e':'#71717a'};`
  sell.style.cssText = `flex:1;padding:12px;border-radius:8px;cursor:pointer;font-size:14px;font-weight:500;font-family:'Geist',sans-serif;transition:all .15s;border:1px solid ${d==='sell'?'rgba(239,68,68,0.35)':'rgba(255,255,255,0.08)'};background:${d==='sell'?'rgba(239,68,68,0.1)':'rgba(255,255,255,0.04)'};color:${d==='sell'?'#ef4444':'#71717a'};`
  updateGoldCalcs()
}

// ─── CONFIANCE ───────────────────────────────────────────────
function setConfidence(v) {
  goldConfidence = v
  document.querySelectorAll('.conf-btn').forEach(btn => {
    btn.classList.toggle('active', parseInt(btn.dataset.v) === v)
  })
  updateGoldCalcs()
}

// ─── CALCULS GOLD — CORRECTION v3 ────────────────────────────
// sl_pips = abs(entry - sl) — différence brute, pas de multiplicateur
// lot = (capital/diviseur * 0.01) / sl_pips
// perte = (lot / 0.01) * sl_pips
// 1 pt de mouvement = 1$ pour 0.01 lot

function getDiv(capital) {
  if (capital < 1500) return 12
  return 12 + Math.floor((capital - 1001) / 500)
}

function calcLot(capital, slPips) {
  if (capital < 250) return 0.01
  if (capital < 500) return 0.015
  if (slPips <= 0)   return 0.01
  const loss = capital / getDiv(capital)
  const lot  = (loss * 0.01) / slPips
  return Math.max(0.01, Math.floor(lot * 100) / 100)
}

function calcGain(lot, pips) {
  return Math.round((lot / 0.01) * pips * 100) / 100
}

function updateGoldCalcs() {
  const entry = parseFloat(document.getElementById('gold-entry')?.value) || 0
  const sl    = parseFloat(document.getElementById('gold-sl')?.value)    || 0
  const tp1   = parseFloat(document.getElementById('gold-tp1')?.value)   || 0
  if (!entry || !sl) return

  // CORRECTION : différence brute abs(entry-sl)
  const slPips  = Math.round(Math.abs(entry - sl) * 100) / 100
  const tp1Pips = tp1 ? Math.round(Math.abs(tp1 - entry) * 100) / 100 : 0

  document.getElementById('gold-sl-pips').textContent  = slPips + ' pts'
  document.getElementById('gold-tp1-pips').textContent = tp1Pips ? tp1Pips + ' pts' : '—'
  document.getElementById('gold-rr').textContent       = slPips > 0 && tp1Pips > 0 ? '1:' + (tp1Pips/slPips).toFixed(1) : '—'

  // Col 1 : < 250$ → 0.01 fixe
  const lot1  = 0.01
  const loss1 = Math.round((lot1 / 0.01) * slPips * 100) / 100
  const gain1 = tp1Pips ? calcGain(lot1, tp1Pips) : 0
  document.getElementById('gold-calc-tp1').innerHTML =
    `<span style="color:#f59e0b;">Lot: ${lot1}</span><br><span style="color:#ef4444;">-${loss1}$</span>${gain1?`<br><span style="color:#22c55e;">+${gain1}$</span>`:''}`

  // Col 2 : 500$, div=12
  const lot2  = calcLot(500, slPips)
  const loss2 = Math.round((lot2 / 0.01) * slPips * 100) / 100
  const gain2 = tp1Pips ? calcGain(lot2, tp1Pips) : 0
  document.getElementById('gold-calc-tp2').innerHTML =
    `<span style="color:#f59e0b;">Lot: ${lot2}</span><br><span style="color:#ef4444;">-${loss2}$</span>${gain2?`<br><span style="color:#22c55e;">+${gain2}$</span>`:''}`

  // Col 3 : 2000$, div=13
  const lot3  = calcLot(2000, slPips)
  const loss3 = Math.round((lot3 / 0.01) * slPips * 100) / 100
  const gain3 = tp1Pips ? calcGain(lot3, tp1Pips) : 0
  document.getElementById('gold-calc-tp3').innerHTML =
    `<span style="color:#f59e0b;">Lot: ${lot3}</span><br><span style="color:#ef4444;">-${loss3}$</span>${gain3?`<br><span style="color:#22c55e;">+${gain3}$</span>`:''}`
}

// ─── LIVE DASHBOARD ──────────────────────────────────────────
async function loadLiveDashboard() {
  const main = document.getElementById('live-main')
  try {
    const d       = await gold('/dashboard')
    const session = d.active_session
    const season  = d.active_season
    const price   = d.live_price
    const sims    = d.simulation_accounts || []
    const recent  = d.recent_sessions || []
    const stats   = d.season_stats

    if (price) updatePriceTickers(price)
    startPriceTicker()

    const ss      = stats?.session_stats || {}
    const winRate = ss.total_trades > 0 ? Math.round((ss.wins||0)/ss.total_trades*100) : 0

    main.innerHTML = `
      <div class="flex flex-col gap-4 fadein">
        ${renderActiveSession(session)}
        <div class="grid grid-cols-2 gap-3">
          ${renderSeasonCard(season, ss, winRate)}
          ${renderPriceCard(price)}
        </div>
        ${sims.length ? renderSimCards(sims) : ''}
        ${recent.length ? renderRecentSessions(recent) : ''}
      </div>`
  } catch(e) {
    main.innerHTML = `<div class="card p-6 text-center"><p class="text-sm" style="color:#ef4444;">Erreur: ${e.message}</p></div>`
  }
}

function updatePriceTickers(price) {
  const p = parseFloat(price).toFixed(2)
  ;['live-price-header','live-price-main'].forEach(id => {
    const el = document.getElementById(id)
    if (el) { el.textContent = p; el.className = 'ticker ticker-up' }
  })
}

function startPriceTicker() {
  if (priceInterval) clearInterval(priceInterval)
  priceInterval = setInterval(async () => {
    try { const d = await gold('/price/live'); updatePriceTickers(d.price) } catch(e) {}
  }, 30000)
}

function renderActiveSession(session) {
  if (!session) return `
    <div class="card p-6 text-center">
      <p class="text-sm font-medium text-zinc-300 mb-2">Aucune session active</p>
      <p class="text-xs mb-4" style="color:#52525b;">Créez un nouveau trade Gold pour démarrer</p>
      <button class="btn-gold mx-auto" onclick="openModal('modal-gold-publish')">+ Nouveau trade Gold</button>
    </div>`

  const isActive = ['teaser','open','tp1_reached','tp2_reached'].includes(session.current_phase)
  return `
    <div class="card-gold p-4 sm:p-5">
      <div class="flex items-center justify-between mb-4 flex-wrap gap-2">
        <div class="flex items-center gap-2 flex-wrap">
          <span class="live-dot live-dot-gold pulse"></span>
          <span class="text-sm font-medium text-white">Session active</span>
          ${phaseBadge(session.current_phase)}
        </div>
        <div class="flex items-center gap-2">
          <button class="btn-ghost text-xs" onclick="openSessionDrawer(${session.id})">Détail</button>
          ${isActive ? `<button class="btn-gold text-xs" onclick="openGoldCloseModal(${session.id})">Clôturer</button>` : ''}
        </div>
      </div>
      <div class="flex items-center flex-wrap gap-2 mb-4">
        ${dirBadge(session.direction)}
        <span class="mono text-sm text-white">Entrée: ${session.entry_price}</span>
        ${session.tp1 ? `<span class="mono text-xs pnl-pos">TP1: ${session.tp1}</span>` : ''}
        ${session.tp2 ? `<span class="mono text-xs" style="color:#38bdf8;">TP2: ${session.tp2}</span>` : ''}
        ${session.tp3 ? `<span class="mono text-xs" style="color:#a78bfa;">TP3: ${session.tp3}</span>` : ''}
        <span class="mono text-xs pnl-neg">SL: ${session.sl}</span>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 mb-4">
        <div class="agg-neu"><p class="text-xs mb-1" style="color:#52525b;">Confirmés</p><p class="text-xl font-light text-white">${session.total_members_in||0}</p></div>
        <div class="agg-neu"><p class="text-xs mb-1" style="color:#f59e0b;">Lots</p><p class="text-xl font-light mono" style="color:#f59e0b;">${(session.total_lots_engaged||0).toFixed(2)}</p></div>
        <div class="agg-loss"><p class="text-xs mb-1" style="color:#ef4444;">Risque SL</p><p class="text-xl font-light pnl-neg">-${Math.round(session.estimated_loss_sl||0)}$</p></div>
        <div class="agg-gain"><p class="text-xs mb-1" style="color:#22c55e;">Gain TP1</p><p class="text-xl font-light pnl-pos">+${Math.round(session.estimated_gain_tp1||0)}$</p></div>
        <div class="agg-gain col-span-2 sm:col-span-1"><p class="text-xs mb-1" style="color:#22c55e;">Gain TP2</p><p class="text-xl font-light pnl-pos">+${Math.round(session.estimated_gain_tp2||0)}$</p></div>
      </div>
      ${isActive ? `
      <div style="border-top:1px solid rgba(245,158,11,0.15);padding-top:12px;">
        <p class="text-xs mb-2" style="color:#52525b;">Déclencher manuellement</p>
        <div class="flex gap-2 flex-wrap">
          <button class="btn-ghost text-xs" style="color:#22c55e;border-color:rgba(34,197,94,0.3);" onclick="triggerTP(${session.id},1)">✅ TP1</button>
          ${session.tp2 ? `<button class="btn-ghost text-xs" style="color:#38bdf8;border-color:rgba(56,189,248,0.3);" onclick="triggerTP(${session.id},2)">🎯 TP2</button>` : ''}
          ${session.tp3 ? `<button class="btn-ghost text-xs" style="color:#a78bfa;border-color:rgba(167,139,250,0.3);" onclick="triggerTP(${session.id},3)">🏆 TP3</button>` : ''}
          <button class="btn-ghost text-xs" style="color:#ef4444;border-color:rgba(239,68,68,0.3);" onclick="triggerSL(${session.id})">❌ SL touché</button>
        </div>
      </div>` : ''}
    </div>`
}

function renderSeasonCard(season, ss, winRate) {
  if (!season) return `<div class="card p-4 text-center"><p class="text-xs" style="color:#3f3f46;">Aucune saison active</p><button class="btn-gold mt-3 text-xs" onclick="showView('saisons',document.getElementById('nav-saisons'))">Créer →</button></div>`
  return `
    <div class="card p-4">
      <div class="flex items-center justify-between mb-3">
        <p class="text-xs font-medium text-zinc-300 truncate">${season.name}</p>
        <span class="badge badge-gold ml-2">Active</span>
      </div>
      <div class="grid grid-cols-2 gap-2">
        <div class="stat-card text-center"><p class="text-base font-light text-white">${ss.total_trades||0}</p><p class="text-xs mt-0.5" style="color:#52525b;">Trades</p></div>
        <div class="stat-card text-center"><p class="text-base font-light pnl-pos">${winRate}%</p><p class="text-xs mt-0.5" style="color:#52525b;">Win</p></div>
      </div>
    </div>`
}

function renderPriceCard(price) {
  return `
    <div class="card p-4">
      <div class="flex items-center gap-2 mb-3">
        <span class="live-dot live-dot-gold pulse"></span>
        <p class="text-xs font-medium text-zinc-300">XAU/USD</p>
      </div>
      <p class="text-3xl font-light mono" style="color:#f59e0b;" id="live-price-main">${price ? parseFloat(price).toFixed(2) : '—'}</p>
      <p class="text-xs mt-2" style="color:#52525b;">Mise à jour / 30s</p>
    </div>`
}

function renderSimCards(sims) {
  return `
    <div>
      <div class="flex items-center justify-between mb-3">
        <p class="text-xs font-medium text-zinc-400">Simulations</p>
        <button class="btn-ghost text-xs" onclick="showView('simulations',document.getElementById('nav-simulations'))">Voir tout →</button>
      </div>
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
        ${sims.map(acc => `
          <div class="sim-card" onclick="showView('simulations',document.getElementById('nav-simulations'))">
            <p class="text-xs font-medium text-zinc-200 truncate">${acc.name}</p>
            <p class="text-xl font-light mt-1 ${(acc.rendement_pct||0)>=0?'pnl-pos':'pnl-neg'}">${(acc.current_capital||0).toFixed(0)}$</p>
            <p class="text-xs mt-0.5" style="color:${(acc.rendement_pct||0)>=0?'#22c55e':'#ef4444'};">${(acc.rendement_pct||0)>0?'+':''}${(acc.rendement_pct||0).toFixed(2)}%</p>
            <p class="text-xs mt-1" style="color:#52525b;">${acc.total_trades||0} trades</p>
          </div>`).join('')}
      </div>
    </div>`
}

function renderRecentSessions(recent) {
  return `
    <div>
      <p class="text-xs font-medium text-zinc-400 mb-3">Sessions récentes</p>
      <div class="card overflow-hidden">
        ${recent.map(s => `
          <div class="session-row" onclick="openSessionDrawer(${s.id})">
            <div style="width:80px;">${dirBadge(s.direction)}</div>
            <span class="mono text-xs text-white flex-1">${s.entry_price}</span>
            ${phaseBadge(s.current_phase)}
            <span class="text-xs" style="color:#52525b;">${fmtDate(s.created_at)}</span>
          </div>`).join('')}
      </div>
    </div>`
}

// ─── SESSIONS ────────────────────────────────────────────────
async function loadSessions() {
  const body  = document.getElementById('sessions-body')
  const phase = document.getElementById('sess-phase-filter')?.value || ''
  body.innerHTML = '<div class="p-8 text-center text-sm" style="color:#3f3f46;">Chargement...</div>'
  try {
    const d        = await gold(`/sessions?limit=30&offset=0${phase?'&phase='+phase:''}`)
    const sessions = d.sessions || []
    if (!sessions.length) { body.innerHTML = '<div class="p-8 text-center text-sm" style="color:#3f3f46;">Aucune session</div>'; return }
    body.innerHTML = sessions.map(s => `
      <div class="session-row" onclick="openSessionDrawer(${s.id})">
        <div>${dirBadge(s.direction)}</div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 flex-wrap">
            <span class="mono text-xs text-white">${s.entry_price}</span>
            ${phaseBadge(s.current_phase)}
          </div>
          <div class="flex gap-2 mt-1 text-xs mono flex-wrap">
            ${s.tp1?`<span class="pnl-pos">TP1:${s.tp1}</span>`:''}
            ${s.tp2?`<span style="color:#38bdf8;">TP2:${s.tp2}</span>`:''}
            <span class="pnl-neg">SL:${s.sl}</span>
          </div>
        </div>
        <div class="text-right flex-shrink-0">
          <p class="text-xs text-zinc-300">${s.total_members_in||0} mbrs</p>
          <p class="text-xs" style="color:#f59e0b;">${(s.total_lots_engaged||0).toFixed(2)} lots</p>
        </div>
        <svg width="14" height="14" fill="none" stroke="#52525b" viewBox="0 0 24 24" stroke-width="2" style="flex-shrink:0;"><polyline points="9 18 15 12 9 6"/></svg>
      </div>`).join('')
  } catch(e) { body.innerHTML = `<div class="p-6 text-center text-sm pnl-neg">Erreur: ${e.message}</div>` }
}

// ─── SESSION DRAWER ───────────────────────────────────────────
async function openSessionDrawer(sessionId) {
  currentGoldSessionId = sessionId
  openDrawer('session-drawer')
  document.getElementById('session-drawer-content').innerHTML = '<div class="text-center py-16 text-sm" style="color:#3f3f46;">Chargement...</div>'
  document.getElementById('session-drawer-actions').innerHTML = ''
  try {
    const s        = await gold(`/sessions/${sessionId}`)
    const isActive = ['teaser','open','tp1_reached','tp2_reached'].includes(s.current_phase)
    document.getElementById('session-drawer-header').innerHTML = `
      <div class="flex items-center gap-2 flex-wrap">
        <p class="text-sm font-medium text-white">XAU/USD — ${s.direction === 'buy' ? 'Buy' : 'Sell'}</p>
        ${phaseBadge(s.current_phase)}
      </div>
      <p class="text-xs mt-0.5" style="color:#52525b;">${fmtDate(s.created_at)} · ⭐×${s.confidence_level||3}</p>`

    document.getElementById('session-drawer-content').innerHTML = `
      <div>
        <p class="text-xs font-medium text-zinc-400 mb-3">Niveaux</p>
        <div class="flex flex-wrap gap-2 text-xs mono">
          <span class="text-white">Entrée: ${s.entry_price}</span>
          ${s.tp1?`<span class="pnl-pos">TP1: ${s.tp1}</span>`:''}
          ${s.tp2?`<span style="color:#38bdf8;">TP2: ${s.tp2}</span>`:''}
          ${s.tp3?`<span style="color:#a78bfa;">TP3: ${s.tp3}</span>`:''}
          <span class="pnl-neg">SL: ${s.sl}</span>
          ${s.sl_pips?`<span style="color:#52525b;">(${s.sl_pips} pips)</span>`:''}
        </div>
      </div>
      <div>
        <p class="text-xs font-medium text-zinc-400 mb-3">Agrégats</p>
        <div class="grid grid-cols-3 gap-2 mb-2">
          <div class="agg-neu text-center"><p class="text-xs mb-1" style="color:#52525b;">Confirmés</p><p class="text-xl font-light text-white">${s.total_members_in||0}</p></div>
          <div class="agg-neu text-center"><p class="text-xs mb-1" style="color:#f59e0b;">Lots</p><p class="text-xl font-light mono" style="color:#f59e0b;">${(s.total_lots_engaged||0).toFixed(2)}</p></div>
          <div class="agg-loss text-center"><p class="text-xs mb-1" style="color:#ef4444;">Risque SL</p><p class="text-xl font-light pnl-neg">-${Math.round(s.estimated_loss_sl||0)}$</p></div>
        </div>
        <div class="grid grid-cols-3 gap-2">
          <div class="agg-gain text-center"><p class="text-xs mb-1" style="color:#22c55e;">TP1</p><p class="text-base font-light pnl-pos">+${Math.round(s.estimated_gain_tp1||0)}$</p></div>
          <div class="agg-gain text-center"><p class="text-xs mb-1" style="color:#22c55e;">TP2</p><p class="text-base font-light pnl-pos">+${Math.round(s.estimated_gain_tp2||0)}$</p></div>
          <div class="agg-gain text-center"><p class="text-xs mb-1" style="color:#a78bfa;">TP3</p><p class="text-base font-light" style="color:#a78bfa;">+${Math.round(s.estimated_gain_tp3||0)}$</p></div>
        </div>
      </div>
      ${(s.tp_distribution||[]).length ? `
      <div>
        <p class="text-xs font-medium text-zinc-400 mb-3">Répartition TP</p>
        ${(s.tp_distribution||[]).map(tp => `
          <div style="padding:10px;background:rgba(255,255,255,0.025);border-radius:8px;margin-bottom:6px;">
            <div class="flex justify-between mb-1">
              <span class="text-xs font-medium text-zinc-200">TP${tp.tp_level_assigned} — ${tp.members} membres</span>
              <span class="text-xs mono" style="color:#f59e0b;">${(tp.total_lots||0).toFixed(2)} lots</span>
            </div>
            <div class="flex gap-3 text-xs">
              <span class="pnl-neg">-${(tp.total_risk||0).toFixed(0)}$</span>
              <span class="pnl-pos">TP1: +${(tp.total_gain_tp1||0).toFixed(0)}$</span>
              ${tp.total_gain_tp2?`<span style="color:#38bdf8;">TP2: +${tp.total_gain_tp2.toFixed(0)}$</span>`:''}
            </div>
          </div>`).join('')}
      </div>` : ''}
      ${(s.entries||[]).length ? `
      <div>
        <p class="text-xs font-medium text-zinc-400 mb-3">Membres confirmés (${(s.entries||[]).length})</p>
        ${(s.entries||[]).map(e => `
          <div class="flex items-center gap-2 py-2" style="border-bottom:1px solid rgba(255,255,255,0.04);">
            <div style="width:26px;height:26px;border-radius:50%;background:rgba(245,158,11,0.15);display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:500;color:#f59e0b;flex-shrink:0;">${initials(e.name)}</div>
            <span class="text-xs text-zinc-200 flex-1 truncate">${e.name||'#'+e.user_id}</span>
            <span class="badge badge-amber text-xs" style="font-size:9px;">TP${e.tp_level_assigned}</span>
            <span class="mono text-xs" style="color:#52525b;">${e.lot_calculated}</span>
            <span class="mono text-xs pnl-neg">${e.perte_sl}$</span>
          </div>`).join('')}
      </div>` : ''}
      ${(s.simulation_trades||[]).length ? `
      <div>
        <p class="text-xs font-medium text-zinc-400 mb-3">Comptes simulation</p>
        ${(s.simulation_trades||[]).map(st => `
          <div class="flex items-center gap-2 py-2" style="border-bottom:1px solid rgba(255,255,255,0.04);">
            <span class="text-xs text-zinc-300 flex-1">${st.account_name}</span>
            <span class="mono text-xs" style="color:#52525b;">${st.capital_before}$</span>
            <span class="badge ${st.status==='open'?'badge-sky':st.result_usd>0?'badge-green':'badge-red'}" style="font-size:9px;">${st.status==='open'?'Ouvert':st.result_usd>=0?'+'+st.result_usd+'$':st.result_usd+'$'}</span>
          </div>`).join('')}
      </div>` : ''}`

    if (isActive) {
      document.getElementById('session-drawer-actions').innerHTML = `
        <button class="btn-ghost text-xs" style="color:#22c55e;" onclick="triggerTP(${s.id},1)">✅ TP1</button>
        ${s.tp2 ? `<button class="btn-ghost text-xs" style="color:#38bdf8;" onclick="triggerTP(${s.id},2)">🎯 TP2</button>` : ''}
        ${s.tp3 ? `<button class="btn-ghost text-xs" style="color:#a78bfa;" onclick="triggerTP(${s.id},3)">🏆 TP3</button>` : ''}
        <button class="btn-ghost text-xs" style="color:#ef4444;" onclick="triggerSL(${s.id})">❌ SL</button>
        <button class="btn-gold text-xs ml-auto" onclick="openGoldCloseModal(${s.id})">Clôturer →</button>`
    }
  } catch(e) { toast('Erreur session', 'error') }
}

// ─── TP / SL ─────────────────────────────────────────────────
async function triggerTP(sessionId, tp) {
  if (!confirm(`Déclencher TP${tp} — session #${sessionId} ?`)) return
  try {
    const r = await gold(`/sessions/${sessionId}/tp/${tp}`, {method:'POST'})
    toast(`TP${tp} déclenché — ${(r.sent_exit||0)+(r.sent_continue||0)} notifiés ✓`, 'success')
    loadLiveDashboard(); closeDrawer()
  } catch(e) { toast('Erreur: '+e.message, 'error') }
}
async function triggerSL(sessionId) {
  if (!confirm(`Déclencher le SL — session #${sessionId} ?`)) return
  try {
    const r = await gold(`/sessions/${sessionId}/sl`, {method:'POST'})
    toast(`SL déclenché — ${r.notified||0} notifiés`, 'info')
    loadLiveDashboard(); closeDrawer()
  } catch(e) { toast('Erreur: '+e.message, 'error') }
}

// ─── GOLD CLOSE MODAL ─────────────────────────────────────────
function openGoldCloseModal(sessionId) {
  currentGoldSessionId = sessionId
  document.getElementById('gold-close-subtitle').textContent = `Session #${sessionId}`
  setGoldCloseType('tp1')
  openModal('modal-gold-close')
}
function setGoldCloseType(t) {
  goldCloseType = t
  document.querySelectorAll('.gold-close-btn').forEach(btn => {
    btn.style.fontWeight = btn.dataset.t === t ? '600' : '400'
    btn.style.boxShadow  = btn.dataset.t === t ? 'inset 0 0 0 1px rgba(255,255,255,0.15)' : 'none'
  })
}
async function confirmGoldClose() {
  if (!currentGoldSessionId) return
  const btn = document.getElementById('btn-gold-close-confirm')
  btn.disabled = true; btn.textContent = 'Clôture...'
  try {
    await gold(`/sessions/${currentGoldSessionId}/close`, {method:'POST', body:JSON.stringify({close_type: goldCloseType})})
    toast(`Session clôturée (${goldCloseType.toUpperCase()}) ✓`, 'success')
    closeModal('modal-gold-close'); closeDrawer()
    loadLiveDashboard(); loadSessions()
  } catch(e) { toast('Erreur: '+e.message, 'error') }
  finally { btn.disabled=false; btn.textContent='Clôturer & notifier →' }
}

// ─── PUBLIER GOLD ─────────────────────────────────────────────
async function publishGoldSession() {
  const entry = document.getElementById('gold-entry')?.value
  const sl    = document.getElementById('gold-sl')?.value
  const tp1   = document.getElementById('gold-tp1')?.value
  if (!entry || !sl || !tp1) { toast('Entrée, SL et TP1 requis','error'); return }

  const btn = document.getElementById('btn-publish')
  btn.disabled = true; btn.textContent = 'Publication...'

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
    screenshot_url:   goldUploadUrl||null,
  }
  try {
    const result = await gold('/sessions', {method:'POST', body:JSON.stringify(payload)})
    const sent   = result.broadcast_report?.sent ?? 0
    const warn   = result.broadcast_warning
    if (warn) toast('Trade créé — teaser non envoyé (bot non configuré)', 'warning')
    else      toast(`Session Gold créée${sent>0?' — teaser envoyé à '+sent+' membres':''} ✓`, 'success')
    closeModal('modal-gold-publish')
    ;['gold-entry','gold-sl','gold-tp1','gold-tp2','gold-tp3','gold-note'].forEach(id => {
      const e = document.getElementById(id); if(e) e.value=''
    })
    setGoldDir('buy'); setConfidence(3); updateGoldCalcs()
    loadLiveDashboard()
  } catch(e) { toast('Erreur: '+e.message,'error') }
  finally { btn.disabled=false; btn.innerHTML='<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>Publier →' }
}

// ─── SAISONS ─────────────────────────────────────────────────
async function loadSaisons() {
  const main = document.getElementById('saisons-main')
  main.innerHTML = '<div class="text-center py-16 text-sm" style="color:#3f3f46;">Chargement...</div>'
  try {
    const seasons = await gold('/seasons')
    if (!seasons.length) { main.innerHTML = `<div class="text-center py-16"><p class="text-sm" style="color:#3f3f46;">Aucune saison</p><button class="btn-gold mt-4" onclick="openModal('modal-new-season')">Créer une saison →</button></div>`; return }
    main.innerHTML = `<div class="flex flex-col gap-4">${seasons.map(s => {
      const isActive = s.status === 'active'
      const winRate  = s.trades_count > 0 ? Math.round((s.wins_count||0)/s.trades_count*100) : 0
      return `
        <div class="${isActive?'card-gold':'card'} p-4 sm:p-5">
          <div class="flex items-start justify-between mb-4 gap-2">
            <div>
              <div class="flex items-center gap-2 mb-1 flex-wrap">
                <p class="text-sm font-medium text-white">${s.name}</p>
                <span class="badge ${isActive?'badge-gold':s.status==='reset'?'badge-amber':'badge-zinc'}">${isActive?'Active':s.status==='reset'?'Réinitialisée':'Clôturée'}</span>
              </div>
              <p class="text-xs" style="color:#52525b;">${fmtDate(s.start_date)} · ${s.members_participated||0} membres</p>
            </div>
            ${isActive ? `
              <div class="flex gap-2 flex-shrink-0">
                <button class="btn-ghost text-xs" onclick="loadSeasonStats(${s.id})">Stats</button>
                <button class="btn-danger text-xs" onclick="openResetModal(${s.id})">Reset</button>
              </div>` : `<button class="btn-ghost text-xs flex-shrink-0" onclick="loadSeasonStats(${s.id})">Stats →</button>`}
          </div>
          <div class="grid grid-cols-4 gap-2">
            <div class="stat-card text-center"><p class="text-base font-light text-white">${s.trades_count||0}</p><p class="text-xs mt-0.5" style="color:#52525b;">Trades</p></div>
            <div class="stat-card text-center"><p class="text-base font-light pnl-pos">${winRate}%</p><p class="text-xs mt-0.5" style="color:#52525b;">Win</p></div>
            <div class="stat-card text-center"><p class="text-base font-light pnl-pos">${s.wins_count||0}</p><p class="text-xs mt-0.5" style="color:#22c55e;">Wins</p></div>
            <div class="stat-card text-center"><p class="text-base font-light pnl-neg">${s.losses_count||0}</p><p class="text-xs mt-0.5" style="color:#ef4444;">Losses</p></div>
          </div>
          <div id="season-stats-${s.id}"></div>
        </div>`
    }).join('')}</div>`
  } catch(e) { main.innerHTML = `<div class="text-center py-16 text-sm pnl-neg">Erreur: ${e.message}</div>` }
}

async function loadSeasonStats(seasonId) {
  const el = document.getElementById('season-stats-'+seasonId)
  if (!el || el.dataset.loaded) { if(el) el.innerHTML=''; delete el?.dataset.loaded; return }
  el.innerHTML = '<div class="text-center py-4 text-xs" style="color:#3f3f46;">Chargement...</div>'
  try {
    const d   = await gold(`/seasons/${seasonId}/stats`)
    const ms  = d.member_stats||{}
    const sim = d.simulation_accounts||[]
    const top = d.top_members||[]
    el.dataset.loaded = '1'
    el.innerHTML = `
      <div class="mt-4 pt-4" style="border-top:1px solid rgba(245,158,11,0.15);">
        <div class="grid grid-cols-3 gap-2 mb-3">
          <div class="stat-card"><p class="text-xs mb-1" style="color:#52525b;">Membres</p><p class="text-base font-light text-white">${ms.unique_members||0}</p></div>
          <div class="stat-card"><p class="text-xs mb-1" style="color:#52525b;">Gains</p><p class="text-base font-light ${(ms.total_gains_members||0)>=0?'pnl-pos':'pnl-neg'}">${(ms.total_gains_members||0)>0?'+':''}${Math.round(ms.total_gains_members||0)}$</p></div>
          <div class="stat-card"><p class="text-xs mb-1" style="color:#52525b;">Suivi</p><p class="text-base font-light" style="color:#38bdf8;">${Math.round(ms.instruction_follow_rate||0)}%</p></div>
        </div>
        ${sim.length ? `
          <p class="text-xs font-medium text-zinc-400 mb-2">Simulations</p>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-2 mb-3">
            ${sim.map(a=>`<div class="sim-card text-center"><p class="text-xs font-medium text-zinc-200 truncate">${a.name}</p><p class="text-base font-light ${(a.rendement_pct||0)>=0?'pnl-pos':'pnl-neg'}">${(a.rendement_pct||0)>0?'+':''}${(a.rendement_pct||0).toFixed(2)}%</p><p class="text-xs" style="color:#52525b;">${a.initial_capital}$→${(a.current_capital||0).toFixed(0)}$</p></div>`).join('')}
          </div>` : ''}
        ${top.slice(0,5).map((m,i)=>`
          <div class="flex items-center gap-2 py-2" style="border-bottom:1px solid rgba(255,255,255,0.04);">
            <span style="font-size:13px;">${['🥇','🥈','🥉','4.','5.'][i]}</span>
            <span class="text-xs text-zinc-200 flex-1">${m.name||'—'}</span>
            <span class="text-xs" style="color:#52525b;">${m.trades} trades</span>
            <span class="text-xs ${(m.total_usd||0)>=0?'pnl-pos':'pnl-neg'}">${(m.total_usd||0)>0?'+':''}${Math.round(m.total_usd||0)}$</span>
          </div>`).join('')}
      </div>`
  } catch(e) { el.innerHTML='' }
}

function openResetModal(id) { resetSeasonId=id; openModal('modal-reset-season') }
async function createSeason() {
  const name = document.getElementById('new-season-name')?.value.trim()
  if (!name) { toast('Nom requis','error'); return }
  try {
    await gold('/seasons', {method:'POST', body:JSON.stringify({name, description:document.getElementById('new-season-desc')?.value, initial_capital_ref:parseFloat(document.getElementById('new-season-capital')?.value)||null})})
    toast('Saison créée ✓','success'); closeModal('modal-new-season'); loadSaisons()
  } catch(e) { toast('Erreur: '+e.message,'error') }
}
async function confirmResetSeason() {
  const name = document.getElementById('reset-season-name')?.value.trim()
  if (!name || !resetSeasonId) { toast('Nom requis','error'); return }
  try {
    const r = await gold(`/seasons/${resetSeasonId}/reset`, {method:'POST', body:JSON.stringify({new_season_name:name, new_initial_capital:parseFloat(document.getElementById('reset-season-capital')?.value)||null})})
    toast(`Réinitialisée — ${r.accounts_reset} comptes remis à zéro ✓`,'success')
    closeModal('modal-reset-season'); loadSaisons(); loadSimulations()
  } catch(e) { toast('Erreur: '+e.message,'error') }
}

// ─── SIMULATIONS ──────────────────────────────────────────────
async function loadSimulations() {
  const main = document.getElementById('simulations-main')
  main.innerHTML = '<div class="text-center py-16 text-sm" style="color:#3f3f46;">Chargement...</div>'
  try {
    const accounts = await gold('/simulations')
    if (!accounts.length) { main.innerHTML = `<div class="text-center py-16"><p class="text-sm" style="color:#3f3f46;">Aucun compte simulation</p><button class="btn-gold mt-4" onclick="openModal('modal-new-sim')">Créer un compte →</button></div>`; return }
    main.innerHTML = `
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-4">
        ${accounts.map(acc => `
          <div class="sim-card">
            <div class="flex items-start justify-between mb-2">
              <p class="text-xs font-medium text-zinc-200 truncate flex-1">${acc.name}</p>
              <div class="flex items-center gap-1 ml-2 flex-shrink-0">
                <span class="badge ${acc.is_active?'badge-gold':'badge-zinc'} ml-1" style="font-size:9px;">${acc.is_active?'Actif':'Off'}</span>
                <button class="btn-icon" style="width:24px;height:24px;" onclick="openDeleteSim(${acc.id},'${acc.name.replace(/'/g,"\\'")}')" title="Supprimer définitivement">
                  <svg width="11" height="11" fill="none" stroke="#ef4444" viewBox="0 0 24 24" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="m19 6-1 14H6L5 6"/></svg>
                </button>
              </div>
            </div>
            <p class="text-2xl font-light mono ${(acc.rendement_pct||0)>=0?'pnl-pos':'pnl-neg'} cursor-pointer" onclick="loadSimDetail(${acc.id})">${(acc.current_capital||0).toFixed(0)}$</p>
            <p class="text-xs mt-0.5" style="color:${(acc.rendement_pct||0)>=0?'#22c55e':'#ef4444'};">${(acc.rendement_pct||0)>0?'+':''}${(acc.rendement_pct||0).toFixed(2)}%</p>
            <div class="flex gap-2 mt-2 text-xs">
              <span class="pnl-pos">W:${acc.wins||0}</span>
              <span class="pnl-neg">L:${acc.losses||0}</span>
              <span style="color:#52525b;">DD:${(acc.max_drawdown_pct||0).toFixed(1)}%</span>
            </div>
            <p class="text-xs mt-1" style="color:#3f3f46;">Init: ${acc.initial_capital}$</p>
            <button class="btn-ghost text-xs w-full justify-center mt-2" onclick="loadSimDetail(${acc.id})">Voir historique →</button>
          </div>`).join('')}
      </div>
      <div id="sim-detail"></div>`
  } catch(e) { main.innerHTML = `<div class="text-center py-16 text-sm pnl-neg">Erreur: ${e.message}</div>` }
}

// ─── DELETE SIM ───────────────────────────────────────────────
function openDeleteSim(id, name) {
  deleteSimId = id
  document.getElementById('delete-sim-name').textContent = name
  openModal('modal-delete-sim')
}

async function confirmDeleteSim() {
  if (!deleteSimId) return
  const btn = document.getElementById('btn-delete-sim-confirm')
  btn.disabled = true; btn.textContent = 'Suppression...'
  try {
    await gold(`/simulations/${deleteSimId}`, {method:'DELETE'})
    toast('Compte supprimé définitivement ✓', 'success')
    closeModal('modal-delete-sim')
    deleteSimId = null
    loadSimulations()
    loadLiveDashboard()
  } catch(e) { toast('Erreur: '+e.message, 'error') }
  finally {
    btn.disabled = false
    btn.innerHTML = '<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="m19 6-1 14H6L5 6"/></svg>Supprimer définitivement'
  }
}

async function loadSimDetail(accountId) {
  const el = document.getElementById('sim-detail')
  if (!el) return
  el.innerHTML = '<div class="text-center py-6 text-xs" style="color:#3f3f46;">Chargement...</div>'
  try {
    const d      = await gold(`/simulations/${accountId}`)
    const curve  = d.capital_curve||[]
    const trades = d.trades||[]
    let curveHtml = ''
    if (curve.length > 1) {
      const caps = curve.map(c=>c.capital)
      const minC = Math.min(...caps), maxC = Math.max(...caps), rng = maxC-minC||1
      const pts  = curve.map((c,i) => `${(i/(curve.length-1)*380).toFixed(1)},${(70-((c.capital-minC)/rng*60)).toFixed(1)}`).join(' ')
      const col  = (d.rendement_pct||0)>=0 ? '#22c55e' : '#ef4444'
      curveHtml = `
        <div class="card p-4 mb-4">
          <div class="flex justify-between mb-3">
            <p class="text-xs font-medium text-zinc-300">${d.name}</p>
            <span class="${(d.rendement_pct||0)>=0?'pnl-pos':'pnl-neg'} text-sm font-medium">${(d.rendement_pct||0)>0?'+':''}${(d.rendement_pct||0).toFixed(2)}%</span>
          </div>
          <svg width="100%" height="70" viewBox="0 0 380 70" preserveAspectRatio="none" style="display:block;">
            <polyline points="${pts}" style="fill:none;stroke:${col};stroke-width:2;"/>
          </svg>
          <div class="flex justify-between mt-2 text-xs">
            <span style="color:#52525b;">Départ: ${d.initial_capital}$</span>
            <span class="${(d.rendement_pct||0)>=0?'pnl-pos':'pnl-neg'}">${(d.current_capital||0).toFixed(0)}$</span>
          </div>
        </div>`
    }
    el.innerHTML = `
      ${curveHtml}
      <div class="card overflow-hidden">
        <div class="px-4 py-3 flex items-center justify-between" style="border-bottom:1px solid rgba(255,255,255,0.05);">
          <p class="text-xs font-medium text-zinc-300">Historique — ${d.name}</p>
          <span class="badge badge-zinc">${trades.length} trades</span>
        </div>
        ${trades.map(t => `
          <div class="session-row">
            <div>${dirBadge(t.direction)}</div>
            <span class="mono text-xs text-white flex-1">${t.entry_price}</span>
            <span class="text-xs" style="color:#f59e0b;">TP${t.tp_level_target}</span>
            <span class="mono text-xs ${t.result_usd!=null?((t.result_usd||0)>=0?'pnl-pos':'pnl-neg'):'text-zinc-500'}">${t.result_usd!=null?((t.result_usd||0)>=0?'+':'')+t.result_usd.toFixed(2)+'$':'—'}</span>
            <span class="badge ${t.status==='open'?'badge-sky':(t.result_usd||0)>0?'badge-green':'badge-red'}" style="font-size:9px;">${t.status==='open'?'Ouvert':t.exit_tp_level?'TP'+t.exit_tp_level:'SL'}</span>
          </div>`).join('')}
        ${!trades.length?'<div class="p-6 text-center text-xs" style="color:#3f3f46;">Aucun trade</div>':''}
      </div>`
  } catch(e) { el.innerHTML = `<div class="text-xs pnl-neg">Erreur: ${e.message}</div>` }
}

async function createSimAccount() {
  const name = document.getElementById('new-sim-name')?.value.trim()
  const cap  = parseFloat(document.getElementById('new-sim-capital')?.value)
  if (!name || !cap) { toast('Nom et capital requis','error'); return }
  try {
    await gold('/simulations', {method:'POST', body:JSON.stringify({name, initial_capital:cap, description:document.getElementById('new-sim-desc')?.value, risk_pct_default:parseFloat(document.getElementById('new-sim-risk')?.value)||1})})
    toast('Compte créé ✓','success'); closeModal('modal-new-sim'); loadSimulations()
  } catch(e) { toast('Erreur: '+e.message,'error') }
}

// ─── PERFORMANCES ─────────────────────────────────────────────
function setPerfPeriod(p, btn) {
  perfPeriod = p
  document.querySelectorAll('#view-performances .btn-ghost').forEach(b => b.style.background='')
  if (btn) btn.style.background='rgba(255,255,255,0.08)'
  loadPerformances()
}

async function loadPerformances() {
  const main = document.getElementById('perf-main')
  main.innerHTML = '<div class="text-center py-16 text-sm" style="color:#3f3f46;">Chargement...</div>'
  try {
    const season = await gold('/seasons/active').catch(()=>null)
    const [sims, sessions] = await Promise.all([
      gold('/simulations'),
      gold('/sessions?limit=50&offset=0')
    ])
    const allSessions  = sessions.sessions||[]
    const closedSess   = allSessions.filter(s => ['tp1_reached','tp2_reached','tp3_reached','sl_touched','closed'].includes(s.current_phase))
    const wins         = closedSess.filter(s => ['tp1_reached','tp2_reached','tp3_reached'].includes(s.current_phase)).length
    const losses       = closedSess.filter(s => s.current_phase==='sl_touched').length
    const winRate      = closedSess.length > 0 ? Math.round(wins/closedSess.length*100) : 0
    const totalLots    = allSessions.reduce((a,s)=>a+(s.total_lots_engaged||0),0)
    const totalMembers = Math.max(...allSessions.map(s=>s.total_members_in||0),0)

    main.innerHTML = `
      <div class="flex flex-col gap-4 fadein">
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
          <div class="stat-gold"><p class="text-xs mb-1" style="color:#52525b;">Sessions</p><p class="text-2xl font-light text-white">${closedSess.length}</p></div>
          <div class="stat-gold"><p class="text-xs mb-1" style="color:#52525b;">Win rate</p><p class="text-2xl font-light pnl-pos">${winRate}%</p><div class="pbar mt-2"><div class="pbar-fill" style="width:${winRate}%;background:#22c55e;"></div></div></div>
          <div class="stat-gold"><p class="text-xs mb-1" style="color:#52525b;">Wins / Losses</p><p class="text-2xl font-light"><span class="pnl-pos">${wins}</span><span style="color:#52525b;font-size:14px;"> / </span><span class="pnl-neg">${losses}</span></p></div>
          <div class="stat-gold"><p class="text-xs mb-1" style="color:#52525b;">Lots total</p><p class="text-2xl font-light mono" style="color:#f59e0b;">${totalLots.toFixed(2)}</p></div>
        </div>

        ${sims.length ? `
        <div>
          <p class="text-xs font-medium text-zinc-400 mb-3">Performance comptes simulation</p>
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            ${sims.map(acc => `
              <div class="sim-card cursor-pointer" onclick="loadSimDetail(${acc.id})">
                <p class="text-xs font-medium text-zinc-200 truncate">${acc.name}</p>
                <p class="text-xl font-light mt-1 ${(acc.rendement_pct||0)>=0?'pnl-pos':'pnl-neg'}">${(acc.rendement_pct||0)>0?'+':''}${(acc.rendement_pct||0).toFixed(2)}%</p>
                <div class="pbar mt-2"><div class="pbar-fill" style="width:${Math.min(100,Math.abs(acc.rendement_pct||0))}%;background:${(acc.rendement_pct||0)>=0?'#22c55e':'#ef4444'};"></div></div>
                <div class="flex justify-between mt-2 text-xs">
                  <span class="pnl-pos">W:${acc.wins||0}</span>
                  <span class="pnl-neg">L:${acc.losses||0}</span>
                  <span style="color:#52525b;">${acc.total_trades||0} trades</span>
                </div>
                <p class="text-xs mt-1" style="color:#52525b;">${acc.initial_capital}$ → ${(acc.current_capital||0).toFixed(0)}$</p>
              </div>`).join('')}
          </div>
        </div>` : ''}

        <div>
          <p class="text-xs font-medium text-zinc-400 mb-3">Dernières sessions</p>
          <div class="card overflow-hidden">
            ${closedSess.slice(0,10).map(s => `
              <div class="session-row" onclick="openSessionDrawer(${s.id})">
                <div>${dirBadge(s.direction)}</div>
                <span class="mono text-xs text-white">${s.entry_price}</span>
                <div class="flex-1">${phaseBadge(s.current_phase)}</div>
                <span class="text-xs" style="color:#52525b;">${s.total_members_in||0} mbrs</span>
                <span class="text-xs" style="color:#3f3f46;">${fmtDate(s.created_at)}</span>
              </div>`).join('')}
            ${!closedSess.length?'<div class="p-8 text-center text-sm" style="color:#3f3f46;">Aucune session clôturée</div>':''}
          </div>
        </div>
        <div id="sim-detail-perf"></div>
      </div>`
  } catch(e) { main.innerHTML = `<div class="text-center py-16 text-sm pnl-neg">Erreur: ${e.message}</div>` }
}

// ─── RÈGLES ───────────────────────────────────────────────────
async function loadRegles() {
  const main = document.getElementById('regles-main')
  main.innerHTML = '<div class="text-center py-16 text-sm" style="color:#3f3f46;">Chargement...</div>'
  try {
    const rules = await gold('/rules')
    if (!rules.length) { main.innerHTML = `<div class="text-center py-16"><p class="text-sm" style="color:#3f3f46;">Aucune règle</p><button class="btn-gold mt-4" onclick="openModal('modal-new-rule')">Créer une règle →</button></div>`; return }

    main.innerHTML = `
      <div style="background:rgba(245,158,11,0.04);border:1px solid rgba(245,158,11,0.12);border-radius:10px;padding:12px 14px;margin-bottom:4px;">
        <p class="text-xs" style="color:#71717a;">Les messages sont envoyés automatiquement selon le capital du membre. Modifiez-les directement depuis le dashboard.</p>
      </div>
      <div class="flex flex-col gap-4">
        ${rules.map(r => {
          const tpColor = r.tp_level===1?'#22c55e':r.tp_level===2?'#38bdf8':'#a78bfa'
          const msgs = [['Teaser',r.message_teaser],['TP1',r.message_tp1_reached],['TP2',r.message_tp2_reached],['TP3',r.message_tp3_reached],['SL',r.message_sl_touched],['Break even',r.message_breakeven],['Confirmation',r.message_confirmation]].filter(([,m])=>m)
          return `
            <div class="rule-card">
              <div class="flex items-start justify-between mb-4 gap-2">
                <div class="flex items-center gap-2 flex-wrap">
                  <div style="padding:3px 10px;border-radius:6px;font-size:11px;font-weight:600;background:rgba(245,158,11,0.1);color:${tpColor};border:1px solid rgba(245,158,11,0.2);">TP${r.tp_level}</div>
                  <p class="text-sm font-medium text-white">${r.rule_name}</p>
                  <span class="badge badge-zinc">${r.min_capital}$${r.max_capital?' – '+r.max_capital+'$':' +'}</span>
                  <span class="badge badge-amber">Risque ${r.risk_pct}%</span>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                  <span class="badge ${r.is_active?'badge-green':'badge-zinc'}" style="font-size:9px;">${r.is_active?'Actif':'Off'}</span>
                  <button class="btn-icon" onclick="editRule(${r.id})" title="Modifier">
                    <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </button>
                </div>
              </div>
              ${msgs.length ? `
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                  ${msgs.map(([label,msg])=>`
                    <div style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.06);border-radius:8px;padding:10px;">
                      <p class="text-xs mb-1.5" style="color:#52525b;text-transform:uppercase;letter-spacing:.04em;">${label}</p>
                      <p class="text-xs" style="color:#a1a1aa;line-height:1.5;">${msg.replace(/\n/g,'<br>').replace(/\*(.*?)\*/g,'<b style="color:#e4e4e7;">$1</b>').substring(0,100)}${msg.length>100?'…':''}</p>
                    </div>`).join('')}
                </div>` : `<p class="text-xs" style="color:#3f3f46;">Aucun message configuré.</p>`}
            </div>`
        }).join('')}
      </div>`
  } catch(e) { main.innerHTML = `<div class="text-center py-16 text-sm pnl-neg">Erreur: ${e.message}</div>` }
}

async function editRule(ruleId) {
  try {
    const rules = await gold('/rules')
    const r = rules.find(x=>x.id===ruleId); if(!r) return
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
  } catch(e) { toast('Erreur','error') }
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
    if (editId) await gold(`/rules/${editId}`, {method:'PATCH', body:JSON.stringify(payload)})
    else        await gold('/rules', {method:'POST', body:JSON.stringify(payload)})
    toast(editId?'Règle mise à jour ✓':'Règle créée ✓','success')
    delete document.getElementById('modal-new-rule').dataset.editId
    closeModal('modal-new-rule'); loadRegles()
  } catch(e) { toast('Erreur: '+e.message,'error') }
}

// ─── UPLOAD ───────────────────────────────────────────────────
function triggerGoldUpload() {
  let input = document.getElementById('_gold-file-input')
  if (!input) {
    input = document.createElement('input'); input.type='file'; input.id='_gold-file-input'
    input.accept='image/*,video/*'; input.style.display='none'; document.body.appendChild(input)
    input.addEventListener('change', () => { if(input.files?.[0]) handleGoldFile(input.files[0]); input.value='' })
  }
  input.click()
}
async function handleGoldFile(file) {
  const zone = document.getElementById('gold-upload-zone')
  if (!zone) return
  zone.innerHTML = '<p class="text-xs" style="color:#f59e0b;">Upload en cours...</p>'
  try {
    const fd = new FormData(); fd.append('user_id','0'); fd.append('file',file)
    const res = await fetch(`${API_URL}/chat/media/upload`, {method:'POST', body:fd})
    if (!res.ok) throw new Error('Upload failed')
    const d = await res.json()
    // CORRECTION : URL complète avec domaine si chemin relatif
    const rawUrl = d.url || d.media_url || d.file_url || d.path
    goldUploadUrl = rawUrl.startsWith('http') ? rawUrl : `${API_URL}${rawUrl}`
    zone.innerHTML = `<p class="text-xs pnl-pos">✓ ${file.name}</p><button onclick="resetGoldUpload()" style="font-size:10px;color:#52525b;background:none;border:none;cursor:pointer;">Supprimer</button>`
    zone.style.borderColor = 'rgba(34,197,94,0.3)'
  } catch(e) { zone.innerHTML='<p class="text-xs pnl-neg">Erreur upload</p>'; toast('Erreur upload','error') }
}
function resetGoldUpload() {
  goldUploadUrl = null
  const zone = document.getElementById('gold-upload-zone')
  if (zone) {
    zone.innerHTML='<svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="margin:0 auto 5px;color:#52525b;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg><p class="text-xs" style="color:#71717a;">Glisser ou <span style="color:#f59e0b;cursor:pointer;">parcourir</span></p>'
    zone.style.borderColor=''
  }
}

// ─── CATÉGORIES ───────────────────────────────────────────────
async function loadCategories() {
  try {
    const res  = await fetch(`${API_URL}/categorie`)
    const data = await res.json()
    const sel  = document.getElementById('gold-category')
    if (sel) {
      sel.innerHTML = ''
      data.forEach(cat => { const o=document.createElement('option'); o.value=cat.name_categorie; o.textContent=cat.name_categorie; sel.appendChild(o) })
    }
  } catch(e) {}
}

// ─── HELPERS ─────────────────────────────────────────────────
function initials(name) { if(!name) return '?'; return name.trim().split(' ').map(w=>w[0]).slice(0,2).join('').toUpperCase() }
function fmtDate(iso) {
  if (!iso) return '—'
  try {
    const d=new Date(iso), now=new Date(), diff=(now-d)/1000
    if(diff<60) return "À l'instant"
    if(diff<3600) return Math.floor(diff/60)+'min'
    if(diff<86400) return d.toLocaleTimeString('fr',{hour:'2-digit',minute:'2-digit'})
    return d.toLocaleDateString('fr',{day:'2-digit',month:'2-digit'})
  } catch { return '—' }
}
function dirBadge(d) { return d==='buy'?'<span class="dir-buy">↑ Buy</span>':'<span class="dir-sell">↓ Sell</span>' }
function phaseBadge(phase) {
  const map={teaser:['phase-teaser','Teaser'],open:['phase-open','Ouvert ●'],tp1_reached:['phase-tp1','TP1 ✓'],tp2_reached:['phase-tp2','TP2 ✓'],tp3_reached:['phase-tp3','TP3 ✓'],sl_touched:['phase-sl','SL ✗'],closed:['phase-closed','Clôturé'],cancelled:['phase-closed','Annulé']}
  const [cls,label]=map[phase]||['phase-closed',phase||'—']
  return `<span class="badge ${cls}" style="font-size:10px;">${label}</span>`
}

// ─── INIT ─────────────────────────────────────────────────────
async function init() {
  setGoldDir('buy')
  setConfidence(3)
  await loadCategories()
  await loadLiveDashboard()
  setInterval(()=>{
    const live = document.getElementById('view-live')
    if (live && live.style.cssText.includes('flex')) loadLiveDashboard()
  }, 60000)
}
init()
</script>
</body>
</html>