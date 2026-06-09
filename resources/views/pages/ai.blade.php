<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>IA Config — TradingBot Admin</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400,500" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════
   RESET + VARIABLES
   ═══════════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --bg:         #08080a;
  --bg-1:       #0c0c0f;
  --bg-2:       #101013;
  --bg-3:       #141418;
  --bg-sidebar: #0d0d0f;
  --border:     rgba(255,255,255,.07);
  --border-2:   rgba(255,255,255,.05);
  --border-3:   rgba(255,255,255,.03);
  --hover:      rgba(255,255,255,.04);
  --focus:      rgba(56,189,248,.45);
  --amber:      #f59e0b;
  --amber-bg:   rgba(245,158,11,.12);
  --amber-bd:   rgba(245,158,11,.28);
  --sky:        #38bdf8;
  --sky-bg:     rgba(56,189,248,.1);
  --green:      #34d399;
  --green-bg:   rgba(52,211,153,.1);
  --red:        #f87171;
  --red-bg:     rgba(248,113,113,.1);
  --teal:       #2dd4bf;
  --teal-bg:    rgba(45,212,191,.1);
  --violet:     #a78bfa;
  --violet-bg:  rgba(167,139,250,.1);
  --amber2:     #fbbf24;
  --amber2-bg:  rgba(251,191,36,.1);
  --txt:        #e4e4e7;
  --txt-2:      #a1a1aa;
  --txt-3:      #71717a;
  --txt-4:      #52525b;
  --txt-5:      #3f3f46;
  --sidebar-w:  200px;
  --topbar-h:   52px;
  --radius:     8px;
  --radius-lg:  12px;
  --ease:       cubic-bezier(.4,0,.2,1);
}

html, body { height: 100dvh; overflow: hidden; font-family: 'Geist', sans-serif; background: var(--bg); color: var(--txt); }

/* ═══════════════════════════════════════════
   LAYOUT RACINE
   ═══════════════════════════════════════════ */
#app  { display: flex; height: 100dvh; overflow: hidden; }
#main { flex: 1; min-width: 0; min-height: 0; display: flex; flex-direction: column; overflow: hidden; }

/* ═══════════════════════════════════════════
   SIDEBAR
   ═══════════════════════════════════════════ */
#sidebar-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.65); z-index: 199;
  backdrop-filter: blur(3px);
}
#sidebar-overlay.open { display: block; }

#sidebar {
  width: var(--sidebar-w); min-width: var(--sidebar-w);
  height: 100%; background: var(--bg-sidebar);
  border-right: 1px solid var(--border);
  display: flex; flex-direction: column;
  flex-shrink: 0; z-index: 200;
  transition: transform .25s var(--ease);
}
.sb-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 16px; border-bottom: 1px solid var(--border); flex-shrink: 0;
}
.sb-logo     { display: flex; align-items: center; gap: 8px; }
.sb-logo-ico {
  width: 24px; height: 24px;
  background: var(--amber-bg); border: 1px solid var(--amber-bd);
  border-radius: 6px; display: flex; align-items: center; justify-content: center;
  font-size: 11px;
}
.sb-logo-txt { font-size: 13px; font-weight: 500; color: #f4f4f5; }
#sb-close {
  display: none; align-items: center; justify-content: center;
  width: 26px; height: 26px; border-radius: 6px;
  background: rgba(255,255,255,.05); border: 1px solid var(--border);
  color: var(--txt-3); font-size: 12px; transition: all .15s; cursor: pointer;
}
#sb-close:hover { color: var(--txt); background: rgba(255,255,255,.1); }
.sb-nav   { flex: 1; padding: 8px; overflow-y: auto; display: flex; flex-direction: column; gap: 1px; }
.sb-label {
  font-size: 10px; font-weight: 500; color: var(--txt-5);
  text-transform: uppercase; letter-spacing: .06em;
  padding: 10px 10px 4px; display: block;
}
.sb-link {
  display: flex; align-items: center; gap: 9px;
  padding: 7px 10px; border-radius: var(--radius);
  font-size: 13px; color: var(--txt-4);
  transition: all .15s; background: none; border: none;
  width: 100%; text-decoration: none;
}
.sb-link:hover  { color: #d4d4d8; background: var(--hover); }
.sb-link.active { color: #f4f4f5; background: rgba(255,255,255,.07); }
.sb-link svg    { width: 14px; height: 14px; flex-shrink: 0; stroke: currentColor; fill: none; }
.sb-badge {
  margin-left: auto; font-size: 10px; padding: 1px 5px;
  border-radius: 5px; background: var(--sky-bg); color: var(--sky);
}
.sb-foot { padding: 10px 12px; border-top: 1px solid var(--border); flex-shrink: 0; }
.sb-user { display: flex; align-items: center; gap: 8px; }
.sb-av {
  width: 24px; height: 24px; border-radius: 50%;
  background: rgba(255,255,255,.07);
  display: flex; align-items: center; justify-content: center;
  font-size: 9px; font-weight: 600; color: var(--txt-4); flex-shrink: 0;
}

/* ═══════════════════════════════════════════
   TOPBAR
   ═══════════════════════════════════════════ */
#topbar {
  flex-shrink: 0; height: var(--topbar-h);
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 16px; background: var(--bg-1);
  border-bottom: 1px solid var(--border);
  gap: 8px; overflow: hidden;
}
.topbar-left  { display: flex; align-items: center; gap: 8px; flex: 1 1 auto; min-width: 0; overflow: hidden; }
.topbar-right { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }

#hamburger {
  display: none; align-items: center; justify-content: center;
  width: 30px; height: 30px; flex-shrink: 0;
  border-radius: var(--radius);
  background: rgba(255,255,255,.05); border: 1px solid var(--border);
  color: var(--txt-4); transition: all .15s; cursor: pointer;
}
#hamburger:hover { color: var(--txt); background: rgba(255,255,255,.1); }
#hamburger svg   { width: 15px; height: 15px; stroke: currentColor; fill: none; }

.topbar-title { font-size: 14px; font-weight: 500; color: white; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex-shrink: 1; }
.topbar-sep   { font-size: 12px; color: var(--txt-5); flex-shrink: 0; }
.topbar-sub   { font-size: 12px; color: var(--txt-5); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* ═══════════════════════════════════════════
   BOUTONS
   ═══════════════════════════════════════════ */
.btn-primary {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 12px; background: var(--amber-bg); border: 1px solid var(--amber-bd);
  border-radius: var(--radius); color: var(--amber); font-size: 12px; font-weight: 500;
  white-space: nowrap; transition: all .15s; cursor: pointer;
}
.btn-primary:hover { background: rgba(245,158,11,.2); }
.btn-primary svg { width: 11px; height: 11px; stroke: currentColor; fill: none; }

.btn-ghost {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 11px; background: rgba(255,255,255,.04); border: 1px solid var(--border);
  border-radius: var(--radius); color: var(--txt-4); font-size: 12px;
  transition: all .15s; cursor: pointer; white-space: nowrap;
}
.btn-ghost:hover { color: var(--txt); background: rgba(255,255,255,.08); }
.btn-ghost svg   { width: 11px; height: 11px; stroke: currentColor; fill: none; flex-shrink: 0; }

.btn-icon {
  display: inline-flex; align-items: center; justify-content: center;
  width: 28px; height: 28px;
  background: rgba(255,255,255,.04); border: 1px solid var(--border);
  border-radius: var(--radius); color: var(--txt-4); transition: all .15s; cursor: pointer;
}
.btn-icon:hover { color: var(--txt); background: rgba(255,255,255,.09); }
.btn-icon svg   { width: 12px; height: 12px; stroke: currentColor; fill: none; }

.btn-danger {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 6px 12px; font-size: 12px;
  background: var(--red-bg); color: var(--red);
  border: 1px solid rgba(248,113,113,.2);
  border-radius: var(--radius); cursor: pointer; transition: all .15s;
}
.btn-danger:hover { background: rgba(248,113,113,.18); }
.btn-danger svg { width: 11px; height: 11px; stroke: currentColor; fill: none; }

/* ═══════════════════════════════════════════
   INPUTS
   ═══════════════════════════════════════════ */
.inp, select.inp, textarea.inp {
  width: 100%; padding: 8px 10px;
  background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
  border-radius: var(--radius); color: var(--txt); font-size: 12px; font-family: inherit;
  outline: none; transition: border-color .15s;
}
.inp:focus, select.inp:focus, textarea.inp:focus { border-color: var(--focus); }
.inp::placeholder { color: var(--txt-5); }
textarea.inp  { resize: vertical; line-height: 1.6; font-family: 'Geist Mono', monospace; font-size: 12px; }
select.inp    { appearance: none; -webkit-appearance: none; cursor: pointer; }
select.inp option { background: #1a1a1c; }

/* ═══════════════════════════════════════════
   BADGES
   ═══════════════════════════════════════════ */
.badge  { display: inline-flex; align-items: center; padding: 2px 7px; border-radius: 99px; font-size: 10px; font-weight: 500; }
.bdg-g  { background: var(--green-bg);  color: var(--green);  }
.bdg-b  { background: var(--sky-bg);    color: var(--sky);    }
.bdg-a  { background: var(--amber2-bg); color: var(--amber2); }
.bdg-r  { background: var(--red-bg);    color: var(--red);    }
.bdg-v  { background: var(--violet-bg); color: var(--violet); }
.bdg-z  { background: rgba(255,255,255,.06); color: var(--txt-3); }

/* ═══════════════════════════════════════════
   TOGGLE
   ═══════════════════════════════════════════ */
.toggle {
  width: 30px; height: 17px;
  background: rgba(255,255,255,.1); border-radius: 99px;
  position: relative; cursor: pointer;
  transition: background .2s; flex-shrink: 0; border: none; padding: 0;
}
.toggle::after {
  content: ''; position: absolute;
  width: 13px; height: 13px; background: var(--txt-3);
  border-radius: 50%; top: 2px; left: 2px; transition: all .2s;
}
.toggle.on { background: rgba(56,189,248,.35); }
.toggle.on::after { left: 15px; background: var(--sky); }

/* ═══════════════════════════════════════════
   CARD
   ═══════════════════════════════════════════ */
.card {
  background: var(--bg-2); border: 1px solid var(--border-2);
  border-radius: var(--radius-lg);
}
.card-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 12px 16px; border-bottom: 1px solid var(--border-2); flex-shrink: 0;
}
.card-title { font-size: 13px; font-weight: 500; color: white; }

/* ═══════════════════════════════════════════
   STAT MINI
   ═══════════════════════════════════════════ */
.stats-row {
  display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px;
}
@media (max-width: 768px) { .stats-row { grid-template-columns: repeat(2,1fr); } }
.stat-m {
  background: rgba(255,255,255,.03); border: 1px solid var(--border-2);
  border-radius: var(--radius-lg); padding: 11px 14px;
}
.stat-lbl { font-size: 10px; color: var(--txt-4); margin-bottom: 6px; }
.stat-val { font-size: 20px; font-weight: 300; color: white; font-variant-numeric: tabular-nums; }

/* ═══════════════════════════════════════════
   PROMPT CARD (ia-card)
   ═══════════════════════════════════════════ */
.ia-card {
  background: var(--bg-2); border: 1px solid var(--border-2);
  border-radius: var(--radius-lg); padding: 14px;
  transition: border-color .15s; cursor: pointer;
}
.ia-card:hover    { border-color: rgba(255,255,255,.12); }
.ia-card.selected { border-color: rgba(56,189,248,.3); background: rgba(56,189,248,.02); }

/* Code block inline */
.code-block {
  background: rgba(255,255,255,.025); border: 1px solid var(--border-3);
  border-radius: var(--radius); padding: 10px 12px;
  font-family: 'Geist Mono', monospace; font-size: 11px; color: var(--txt-3);
  line-height: 1.7; overflow-x: auto; white-space: pre;
  max-height: 80px; overflow-y: auto;
}

/* Format pills */
.fmt-pill     { display: inline-flex; align-items: center; padding: 2px 7px; border-radius: 6px; font-size: 10px; font-weight: 500; }
.fmt-text     { background: rgba(255,255,255,.06); color: var(--txt-3); }
.fmt-json     { background: var(--amber2-bg); color: var(--amber2); }
.fmt-list     { background: var(--green-bg);  color: var(--green);  }
.fmt-markdown { background: var(--violet-bg); color: var(--violet); }

/* Inactif */
.ia-inactive { opacity: .45; }

/* ═══════════════════════════════════════════
   CONTENU
   ═══════════════════════════════════════════ */
#content {
  flex: 1; min-height: 0; overflow-y: auto; padding: 16px;
  display: flex; flex-direction: column; gap: 14px;
}

/* ═══════════════════════════════════════════
   MODAL
   ═══════════════════════════════════════════ */
.overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.7); z-index: 300;
  align-items: center; justify-content: center; padding: 16px;
  backdrop-filter: blur(4px);
}
.overlay.open { display: flex; }
.modal {
  background: var(--bg-3); border: 1px solid rgba(255,255,255,.1);
  border-radius: var(--radius-lg);
  width: min(620px, 100%); max-height: 92dvh;
  display: flex; flex-direction: column; overflow: hidden;
  box-shadow: 0 32px 80px rgba(0,0,0,.5);
  animation: fadein .16s var(--ease);
}
.modal-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 20px; border-bottom: 1px solid var(--border); flex-shrink: 0;
}
.modal-head h2 { font-size: 13px; font-weight: 500; color: white; }
.modal-body    { padding: 20px; overflow-y: auto; min-height: 0; display: flex; flex-direction: column; gap: 14px; }
.modal-foot    { display: flex; align-items: center; padding: 12px 20px; border-top: 1px solid var(--border); flex-shrink: 0; gap: 8px; }
.field-label   { font-size: 11px; color: var(--txt-4); margin-bottom: 6px; display: block; }

/* ═══════════════════════════════════════════
   TOAST
   ═══════════════════════════════════════════ */
#toast-container {
  position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
  display: flex; flex-direction: column; gap: 8px;
  z-index: 9999; pointer-events: none; align-items: center;
}
.toast {
  padding: 9px 18px; border-radius: var(--radius);
  background: var(--bg-3); border: 1px solid var(--border);
  font-size: 12px; color: var(--txt); white-space: nowrap;
  animation: fadein .2s; pointer-events: none;
}
.toast.success { border-color: rgba(52,211,153,.3);  color: var(--green); }
.toast.error   { border-color: rgba(248,113,113,.3); color: var(--red);   }
.toast.warn    { border-color: rgba(251,191,36,.3);  color: var(--amber2); }

/* ═══════════════════════════════════════════
   SCROLLBAR + ANIMATIONS
   ═══════════════════════════════════════════ */
::-webkit-scrollbar       { width: 3px; height: 3px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(255,255,255,.08); border-radius: 99px; }

@keyframes fadein { from { opacity:0; transform:translateY(4px); } to { opacity:1; transform:none; } }
.fadein { animation: fadein .18s var(--ease); }

/* ═══════════════════════════════════════════
   RESPONSIVE
   ═══════════════════════════════════════════ */
@media (max-width: 768px) {
  #hamburger  { display: flex; }
  #sidebar {
    position: fixed; top: 0; left: 0; height: 100%;
    transform: translateX(-100%); box-shadow: 4px 0 40px rgba(0,0,0,.6);
  }
  #sidebar.open { transform: translateX(0); }
  #sb-close   { display: flex; }
  #topbar     { padding: 0 10px; }
  .topbar-sub, .topbar-sep { display: none; }
  .btn-txt    { display: none; }
  #content    { padding: 10px; }
}
@media (min-width: 769px) { .btn-txt { display: inline; } }
</style>
</head>
<body>

<div id="toast-container"></div>
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<div id="app">

  <!-- ─── SIDEBAR ─── -->
  <aside id="sidebar">
    <div class="sb-head">
      <div class="sb-logo">
        <div class="sb-logo-ico">⚡</div>
        <span class="sb-logo-txt">TradingBot</span>
      </div>
      <button id="sb-close" onclick="closeSidebar()">✕</button>
    </div>
    <nav class="sb-nav">
      <p class="sb-label">Principal</p>
      <a href="/dashboard" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
        Dashboard
      </a>
      <a href="/categories" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
        Catégories
      </a>
      <a href="/chat" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Chat direct
      </a>
      <a href="/message" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M22 2 11 13"/><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
        Broadcast
      </a>
      <p class="sb-label">Trading</p>
      <a href="/trade" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
        Trade
      </a>
      <p class="sb-label">Croissance</p>
      <a href="/tache" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        Liens & Onboarding
      </a>
      
      <p class="sb-label">Outils</p>
      <a href="/form" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
        Formulaires
      </a>
    
      <a href="/ai" class="sb-link active">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/></svg>
        Agent IA
        <span class="sb-badge" id="sb-prompts-count" style="display:none;">0</span>
      </a>
    </nav>
    <div class="sb-foot">
      <div class="sb-user">
        <div class="sb-av">AD</div>
        <div>
          <p style="font-size:11px;font-weight:500;color:#d4d4d8;">Admin</p>
          <p style="font-size:10px;color:var(--txt-5);">fdkvip.com</p>
        </div>
      </div>
    </div>
  </aside>

  <!-- ─── MAIN ─── -->
  <div id="main">

    <!-- Topbar -->
    <header id="topbar">
      <div class="topbar-left">
        <button id="hamburger" onclick="openSidebar()">
          <svg viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <span class="topbar-title">Prompts IA</span>
        <span class="topbar-sep">·</span>
        <span class="topbar-sub">Gérer les prompts injectés dans l'intelligence artificielle</span>
      </div>
      <div class="topbar-right">
        <button class="btn-ghost" onclick="exportPrompts()">
          <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          <span class="btn-txt">Exporter .py</span>
        </button>
        <button class="btn-primary" onclick="openPromptModal()">
          <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
          <span class="btn-txt">Nouveau prompt</span>
        </button>
      </div>
    </header>

    <!-- Contenu -->
    <div id="content">

      <!-- Stats -->
      <div class="stats-row">
        <div class="stat-m">
          <p class="stat-lbl">Total prompts</p>
          <p class="stat-val" id="stat-total">0</p>
        </div>
        <div class="stat-m">
          <p class="stat-lbl">Actifs</p>
          <p class="stat-val" style="color:var(--green);" id="stat-active">0</p>
        </div>
        <div class="stat-m">
          <p class="stat-lbl">Formats utilisés</p>
          <p class="stat-val" style="font-size:13px;padding-top:4px;" id="stat-formats">—</p>
        </div>
        <div class="stat-m">
          <p class="stat-lbl">Dernier ajout</p>
          <p class="stat-val" style="font-size:13px;padding-top:4px;" id="stat-last">—</p>
        </div>
      </div>

      <!-- Bibliothèque -->
      <div class="card" style="overflow:hidden;">
        <div class="card-head">
          <div style="display:flex;align-items:center;gap:8px;">
            <span class="card-title">Bibliothèque de prompts</span>
            <span class="badge bdg-z" id="prompts-count-badge">0</span>
          </div>
          <select class="inp" style="width:140px;padding:5px 8px;font-size:11px;" onchange="filterPrompts(this.value)" id="fmt-filter">
            <option value="">Tous les formats</option>
            <option value="text">Texte</option>
            <option value="json">JSON</option>
            <option value="list">Liste</option>
            <option value="markdown">Markdown</option>
          </select>
        </div>
        <div id="prompts-list" style="padding:14px;display:flex;flex-direction:column;gap:10px;"></div>
        <div id="prompts-empty" style="padding:40px 20px;text-align:center;display:none;">
          <p style="font-size:13px;color:var(--txt-5);">Aucun prompt — créez le premier</p>
        </div>
      </div>

    </div><!-- /content -->
  </div><!-- /main -->
</div><!-- /app -->

<!-- ══════════════════════════════════════════
     MODAL PROMPT
     ══════════════════════════════════════════ -->
<div class="overlay" id="m-prompt">
  <div class="modal">
    <div class="modal-head">
      <h2 id="m-prompt-title">Nouveau prompt</h2>
      <button class="btn-icon" onclick="closeModal('m-prompt')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="p-id">
      <div>
        <label class="field-label">Nom du prompt</label>
        <input class="inp" id="p-name" placeholder="ex: Analyse comportement trader">
      </div>
      <div>
        <label class="field-label">Description courte</label>
        <input class="inp" id="p-desc" placeholder="À quoi sert ce prompt ?">
      </div>
      <div>
        <label class="field-label">Format de retour attendu</label>
        <select class="inp" id="p-format">
          <option value="text">Texte libre</option>
          <option value="json">JSON</option>
          <option value="list">Liste</option>
          <option value="markdown">Markdown</option>
        </select>
      </div>
      <div>
        <label class="field-label">Contenu du prompt</label>
        <textarea class="inp" id="p-content" rows="12" placeholder="Tu es un assistant spécialisé en trading..."></textarea>
      </div>
      <div style="display:flex;align-items:center;gap:8px;">
        <button class="toggle on" id="p-active-toggle" onclick="this.classList.toggle('on')"></button>
        <span style="font-size:12px;color:var(--txt-3);">Actif</span>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-danger" id="p-del-btn" style="display:none;" onclick="deletePrompt()">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>
        Supprimer
      </button>
      <div style="display:flex;gap:8px;margin-left:auto;">
        <button class="btn-ghost" onclick="closeModal('m-prompt')">Annuler</button>
        <button class="btn-primary" onclick="savePrompt()">Enregistrer</button>
      </div>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════
     SCRIPT
     ══════════════════════════════════════════ -->
<script>
/* ═══════════════════════════════════════════
   SIDEBAR
   ═══════════════════════════════════════════ */
function openSidebar() {
  document.getElementById('sidebar').classList.add('open');
  document.getElementById('sidebar-overlay').classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('sidebar-overlay').classList.remove('open');
  document.body.style.overflow = '';
}
window.addEventListener('resize', () => {
  if (window.innerWidth > 768) {
    document.getElementById('sidebar-overlay').classList.remove('open');
    document.body.style.overflow = '';
  }
});

/* ═══════════════════════════════════════════
   MODAL + TOAST
   ═══════════════════════════════════════════ */
function openModal(id)  { document.getElementById(id)?.classList.add('open'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('open'); }

document.addEventListener('click', e => {
  if (e.target.classList.contains('overlay')) e.target.classList.remove('open');
});
document.addEventListener('keydown', e => {
  if (e.key !== 'Escape') return;
  document.querySelectorAll('.overlay.open').forEach(m => m.classList.remove('open'));
  if (window.innerWidth <= 768) closeSidebar();
});

function toast(msg, type = 'success', dur = 2800) {
  const el = document.createElement('div');
  el.className = `toast ${type}`;
  el.textContent = msg;
  document.getElementById('toast-container').appendChild(el);
  setTimeout(() => { el.style.opacity = '0'; el.style.transition = 'opacity .2s'; setTimeout(() => el.remove(), 200); }, dur);
}

/* ═══════════════════════════════════════════
   API
   ═══════════════════════════════════════════ */
const API = 'https://52.90.21.131/ia-config';

async function apiFetch(path, opts = {}) {
  const res = await fetch(API + path, {
    headers: { 'Content-Type': 'application/json' }, ...opts
  });
  if (!res.ok) {
    const e = await res.json().catch(() => ({}));
    throw new Error(e.detail || `HTTP ${res.status}`);
  }
  return res.json();
}

/* ═══════════════════════════════════════════
   STATE
   ═══════════════════════════════════════════ */
let _prompts = [];
let _filter  = '';

/* ═══════════════════════════════════════════
   HELPERS
   ═══════════════════════════════════════════ */
function esc(s) {
  return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function fmtDate(d) {
  if (!d) return '—';
  try { return new Date(d).toLocaleDateString('fr-FR', { day:'2-digit', month:'short', year:'numeric' }); }
  catch { return d; }
}

const FMT_CLASS = { text:'fmt-text', json:'fmt-json', list:'fmt-list', markdown:'fmt-markdown' };
const FMT_LABEL = { text:'Texte', json:'JSON', list:'Liste', markdown:'Markdown' };

/* ═══════════════════════════════════════════
   LOAD + RENDER PROMPTS
   ═══════════════════════════════════════════ */
async function loadPrompts() {
  try {
    _prompts = await apiFetch('/prompts');
  } catch (e) {
    toast('Erreur chargement : ' + e.message, 'error');
    _prompts = [];
  }
  renderPrompts();
  updateStats();

  // Badge sidebar
  const badge = document.getElementById('sb-prompts-count');
  if (badge) {
    badge.textContent = _prompts.length;
    badge.style.display = _prompts.length ? '' : 'none';
  }
}

function renderPrompts() {
  const filtered = _filter ? _prompts.filter(p => p.return_format === _filter) : _prompts;
  const list  = document.getElementById('prompts-list');
  const empty = document.getElementById('prompts-empty');
  document.getElementById('prompts-count-badge').textContent = filtered.length;

  if (!filtered.length) {
    list.innerHTML = '';
    empty.style.display = '';
    return;
  }
  empty.style.display = 'none';

  list.innerHTML = filtered.map(p => `
    <div class="ia-card fadein ${p.is_active ? '' : 'ia-inactive'}" onclick="openPromptModal(${p.id})">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
        <div style="flex:1;min-width:0;">
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;flex-wrap:wrap;">
            <p style="font-size:12px;font-weight:500;color:white;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${esc(p.name)}</p>
            <span class="fmt-pill ${FMT_CLASS[p.return_format] || 'fmt-text'}">${FMT_LABEL[p.return_format] || p.return_format}</span>
            ${!p.is_active ? '<span class="badge bdg-z" style="font-size:9px;">Inactif</span>' : ''}
          </div>
          ${p.description ? `<p style="font-size:11px;color:var(--txt-4);margin-bottom:8px;">${esc(p.description)}</p>` : ''}
          <div class="code-block">${esc(p.content || '').substring(0, 280)}${(p.content || '').length > 280 ? '…' : ''}</div>
          <p style="font-size:10px;color:var(--txt-5);margin-top:8px;">${fmtDate(p.created_at)}</p>
        </div>
        <button class="btn-icon" style="flex-shrink:0;" onclick="event.stopPropagation();openPromptModal(${p.id})" title="Modifier">
          <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </button>
      </div>
    </div>
  `).join('');
}

function updateStats() {
  document.getElementById('stat-total').textContent  = _prompts.length;
  document.getElementById('stat-active').textContent = _prompts.filter(p => p.is_active).length;
  const fmts = [...new Set(_prompts.map(p => FMT_LABEL[p.return_format] || p.return_format))].join(', ');
  document.getElementById('stat-formats').textContent = fmts || '—';
  document.getElementById('stat-last').textContent    = _prompts[0] ? fmtDate(_prompts[0].created_at) : '—';
}

function filterPrompts(v) {
  _filter = v;
  renderPrompts();
}

/* ═══════════════════════════════════════════
   MODAL PROMPT — OUVRIR
   ═══════════════════════════════════════════ */
async function openPromptModal(id = null) {
  // Reset
  document.getElementById('p-id').value      = '';
  document.getElementById('p-name').value    = '';
  document.getElementById('p-desc').value    = '';
  document.getElementById('p-content').value = '';
  document.getElementById('p-format').value  = 'text';
  document.getElementById('p-active-toggle').classList.add('on');
  document.getElementById('p-del-btn').style.display = 'none';
  document.getElementById('m-prompt-title').textContent = 'Nouveau prompt';

  if (id !== null) {
    // Chercher dans le cache local d'abord
    const p = _prompts.find(x => x.id === id) ||
              await apiFetch('/prompts').then(d => d.find(x => x.id === id)).catch(() => null);
    if (!p) return;

    document.getElementById('p-id').value      = p.id;
    document.getElementById('p-name').value    = p.name;
    document.getElementById('p-desc').value    = p.description || '';
    document.getElementById('p-content').value = p.content || '';
    document.getElementById('p-format').value  = p.return_format || 'text';
    if (!p.is_active) document.getElementById('p-active-toggle').classList.remove('on');
    document.getElementById('p-del-btn').style.display = '';
    document.getElementById('m-prompt-title').textContent = 'Modifier le prompt';
  }

  openModal('m-prompt');
}

/* ═══════════════════════════════════════════
   MODAL PROMPT — SAUVEGARDER
   ═══════════════════════════════════════════ */
async function savePrompt() {
  const id = document.getElementById('p-id').value;
  const payload = {
    name:          document.getElementById('p-name').value.trim(),
    description:   document.getElementById('p-desc').value.trim(),
    content:       document.getElementById('p-content').value,
    return_format: document.getElementById('p-format').value,
    is_active:     document.getElementById('p-active-toggle').classList.contains('on') ? 1 : 0,
  };
  if (!payload.name) { toast('Nom requis', 'warn'); return; }

  try {
    if (id) {
      await apiFetch('/prompts/' + id, { method: 'PATCH', body: JSON.stringify(payload) });
      toast('Prompt mis à jour');
    } else {
      await apiFetch('/prompts', { method: 'POST', body: JSON.stringify(payload) });
      toast('Prompt créé');
    }
    closeModal('m-prompt');
    await loadPrompts();
  } catch (e) {
    toast('Erreur : ' + e.message, 'error');
  }
}

/* ═══════════════════════════════════════════
   MODAL PROMPT — SUPPRIMER
   ═══════════════════════════════════════════ */
async function deletePrompt() {
  const id = document.getElementById('p-id').value;
  if (!id || !confirm('Supprimer ce prompt ?')) return;
  try {
    await apiFetch('/prompts/' + id, { method: 'DELETE' });
    toast('Prompt supprimé', 'warn');
    closeModal('m-prompt');
    await loadPrompts();
  } catch (e) {
    toast('Erreur : ' + e.message, 'error');
  }
}

/* ═══════════════════════════════════════════
   EXPORT
   ═══════════════════════════════════════════ */
function exportPrompts() {
  window.open(API + '/export/prompts', '_blank');
}

/* ═══════════════════════════════════════════
   INIT
   ═══════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  loadPrompts();
});
</script>
</body>
</html>