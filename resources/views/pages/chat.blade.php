<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Felipe Bot — Chat direct</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400" rel="stylesheet">
<style>

/* ════════════════════════════════════════════════════════
   RESET & BASE
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
  --txt:        #e4e4e7;
  --txt-2:      #a1a1aa;
  --txt-3:      #71717a;
  --txt-4:      #52525b;
  --txt-5:      #3f3f46;
  --sidebar-w:  200px;
  --conv-w:     280px;
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

button { font-family: inherit; cursor: pointer; }
a      { text-decoration: none; }
input, textarea, select { font-family: inherit; }

::-webkit-scrollbar       { width: 3px; height: 3px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 99px; }

/* ════════════════════════════════════════════════════════
   LAYOUT RACINE : sidebar + main côte à côte
   ════════════════════════════════════════════════════════ */
#app {
  display: flex;
  height: 100dvh;
  overflow: hidden;
}

/* ════════════════════════════════════════════════════════
   SIDEBAR
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
  /* desktop : toujours visible */
  transform: translateX(0);
  transition: transform .25s ease;
  z-index: 200;
}

/* Sur mobile la sidebar passe en overlay */
#sidebar-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.55);
  z-index: 199;
}

.sb-head {
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
  border: 1px solid rgba(245,158,11,.28);
  border-radius: 6px;
  display: flex; align-items: center; justify-content: center;
  font-size: 11px;
}

.sb-logo-text {
  font-size: 13px;
  font-weight: 500;
  color: #f4f4f5;
}

#sidebar-close {
  display: none; /* visible uniquement mobile */
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

.sb-nav {
  flex: 1;
  padding: 8px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  gap: 1px;
}

.sb-label {
  font-size: 10px;
  font-weight: 500;
  color: var(--txt-5);
  text-transform: uppercase;
  letter-spacing: .06em;
  padding: 10px 10px 4px;
}

.sb-link {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 7px 10px;
  border-radius: var(--radius);
  font-size: 13px;
  color: var(--txt-4);
  transition: all .15s;
  background: none;
  border: none;
  width: 100%;
  text-align: left;
}
.sb-link:hover  { color: #d4d4d8; background: var(--hover); }
.sb-link.active { color: #f4f4f5;  background: rgba(255,255,255,.07); }
.sb-link svg    { width: 14px; height: 14px; flex-shrink: 0; stroke: currentColor; fill: none; }

.sb-badge {
  margin-left: auto;
  font-size: 10px;
  padding: 1px 5px;
  border-radius: 5px;
  background: rgba(56,189,248,.12);
  color: var(--sky);
}

.sb-foot {
  padding: 10px 12px;
  border-top: 1px solid var(--border);
  flex-shrink: 0;
}

.sb-user {
  display: flex;
  align-items: center;
  gap: 8px;
}

.sb-av {
  width: 24px; height: 24px;
  border-radius: 50%;
  background: rgba(255,255,255,.07);
  display: flex; align-items: center; justify-content: center;
  font-size: 9px; font-weight: 600;
  color: var(--txt-4);
  flex-shrink: 0;
}

/* ════════════════════════════════════════════════════════
   MAIN : topbar + chat-root
   ════════════════════════════════════════════════════════ */
#main {
  flex: 1;
  min-width: 0;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

/* ── Topbar ──────────────────────────────────────────── */
#topbar {
  flex-shrink: 0;
  height: var(--topbar-h);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 20px;
  background: var(--bg-1);
  border-bottom: 1px solid var(--border);
  gap: 12px;
}

.topbar-left {
  display: flex;
  align-items: center;
  gap: 10px;
  min-width: 0;
}

#hamburger {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 30px; height: 30px;
  flex-shrink: 0;
  border-radius: var(--radius);
  background: rgba(255,255,255,.05);
  border: 1px solid var(--border);
  color: var(--txt-4);
  transition: all .15s;
}
#hamburger:hover { color: var(--txt); background: rgba(255,255,255,.1); }
#hamburger svg   { width: 15px; height: 15px; stroke: currentColor; fill: none; }

.topbar-title {
  font-size: 14px;
  font-weight: 500;
  color: white;
  white-space: nowrap;
}

.topbar-sub {
  font-size: 12px;
  color: var(--txt-5);
  white-space: nowrap;
}

/* ════════════════════════════════════════════════════════
   CHAT ROOT : conv-col | messages-col | profile-col
   ════════════════════════════════════════════════════════ */
#chat-root {
  flex: 1;
  display: flex;
  overflow: hidden;
  position: relative;
}

/* ── Colonne 1 : liste conversations ─────────────────── */
#conv-col {
  width: var(--conv-w);
  min-width: var(--conv-w);
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  background: var(--bg-1);
  border-right: 1px solid var(--border);
  overflow: hidden;
}

.conv-filters {
  flex-shrink: 0;
  padding: 10px;
  border-bottom: 1px solid var(--border);
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.search-wrap {
  position: relative;
}

.search-wrap svg {
  position: absolute;
  left: 9px; top: 50%;
  transform: translateY(-50%);
  width: 12px; height: 12px;
  stroke: var(--txt-5); fill: none;
  pointer-events: none;
}

.search-input {
  width: 100%;
  padding: 7px 10px 7px 28px;
  background: rgba(255,255,255,.03);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  font-size: 12px;
  color: var(--txt);
  outline: none;
  transition: border-color .15s;
}
.search-input::placeholder { color: var(--txt-5); }
.search-input:focus { border-color: var(--amber-bd); }

.tabs-wrap {
  display: flex;
  gap: 2px;
  background: rgba(255,255,255,.03);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 2px;
  overflow-x: auto;
}

.tab {
  flex: 1;
  padding: 5px 6px;
  border-radius: 6px;
  font-size: 11px;
  color: var(--txt-4);
  background: none;
  border: none;
  white-space: nowrap;
  transition: all .15s;
  min-width: 0;
}
.tab:hover { color: var(--txt-2); }
.tab.active { background: rgba(255,255,255,.08); color: var(--txt); }

#conv-list {
  flex: 1;
  overflow-y: auto;
}

/* Items conversation */
.conv-item {
  display: flex;
  align-items: flex-start;
  gap: 10px;
  padding: 10px 12px;
  border-radius: var(--radius);
  margin: 2px 4px;
  cursor: pointer;
  transition: background .1s;
  animation: fadein .18s ease;
}
.conv-item:hover  { background: var(--hover); }
.conv-item.active { background: rgba(255,255,255,.07); }
.conv-item.blocked { opacity: .55; }

/* ── Colonne 2 : messages ────────────────────────────── */
#messages-col {
  flex: 1;
  min-width: 0;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  background: var(--bg);
}

.chat-header {
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 16px;
  background: var(--bg-1);
  border-bottom: 1px solid var(--border);
  gap: 10px;
  min-height: 52px;
}

.chat-header-left  { display: flex; align-items: center; gap: 10px; min-width: 0; }
.chat-header-right { display: flex; align-items: center; gap: 8px; flex-shrink: 0; }

#btn-back-conv {
  display: none; /* visible uniquement mobile */
  align-items: center; justify-content: center;
  width: 28px; height: 28px; flex-shrink: 0;
  border-radius: var(--radius);
  background: rgba(255,255,255,.05);
  border: 1px solid var(--border);
  color: var(--txt-4);
  transition: all .15s;
}
#btn-back-conv:hover { color: var(--txt); background: rgba(255,255,255,.1); }
#btn-back-conv svg   { width: 13px; height: 13px; stroke: currentColor; fill: none; }

.banner {
  flex-shrink: 0;
  display: none;
  align-items: center;
  gap: 8px;
  padding: 7px 16px;
  font-size: 12px;
  border-bottom: 1px solid var(--border);
}
.banner.show,
.banner[style*="display: flex"],
.banner[style*="display:flex"] { display: flex; }
.banner-blocked { background: rgba(248,113,113,.06); color: var(--red); }
.banner-ia      { background: rgba(45,212,191,.05); color: var(--teal); }
.banner svg     { width: 12px; height: 12px; stroke: currentColor; fill: none; flex-shrink: 0; }

#messages-feed {
  flex: 1;
  overflow-y: auto;
  padding: 16px 20px;
}

/* ── Zone de saisie ──────────────────────────────────── */
#compose-area {
  flex-shrink: 0;
  display: none;
  border-top: 1px solid var(--border);
  background: var(--bg-1);
  padding: 12px 16px;
}
#compose-area.show,
#compose-area[style*="display: block"],
#compose-area[style*="display:block"] { display: block; }

#reply-preview {
  display: none;
  align-items: center;
  gap: 8px;
  margin-bottom: 8px;
}
#reply-preview.show,
#reply-preview[style*="display: flex"],
#reply-preview[style*="display:flex"] { display: flex; }

.reply-quote {
  flex: 1;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
  font-size: 11px;
  color: var(--txt-4);
  padding: 4px 8px;
  border-left: 2px solid var(--amber);
  background: rgba(255,255,255,.04);
  border-radius: 0 6px 6px 0;
}

#upload-preview {
  margin-bottom: 8px;
}
#upload-preview:empty { display: none; }

.compose-row {
  display: flex;
  align-items: flex-end;
  gap: 8px;
}

#compose-input {
  flex: 1;
  min-height: 38px;
  max-height: 120px;
  padding: 9px 12px;
  background: rgba(255,255,255,.04);
  border: 1px solid var(--border);
  border-radius: 10px;
  font-size: 13px;
  color: var(--txt);
  resize: none;
  outline: none;
  line-height: 1.5;
  overflow-y: auto;
  transition: border-color .15s;
}
#compose-input::placeholder { color: var(--txt-5); }
#compose-input:focus { border-color: var(--amber-bd); }

.compose-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-top: 6px;
}

.compose-hint { font-size: 10px; color: var(--txt-5); }

/* ── Colonne 3 : profil ──────────────────────────────── */
#profile-col {
  width: 260px;
  min-width: 260px;
  flex-shrink: 0;
  background: var(--bg-1);
  border-left: 1px solid var(--border);
  overflow-y: auto;
  display: flex;
  flex-direction: column;
}

#profile-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.45);
  z-index: 89;
}

.profile-handle {
  display: flex;
  justify-content: center;
  padding: 10px 0 4px;
  flex-shrink: 0;
}

.profile-handle span {
  width: 32px; height: 4px;
  border-radius: 99px;
  background: rgba(255,255,255,.1);
  display: block;
}

.profile-empty {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  color: var(--txt-5);
  padding: 20px;
  text-align: center;
}

/* Profile sections (injectées par JS) */
.profile-section {
  padding: 14px 16px;
  border-bottom: 1px solid var(--border);
}
.profile-label {
  font-size: 10px;
  font-weight: 500;
  color: var(--txt-5);
  text-transform: uppercase;
  letter-spacing: .06em;
  margin-bottom: 10px;
}
.stat-row   { display: flex; align-items: center; justify-content: space-between; margin-bottom: 4px; }
.stat-label { font-size: 11px; color: var(--txt-4); }
.stat-val   { font-size: 11px; color: var(--txt-2); }
.pbar       { height: 3px; background: rgba(255,255,255,.06); border-radius: 99px; margin-top: 4px; }
.pbar-fill  { height: 100%; border-radius: 99px; }

/* ════════════════════════════════════════════════════════
   COMPOSANTS RÉUTILISABLES
   ════════════════════════════════════════════════════════ */

/* Boutons */
.btn-primary {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 12px;
  background: var(--amber-bg);
  border: 1px solid var(--amber-bd);
  border-radius: var(--radius);
  color: var(--amber);
  font-size: 12px; font-weight: 500;
  white-space: nowrap;
  transition: all .15s;
}
.btn-primary:hover { background: rgba(245,158,11,.2); border-color: rgba(245,158,11,.5); }
.btn-primary svg   { width: 11px; height: 11px; stroke: currentColor; fill: none; flex-shrink: 0; }

.btn-ghost {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 12px;
  background: rgba(255,255,255,.04);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  color: var(--txt-4);
  font-size: 12px;
  transition: all .15s;
}
.btn-ghost:hover { color: var(--txt); background: rgba(255,255,255,.08); }
.btn-ghost svg   { width: 11px; height: 11px; stroke: currentColor; fill: none; flex-shrink: 0; }

.btn-icon {
  display: inline-flex; align-items: center; justify-content: center;
  width: 28px; height: 28px;
  background: rgba(255,255,255,.04);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  color: var(--txt-4);
  transition: all .15s;
  flex-shrink: 0;
}
.btn-icon:hover { color: var(--txt); background: rgba(255,255,255,.09); }
.btn-icon svg   { width: 13px; height: 13px; stroke: currentColor; fill: none; }

.btn-icon-sm {
  display: inline-flex; align-items: center; justify-content: center;
  width: 22px; height: 22px;
  background: rgba(255,255,255,.04);
  border: 1px solid var(--border);
  border-radius: 6px;
  color: var(--txt-4);
  transition: all .15s;
  flex-shrink: 0;
}
.btn-icon-sm:hover { color: var(--txt); background: rgba(255,255,255,.09); }
.btn-icon-sm svg   { width: 10px; height: 10px; stroke: currentColor; fill: none; }

.btn-link {
  background: none;
  border: none;
  color: var(--teal);
  font-size: 10px;
  text-decoration: underline;
  cursor: pointer;
  margin-left: auto;
}

/* Input générique */
.inp {
  width: 100%;
  padding: 8px 10px;
  background: rgba(255,255,255,.04);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  color: var(--txt);
  font-size: 12px;
  outline: none;
  transition: border-color .15s;
  font-family: inherit;
}
.inp::placeholder { color: var(--txt-5); }
.inp:focus { border-color: var(--amber-bd); }

/* Toggle IA */
.toggle {
  position: relative;
  width: 30px; height: 17px;
  border-radius: 99px;
  background: rgba(255,255,255,.1);
  border: none;
  cursor: pointer;
  flex-shrink: 0;
  transition: background .2s;
}
.toggle::after {
  content: '';
  position: absolute;
  top: 2px; left: 2px;
  width: 13px; height: 13px;
  border-radius: 50%;
  background: var(--txt-3);
  transition: all .2s;
}
.toggle.on { background: rgba(45,212,191,.25); }
.toggle.on::after { left: 15px; background: var(--teal); }

/* Avatars */
.av {
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-weight: 600; flex-shrink: 0;
}
.av-sm  { width: 28px; height: 28px; font-size: 10px; }
.av-md  { width: 32px; height: 32px; font-size: 11px; }
.av-lg  { width: 44px; height: 44px; font-size: 15px; }
.av-sky     { background: rgba(56,189,248,.15);  color: var(--sky); }
.av-green   { background: rgba(52,211,153,.15);  color: var(--green); }
.av-amber   { background: rgba(251,191,36,.15);  color: var(--amber); }
.av-violet  { background: rgba(167,139,250,.15); color: var(--violet); }
.av-teal    { background: rgba(45,212,191,.15);  color: var(--teal); }
.av-coral   { background: rgba(248,113,113,.15); color: var(--red); }
.av-default { background: rgba(255,255,255,.07); color: var(--txt-3); }

/* Badges */
.badge {
  display: inline-flex; align-items: center;
  padding: 2px 6px;
  border-radius: 5px;
  font-size: 10px; font-weight: 500;
}
.badge-sky    { background: rgba(56,189,248,.12);  color: var(--sky); }
.badge-green  { background: rgba(52,211,153,.12);  color: var(--green); }
.badge-amber  { background: rgba(251,191,36,.12);  color: var(--amber); }
.badge-red    { background: rgba(248,113,113,.12); color: var(--red); }
.badge-violet { background: rgba(167,139,250,.12); color: var(--violet); }
.badge-teal   { background: rgba(45,212,191,.12);  color: var(--teal); }
.badge-zinc   { background: rgba(255,255,255,.07); color: var(--txt-2); }

/* Chip IA */
.ai-chip {
  display: inline-flex; align-items: center;
  background: rgba(45,212,191,.1);
  border: 1px solid rgba(45,212,191,.2);
  border-radius: 4px;
  padding: 1px 5px;
  font-size: 10px; color: var(--teal);
  margin-bottom: 3px;
}

/* Bulles messages */
.bubble {
  display: inline-block;
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
}
.bubble-admin {
  background: rgba(245,158,11,.10);
  border: 1px solid rgba(245,158,11,.20);
  border-bottom-right-radius: 4px;
}
.bubble-ia {
  background: rgba(45,212,191,.07);
  border: 1px solid rgba(45,212,191,.15);
  border-bottom-left-radius: 4px;
}

/* Broadcast */
.bubble-broadcast {
  max-width: 82%;
  background: rgba(255,255,255,.03);
  border: 1px solid var(--border);
  border-radius: 10px;
  padding: 10px 12px;
  font-size: 12px;
}
.bc-header { display: flex; align-items: center; gap: 6px; margin-bottom: 6px; color: var(--txt-3); }
.bc-header svg { width: 13px; height: 13px; stroke: currentColor; fill: none; }
.bc-body   { color: var(--txt-2); line-height: 1.5; }
.bc-footer { display: flex; align-items: center; gap: 6px; margin-top: 8px; padding-top: 6px; border-top: 1px solid var(--border); }

/* Groupe message */
.msg-group { margin-bottom: 8px; animation: fadein .18s ease; }

.msg-row   { display: flex; align-items: flex-end; gap: 8px; }
.msg-right { justify-content: flex-end; }

.msg-meta {
  display: flex; align-items: center; gap: 4px;
  font-size: 10px; color: var(--txt-5);
  margin-top: 3px; padding: 0 2px;
}
.msg-meta-right { justify-content: flex-end; }

.status-sent { color: var(--txt-4); }
.status-read { color: var(--sky); }

.admin-banner {
  display: flex; align-items: center; gap: 5px;
  font-size: 10px; color: #fb923c;
  margin-bottom: 4px;
}
.admin-banner svg { width: 10px; height: 10px; stroke: #fb923c; fill: none; }

/* Séparateur date */
.date-sep {
  display: flex; align-items: center;
  margin: 12px 0; gap: 10px;
}
.date-sep::before, .date-sep::after {
  content: ''; flex: 1; height: 1px;
  background: var(--border);
}
.date-sep span {
  font-size: 10px; color: var(--txt-5);
  white-space: nowrap; padding: 0 4px;
}

/* Media */
.media-doc {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 10px;
  background: rgba(255,255,255,.04);
  border: 1px solid var(--border);
  border-radius: var(--radius);
  font-size: 12px; color: var(--txt-2);
  margin-bottom: 6px;
}
.media-doc svg { width: 13px; height: 13px; stroke: currentColor; fill: none; flex-shrink: 0; }
.media-thumb   { margin-bottom: 6px; }
.media-thumb img { max-width: 220px; border-radius: var(--radius); display: block; cursor: pointer; }

/* Boutons reply (hover) */
.reply-actions { display: flex; flex-direction: column; gap: 3px; }
.reply-btn { opacity: 0; transition: opacity .15s; }
.msg-group:hover .reply-btn { opacity: 1; }

/* Dots loader */
.dots { display: flex; align-items: center; justify-content: center; gap: 5px; padding: 32px; }
.dot  { width: 5px; height: 5px; border-radius: 50%; background: var(--txt-5); }
.dot:nth-child(1) { animation: dotpulse 1.2s ease 0s    infinite; }
.dot:nth-child(2) { animation: dotpulse 1.2s ease .15s  infinite; }
.dot:nth-child(3) { animation: dotpulse 1.2s ease .3s   infinite; }

/* ════════════════════════════════════════════════════════
   MODALS
   ════════════════════════════════════════════════════════ */
.modal-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,.55);
  z-index: 300;
  align-items: center;
  justify-content: center;
  padding: 16px;
}
.modal-overlay.open { display: flex; }

.modal {
  background: var(--bg-2);
  border: 1px solid var(--border);
  border-radius: 12px;
  width: min(480px, 100%);
  max-height: 90dvh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.modal-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 20px;
  border-bottom: 1px solid var(--border);
  flex-shrink: 0;
}
.modal-head h2 { font-size: 13px; font-weight: 500; color: white; }
.modal-body    { padding: 18px 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 14px; }
.modal-foot    { display: flex; align-items: center; justify-content: flex-end; gap: 8px; padding: 12px 20px; border-top: 1px solid var(--border); flex-shrink: 0; }

.modal-label { font-size: 12px; color: var(--txt-4); margin-bottom: 7px; }

.modal-tip {
  background: rgba(45,212,191,.05);
  border: 1px solid rgba(45,212,191,.12);
  border-radius: var(--radius);
  padding: 10px 13px;
  font-size: 11px;
  color: #5eead4;
}

.divider { height: 1px; background: var(--border); margin: 2px 0; }

/* ════════════════════════════════════════════════════════
   ANIMATIONS
   ════════════════════════════════════════════════════════ */
@keyframes fadein {
  from { opacity: 0; transform: translateY(4px); }
  to   { opacity: 1; transform: translateY(0); }
}
@keyframes dotpulse {
  0%, 100% { opacity: .3; transform: scale(.8); }
  50%       { opacity: 1;  transform: scale(1); }
}

/* ════════════════════════════════════════════════════════
   RESPONSIVE
   ════════════════════════════════════════════════════════ */

/* Tablette ≤ 1024px : masquer la colonne profil, drawer latéral */
@media (max-width: 1024px) {
  #profile-col {
    position: fixed;
    top: 0; right: 0;
    height: 100%;
    width: min(300px, 90vw);
    z-index: 90;
    transform: translateX(100%);
    transition: transform .25s ease;
    border-left: 1px solid var(--border);
  }
  #profile-col.open { transform: translateX(0); }
  #profile-overlay.open { display: block; }
}

/* Mobile ≤ 700px : sidebar en overlay + stack conv/messages */
@media (max-width: 700px) {

  /* Sidebar en overlay */
  #sidebar {
    position: fixed;
    top: 0; left: 0;
    height: 100%;
    transform: translateX(-100%);
  }
  #sidebar.open { transform: translateX(0); }
  #sidebar-overlay.open { display: block; }
  #sidebar-close { display: flex; }

  /* Topbar compact */
  #topbar { padding: 0 12px; }
  .topbar-sub { display: none; }

  /* Col 1 : plein écran, cachée si messages actifs */
  #conv-col {
    position: absolute;
    inset: 0;
    width: 100%;
    min-width: 0;
    z-index: 5;
  }
  #conv-col.hidden-mobile { display: none; }

  /* Col 2 : plein écran, cachée par défaut */
  #messages-col {
    position: absolute;
    inset: 0;
    z-index: 5;
    display: none;
  }
  #messages-col.visible-mobile,
  #messages-col.visible { display: flex; }

  /* Col 3 : bottom sheet sur mobile */
  #profile-col {
    top: auto; bottom: 0;
    right: 0;
    width: 100%;
    height: 80dvh;
    border-left: none;
    border-top: 1px solid var(--border);
    border-radius: 16px 16px 0 0;
    transform: translateY(100%);
    transition: transform .25s ease;
  }
  #profile-col.open { transform: translateY(0); }

  /* Bouton retour visible */
  #btn-back-conv { display: flex; }

  /* Réduire messages feed padding */
  #messages-feed { padding: 12px; }
}

/* Masquer profil < 1024 depuis le flux normal */
@media (max-width: 1024px) {
  /* Le profil est en position fixed donc ne prend plus de place */
}

/* Très petit ≤ 380px */
@media (max-width: 380px) {
  .topbar-title { font-size: 13px; }
  #compose-input { font-size: 12px; }
}

</style>
</head>

<body>
<div id="app">

  <!-- ══════════════════════════════════════════════
       OVERLAY SIDEBAR
       ══════════════════════════════════════════════ -->
  <div id="sidebar-overlay" onclick="closeSidebar()"></div>

  <!-- ══════════════════════════════════════════════
       SIDEBAR
       ══════════════════════════════════════════════ -->
  <aside id="sidebar">

    <div class="sb-head">
      <div class="sb-logo">
        <div class="sb-logo-icon">⚡</div>
        <span class="sb-logo-text">Felipe Bot</span>
      </div>
      <button id="sidebar-close" onclick="closeSidebar()">✕</button>
    </div>

    <nav class="sb-nav">

      <p class="sb-label">Principal</p>

      <a href="/dashboard" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5">
          <rect x="3" y="3" width="7" height="7" rx="1"/>
          <rect x="14" y="3" width="7" height="7" rx="1"/>
          <rect x="3" y="14" width="7" height="7" rx="1"/>
          <rect x="14" y="14" width="7" height="7" rx="1"/>
        </svg>
        Dashboard
      </a>

      <a href="/categories" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
          <line x1="7" y1="7" x2="7.01" y2="7"/>
        </svg>
        Catégories
      </a>

      <a href="/chat" class="sb-link active">
        <svg viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
        Chat direct
        <span id="nav-unread-badge" class="sb-badge" style="display:none;"></span>
      </a>

      <a href="/message" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M22 2 11 13"/><path d="m22 2-7 20-4-9-9-4 20-7z"/>
        </svg>
        Broadcast
      </a>

      <p class="sb-label">Trading</p>

      <a href="/trade" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
        </svg>
        Trade
      </a>
      <p class="sb-label">Croissance</p>
      <a href="/trade" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        Liens & Onboarding
      </a>

      <p class="sb-label">Outils</p>

      <a href="/form" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
        </svg>
        Formulaires
      </a>

      <a href="/ai" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5">
          <circle cx="12" cy="12" r="3"/>
          <path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/>
        </svg>
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
    <div id="topbar">
      <div class="topbar-left">
        <!-- Hamburger — toujours visible -->
        <button id="hamburger" onclick="openSidebar()">
          <svg viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        <span class="topbar-title">Chat direct</span>
        <span class="topbar-sub">· Timeline unifiée</span>
      </div>
      <div>
        <button class="btn-primary" onclick="openModal('modal-new-conv')">
          <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
          <span class="hide-xs">Nouvelle conversation</span>
          <span class="show-xs" style="display:none;">Nouveau</span>
        </button>
      </div>
    </div>

    <!-- Chat root -->
    <div id="chat-root">

      <!-- COL 1 — Conversations -->
      <div id="conv-col">

        <div class="conv-filters">
          <div class="search-wrap">
            <svg viewBox="0 0 24 24" stroke-width="2">
              <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
            </svg>
            <input class="search-input"
                   type="text"
                   placeholder="Rechercher…"
                   oninput="App.filterConvs(this.value)">
          </div>

          <div class="tabs-wrap">
            <button class="tab active" onclick="App.switchConvTab(this,'all')">Tous</button>
            <button class="tab" onclick="App.switchConvTab(this,'requires_admin')">
              ⚡ <span id="tab-admin-count" style="color:#fb923c;"></span>
            </button>
            <button class="tab" onclick="App.switchConvTab(this,'unread')">
              Non lus <span id="tab-unread-count" style="color:var(--sky);"></span>
            </button>
            <button class="tab" onclick="App.switchConvTab(this,'ia')">IA</button>
            <button class="tab" onclick="App.switchConvTab(this,'blocked')">Bloqués</button>
          </div>
        </div>

        <div id="conv-list">
          <div class="dots">
            <div class="dot"></div><div class="dot"></div><div class="dot"></div>
          </div>
        </div>
      </div>

      <!-- COL 2 — Messages -->
      <div id="messages-col">

        <!-- Header -->
        <div class="chat-header">
          <div class="chat-header-left">
            <button id="btn-back-conv" onclick="App.backToList()">
              <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" d="m15 18-6-6 6-6"/>
              </svg>
            </button>
            <div id="chat-av" class="av av-md av-default">—</div>
            <div style="min-width:0;">
              <p id="chat-name" style="font-size:13px;font-weight:500;color:white;
                 overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                Sélectionner une conversation
              </p>
              <p id="chat-handle" style="font-size:11px;color:var(--txt-4);
                 overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></p>
            </div>
          </div>

          <div class="chat-header-right">
            <span style="font-size:11px;color:var(--txt-4);" class="hide-sm">Agent IA</span>
            <button class="toggle" id="ia-toggle" onclick="App.toggleIA(this)"></button>
            <div style="width:1px;height:16px;background:var(--border);"></div>
            <button class="btn-icon" onclick="App.openProfilePanel()" title="Profil">
              <svg viewBox="0 0 24 24" stroke-width="1.5">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                <circle cx="12" cy="7" r="4"/>
              </svg>
            </button>
            <button class="btn-icon" onclick="openModal('modal-actions')" title="Actions">
              <svg viewBox="0 0 24 24" stroke-width="1.5">
                <circle cx="12" cy="5" r="1"/>
                <circle cx="12" cy="12" r="1"/>
                <circle cx="12" cy="19" r="1"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Bannière bloqué -->
        <div id="blocked-banner" class="banner banner-blocked">
          <svg viewBox="0 0 24 24" stroke-width="1.5">
            <circle cx="12" cy="12" r="10"/>
            <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
          </svg>
          Ce membre a <strong style="margin:0 3px;">bloqué le bot</strong> — les messages ne peuvent plus lui être envoyés.
        </div>

        <!-- Bannière IA -->
        <div id="ia-banner" class="banner banner-ia">
          <svg viewBox="0 0 24 24" stroke-width="1.5">
            <circle cx="12" cy="12" r="3"/>
            <path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/>
          </svg>
          L'Agent IA gère cette conversation.
          <button class="btn-link" onclick="App.toggleIA(document.getElementById('ia-toggle'))">
            Reprendre manuellement
          </button>
        </div>

        <!-- Fil messages -->
        <div id="messages-feed">
          <div class="dots">
            <div class="dot"></div><div class="dot"></div><div class="dot"></div>
          </div>
        </div>

        <!-- Zone saisie -->
        <div id="compose-area">

          <div id="reply-preview">
            <div class="reply-quote" id="reply-text">—</div>
            <button class="btn-icon-sm" onclick="App.clearReply()">
              <svg viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <div id="upload-preview"></div>
          <div class="compose-row">
            <button class="btn-icon" style="margin-bottom:2px;" onclick="App.triggerUpload()" title="Joindre">
              <svg viewBox="0 0 24 24" stroke-width="1.5">
                <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
              </svg>
            </button>
            <input type="file" id="file-input" style="display:none;">
            <textarea id="compose-input"
                      placeholder="Sélectionner une conversation…"
                      rows="1"
                      onkeydown="App.handleKey(event)"
                      oninput="App.autoResize(this)"></textarea>
            <button id="send-btn" class="btn-primary" style="margin-bottom:2px;" onclick="App.sendMessage()">
              <svg viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m22 2-7 20-4-9-9-4 20-7z"/>
              </svg>
              <span id="send-label">Envoyer</span>
            </button>
          </div>

          <div class="compose-footer">
            <span class="compose-hint">Enter pour envoyer · Shift+Enter saut de ligne</span>
            <span class="compose-hint" id="compose-count">0 / 4096</span>
          </div>
        </div>

      </div><!-- /messages-col -->

      <!-- COL 3 — Profil -->
      <div id="profile-col">
        <div class="profile-handle"><span></span></div>
        <div class="profile-empty">Sélectionnez une conversation</div>
      </div>

      <!-- Overlay profil -->
      <div id="profile-overlay" onclick="App.closeProfilePanel()"></div>

    </div><!-- /chat-root -->
  </div><!-- /main -->
</div><!-- /app -->


<!-- ══════════════════════════════════════════════
     MODALS
     ══════════════════════════════════════════════ -->

<!-- Actions -->
<div class="modal-overlay" id="modal-actions">
  <div class="modal" style="max-width:320px;">
    <div class="modal-head">
      <h2>Actions</h2>
      <button class="btn-icon" onclick="closeModal('modal-actions')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div style="padding:8px 10px;display:flex;flex-direction:column;gap:4px;">
      <button class="btn-ghost" style="justify-content:flex-start;width:100%;font-size:12px;"
              onclick="closeModal('modal-actions')">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
        Envoyer un broadcast ciblé
      </button>
      <button class="btn-ghost" style="justify-content:flex-start;width:100%;font-size:12px;"
              onclick="closeModal('modal-actions')">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg>
        Ajouter à une catégorie
      </button>
      <button class="btn-ghost" style="justify-content:flex-start;width:100%;font-size:12px;"
              onclick="App.exportConv('json');closeModal('modal-actions')">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/></svg>
        Exporter la conversation
      </button>
      <div class="divider"></div>
      <button class="btn-ghost" style="justify-content:flex-start;width:100%;font-size:12px;color:var(--red);"
              onclick="closeModal('modal-actions')">
        <svg viewBox="0 0 24 24" stroke-width="1.5" style="stroke:var(--red);">
          <circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
        </svg>
        Signaler comme bloqué
      </button>
    </div>
  </div>
</div>

<!-- Nouvelle conversation -->
<div class="modal-overlay" id="modal-new-conv">
  <div class="modal">
    <div class="modal-head">
      <h2>Nouvelle conversation</h2>
      <button class="btn-icon" onclick="closeModal('modal-new-conv')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div>
        <p class="modal-label">Rechercher un membre</p>
        <input class="inp" type="text" placeholder="Nom, @handle ou ID Telegram…">
      </div>
      <p style="font-size:10px;color:var(--txt-5);">Les membres apparaîtront ici lors de la connexion à l'API.</p>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeModal('modal-new-conv')">Annuler</button>
      <button class="btn-primary" onclick="closeModal('modal-new-conv')">Ouvrir →</button>
    </div>
  </div>
</div>

<!-- Abonnement -->
<div class="modal-overlay" id="modal-subscription">
  <div class="modal">
    <div class="modal-head">
      <div>
        <h2>Gérer l'abonnement</h2>
        <p id="sub-modal-name" style="font-size:11px;color:var(--txt-4);margin-top:2px;">—</p>
      </div>
      <button class="btn-icon" onclick="closeModal('modal-subscription')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div>
        <p class="modal-label">Plan</p>
        <select class="inp" style="cursor:pointer;">
          <option value="mensuel">Mensuel — 30 jours</option>
          <option value="trimestriel">Trimestriel — 90 jours</option>
          <option value="semestriel">Semestriel — 180 jours</option>
          <option value="annuel">Annuel — 270 jours</option>
        </select>
      </div>
      <div>
        <p class="modal-label">Note interne (optionnel)</p>
        <textarea class="inp" style="min-height:60px;resize:vertical;" placeholder="Ex : offre spéciale…"></textarea>
      </div>
      <div class="modal-tip">
        Les durées s'additionnent — si un abonnement actif existe, le nouveau repart de sa date d'expiration.
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeModal('modal-subscription')">Annuler</button>
      <button class="btn-primary" onclick="App.createSubscription()">Créer →</button>
    </div>
  </div>
</div>


<!-- ══════════════════════════════════════════════
     SCRIPTS
     ══════════════════════════════════════════════ -->
<script>
/* ── Sidebar ─────────────────────────────── */
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

/* ── Modals ──────────────────────────────── */
function openModal(id) {
  const el = document.getElementById(id)
  if (el) el.classList.add('open')
}
function closeModal(id) {
  const el = document.getElementById(id)
  if (el) el.classList.remove('open')
}

/* Exposer globalement pour les onclick HTML et pour chat_app.js */
window.openSidebar  = openSidebar
window.closeSidebar = closeSidebar
window.openModal    = openModal
window.closeModal   = closeModal

/* Fermer modal en cliquant sur l'overlay */
document.addEventListener('click', e => {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('open')
  }
})

/* Échap */
document.addEventListener('keydown', e => {
  if (e.key !== 'Escape') return
  document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'))
  if (window.innerWidth <= 700) closeSidebar()
})

/* Resize : reset sidebar si on passe en desktop */
window.addEventListener('resize', () => {
  if (window.innerWidth > 700) {
    document.getElementById('sidebar-overlay').classList.remove('open')
    document.body.style.overflow = ''
  }
})

/* ── Tabs ────────────────────────────────── */
/* NB: chat_app.js gère aussi switchConvTab — on ne double-bind pas */
document.querySelectorAll('.tab').forEach(tab => {
  tab.addEventListener('click', function () {
    this.closest('.tabs-wrap').querySelectorAll('.tab').forEach(t => t.classList.remove('active'))
    this.classList.add('active')
  })
})
</script>

<script type="module" src="../js/chats.js" defer></script>

</body>
</html>