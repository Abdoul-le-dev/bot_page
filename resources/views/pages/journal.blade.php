<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TradingBot — Journal de Trading</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400" rel="stylesheet">
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Geist','sans-serif'],mono:['Geist Mono','monospace']}}}}</script>
<style>
*{box-sizing:border-box;}
body{background:#09090b;font-family:'Geist',sans-serif;}
::-webkit-scrollbar{width:3px;height:3px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:rgba(255,255,255,.07);border-radius:99px;}
.nav-item{display:flex;align-items:center;gap:9px;padding:6px 10px;border-radius:7px;font-size:13px;color:#52525b;cursor:pointer;transition:all .15s;border:none;background:none;width:100%;text-align:left;}
.nav-item:hover{color:#d4d4d8;background:rgba(255,255,255,.04);}
.nav-item.active{color:#fafafa;background:rgba(255,255,255,.07);}
.nav-item svg{width:14px;height:14px;flex-shrink:0;opacity:.7;}
.nav-item.active svg{opacity:1;}
.nav-section{font-size:10px;font-weight:500;color:#3f3f46;letter-spacing:.07em;text-transform:uppercase;padding:10px 10px 3px;}
.topbar{backdrop-filter:blur(12px);background:rgba(9,9,11,.85);}
.badge{display:inline-flex;align-items:center;padding:2px 8px;border-radius:99px;font-size:11px;font-weight:500;white-space:nowrap;}
.badge-green{background:rgba(52,211,153,.1);color:#34d399;}
.badge-sky{background:rgba(56,189,248,.1);color:#38bdf8;}
.badge-amber{background:rgba(251,191,36,.1);color:#fbbf24;}
.badge-red{background:rgba(248,113,113,.1);color:#f87171;}
.badge-violet{background:rgba(167,139,250,.1);color:#a78bfa;}
.badge-zinc{background:rgba(255,255,255,.06);color:#71717a;}
.badge-teal{background:rgba(45,212,191,.1);color:#2dd4bf;}
.input{width:100%;padding:7px 11px;font-size:13px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:8px;color:#e4e4e7;font-family:'Geist',sans-serif;outline:none;transition:border-color .15s;}
.input:focus{border-color:rgba(56,189,248,.4);}
.input::placeholder{color:#3f3f46;}
select.input{cursor:pointer;}
textarea.input{resize:vertical;}
.btn-primary{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;font-size:12px;font-weight:500;background:#38bdf8;color:#082f49;border:none;border-radius:8px;cursor:pointer;transition:background .15s;font-family:'Geist',sans-serif;white-space:nowrap;}
.btn-primary:hover{background:#7dd3fc;}
.btn-primary:disabled{opacity:.5;cursor:not-allowed;}
.btn-ghost{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;font-size:12px;background:rgba(255,255,255,.05);color:#a1a1aa;border:1px solid rgba(255,255,255,.08);border-radius:8px;cursor:pointer;transition:all .15s;font-family:'Geist',sans-serif;white-space:nowrap;}
.btn-ghost:hover{background:rgba(255,255,255,.09);color:#e4e4e7;}
.btn-icon{width:28px;height:28px;display:flex;align-items:center;justify-content:center;color:#52525b;border:none;background:rgba(255,255,255,.04);border-radius:7px;cursor:pointer;transition:all .15s;flex-shrink:0;}
.btn-icon:hover{background:rgba(255,255,255,.08);color:#d4d4d8;}
.card{background:#111113;border:1px solid rgba(255,255,255,.06);border-radius:12px;}
.tab{padding:5px 12px;font-size:12px;border-radius:7px;cursor:pointer;transition:all .15s;border:none;background:none;color:#52525b;font-family:'Geist',sans-serif;white-space:nowrap;}
.tab:hover{color:#a1a1aa;}
.tab.active{background:rgba(255,255,255,.07);color:#fafafa;}
.signal-card{background:#111113;border:1px solid rgba(255,255,255,.06);border-radius:12px;overflow:hidden;transition:all .18s;cursor:pointer;}
.signal-card:hover{border-color:rgba(255,255,255,.12);}
.signal-card.open-sig{border-color:rgba(56,189,248,.3);}
.signal-card.win{border-color:rgba(52,211,153,.25);}
.signal-card.loss{border-color:rgba(248,113,113,.2);}
.signal-accent{height:3px;width:100%;}
.tg-phone{background:#1c2733;border-radius:12px;overflow:hidden;}
.tg-bar{background:#17212b;padding:8px 12px;display:flex;align-items:center;gap:8px;}
.tg-msg-area{padding:10px 12px;display:flex;flex-direction:column;gap:6px;}
.tg-bubble{background:#1e3040;border-radius:12px 12px 12px 2px;padding:10px 13px;font-size:12px;line-height:1.65;color:#e2e8f0;max-width:92%;}
.tg-inline-btns{display:flex;flex-direction:column;gap:4px;margin-top:2px;max-width:92%;}
.tg-btn{background:rgba(56,189,248,.1);border:1px solid rgba(56,189,248,.2);color:#64b5f6;border-radius:8px;padding:8px 12px;font-size:11px;text-align:center;cursor:pointer;transition:all .15s;font-family:'Geist',sans-serif;width:100%;}
.tg-btn.green{background:rgba(52,211,153,.1);border-color:rgba(52,211,153,.25);color:#34d399;}
.tg-btn.red{background:rgba(248,113,113,.1);border-color:rgba(248,113,113,.2);color:#f87171;}
.tg-btn.amber{background:rgba(251,191,36,.1);border-color:rgba(251,191,36,.2);color:#fbbf24;}
.tg-btn.gray{background:rgba(255,255,255,.04);border-color:rgba(255,255,255,.08);color:#71717a;}
.part-bar{display:flex;height:6px;border-radius:99px;overflow:hidden;gap:1px;}
.part-seg{height:100%;transition:width .4s ease;}
.exit-bar{display:flex;flex-direction:column;gap:4px;}
.exit-row{display:flex;align-items:center;gap:8px;font-size:11px;}
.exit-track{flex:1;height:6px;background:rgba(255,255,255,.06);border-radius:99px;overflow:hidden;}
.exit-fill{height:100%;border-radius:99px;}
.modal-overlay{position:fixed;inset:0;background:rgba(0,0,0,.75);z-index:60;display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .15s;}
.modal-overlay.open{opacity:1;pointer-events:auto;}
.modal{background:#111113;border:1px solid rgba(255,255,255,.1);border-radius:14px;transform:scale(.97);transition:transform .15s;max-height:92vh;display:flex;flex-direction:column;overflow:hidden;}
.modal-overlay.open .modal{transform:scale(1);}
.drawer-overlay{position:fixed;inset:0;background:rgba(0,0,0,.6);z-index:40;opacity:0;pointer-events:none;transition:opacity .2s;}
.drawer-overlay.open{opacity:1;pointer-events:auto;}
.drawer{position:fixed;top:0;right:0;bottom:0;background:#111113;border-left:1px solid rgba(255,255,255,.08);z-index:50;transform:translateX(100%);transition:transform .22s cubic-bezier(.4,0,.2,1);display:flex;flex-direction:column;overflow:hidden;}
.drawer.open{transform:translateX(0);}
.pnl-pos{color:#34d399;font-weight:500;}
.pnl-neg{color:#f87171;font-weight:500;}
.pbar{height:3px;background:rgba(255,255,255,.06);border-radius:99px;overflow:hidden;}
.pbar-fill{height:100%;border-radius:99px;}
.stat-mini{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);border-radius:9px;padding:12px 14px;}
.member-row{display:flex;align-items:center;gap:10px;padding:8px 0;border-bottom:1px solid rgba(255,255,255,.04);}
.member-row:last-child{border-bottom:none;}
.av{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:600;flex-shrink:0;}
.av-green{background:rgba(52,211,153,.15);color:#34d399;}
.av-sky{background:rgba(56,189,248,.15);color:#38bdf8;}
.av-amber{background:rgba(251,191,36,.15);color:#fbbf24;}
.av-violet{background:rgba(167,139,250,.15);color:#a78bfa;}
.av-teal{background:rgba(45,212,191,.15);color:#2dd4bf;}
.av-red{background:rgba(248,113,113,.15);color:#f87171;}
.av-default{background:rgba(255,255,255,.07);color:#71717a;}
.ia-chip{display:inline-flex;align-items:center;gap:4px;font-size:10px;padding:2px 7px;border-radius:99px;background:rgba(45,212,191,.12);color:#2dd4bf;font-weight:500;}
.ia-card{background:rgba(45,212,191,.04);border:1px solid rgba(45,212,191,.15);border-radius:12px;padding:16px;}
.slabel{font-size:10px;font-weight:500;color:#3f3f46;letter-spacing:.06em;text-transform:uppercase;margin-bottom:8px;}
.upload-zone{border:1px dashed rgba(255,255,255,.12);border-radius:9px;padding:16px;text-align:center;cursor:pointer;transition:all .15s;}
.upload-zone:hover{border-color:rgba(56,189,248,.35);background:rgba(56,189,248,.04);}
.toggle{width:30px;height:16px;background:rgba(255,255,255,.1);border-radius:99px;position:relative;cursor:pointer;transition:background .2s;flex-shrink:0;border:none;padding:0;}
.toggle.on{background:#38bdf8;}
.toggle::after{content:'';position:absolute;width:11px;height:11px;background:white;border-radius:50%;top:2.5px;left:2.5px;transition:transform .2s;}
.toggle.on::after{transform:translateX(14px);}
.step-dot{width:7px;height:7px;border-radius:50%;background:rgba(255,255,255,.15);}
.step-dot.active{background:#38bdf8;}
.step-dot.done{background:#34d399;}
.dir-long{background:rgba(52,211,153,.12);color:#34d399;border:1px solid rgba(52,211,153,.2);padding:3px 10px;border-radius:6px;font-size:11px;font-weight:500;}
.dir-short{background:rgba(248,113,113,.12);color:#f87171;border:1px solid rgba(248,113,113,.2);padding:3px 10px;border-radius:6px;font-size:11px;font-weight:500;}
.live-dot{width:7px;height:7px;border-radius:50%;background:#38bdf8;flex-shrink:0;}
@keyframes pulse{0%,100%{opacity:1}50%{opacity:.3}}
.pulse{animation:pulse 2s ease infinite;}
@keyframes fadein{from{opacity:0;transform:translateY(4px)}to{opacity:1;transform:translateY(0)}}
.fadein{animation:fadein .2s ease;}
.chart-bar{background:rgba(56,189,248,.15);border-radius:3px 3px 0 0;transition:all .3s;cursor:pointer;}
.chart-bar.win-bar{background:rgba(52,211,153,.2);}
.chart-bar.loss-bar{background:rgba(248,113,113,.2);}
.eng-grid{display:grid;grid-template-columns:1fr 1fr 1fr;gap:6px;margin-top:8px;}
.eng-cell{padding:7px 8px;border-radius:7px;text-align:center;}
.beh-tag{display:inline-flex;align-items:center;gap:5px;padding:4px 9px;border-radius:7px;font-size:11px;font-weight:500;}
.beh-tag.disciplined{background:rgba(52,211,153,.1);color:#34d399;border:1px solid rgba(52,211,153,.2);}
.beh-tag.early_exit{background:rgba(251,191,36,.1);color:#fbbf24;border:1px solid rgba(251,191,36,.2);}
.beh-tag.sl_skip{background:rgba(248,113,113,.1);color:#f87171;border:1px solid rgba(248,113,113,.2);}
.beh-tag.passive{background:rgba(255,255,255,.05);color:#71717a;border:1px solid rgba(255,255,255,.08);}
.alert-banner{border-radius:10px;padding:14px 16px;display:flex;align-items:center;gap:12px;}
.alert-tp{background:rgba(52,211,153,.07);border:1px solid rgba(52,211,153,.25);}
.alert-sl{background:rgba(248,113,113,.07);border:1px solid rgba(248,113,113,.25);}
.tbl-row{display:flex;align-items:center;gap:10px;padding:9px 16px;border-bottom:1px solid rgba(255,255,255,.04);transition:background .1s;cursor:pointer;}
.tbl-row:hover{background:rgba(255,255,255,.02);}
.tbl-row.not-taken{opacity:.5;}
.tbl-head{display:flex;align-items:center;gap:10px;padding:8px 16px;background:rgba(255,255,255,.02);border-bottom:1px solid rgba(255,255,255,.05);}
.pair-row{display:flex;align-items:center;gap:10px;padding:10px 16px;border-bottom:1px solid rgba(255,255,255,.04);transition:background .12s;}
.pair-row:hover{background:rgba(255,255,255,.02);}
.form-card{background:#111113;border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:14px 16px;transition:border-color .15s;cursor:pointer;}
.form-card:hover{border-color:rgba(255,255,255,.12);}
.form-card.selected{border-color:rgba(167,139,250,.4);background:rgba(167,139,250,.04);}
.cap-bar{border-radius:3px;transition:height .3s;}
.cap-bar.up{background:rgba(52,211,153,.4);}
.cap-bar.down{background:rgba(248,113,113,.4);}
.cap-bar.flat{background:rgba(255,255,255,.1);}
.map-row{display:flex;align-items:center;gap:8px;padding:8px 10px;background:rgba(255,255,255,.02);border:1px solid rgba(255,255,255,.05);border-radius:8px;margin-bottom:6px;}
.calc-result{background:rgba(56,189,248,.06);border:1px solid rgba(56,189,248,.15);border-radius:8px;padding:10px 14px;}
.ticker-live{font-family:'Geist Mono',monospace;font-size:13px;font-weight:500;padding:3px 8px;border-radius:6px;}
.ticker-live.up{color:#34d399;background:rgba(52,211,153,.1);}
.ticker-live.down{color:#f87171;background:rgba(248,113,113,.1);}
.pips-dist{font-size:11px;font-family:'Geist Mono',monospace;}
.htab{padding:5px 10px;font-size:11px;border-radius:6px;cursor:pointer;border:none;background:none;color:#52525b;font-family:'Geist',sans-serif;}
.htab:hover{color:#a1a1aa;}
.htab.active{background:rgba(255,255,255,.07);color:#fafafa;}
.skel{background:rgba(255,255,255,.05);border-radius:4px;animation:skelPulse 1.4s ease infinite;}
@keyframes skelPulse{0%,100%{opacity:.5}50%{opacity:1}}
#toast{position:fixed;bottom:20px;right:20px;z-index:999;display:flex;flex-direction:column;gap:8px;}
.toast-item{padding:10px 16px;border-radius:9px;font-size:12px;font-weight:500;border:1px solid;animation:fadeInUp .2s ease;max-width:300px;}
.toast-item.success{background:rgba(52,211,153,.1);border-color:rgba(52,211,153,.3);color:#34d399;}
.toast-item.error{background:rgba(248,113,113,.1);border-color:rgba(248,113,113,.3);color:#f87171;}
.toast-item.info{background:rgba(56,189,248,.1);border-color:rgba(56,189,248,.3);color:#38bdf8;}
@keyframes fadeInUp{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
</style>
</head>
<body class="h-screen overflow-hidden text-zinc-200">
<div id="toast"></div>
<div class="flex h-full">

<!-- ─── SIDEBAR Journal─────────────────────────────────────────────────── -->
<aside style="width:208px;flex-shrink:0;background:#0d0d0f;border-right:1px solid rgba(255,255,255,.05);" class="flex flex-col h-full">
  <div class="px-4 py-4" style="border-bottom:1px solid rgba(255,255,255,.05);">
    <div class="flex items-center gap-3">
      <div style="width:30px;height:30px;background:#0ea5e9;border-radius:8px;" class="flex items-center justify-center flex-shrink-0">
        <svg width="15" height="15" fill="white" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.94 8.19l-2.02 9.52c-.14.66-.54.82-1.09.51l-3-2.21-1.45 1.39c-.16.16-.3.3-.61.3l.22-3.1 5.6-5.06c.24-.22-.06-.34-.38-.12L7.03 14.5 4.06 13.6c-.65-.2-.66-.65.14-.96l11.65-4.5c.54-.2 1.01.13.09 2.05z"/></svg>
      </div>
      <div><p class="text-sm font-medium text-white leading-none">TradingBot</p><p class="text-xs mt-0.5" style="color:#3f3f46;" id="sb-members">— membres</p></div>
    </div>
  </div>
  <nav class="flex-1 px-2 py-3 overflow-y-auto flex flex-col gap-0.5">
    <div class="nav-section">Vue d'ensemble</div>
    <button class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>Dashboard</button>
    <button class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M3 3v18h18"/><path d="m7 16 4-4 4 4 4-6"/></svg>Statistiques</button>
    <div class="nav-section" style="margin-top:6px;">Trading</div>
    <button class="nav-item active" id="nav-journal" onclick="showMainView('journal',this)"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>Journal</button>
    <button class="nav-item" id="nav-paires" onclick="showMainView('paires',this)"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M3 3v18h18"/><line x1="7" y1="17" x2="7" y2="9"/><line x1="12" y1="17" x2="12" y2="5"/><line x1="17" y1="17" x2="17" y2="12"/></svg>Paires & Pip</button>
    <button class="nav-item" id="nav-formules" onclick="showMainView('formules',this)"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="15" y2="17"/></svg>Formulaires & Collecte</button>
  </nav>
  <div class="px-4 py-3" style="border-top:1px solid rgba(255,255,255,.05);">
    <div class="flex items-center gap-2.5">
      <div style="width:27px;height:27px;border-radius:50%;background:linear-gradient(135deg,#0ea5e9,#6366f1);flex-shrink:0;"></div>
      <div class="flex-1 min-w-0"><p class="text-xs font-medium text-zinc-300 truncate">Admin</p><p class="text-[10px]" style="color:#3f3f46;">admin@tradingbot.io</p></div>
    </div>
  </div>
</aside>

<div class="flex-1 flex flex-col min-w-0 overflow-hidden" id="main-area">

<!-- ═══════════════════════════════════════════════════════════════ -->
<!-- SECTION JOURNAL                                                -->
<!-- ═══════════════════════════════════════════════════════════════ -->
<!-- BUG FIX : display:flex sans !important pour permettre showMainView() -->
<div id="main-journal" style="display:flex;flex-direction:column;height:100%;">
  <header class="topbar flex-shrink-0 flex items-center justify-between px-6" style="height:52px;border-bottom:1px solid rgba(255,255,255,.05);">
    <div class="flex items-center gap-3">
      <h1 class="text-sm font-medium text-white">Journal de trading</h1>
      <span style="color:#27272a;">·</span>
      <div class="flex items-center gap-0.5" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:8px;padding:2px;">
        <button class="tab active" onclick="switchView('journal',this)">Signaux</button>
        <button class="tab" onclick="switchView('history',this)">Historique</button>
        <button class="tab" onclick="switchView('members',this)">Performances</button>
        <button class="tab" onclick="switchView('leaderboard',this)">Classement</button>
        <button class="tab" onclick="switchView('ia',this)">Bilan IA</button>
      </div>
    </div>
    <div class="flex items-center gap-2">
      <select class="input" id="period-select" style="width:130px;font-size:11px;padding:5px 9px;" onchange="loadDashboardStats()">
        <option value="week">Cette semaine</option>
        <option value="month" selected>Ce mois</option>
        <option value="all">Tout</option>
      </select>
      <button class="btn-primary" onclick="openModal('modal-publish')">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
        Publier un trade
      </button>
    </div>
  </header>

  <main class="flex-1 overflow-y-auto p-5 flex flex-col gap-4">

    <!-- ══ VUE SIGNAUX ════════════════════════════════════════════ -->
    <div id="view-journal">
      <div class="alert-banner alert-tp mb-4" id="alert-banner" style="display:none;">
        <div class="live-dot pulse" style="background:#34d399;flex-shrink:0;"></div>
        <div class="flex-1" id="alert-content"></div>
        <div class="flex items-center gap-2" id="alert-actions"></div>
      </div>

      <div class="grid grid-cols-5 gap-3 mb-4" id="stats-grid">
        <div class="stat-mini"><div class="skel h-3 w-16 mb-2"></div><div class="skel h-7 w-10"></div></div>
        <div class="stat-mini"><div class="skel h-3 w-16 mb-2"></div><div class="skel h-7 w-10"></div></div>
        <div class="stat-mini"><div class="skel h-3 w-16 mb-2"></div><div class="skel h-7 w-10"></div></div>
        <div class="stat-mini"><div class="skel h-3 w-16 mb-2"></div><div class="skel h-7 w-10"></div></div>
        <div class="stat-mini"><div class="skel h-3 w-16 mb-2"></div><div class="skel h-7 w-10"></div></div>
      </div>

      <div class="card p-5 mb-4">
        <div class="flex items-center justify-between mb-4">
          <p class="text-sm font-medium text-white">Performance hebdomadaire</p>
          <div class="flex items-center gap-4 text-[11px]" style="color:#71717a;">
            <span class="flex items-center gap-1.5"><span style="width:8px;height:8px;border-radius:2px;background:rgba(52,211,153,.3);display:inline-block;"></span>Win</span>
            <span class="flex items-center gap-1.5"><span style="width:8px;height:8px;border-radius:2px;background:rgba(248,113,113,.3);display:inline-block;"></span>Loss</span>
          </div>
        </div>
        <div class="flex items-end gap-2" style="height:70px;" id="weekly-chart">
          <div class="text-center w-full text-xs" style="color:#3f3f46;padding-top:20px;">Chargement...</div>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-4" id="signals-grid">
        <div class="signal-card" style="height:200px;"><div class="p-4"><div class="skel h-4 w-24 mb-2"></div><div class="skel h-3 w-32 mb-4"></div><div class="skel h-16 w-full"></div></div></div>
        <div class="signal-card" style="height:200px;"><div class="p-4"><div class="skel h-4 w-24 mb-2"></div><div class="skel h-3 w-32 mb-4"></div><div class="skel h-16 w-full"></div></div></div>
      </div>
    </div>

    <!-- ══ VUE HISTORIQUE ══════════════════════════════════════════ -->
    <div id="view-history" style="display:none;">
      <div class="card p-5 mb-4">
        <div class="flex items-center justify-between mb-4">
          <div class="flex items-center gap-3">
            <p class="text-sm font-medium text-white">Performance croisée</p>
            <div class="flex items-center gap-0.5" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:7px;padding:2px;">
              <button class="htab active" onclick="setChartPeriod('day',this)">Jour</button>
              <button class="htab" onclick="setChartPeriod('week',this)">Semaine</button>
              <button class="htab" onclick="setChartPeriod('month',this)">Mois</button>
            </div>
          </div>
          <div class="flex items-center gap-3">
            <select class="input" id="hist-pair-filter" style="width:120px;font-size:11px;padding:4px 8px;" onchange="loadCrossedPerf()"><option value="">Toutes paires</option></select>
            <div class="flex items-center gap-3 text-[10px]" style="color:#71717a;">
              <span class="flex items-center gap-1.5"><span style="width:20px;height:2px;background:rgba(56,189,248,.6);display:inline-block;border-radius:99px;border-top:1.5px dashed #38bdf8;"></span>Admin</span>
              <span class="flex items-center gap-1.5"><span style="width:20px;height:2px;background:#34d399;display:inline-block;border-radius:99px;"></span>Membres</span>
            </div>
          </div>
        </div>
        <div style="position:relative;height:180px;" id="perf-chart-container">
          <div class="text-center text-xs pt-16" style="color:#3f3f46;">Chargement du graphique...</div>
        </div>
      </div>

      <div class="card overflow-hidden">
        <div class="flex items-center justify-between p-4" style="border-bottom:1px solid rgba(255,255,255,.05);">
          <div class="flex items-center gap-2">
            <div class="flex items-center gap-0.5" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:7px;padding:2px;">
              <button class="htab active" onclick="setHistoryTab('all',this)">Tous</button>
              <button class="htab" onclick="setHistoryTab('took',this)">A pris</button>
              <button class="htab" onclick="setHistoryTab('skip',this)">N'a pas pris</button>
            </div>
            <select class="input" id="hist-status-filter" style="width:110px;font-size:11px;padding:4px 8px;" onchange="loadHistory()">
              <option value="">Tous statuts</option><option>TP</option><option>SL</option><option>Partiel</option><option>Ouvert</option>
            </select>
          </div>
          <div class="relative" style="width:200px;">
            <svg width="11" height="11" fill="none" stroke="#3f3f46" viewBox="0 0 24 24" stroke-width="2" style="position:absolute;left:8px;top:50%;transform:translateY(-50%);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input class="input" type="text" id="hist-search" placeholder="Recherche..." style="padding-left:26px;font-size:11px;" oninput="debounceSearch()">
          </div>
        </div>
        <div class="tbl-head">
          <span class="text-[10px] font-medium" style="color:#3f3f46;flex:1;">Membre</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:90px;">Signal</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:70px;">Entrée</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:70px;">Sortie</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:55px;">Pips</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:70px;">Gain $</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:80px;">Capital après</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:100px;">Comportement</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:36px;">📸</span>
        </div>
        <div id="history-table-body"><div class="p-8 text-center text-xs" style="color:#3f3f46;">Chargement...</div></div>
        <div class="flex items-center justify-between px-4 py-3" style="border-top:1px solid rgba(255,255,255,.04);background:rgba(255,255,255,.01);">
          <span class="text-xs" id="hist-pagination-label" style="color:#52525b;">—</span>
          <div class="flex items-center gap-1.5">
            <button class="btn-icon" id="hist-prev" onclick="histPage(-1)" disabled><svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></button>
            <span class="text-xs px-2" id="hist-page-label" style="color:#71717a;">1</span>
            <button class="btn-icon" id="hist-next" onclick="histPage(1)"><svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></button>
          </div>
        </div>
      </div>
    </div>

    <!-- ══ VUE PERFORMANCES ════════════════════════════════════════ -->
    <div id="view-members" style="display:none;">
      <div class="flex items-center gap-3 mb-4">
        <div class="relative" style="width:240px;">
          <svg width="12" height="12" fill="none" stroke="#3f3f46" viewBox="0 0 24 24" stroke-width="2" style="position:absolute;left:9px;top:50%;transform:translateY(-50%);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          <input class="input" type="text" id="perf-search" placeholder="Chercher un membre..." style="padding-left:28px;font-size:12px;" oninput="debouncePerf()">
        </div>
        <select class="input" id="perf-sort" style="width:180px;font-size:12px;" onchange="loadPerformances()">
          <option value="win_rate">Trier : Win rate ↓</option>
          <option value="discipline">Trier : Discipline ↓</option>
          <option value="engagement">Trier : Engagement ↓</option>
          <option value="capital">Trier : Capital ↓</option>
          <option value="perf">Trier : Perf totale ↓</option>
        </select>
      </div>
      <div class="card overflow-hidden">
        <div class="flex items-center gap-4 px-4 py-3" style="background:rgba(255,255,255,.02);border-bottom:1px solid rgba(255,255,255,.05);">
          <span class="text-[10px] font-medium flex-1" style="color:#3f3f46;">Membre</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:80px;">Capital</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:60px;">Trades</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:70px;">Engagement</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:70px;">Win rate</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:80px;">Perf. totale</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:110px;">Comportement</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:60px;">Suivi</span>
          <span style="width:28px;"></span>
        </div>
        <div id="performances-body"><div class="p-8 text-center text-xs" style="color:#3f3f46;">Chargement...</div></div>
        <div class="flex items-center justify-between px-4 py-3" style="border-top:1px solid rgba(255,255,255,.04);">
          <span class="text-xs" id="perf-pagination-label" style="color:#52525b;">—</span>
          <div class="flex items-center gap-1.5">
            <button class="btn-icon" id="perf-prev" onclick="perfPage(-1)" disabled><svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg></button>
            <span class="text-xs px-2" id="perf-page-label" style="color:#71717a;">1</span>
            <button class="btn-icon" id="perf-next" onclick="perfPage(1)"><svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg></button>
          </div>
        </div>
      </div>
    </div>

    <!-- ══ VUE CLASSEMENT ══════════════════════════════════════════ -->
    <div id="view-leaderboard" style="display:none;" class="flex flex-col gap-4">
      <div class="flex items-center gap-2">
        <div class="flex items-center gap-0.5" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:7px;padding:2px;">
          <button class="htab active" onclick="setLeaderPeriod('all',this)">Tout temps</button>
          <button class="htab" onclick="setLeaderPeriod('month',this)">Ce mois</button>
          <button class="htab" onclick="setLeaderPeriod('week',this)">Cette semaine</button>
        </div>
      </div>
      <div id="podium-row" class="grid grid-cols-3 gap-3 mb-2"></div>
      <div class="card overflow-hidden">
        <div class="flex items-center gap-4 px-4 py-3" style="background:rgba(255,255,255,.02);border-bottom:1px solid rgba(255,255,255,.05);">
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:30px;">#</span>
          <span class="text-[10px] font-medium flex-1" style="color:#3f3f46;">Membre</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:70px;">Capital</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:60px;">Trades</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:70px;">Engagement</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:70px;">Win rate</span>
          <span class="text-[10px] font-medium" style="color:#3f3f46;width:90px;">Perf. totale</span>
        </div>
        <div id="leaderboard-body"><div class="p-8 text-center text-xs" style="color:#3f3f46;">Chargement...</div></div>
      </div>
    </div>

    <!-- ══ VUE BILAN IA ══════════════════════════════════════════════ -->
    <div id="view-ia" style="display:none;" class="flex flex-col gap-4">
      <div class="grid grid-cols-2 gap-4">
        <div class="flex flex-col gap-4">
          <div class="ia-card">
            <div class="flex items-center gap-2 mb-4"><span class="ia-chip">Agent IA</span><p class="text-sm font-medium text-white">Bilan hebdomadaire personnalisé</p></div>
            <div class="flex flex-col gap-3 mb-4">
              <div>
                <p class="text-[10px] mb-1.5" style="color:#52525b;">Semaine</p>
                <select class="input" id="ia-week" style="font-size:12px;" onchange="updateWeekDates()"></select>
              </div>
              <div>
                <p class="text-[10px] mb-1.5" style="color:#52525b;">Envoyer à</p>
                <select class="input" id="ia-target" style="font-size:12px;">
                  <option value="journalised">Membres ayant journalisé ≥1 trade</option>
                  <option value="clients_actifs">Clients actifs</option>
                  <option value="all">Tous</option>
                </select>
              </div>
              <div>
                <p class="text-[10px] mb-2" style="color:#52525b;">Inclure dans le bilan</p>
                <div class="flex flex-col gap-1.5">
                  <label class="flex items-center gap-2 text-xs text-zinc-400 cursor-pointer"><input type="checkbox" id="ia-cfg-perf" checked style="accent-color:#38bdf8;"> Résumé performance</label>
                  <label class="flex items-center gap-2 text-xs text-zinc-400 cursor-pointer"><input type="checkbox" id="ia-cfg-beh" checked style="accent-color:#38bdf8;"> Analyse comportement</label>
                  <label class="flex items-center gap-2 text-xs text-zinc-400 cursor-pointer"><input type="checkbox" id="ia-cfg-reco" checked style="accent-color:#38bdf8;"> Recommandations</label>
                  <label class="flex items-center gap-2 text-xs text-zinc-400 cursor-pointer"><input type="checkbox" id="ia-cfg-comp" style="accent-color:#38bdf8;"> Comparaison admin</label>
                </div>
              </div>
            </div>
            <button class="btn-primary w-full justify-center" id="btn-generate-bilan" onclick="generateBilanPreview()">
              <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/></svg>
              Générer un aperçu
            </button>
          </div>
          <div class="card p-4">
            <div class="flex items-center justify-between mb-3">
              <p class="text-xs font-medium text-zinc-300">Aperçu bilan</p>
              <span class="ia-chip" id="ia-status">En attente</span>
            </div>
            <div id="ia-preview-box" style="background:rgba(255,255,255,.025);border:1px solid rgba(255,255,255,.06);border-radius:8px;padding:12px;font-size:12px;line-height:1.7;color:#52525b;min-height:130px;font-style:italic;">Cliquer sur "Générer" pour voir un exemple...</div>
            <div class="flex gap-2 mt-3">
              <button class="btn-ghost flex-1 justify-center" style="font-size:11px;" onclick="generateBilanPreview()">Régénérer</button>
              <button class="btn-primary flex-1 justify-center" style="font-size:11px;" onclick="sendBilanToAll()">Envoyer à tous →</button>
            </div>
          </div>
        </div>
        <div class="flex flex-col gap-3">
          <div class="card p-4">
            <p class="text-sm font-medium text-white mb-4">Bilans envoyés</p>
            <div id="bilan-history-list" class="flex flex-col gap-2">
              <div class="text-xs" style="color:#3f3f46;">Chargement...</div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </main>
</div><!-- end main-journal -->

<!-- ═══════════════════════════════════════════════════════════════ -->
<!-- SECTION PAIRES & PIP                                           -->
<!-- BUG FIX : display:none sans !important                        -->
<!-- ═══════════════════════════════════════════════════════════════ -->
<div id="main-paires" style="display:none;flex-direction:column;height:100%;">
  <header class="topbar flex-shrink-0 flex items-center justify-between px-6" style="height:52px;border-bottom:1px solid rgba(255,255,255,.05);">
    <h1 class="text-sm font-medium text-white">Paires & Pip</h1>
    <button class="btn-primary" onclick="openModal('modal-add-pair')">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      Ajouter une paire
    </button>
  </header>
  <main class="flex-1 overflow-y-auto p-5 flex flex-col gap-4">
    <div class="card p-5">
      <div class="flex items-center gap-2 mb-4"><svg width="14" height="14" fill="none" stroke="#38bdf8" viewBox="0 0 24 24" stroke-width="1.5"><rect x="4" y="2" width="16" height="20" rx="2"/><path d="M8 10h8M8 14h4"/><circle cx="16" cy="16" r="4"/><path d="M16 14v2l1 1"/></svg><p class="text-sm font-medium text-white">Calculateur de lot</p></div>
      <div class="grid grid-cols-5 gap-3 mb-4">
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Capital ($)</p><input class="input" type="number" value="1000" id="calc-capital" oninput="calcLot()" style="font-family:'Geist Mono',monospace;"></div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Risque %</p><input class="input" type="number" value="2" id="calc-risk" oninput="calcLot()" style="font-family:'Geist Mono',monospace;"></div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">SL en pips</p><input class="input" type="number" value="42" id="calc-sl-pips" oninput="calcLot()" style="font-family:'Geist Mono',monospace;"></div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Paire</p>
          <select class="input" id="calc-pair-sel" onchange="calcLot()">
            <option value="10" data-sym="EUR/USD">EUR/USD (10$/pip)</option>
            <option value="10" data-sym="GBP/USD">GBP/USD (10$/pip)</option>
            <option value="1" data-sym="XAU/USD">XAU/USD (1$/pip)</option>
            <option value="1" data-sym="BTC/USD">BTC/USD (1$/pip)</option>
            <option value="8.2" data-sym="GBP/JPY">GBP/JPY (8.2$/pip)</option>
          </select>
        </div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">TP1 en pips</p><input class="input" type="number" value="78" id="calc-tp-pips" oninput="calcLot()" style="font-family:'Geist Mono',monospace;"></div>
      </div>
      <div class="grid grid-cols-4 gap-3">
        <div class="calc-result"><p class="text-[10px] mb-1" style="color:#38bdf8;">Risque $</p><p class="text-lg font-light tabular-nums text-white" id="res-risk-usd">—</p></div>
        <div class="calc-result"><p class="text-[10px] mb-1" style="color:#38bdf8;">Lot suggéré</p><p class="text-lg font-light tabular-nums text-white" id="res-lot">—</p></div>
        <div class="calc-result"><p class="text-[10px] mb-1" style="color:#f87171;">Max loss si SL</p><p class="text-lg font-light tabular-nums pnl-neg" id="res-loss">—</p></div>
        <div class="calc-result"><p class="text-[10px] mb-1" style="color:#34d399;">Gain potentiel TP1</p><p class="text-lg font-light tabular-nums pnl-pos" id="res-gain">—</p></div>
      </div>
    </div>
    <div class="card overflow-hidden">
      <div class="flex items-center gap-4 px-4 py-3" style="background:rgba(255,255,255,.02);border-bottom:1px solid rgba(255,255,255,.05);">
        <span class="text-[10px] font-medium flex-1" style="color:#3f3f46;">Symbole</span>
        <span class="text-[10px] font-medium" style="color:#3f3f46;width:90px;">Catégorie</span>
        <span class="text-[10px] font-medium" style="color:#3f3f46;width:110px;">Valeur pip</span>
        <span class="text-[10px] font-medium" style="color:#3f3f46;width:80px;">Décimales</span>
        <span class="text-[10px] font-medium" style="color:#3f3f46;width:120px;">Binance</span>
        <span class="text-[10px] font-medium" style="color:#3f3f46;width:80px;">Statut</span>
        <span style="width:60px;"></span>
      </div>
      <div id="pairs-table-body"><div class="p-6 text-center text-xs" style="color:#3f3f46;">Chargement...</div></div>
    </div>
  </main>
</div><!-- end main-paires -->

<!-- ═══════════════════════════════════════════════════════════════ -->
<!-- SECTION FORMULAIRES & COLLECTE                                 -->
<!-- BUG FIX : display:none sans !important                        -->
<!-- ═══════════════════════════════════════════════════════════════ -->
<div id="main-formules" style="display:none;flex-direction:column;height:100%;">
  <header class="topbar flex-shrink-0 flex items-center justify-between px-6" style="height:52px;border-bottom:1px solid rgba(255,255,255,.05);">
    <h1 class="text-sm font-medium text-white">Formulaires & Collecte</h1>
    <button class="btn-primary" onclick="openModal('modal-create-form')">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      Créer un formulaire
    </button>
  </header>
  <main class="flex-1 overflow-y-auto p-5">
    <div class="grid grid-cols-3 gap-4 mb-5" id="forms-stats-grid">
      <div class="stat-mini"><div class="skel h-3 w-24 mb-2"></div><div class="skel h-7 w-10"></div></div>
      <div class="stat-mini"><div class="skel h-3 w-24 mb-2"></div><div class="skel h-7 w-10"></div></div>
      <div class="stat-mini"><div class="skel h-3 w-24 mb-2"></div><div class="skel h-7 w-10"></div></div>
    </div>
    <div class="grid grid-cols-2 gap-4">
      <div class="flex flex-col gap-3">
        <p class="text-xs font-medium text-zinc-400">Formulaires</p>
        <div id="forms-list"><div class="text-xs" style="color:#3f3f46;">Chargement...</div></div>
      </div>
      <div class="flex flex-col gap-4">
        <div class="card p-4" id="mapping-panel">
          <p class="text-xs font-medium text-zinc-400 mb-4">Sélectionner un formulaire pour voir son mapping</p>
        </div>
        <div class="card p-4">
          <p class="text-xs font-medium text-zinc-300 mb-3">Statistiques alimentées</p>
          <div id="forms-summary-table"><div class="text-xs" style="color:#3f3f46;">Chargement...</div></div>
        </div>
      </div>
    </div>
  </main>
</div><!-- end main-formules -->

</div><!-- end main-area -->
</div><!-- end flex h-full -->

<!-- ════════════════════════════════════════════════════════════════ -->
<!-- MODAL : PUBLIER UN TRADE                                        -->
<!-- ════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-publish">
  <div class="modal" style="width:620px;">
    <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
      <div><p class="text-sm font-medium text-white">Publier un signal</p><p class="text-[11px] mt-0.5" style="color:#52525b;">Signal → broadcast Telegram + boutons participation</p></div>
      <button class="btn-icon" onclick="closeModal('modal-publish')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="flex items-center gap-2 px-6 py-3" style="border-bottom:1px solid rgba(255,255,255,.05);">
      <div class="flex items-center gap-2"><div class="step-dot active" id="sdot-1"></div><span class="text-xs" style="color:#38bdf8;" id="slbl-1">1 · Signal</span></div>
      <div style="flex:1;height:1px;background:rgba(255,255,255,.06);"></div>
      <div class="flex items-center gap-2"><div class="step-dot" id="sdot-2"></div><span class="text-xs" style="color:#3f3f46;" id="slbl-2">2 · Diffusion</span></div>
      <div style="flex:1;height:1px;background:rgba(255,255,255,.06);"></div>
      <div class="flex items-center gap-2"><div class="step-dot" id="sdot-3"></div><span class="text-xs" style="color:#3f3f46;" id="slbl-3">3 · Aperçu</span></div>
    </div>

    <!-- Étape 1 -->
    <div id="pub-s1" class="px-6 py-5 overflow-y-auto" style="max-height:55vh;">
      <div class="grid grid-cols-2 gap-3 mb-3">
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Paire</p>
          <select class="input" id="sig-pair" onchange="updatePreview();calcRR()">
            <option>EUR/USD</option><option>GBP/USD</option><option>XAU/USD</option><option>BTC/USD</option><option>GBP/JPY</option><option>NAS100</option>
          </select>
        </div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Direction</p>
          <div class="flex gap-2">
            <button id="dir-long" onclick="setDir('long')" style="flex:1;padding:7px;border-radius:8px;cursor:pointer;font-size:12px;font-family:'Geist',sans-serif;border:1px solid rgba(52,211,153,.3);background:rgba(52,211,153,.1);color:#34d399;font-weight:500;">📈 LONG</button>
            <button id="dir-short" onclick="setDir('short')" style="flex:1;padding:7px;border-radius:8px;cursor:pointer;font-size:12px;font-family:'Geist',sans-serif;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:#71717a;">📉 SHORT</button>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3 mb-3">
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Prix d'entrée *</p><input class="input" type="text" id="sig-entry" placeholder="ex: 1.0842" style="font-family:'Geist Mono',monospace;" oninput="updatePreview();calcRR()"></div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Timeframe</p><select class="input" id="sig-tf"><option>M15</option><option>M30</option><option>H1</option><option selected>H4</option><option>D1</option></select></div>
      </div>
      <div class="grid grid-cols-3 gap-3 mb-3">
        <div><p class="text-[10px] mb-1.5" style="color:#34d399;">Take Profit 1</p><input class="input" type="text" id="sig-tp1" placeholder="ex: 1.0920" style="font-family:'Geist Mono',monospace;border-color:rgba(52,211,153,.2);" oninput="updatePreview();calcRR()"></div>
        <div><p class="text-[10px] mb-1.5" style="color:#34d399;opacity:.7;">TP2 (optionnel)</p><input class="input" type="text" id="sig-tp2" placeholder="ex: 1.0960" style="font-family:'Geist Mono',monospace;"></div>
        <div><p class="text-[10px] mb-1.5" style="color:#f87171;">Stop Loss</p><input class="input" type="text" id="sig-sl" placeholder="ex: 1.0800" style="font-family:'Geist Mono',monospace;border-color:rgba(248,113,113,.2);" oninput="updatePreview();calcRR()"></div>
      </div>
      <div class="mb-3">
        <div class="flex items-center justify-between mb-1.5"><p class="text-[10px]" style="color:#52525b;">Analyse / Note</p><span class="text-[10px] font-medium" id="rr-display" style="color:#38bdf8;">R:R —</span></div>
        <textarea class="input" id="sig-note" style="min-height:56px;font-size:12px;" placeholder="Setup, contexte, invalidation..." oninput="updatePreview()"></textarea>
      </div>

      <!-- Zone upload média -->
      <div class="mb-3">
        <p class="text-[10px] mb-1.5" style="color:#52525b;">Screenshot / Vidéo (optionnel)</p>
        <div id="media-upload">
          <!-- BUG FIX : upload-zone initialisée par resetUploadZone() au démarrage -->
          <div class="upload-zone" onclick="triggerUpload()">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 stroke-width="1.5" style="margin:0 auto 6px;color:#52525b;">
              <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
              <polyline points="17 8 12 3 7 8"/>
              <line x1="12" y1="3" x2="12" y2="15"/>
            </svg>
            <p class="text-xs" style="color:#71717a;">Glisser un fichier ou <span style="color:#38bdf8;cursor:pointer;">parcourir</span></p>
            <p class="text-[10px] mt-1" style="color:#3f3f46;">Image (max 10 MB) · Vidéo (max 50 MB)</p>
          </div>
        </div>
      </div>

      <div style="background:rgba(56,189,248,.04);border:1px solid rgba(56,189,248,.1);border-radius:8px;padding:10px 12px;margin-bottom:12px;">
        <p class="text-[10px] font-medium mb-1" style="color:#38bdf8;">Lot suggéré (capital moyen membres · risque 2%)</p>
        <p class="text-xs" style="color:#71717a;">Capital moyen : <span id="avg-cap-display" style="color:#e4e4e7;">—</span> · Risque 2% : <span id="risk-usd-display" style="color:#e4e4e7;">—</span> · SL : <span id="lot-sl-display" style="color:#e4e4e7;">—</span> · <strong style="color:#38bdf8;">Lot : <span id="lot-suggested">—</span></strong></p>
      </div>
    </div>

    <!-- Étape 2 -->
    <div id="pub-s2" class="px-6 py-5 overflow-y-auto" style="max-height:55vh;display:none;">
      <!-- BUG FIX : id="dest-block-category" avec <select> à l'intérieur -->
      <div class="mb-4" id="dest-block-category">
        <p class="text-[10px] mb-2" style="color:#52525b;">Catégories destinataires</p>
        <select class="input mb-3">
          <option value="">Sélectionner une catégorie...</option>
        </select>
      </div>
      <div style="padding:12px;background:rgba(52,211,153,.04);border:1px solid rgba(52,211,153,.12);border-radius:9px;margin-bottom:12px;">
        <p class="text-xs font-medium text-zinc-200 mb-2">Boutons de participation inclus automatiquement</p>
        <div class="flex gap-2 mb-2">
          <div class="tg-btn green" style="flex:1;font-size:11px;">✅ Je suis dans ce trade</div>
          <div class="tg-btn red" style="flex:1;font-size:11px;">❌ Je ne prends pas</div>
        </div>
      </div>
      <div style="padding:10px 12px;background:rgba(251,191,36,.04);border:1px solid rgba(251,191,36,.15);border-radius:8px;">
        <p class="text-[10px] font-medium mb-1" style="color:#fbbf24;">Message de gestion du risque personnalisé</p>
        <p class="text-[11px]" style="color:#71717a;">Chaque membre recevra son lot suggéré calculé individuellement selon son capital déclaré.</p>
      </div>
    </div>

    <!-- Étape 3 -->
    <div id="pub-s3" class="px-6 py-5 overflow-y-auto" style="max-height:55vh;display:none;">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <p class="text-xs font-medium text-zinc-300 mb-3">Aperçu Telegram</p>
          <div class="tg-phone">
            <div class="tg-bar"><div style="width:26px;height:26px;border-radius:50%;background:#0ea5e9;display:flex;align-items:center;justify-content:center;"><svg width="13" height="13" fill="white" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.94 8.19l-2.02 9.52c-.14.66-.54.82-1.09.51l-3-2.21-1.45 1.39c-.16.16-.3.3-.61.3l.22-3.1 5.6-5.06c.24-.22-.06-.34-.38-.12L7.03 14.5 4.06 13.6c-.65-.2-.66-.65.14-.96l11.65-4.5c.54-.2 1.01.13.09 2.05z"/></svg></div><p style="font-size:11px;font-weight:600;color:#e2e8f0;">TradingBot</p></div>
            <div class="tg-msg-area">
              <div class="tg-bubble" id="tg-preview-msg">📊 <b>Signal de Trading</b><br><br>Remplis les champs pour voir l'aperçu...</div>
              <div class="tg-inline-btns"><div class="tg-btn green">✅ Je suis dans ce trade</div><div class="tg-btn red">❌ Je ne prends pas</div></div>
            </div>
          </div>
        </div>
        <div>
          <p class="text-xs font-medium text-zinc-300 mb-3">Résumé</p>
          <div style="background:rgba(52,211,153,.04);border:1px solid rgba(52,211,153,.12);border-radius:9px;padding:12px;">
            <p class="text-xs font-medium text-zinc-200 mb-1.5">✓ Prêt à publier</p>
            <div class="flex flex-col gap-1">
              <p class="text-[11px]" style="color:#52525b;">→ Signal enregistré en base</p>
              <p class="text-[11px]" style="color:#52525b;">→ Broadcast via broadcast_engine</p>
              <p class="text-[11px]" style="color:#52525b;">→ Boutons inline Telegram attachés</p>
              <p class="text-[11px]" style="color:#52525b;">→ Message risque personnalisé / membre</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex items-center justify-between px-6 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-ghost" id="btn-prev" onclick="prevStep()" style="display:none;">← Retour</button>
      <div class="flex-1"></div>
      <button class="btn-ghost" onclick="closeModal('modal-publish')">Annuler</button>
      <button class="btn-primary ml-2" id="btn-next" onclick="nextStep()">Continuer →</button>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════════════════ -->
<!-- MODAL : COMMENTAIRE DE SUIVI                                   -->
<!-- ════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-followup">
  <div class="modal" style="width:560px;">
    <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
      <div><p class="text-sm font-medium text-white">Commentaire de suivi</p><p class="text-[11px] mt-0.5" style="color:#52525b;" id="followup-subtitle">Envoyé uniquement aux membres "Je suis dedans"</p></div>
      <button class="btn-icon" onclick="closeModal('modal-followup')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="px-6 py-5 flex flex-col gap-4">
      <div>
        <p class="text-[10px] mb-2" style="color:#52525b;">Type de message</p>
        <div class="flex gap-2 flex-wrap">
          <button id="fu-update" onclick="setFollowupType('update')" style="padding:6px 12px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid rgba(56,189,248,.3);background:rgba(56,189,248,.1);color:#38bdf8;font-weight:500;">🔔 Mise à jour</button>
          <button id="fu-invalidation" onclick="setFollowupType('invalidation')" style="padding:6px 12px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:#71717a;">⚠️ Invalidation</button>
          <button id="fu-secure" onclick="setFollowupType('secure')" style="padding:6px 12px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:#71717a;">🔒 Sécuriser</button>
          <button id="fu-encourage" onclick="setFollowupType('encourage')" style="padding:6px 12px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.04);color:#71717a;">💪 Encouragement</button>
        </div>
      </div>
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Message</p><textarea class="input" id="followup-text" style="min-height:70px;font-size:12px;" placeholder="Le prix approche de la zone de résistance..." oninput="updateFollowupPreview()"></textarea></div>
      <div>
        <p class="text-[10px] mb-2" style="color:#52525b;">Aperçu Telegram</p>
        <div class="tg-phone"><div class="tg-bar"><div style="width:22px;height:22px;border-radius:50%;background:#0ea5e9;display:flex;align-items:center;justify-content:center;"><svg width="11" height="11" fill="white" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.94 8.19l-2.02 9.52c-.14.66-.54.82-1.09.51l-3-2.21-1.45 1.39c-.16.16-.3.3-.61.3l.22-3.1 5.6-5.06c.24-.22-.06-.34-.38-.12L7.03 14.5 4.06 13.6c-.65-.2-.66-.65.14-.96l11.65-4.5c.54-.2 1.01.13.09 2.05z"/></svg></div><p style="font-size:10px;font-weight:600;color:#e2e8f0;">TradingBot</p></div>
        <div class="tg-msg-area"><div class="tg-bubble" id="fu-preview">🔔 <b>Mise à jour</b><br><br>Saisissez votre message...</div></div></div>
      </div>
    </div>
    <div class="flex items-center justify-end gap-2 px-6 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-ghost" onclick="closeModal('modal-followup')">Annuler</button>
      <button class="btn-primary" id="btn-send-followup" onclick="sendFollowup()">Envoyer →</button>
    </div>
  </div>
</div>

<!-- ════════════════════════════════════════════════════════════════ -->
<!-- MODAL : CLÔTURER + FORMULAIRE                                  -->
<!-- ════════════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-close-trade">
  <div class="modal" style="width:500px;">
    <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
      <div><p class="text-sm font-medium text-white">Clôturer le trade</p><p class="text-[11px] mt-0.5" style="color:#52525b;" id="close-modal-subtitle">—</p></div>
      <button class="btn-icon" onclick="closeModal('modal-close-trade')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="px-6 py-5 overflow-y-auto flex flex-col gap-4" style="max-height:70vh;">
      <div>
        <p class="text-[10px] mb-2" style="color:#52525b;">Résultat du trade</p>
        <div class="flex gap-2">
          <button id="close-tp" onclick="setCloseStatus('tp')" style="flex:1;padding:9px 6px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid rgba(52,211,153,.3);background:rgba(52,211,153,.1);color:#34d399;font-weight:500;">✅ TP atteint</button>
          <button id="close-sl" onclick="setCloseStatus('sl')" style="flex:1;padding:9px 6px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid rgba(255,255,255,.07);background:rgba(255,255,255,.03);color:#71717a;">❌ SL touché</button>
          <button id="close-partial" onclick="setCloseStatus('partial')" style="flex:1;padding:9px 6px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid rgba(255,255,255,.07);background:rgba(255,255,255,.03);color:#71717a;">⚡ Partiel</button>
          <!-- BUG FIX : id="close-cancelled" (corrigé depuis close-cancel) -->
          <button id="close-cancelled" onclick="setCloseStatus('cancelled')" style="flex:1;padding:9px 6px;border-radius:8px;cursor:pointer;font-size:11px;font-family:'Geist',sans-serif;border:1px solid rgba(255,255,255,.07);background:rgba(255,255,255,.03);color:#71717a;">🚫 Annulé</button>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3" id="price-block">
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Prix de clôture réel</p><input class="input" type="text" id="close-price" placeholder="ex: 1.0920" style="font-family:'Geist Mono',monospace;" oninput="calcPnL()"></div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Résultat calculé</p>
          <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:8px;padding:7px 12px;display:flex;align-items:center;gap:8px;">
            <span class="text-sm font-medium tabular-nums" id="calc-pnl" style="color:#52525b;">—</span>
            <span class="text-xs" id="calc-pct" style="color:#52525b;"></span>
          </div>
        </div>
      </div>
      <div style="background:rgba(167,139,250,.05);border:1px solid rgba(167,139,250,.18);border-radius:10px;padding:14px;">
        <div class="flex items-center gap-2 mb-3">
          <p class="text-xs font-medium" style="color:#a78bfa;">Formulaire de collecte</p>
        </div>
        <div id="form-msg-preview" class="tg-phone mb-3">
          <div class="tg-bar"><div style="width:22px;height:22px;border-radius:50%;background:#0ea5e9;display:flex;align-items:center;justify-content:center;"><svg width="11" height="11" fill="white" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.94 8.19l-2.02 9.52c-.14.66-.54.82-1.09.51l-3-2.21-1.45 1.39c-.16.16-.3.3-.61.3l.22-3.1 5.6-5.06c.24-.22-.06-.34-.38-.12L7.03 14.5 4.06 13.6c-.65-.2-.66-.65.14-.96l11.65-4.5c.54-.2 1.01.13.09 2.05z"/></svg></div><p style="font-size:10px;font-weight:600;color:#e2e8f0;">TradingBot</p></div>
          <div class="tg-msg-area"><div class="tg-bubble" id="form-bbl">Sélectionne un résultat...</div><div class="tg-inline-btns" id="form-btns"></div></div>
        </div>
        <div class="mb-3"><p class="text-[10px] mb-1.5" style="color:#52525b;">Choisir le formulaire</p>
          <select class="input" id="close-form-select" style="font-size:12px;"><option value="">Pas de formulaire</option></select>
        </div>
        <div class="flex items-center justify-between pt-3" style="border-top:1px solid rgba(167,139,250,.15);">
          <span class="text-xs text-zinc-400">Activer l'envoi du formulaire</span>
          <button class="toggle on" id="form-toggle" onclick="this.classList.toggle('on')"></button>
        </div>
      </div>
    </div>
    <div class="flex items-center justify-end gap-2 px-6 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-ghost" onclick="closeModal('modal-close-trade')">Annuler</button>
      <button class="btn-primary" id="btn-close-confirm" onclick="confirmClose()">Clôturer & envoyer formulaire</button>
    </div>
  </div>
</div>

<!-- MODAL : AJOUTER PAIRE -->
<div class="modal-overlay" id="modal-add-pair">
  <div class="modal" style="width:440px;">
    <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);"><p class="text-sm font-medium text-white">Ajouter une paire</p><button class="btn-icon" onclick="closeModal('modal-add-pair')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button></div>
    <div class="px-6 py-5 flex flex-col gap-3">
      <div class="grid grid-cols-2 gap-3">
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Symbole *</p><input class="input" id="pair-symbol" type="text" placeholder="ex: EUR/USD" style="font-family:'Geist Mono',monospace;"></div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Catégorie</p><select class="input" id="pair-category"><option value="forex">Forex</option><option value="crypto">Crypto</option><option value="indices">Indices</option><option value="commodities">Matières premières</option></select></div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Valeur pip ($) *</p><input class="input" id="pair-pip" type="number" placeholder="ex: 10" style="font-family:'Geist Mono',monospace;"></div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Décimales</p><input class="input" id="pair-dec" type="number" value="5" style="font-family:'Geist Mono',monospace;"></div>
      </div>
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Symbole Binance</p><input class="input" id="pair-binance" type="text" placeholder="ex: EURUSDT" style="font-family:'Geist Mono',monospace;"></div>
      <div class="flex items-center justify-between py-2"><span class="text-xs text-zinc-400">Statut actif</span><button class="toggle on" id="pair-active-toggle" onclick="this.classList.toggle('on')"></button></div>
    </div>
    <div class="flex items-center justify-end gap-2 px-6 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-ghost" onclick="closeModal('modal-add-pair')">Annuler</button>
      <button class="btn-primary" onclick="savePair()">Ajouter la paire</button>
    </div>
  </div>
</div>

<!-- MODAL : CRÉER FORMULAIRE -->
<div class="modal-overlay" id="modal-create-form">
  <div class="modal" style="width:460px;">
    <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);"><p class="text-sm font-medium text-white">Créer un formulaire</p><button class="btn-icon" onclick="closeModal('modal-create-form')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button></div>
    <div class="px-6 py-5 flex flex-col gap-3">
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Nom du formulaire *</p><input class="input" id="new-form-name" type="text" placeholder="ex: Bilan mensuel membre"></div>
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Type</p><select class="input" id="new-form-type"><option value="system">Système</option><option value="custom">Personnalisé</option></select></div>
    </div>
    <div class="flex items-center justify-end gap-2 px-6 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-ghost" onclick="closeModal('modal-create-form')">Annuler</button>
      <button class="btn-primary" onclick="toast('Formulaire créé ✓','success');closeModal('modal-create-form')">Créer</button>
    </div>
  </div>
</div>

<!-- DRAWER : SIGNAL DETAIL -->
<div class="drawer-overlay" id="drawer-overlay" onclick="closeAllDrawers()"></div>
<div class="drawer" id="signal-drawer" style="width:480px;">
  <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
    <div id="signal-drawer-header"><p class="text-sm font-medium text-white">Signal</p></div>
    <button class="btn-icon" onclick="closeAllDrawers()"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
  </div>
  <div class="flex-1 overflow-y-auto px-5 py-4 flex flex-col gap-5" id="signal-drawer-content">
    <div class="text-center text-xs pt-16" style="color:#3f3f46;">Chargement...</div>
  </div>
</div>

<!-- DRAWER : MEMBRE -->
<div class="drawer" id="member-drawer" style="width:440px;">
  <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
    <div class="flex items-center gap-3" id="member-drawer-header">
      <div class="av av-default" id="member-av">—</div>
      <div><p class="text-sm font-medium text-white" id="member-drawer-name">—</p><p class="text-[11px] mt-0.5" style="color:#52525b;" id="member-drawer-sub">—</p></div>
    </div>
    <button class="btn-icon" onclick="closeAllDrawers()"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
  </div>
  <div class="flex-1 overflow-y-auto px-5 py-4 flex flex-col gap-5" id="member-drawer-content">
    <div class="text-center text-xs pt-16" style="color:#3f3f46;">Chargement...</div>
  </div>
  <div class="flex items-center gap-2 px-5 py-4" style="border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;">
    <button class="btn-ghost" style="flex:1;justify-content:center;font-size:11px;">Chat direct</button>
    <button class="btn-primary" style="flex:1;justify-content:center;font-size:11px;" id="btn-send-bilan-member" onclick="sendBilanToMember()">Envoyer bilan IA</button>
  </div>
</div>

<script src="../js/journal.js"></script>
</body>
</html>