<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TradingBot — IA Config</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400,500" rel="stylesheet">
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Geist','sans-serif'],mono:['Geist Mono','monospace']}}}}</script>
<style>
*{box-sizing:border-box;margin:0;padding:0;}
body{background:#09090b;font-family:'Geist',sans-serif;color:#e4e4e7;}
::-webkit-scrollbar{width:3px;height:3px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:rgba(255,255,255,.08);border-radius:99px;}
.nav-item{display:flex;align-items:center;gap:9px;padding:6px 10px;border-radius:7px;font-size:13px;color:#52525b;cursor:pointer;transition:all .15s;border:none;background:none;width:100%;text-align:left;}
.nav-item:hover{color:#d4d4d8;background:rgba(255,255,255,.04);}
.nav-item.active{color:#fafafa;background:rgba(255,255,255,.07);}
.nav-item svg{width:14px;height:14px;flex-shrink:0;opacity:.7;}
.nav-item.active svg{opacity:1;}
.nav-section{font-size:10px;font-weight:500;color:#3f3f46;letter-spacing:.08em;text-transform:uppercase;padding:10px 10px 3px;}
.badge{display:inline-flex;align-items:center;padding:2px 8px;border-radius:99px;font-size:11px;font-weight:500;white-space:nowrap;}
.bdg-g{background:rgba(52,211,153,.1);color:#34d399;}
.bdg-b{background:rgba(56,189,248,.1);color:#38bdf8;}
.bdg-a{background:rgba(251,191,36,.1);color:#fbbf24;}
.bdg-r{background:rgba(248,113,113,.1);color:#f87171;}
.bdg-v{background:rgba(167,139,250,.1);color:#a78bfa;}
.bdg-z{background:rgba(255,255,255,.06);color:#71717a;}
.input{width:100%;padding:7px 11px;font-size:13px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);border-radius:8px;color:#e4e4e7;font-family:'Geist',sans-serif;outline:none;transition:border-color .15s;}
.input:focus{border-color:rgba(56,189,248,.45);}
.input::placeholder{color:#3f3f46;}
select.input option{background:#1a1a1c;color:#e4e4e7;}
textarea.input{resize:vertical;font-family:'Geist Mono',monospace;font-size:12px;line-height:1.6;}
.btn-p{display:inline-flex;align-items:center;gap:6px;padding:7px 14px;font-size:12px;font-weight:500;background:#38bdf8;color:#082f49;border:none;border-radius:8px;cursor:pointer;transition:background .15s;font-family:'Geist',sans-serif;white-space:nowrap;}
.btn-p:hover{background:#7dd3fc;}
.btn-g{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;font-size:12px;background:rgba(255,255,255,.05);color:#a1a1aa;border:1px solid rgba(255,255,255,.08);border-radius:8px;cursor:pointer;transition:all .15s;font-family:'Geist',sans-serif;white-space:nowrap;}
.btn-g:hover{background:rgba(255,255,255,.09);color:#e4e4e7;}
.btn-i{width:28px;height:28px;display:flex;align-items:center;justify-content:center;color:#52525b;border:none;background:rgba(255,255,255,.04);border-radius:7px;cursor:pointer;transition:all .15s;flex-shrink:0;}
.btn-i:hover{background:rgba(255,255,255,.09);color:#d4d4d8;}
.btn-danger{display:inline-flex;align-items:center;gap:5px;padding:6px 12px;font-size:12px;background:rgba(248,113,113,.08);color:#f87171;border:1px solid rgba(248,113,113,.18);border-radius:8px;cursor:pointer;font-family:'Geist',sans-serif;}
.btn-danger:hover{background:rgba(248,113,113,.15);}
.card{background:#111113;border:1px solid rgba(255,255,255,.06);border-radius:12px;}
.stat-m{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);border-radius:9px;padding:11px 13px;}
.tab{padding:5px 12px;font-size:12px;border-radius:7px;cursor:pointer;transition:all .15s;border:none;background:none;color:#52525b;font-family:'Geist',sans-serif;white-space:nowrap;}
.tab:hover{color:#a1a1aa;}
.tab.active{background:rgba(255,255,255,.07);color:#fafafa;}
.slbl{font-size:10px;font-weight:500;color:#3f3f46;letter-spacing:.06em;text-transform:uppercase;margin-bottom:5px;}
.mono{font-family:'Geist Mono',monospace;}
.overlay{position:fixed;inset:0;background:rgba(0,0,0,.76);z-index:60;display:flex;align-items:center;justify-content:center;opacity:0;pointer-events:none;transition:opacity .16s;}
.overlay.open{opacity:1;pointer-events:auto;}
.modal{background:#111113;border:1px solid rgba(255,255,255,.1);border-radius:14px;transform:scale(.97);transition:transform .16s;max-height:92vh;display:flex;flex-direction:column;overflow:hidden;}
.overlay.open .modal{transform:scale(1);}
.toggle{width:30px;height:16px;background:rgba(255,255,255,.1);border-radius:99px;position:relative;cursor:pointer;transition:background .2s;flex-shrink:0;border:none;padding:0;}
.toggle.on{background:#38bdf8;}
.toggle::after{content:'';position:absolute;width:11px;height:11px;background:white;border-radius:50%;top:2.5px;left:2.5px;transition:transform .2s;}
.toggle.on::after{transform:translateX(14px);}
#toast-container{position:fixed;bottom:24px;left:50%;transform:translateX(-50%);z-index:9999;display:flex;flex-direction:column;gap:8px;align-items:center;pointer-events:none;}
.toast{background:#111113;border:1px solid rgba(255,255,255,.12);color:#e4e4e7;padding:9px 18px;border-radius:9px;font-size:12px;font-family:'Geist',sans-serif;white-space:nowrap;animation:toastin .2s ease;pointer-events:none;}
.toast.success{border-color:rgba(52,211,153,.3);color:#34d399;}
.toast.error{border-color:rgba(248,113,113,.3);color:#f87171;}
.toast.warn{border-color:rgba(251,191,36,.3);color:#fbbf24;}
@keyframes toastin{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
@keyframes fadein{from{opacity:0;transform:translateY(4px)}to{opacity:1;transform:translateY(0)}}
.fadein{animation:fadein .18s ease;}
.ia-card{background:#111113;border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:14px;transition:border-color .15s;cursor:pointer;}
.ia-card:hover{border-color:rgba(255,255,255,.12);}
.ia-card.active-card{border-color:rgba(56,189,248,.3);background:rgba(56,189,248,.02);}
.code-block{background:#0d0d0f;border:1px solid rgba(255,255,255,.06);border-radius:8px;padding:12px;font-family:'Geist Mono',monospace;font-size:11px;color:#a1a1aa;line-height:1.7;overflow-x:auto;white-space:pre;max-height:120px;overflow-y:auto;}
.fmt-pill{display:inline-flex;align-items:center;padding:2px 8px;border-radius:6px;font-size:10px;font-weight:500;}
.fmt-text{background:rgba(255,255,255,.06);color:#a1a1aa;}
.fmt-json{background:rgba(251,191,36,.1);color:#fbbf24;}
.fmt-list{background:rgba(52,211,153,.1);color:#34d399;}
.fmt-markdown{background:rgba(167,139,250,.1);color:#a78bfa;}
.drop-zone{border:1.5px dashed rgba(255,255,255,.1);border-radius:10px;padding:28px;text-align:center;transition:all .2s;cursor:pointer;}
.drop-zone:hover,.drop-zone.over{border-color:rgba(56,189,248,.4);background:rgba(56,189,248,.03);}
.file-row{display:flex;align-items:center;gap:10px;padding:9px 12px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:8px;}
</style>
</head>
<body class="h-screen overflow-hidden">
<div id="toast-container"></div>

<div class="flex h-full">
<!-- SIDEBAR -->
<aside style="width:208px;flex-shrink:0;background:#0d0d0f;border-right:1px solid rgba(255,255,255,.05);" class="flex flex-col h-full">
  <div class="px-4 py-4" style="border-bottom:1px solid rgba(255,255,255,.05);">
    <div class="flex items-center gap-3">
      <div style="width:30px;height:30px;background:#0ea5e9;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="15" height="15" fill="white" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.94 8.19l-2.02 9.52c-.14.66-.54.82-1.09.51l-3-2.21-1.45 1.39c-.16.16-.3.3-.61.3l.22-3.1 5.6-5.06c.24-.22-.06-.34-.38-.12L7.03 14.5 4.06 13.6c-.65-.2-.66-.65.14-.96l11.65-4.5c.54-.2 1.01.13.09 2.05z"/></svg>
      </div>
      <div><p class="text-sm font-medium text-white leading-none">TradingBot</p><p class="text-xs mt-0.5" style="color:#3f3f46;">3 247 membres</p></div>
    </div>
  </div>
  <nav class="flex-1 px-2 py-3 overflow-y-auto flex flex-col gap-0.5">
    <div class="nav-section">Vue d'ensemble</div>
    <button class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>Dashboard</button>
    <div class="nav-section" style="margin-top:6px;">Membres</div>
    <button class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>Utilisateurs</button>
    <button class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>Catégories</button>
    <button class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>Formulaires</button>
    <div class="nav-section" style="margin-top:6px;">Messagerie</div>
    <button class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>Messages ciblés</button>
    <button class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Chat direct</button>
    <div class="nav-section" style="margin-top:6px;">Trading</div>
    <button class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>Journal</button>
    <div class="nav-section" style="margin-top:6px;">Croissance</div>
    <button class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>Liens & Onboarding</button>
    <button class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>Automations</button>
    <button class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>Abonnements</button>
    <div class="nav-section" style="margin-top:6px;">Intelligence</div>
    <button class="nav-item active" id="nav-prompts" onclick="sv('prompts',this)"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><path d="M9 12h6M9 16h4"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>Prompts IA<span class="badge bdg-b ml-auto text-[9px]" id="nav-prompts-count">0</span></button>
    <button class="nav-item" id="nav-functions" onclick="sv('functions',this)"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>Fonctions<span class="badge bdg-v ml-auto text-[9px]" id="nav-functions-count">0</span></button>
    <button class="nav-item" id="nav-tables" onclick="sv('tables',this)"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M3 3h18v4H3zM3 11h18v4H3zM3 19h18v2H3z"/></svg>Tables DB</button>
    <button class="nav-item" id="nav-endpoints" onclick="sv('endpoints',this)"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/><path d="M3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/></svg>Endpoints API</button>
  </nav>
  <div class="px-4 py-3" style="border-top:1px solid rgba(255,255,255,.05);">
    <div class="flex items-center gap-2.5"><div style="width:27px;height:27px;border-radius:50%;background:linear-gradient(135deg,#0ea5e9,#6366f1);flex-shrink:0;"></div><div><p class="text-xs font-medium text-white">Admin</p><p class="text-[10px]" style="color:#3f3f46;">admin@tradingbot.io</p></div></div>
  </div>
</aside>

<!-- MAIN -->
<div class="flex-1 flex flex-col min-w-0 overflow-hidden">
<header style="height:52px;border-bottom:1px solid rgba(255,255,255,.05);backdrop-filter:blur(14px);background:rgba(9,9,11,.88);flex-shrink:0;" class="flex items-center justify-between px-5">
  <div class="flex items-center gap-3">
    <h1 class="text-sm font-medium text-white" id="page-title">Prompts IA</h1>
    <span style="color:#27272a;">·</span>
    <p class="text-xs" style="color:#52525b;" id="page-sub">Gérer les prompts injectés dans l'intelligence artificielle</p>
  </div>
  <div class="flex items-center gap-2">
    <button class="btn-g" style="font-size:11px;" id="btn-export" onclick="exportCurrent()">
      <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      Exporter .py
    </button>
    <button class="btn-p" id="main-cta" onclick="openCreate()">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      <span id="cta-label">Nouveau prompt</span>
    </button>
  </div>
</header>

<main class="flex-1 overflow-y-auto" style="padding:20px;" id="main-content">

<!-- VUE PROMPTS -->
<div id="v-prompts" class="flex flex-col gap-4">
  <div class="grid grid-cols-4 gap-3">
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Total prompts</p><p class="text-xl font-light text-white" id="stat-p-total">0</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Actifs</p><p class="text-xl font-light" style="color:#34d399;" id="stat-p-active">0</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Formats</p><p class="text-sm font-medium text-white" id="stat-p-formats">—</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Dernier ajout</p><p class="text-sm font-medium text-white" id="stat-p-last">—</p></div>
  </div>
  <div class="card overflow-hidden">
    <div class="flex items-center justify-between px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,.05);">
      <div class="flex items-center gap-2">
        <p class="text-sm font-medium text-white">Bibliothèque de prompts</p>
        <span class="badge bdg-z text-[10px]" id="prompts-count-badge">0</span>
      </div>
      <div class="flex items-center gap-2">
        <select class="input" style="width:130px;padding:5px 8px;font-size:11px;" onchange="filterPrompts(this.value)">
          <option value="">Tous les formats</option>
          <option value="text">Texte</option>
          <option value="json">JSON</option>
          <option value="list">Liste</option>
          <option value="markdown">Markdown</option>
        </select>
      </div>
    </div>
    <div id="prompts-list" class="p-4 flex flex-col gap-3"></div>
    <div id="prompts-empty" class="hidden p-10 text-center">
      <p class="text-sm" style="color:#3f3f46;">Aucun prompt — créez le premier</p>
    </div>
  </div>
</div>

<!-- VUE FONCTIONS -->
<div id="v-functions" class="hidden flex flex-col gap-4">
  <div class="grid grid-cols-4 gap-3">
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Total fonctions</p><p class="text-xl font-light text-white" id="stat-f-total">0</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Actives</p><p class="text-xl font-light" style="color:#a78bfa;" id="stat-f-active">0</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Lignes de code</p><p class="text-xl font-light" style="color:#38bdf8;" id="stat-f-lines">0</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Dernier ajout</p><p class="text-sm font-medium text-white" id="stat-f-last">—</p></div>
  </div>
  <div class="card overflow-hidden">
    <div class="flex items-center justify-between px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,.05);">
      <div class="flex items-center gap-2">
        <p class="text-sm font-medium text-white">Fonctions injectables</p>
        <span class="badge bdg-z text-[10px]" id="functions-count-badge">0</span>
      </div>
    </div>
    <div id="functions-list" class="p-4 flex flex-col gap-3"></div>
    <div id="functions-empty" class="hidden p-10 text-center">
      <p class="text-sm" style="color:#3f3f46;">Aucune fonction — créez la première</p>
    </div>
  </div>
</div>

<!-- VUE TABLES DB -->
<div id="v-tables" class="hidden flex flex-col gap-4">
  <div class="grid grid-cols-2 gap-4">
    <div class="card overflow-hidden">
      <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,.05);">
        <p class="text-sm font-medium text-white">Importer un fichier Tables</p>
        <p class="text-xs mt-1" style="color:#52525b;">Fichier JSON décrivant la structure des tables DB</p>
      </div>
      <div class="p-4 flex flex-col gap-3">
        <div class="drop-zone" id="drop-tables" onclick="document.getElementById('file-tables').click()">
          <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="color:#3f3f46;margin:0 auto 8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          <p class="text-xs" style="color:#52525b;">Glisser un fichier <span class="mono" style="color:#38bdf8;">.json</span> ou cliquer</p>
          <input type="file" id="file-tables" accept=".json" class="hidden" onchange="loadTablesFile(this)">
        </div>
        <div id="tables-file-info" class="hidden file-row">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="color:#34d399;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>
          <span class="text-xs flex-1" id="tables-file-name" style="color:#a1a1aa;"></span>
          <button class="btn-i" onclick="clearTablesFile()"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
        </div>
        <button class="btn-g w-full justify-center" onclick="downloadTablesTemplate()">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Télécharger le template
        </button>
      </div>
    </div>
    <div class="card overflow-hidden">
      <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,.05);">
        <p class="text-sm font-medium text-white">Aperçu du fichier</p>
        <p class="text-xs mt-1" style="color:#52525b;">Contenu importé</p>
      </div>
      <div class="p-4">
        <div id="tables-preview" class="code-block" style="max-height:280px;color:#52525b;">Aucun fichier importé</div>
      </div>
    </div>
  </div>
  <!-- Tables importées -->
  <div class="card overflow-hidden">
    <div class="flex items-center justify-between px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,.05);">
      <p class="text-sm font-medium text-white">Tables importées</p>
      <span class="badge bdg-z text-[10px]" id="tables-count">0 tables</span>
    </div>
    <div id="tables-rendered" class="p-4 grid grid-cols-2 gap-3"></div>
    <div id="tables-empty" class="p-8 text-center"><p class="text-xs" style="color:#3f3f46;">Importez un fichier JSON pour voir les tables</p></div>
  </div>
</div>

<!-- VUE ENDPOINTS -->
<div id="v-endpoints" class="hidden flex flex-col gap-4">
  <div class="grid grid-cols-2 gap-4">
    <div class="card overflow-hidden">
      <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,.05);">
        <p class="text-sm font-medium text-white">Importer un fichier Endpoints</p>
        <p class="text-xs mt-1" style="color:#52525b;">Fichier JSON décrivant les routes API disponibles</p>
      </div>
      <div class="p-4 flex flex-col gap-3">
        <div class="drop-zone" id="drop-endpoints" onclick="document.getElementById('file-endpoints').click()">
          <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="color:#3f3f46;margin:0 auto 8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          <p class="text-xs" style="color:#52525b;">Glisser un fichier <span class="mono" style="color:#38bdf8;">.json</span> ou cliquer</p>
          <input type="file" id="file-endpoints" accept=".json" class="hidden" onchange="loadEndpointsFile(this)">
        </div>
        <div id="endpoints-file-info" class="hidden file-row">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="color:#34d399;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>
          <span class="text-xs flex-1" id="endpoints-file-name" style="color:#a1a1aa;"></span>
          <button class="btn-i" onclick="clearEndpointsFile()"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
        </div>
        <button class="btn-g w-full justify-center" onclick="downloadEndpointsTemplate()">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Télécharger le template
        </button>
      </div>
    </div>
    <div class="card overflow-hidden">
      <div class="px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,.05);">
        <p class="text-sm font-medium text-white">Aperçu du fichier</p>
        <p class="text-xs mt-1" style="color:#52525b;">Contenu importé</p>
      </div>
      <div class="p-4">
        <div id="endpoints-preview" class="code-block" style="max-height:280px;color:#52525b;">Aucun fichier importé</div>
      </div>
    </div>
  </div>
  <!-- Endpoints importés -->
  <div class="card overflow-hidden">
    <div class="flex items-center justify-between px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,.05);">
      <p class="text-sm font-medium text-white">Endpoints importés</p>
      <span class="badge bdg-z text-[10px]" id="endpoints-count">0 routes</span>
    </div>
    <div id="endpoints-rendered" class="p-4 flex flex-col gap-2"></div>
    <div id="endpoints-empty" class="p-8 text-center"><p class="text-xs" style="color:#3f3f46;">Importez un fichier JSON pour voir les endpoints</p></div>
  </div>
</div>

</main>
</div>
</div>

<!-- MODAL PROMPT -->
<div class="overlay" id="m-prompt" onclick="closeModal('m-prompt',event)">
  <div class="modal" style="width:600px;">
    <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.07);">
      <p class="text-sm font-medium text-white" id="m-prompt-title">Nouveau prompt</p>
      <button class="btn-i" onclick="closeModal('m-prompt')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="overflow-y-auto flex flex-col gap-4 p-5">
      <input type="hidden" id="p-id">
      <div>
        <p class="slbl">Nom du prompt</p>
        <input class="input" id="p-name" placeholder="ex: Analyse comportement trader">
      </div>
      <div>
        <p class="slbl">Description courte</p>
        <input class="input" id="p-desc" placeholder="À quoi sert ce prompt ?">
      </div>
      <div>
        <p class="slbl">Format de retour attendu</p>
        <select class="input" id="p-format">
          <option value="text">Texte libre</option>
          <option value="json">JSON</option>
          <option value="list">Liste</option>
          <option value="markdown">Markdown</option>
        </select>
      </div>
      <div>
        <p class="slbl">Contenu du prompt</p>
        <textarea class="input" id="p-content" rows="10" placeholder="Tu es un assistant spécialisé en trading..."></textarea>
      </div>
      <div class="flex items-center gap-2">
        <button class="toggle on" id="p-active-toggle" onclick="this.classList.toggle('on')"></button>
        <span class="text-xs" style="color:#71717a;">Actif</span>
      </div>
    </div>
    <div class="flex items-center justify-between px-5 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-danger hidden" id="p-del-btn" onclick="deletePrompt()">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>Supprimer
      </button>
      <div class="flex gap-2 ml-auto">
        <button class="btn-g" onclick="closeModal('m-prompt')">Annuler</button>
        <button class="btn-p" onclick="savePrompt()">Enregistrer</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL FONCTION -->
<div class="overlay" id="m-function" onclick="closeModal('m-function',event)">
  <div class="modal" style="width:660px;">
    <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.07);">
      <p class="text-sm font-medium text-white" id="m-function-title">Nouvelle fonction</p>
      <button class="btn-i" onclick="closeModal('m-function')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="overflow-y-auto flex flex-col gap-4 p-5">
      <input type="hidden" id="f-id">
      <div class="grid grid-cols-2 gap-3">
        <div>
          <p class="slbl">Nom de la fonction</p>
          <input class="input mono" id="f-name" placeholder="ex: compute_win_rate">
        </div>
        <div>
          <p class="slbl">Description</p>
          <input class="input" id="f-desc" placeholder="Ce que fait la fonction">
        </div>
      </div>
      <div>
        <p class="slbl">Code Python</p>
        <textarea class="input" id="f-code" rows="14" placeholder="async def compute_win_rate(user_id: int) -> float:&#10;    # ...&#10;    pass"></textarea>
      </div>
      <div class="flex items-center gap-2">
        <button class="toggle on" id="f-active-toggle" onclick="this.classList.toggle('on')"></button>
        <span class="text-xs" style="color:#71717a;">Active</span>
      </div>
    </div>
    <div class="flex items-center justify-between px-5 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-danger hidden" id="f-del-btn" onclick="deleteFunction()">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>Supprimer
      </button>
      <div class="flex gap-2 ml-auto">
        <button class="btn-g" onclick="closeModal('m-function')">Annuler</button>
        <button class="btn-p" onclick="saveFunction()">Enregistrer</button>
      </div>
    </div>
  </div>
</div>

<script>
const API = 'http://54.226.165.244:8000/ia-config'

// ── State ──────────────────────────────────────────────────
let currentView = 'prompts'
let tablesData   = null
let endpointsData = null

// ── API ────────────────────────────────────────────────────
async function api(url, opts={}) {
  try {
    const r = await fetch(url, {headers:{'Content-Type':'application/json'}, ...opts})
    if (!r.ok) { const e = await r.json().catch(()=>({detail:'Erreur'})); throw new Error(e.detail||`HTTP ${r.status}`) }
    return r.json()
  } catch(e) { toast(e.message,'error'); throw e }
}

// ── Nav ────────────────────────────────────────────────────
const VIEWS = {
  prompts:   {title:'Prompts IA',      sub:'Gérer les prompts injectés dans l\'intelligence artificielle', cta:'Nouveau prompt',   export:'Exporter .py'},
  functions: {title:'Fonctions',       sub:'Fonctions Python injectables dans le bot', cta:'Nouvelle fonction', export:'Exporter .py'},
  tables:    {title:'Tables DB',       sub:'Importer le fichier de définition des tables', cta:null, export:'Exporter JSON'},
  endpoints: {title:'Endpoints API',   sub:'Importer le fichier de définition des routes', cta:null, export:'Exporter JSON'},
}

function sv(v, btn) {
  currentView = v
  document.querySelectorAll('.nav-item').forEach(b=>b.classList.remove('active'))
  if(btn) btn.classList.add('active')
  document.querySelectorAll('[id^="v-"]').forEach(el=>el.classList.add('hidden'))
  document.getElementById('v-'+v).classList.remove('hidden')
  const cfg = VIEWS[v]
  document.getElementById('page-title').textContent = cfg.title
  document.getElementById('page-sub').textContent   = cfg.sub
  document.getElementById('btn-export').textContent = cfg.export||'Exporter'
  const cta = document.getElementById('main-cta')
  if(cfg.cta) { cta.style.display=''; document.getElementById('cta-label').textContent=cfg.cta }
  else cta.style.display='none'
}

function openCreate() {
  if(currentView==='prompts')   openPromptModal()
  if(currentView==='functions') openFunctionModal()
}

function exportCurrent() {
  if(currentView==='prompts')   window.open(API+'/export/prompts','_blank')
  if(currentView==='functions') window.open(API+'/export/functions','_blank')
  if(currentView==='tables')    exportJSON(tablesData,'ia_db_tables.json')
  if(currentView==='endpoints') exportJSON(endpointsData,'ia_endpoints.json')
}

// ── Toast ──────────────────────────────────────────────────
function toast(msg, type='success') {
  const t = document.createElement('div')
  t.className = `toast ${type}`; t.textContent = msg
  document.getElementById('toast-container').appendChild(t)
  setTimeout(()=>t.remove(), 2800)
}

// ── Modal ──────────────────────────────────────────────────
function closeModal(id, e) {
  if(e && e.target.id !== id) return
  document.getElementById(id).classList.remove('open')
}

// ══════════════════════════════════════════════════════
// PROMPTS
// ══════════════════════════════════════════════════════
let _pFilter = ''

async function loadPrompts() {
  const data = await api(API+'/prompts').catch(()=>[])
  renderPrompts(data)
  updatePromptStats(data)
  document.getElementById('nav-prompts-count').textContent = data.length
}

function renderPrompts(data) {
  const fmtClass = {text:'fmt-text',json:'fmt-json',list:'fmt-list',markdown:'fmt-markdown'}
  const fmtLabel = {text:'Texte',json:'JSON',list:'Liste',markdown:'Markdown'}
  const filtered = _pFilter ? data.filter(p=>p.return_format===_pFilter) : data
  const list = document.getElementById('prompts-list')
  const empty = document.getElementById('prompts-empty')
  document.getElementById('prompts-count-badge').textContent = filtered.length
  if(!filtered.length) { list.innerHTML=''; empty.classList.remove('hidden'); return }
  empty.classList.add('hidden')
  list.innerHTML = filtered.map(p=>`
    <div class="ia-card fadein ${p.is_active?'':'opacity-50'}" onclick="openPromptModal(${p.id})">
      <div class="flex items-start justify-between gap-3">
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 mb-1">
            <p class="text-sm font-medium text-white truncate">${p.name}</p>
            <span class="fmt-pill ${fmtClass[p.return_format]||'fmt-text'}">${fmtLabel[p.return_format]||p.return_format}</span>
            ${!p.is_active?'<span class="badge bdg-z" style="font-size:9px;">Inactif</span>':''}
          </div>
          ${p.description?`<p class="text-xs mb-2" style="color:#52525b;">${p.description}</p>`:''}
          <div class="code-block" style="max-height:70px;">${escHtml(p.content||'').substring(0,300)}${(p.content||'').length>300?'…':''}</div>
        </div>
        <button class="btn-i flex-shrink-0" onclick="event.stopPropagation();openPromptModal(${p.id})">
          <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </button>
      </div>
      <p class="text-[10px] mt-2" style="color:#3f3f46;">${fmtDate(p.created_at)}</p>
    </div>
  `).join('')
}

function updatePromptStats(data) {
  document.getElementById('stat-p-total').textContent  = data.length
  document.getElementById('stat-p-active').textContent = data.filter(p=>p.is_active).length
  const fmts = [...new Set(data.map(p=>p.return_format))].join(', ')
  document.getElementById('stat-p-formats').textContent = fmts||'—'
  const last = data[0]
  document.getElementById('stat-p-last').textContent = last ? fmtDate(last.created_at) : '—'
}

function filterPrompts(v) { _pFilter=v; loadPrompts() }

async function openPromptModal(id=null) {
  const m = document.getElementById('m-prompt')
  document.getElementById('p-id').value = ''
  document.getElementById('p-name').value = ''
  document.getElementById('p-desc').value = ''
  document.getElementById('p-content').value = ''
  document.getElementById('p-format').value = 'text'
  document.getElementById('p-active-toggle').classList.add('on')
  document.getElementById('p-del-btn').classList.add('hidden')
  document.getElementById('m-prompt-title').textContent = 'Nouveau prompt'

  if(id) {
    const p = await api(API+'/prompts').then(d=>d.find(x=>x.id===id)).catch(()=>null)
    if(!p) return
    document.getElementById('p-id').value      = p.id
    document.getElementById('p-name').value    = p.name
    document.getElementById('p-desc').value    = p.description||''
    document.getElementById('p-content').value = p.content||''
    document.getElementById('p-format').value  = p.return_format||'text'
    if(!p.is_active) document.getElementById('p-active-toggle').classList.remove('on')
    document.getElementById('p-del-btn').classList.remove('hidden')
    document.getElementById('m-prompt-title').textContent = 'Modifier le prompt'
  }
  m.classList.add('open')
}

async function savePrompt() {
  const id      = document.getElementById('p-id').value
  const payload = {
    name:          document.getElementById('p-name').value.trim(),
    description:   document.getElementById('p-desc').value.trim(),
    content:       document.getElementById('p-content').value,
    return_format: document.getElementById('p-format').value,
    is_active:     document.getElementById('p-active-toggle').classList.contains('on') ? 1 : 0,
  }
  if(!payload.name) { toast('Nom requis','warn'); return }
  if(id) await api(API+'/prompts/'+id, {method:'PATCH',body:JSON.stringify(payload)})
  else   await api(API+'/prompts',     {method:'POST', body:JSON.stringify(payload)})
  toast(id?'Prompt mis à jour':'Prompt créé')
  closeModal('m-prompt')
  loadPrompts()
}

async function deletePrompt() {
  const id = document.getElementById('p-id').value
  if(!id || !confirm('Supprimer ce prompt ?')) return
  await api(API+'/prompts/'+id, {method:'DELETE'})
  toast('Prompt supprimé','warn')
  closeModal('m-prompt')
  loadPrompts()
}

// ══════════════════════════════════════════════════════
// FONCTIONS
// ══════════════════════════════════════════════════════
async function loadFunctions() {
  const data = await api(API+'/functions').catch(()=>[])
  renderFunctions(data)
  updateFunctionStats(data)
  document.getElementById('nav-functions-count').textContent = data.length
}

function renderFunctions(data) {
  const list  = document.getElementById('functions-list')
  const empty = document.getElementById('functions-empty')
  document.getElementById('functions-count-badge').textContent = data.length
  if(!data.length) { list.innerHTML=''; empty.classList.remove('hidden'); return }
  empty.classList.add('hidden')
  list.innerHTML = data.map(f=>`
    <div class="ia-card fadein ${f.is_active?'':'opacity-50'}" onclick="openFunctionModal(${f.id})">
      <div class="flex items-start justify-between gap-3">
        <div class="flex-1 min-w-0">
          <div class="flex items-center gap-2 mb-1">
            <p class="text-sm font-medium mono text-white truncate">${f.name}</p>
            ${!f.is_active?'<span class="badge bdg-z" style="font-size:9px;">Inactive</span>':'<span class="badge bdg-v" style="font-size:9px;">Active</span>'}
          </div>
          ${f.description?`<p class="text-xs mb-2" style="color:#52525b;">${f.description}</p>`:''}
          <div class="code-block" style="max-height:90px;">${escHtml(f.code||'').substring(0,400)}${(f.code||'').length>400?'…':''}</div>
          <p class="text-[10px] mt-1.5" style="color:#3f3f46;">${(f.code||'').split('\n').length} lignes · ${fmtDate(f.created_at)}</p>
        </div>
        <button class="btn-i flex-shrink-0" onclick="event.stopPropagation();openFunctionModal(${f.id})">
          <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </button>
      </div>
    </div>
  `).join('')
}

function updateFunctionStats(data) {
  document.getElementById('stat-f-total').textContent  = data.length
  document.getElementById('stat-f-active').textContent = data.filter(f=>f.is_active).length
  const lines = data.reduce((a,f)=>a+(f.code||'').split('\n').length,0)
  document.getElementById('stat-f-lines').textContent  = lines
  document.getElementById('stat-f-last').textContent   = data[0] ? fmtDate(data[0].created_at) : '—'
}

async function openFunctionModal(id=null) {
  const m = document.getElementById('m-function')
  document.getElementById('f-id').value   = ''
  document.getElementById('f-name').value = ''
  document.getElementById('f-desc').value = ''
  document.getElementById('f-code').value = ''
  document.getElementById('f-active-toggle').classList.add('on')
  document.getElementById('f-del-btn').classList.add('hidden')
  document.getElementById('m-function-title').textContent = 'Nouvelle fonction'

  if(id) {
    const f = await api(API+'/functions').then(d=>d.find(x=>x.id===id)).catch(()=>null)
    if(!f) return
    document.getElementById('f-id').value   = f.id
    document.getElementById('f-name').value = f.name
    document.getElementById('f-desc').value = f.description||''
    document.getElementById('f-code').value = f.code||''
    if(!f.is_active) document.getElementById('f-active-toggle').classList.remove('on')
    document.getElementById('f-del-btn').classList.remove('hidden')
    document.getElementById('m-function-title').textContent = 'Modifier la fonction'
  }
  m.classList.add('open')
}

async function saveFunction() {
  const id = document.getElementById('f-id').value
  const payload = {
    name:      document.getElementById('f-name').value.trim(),
    description: document.getElementById('f-desc').value.trim(),
    code:      document.getElementById('f-code').value,
    is_active: document.getElementById('f-active-toggle').classList.contains('on') ? 1 : 0,
  }
  if(!payload.name) { toast('Nom requis','warn'); return }
  if(id) await api(API+'/functions/'+id, {method:'PATCH',body:JSON.stringify(payload)})
  else   await api(API+'/functions',     {method:'POST', body:JSON.stringify(payload)})
  toast(id?'Fonction mise à jour':'Fonction créée')
  closeModal('m-function')
  loadFunctions()
}

async function deleteFunction() {
  const id = document.getElementById('f-id').value
  if(!id || !confirm('Supprimer cette fonction ?')) return
  await api(API+'/functions/'+id, {method:'DELETE'})
  toast('Fonction supprimée','warn')
  closeModal('m-function')
  loadFunctions()
}

// ══════════════════════════════════════════════════════
// TABLES DB (fichier local)
// ══════════════════════════════════════════════════════
function loadTablesFile(input) {
  const file = input.files[0]; if(!file) return
  const reader = new FileReader()
  reader.onload = e => {
    try {
      tablesData = JSON.parse(e.target.result)
      document.getElementById('tables-file-name').textContent = file.name
      document.getElementById('tables-file-info').classList.remove('hidden')
      document.getElementById('tables-preview').textContent = JSON.stringify(tablesData, null, 2)
      renderTables(tablesData)
      toast('Fichier importé')
    } catch { toast('JSON invalide','error') }
  }
  reader.readAsText(file)
}

function clearTablesFile() {
  tablesData = null
  document.getElementById('file-tables').value = ''
  document.getElementById('tables-file-info').classList.add('hidden')
  document.getElementById('tables-preview').textContent = 'Aucun fichier importé'
  document.getElementById('tables-rendered').innerHTML = ''
  document.getElementById('tables-empty').classList.remove('hidden')
  document.getElementById('tables-count').textContent = '0 tables'
}

function renderTables(data) {
  const tables = Array.isArray(data) ? data : (data.tables || [])
  const container = document.getElementById('tables-rendered')
  const empty     = document.getElementById('tables-empty')
  document.getElementById('tables-count').textContent = tables.length+' tables'
  if(!tables.length) { container.innerHTML=''; empty.classList.remove('hidden'); return }
  empty.classList.add('hidden')
  container.innerHTML = tables.map(t=>`
    <div class="card p-3 fadein">
      <div class="flex items-center gap-2 mb-2">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="color:#38bdf8;"><path d="M3 3h18v4H3zM3 11h18v4H3zM3 19h18v2H3z"/></svg>
        <p class="text-sm font-medium mono text-white">${t.name}</p>
        <span class="badge bdg-z text-[9px]">${(t.columns||[]).length} cols</span>
      </div>
      <div class="flex flex-wrap gap-1.5">
        ${(t.columns||[]).map(c=>`
          <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:5px;padding:2px 7px;">
            <span class="mono text-[10px]" style="color:#a1a1aa;">${c.name}</span>
            <span class="text-[9px] ml-1" style="color:#3f3f46;">${c.type||''}</span>
          </div>
        `).join('')}
      </div>
    </div>
  `).join('')
}

function downloadTablesTemplate() {
  const tpl = {
    tables: [
      { name: "users", columns: [
          {name:"id",type:"INTEGER",pk:true},
          {name:"name",type:"TEXT",notnull:true},
          {name:"telegram_id",type:"INTEGER"},
          {name:"created_at",type:"TEXT",default:"datetime('now')"}
      ]},
      { name: "messages", columns: [
          {name:"id",type:"INTEGER",pk:true},
          {name:"user_id",type:"INTEGER",notnull:true},
          {name:"message_text",type:"TEXT",notnull:true},
          {name:"created_at",type:"TEXT",default:"datetime('now')"}
      ]}
    ]
  }
  exportJSON(tpl,'ia_db_tables_template.json')
}

// ══════════════════════════════════════════════════════
// ENDPOINTS API (fichier local)
// ══════════════════════════════════════════════════════
function loadEndpointsFile(input) {
  const file = input.files[0]; if(!file) return
  const reader = new FileReader()
  reader.onload = e => {
    try {
      endpointsData = JSON.parse(e.target.result)
      document.getElementById('endpoints-file-name').textContent = file.name
      document.getElementById('endpoints-file-info').classList.remove('hidden')
      document.getElementById('endpoints-preview').textContent = JSON.stringify(endpointsData, null, 2)
      renderEndpoints(endpointsData)
      toast('Fichier importé')
    } catch { toast('JSON invalide','error') }
  }
  reader.readAsText(file)
}

function clearEndpointsFile() {
  endpointsData = null
  document.getElementById('file-endpoints').value = ''
  document.getElementById('endpoints-file-info').classList.add('hidden')
  document.getElementById('endpoints-preview').textContent = 'Aucun fichier importé'
  document.getElementById('endpoints-rendered').innerHTML = ''
  document.getElementById('endpoints-empty').classList.remove('hidden')
  document.getElementById('endpoints-count').textContent = '0 routes'
}

const METHOD_COLORS = {
  GET:'bdg-g', POST:'bdg-b', PATCH:'bdg-a', DELETE:'bdg-r', PUT:'bdg-v'
}

function renderEndpoints(data) {
  const eps   = Array.isArray(data) ? data : (data.endpoints || [])
  const cont  = document.getElementById('endpoints-rendered')
  const empty = document.getElementById('endpoints-empty')
  document.getElementById('endpoints-count').textContent = eps.length+' routes'
  if(!eps.length) { cont.innerHTML=''; empty.classList.remove('hidden'); return }
  empty.classList.add('hidden')
  cont.innerHTML = eps.map(ep=>`
    <div class="file-row fadein">
      <span class="badge ${METHOD_COLORS[ep.method]||'bdg-z'}" style="font-size:10px;min-width:52px;justify-content:center;">${ep.method}</span>
      <span class="mono text-xs text-white flex-1">${ep.route}</span>
      <span class="text-xs" style="color:#52525b;">${ep.description||''}</span>
      ${ep.payload?`<span class="badge bdg-z text-[9px]">payload</span>`:''}
    </div>
  `).join('')
}

function downloadEndpointsTemplate() {
  const tpl = {
    endpoints: [
      {method:"GET",  route:"/categorie",          description:"Liste des catégories", payload:null},
      {method:"POST", route:"/broadcast",           description:"Envoyer un message ciblé", payload:{message:"string",category:"string",format:"text|image+text"}},
      {method:"GET",  route:"/trading/signals",     description:"Liste des signaux", payload:null},
      {method:"POST", route:"/trading/signals",     description:"Créer un signal", payload:{pair:"string",direction:"BUY|SELL",entry_price:"float"}},
      {method:"GET",  route:"/ia-config/prompts",   description:"Liste des prompts IA", payload:null},
      {method:"POST", route:"/ia-config/prompts",   description:"Créer un prompt IA", payload:{name:"string",content:"string",return_format:"text|json|list|markdown"}}
    ]
  }
  exportJSON(tpl,'ia_endpoints_template.json')
}

// ── Drag & Drop ────────────────────────────────────────────
function setupDrop(zoneId, inputId, loadFn) {
  const zone = document.getElementById(zoneId)
  zone.addEventListener('dragover', e=>{ e.preventDefault(); zone.classList.add('over') })
  zone.addEventListener('dragleave',()=> zone.classList.remove('over'))
  zone.addEventListener('drop', e=>{
    e.preventDefault(); zone.classList.remove('over')
    const file = e.dataTransfer.files[0]; if(!file) return
    const input = document.getElementById(inputId)
    const dt = new DataTransfer(); dt.items.add(file); input.files = dt.files
    loadFn(input)
  })
}

// ── Helpers ────────────────────────────────────────────────
function fmtDate(d) {
  if(!d) return '—'
  try { return new Date(d).toLocaleDateString('fr-FR',{day:'2-digit',month:'short',year:'numeric'}) }
  catch { return d }
}
function escHtml(s) {
  return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
}
function exportJSON(data, filename) {
  if(!data) { toast('Aucune donnée à exporter','warn'); return }
  const blob = new Blob([JSON.stringify(data,null,2)], {type:'application/json'})
  const a = Object.assign(document.createElement('a'),{href:URL.createObjectURL(blob),download:filename})
  a.click(); URL.revokeObjectURL(a.href)
}

// ── Init ───────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  setupDrop('drop-tables',   'file-tables',   loadTablesFile)
  setupDrop('drop-endpoints','file-endpoints',loadEndpointsFile)
  loadPrompts()
  loadFunctions()
})
</script>
</body>
</html>