<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Relances — Felipe Bot Admin</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400,500" rel="stylesheet">
<style>
/* ═══════════════════════════════════════════
   RESET + VARIABLES (identiques à growth.html)
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
  --pink:       #f472b6;
  --pink-bg:    rgba(244,114,182,.1);
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
  width: 100%; text-decoration: none; cursor: pointer;
}
.sb-link:hover  { color: #d4d4d8; background: var(--hover); }
.sb-link.active { color: #f4f4f5; background: rgba(255,255,255,.07); }
.sb-link svg    { width: 14px; height: 14px; flex-shrink: 0; stroke: currentColor; fill: none; }
.sb-badge {
  margin-left: auto; font-size: 10px; padding: 1px 5px;
  border-radius: 5px; background: var(--sky-bg); color: var(--sky);
}
.sb-badge-green { background: var(--green-bg); color: var(--green); }
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
.topbar-sub   { font-size: 12px; color: var(--txt-5); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.topbar-sep   { font-size: 12px; color: var(--txt-5); flex-shrink: 0; }

/* ═══════════════════════════════════════════
   BOUTONS
   ═══════════════════════════════════════════ */
.btn-primary {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 12px; background: var(--amber-bg); border: 1px solid var(--amber-bd);
  border-radius: var(--radius); color: var(--amber); font-size: 12px; font-weight: 500;
  white-space: nowrap; transition: all .15s; cursor: pointer;
}
.btn-primary:hover   { background: rgba(245,158,11,.2); }
.btn-primary:disabled { opacity: .45; cursor: not-allowed; }
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
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 11px; background: var(--red-bg); border: 1px solid rgba(248,113,113,.25);
  border-radius: var(--radius); color: var(--red); font-size: 12px;
  transition: all .15s; cursor: pointer; white-space: nowrap;
}
.btn-danger:hover { background: rgba(248,113,113,.18); }
.btn-danger svg   { width: 11px; height: 11px; stroke: currentColor; fill: none; }

.btn-teal {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 11px; background: var(--teal-bg); border: 1px solid rgba(45,212,191,.25);
  border-radius: var(--radius); color: var(--teal); font-size: 12px;
  transition: all .15s; cursor: pointer; white-space: nowrap;
}
.btn-teal:hover { background: rgba(45,212,191,.18); }

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
textarea.inp  { resize: vertical; line-height: 1.5; }
select.inp    { appearance: none; -webkit-appearance: none; cursor: pointer; }
.inp-sm       { padding: 5px 8px; font-size: 11px; }
.inp-mono     { font-family: 'Geist Mono', monospace; }

/* ═══════════════════════════════════════════
   BADGES
   ═══════════════════════════════════════════ */
.badge      { display: inline-flex; align-items: center; padding: 2px 7px; border-radius: 99px; font-size: 10px; font-weight: 500; }
.bdg-g      { background: var(--green-bg);  color: var(--green);  }
.bdg-r      { background: var(--red-bg);    color: var(--red);    }
.bdg-a      { background: var(--amber-bg);  color: var(--amber);  }
.bdg-b      { background: var(--sky-bg);    color: var(--sky);    }
.bdg-t      { background: var(--teal-bg);   color: var(--teal);   }
.bdg-v      { background: var(--violet-bg); color: var(--violet); }
.bdg-z      { background: rgba(255,255,255,.06); color: var(--txt-3); }

/* ═══════════════════════════════════════════
   TOGGLE SWITCH
   ═══════════════════════════════════════════ */
.toggle {
  position: relative; width: 30px; height: 17px;
  border-radius: 99px; background: rgba(255,255,255,.1);
  border: none; cursor: pointer; flex-shrink: 0; transition: background .2s;
}
.toggle::after {
  content: ''; position: absolute; top: 2px; left: 2px;
  width: 13px; height: 13px; border-radius: 50%;
  background: var(--txt-3); transition: all .2s;
}
.toggle.on { background: rgba(45,212,191,.25); }
.toggle.on::after { left: 15px; background: var(--teal); }

/* ═══════════════════════════════════════════
   CARD
   ═══════════════════════════════════════════ */
.card {
  background: var(--bg-2); border: 1px solid var(--border-2);
  border-radius: var(--radius-lg);
}

/* ═══════════════════════════════════════════
   STAT MINI
   ═══════════════════════════════════════════ */
.stat-grid {
  display: grid; grid-template-columns: repeat(4, 1fr);
  gap: 8px; flex-shrink: 0;
}
.stat-grid-5 { grid-template-columns: repeat(5, 1fr); }
.stat-grid-3 { grid-template-columns: repeat(3, 1fr); }
@media (max-width: 768px) {
  .stat-grid   { grid-template-columns: repeat(2,1fr); }
  .stat-grid-5 { grid-template-columns: repeat(2,1fr); }
}
.stat-m {
  background: var(--bg-2); border: 1px solid var(--border-2);
  border-radius: var(--radius-lg); padding: 12px 14px;
}
.stat-lbl  { font-size: 10px; color: var(--txt-4); margin-bottom: 6px; }
.stat-val  { font-size: 20px; font-weight: 300; color: white; font-variant-numeric: tabular-nums; }
.stat-sub  { font-size: 10px; margin-top: 4px; color: var(--txt-4); }
.pos       { color: var(--green); }
.neg       { color: var(--red); }
.sky-txt   { color: var(--sky); }

/* ═══════════════════════════════════════════
   PBAR
   ═══════════════════════════════════════════ */
.pbar      { height: 3px; background: rgba(255,255,255,.06); border-radius: 99px; overflow: hidden; }
.pbar-f    { height: 100%; border-radius: 99px; transition: width .4s var(--ease); }

/* ═══════════════════════════════════════════
   MAIN GRID (2 colonnes)
   ═══════════════════════════════════════════ */
.main-grid {
  display: grid; grid-template-columns: 1fr 340px; gap: 14px; align-items: start;
}
.col-2 { grid-column: 1 / -1; }
@media (max-width: 1000px) { .main-grid { grid-template-columns: 1fr; } }

/* ═══════════════════════════════════════════
   CARD HEADER
   ═══════════════════════════════════════════ */
.card-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 12px 16px; border-bottom: 1px solid var(--border-2); flex-shrink: 0;
}
.card-title { font-size: 13px; font-weight: 500; color: white; }

/* ═══════════════════════════════════════════
   TELEGRAM PREVIEW
   ═══════════════════════════════════════════ */
.tg {
  background: rgba(255,255,255,.025); border: 1px solid var(--border-2);
  border-radius: var(--radius-lg); overflow: hidden;
}
.tg-top {
  display: flex; align-items: center; gap: 6px;
  padding: 8px 12px; border-bottom: 1px solid var(--border-3);
  background: rgba(14,165,233,.06);
}
.tg-body   { padding: 10px 12px; }
.tg-bbl    {
  background: rgba(56,189,248,.08); border-radius: 0 var(--radius-lg) var(--radius-lg);
  padding: 8px 10px; font-size: 11px; color: var(--txt-2); line-height: 1.5;
  max-width: 90%; display: inline-block; word-break: break-word;
}
.tg-ico {
  width: 18px; height: 18px; border-radius: 50%;
  background: #0ea5e9; display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}

/* ═══════════════════════════════════════════
   MODAL
   ═══════════════════════════════════════════ */
.overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.6); z-index: 300;
  align-items: center; justify-content: center; padding: 16px;
  backdrop-filter: blur(4px);
}
.overlay.open { display: flex; }
.modal {
  background: var(--bg-3); border: 1px solid rgba(255,255,255,.1);
  border-radius: var(--radius-lg);
  width: min(520px, 100%); max-height: 92dvh;
  display: flex; flex-direction: column; overflow: hidden;
  box-shadow: 0 32px 80px rgba(0,0,0,.5);
}
.modal-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 20px; border-bottom: 1px solid var(--border); flex-shrink: 0;
}
.modal-head h2   { font-size: 13px; font-weight: 500; color: white; }
.modal-head p    { font-size: 11px; color: var(--txt-4); margin-top: 2px; }
.modal-body      { padding: 18px 20px; overflow-y: auto; min-height: 0; display: flex; flex-direction: column; gap: 14px; }
.modal-body-scroll { max-height: 65dvh; overflow-y: auto; padding: 18px 20px; display: flex; flex-direction: column; gap: 12px; }
.modal-foot      { display: flex; align-items: center; justify-content: flex-end; gap: 8px; padding: 12px 20px; border-top: 1px solid var(--border); flex-shrink: 0; }
.field-label     { font-size: 11px; color: var(--txt-4); margin-bottom: 6px; display: block; }
.field-label span { color: var(--txt-5); }
.form-grid-2     { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
@media (max-width: 480px) { .form-grid-2 { grid-template-columns: 1fr; } }

/* ═══════════════════════════════════════════
   TOAST
   ═══════════════════════════════════════════ */
#toast-container {
  position: fixed; bottom: 20px; right: 20px;
  display: flex; flex-direction: column; gap: 8px;
  z-index: 9999; pointer-events: none;
}
.toast {
  padding: 10px 14px; border-radius: var(--radius);
  background: var(--bg-3); border: 1px solid var(--border);
  font-size: 12px; color: var(--txt);
  animation: fadein .2s; max-width: 300px; pointer-events: auto;
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
@keyframes spin   { to { transform: rotate(360deg); } }
.fadein  { animation: fadein .18s var(--ease); }
.spinner { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,.1); border-top-color: var(--sky); border-radius: 50%; animation: spin .6s linear infinite; }

/* ═══════════════════════════════════════════
   CONTENU VUES
   ═══════════════════════════════════════════ */
#content { flex: 1; min-height: 0; overflow-y: auto; padding: 16px; }
.view { display: flex; flex-direction: column; gap: 14px; }

/* ═══════════════════════════════════════════
   SEGMENT CARD (catégories de relance)
   — élément signature de cette page : chaque
   segment porte sa propre fenêtre temporelle
   ═══════════════════════════════════════════ */
.seg-row {
  display: flex; align-items: center; gap: 12px;
  padding: 12px 14px; border-radius: var(--radius-lg);
  background: var(--bg-2); border: 1px solid var(--border-2);
  transition: all .15s;
}
.seg-row:hover { border-color: rgba(255,255,255,.12); }
.seg-dot {
  width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0;
}
.seg-info  { flex: 1; min-width: 0; }
.seg-name  { font-size: 12px; font-weight: 500; color: white; }
.seg-when  { font-size: 10px; color: var(--txt-4); margin-top: 1px; }
.seg-count { text-align: right; flex-shrink: 0; }
.seg-count .n { font-size: 18px; font-weight: 300; color: white; font-variant-numeric: tabular-nums; }
.seg-count .l { font-size: 9px; color: var(--txt-4); }
.seg-msg-toggle {
  display: flex; align-items: center; justify-content: center;
  width: 28px; height: 28px; flex-shrink: 0;
  background: rgba(255,255,255,.04); border: 1px solid var(--border);
  border-radius: var(--radius); color: var(--txt-4); cursor: pointer; transition: all .15s;
}
.seg-msg-toggle:hover { color: var(--txt); background: rgba(255,255,255,.09); }
.seg-msg-toggle svg { width: 12px; height: 12px; stroke: currentColor; fill: none; }

/* Carte "fenêtre éditable" — apparaît sous le segment */
.seg-editor {
  display: none; margin-top: 8px; padding: 12px 14px;
  border-radius: var(--radius-lg); background: rgba(255,255,255,.02);
  border: 1px solid var(--border-2);
}
.seg-editor.open { display: block; }

/* ═══════════════════════════════════════════
   TIMELINE (historique des relances)
   ═══════════════════════════════════════════ */
.timeline-row {
  display: flex; align-items: flex-start; gap: 12px;
  padding: 10px 0; border-bottom: 1px solid var(--border-3);
}
.timeline-row:last-child { border-bottom: none; }
.timeline-time { width: 46px; text-align: center; flex-shrink: 0; font-size: 11px; color: var(--sky); font-weight: 500; padding-top: 1px; }
.timeline-sep  { width: 1px; align-self: stretch; background: rgba(56,189,248,.25); flex-shrink: 0; }
.timeline-body { flex: 1; min-width: 0; }
.timeline-head { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 3px; }
.timeline-cat  { font-size: 12px; font-weight: 500; color: white; }
.timeline-meta { font-size: 11px; color: var(--txt-4); }

/* ═══════════════════════════════════════════
   STATUS BAR (job actif/inactif)
   ═══════════════════════════════════════════ */
.status-row {
  display: flex; align-items: center; gap: 12px; padding: 14px 16px;
}
.status-dot {
  width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0;
  background: var(--green); box-shadow: 0 0 0 3px rgba(52,211,153,.18);
}
.status-dot.off { background: var(--txt-5); box-shadow: none; }

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
  #sb-close { display: flex; }
  #topbar   { padding: 0 10px; }
  .topbar-sub, .topbar-sep { display: none; }
  .btn-txt  { display: none; }
  #content  { padding: 10px; }
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
        <span class="sb-logo-txt">Felipe Bot</span>
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
      <a href="/relances" class="sb-link active" id="nav-relances">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 12a9 9 0 1 1-3.5-7.1M21 4v6h-6"/></svg>
        Relances
        <span class="sb-badge" id="sb-relances-badge">—</span>
      </a>
      <p class="sb-label">Outils</p>
      <a href="/form" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
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

  <!-- ─── MAIN ─── -->
  <div id="main">

    <!-- Topbar -->
    <header id="topbar">
      <div class="topbar-left">
        <button id="hamburger" onclick="openSidebar()">
          <svg viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <span class="topbar-title">Relances</span>
        <span class="topbar-sep">·</span>
        <span class="topbar-sub">Messages automatiques quotidiens par segment d'abonnement</span>
      </div>
      <div class="topbar-right">
        <button class="btn-ghost" onclick="toast('Historique complet — bientôt connecté','info')">
          <svg viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
          <span class="btn-txt">Historique complet</span>
        </button>
        <button class="btn-primary" onclick="testRunNow()">
          <svg viewBox="0 0 24 24" stroke-width="1.5"><polygon points="5 3 19 12 5 21 5 3"/></svg>
          <span class="btn-txt">Lancer un test</span>
        </button>
      </div>
    </header>

    <!-- Contenu -->
    <div id="content">
      <div class="view">

        <!-- ══ STATUT JOB ══ -->
        <div class="card">
          <div class="status-row">
            <span class="status-dot" id="job-status-dot"></span>
            <div style="flex:1;min-width:0;">
              <p style="font-size:13px;font-weight:500;color:white;" id="job-status-label">Relances actives</p>
              <p style="font-size:11px;color:var(--txt-4);margin-top:1px;" id="job-status-sub">Prochain envoi : aujourd'hui à 08:00 (GMT+1)</p>
            </div>
            <span class="badge bdg-z" style="font-size:10px;" title="Pour couper une catégorie, désactivez-la individuellement via son crayon d'édition">Géré par segment</span>
          </div>
        </div>

        <!-- ══ STATS CATÉGORIES ══ -->
        <div class="stat-grid stat-grid-5" id="cat-stats">
          <!-- généré par JS -->
        </div>

        <div class="main-grid">

          <!-- ── Colonne gauche : segments + config message ── -->
          <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="card">
              <div class="card-head">
                <span class="card-title">Segments & messages</span>
                <span class="badge bdg-z" id="seg-count-badge" style="font-size:9px;">—</span>
              </div>
              <div style="padding:10px;display:flex;flex-direction:column;gap:8px;" id="seg-list">
                <!-- généré par JS -->
              </div>
            </div>
          </div>

          <!-- ── Colonne droite : aperçu Telegram du segment sélectionné ── -->
          <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="card" style="padding:16px;">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                <p style="font-size:12px;font-weight:500;color:white;">Aperçu du segment sélectionné</p>
                <span class="badge bdg-z" style="font-size:9px;" id="seg-preview-hour">—</span>
              </div>
              <div class="tg">
                <div class="tg-top">
                  <div class="tg-ico"><svg width="9" height="9" fill="white" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.94 8.19l-2.02 9.52c-.14.66-.54.82-1.09.51l-3-2.21-1.45 1.39c-.16.16-.3.3-.61.3l.22-3.1 5.6-5.06c.24-.22-.06-.34-.38-.12L7.03 14.5 4.06 13.6c-.65-.2-.66-.65.14-.96l11.65-4.5c.54-.2 1.01.13.09 2.05z"/></svg></div>
                  <p style="font-size:9px;font-weight:600;color:#e2e8f0;margin-left:5px;">Felipe Bot</p>
                </div>
                <div class="tg-body"><div class="tg-bbl" id="seg-preview">Sélectionnez un segment pour voir l'aperçu...</div></div>
              </div>
              <p style="font-size:10px;color:var(--txt-4);margin-top:10px;">Cliquez sur le crayon d'un segment pour modifier son message, son heure d'envoi, et l'activer ou non.</p>
            </div>
          </div>
        </div>

        <!-- ══ HISTORIQUE ══ -->
        <div class="card col-2">
          <div class="card-head">
            <span class="card-title">Historique des relances</span>
            <span class="badge bdg-z" style="font-size:9px;" id="history-count-badge">—</span>
          </div>
          <div style="padding:6px 16px;" id="history-list">
            <!-- généré par JS -->
          </div>
        </div>

      </div>
    </div><!-- /content -->
  </div><!-- /main -->
</div><!-- /app -->

<!-- ══════════════════════════════════════════
     MODAL — édition d'un message de segment
     ══════════════════════════════════════════ -->
<div class="overlay" id="m-edit-msg">
  <div class="modal" style="width:min(540px,100%);">
    <div class="modal-head">
      <div><h2 id="edit-msg-title">Modifier le message</h2><p id="edit-msg-sub">Envoyé automatiquement chaque jour à l'heure définie</p></div>
      <button class="btn-icon" onclick="closeM('m-edit-msg')"><svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="modal-body">
      <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
          <label class="field-label" style="margin-bottom:0;">Message *</label>
          <span class="badge bdg-z" style="font-size:9px;" id="edit-msg-charcount">0 caractères</span>
        </div>
        <textarea class="inp" id="edit-msg-text" style="min-height:120px;" placeholder="Votre message de relance..." oninput="updateEditPreview()"></textarea>
        <p style="font-size:10px;color:var(--txt-4);margin-top:6px;">Variables disponibles : <code class="inp-mono" style="background:rgba(255,255,255,.06);padding:1px 4px;border-radius:4px;">+prenom</code> <code class="inp-mono" style="background:rgba(255,255,255,.06);padding:1px 4px;border-radius:4px;">+jours_restants</code></p>
      </div>
      <div>
        <label class="field-label">Heure d'envoi <span>(GMT+1)</span></label>
        <input class="inp inp-mono" type="time" id="edit-msg-hour" style="max-width:140px;">
      </div>
      <div>
        <label class="field-label">Activer pour ce segment</label>
        <div style="display:flex;align-items:center;gap:10px;">
          <button class="toggle on" id="edit-msg-active-toggle" onclick="this.classList.toggle('on')"></button>
          <span style="font-size:11px;color:var(--txt-3);">Si désactivé, ce segment ne recevra aucune relance</span>
        </div>
      </div>
      <div class="tg">
        <div class="tg-top">
          <div class="tg-ico"><svg width="9" height="9" fill="white" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.94 8.19l-2.02 9.52c-.14.66-.54.82-1.09.51l-3-2.21-1.45 1.39c-.16.16-.3.3-.61.3l.22-3.1 5.6-5.06c.24-.22-.06-.34-.38-.12L7.03 14.5 4.06 13.6c-.65-.2-.66-.65.14-.96l11.65-4.5c.54-.2 1.01.13.09 2.05z"/></svg></div>
          <p style="font-size:9px;font-weight:600;color:#e2e8f0;margin-left:5px;">Felipe Bot</p>
        </div>
        <div class="tg-body"><div class="tg-bbl" id="edit-msg-preview">Votre message...</div></div>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeM('m-edit-msg')">Annuler</button>
      <button class="btn-primary" onclick="saveSegMessage()">Enregistrer</button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════
     SCRIPT
     ══════════════════════════════════════════ -->
<script>
/* ═══════════════════════════════════════════
   SIDEBAR / UTILS (identiques à growth.html)
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

function openM(id)  { document.getElementById(id)?.classList.add('open'); }
function closeM(id) { document.getElementById(id)?.classList.remove('open'); }

document.addEventListener('click', e => { if (e.target.classList.contains('overlay')) e.target.classList.remove('open'); });
document.addEventListener('keydown', e => {
  if (e.key !== 'Escape') return;
  document.querySelectorAll('.overlay.open').forEach(m=>m.classList.remove('open'));
  if (window.innerWidth <= 768) closeSidebar();
});

function toast(msg, type='info', dur=3000) {
  const el = document.createElement('div');
  el.className = `toast ${type}`;
  el.textContent = msg;
  document.getElementById('toast-container').appendChild(el);
  setTimeout(() => { el.style.opacity='0'; el.style.transition='opacity .2s'; setTimeout(()=>el.remove(),200); }, dur);
}

function esc(s) { return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

/* ═══════════════════════════════════════════
   CONFIG API
   ═══════════════════════════════════════════ */
const API_B = 'https://fdkvip.com';

async function apiFetch(url, opts={}) {
  const res = await fetch(url, { headers:{'Content-Type':'application/json'}, ...opts });
  if (!res.ok) { const e = await res.json().catch(()=>({})); throw new Error(e.detail || `HTTP ${res.status}`); }
  return res.json();
}

/* ═══════════════════════════════════════════
   MÉTADONNÉES DE PRÉSENTATION
   — label/couleur/contexte par catégorie. Le
   backend ne connaît que name_categorie ; ce
   mapping est purement visuel, pas de la donnée
   métier. Toute catégorie absente d'ici reçoit
   un fallback générique (voir metaFor()).
   ═══════════════════════════════════════════ */
const CATEGORY_META = {
  clients_actifs:  { label: 'Clients actifs', when: 'Tous les abonnés actifs',  color: 'var(--green)' },
  clients_j7:      { label: 'J-7',            when: 'Expire dans 7 jours',      color: 'var(--sky)'   },
  clients_j3:      { label: 'J-3',            when: 'Expire dans 3 jours',      color: 'var(--amber)' },
  clients_j1:      { label: 'J-1',            when: 'Expire dans 1 jour',       color: 'var(--red)'   },
  clients_expires: { label: 'Expirés',        when: 'Abonnement terminé',       color: 'var(--txt-4)' },
};
function metaFor(name_categorie) {
  return CATEGORY_META[name_categorie] || { label: name_categorie, when: '', color: 'var(--txt-4)' };
}

/* ═══════════════════════════════════════════
   STATE
   ═══════════════════════════════════════════ */
let RELANCES = [];      // chargé depuis GET /relance
let HISTORY  = [];      // chargé depuis GET /relance/history/list
let currentEditId = null;

/* ═══════════════════════════════════════════
   CHARGEMENT DES DONNÉES
   ═══════════════════════════════════════════ */
async function loadRelances() {
  try {
    RELANCES = await apiFetch(API_B + '/relance');
  } catch (e) {
    toast('Erreur chargement relances : ' + e.message, 'error');
    RELANCES = [];
  }
}

async function loadHistory() {
  try {
    const rows = await apiFetch(API_B + '/relance/history/list?limit=50');
    // started_at vient en 'YYYY-MM-DD HH:MM:SS' -> on dérive time/date pour l'affichage
    HISTORY = rows.map(h => {
      const dt = h.started_at ? new Date(h.started_at.replace(' ', 'T')) : null;
      return {
        time:   dt ? dt.toTimeString().slice(0,5) : '—',
        date:   dt ? dt.toLocaleDateString('fr-FR', { day:'2-digit', month:'short' }) : '—',
        seg:    h.name_categorie,
        sent:   h.sent || 0,
        failed: h.errors || 0,
        status: (h.errors || 0) > 0 ? 'warn' : 'ok',
      };
    });
  } catch (e) {
    toast('Erreur chargement historique : ' + e.message, 'error');
    HISTORY = [];
  }
}

/* ═══════════════════════════════════════════
   RENDU : STATS CATÉGORIES
   ═══════════════════════════════════════════ */
function renderCatStats() {
  const el = document.getElementById('cat-stats');
  if (!RELANCES.length) {
    el.innerHTML = `<div class="stat-m" style="grid-column:1/-1;text-align:center;color:var(--txt-5);font-size:12px;padding:20px;">Aucune relance configurée.</div>`;
    document.getElementById('sb-relances-badge').textContent = '0';
    document.getElementById('seg-count-badge').textContent = '0 segment';
    return;
  }

  el.innerHTML = RELANCES.map(r => {
    const meta = metaFor(r.name_categorie);
    return `
    <div class="stat-m">
      <p class="stat-lbl" style="display:flex;align-items:center;gap:5px;">
        <span style="width:6px;height:6px;border-radius:50%;background:${meta.color};display:inline-block;"></span>
        ${meta.label}
      </p>
      <p class="stat-val">${r.member_count}</p>
      <p class="stat-sub">${meta.when}</p>
    </div>`;
  }).join('');

  const total = RELANCES.reduce((a,r)=>a+r.member_count, 0);
  document.getElementById('sb-relances-badge').textContent = total;
  document.getElementById('seg-count-badge').textContent = RELANCES.length + (RELANCES.length===1 ? ' segment' : ' segments');
}

/* ═══════════════════════════════════════════
   RENDU : SEGMENTS & MESSAGES
   ═══════════════════════════════════════════ */
function firstActiveHour(relance) {
  const sched = (relance.schedules || []).find(s => s.is_active);
  return sched ? sched.heure_envoi.slice(0,5) : null;
}

function renderSegList() {
  const el = document.getElementById('seg-list');
  if (!RELANCES.length) {
    el.innerHTML = `<div style="padding:30px 20px;text-align:center;color:var(--txt-5);font-size:12px;">Aucune relance configurée.</div>`;
    return;
  }

  el.innerHTML = RELANCES.map(r => {
    const meta = metaFor(r.name_categorie);
    const hour = firstActiveHour(r);
    const statusTxt = r.is_active
      ? `<span style="color:var(--teal);">Relance active${hour ? ' · ' + hour : ''}</span>`
      : `<span style="color:var(--txt-5);">Relance désactivée</span>`;
    return `
    <div class="seg-row">
      <span class="seg-dot" style="background:${meta.color};"></span>
      <div class="seg-info" style="cursor:pointer;" onclick="previewSeg(${r.id})">
        <p class="seg-name">${meta.label}</p>
        <p class="seg-when">${meta.when} · ${statusTxt}</p>
      </div>
      <div class="seg-count">
        <p class="n">${r.member_count}</p>
        <p class="l">membres</p>
      </div>
      <button class="seg-msg-toggle" onclick="openEditMsg(${r.id})" title="Modifier le message">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.12 2.12 0 0 1 3 3L12 15l-4 1 1-4Z"/></svg>
      </button>
    </div>`;
  }).join('');
}

/* ═══════════════════════════════════════════
   RENDU : HISTORIQUE
   ═══════════════════════════════════════════ */
function segLabel(name_categorie) { return metaFor(name_categorie).label; }
function segColor(name_categorie) { return metaFor(name_categorie).color; }

function renderHistory() {
  const el = document.getElementById('history-list');
  if (!HISTORY.length) {
    el.innerHTML = `<div style="padding:30px 20px;text-align:center;color:var(--txt-5);font-size:12px;">Aucun envoi enregistré pour l'instant.</div>`;
    document.getElementById('history-count-badge').textContent = '0 envoi';
    return;
  }

  let lastDate = null;
  el.innerHTML = HISTORY.map(h => {
    const dateHeader = h.date !== lastDate ? `<p style="font-size:10px;color:var(--txt-5);text-transform:uppercase;letter-spacing:.05em;padding:10px 0 2px;">${h.date}</p>` : '';
    lastDate = h.date;
    const statusBadge = h.status === 'ok'
      ? `<span class="badge bdg-g" style="font-size:9px;">Envoyé</span>`
      : `<span class="badge bdg-a" style="font-size:9px;">${h.failed} échec(s)</span>`;
    return `${dateHeader}
      <div class="timeline-row">
        <span class="timeline-time">${h.time}</span>
        <span class="timeline-sep"></span>
        <div class="timeline-body">
          <div class="timeline-head">
            <span style="width:6px;height:6px;border-radius:50%;background:${segColor(h.seg)};display:inline-block;"></span>
            <span class="timeline-cat">${segLabel(h.seg)}</span>
            ${statusBadge}
          </div>
          <p class="timeline-meta">${h.sent} message(s) envoyé(s)${h.failed ? `, ${h.failed} échec(s)` : ''}</p>
        </div>
      </div>`;
  }).join('');
  document.getElementById('history-count-badge').textContent = HISTORY.length + (HISTORY.length===1 ? ' envoi' : ' envois');
}

/* ═══════════════════════════════════════════
   ÉDITION MESSAGE / HEURE / ACTIF — MODAL
   ═══════════════════════════════════════════ */
function fillMsgVars(text, sample) {
  return esc(text)
    .replace(/\+prenom/g, sample.prenom)
    .replace(/\+jours_restants/g, sample.jours)
    .replace(/\n/g,'<br>');
}

function openEditMsg(relanceId) {
  const r = RELANCES.find(x=>x.id===relanceId); if (!r) return;
  const meta = metaFor(r.name_categorie);
  currentEditId = relanceId;
  document.getElementById('edit-msg-title').textContent = `Message — ${meta.label}`;
  document.getElementById('edit-msg-sub').textContent   = meta.when;
  document.getElementById('edit-msg-text').value        = r.message;
  document.getElementById('edit-msg-hour').value        = firstActiveHour(r) || '08:00';
  document.getElementById('edit-msg-active-toggle').classList.toggle('on', !!r.is_active);
  updateEditPreview();
  openM('m-edit-msg');
}

function updateEditPreview() {
  const txt = document.getElementById('edit-msg-text').value;
  document.getElementById('edit-msg-charcount').textContent = txt.length + ' caractères';
  document.getElementById('edit-msg-preview').innerHTML = fillMsgVars(txt, {prenom:'Karim', jours:'7'}) || '<span style="color:var(--txt-5);">Votre message...</span>';
}

async function saveSegMessage() {
  const r = RELANCES.find(x=>x.id===currentEditId); if (!r) return;
  const newMessage = document.getElementById('edit-msg-text').value.trim();
  const newHour    = document.getElementById('edit-msg-hour').value;
  const newActive  = document.getElementById('edit-msg-active-toggle').classList.contains('on');

  if (!newMessage) { toast('Le message ne peut pas être vide', 'error'); return; }

  try {
    await apiFetch(`${API_B}/relance/${r.id}/message`, { method:'PATCH', body: JSON.stringify({ message: newMessage }) });
    await apiFetch(`${API_B}/relance/${r.id}/active`,  { method:'PATCH', body: JSON.stringify({ is_active: newActive }) });
    await apiFetch(`${API_B}/relance/${r.id}/schedule`,{ method:'PATCH', body: JSON.stringify({ heure_envoi: newHour }) });

    await loadRelances();
    renderSegList();
    renderCatStats();
    closeM('m-edit-msg');
    toast(`Relance "${metaFor(r.name_categorie).label}" enregistrée ✓`, 'success');
  } catch (e) {
    toast('Erreur enregistrement : ' + e.message, 'error');
  }
}

/* ═══════════════════════════════════════════
   APERÇU SEGMENT (clic sur une ligne, hors modal)
   ═══════════════════════════════════════════ */
function previewSeg(relanceId) {
  const r = RELANCES.find(x=>x.id===relanceId); if (!r) return;
  const hour = firstActiveHour(r);
  document.getElementById('seg-preview').innerHTML = fillMsgVars(r.message, {prenom:'Karim', jours:'7'});
  document.getElementById('seg-preview-hour').textContent = hour ? `${hour} GMT+1` : 'Pas de créneau';
}

/* ═══════════════════════════════════════════
   STATUT JOB (indicatif — pas de route on/off
   globale côté serveur pour l'instant ; le
   scheduler tourne tant que l'app FastAPI tourne)
   ═══════════════════════════════════════════ */
function nextUpcomingHour() {
  const hours = RELANCES
    .filter(r => r.is_active)
    .map(r => firstActiveHour(r))
    .filter(Boolean)
    .sort();
  return hours.length ? hours[0] : null;
}

function renderJobStatus() {
  const dot = document.getElementById('job-status-dot');
  const lbl = document.getElementById('job-status-label');
  const sub = document.getElementById('job-status-sub');
  const activeCount = RELANCES.filter(r => r.is_active).length;

  dot.classList.toggle('off', activeCount === 0);
  lbl.textContent = activeCount > 0 ? 'Relances actives' : 'Aucune relance active';

  if (!activeCount) {
    sub.textContent = 'Aucune relance active pour le moment';
    return;
  }
  const next = nextUpcomingHour();
  sub.textContent = next
    ? `${activeCount} segment(s) actif(s) · prochain créneau à ${next} (GMT+1)`
    : `${activeCount} segment(s) actif(s)`;
}

function testRunNow() {
  toast("Le déclenchement manuel n'est pas encore disponible — le scheduler s'exécute automatiquement chaque minute côté serveur", 'info');
}

/* ═══════════════════════════════════════════
   INIT
   ═══════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', async () => {
  await loadRelances();
  renderCatStats();
  renderSegList();
  renderJobStatus();
  if (RELANCES.length) previewSeg(RELANCES[0].id);

  await loadHistory();
  renderHistory();
});
</script>
</body>
</html>