<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TradingBot — Messages ciblés</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400" rel="stylesheet">
<style>

/* ════════════════════════════════════════════════════════
   RESET & VARIABLES — identique à chat-direct
   ════════════════════════════════════════════════════════ */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --bg:         #0c0c0e;
  --bg-1:       #0f0f11;
  --bg-2:       #141416;
  --bg-sidebar: #0d0d0f;
  --border:     rgba(255,255,255,.06);
  --hover:      rgba(255,255,255,.04);
  --amber:      #f59e0b;
  --amber-bg:   rgba(245,158,11,.12);
  --amber-bd:   rgba(245,158,11,.28);
  --sky:        #38bdf8;
  --green:      #34d399;
  --red:        #f87171;
  --teal:       #2dd4bf;
  --violet:     #a78bfa;
  --orange:     #fb923c;
  --txt:        #e4e4e7;
  --txt-2:      #a1a1aa;
  --txt-3:      #71717a;
  --txt-4:      #52525b;
  --txt-5:      #3f3f46;
  --sidebar-w:  200px;
  --topbar-h:   52px;
  --radius:     8px;
}

html, body {
  height: 100dvh;
  overflow: hidden;
  font-family: 'Geist', sans-serif;
  font-size: 13px;
  background: var(--bg);
  color: var(--txt);
  -webkit-font-smoothing: antialiased;
}

button  { font-family: inherit; cursor: pointer; }
a       { text-decoration: none; color: inherit; }
input, textarea, select { font-family: inherit; color: var(--txt); }

::-webkit-scrollbar       { width: 3px; height: 3px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 99px; }

/* ════════════════════════════════════════════════════════
   LAYOUT RACINE
   ════════════════════════════════════════════════════════ */
#app {
  display: flex;
  height: 100dvh;
  overflow: hidden;
}

/* ════════════════════════════════════════════════════════
   SIDEBAR — copie exacte de chat-direct
   ════════════════════════════════════════════════════════ */
#sidebar {
  width: var(--sidebar-w);
  min-width: var(--sidebar-w);
  height: 100%;
  background: var(--bg-sidebar);
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  flex-shrink: 0;
  overflow: hidden;
  transition: transform .25s ease;
  z-index: 200;
}

#sidebar-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.55);
  z-index: 199;
}
#sidebar-overlay.open { display: block; }

.sb-head {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 16px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}
.sb-logo      { display: flex; align-items: center; gap: 8px; }
.sb-logo-icon {
  width: 24px; height: 24px;
  background: var(--amber-bg); border: 1px solid var(--amber-bd);
  border-radius: 6px;
  display: flex; align-items: center; justify-content: center;
  font-size: 11px;
}
.sb-logo-text { font-size: 13px; font-weight: 500; color: #f4f4f5; }

#sidebar-close {
  display: none;
  align-items: center; justify-content: center;
  width: 26px; height: 26px;
  border-radius: 6px;
  background: rgba(255,255,255,.05);
  border: 1px solid var(--border);
  color: var(--txt-3);
  font-size: 12px;
  transition: all .15s;
}
#sidebar-close:hover { color: var(--txt); background: rgba(255,255,255,.1); }

.sb-nav   { flex: 1; padding: 8px; overflow-y: auto; display: flex; flex-direction: column; gap: 1px; }
.sb-label {
  font-size: 10px; font-weight: 500; color: var(--txt-5);
  text-transform: uppercase; letter-spacing: .06em;
  padding: 10px 10px 4px;
}
.sb-link {
  display: flex; align-items: center; gap: 9px;
  padding: 7px 10px; border-radius: var(--radius);
  font-size: 13px; color: var(--txt-4);
  transition: all .15s;
  background: none; border: none; width: 100%; text-align: left;
}
.sb-link:hover  { color: #d4d4d8; background: var(--hover); }
.sb-link.active { color: #f4f4f5; background: rgba(255,255,255,.07); }
.sb-link svg    { width: 14px; height: 14px; flex-shrink: 0; stroke: currentColor; fill: none; }

.sb-foot { padding: 10px 12px; border-top: 1px solid var(--border); flex-shrink: 0; }
.sb-user { display: flex; align-items: center; gap: 8px; }
.sb-av {
  width: 24px; height: 24px; border-radius: 50%;
  background: rgba(255,255,255,.07);
  display: flex; align-items: center; justify-content: center;
  font-size: 9px; font-weight: 600; color: var(--txt-4); flex-shrink: 0;
}

/* ════════════════════════════════════════════════════════
   MAIN
   ════════════════════════════════════════════════════════ */
#main { flex: 1; min-width: 0; display: flex; flex-direction: column; overflow: hidden; }

/* ── Topbar ──────────────────────────────────────────── */
#topbar {
  flex-shrink: 0;
  height: var(--topbar-h);
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 16px;
  background: var(--bg-1);
  border-bottom: 1px solid var(--border);
  gap: 8px; overflow: hidden;
}
/* Gauche : se réduit si besoin, ne déborde jamais */
.topbar-left {
  display: flex; align-items: center; gap: 8px;
  flex: 1 1 auto; min-width: 0; overflow: hidden;
}
/* Droite : bloc fixe, ne se compresse pas */
.topbar-right {
  display: flex; align-items: center; gap: 6px;
  flex-shrink: 0;
}

#hamburger {
  display: flex; align-items: center; justify-content: center;
  width: 30px; height: 30px; flex-shrink: 0;
  border-radius: var(--radius);
  background: rgba(255,255,255,.05);
  border: 1px solid var(--border);
  color: var(--txt-4); transition: all .15s;
}
#hamburger:hover { color: var(--txt); background: rgba(255,255,255,.1); }
#hamburger svg   { width: 15px; height: 15px; stroke: currentColor; fill: none; }

.topbar-title {
  font-size: 14px; font-weight: 500; color: white;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  flex-shrink: 1;
}
.topbar-sub {
  font-size: 12px; color: var(--txt-5);
  white-space: nowrap; flex-shrink: 2;
}

/* Tabs topbar — bloc compact, icône + label */
.topbar-tabs {
  display: flex; align-items: center; gap: 1px;
  background: rgba(255,255,255,.03);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 2px; flex-shrink: 0;
}
.topbar-tab {
  display: flex; align-items: center; gap: 5px;
  padding: 5px 10px; border-radius: 6px;
  font-size: 12px; color: var(--txt-4);
  background: none; border: none;
  transition: all .15s; white-space: nowrap;
}
.topbar-tab svg    { width: 11px; height: 11px; stroke: currentColor; fill: none; flex-shrink: 0; }
.topbar-tab:hover  { color: var(--txt-2); }
.topbar-tab.active { background: rgba(255,255,255,.08); color: var(--txt); }
/* Label masqué sur petits écrans via media query */
.tab-label { display: inline; }

/* Notif bell */
.notif-wrap { position: relative; }
.notif-dot {
  position: absolute; top: 7px; right: 7px;
  width: 6px; height: 6px;
  border-radius: 50%; background: var(--red);
  border: 1.5px solid var(--bg-1);
}
.notif-panel {
  position: absolute; top: calc(100% + 8px); right: 0;
  width: 300px;
  background: var(--bg-2); border: 1px solid var(--border);
  border-radius: 12px;
  box-shadow: 0 8px 32px rgba(0,0,0,.5);
  z-index: 500; overflow: hidden;
  display: none;
}
.notif-panel.open { display: block; }
.notif-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 12px 16px; border-bottom: 1px solid var(--border);
}
.notif-item {
  display: flex; align-items: flex-start; gap: 12px;
  padding: 11px 16px;
  border-bottom: 1px solid rgba(255,255,255,.04);
  border-left: 2px solid transparent;
  cursor: pointer; transition: background .1s;
}
.notif-item:hover { background: var(--hover); }
.notif-item:last-child { border-bottom: none; }

/* ── Contenu principal ───────────────────────────────── */
#page-content {
  flex: 1; overflow-y: auto;
  padding: 16px;
  display: flex; flex-direction: column; gap: 14px;
}

/* ════════════════════════════════════════════════════════
   VUES
   ════════════════════════════════════════════════════════ */
#view-compose {
  display: flex; gap: 14px; align-items: flex-start;
}
#view-history {
  display: none; flex-direction: column; gap: 14px;
}

/* ── Formulaire ──────────────────────────────────────── */
.compose-form    { flex: 1; min-width: 0; display: flex; flex-direction: column; gap: 14px; }
.compose-sidebar { width: 280px; flex-shrink: 0; display: flex; flex-direction: column; gap: 14px; }

/* ── Cards ───────────────────────────────────────────── */
.card {
  background: var(--bg-1);
  border: 1px solid var(--border);
  border-radius: 12px;
  padding: 16px;
}

.section-label {
  font-size: 11px; font-weight: 500; color: var(--txt-4);
  text-transform: uppercase; letter-spacing: .06em;
  margin-bottom: 14px;
}

/* ── Grilles boutons format/dest ─────────────────────── */
.dest-grid, .format-grid {
  display: grid; gap: 6px; margin-bottom: 14px;
}
.dest-grid   { grid-template-columns: repeat(3, 1fr); }
.format-grid { grid-template-columns: repeat(5, 1fr); }

.format-btn {
  display: flex; flex-direction: column; align-items: center; gap: 5px;
  padding: 10px 8px;
  background: rgba(255,255,255,.03);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  font-size: 11px; color: var(--txt-4);
  transition: all .15s;
}
.format-btn:hover  { color: var(--txt-2); border-color: rgba(255,255,255,.12); background: rgba(255,255,255,.05); }
.format-btn.active { color: var(--amber); border-color: var(--amber-bd); background: var(--amber-bg); }
.format-btn svg    { width: 15px; height: 15px; stroke: currentColor; fill: none; flex-shrink: 0; }

/* ── Inputs ──────────────────────────────────────────── */
.inp {
  width: 100%;
  padding: 8px 10px;
  background: rgba(255,255,255,.04);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  font-size: 12px; color: var(--txt);
  outline: none; transition: border-color .15s;
}
.inp:focus        { border-color: var(--amber-bd); }
.inp::placeholder { color: var(--txt-5); }

textarea.inp { resize: vertical; line-height: 1.6; }
select.inp   { cursor: pointer; }

/* ── Zone upload ─────────────────────────────────────── */
.upload-zone {
  border: 1.5px dashed rgba(255,255,255,.12);
  border-radius: var(--radius);
  padding: 20px;
  text-align: center;
  cursor: pointer;
  transition: all .2s;
  font-size: 12px; color: var(--txt-4);
}
.upload-zone:hover { border-color: rgba(56,189,248,.4); background: rgba(56,189,248,.04); }
.upload-zone svg   { display: block; margin: 0 auto 8px; stroke: var(--txt-4); fill: none; }

/* ── Variables chips ─────────────────────────────────── */
.var-chip {
  display: inline-flex; align-items: center;
  padding: 3px 8px;
  background: rgba(255,255,255,.05);
  border: 1px solid var(--border);
  border-radius: 5px;
  font-size: 11px; font-family: 'Geist Mono', monospace;
  color: var(--sky); cursor: pointer;
  transition: all .15s;
}
.var-chip:hover { background: rgba(56,189,248,.1); border-color: rgba(56,189,248,.3); }

/* ── Filter tag ──────────────────────────────────────── */
.filter-tag {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 3px 8px;
  background: rgba(56,189,248,.08);
  border: 1px solid rgba(56,189,248,.2);
  border-radius: 5px;
  font-size: 11px; color: var(--sky);
}
.filter-tag button {
  background: none; border: none;
  color: var(--sky); font-size: 11px; line-height: 1;
  opacity: .7;
}
.filter-tag button:hover { opacity: 1; }

/* ── Options grid ────────────────────────────────────── */
.options-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
}

/* ── Telegram preview ────────────────────────────────── */
.tg-preview {
  background: #1a2535;
  border-radius: 10px;
  overflow: hidden;
}
.tg-header {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 14px;
  background: #1e2d42;
  border-bottom: 1px solid rgba(255,255,255,.06);
}
.tg-body   { padding: 12px 14px; }
.tg-bubble {
  display: inline-block;
  background: #2b5278;
  border-radius: 12px 12px 2px 12px;
  padding: 8px 12px;
  font-size: 12px; color: #e2e8f0; line-height: 1.5;
  max-width: 100%; word-break: break-word;
}
.tg-time   { font-size: 10px; color: #4a6478; margin-top: 5px; text-align: right; }

/* ── Stat bar ────────────────────────────────────────── */
.stat-bar-track { height: 3px; background: rgba(255,255,255,.06); border-radius: 99px; }
.stat-bar-fill  { height: 100%; border-radius: 99px; transition: width .3s; }

/* ── Historique table ────────────────────────────────── */
.camp-table-header, .camp-row {
  display: grid;
  grid-template-columns: 1fr 80px 90px 70px 70px 80px 32px;
  align-items: center;
  padding: 10px 16px;
  gap: 8px;
}
.camp-table-header {
  font-size: 10px; font-weight: 500; color: var(--txt-4);
  text-transform: uppercase; letter-spacing: .06em;
  border-bottom: 1px solid var(--border);
  background: rgba(255,255,255,.02);
}
.camp-row {
  font-size: 12px;
  border-bottom: 1px solid var(--border);
  cursor: pointer; transition: background .1s;
}
.camp-row:hover         { background: var(--hover); }
.camp-row:last-child    { border-bottom: none; }

.history-stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 10px;
}

/* ── Modal envoi en cours ─────────────────────────────── */
#modal-sending .send-progress {
  width: 100%;
  height: 3px;
  background: rgba(255,255,255,.06);
  border-radius: 99px;
  overflow: hidden;
  margin: 14px 0;
}
#modal-sending .send-progress-bar {
  height: 100%;
  border-radius: 99px;
  background: var(--amber);
  animation: sendprog 2s ease infinite;
  transform-origin: left;
}
@keyframes sendprog {
  0%   { width: 0%; }
  60%  { width: 85%; }
  100% { width: 95%; }
}

/* ── Preview mobile only ─────────────────────────────── */
.preview-mobile-only { display: none; }

/* ════════════════════════════════════════════════════════
   COMPOSANTS COMMUNS
   ════════════════════════════════════════════════════════ */
.btn-primary {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 12px;
  background: var(--amber-bg); border: 1px solid var(--amber-bd);
  border-radius: var(--radius);
  color: var(--amber); font-size: 12px; font-weight: 500;
  white-space: nowrap; transition: all .15s;
}
.btn-primary:hover    { background: rgba(245,158,11,.2); border-color: rgba(245,158,11,.5); }
.btn-primary:disabled { opacity: .45; cursor: not-allowed; }
.btn-primary svg      { width: 11px; height: 11px; stroke: currentColor; fill: none; flex-shrink: 0; }

.btn-ghost {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 12px;
  background: rgba(255,255,255,.04); border: 1px solid var(--border);
  border-radius: var(--radius);
  color: var(--txt-4); font-size: 12px;
  transition: all .15s;
}
.btn-ghost:hover { color: var(--txt); background: rgba(255,255,255,.08); }
.btn-ghost svg   { width: 11px; height: 11px; stroke: currentColor; fill: none; flex-shrink: 0; }

.btn-icon {
  display: inline-flex; align-items: center; justify-content: center;
  width: 28px; height: 28px;
  background: rgba(255,255,255,.04); border: 1px solid var(--border);
  border-radius: var(--radius);
  color: var(--txt-4); transition: all .15s; flex-shrink: 0;
}
.btn-icon:hover { color: var(--txt); background: rgba(255,255,255,.09); }
.btn-icon svg   { width: 13px; height: 13px; stroke: currentColor; fill: none; }

.badge {
  display: inline-flex; align-items: center;
  padding: 2px 6px; border-radius: 5px;
  font-size: 10px; font-weight: 500;
}
.badge-sky   { background: rgba(56,189,248,.12);  color: var(--sky); }
.badge-green { background: rgba(52,211,153,.12);  color: var(--green); }
.badge-amber { background: rgba(251,191,36,.12);  color: var(--amber); }
.badge-red   { background: rgba(248,113,113,.12); color: var(--red); }
.badge-zinc  { background: rgba(255,255,255,.07); color: var(--txt-2); }

.toggle {
  position: relative; width: 30px; height: 17px;
  border-radius: 99px; background: rgba(255,255,255,.1);
  border: none; cursor: pointer; flex-shrink: 0; transition: background .2s;
}
.toggle::after {
  content: ''; position: absolute;
  top: 2px; left: 2px;
  width: 13px; height: 13px; border-radius: 50%;
  background: var(--txt-3); transition: all .2s;
}
.toggle.on { background: rgba(45,212,191,.25); }
.toggle.on::after { left: 15px; background: var(--teal); }

/* Modals */
.modal-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.55);
  z-index: 300;
  align-items: center; justify-content: center;
  padding: 16px;
}
.modal-overlay.open { display: flex; }

.modal {
  background: var(--bg-2); border: 1px solid var(--border);
  border-radius: 12px;
  width: min(520px, 100%);
  max-height: 90dvh;
  display: flex; flex-direction: column; overflow: hidden;
}
.modal-sm { width: min(380px, 100%); }

.modal-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 20px; border-bottom: 1px solid var(--border); flex-shrink: 0;
}
.modal-head h2 { font-size: 13px; font-weight: 500; color: white; }
.modal-body    { padding: 18px 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 14px; }
.modal-foot    {
  display: flex; align-items: center; justify-content: flex-end;
  gap: 8px; padding: 12px 20px;
  border-top: 1px solid var(--border); flex-shrink: 0;
}
.modal-label { font-size: 12px; color: var(--txt-4); margin-bottom: 7px; }
.modal-tip {
  background: rgba(45,212,191,.05); border: 1px solid rgba(45,212,191,.12);
  border-radius: var(--radius); padding: 10px 13px;
  font-size: 11px; color: #5eead4;
}
.divider { height: 1px; background: var(--border); margin: 2px 0; }

/* Animations */
@keyframes fadein {
  from { opacity: 0; transform: translateY(4px); }
  to   { opacity: 1; transform: translateY(0); }
}
.fadein { animation: fadein .18s ease; }

/* Toast */
#toast-container {
  position: fixed; bottom: 20px; right: 20px; z-index: 9999;
  display: flex; flex-direction: column; gap: 8px; pointer-events: none;
}
.toast {
  padding: 10px 16px; border-radius: 9px; font-size: 12px;
  pointer-events: auto; max-width: 320px;
  box-shadow: 0 4px 16px rgba(0,0,0,.4);
  animation: fadein .2s ease;
}


/* ── Barre secondaire (tabs) — mobile uniquement ─── */
#topbar-mobile-tabs {
  display: none; /* caché par défaut */
  flex-shrink: 0;
  align-items: center;
  padding: 0 12px;
  height: 40px;
  background: var(--bg-1);
  border-bottom: 1px solid var(--border);
  gap: 4px;
}

/* ════════════════════════════════════════════════════════
   RESPONSIVE
   ════════════════════════════════════════════════════════ */
@media (max-width: 1024px) {
  .compose-sidebar        { display: none; }
  .preview-mobile-only    { display: block; }
  .history-stats-grid     { grid-template-columns: repeat(2, 1fr); }
  .camp-table-header,
  .camp-row               { grid-template-columns: 1fr 70px 80px 60px 30px; }
  .camp-table-header > span:nth-child(4),
  .camp-row > span:nth-child(4)  { display: none; }
}

@media (max-width: 560px) {
  .topbar-sub { display: none; }
}

@media (max-width: 700px) {
  /* Sidebar overlay */
  #sidebar {
    position: fixed; top: 0; left: 0; height: 100%;
    transform: translateX(-100%);
  }
  #sidebar.open { transform: translateX(0); }
  #sidebar-overlay.open { display: block; }
  #sidebar-close { display: flex; }

  #topbar { padding: 0 10px; gap: 6px; }
  .topbar-sub { display: none; }
  .topbar-title { font-size: 13px; }
  /* Masquer les tabs dans la topbar sur mobile */
  #topbar .topbar-tabs { display: none; }
  /* Afficher la barre secondaire sous la topbar */
  #topbar-mobile-tabs { display: flex; }

  #page-content { padding: 10px; gap: 10px; }

  #view-compose       { flex-direction: column; }
  .format-grid        { grid-template-columns: repeat(3, 1fr); }
  .options-grid       { grid-template-columns: 1fr; }
  .history-stats-grid { grid-template-columns: repeat(2, 1fr); }

  /* Table → cards sur mobile */
  .camp-table-desktop { display: none; }
  .camp-cards-mobile  { display: flex !important; }

  .modal { width: min(100vw - 16px, 520px); }
  .topbar-tab span { display: none; }
}

@media (max-width: 380px) {
  .dest-grid   { grid-template-columns: 1fr 1fr; }
  .format-grid { grid-template-columns: repeat(2, 1fr); }
}

</style>
</head>

<body>

<!-- Toast container -->
<div id="toast-container"></div>

<!-- ══════════════════════════════════════════════
     OVERLAY SIDEBAR
     ══════════════════════════════════════════════ -->
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<div id="app">

  <!-- ══════════════════════════════════════════════
       SIDEBAR (identique à chat-direct)
       ══════════════════════════════════════════════ -->
  <aside id="sidebar">
    <div class="sb-head">
      <div class="sb-logo">
        <div class="sb-logo-icon">⚡</div>
        <span class="sb-logo-text">TradingBot</span>
      </div>
      <button id="sidebar-close" onclick="closeSidebar()">✕</button>
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
      <a href="/message" class="sb-link active">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M22 2 11 13"/><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
        Broadcast
      </a>
      <p class="sb-label">Trading</p>
      <a href="/trade" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
        Trade
      </a>
      <p class="sb-label">Outils</p>
      <a href="/form" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Formulaires
      </a>
      <a href="/ai" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/></svg>
        Agent IA
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

  <!-- ══════════════════════════════════════════════
       MAIN
       ══════════════════════════════════════════════ -->
  <div id="main">

    <!-- Topbar -->
    <header id="topbar">
      <div class="topbar-left">
        <button id="hamburger" onclick="openSidebar()">
          <svg viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <span class="topbar-title">Messages ciblés</span>
        <span class="topbar-sub">· Broadcast Telegram</span>
      </div>
      <div class="topbar-right">

        <!-- Tabs Composer / Historique -->
        <div class="topbar-tabs">
          <button class="topbar-tab active" onclick="switchView('compose', this)">
            <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M22 2 11 13"/><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
            <span class="tab-label">Composer</span>
          </button>
          <button class="topbar-tab" onclick="switchView('history', this)">
            <svg viewBox="0 0 24 24" stroke-width="1.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
            <span class="tab-label">Historique</span>
          </button>
        </div>

        <!-- Notif bell -->
        <div class="notif-wrap">
          <button class="btn-icon" id="bell-btn" onclick="toggleNotif()" style="position:relative;">
            <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            <span class="notif-dot"></span>
          </button>
          <div class="notif-panel" id="notif-panel">
            <div class="notif-head">
              <span style="font-size:12px;font-weight:500;color:white;">Notifications</span>
              <button onclick="clearNotifs()" style="font-size:11px;color:var(--sky);background:none;border:none;cursor:pointer;">Tout lire</button>
            </div>
            <div style="max-height:280px;overflow-y:auto;">
              <div class="notif-item" style="border-left-color:var(--red);">
                <span style="width:6px;height:6px;border-radius:50%;background:var(--red);margin-top:4px;flex-shrink:0;display:block;"></span>
                <div><p style="font-size:12px;font-weight:500;color:#e4e4e7;">23 abonnements expirent</p><p style="font-size:10px;color:var(--txt-4);margin-top:2px;">Dans 7 jours · il y a 2h</p></div>
              </div>
              <div class="notif-item" style="border-left-color:var(--amber);">
                <span style="width:6px;height:6px;border-radius:50%;background:var(--amber);margin-top:4px;flex-shrink:0;display:block;"></span>
                <div><p style="font-size:12px;font-weight:500;color:#e4e4e7;">Agent IA — 4 escalades</p><p style="font-size:10px;color:var(--txt-4);margin-top:2px;">Intervention requise · il y a 35min</p></div>
              </div>
              <div class="notif-item" style="border-left-color:var(--sky);">
                <span style="width:6px;height:6px;border-radius:50%;background:var(--sky);margin-top:4px;flex-shrink:0;display:block;"></span>
                <div><p style="font-size:12px;font-weight:500;color:#e4e4e7;">Témoignage vidéo · Lucie B.</p><p style="font-size:10px;color:var(--txt-4);margin-top:2px;">Score 94% · il y a 1h</p></div>
              </div>
              <div class="notif-item" style="border-left-color:var(--amber);">
                <span style="width:6px;height:6px;border-radius:50%;background:var(--amber);margin-top:4px;flex-shrink:0;display:block;"></span>
                <div><p style="font-size:12px;font-weight:500;color:#e4e4e7;">67 membres inactifs +21j</p><p style="font-size:10px;color:var(--txt-4);margin-top:2px;">Relance recommandée · hier</p></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Bouton Envoyer principal -->
        <button class="btn-primary" id="btn-send" onclick="openConfirmModal()">
          <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
          <span class="topbar-btn-label">Envoyer</span>
        </button>

      </div>
    </header>

    <!-- Barre secondaire — tabs visible uniquement sur mobile -->
    <div id="topbar-mobile-tabs">
      <button class="topbar-tab active" id="mob-tab-compose" onclick="switchView('compose', this); syncTabs(this, 'compose')">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M22 2 11 13"/><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
        <span>Composer</span>
      </button>
      <button class="topbar-tab" id="mob-tab-history" onclick="switchView('history', this); syncTabs(this, 'history')">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        <span>Historique</span>
      </button>
    </div>

    <!-- ── PAGE CONTENT ── -->
    <main id="page-content">

      <!-- ════ VUE COMPOSER ════ -->
      <div id="view-compose">

        <!-- Colonne formulaire -->
        <div class="compose-form">

          <!-- 1. Destinataires -->
          <div class="card">
            <p class="section-label">1 · Destinataires</p>

            <div class="dest-grid">
              <button class="format-btn active" id="dest-category" onclick="switchDest('category',this)">
                <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                Par catégorie
              </button>
              <button class="format-btn" id="dest-ids" onclick="switchDest('ids',this)">
                <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                IDs manuels
              </button>
              <button class="format-btn" id="dest-all" onclick="switchDest('all',this)">
                <svg viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                Tous
              </button>
            </div>

            <!-- Catégorie -->
            <div id="dest-block-category">
              <select class="inp" style="margin-bottom:10px;" onchange="updateSummary()">
                <option value="">Sélectionner une catégorie...</option>
              </select>

              <div>
                <button class="btn-ghost" style="font-size:11px;margin-bottom:10px;" onclick="toggleFilters()">
                  <svg viewBox="0 0 24 24" stroke-width="2"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                  Filtres avancés
                  <span id="filter-count" class="badge badge-sky" style="font-size:10px;display:none;"></span>
                </button>
                <div id="filters-panel" style="display:none;" class="flex flex-col gap-3">
                  <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div>
                      <p style="font-size:10px;color:var(--txt-4);margin-bottom:6px;">Inscrit après</p>
                      <input type="date" class="inp" style="font-size:12px;">
                    </div>
                    <div>
                      <p style="font-size:10px;color:var(--txt-4);margin-bottom:6px;">Inscrit avant</p>
                      <input type="date" class="inp" style="font-size:12px;">
                    </div>
                  </div>
                  <div style="display:flex;flex-wrap:wrap;gap:6px;" id="active-filters">
                    <span class="filter-tag">inscrit après 01/01/2025 <button onclick="removeFilter(this)">✕</button></span>
                    <span class="filter-tag">inscrit avant 01/01/2026 <button onclick="removeFilter(this)">✕</button></span>
                  </div>
                </div>
              </div>

              <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);">
                <p style="font-size:10px;color:var(--txt-4);margin-bottom:6px;">Exclure des user_ids</p>
                <input class="inp" type="text" placeholder="ex: 789, 1042, 2310" style="font-size:12px;font-family:'Geist Mono',monospace;">
              </div>
            </div>

            <!-- IDs manuels -->
            <div id="dest-block-ids" style="display:none;">
              <p style="font-size:10px;color:var(--txt-4);margin-bottom:6px;">user_ids — séparés par virgules</p>
              <textarea class="inp" style="min-height:64px;font-family:'Geist Mono',monospace;font-size:12px;" placeholder="123, 456, 789, 1042..."></textarea>
            </div>

            <!-- Tous -->
            <div id="dest-block-all" style="display:none;">
              <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:rgba(251,191,36,.07);border:1px solid rgba(251,191,36,.18);border-radius:var(--radius);">
                <svg style="width:14px;height:14px;stroke:#fbbf24;fill:none;flex-shrink:0;" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
                <p style="font-size:12px;color:#fbbf24;">Message envoyé à tous les membres</p>
              </div>
            </div>
          </div>

          <!-- 2. Format & Contenu -->
          <div class="card">
            <p class="section-label">2 · Format & contenu</p>

            <div class="format-grid">
              <button class="format-btn active" onclick="switchFormat('text',this)" id="fmt-text">
                <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
                Texte
              </button>
              <button class="format-btn" onclick="switchFormat('image',this)" id="fmt-image">
                <svg viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                Image
              </button>
              <button class="format-btn" onclick="switchFormat('video',this)" id="fmt-video">
                <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 8-6 4 6 4V8z"/><rect x="2" y="6" width="14" height="12" rx="2"/></svg>
                Vidéo
              </button>
              <button class="format-btn" onclick="switchFormat('image+text',this)" id="fmt-imagetext">
                <svg viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                Img+texte
              </button>
              <button class="format-btn" onclick="switchFormat('video+text',this)" id="fmt-videotext">
                <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 8-6 4 6 4V8z"/><rect x="2" y="6" width="14" height="12" rx="2"/></svg>
                Vid+texte
              </button>
            </div>

            <!-- Upload média -->
            <div id="media-upload" style="display:none;margin-bottom:14px;">
              <p style="font-size:10px;color:var(--txt-4);margin-bottom:8px;">Fichier média</p>
              <div class="upload-zone" onclick="triggerUpload()">
                <svg width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5">
                  <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                  <polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/>
                </svg>
                <p style="font-size:12px;">Glisser un fichier ou <span style="color:var(--sky);">parcourir</span></p>
                <p style="font-size:10px;margin-top:4px;color:var(--txt-5);">ou coller un file_id Telegram</p>
              </div>
              <input class="inp" type="text" placeholder="file_id Telegram (ex: AgACAgIAAxkBAAI...)"
                     style="font-size:12px;font-family:'Geist Mono',monospace;margin-top:8px;">
            </div>

            <!-- Texte -->
            <div id="text-block">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <p style="font-size:10px;color:var(--txt-4);">Message — variables : +prenom, +offre, +lien...</p>
                <span style="font-size:10px;color:var(--txt-5);font-variant-numeric:tabular-nums;" id="char-count">0 / 4096</span>
              </div>
              <textarea class="inp" id="msg-textarea" style="min-height:110px;font-size:13px;line-height:1.6;"
                        placeholder="Bonjour +prenom, ..."
                        oninput="updatePreview();updateCount(this)"></textarea>
            </div>

            <!-- Variables -->
            <div style="margin-top:12px;">
              <p style="font-size:10px;color:var(--txt-4);margin-bottom:8px;">Variables — cliquer pour insérer</p>
              <div style="display:flex;flex-wrap:wrap;gap:6px;">
                <span class="var-chip" onclick="insertVar('+prenom')">+prenom</span>
                <span class="var-chip" onclick="insertVar('+offre')">+offre</span>
                <span class="var-chip" onclick="insertVar('+lien')">+lien</span>
                <span class="var-chip" onclick="insertVar('+perf')">+perf</span>
                <span class="var-chip" onclick="insertVar('+date')">+date</span>
                <span class="var-chip" onclick="insertVar('+plan')">+plan</span>
              </div>
            </div>

            <!-- Variables personnalisées -->
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                <p style="font-size:10px;color:var(--txt-4);">Variables personnalisées</p>
                <button class="btn-ghost" style="font-size:10px;padding:3px 8px;" onclick="addVarRow()">+ Ajouter</button>
              </div>
              <div id="custom-vars" style="display:flex;flex-direction:column;gap:8px;">
                <div style="display:flex;align-items:center;gap:8px;">
                  <input class="inp" type="text" placeholder="+offre" style="width:110px;font-size:12px;font-family:'Geist Mono',monospace;flex-shrink:0;">
                  <span style="color:var(--txt-5);font-size:12px;">→</span>
                  <input class="inp" type="text" placeholder="50%" style="font-size:12px;">
                  <button class="btn-icon" style="width:24px;height:24px;" onclick="this.closest('div').remove()">
                    <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- 3. Options d'envoi -->
          <div class="card">
            <p class="section-label">3 · Options d'envoi</p>
            <div class="options-grid">
              <div>
                <p style="font-size:10px;color:var(--txt-4);margin-bottom:6px;">Délai entre envois (secondes)</p>
                <input class="inp" type="number" value="0.1" step="0.1" min="0.05" style="font-size:13px;" oninput="updateSummary()">
                <p style="font-size:10px;color:var(--txt-5);margin-top:4px;">Recommandé 0.1s · min 0.05s</p>
              </div>
              <div>
                <p style="font-size:10px;color:var(--txt-4);margin-bottom:6px;">Tag de campagne</p>
                <input class="inp" id="tag-input" type="text" placeholder="ex: promo_avril" style="font-size:13px;font-family:'Geist Mono',monospace;">
              </div>
              <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 12px;background:rgba(255,255,255,.025);border:1px solid var(--border);border-radius:var(--radius);">
                <div>
                  <p style="font-size:12px;color:#d4d4d8;">Retry automatique</p>
                  <p style="font-size:10px;color:var(--txt-4);margin-top:2px;">Réessaie en cas d'échec</p>
                </div>
                <button class="toggle on" id="toggle-retry" onclick="this.classList.toggle('on')"></button>
              </div>
              <div>
                <p style="font-size:10px;color:var(--txt-4);margin-bottom:6px;">Webhook de fin</p>
                <input class="inp" id="webhook-input" type="text" placeholder="https://monsite.com/webhook" style="font-size:12px;">
              </div>
            </div>

            <!-- Planification -->
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;">
              <div>
                <p style="font-size:12px;color:#d4d4d8;">Envoi planifié</p>
                <p style="font-size:11px;color:var(--txt-4);margin-top:2px;" id="schedule-display">Envoi immédiat</p>
              </div>
              <button class="btn-ghost" style="font-size:11px;" onclick="openModal('modal-schedule')">
                <svg viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                Planifier
              </button>
            </div>
          </div>

          <!-- Preview mobile -->
          <div class="card preview-mobile-only">
            <p class="section-label">Aperçu Telegram</p>
            <div class="tg-preview">
              <div class="tg-header">
                <div style="width:28px;height:28px;border-radius:50%;background:#0ea5e9;flex-shrink:0;"></div>
                <div>
                  <p style="font-size:12px;font-weight:500;color:#e2e8f0;">TradingBot</p>
                  <p style="font-size:10px;color:#4a6478;">bot</p>
                </div>
              </div>
              <div class="tg-body">
                <div id="preview-media-mobile" style="display:none;margin-bottom:6px;">
                  <div style="background:rgba(255,255,255,.07);border-radius:8px;height:80px;display:flex;align-items:center;justify-content:center;">
                    <svg style="width:24px;height:24px;stroke:#4a6478;fill:none;" viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                  </div>
                </div>
                <div class="tg-bubble" id="preview-text-mobile">Bonjour <span style="color:#7dd3fc;">Marc</span>, votre message apparaîtra ici...</div>
                <p class="tg-time">14:30 ✓✓</p>
              </div>
            </div>
          </div>

        </div><!-- /compose-form -->

        <!-- Colonne droite (desktop) -->
        <div class="compose-sidebar">

          <div class="card">
            <p class="section-label">Aperçu Telegram</p>
            <div class="tg-preview">
              <div class="tg-header">
                <div style="width:28px;height:28px;border-radius:50%;background:#0ea5e9;flex-shrink:0;"></div>
                <div>
                  <p style="font-size:12px;font-weight:500;color:#e2e8f0;">TradingBot</p>
                  <p style="font-size:10px;color:#4a6478;">bot</p>
                </div>
              </div>
              <div class="tg-body">
                <div id="preview-media" style="display:none;margin-bottom:6px;">
                  <div style="background:rgba(255,255,255,.07);border-radius:8px;height:80px;display:flex;align-items:center;justify-content:center;">
                    <svg style="width:24px;height:24px;stroke:#4a6478;fill:none;" viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                  </div>
                </div>
                <div class="tg-bubble" id="preview-text">Bonjour <span style="color:#7dd3fc;">Marc</span>, votre message apparaîtra ici...</div>
                <p class="tg-time">14:30 ✓✓</p>
              </div>
            </div>
          </div>

          <div class="card">
            <p class="section-label">Résumé</p>
            <div style="display:flex;flex-direction:column;gap:10px;">
              <div style="display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:11px;color:var(--txt-4);">Destinataires</span>
                <span style="font-size:11px;font-weight:500;color:#d4d4d8;" id="summary-dest">—</span>
              </div>
              <div style="display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:11px;color:var(--txt-4);">Estimé</span>
                <span style="font-size:11px;font-weight:500;color:var(--sky);" id="summary-count">—</span>
              </div>
              <div style="display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:11px;color:var(--txt-4);">Format</span>
                <span style="font-size:11px;color:#d4d4d8;" id="summary-format">text</span>
              </div>
              <div style="display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:11px;color:var(--txt-4);">Retry</span>
                <span class="badge badge-green" style="font-size:10px;">activé</span>
              </div>
              <div style="display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:11px;color:var(--txt-4);">Planifié</span>
                <span style="font-size:11px;color:var(--txt-4);" id="summary-schedule">Maintenant</span>
              </div>
            </div>
          </div>

          <div class="card">
            <p class="section-label">Durée estimée</p>
            <p style="font-size:22px;font-weight:300;color:white;font-variant-numeric:tabular-nums;" id="est-duration">—</p>
            <p style="font-size:10px;color:var(--txt-4);margin-top:4px;" id="est-info">avec delay 0.1s · 0 destinataires</p>
            <div style="margin-top:12px;padding-top:12px;border-top:1px solid var(--border);">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                <span style="font-size:10px;color:var(--txt-4);">Taux d'ouverture moyen</span>
                <span style="font-size:10px;color:var(--green);">67%</span>
              </div>
              <div class="stat-bar-track"><div class="stat-bar-fill" style="width:67%;background:var(--green);"></div></div>
            </div>
          </div>

          <!-- Bouton envoi sidebar -->
          <button class="btn-primary" id="btn-send-sidebar" onclick="openConfirmModal()"
                  style="width:100%;justify-content:center;padding:10px;">
            <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
            Lancer l'envoi
          </button>

        </div><!-- /compose-sidebar -->
      </div><!-- /view-compose -->

      <!-- ════ VUE HISTORIQUE ════ -->
      <div id="view-history">

        <div class="history-stats-grid">
          <div class="card" style="padding:14px;">
            <p style="font-size:10px;color:var(--txt-4);margin-bottom:8px;">Campagnes ce mois</p>
            <p style="font-size:24px;font-weight:300;color:white;" id="stat-campagnes">—</p>
          </div>
          <div class="card" style="padding:14px;">
            <p style="font-size:10px;color:var(--txt-4);margin-bottom:8px;">Messages envoyés</p>
            <p style="font-size:24px;font-weight:300;color:white;" id="stat-messages">—</p>
          </div>
          <div class="card" style="padding:14px;">
            <p style="font-size:10px;color:var(--txt-4);margin-bottom:8px;">Taux envoi moy.</p>
            <p style="font-size:24px;font-weight:300;color:var(--green);" id="stat-taux">—</p>
          </div>
          <div class="card" style="padding:14px;">
            <p style="font-size:10px;color:var(--txt-4);margin-bottom:8px;">Erreurs cumulées</p>
            <p style="font-size:24px;font-weight:300;color:var(--red);" id="stat-erreurs">—</p>
          </div>
        </div>

        <!-- Table desktop -->
        <div class="card camp-table-desktop" style="padding:0;overflow:hidden;">
          <div class="camp-table-header">
            <span>Campagne</span>
            <span>Envoyés</span>
            <span>Taux</span>
            <span>Total</span>
            <span>Erreurs</span>
            <span>Statut</span>
            <span></span>
          </div>
          <div id="camp-table-body">
            <p style="color:var(--txt-5);font-size:12px;padding:20px;">Chargement...</p>
          </div>
        </div>

        <!-- Cards mobile -->
        <div class="camp-cards-mobile" id="camp-cards-body" style="display:none;flex-direction:column;gap:10px;">
          <p style="color:var(--txt-5);font-size:12px;padding:10px;">Chargement...</p>
        </div>

      </div><!-- /view-history -->

    </main>
  </div><!-- /main -->
</div><!-- /app -->


<!-- ══════════════════════════════════════════════
     MODALS
     ══════════════════════════════════════════════ -->

<!-- Confirmation envoi -->
<div class="modal-overlay" id="modal-confirm">
  <div class="modal modal-sm">
    <div class="modal-head">
      <h2>Confirmer l'envoi</h2>
      <button class="btn-icon" onclick="closeModal('modal-confirm')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div style="background:rgba(245,158,11,.06);border:1px solid var(--amber-bd);border-radius:var(--radius);padding:12px 14px;">
        <p style="font-size:12px;font-weight:500;color:var(--amber);margin-bottom:4px;">Destinataires</p>
        <p style="font-size:12px;color:#d4d4d8;" id="confirm-dest">—</p>
      </div>
      <div style="background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:var(--radius);padding:12px 14px;">
        <p style="font-size:12px;font-weight:500;color:var(--txt-2);margin-bottom:4px;">Paramètres</p>
        <p style="font-size:11px;color:var(--txt-4);font-family:'Geist Mono',monospace;line-height:1.6;" id="confirm-meta">—</p>
      </div>
      <div style="background:rgba(248,113,113,.05);border:1px solid rgba(248,113,113,.15);border-radius:var(--radius);padding:10px 14px;display:flex;align-items:center;gap:8px;">
        <svg style="width:13px;height:13px;stroke:var(--red);fill:none;flex-shrink:0;" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <p style="font-size:11px;color:var(--red);">Cette action est irréversible. Vérifiez les destinataires avant de confirmer.</p>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeModal('modal-confirm')">Annuler</button>
      <button class="btn-primary" id="btn-confirm" onclick="sendBroadcast()">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
        Lancer l'envoi →
      </button>
    </div>
  </div>
</div>

<!-- ★ Modal envoi en cours — NOUVEAU ★ -->
<div class="modal-overlay" id="modal-sending">
  <div class="modal modal-sm">
    <div class="modal-head">
      <h2>Envoi en cours…</h2>
      <!-- Pas de bouton fermer : l'envoi est bloquant -->
    </div>
    <div class="modal-body" style="align-items:center;text-align:center;padding:24px 20px;gap:16px;">
      <!-- Spinner -->
      <div style="width:48px;height:48px;border:3px solid rgba(245,158,11,.15);border-top-color:var(--amber);border-radius:50%;animation:spin .8s linear infinite;"></div>
      <!-- Texte dynamique -->
      <div>
        <p style="font-size:14px;font-weight:500;color:white;" id="sending-title">Diffusion en cours</p>
        <p style="font-size:12px;color:var(--txt-4);margin-top:4px;" id="sending-sub">Envoi des messages...</p>
      </div>
      <!-- Barre de progression indéterminée -->
      <div class="send-progress" style="width:100%;">
        <div class="send-progress-bar"></div>
      </div>
      <!-- Compteur -->
      <p style="font-size:11px;color:var(--txt-4);" id="sending-counter">Patientez, cela peut prendre quelques minutes selon le nombre de destinataires.</p>
    </div>
    <div class="modal-foot" style="justify-content:center;">
      <button class="btn-ghost" style="font-size:11px;color:var(--red);border-color:rgba(248,113,113,.2);" onclick="cancelBroadcast()">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        Annuler l'envoi
      </button>
    </div>
  </div>
</div>

<!-- Résultat envoi -->
<div class="modal-overlay" id="modal-result">
  <div class="modal modal-sm">
    <div class="modal-head">
      <h2 id="result-title">Envoi terminé</h2>
      <button class="btn-icon" onclick="closeModal('modal-result')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;" id="result-stats"></div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeModal('modal-result')">Fermer</button>
      <button class="btn-primary" onclick="closeModal('modal-result');switchView('history',document.querySelector('.topbar-tab:last-child'))">
        Voir l'historique →
      </button>
    </div>
  </div>
</div>

<!-- Détail campagne -->
<div class="modal-overlay" id="modal-detail">
  <div class="modal">
    <div class="modal-head">
      <div>
        <h2 id="modal-detail-title">—</h2>
        <p style="font-size:11px;color:var(--txt-4);margin-top:2px;" id="modal-detail-dates">—</p>
      </div>
      <button class="btn-icon" onclick="closeModal('modal-detail')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;">
        <div style="text-align:center;padding:10px;background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:var(--radius);">
          <p style="font-size:18px;font-weight:300;color:var(--green);" id="detail-sent">—</p>
          <p style="font-size:10px;color:var(--txt-4);margin-top:3px;">Envoyés</p>
        </div>
        <div style="text-align:center;padding:10px;background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:var(--radius);">
          <p style="font-size:18px;font-weight:300;color:var(--sky);" id="detail-taux">—</p>
          <p style="font-size:10px;color:var(--txt-4);margin-top:3px;">Taux</p>
        </div>
        <div style="text-align:center;padding:10px;background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:var(--radius);">
          <p style="font-size:18px;font-weight:300;color:white;" id="detail-total">—</p>
          <p style="font-size:10px;color:var(--txt-4);margin-top:3px;">Total</p>
        </div>
        <div style="text-align:center;padding:10px;background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:var(--radius);">
          <p style="font-size:18px;font-weight:300;color:var(--red);" id="detail-errors">—</p>
          <p style="font-size:10px;color:var(--txt-4);margin-top:3px;">Erreurs</p>
        </div>
      </div>
      <div>
        <p class="modal-label">Payload</p>
        <pre id="detail-payload" style="font-size:11px;font-family:'Geist Mono',monospace;color:var(--txt-2);background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:var(--radius);padding:12px;overflow-x:auto;line-height:1.5;"></pre>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeModal('modal-detail')">Fermer</button>
    </div>
  </div>
</div>

<!-- Planification -->
<div class="modal-overlay" id="modal-schedule">
  <div class="modal modal-sm">
    <div class="modal-head">
      <h2>Planifier l'envoi</h2>
      <button class="btn-icon" onclick="closeModal('modal-schedule')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div>
        <p class="modal-label">Date et heure d'envoi</p>
        <input type="datetime-local" class="inp" style="font-size:13px;">
      </div>
      <div class="modal-tip">Les envois planifiés s'exécutent côté serveur — vous pouvez fermer cette fenêtre.</div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeModal('modal-schedule')">Annuler</button>
      <button class="btn-primary" onclick="confirmSchedule()">Confirmer →</button>
    </div>
  </div>
</div>


<!-- ══════════════════════════════════════════════
     SCRIPTS
     ══════════════════════════════════════════════ -->
<script>
const API_URL = window.API_URL || 'https://fdkvip.com'

/* ════════════════════════════════════════════════
   SIDEBAR
   ════════════════════════════════════════════════ */
function openSidebar() {
  document.getElementById('sidebar').classList.add('open')
  document.getElementById('sidebar-overlay').classList.add('open')
  document.body.style.overflow = 'hidden'
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open')
  document.getElementById('sidebar-overlay').classList.remove('open')
  document.body.style.overflow = ''
}
window.addEventListener('resize', () => {
  if (window.innerWidth > 700) {
    document.getElementById('sidebar-overlay').classList.remove('open')
    document.body.style.overflow = ''
  }
})

/* ════════════════════════════════════════════════
   MODALS
   ════════════════════════════════════════════════ */
function openModal(id)  { document.getElementById(id)?.classList.add('open') }
function closeModal(id) { document.getElementById(id)?.classList.remove('open') }

document.addEventListener('click', e => {
  if (e.target.classList.contains('modal-overlay') && e.target.id !== 'modal-sending')
    e.target.classList.remove('open')
})
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    document.querySelectorAll('.modal-overlay.open:not(#modal-sending)').forEach(m => m.classList.remove('open'))
    if (window.innerWidth <= 700) closeSidebar()
    closeNotif()
  }
})

/* ════════════════════════════════════════════════
   NOTIFICATIONS
   ════════════════════════════════════════════════ */
function toggleNotif() {
  document.getElementById('notif-panel').classList.toggle('open')
}
function closeNotif() {
  document.getElementById('notif-panel')?.classList.remove('open')
}
function clearNotifs() {
  document.querySelectorAll('.notif-item span:first-child').forEach(s => s.remove())
  document.querySelector('.notif-dot')?.remove()
  closeNotif()
}
document.addEventListener('click', e => {
  if (!e.target.closest('.notif-wrap')) closeNotif()
})

/* ════════════════════════════════════════════════
   VUES
   ════════════════════════════════════════════════ */
function syncTabs(clickedEl, view) {
  // Synchronise la barre mobile avec les tabs desktop et vice versa
  const desktopTabs = document.querySelectorAll('#topbar .topbar-tab')
  const mobileTabs  = document.querySelectorAll('#topbar-mobile-tabs .topbar-tab')
  desktopTabs.forEach(t => t.classList.remove('active'))
  mobileTabs.forEach(t => t.classList.remove('active'))

  // Activer le bon tab dans les deux barres
  desktopTabs.forEach(t => { if (t.getAttribute('onclick')?.includes("'" + view + "'")) t.classList.add('active') })
  mobileTabs.forEach(t  => { if (t.getAttribute('onclick')?.includes("'" + view + "'")) t.classList.add('active') })
}

function switchView(view, el) {
  document.getElementById('view-compose').style.display = view === 'compose' ? 'flex' : 'none'
  const histEl = document.getElementById('view-history')
  histEl.style.display = view === 'history' ? 'flex' : 'none'
  if (view === 'history') { histEl.style.flexDirection = 'column'; loadHistory() }

  document.querySelectorAll('.topbar-tab').forEach(t => t.classList.remove('active'))
  el.classList.add('active')
}

/* ════════════════════════════════════════════════
   FORMAT & DESTINATAIRES
   ════════════════════════════════════════════════ */
function switchFormat(fmt, el) {
  document.querySelectorAll('[id^="fmt-"]').forEach(b => b.classList.remove('active'))
  el.classList.add('active')

  const hasMedia = fmt !== 'text'
  const hasText  = fmt !== 'image' && fmt !== 'video'

  document.getElementById('media-upload').style.display = hasMedia ? 'block' : 'none'
  document.getElementById('text-block').style.display   = hasText  ? 'block' : 'none'

  ;['preview-media','preview-media-mobile'].forEach(id => {
    const el2 = document.getElementById(id)
    if (el2) el2.style.display = hasMedia ? 'block' : 'none'
  })

  if (!hasMedia) { clearUploadedMediaUrl(); resetUploadZone() }
  updateSummary()
}

function switchDest(type, el) {
  document.querySelectorAll('[id^="dest-"].format-btn').forEach(b => b.classList.remove('active'))
  el.classList.add('active')
  document.getElementById('dest-block-category').style.display = type === 'category' ? 'block' : 'none'
  document.getElementById('dest-block-ids').style.display      = type === 'ids'      ? 'block' : 'none'
  document.getElementById('dest-block-all').style.display      = type === 'all'      ? 'block' : 'none'
  updateSummary()
}

/* ════════════════════════════════════════════════
   FILTRES
   ════════════════════════════════════════════════ */
function toggleFilters() {
  const p = document.getElementById('filters-panel')
  p.style.display = p.style.display === 'none' ? 'block' : 'none'
  updateFilterBadge()
}
function removeFilter(btn) {
  btn.closest('.filter-tag').remove()
  updateFilterBadge()
}
function updateFilterBadge() {
  const count = document.getElementById('active-filters').children.length
  const badge = document.getElementById('filter-count')
  badge.style.display = count > 0 ? 'inline-flex' : 'none'
  badge.textContent   = count
}

/* ════════════════════════════════════════════════
   VARIABLES
   ════════════════════════════════════════════════ */
function insertVar(v) {
  const ta  = document.getElementById('msg-textarea')
  const pos = ta.selectionStart
  ta.value  = ta.value.substring(0, pos) + v + ta.value.substring(pos)
  ta.selectionStart = ta.selectionEnd = pos + v.length
  ta.focus()
  updatePreview(); updateCount(ta)
}

function addVarRow() {
  const row = document.createElement('div')
  row.style.cssText = 'display:flex;align-items:center;gap:8px;'
  row.innerHTML = `
    <span style="color:var(--txt-5);font-size:12px;">→</span>
    <input class="inp" type="text" placeholder="valeur" style="font-size:12px;">
    <button class="btn-icon" style="width:24px;height:24px;" onclick="this.closest('div').remove()">
      <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
    </button>`
  document.getElementById('custom-vars').appendChild(row)
}

function updateCount(ta) {
  document.getElementById('char-count').textContent = `${ta.value.length} / 4096`
}

/* ════════════════════════════════════════════════
   PREVIEW
   ════════════════════════════════════════════════ */
function updatePreview() {
  const raw = document.getElementById('msg-textarea').value
    || 'Bonjour +prenom, votre message apparaîtra ici...'
  const rendered = raw
    .replace(/\+prenom/g, '<span style="color:#7dd3fc;">Marc</span>')
    .replace(/\+offre/g,  '<span style="color:#7dd3fc;">50%</span>')
    .replace(/\+lien/g,   '<span style="color:#7dd3fc;">https://...</span>')
    .replace(/\+perf/g,   '<span style="color:#7dd3fc;">+4.2%</span>')
    .replace(/\+date/g,   '<span style="color:#7dd3fc;">19/04/2026</span>')
    .replace(/\+plan/g,   '<span style="color:#7dd3fc;">Premium</span>')
    .replace(/\n/g, '<br>')
  ;['preview-text','preview-text-mobile','modal-preview-text'].forEach(id => {
    const el = document.getElementById(id)
    if (el) el.innerHTML = rendered
  })
}

/* ════════════════════════════════════════════════
   RÉSUMÉ & DURÉE
   ════════════════════════════════════════════════ */
function updateSummary() {
  const destType = document.querySelector('[id^="dest-"].format-btn.active')?.id
  const format   = document.querySelector('[id^="fmt-"].active')?.id
                           ?.replace('fmt-','')
                           ?.replace('imagetext','image+text')
                           ?.replace('videotext','video+text') || 'text'

  let destLabel = '—', total = 0
  if (destType === 'dest-all') {
    destLabel = 'Tous les membres'
  } else if (destType === 'dest-ids') {
    const raw = document.querySelector('#dest-block-ids textarea')?.value || ''
    total     = raw.split(',').filter(s => s.trim()).length
    destLabel = total + ' IDs manuels'
  } else {
    const sel = document.querySelector('#dest-block-category select')
    destLabel = sel?.options[sel.selectedIndex]?.textContent || '—'
    const m   = destLabel.match(/\((\d+)\)/)
    if (m) total = parseInt(m[1])
  }

  const setEl = (id, v) => { const e = document.getElementById(id); if (e) e.textContent = v }
  setEl('summary-dest',   destLabel)
  setEl('summary-format', format)

  const delay   = parseFloat(document.querySelector('.options-grid input[type="number"]')?.value) || 0.1
  const seconds = Math.round(total * delay)
  const mins    = Math.floor(seconds / 60)
  const secs    = seconds % 60

  setEl('est-duration',  total > 0 ? '~' + mins + 'm ' + secs + 's' : '—')
  setEl('est-info',      'avec delay ' + delay + 's · ' + total + ' destinataires')
  setEl('summary-count', total + ' msgs')
}

/* ════════════════════════════════════════════════
   UPLOAD FICHIER
   ════════════════════════════════════════════════ */
let _uploadedUrl = null
function getUploadedMediaUrl()   { return _uploadedUrl }
function setUploadedMediaUrl(u)  { _uploadedUrl = u }
function clearUploadedMediaUrl() { _uploadedUrl = null }

function _getFileInput() {
  let fi = document.getElementById('_file-input')
  if (!fi) {
    fi = document.createElement('input')
    fi.type = 'file'; fi.id = '_file-input'
    fi.accept = 'image/*,video/*'; fi.style.display = 'none'
    document.body.appendChild(fi)
    fi.addEventListener('change', () => {
      if (fi.files[0]) _handleFile(fi.files[0])
      fi.value = ''
    })
  }
  return fi
}

function triggerUpload() { _getFileInput().click() }

async function _handleFile(file) {
  const fmt    = document.querySelector('[id^="fmt-"].active')?.id?.replace('fmt-','') || 'text'
  const isImg  = file.type.startsWith('image/')
  const isVid  = file.type.startsWith('video/')
  if ((fmt === 'image' || fmt === 'image+text') && !isImg) { showToast('Ce format attend une image', 'error'); return }
  if ((fmt === 'video' || fmt === 'video+text') && !isVid) { showToast('Ce format attend une vidéo', 'error'); return }
  if (isImg && file.size > 10*1024*1024) { showToast('Image trop lourde (max 10 Mo)', 'error'); return }
  if (isVid && file.size > 50*1024*1024) { showToast('Vidéo trop lourde (max 50 Mo)', 'error'); return }

  _showUploadLoading(file.name, file.size)
  if (isImg) {
    const r = new FileReader()
    r.onload = e => _showImgPreview(e.target.result, file.name, file.size)
    r.readAsDataURL(file)
  } else if (isVid) {
    _showVidPreview(URL.createObjectURL(file), file.name, file.size)
  }
  try {
    const fd = new FormData()
    fd.append('user_id', '0'); fd.append('file', file)
    const res  = await fetch(API_URL + '/chat/media/upload', { method:'POST', body:fd })
    const data = await res.json()
    if (!res.ok || !data.url) throw new Error(data.detail || 'Upload échoué')
    setUploadedMediaUrl(data.url)
    _markUploadOk(data.url)
  } catch (e) {
    clearUploadedMediaUrl()
    _showUploadErr(e.message)
  }
}

function _humanSize(b) {
  return b < 1048576 ? Math.round(b/1024) + ' Ko' : (b/1048576).toFixed(1) + ' Mo'
}

function resetUploadZone() {
  const z = document.querySelector('.upload-zone')
  if (!z) return
  z.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>'
    + '<p style="font-size:12px;">Glisser un fichier ou <span style="color:var(--sky);">parcourir</span></p>'
    + '<p style="font-size:10px;margin-top:4px;color:var(--txt-5);">ou coller un file_id Telegram</p>'
  z.style.borderColor = ''; z.style.background = ''
  const inp = document.querySelector('#media-upload input[type="text"]')
  if (inp) inp.value = ''
}

function _ensureSpin() {
  if (!document.getElementById('_spin-style')) {
    const s = document.createElement('style')
    s.id = '_spin-style'; s.textContent = '@keyframes spin{to{transform:rotate(360deg)}}'
    document.head.appendChild(s)
  }
}

function _showUploadLoading(name, size) {
  _ensureSpin()
  const z = document.querySelector('.upload-zone')
  if (!z) return
  z.innerHTML = '<div style="display:flex;flex-direction:column;align-items:center;gap:8px;">'
    + '<div style="width:24px;height:24px;border:2px solid rgba(56,189,248,.2);border-top-color:var(--sky);border-radius:50%;animation:spin .7s linear infinite;"></div>'
    + '<p style="font-size:12px;color:var(--sky);">Upload en cours...</p>'
    + '<p style="font-size:10px;color:var(--txt-5);">' + name + ' · ' + _humanSize(size) + '</p>'
    + '</div>'
}

function _showImgPreview(src, name, size) {
  _ensureSpin()
  const z = document.querySelector('.upload-zone'); if (!z) return
  z.innerHTML = '<div style="position:relative;">'
    + '<img src="' + src + '" style="max-height:120px;max-width:100%;border-radius:8px;object-fit:cover;opacity:.5;">'
    + '<div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">'
    + '<div style="width:20px;height:20px;border:2px solid rgba(56,189,248,.3);border-top-color:var(--sky);border-radius:50%;animation:spin .7s linear infinite;"></div>'
    + '</div></div>'
    + '<p style="font-size:10px;margin-top:8px;color:var(--txt-4);">' + name + ' · ' + _humanSize(size) + '</p>'
}

function _showVidPreview(src, name, size) {
  _ensureSpin()
  const z = document.querySelector('.upload-zone'); if (!z) return
  z.innerHTML = '<div style="position:relative;">'
    + '<video src="' + src + '" muted style="max-height:100px;max-width:100%;border-radius:8px;object-fit:cover;opacity:.5;"></video>'
    + '<div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;">'
    + '<div style="width:20px;height:20px;border:2px solid rgba(56,189,248,.3);border-top-color:var(--sky);border-radius:50%;animation:spin .7s linear infinite;"></div>'
    + '</div></div>'
    + '<p style="font-size:10px;margin-top:8px;color:var(--txt-4);">' + name + ' · ' + _humanSize(size) + '</p>'
}

function _markUploadOk(url) {
  const z = document.querySelector('.upload-zone'); if (!z) return
  const media = z.querySelector('img, video')
  if (media) media.style.opacity = '1'
  const sp = z.querySelector('div[style*="animation"]')
  if (sp) sp.remove()
  const fname = url.split('/').pop()
  const banner = document.createElement('div')
  banner.style.cssText = 'display:flex;align-items:center;justify-content:space-between;margin-top:8px;padding:6px 10px;background:rgba(52,211,153,.08);border:1px solid rgba(52,211,153,.2);border-radius:var(--radius);gap:8px;'
  banner.innerHTML = '<div style="display:flex;align-items:center;gap:6px;min-width:0;">'
    + '<svg style="width:12px;height:12px;stroke:var(--green);fill:none;" viewBox="0 0 24 24" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>'
    + '<span style="font-size:11px;color:var(--green);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:200px;" title="' + url + '">' + fname + '</span>'
    + '</div>'
    + '<button onclick="clearUploadedMediaUrl();resetUploadZone()" style="background:none;border:none;cursor:pointer;color:var(--txt-4);">'
    + '<svg style="width:12px;height:12px;stroke:currentColor;fill:none;" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>'
    + '</button>'
  z.appendChild(banner)
  z.style.borderColor = 'rgba(52,211,153,.3)'
  z.style.background  = 'rgba(52,211,153,.03)'
}

function _showUploadErr(msg) {
  const z = document.querySelector('.upload-zone'); if (!z) return
  z.innerHTML = '<div style="display:flex;flex-direction:column;align-items:center;gap:6px;">'
    + '<svg style="width:20px;height:20px;stroke:var(--red);fill:none;" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>'
    + '<p style="font-size:12px;color:var(--red);">' + msg + '</p>'
    + '<button onclick="triggerUpload()" style="font-size:11px;color:var(--sky);background:none;border:none;cursor:pointer;margin-top:2px;">Réessayer</button>'
    + '</div>'
  z.style.borderColor = 'rgba(248,113,113,.3)'
  z.style.background  = 'rgba(248,113,113,.03)'
}

/* ════════════════════════════════════════════════
   PAYLOAD & VALIDATION
   ════════════════════════════════════════════════ */
function buildPayload() {
  const destType = document.querySelector('[id^="dest-"].format-btn.active')?.id
  let category = null, user_ids = null
  if (destType === 'dest-category') {
    category = document.querySelector('#dest-block-category select').value || null
  } else if (destType === 'dest-ids') {
    const raw = document.querySelector('#dest-block-ids textarea').value
    user_ids  = raw.split(',').map(s => parseInt(s.trim())).filter(n => !isNaN(n))
  } else if (destType === 'dest-all') {
    category = 'all'
  }
  const excludeRaw       = document.querySelector('#dest-block-category input[type="text"]')?.value || ''
  const exclude_user_ids = excludeRaw.split(',').map(s => parseInt(s.trim())).filter(n => !isNaN(n))
  const format           = document.querySelector('[id^="fmt-"].active')?.id?.replace('fmt-','')?.replace('imagetext','image+text')?.replace('videotext','video+text') || 'text'
  const message          = document.getElementById('msg-textarea')?.value || ''
  const manualFileId     = document.querySelector('#media-upload input[type="text"]')?.value?.trim() || null
  const media_url        = getUploadedMediaUrl() || manualFileId || null
  const variables        = {}
  document.querySelectorAll('#custom-vars > div').forEach(row => {
    const inputs = row.querySelectorAll('input')
    const k = inputs[0]?.value?.trim(); const v = inputs[1]?.value?.trim()
    if (k && v) variables[k] = v
  })
  const filters = {}
  const dates = document.querySelectorAll('#filters-panel input[type="date"]')
  if (dates[0]?.value) filters.created_after  = dates[0].value
  if (dates[1]?.value) filters.created_before = dates[1].value
  const delay        = parseFloat(document.querySelector('.options-grid input[type="number"]')?.value) || 0.1
  const tag          = document.getElementById('tag-input')?.value?.trim() || ''
  const retry        = document.getElementById('toggle-retry')?.classList.contains('on') ?? true
  const callback_url = document.getElementById('webhook-input')?.value?.trim() || null
  const scheduled_at = document.getElementById('btn-schedule-hidden')?.dataset.scheduledAt || null
  return { message, format, media_url, category, user_ids, scheduled_at, delay, retry, exclude_user_ids, variables, filters, tag, callback_url }
}

function validatePayload(p) {
  if (!p.category && (!p.user_ids || !p.user_ids.length)) return 'Sélectionne des destinataires avant d\'envoyer.'
  if (p.format === 'text' && !p.message.trim()) return 'Le message ne peut pas être vide.'
  if (['image','video','image+text','video+text'].includes(p.format) && !p.media_url) return 'Ajoute un fichier ou colle un file_id Telegram.'
  if (p.message.length > 4096) return 'Le message dépasse 4096 caractères.'
  return null
}

/* ════════════════════════════════════════════════
   CONFIRMATION & ENVOI
   ════════════════════════════════════════════════ */
function openConfirmModal() {
  const p = buildPayload()
  const err = validatePayload(p)
  if (err) { showToast(err, 'error'); return }
  let destLabel = '—'
  if (p.category === 'all') destLabel = 'Tous les membres'
  else if (p.category) {
    const sel = document.querySelector('#dest-block-category select')
    destLabel = sel?.options[sel.selectedIndex]?.textContent || p.category
  } else if (p.user_ids?.length) destLabel = p.user_ids.length + ' IDs manuels'
  document.getElementById('confirm-dest').textContent = destLabel
  document.getElementById('confirm-meta').textContent = 'format: ' + p.format + ' · delay: ' + p.delay + 's · retry: ' + p.retry + ' · tag: ' + (p.tag || '—')
  openModal('modal-confirm')
}

let _broadcastAborted = false

async function sendBroadcast() {
  const p = buildPayload()
  const err = validatePayload(p)
  if (err) { showToast(err, 'error'); return }
  _broadcastAborted = false
  _lockSendButtons(true)
  closeModal('modal-confirm')
  openModal('modal-sending')
  try {
    const res    = await fetch(API_URL + '/broadcast', { method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(p) })
    const report = await res.json()
    if (!res.ok) throw new Error(report.detail || 'Erreur inconnue')
    if (_broadcastAborted) return
    closeModal('modal-sending')
    _showResult(report)
  } catch (e) {
    closeModal('modal-sending')
    showToast('Erreur : ' + e.message, 'error')
    _lockSendButtons(false)
  }
}

function cancelBroadcast() {
  _broadcastAborted = true
  closeModal('modal-sending')
  _lockSendButtons(false)
  showToast('Envoi annulé', 'info')
}

function _lockSendButtons(locked) {
  ['btn-send','btn-send-sidebar'].forEach(id => {
    const btn = document.getElementById(id)
    if (!btn) return
    btn.disabled = locked
    btn.style.opacity = locked ? '.5' : ''
    btn.style.cursor  = locked ? 'not-allowed' : ''
  })
}

function _showResult(report) {
  const taux    = report.total > 0 ? Math.round(report.sent / report.total * 100) : 0
  const couleur = taux >= 70 ? 'var(--green)' : taux >= 40 ? 'var(--amber)' : 'var(--red)'
  const stats   = document.getElementById('result-stats')
  if (stats) {
    const stat = (val, lbl, color) =>
      '<div style="text-align:center;padding:12px;background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:var(--radius);">'
      + '<p style="font-size:22px;font-weight:300;color:' + color + ';">' + val + '</p>'
      + '<p style="font-size:10px;color:var(--txt-4);margin-top:3px;">' + lbl + '</p></div>'
    stats.innerHTML = stat(report.sent, 'Envoyés', 'var(--green)')
      + stat(taux + '%', 'Taux', couleur)
      + stat(report.total, 'Total', 'white')
      + stat(report.errors, 'Erreurs', 'var(--red)')
  }
  const title = document.getElementById('result-title')
  if (title) title.textContent = taux >= 70 ? '✓ Envoi réussi' : taux > 0 ? 'Envoi partiel' : '⚠ Échec envoi'
  openModal('modal-result')
  _lockSendButtons(false)
  showToast('Diffusion terminée · ' + report.sent + '/' + report.total + ' envoyés', taux >= 70 ? 'success' : 'info')
}

/* ════════════════════════════════════════════════
   PLANIFICATION
   ════════════════════════════════════════════════ */
function confirmSchedule() {
  const inp = document.querySelector('#modal-schedule input[type="datetime-local"]')
  if (!inp?.value) { showToast('Sélectionne une date et heure', 'error'); return }
  const formatted = inp.value.replace('T', ' ') + ':00'
  let el = document.getElementById('btn-schedule-hidden')
  if (!el) { el = document.createElement('span'); el.id = 'btn-schedule-hidden'; el.style.display='none'; document.body.appendChild(el) }
  el.dataset.scheduledAt = formatted
  const sd = document.getElementById('schedule-display'); if (sd) sd.textContent = formatted
  const ss = document.getElementById('summary-schedule'); if (ss) ss.textContent = formatted
  showToast('Envoi planifié le ' + formatted, 'success')
  closeModal('modal-schedule')
}

/* ════════════════════════════════════════════════
   CATÉGORIES
   ════════════════════════════════════════════════ */
async function loadCategories() {
  try {
    const res  = await fetch(API_URL + '/categories')
    const data = await res.json()
    const sel  = document.querySelector('#dest-block-category select')
    sel.innerHTML = '<option value="">Sélectionner une catégorie...</option>'
    data.forEach(cat => {
      const o = document.createElement('option')
      o.value = cat.name; o.textContent = cat.name + ' (' + cat.total + ')'
      sel.appendChild(o)
    })
  } catch (e) { console.warn('loadCategories:', e.message) }
}

/* ════════════════════════════════════════════════
   HISTORIQUE
   ════════════════════════════════════════════════ */
async function loadHistory() {
  const body = document.getElementById('camp-table-body')
  if (body) body.innerHTML = '<p style="color:var(--txt-5);font-size:12px;padding:20px;">Chargement...</p>'
  try {
    const res  = await fetch(API_URL + '/broadcast/history')
    const data = await res.json()
    renderHistoryStats(data); renderHistoryTable(data); renderHistoryCards(data)
  } catch (e) {
    if (body) body.innerHTML = '<p style="color:var(--txt-5);font-size:12px;padding:20px;">Erreur de chargement</p>'
  }
}

function renderHistoryStats(data) {
  const now = new Date()
  const mo  = data.filter(c => { const d = new Date(c.started_at); return d.getMonth()===now.getMonth() && d.getFullYear()===now.getFullYear() })
  const sent = data.reduce((a,c) => a + c.sent,   0)
  const errs = data.reduce((a,c) => a + c.errors, 0)
  const avg  = data.length ? Math.round(data.reduce((a,c) => a + (c.total > 0 ? c.sent/c.total : 0), 0) / data.length * 100) : 0
  const s = (id, v) => { const e = document.getElementById(id); if (e) e.textContent = v }
  s('stat-campagnes', mo.length); s('stat-messages', sent.toLocaleString('fr-FR')); s('stat-taux', avg + '%'); s('stat-erreurs', errs)
}

function renderHistoryTable(data) {
  const body = document.getElementById('camp-table-body'); if (!body) return
  if (!data.length) { body.innerHTML = '<p style="color:var(--txt-5);font-size:12px;padding:20px;">Aucune campagne.</p>'; return }
  body.innerHTML = data.map(camp => {
    const taux    = camp.total > 0 ? Math.round(camp.sent/camp.total*100) : 0
    const couleur = taux >= 70 ? 'var(--green)' : taux >= 40 ? 'var(--amber)' : 'var(--red)'
    const safecamp = JSON.stringify(camp).replace(/"/g, '&quot;')
    return '<div class="camp-row fadein" onclick=\'openDetailModal(this.dataset.camp)\' data-camp="' + safecamp + '">'
      + '<div><p style="font-size:12px;font-weight:500;color:#e4e4e7;">' + (camp.tag || 'Sans tag') + '</p>'
      + '<p style="font-size:10px;color:var(--txt-4);margin-top:2px;">' + camp.started_at + ' · ' + (camp.category || 'IDs manuels') + ' · ' + camp.format + '</p></div>'
      + '<span style="font-size:12px;color:var(--txt-2);">' + camp.sent + '</span>'
      + '<div><p style="font-size:12px;color:' + couleur + ';">' + taux + '%</p>'
      + '<div class="stat-bar-track" style="margin-top:4px;"><div class="stat-bar-fill" style="width:' + taux + '%;background:' + couleur + ';"></div></div></div>'
      + '<span style="font-size:12px;color:var(--txt-4);">' + camp.total + '</span>'
      + '<span style="font-size:12px;color:var(--red);">' + camp.errors + '</span>'
      + '<span><span class="badge badge-green">Terminé</span></span>'
      + '<button class="btn-icon" style="width:24px;height:24px;"><svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>'
      + '</div>'
  }).join('')
}

function renderHistoryCards(data) {
  const body = document.getElementById('camp-cards-body'); if (!body) return
  if (!data.length) { body.innerHTML = '<p style="color:var(--txt-5);font-size:12px;padding:10px;">Aucune campagne.</p>'; return }
  body.innerHTML = data.map(camp => {
    const taux    = camp.total > 0 ? Math.round(camp.sent/camp.total*100) : 0
    const couleur = taux >= 70 ? 'var(--green)' : taux >= 40 ? 'var(--amber)' : 'var(--red)'
    const safecamp = JSON.stringify(camp).replace(/"/g, '&quot;')
    return '<div class="card fadein" style="cursor:pointer;" onclick=\'openDetailModal(this.dataset.camp)\' data-camp="' + safecamp + '">'
      + '<div style="display:flex;align-items:start;justify-content:space-between;margin-bottom:10px;">'
      + '<div><p style="font-size:13px;font-weight:500;color:#e4e4e7;">' + (camp.tag || 'Sans tag') + '</p>'
      + '<p style="font-size:10px;color:var(--txt-4);margin-top:2px;">' + camp.started_at + ' · ' + (camp.category || 'IDs manuels') + '</p></div>'
      + '<span class="badge badge-green">Terminé</span></div>'
      + '<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;text-align:center;">'
      + '<div><p style="font-size:16px;font-weight:300;color:white;">' + camp.sent + '</p><p style="font-size:10px;color:var(--txt-4);">Envoyés</p></div>'
      + '<div><p style="font-size:16px;font-weight:300;color:' + couleur + ';">' + taux + '%</p><p style="font-size:10px;color:var(--txt-4);">Taux</p></div>'
      + '<div><p style="font-size:16px;font-weight:300;color:var(--red);">' + camp.errors + '</p><p style="font-size:10px;color:var(--txt-4);">Erreurs</p></div>'
      + '</div></div>'
  }).join('')
}

function openDetailModal(campStr) {
  const camp = typeof campStr === 'string' ? JSON.parse(campStr) : campStr
  const taux = camp.total > 0 ? Math.round(camp.sent/camp.total*100) : 0
  document.getElementById('modal-detail-title').textContent = camp.tag || 'Sans tag'
  document.getElementById('modal-detail-dates').textContent = camp.started_at + ' → ' + camp.finished_at
  const s = (id, v) => { const e = document.getElementById(id); if (e) e.textContent = v }
  s('detail-sent', camp.sent); s('detail-taux', taux + '%'); s('detail-total', camp.total); s('detail-errors', camp.errors)
  const pre = document.getElementById('detail-payload')
  if (pre) pre.textContent = JSON.stringify({ category:camp.category, format:camp.format, tag:camp.tag, total:camp.total, sent:camp.sent, errors:camp.errors }, null, 2)
  openModal('modal-detail')
}

/* ════════════════════════════════════════════════
   TOAST
   ════════════════════════════════════════════════ */
function showToast(msg, type) {
  type = type || 'info'
  const colors = {
    success: { bg:'rgba(52,211,153,.12)',  bd:'rgba(52,211,153,.25)',  txt:'#34d399' },
    error:   { bg:'rgba(248,113,113,.12)', bd:'rgba(248,113,113,.25)', txt:'#f87171' },
    info:    { bg:'rgba(56,189,248,.12)',  bd:'rgba(56,189,248,.25)',  txt:'#38bdf8' },
  }
  const c = colors[type] || colors.info
  const t = document.createElement('div')
  t.className = 'toast'
  t.style.cssText = 'background:' + c.bg + ';border:1px solid ' + c.bd + ';color:' + c.txt + ';'
  t.textContent = msg
  document.getElementById('toast-container').appendChild(t)
  setTimeout(function() { t.style.opacity = '0'; t.style.transition = 'opacity .2s'; setTimeout(function() { t.remove() }, 200) }, 3500)
}

/* ════════════════════════════════════════════════
   INIT
   ════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', function() {
  _ensureSpin()
  _getFileInput()
  loadCategories()
  updateSummary()
  updatePreview()

  // Drag & drop
  var z = document.querySelector('.upload-zone')
  if (z) {
    z.addEventListener('dragover', function(e) {
      e.preventDefault()
      z.style.borderColor = 'rgba(56,189,248,.5)'
      z.style.background  = 'rgba(56,189,248,.06)'
    })
    z.addEventListener('dragleave', function() {
      if (!getUploadedMediaUrl()) { z.style.borderColor = ''; z.style.background = '' }
    })
    z.addEventListener('drop', function(e) {
      e.preventDefault()
      var f = e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files[0]
      if (f) _handleFile(f)
    })
  }
})
</script>
</body>
</html>