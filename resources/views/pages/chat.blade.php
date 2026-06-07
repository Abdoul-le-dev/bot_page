<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TradingBot — Chat direct</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400" rel="stylesheet">
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Geist','sans-serif'],mono:['Geist Mono','monospace']}}}}</script>
<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="../css/chat.css">

<style>
/* ── Variables & reset ────────────────────────────────────────────── */
:root {
  --bg:       #0c0c0e;
  --bg-card:  #0f0f11;
  --bg-hover: rgba(255,255,255,.04);
  --border:   rgba(255,255,255,.06);
  --amber:    #f59e0b;
  --sky:      #38bdf8;
  --green:    #34d399;
  --red:      #f87171;
  --teal:     #2dd4bf;
  --txt:      #e4e4e7;
  --txt-muted:#52525b;
  --txt-dim:  #3f3f46;
  --sidebar-w: 200px;
  --conv-w:    280px;
}

*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

body {
  font-family: 'Geist', sans-serif;
  background: var(--bg);
  color: var(--txt);
  height: 100dvh;
  overflow: hidden;
}

/* ── Layout racine ────────────────────────────────────────────────── */
#app-root {
  display: flex;
  height: 100dvh;
  overflow: hidden;
}

/* ══════════════════════════════════════════════════════
   SIDEBAR PRINCIPALE (Navigation globale)
   ══════════════════════════════════════════════════════ */
#sidebar {
  width: var(--sidebar-w);
  flex-shrink: 0;
  background: #0d0d0f;
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  z-index: 100;
  transition: transform .25s ease;
}

/* Mobile : sidebar en overlay */
@media (max-width: 768px) {
  #sidebar {
    position: fixed;
    top: 0; left: 0;
    height: 100%;
    transform: translateX(-100%);
  }
  #sidebar.open { transform: translateX(0); }
}

#sidebar-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.5);
  z-index: 99;
  backdrop-filter: blur(2px);
}
#sidebar-overlay.open { display: block; }

/* Header sidebar */
.sb-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 16px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}

.sb-logo {
  display: flex;
  align-items: center;
  gap: 8px;
}

.sb-logo-icon {
  width: 24px; height: 24px;
  background: rgba(245,158,11,.12);
  border: 1px solid rgba(245,158,11,.25);
  border-radius: 6px;
  display: flex; align-items: center; justify-content: center;
  font-size: 11px;
}

.sb-logo-text {
  font-size: 13px;
  font-weight: 500;
  color: #f4f4f5;
}

.sb-close {
  display: none;
  width: 28px; height: 28px;
  align-items: center; justify-content: center;
  background: rgba(255,255,255,.05);
  border: 1px solid var(--border);
  border-radius: 6px;
  color: var(--txt-muted);
  cursor: pointer;
  font-size: 12px;
  transition: all .15s;
}
.sb-close:hover { color: var(--txt); background: rgba(255,255,255,.1); }

/* Bouton ✕ visible partout — hamburger toujours accessible */
.sb-close { display: flex; }

/* Nav sidebar */
.sb-nav {
  flex: 1;
  padding: 8px;
  display: flex;
  flex-direction: column;
  gap: 2px;
  overflow-y: auto;
}

.sb-section {
  font-size: 10px;
  font-weight: 500;
  color: var(--txt-dim);
  text-transform: uppercase;
  letter-spacing: .06em;
  padding: 10px 10px 4px;
}

.sb-link {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 7px 10px;
  border-radius: 8px;
  font-size: 13px;
  color: var(--txt-muted);
  text-decoration: none;
  transition: all .15s;
  cursor: pointer;
  background: none;
  border: none;
  width: 100%;
  text-align: left;
}
.sb-link:hover { color: #d4d4d8; background: var(--bg-hover); }
.sb-link.active { color: #f4f4f5; background: rgba(255,255,255,.07); }
.sb-link svg { width: 14px; height: 14px; flex-shrink: 0; }

/* Footer sidebar */
.sb-footer {
  padding: 10px 12px;
  border-top: 1px solid var(--border);
  flex-shrink: 0;
}

.sb-user {
  display: flex;
  align-items: center;
  gap: 8px;
}

.sb-avatar {
  width: 24px; height: 24px;
  border-radius: 50%;
  background: rgba(255,255,255,.07);
  display: flex; align-items: center; justify-content: center;
  font-size: 9px;
  font-weight: 600;
  color: var(--txt-muted);
  flex-shrink: 0;
}

/* ══════════════════════════════════════════════════════
   ZONE PRINCIPALE (topbar + chat-root)
   ══════════════════════════════════════════════════════ */
#main {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
  overflow: hidden;
}

/* Topbar */
.topbar {
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  height: 52px;
  border-bottom: 1px solid var(--border);
  background: var(--bg-card);
  gap: 12px;
}

.topbar-left {
  display: flex;
  align-items: center;
  gap: 10px;
  min-width: 0;
}

/* Hamburger — toujours visible (ouvre la sidebar en overlay sur tous écrans) */
#hamburger {
  display: flex !important;
  align-items: center;
  justify-content: center;
  width: 30px; height: 30px;
  border-radius: 7px;
  background: rgba(255,255,255,.05);
  border: 1px solid var(--border);
  color: var(--txt-muted);
  cursor: pointer;
  flex-shrink: 0;
  transition: all .15s;
}
#hamburger:hover { color: var(--txt); background: rgba(255,255,255,.1); }

/* ══════════════════════════════════════════════════════
   CHAT ROOT — 3 colonnes
   ══════════════════════════════════════════════════════ */
#chat-root {
  flex: 1;
  display: flex;
  overflow: hidden;
}

/* Col 1 — Liste conversations */
#conv-col {
  width: var(--conv-w);
  flex-shrink: 0;
  border-right: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  background: var(--bg-card);
  overflow: hidden;
}

/* Col 2 — Messages */
#messages-col {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 0;
  overflow: hidden;
  background: var(--bg);
}

/* Col 3 — Profil */
#profile-col {
  width: 260px;
  flex-shrink: 0;
  border-left: 1px solid var(--border);
  background: var(--bg-card);
  overflow-y: auto;
  transition: transform .25s ease;
}

/* ── Responsive breakpoints ──────────────────────────── */

/* Tablette : profil en drawer par-dessus */
@media (max-width: 1024px) {
  #profile-col {
    position: fixed;
    top: 0; right: 0;
    height: 100%;
    width: min(320px, 90vw);
    z-index: 90;
    transform: translateX(100%);
    border-left: 1px solid var(--border);
  }
  #profile-col.open { transform: translateX(0); }
  #profile-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.4);
    z-index: 89;
  }
  #profile-overlay.open { display: block; }
}

/* Mobile : stack liste ↔ messages */
@media (max-width: 700px) {
  #conv-col {
    position: absolute;
    inset: 0;
    width: 100%;
    z-index: 10;
  }
  #conv-col.hidden-mobile { display: none; }

  #messages-col {
    position: absolute;
    inset: 0;
    z-index: 10;
    display: none;
  }
  #messages-col.visible-mobile { display: flex; }

  /* Profil en bottom sheet sur mobile */
  #profile-col {
    top: auto; bottom: 0;
    width: 100%;
    height: 80dvh;
    border-left: none;
    border-top: 1px solid var(--border);
    border-radius: 16px 16px 0 0;
    transform: translateY(100%);
  }
  #profile-col.open { transform: translateY(0); }
}

/* ── Composants communs ───────────────────────────────── */

/* Boutons */
.btn-primary {
  display: flex; align-items: center; gap: 6px;
  padding: 7px 12px;
  background: rgba(245,158,11,.15);
  border: 1px solid rgba(245,158,11,.3);
  border-radius: 8px;
  color: var(--amber);
  font-size: 12px;
  font-weight: 500;
  cursor: pointer;
  transition: all .15s;
  white-space: nowrap;
}
.btn-primary:hover { background: rgba(245,158,11,.22); border-color: rgba(245,158,11,.5); }

.btn-ghost {
  display: flex; align-items: center; gap: 6px;
  padding: 7px 12px;
  background: rgba(255,255,255,.04);
  border: 1px solid var(--border);
  border-radius: 8px;
  color: var(--txt-muted);
  font-size: 12px;
  cursor: pointer;
  transition: all .15s;
}
.btn-ghost:hover { color: var(--txt); background: rgba(255,255,255,.08); }

.btn-icon {
  display: flex; align-items: center; justify-content: center;
  width: 28px; height: 28px;
  border-radius: 7px;
  background: rgba(255,255,255,.04);
  border: 1px solid var(--border);
  color: var(--txt-muted);
  cursor: pointer;
  transition: all .15s;
}
.btn-icon:hover { color: var(--txt); background: rgba(255,255,255,.08); }

/* Input */
.input {
  width: 100%;
  padding: 8px 10px;
  background: rgba(255,255,255,.04);
  border: 1px solid var(--border);
  border-radius: 8px;
  color: var(--txt);
  font-size: 12px;
  font-family: inherit;
  outline: none;
  transition: border-color .15s;
}
.input:focus { border-color: rgba(245,158,11,.4); }
.input::placeholder { color: var(--txt-dim); }

/* Tabs */
.tab {
  flex: 1;
  padding: 5px 4px;
  border-radius: 6px;
  font-size: 11px;
  color: var(--txt-muted);
  background: none;
  border: none;
  cursor: pointer;
  transition: all .15s;
  white-space: nowrap;
}
.tab:hover { color: #a1a1aa; }
.tab.active { background: rgba(255,255,255,.08); color: var(--txt); }

/* Toggle */
.toggle {
  position: relative;
  width: 30px; height: 17px;
  border-radius: 99px;
  background: rgba(255,255,255,.1);
  border: none;
  cursor: pointer;
  transition: background .2s;
}
.toggle::after {
  content: '';
  position: absolute;
  top: 2px; left: 2px;
  width: 13px; height: 13px;
  border-radius: 50%;
  background: #71717a;
  transition: all .2s;
}
.toggle.on { background: rgba(45,212,191,.25); }
.toggle.on::after { left: 15px; background: var(--teal); }

/* Badges */
.badge {
  display: inline-flex; align-items: center;
  padding: 2px 6px;
  border-radius: 5px;
  font-size: 10px;
  font-weight: 500;
}
.badge-sky    { background: rgba(56,189,248,.12); color: var(--sky); }
.badge-green  { background: rgba(52,211,153,.12); color: var(--green); }
.badge-amber  { background: rgba(251,191,36,.12); color: var(--amber); }
.badge-red    { background: rgba(248,113,113,.12); color: var(--red); }
.badge-violet { background: rgba(167,139,250,.12); color: #a78bfa; }
.badge-teal   { background: rgba(45,212,191,.12); color: var(--teal); }
.badge-zinc   { background: rgba(255,255,255,.07); color: #a1a1aa; }

/* Avatars */
.av {
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 11px; font-weight: 600;
  flex-shrink: 0;
}
.av-sky     { background: rgba(56,189,248,.15);  color: var(--sky); }
.av-green   { background: rgba(52,211,153,.15);  color: var(--green); }
.av-amber   { background: rgba(251,191,36,.15);  color: var(--amber); }
.av-violet  { background: rgba(167,139,250,.15); color: #a78bfa; }
.av-teal    { background: rgba(45,212,191,.15);  color: var(--teal); }
.av-coral   { background: rgba(248,113,113,.15); color: var(--red); }
.av-default { background: rgba(255,255,255,.07); color: #71717a; }
.av-sm { width: 28px; height: 28px; font-size: 10px; }

/* Conversations list items */
.conv-item {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 10px 12px;
  border-radius: 8px;
  cursor: pointer;
  transition: background .1s;
  margin: 1px 4px;
}
.conv-item:hover { background: var(--bg-hover); }
.conv-item.active { background: rgba(255,255,255,.07); }
.conv-item.blocked { opacity: .6; }

/* Bulles messages */
.bubble {
  max-width: 78%;
  padding: 9px 12px;
  border-radius: 12px;
  font-size: 13px;
  line-height: 1.5;
  word-break: break-word;
}
.bubble-in {
  background: rgba(255,255,255,.05);
  border: 1px solid var(--border);
  border-bottom-left-radius: 4px;
  color: var(--txt);
}
.bubble-admin {
  background: rgba(245,158,11,.1);
  border: 1px solid rgba(245,158,11,.2);
  border-bottom-right-radius: 4px;
  color: var(--txt);
}
.bubble-ia {
  background: rgba(45,212,191,.07);
  border: 1px solid rgba(45,212,191,.15);
  border-bottom-left-radius: 4px;
  color: var(--txt);
}

.bubble-broadcast {
  background: rgba(255,255,255,.03);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 10px 12px;
}

.bc-header { display: flex; align-items: center; gap: 6px; margin-bottom: 6px; }
.bc-body { font-size: 12px; color: #a1a1aa; }
.bc-footer { display: flex; align-items: center; gap: 6px; margin-top: 8px; border-top: 1px solid var(--border); padding-top: 6px; }

/* Métadonnées messages */
.msg-group { margin-bottom: 8px; }
.msg-meta {
  display: flex; align-items: center; gap: 4px;
  font-size: 10px; color: var(--txt-dim);
  margin-top: 3px; padding: 0 2px;
}

.status-sent { color: #52525b; }
.status-read { color: var(--sky); }

/* Reply quote */
.reply-quote {
  background: rgba(255,255,255,.04);
  border-left: 2px solid var(--amber);
  border-radius: 0 6px 6px 0;
  padding: 5px 8px;
  font-size: 11px;
  color: var(--txt-muted);
}

/* Date séparateur */
.date-sep {
  display: flex; align-items: center;
  margin: 12px 0;
  gap: 10px;
}
.date-sep::before, .date-sep::after {
  content: '';
  flex: 1;
  height: 1px;
  background: var(--border);
}
.date-sep span {
  font-size: 10px;
  color: var(--txt-dim);
  white-space: nowrap;
  padding: 0 4px;
}

/* Bannières */
.blocked-banner, .ia-banner {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 8px 16px;
  font-size: 12px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}
.blocked-banner { background: rgba(248,113,113,.06); color: var(--red); }
.ia-banner { background: rgba(45,212,191,.05); color: var(--teal); }

/* Zone de saisie */
.compose-area {
  border-top: 1px solid var(--border);
  padding: 12px 16px;
  background: var(--bg-card);
  flex-shrink: 0;
}

.compose-input {
  flex: 1;
  background: rgba(255,255,255,.04);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 9px 12px;
  font-size: 13px;
  font-family: inherit;
  color: var(--txt);
  resize: none;
  outline: none;
  min-height: 38px;
  max-height: 120px;
  line-height: 1.5;
  transition: border-color .15s;
}
.compose-input:focus { border-color: rgba(245,158,11,.35); }
.compose-input::placeholder { color: var(--txt-dim); }

/* Chip IA */
.ai-chip {
  display: inline-flex; align-items: center;
  background: rgba(45,212,191,.1);
  border: 1px solid rgba(45,212,191,.2);
  border-radius: 4px;
  padding: 1px 5px;
  font-size: 10px;
  color: var(--teal);
  margin-bottom: 4px;
}

/* Profile column sections */
.profile-section {
  padding: 14px 16px;
  border-bottom: 1px solid var(--border);
}
.profile-label {
  font-size: 10px;
  font-weight: 500;
  color: var(--txt-dim);
  text-transform: uppercase;
  letter-spacing: .06em;
  margin-bottom: 10px;
}
.stat-row { display: flex; align-items: center; justify-content: space-between; }
.stat-label { font-size: 11px; color: var(--txt-muted); }
.stat-val { font-size: 11px; color: #a1a1aa; }

/* Progress bar */
.pbar { height: 3px; background: rgba(255,255,255,.06); border-radius: 99px; margin-top: 4px; }
.pbar-fill { height: 100%; border-radius: 99px; transition: width .3s; }

/* Media */
.media-doc {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 10px;
  background: rgba(255,255,255,.04);
  border-radius: 8px;
  border: 1px solid var(--border);
}
.media-thumb img { max-width: 220px; border-radius: 8px; display: block; cursor: pointer; }

/* Modals */
.modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.5);
  z-index: 200;
  align-items: center;
  justify-content: center;
  backdrop-filter: blur(4px);
}
.modal-overlay.open { display: flex; }

.modal {
  background: #141416;
  border: 1px solid var(--border);
  border-radius: 12px;
  width: min(480px, calc(100vw - 32px));
  max-height: 90dvh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

/* Animations */
@keyframes fadein {
  from { opacity: 0; transform: translateY(4px); }
  to   { opacity: 1; transform: translateY(0); }
}
.fadein { animation: fadein .18s ease; }

/* Loader dots */
.loader-dot {
  width: 5px; height: 5px;
  border-radius: 50%;
  background: var(--txt-dim);
  display: inline-block;
  animation: pulse 1.2s ease infinite;
}
@keyframes pulse {
  0%, 100% { opacity: .3; transform: scale(.8); }
  50%       { opacity: 1;  transform: scale(1); }
}

/* Topbar responsive */
@media (max-width: 480px) {
  .topbar-label { display: none; }
  .topbar { padding: 0 12px; }
}

/* Reply btn hover */
.reply-btn { opacity: 0; transition: opacity .15s; }
.msg-group:hover .reply-btn { opacity: 1; }
</style>
</head>

<body>

<!-- Overlay sidebar mobile -->
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<div id="app-root">

  <!-- ═══════════════════════════════════════════════════
       SIDEBAR PRINCIPALE
       ═══════════════════════════════════════════════════ -->
  <aside id="sidebar">

    <!-- Header -->
    <div class="sb-header">
      <div class="sb-logo">
        <div class="sb-logo-icon">⚡</div>
        <span class="sb-logo-text">TradingBot</span>
      </div>
      <button class="sb-close" id="sidebar-close">✕</button>
    </div>

    <!-- Nav -->
    <nav class="sb-nav">

      <p class="sb-section">Principal</p>

      <a href="/dashboard" class="sb-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <rect x="3" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/>
          <rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
        Dashboard
      </a>

      <a href="/categories" class="sb-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
          <line x1="7" y1="7" x2="7.01" y2="7"/>
        </svg>
        Catégories
      </a>

      <a href="/chat" class="sb-link active">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
        Chat direct
        <span class="badge badge-sky" style="margin-left:auto;font-size:10px;" id="nav-unread-badge"></span>
      </a>

      <a href="/message" class="sb-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M22 2 11 13"/><path d="m22 2-7 20-4-9-9-4 20-7z"/>
        </svg>
        Broadcast
      </a>

      <p class="sb-section">Trading</p>

      <a href="/trade" class="sb-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
        </svg>
        Trade
      </a>

      <p class="sb-section">Outils</p>

      <a href="/form" class="sb-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
        </svg>
        Formulaires
      </a>

      <a href="/ai" class="sb-link">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <circle cx="12" cy="12" r="3"/>
          <path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/>
        </svg>
        Agent IA
      </a>
    </nav>

    <!-- Footer -->
    <div class="sb-footer">
      <div class="sb-user">
        <div class="sb-avatar">AD</div>
        <div>
          <p style="font-size:11px;font-weight:500;color:#d4d4d8;">Admin</p>
          <p style="font-size:10px;color:var(--txt-dim);">fdkvip.com</p>
        </div>
      </div>
    </div>
  </aside>

  <!-- ═══════════════════════════════════════════════════
       ZONE PRINCIPALE
       ═══════════════════════════════════════════════════ -->
  <div id="main">

    <!-- Topbar -->
    <header class="topbar">
      <div class="topbar-left">
        <button id="hamburger" onclick="openSidebar()" aria-label="Menu">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        <h1 style="font-size:14px;font-weight:500;color:white;white-space:nowrap;">Chat direct</h1>
        <span style="color:var(--txt-dim);" class="hidden sm:inline">·</span>
        <span style="font-size:12px;color:var(--txt-dim);white-space:nowrap;" class="hidden sm:inline">Timeline unifiée</span>
      </div>
      <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
        <button class="btn-primary" onclick="openModal('modal-new-conv')">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" d="M12 4v16m8-8H4"/>
          </svg>
          <span class="topbar-label">Nouvelle conversation</span>
        </button>
      </div>
    </header>

    <!-- ── CHAT ROOT 3 colonnes ── -->
    <div id="chat-root">

      <!-- COL 1 — Liste conversations -->
      <div id="conv-col">

        <!-- Barre de recherche + tabs -->
        <div style="padding:10px;border-bottom:1px solid var(--border);flex-shrink:0;">
          <div style="position:relative;margin-bottom:8px;">
            <svg width="12" height="12" fill="none" stroke="var(--txt-dim)" viewBox="0 0 24 24" stroke-width="2"
                 style="position:absolute;left:9px;top:50%;transform:translateY(-50%);">
              <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input class="input" type="text" placeholder="Rechercher…"
                   style="padding-left:28px;font-size:12px;"
                   oninput="App.filterConvs(this.value)">
          </div>

          <div style="display:flex;align-items:center;gap:2px;background:rgba(255,255,255,.03);border:1px solid var(--border);border-radius:8px;padding:2px;overflow-x:auto;">
            <button class="tab active" onclick="App.switchConvTab(this,'all')">Tous</button>
            <button class="tab" onclick="App.switchConvTab(this,'requires_admin')">
              ⚡ Admin<span id="tab-admin-count" style="margin-left:3px;font-size:10px;color:#fb923c;"></span>
            </button>
            <button class="tab" onclick="App.switchConvTab(this,'unread')">
              Non lus<span id="tab-unread-count" style="margin-left:3px;font-size:10px;color:var(--sky);"></span>
            </button>
            <button class="tab" onclick="App.switchConvTab(this,'ia')">IA</button>
            <button class="tab" onclick="App.switchConvTab(this,'blocked')">Bloqués</button>
          </div>
        </div>

        <!-- Liste -->
        <div id="conv-list" style="flex:1;overflow-y:auto;">
          <div style="padding:30px;text-align:center;color:var(--txt-dim);font-size:12px;">
            <span class="loader-dot"></span>
            <span class="loader-dot" style="animation-delay:.15s"></span>
            <span class="loader-dot" style="animation-delay:.3s"></span>
          </div>
        </div>
      </div>

      <!-- COL 2 — Fil messages -->
      <div id="messages-col">

        <!-- Chat header -->
        <div id="chat-header" style="display:flex;align-items:center;justify-content:space-between;padding:10px 16px;border-bottom:1px solid var(--border);background:var(--bg-card);flex-shrink:0;gap:10px;">
          <div style="display:flex;align-items:center;gap:10px;min-width:0;">
            <button id="btn-back-conv" onclick="App.backToList()" aria-label="Retour"
                    style="display:none;align-items:center;justify-content:center;width:28px;height:28px;border-radius:7px;background:rgba(255,255,255,.05);border:1px solid var(--border);color:var(--txt-muted);cursor:pointer;">
              <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" d="m15 18-6-6 6-6"/>
              </svg>
            </button>
            <div class="av av-default" id="chat-av" style="width:32px;height:32px;font-size:11px;">—</div>
            <div style="min-width:0;">
              <p style="font-size:13px;font-weight:500;color:white;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" id="chat-name">Sélectionner une conversation</p>
              <p style="font-size:11px;color:var(--txt-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" id="chat-handle"></p>
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
            <div style="display:flex;align-items:center;gap:6px;">
              <span style="font-size:11px;color:var(--txt-muted);">Agent IA</span>
              <button class="toggle" id="ia-toggle" onclick="App.toggleIA(this)" title="Activer / désactiver l'IA"></button>
            </div>
            <div style="width:1px;height:16px;background:var(--border);"></div>
            <button class="btn-icon" onclick="App.openProfilePanel()" title="Profil">
              <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
            </button>
            <button class="btn-icon" onclick="openModal('modal-actions')" title="Actions">
              <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Bannière bloqué -->
        <div id="blocked-banner" class="blocked-banner" style="display:none;">
          <svg width="13" height="13" fill="none" stroke="var(--red)" viewBox="0 0 24 24" stroke-width="1.5">
            <circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
          </svg>
          <span>Ce membre a <strong>bloqué le bot</strong> — les messages ne peuvent plus lui être envoyés.</span>
        </div>

        <!-- Bannière IA -->
        <div id="ia-banner" class="ia-banner" style="display:none;">
          <svg width="12" height="12" fill="none" stroke="var(--teal)" viewBox="0 0 24 24" stroke-width="1.5">
            <circle cx="12" cy="12" r="3"/>
            <path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/>
          </svg>
          <span>L'Agent IA gère cette conversation.</span>
          <button style="margin-left:auto;font-size:10px;text-decoration:underline;background:none;border:none;color:#5eead4;cursor:pointer;"
                  onclick="App.toggleIA(document.getElementById('ia-toggle'))">
            Reprendre manuellement
          </button>
        </div>

        <!-- Fil messages -->
        <div id="messages-feed" style="flex:1;overflow-y:auto;padding:16px 20px;"></div>

        <!-- Zone de saisie -->
        <div class="compose-area" id="compose-area" style="display:none;">

          <!-- Reply preview -->
          <div id="reply-preview" style="display:none;margin-bottom:8px;display:none;align-items:center;gap:8px;">
            <div class="reply-quote" id="reply-text" style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">—</div>
            <button class="btn-icon" style="width:20px;height:20px;flex-shrink:0;" onclick="App.clearReply()">
              <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <!-- Upload preview -->
          <div id="upload-preview" style="display:none;margin-bottom:8px;"></div>

          <div style="display:flex;align-items:flex-end;gap:8px;">
            <button class="btn-icon" style="margin-bottom:2px;" title="Joindre un fichier" onclick="App.triggerUpload()">
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
              </svg>
            </button>
            <input type="file" id="file-input" style="display:none;">
            <textarea class="compose-input" id="compose-input"
                      placeholder="Sélectionner une conversation…"
                      rows="1"
                      onkeydown="App.handleKey(event)"></textarea>
            <button class="btn-primary" id="send-btn" style="flex-shrink:0;margin-bottom:2px;" onclick="App.sendMessage()">
              <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m22 2-7 20-4-9-9-4 20-7z"/>
              </svg>
              <span class="topbar-label">Envoyer</span>
            </button>
          </div>

          <div style="display:flex;align-items:center;justify-content:space-between;margin-top:6px;">
            <span style="font-size:10px;color:var(--txt-dim);">Enter · Shift+Enter saut de ligne</span>
            <span style="font-size:10px;color:var(--txt-dim);" id="compose-count">0 / 4096</span>
          </div>
        </div>

      </div><!-- /messages-col -->

      <!-- COL 3 — Profil -->
      <div id="profile-col">
        <div style="display:flex;justify-content:center;padding:10px 0 4px;">
          <div style="width:32px;height:4px;border-radius:99px;background:rgba(255,255,255,.1);"></div>
        </div>
        <div style="padding:40px 16px;text-align:center;color:var(--txt-dim);font-size:12px;">
          Sélectionnez une conversation
        </div>
      </div>

      <!-- Overlay profil -->
      <div id="profile-overlay" onclick="App.closeProfilePanel()"></div>

    </div><!-- /chat-root -->
  </div><!-- /main -->
</div><!-- /app-root -->


<!-- ═══════════════════════════════════════════════════════
     MODALS
     ═══════════════════════════════════════════════════════ -->

<!-- Actions -->
<div class="modal-overlay" id="modal-actions">
  <div class="modal" style="max-width:320px;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--border);flex-shrink:0;">
      <p style="font-size:13px;font-weight:500;color:white;">Actions</p>
      <button class="btn-icon" onclick="closeModal('modal-actions')">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div style="padding:8px 10px;display:flex;flex-direction:column;gap:2px;">
      <button class="btn-ghost" style="font-size:12px;justify-content:flex-start;width:100%;" onclick="closeModal('modal-actions')">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
        Envoyer un broadcast ciblé
      </button>
      <button class="btn-ghost" style="font-size:12px;justify-content:flex-start;width:100%;" onclick="closeModal('modal-actions')">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg>
        Ajouter à une catégorie
      </button>
      <button class="btn-ghost" style="font-size:12px;justify-content:flex-start;width:100%;" onclick="App.exportConv('json');closeModal('modal-actions')">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/></svg>
        Exporter la conversation
      </button>
      <div style="height:1px;background:var(--border);margin:4px 0;"></div>
      <button class="btn-ghost" style="font-size:12px;justify-content:flex-start;width:100%;color:var(--red);" onclick="closeModal('modal-actions')">
        <svg width="13" height="13" fill="none" stroke="var(--red)" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        Signaler comme bloqué
      </button>
    </div>
  </div>
</div>

<!-- Nouvelle conversation -->
<div class="modal-overlay" id="modal-new-conv">
  <div class="modal">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--border);flex-shrink:0;">
      <p style="font-size:13px;font-weight:500;color:white;">Nouvelle conversation</p>
      <button class="btn-icon" onclick="closeModal('modal-new-conv')">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div style="padding:18px 20px;display:flex;flex-direction:column;gap:12px;">
      <div>
        <p style="font-size:12px;color:var(--txt-muted);margin-bottom:8px;">Rechercher un membre</p>
        <input class="input" type="text" placeholder="Nom, @handle ou ID Telegram…">
      </div>
      <p style="font-size:10px;color:var(--txt-dim);">Les membres apparaîtront ici lors de la connexion à l'API.</p>
    </div>
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:12px 20px;border-top:1px solid var(--border);flex-shrink:0;">
      <button class="btn-ghost" onclick="closeModal('modal-new-conv')">Annuler</button>
      <button class="btn-primary" onclick="closeModal('modal-new-conv')">Ouvrir →</button>
    </div>
  </div>
</div>

<!-- Abonnement -->
<div class="modal-overlay" id="modal-subscription">
  <div class="modal">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid var(--border);flex-shrink:0;">
      <div>
        <p style="font-size:13px;font-weight:500;color:white;">Gérer l'abonnement</p>
        <p style="font-size:11px;color:var(--txt-muted);margin-top:2px;" id="sub-modal-name">—</p>
      </div>
      <button class="btn-icon" onclick="closeModal('modal-subscription')">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div style="padding:18px 20px;display:flex;flex-direction:column;gap:14px;overflow-y:auto;">
      <div>
        <p style="font-size:12px;color:var(--txt-muted);margin-bottom:8px;">Plan</p>
        <select class="input">
          <option value="mensuel">Mensuel — 30 jours</option>
          <option value="trimestriel">Trimestriel — 90 jours</option>
          <option value="semestriel">Semestriel — 180 jours</option>
          <option value="annuel">Annuel — 270 jours</option>
        </select>
      </div>
      <div>
        <p style="font-size:12px;color:var(--txt-muted);margin-bottom:8px;">Note interne (optionnel)</p>
        <textarea class="input" style="min-height:60px;" placeholder="Ex : offre spéciale avril…"></textarea>
      </div>
      <div style="background:rgba(45,212,191,.05);border:1px solid rgba(45,212,191,.12);border-radius:8px;padding:10px 13px;font-size:11px;color:#5eead4;">
        Les durées s'additionnent — si un abonnement actif existe, le nouveau repart de sa date d'expiration.
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:12px 20px;border-top:1px solid var(--border);flex-shrink:0;">
      <button class="btn-ghost" onclick="closeModal('modal-subscription')">Annuler</button>
      <button class="btn-primary" onclick="App.createSubscription()">Créer →</button>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════
     SCRIPTS
     ═══════════════════════════════════════════════════════ -->
<script>
  // Afficher le bouton back uniquement sur mobile
  function updateBackBtn() {
    const btn = document.getElementById('btn-back-conv')
    if (!btn) return
    btn.style.display = window.innerWidth <= 700 ? 'flex' : 'none'
  }
  updateBackBtn()
  window.addEventListener('resize', updateBackBtn)
</script>

<script type="module" src="../js/chats.js" defer></script>

</body>
</html>