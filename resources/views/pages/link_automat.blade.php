<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Growth Hub — TradingBot Admin</title>
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

/* ── Sous-nav croissance (dans sidebar) ── */
.sb-sub { padding: 2px 0 6px 22px; display: flex; flex-direction: column; gap: 1px; }
.sb-sub-link {
  display: flex; align-items: center; gap: 7px;
  padding: 5px 8px; border-radius: var(--radius);
  font-size: 12px; color: var(--txt-5);
  transition: all .15s; background: none; border: none;
  width: 100%; cursor: pointer;
}
.sb-sub-link:hover  { color: var(--txt-2); background: var(--hover); }
.sb-sub-link.active { color: var(--txt); background: rgba(255,255,255,.06); }

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
   PBAR + FUNNEL BAR
   ═══════════════════════════════════════════ */
.pbar      { height: 3px; background: rgba(255,255,255,.06); border-radius: 99px; overflow: hidden; }
.pbar-f    { height: 100%; border-radius: 99px; transition: width .4s var(--ease); }
.funnel-bar { height: 4px; background: rgba(255,255,255,.06); border-radius: 99px; overflow: hidden; }

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
   LINK CARD
   ═══════════════════════════════════════════ */
.link-card {
  padding: 12px 14px; border-radius: var(--radius-lg);
  background: var(--bg-2); border: 1px solid var(--border-2);
  cursor: pointer; transition: all .15s;
}
.link-card:hover    { border-color: rgba(255,255,255,.12); background: rgba(255,255,255,.03); }
.link-card.featured { border-color: rgba(56,189,248,.2); }

/* ═══════════════════════════════════════════
   JOB CARD
   ═══════════════════════════════════════════ */
.job-card {
  padding: 12px 14px; border-radius: var(--radius-lg);
  background: var(--bg-2); border: 1px solid var(--border-2);
  cursor: pointer; transition: all .15s;
}
.job-card:hover { border-color: rgba(255,255,255,.12); }

/* Trigger pills (jobs) */
.trigger-pill {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 3px 8px; border-radius: 99px; font-size: 10px; font-weight: 500;
}
.trigger-pill.time     { background: rgba(56,189,248,.1);  color: var(--sky);    border: 1px solid rgba(56,189,248,.2); }
.trigger-pill.cond     { background: var(--violet-bg);     color: var(--violet); border: 1px solid rgba(167,139,250,.2); }
.trigger-pill.event    { background: var(--green-bg);      color: var(--green);  border: 1px solid rgba(52,211,153,.2); }
.trigger-pill.inactive { background: rgba(255,255,255,.04); color: var(--txt-4); border: 1px solid var(--border); cursor: pointer; }
.trigger-pill.inactive:hover { border-color: rgba(255,255,255,.15); color: var(--txt-2); }

/* ═══════════════════════════════════════════
   PROMO CARD
   ═══════════════════════════════════════════ */
.promo-card {
  padding: 12px 14px; border-radius: var(--radius-lg);
  background: var(--bg-2); border: 1px solid var(--border-2);
  transition: all .15s;
}
.promo-card.active-p { border-color: rgba(52,211,153,.2); background: rgba(52,211,153,.02); }
.del-row-btn {
  display: inline-flex; align-items: center; justify-content: center;
  width: 22px; height: 22px; background: var(--red-bg); border: 1px solid rgba(248,113,113,.2);
  border-radius: var(--radius); color: var(--red); cursor: pointer; transition: all .15s; flex-shrink: 0;
}
.del-row-btn:hover { background: rgba(248,113,113,.25); }

/* ═══════════════════════════════════════════
   MEMBER ROW
   ═══════════════════════════════════════════ */
.mrow {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 16px; border-bottom: 1px solid var(--border-3);
  transition: background .12s;
}
.mrow:hover { background: var(--hover); }
.av {
  width: 28px; height: 28px; border-radius: 50%;
  background: rgba(56,189,248,.12); color: var(--sky);
  display: flex; align-items: center; justify-content: center;
  font-size: 9px; font-weight: 600; flex-shrink: 0;
}

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
   DRAWER
   ═══════════════════════════════════════════ */
.dov {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.55); z-index: 250;
  backdrop-filter: blur(2px);
}
.dov.open { display: block; }
.drawer {
  position: fixed; top: 0; right: 0; height: 100%;
  background: var(--bg-3); border-left: 1px solid var(--border);
  z-index: 251; display: flex; flex-direction: column;
  transform: translateX(100%); transition: transform .25s var(--ease);
  width: min(440px, 100vw);
}
.drawer.open { transform: translateX(0); }
.drawer-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 20px; border-bottom: 1px solid var(--border); flex-shrink: 0;
}
.drawer-body { flex: 1; overflow-y: auto; padding: 18px 20px; display: flex; flex-direction: column; gap: 14px; min-height: 0; }
.drawer-foot { display: flex; gap: 8px; padding: 12px 20px; border-top: 1px solid var(--border); flex-shrink: 0; }
.slbl        { font-size: 10px; color: var(--txt-4); margin-bottom: 6px; text-transform: uppercase; letter-spacing: .04em; }

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
.skeleton { background: rgba(255,255,255,.05); border-radius: var(--radius); }
.pulse { animation: fadein 1s ease infinite alternate; }

/* ═══════════════════════════════════════════
   CONTENU VUES
   ═══════════════════════════════════════════ */
#content {
  flex: 1; min-height: 0; overflow-y: auto; padding: 16px;
}
.view { display: flex; flex-direction: column; gap: 14px; }

/* Lien URL préview */
.url-preview {
  padding: 8px 12px;
  background: rgba(56,189,248,.06); border: 1px solid rgba(56,189,248,.2);
  border-radius: var(--radius); font-size: 12px; font-family: 'Geist Mono', monospace;
  color: var(--sky); word-break: break-all;
}

/* Config IA trigger */
.ia-opt {
  display: flex; align-items: center; gap: 8px;
  padding: 6px 8px; border-radius: var(--radius);
  border: 1px solid transparent; transition: all .15s; cursor: pointer;
  font-size: 11px; color: var(--txt-3);
}
.ia-opt:hover { background: var(--hover); }

/* Auto promo block */
.auto-promo-block {
  padding: 10px 12px; background: rgba(255,255,255,.025);
  border-radius: var(--radius); border: 1px solid var(--border-2);
}

/* Timeline job */
.timeline-row {
  display: flex; align-items: center; gap: 12px;
  padding: 8px 0; border-bottom: 1px solid var(--border-3);
}
.timeline-time { width: 46px; text-align: center; flex-shrink: 0; font-size: 12px; color: var(--sky); font-weight: 500; }
.timeline-sep  { width: 1px; height: 26px; background: rgba(56,189,248,.25); flex-shrink: 0; }

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
@media (max-width: 480px) {
  .mrow .mrow-date, .mrow .mrow-price { display: none; }
}
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
      <button class="sb-link active" onclick="sv('links',this);closeSidebar()" id="nav-links">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
        Liens & Onboarding
      </button>
      <button class="sb-link" onclick="sv('automations',this);closeSidebar()" id="nav-automations">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
        Automations
        <span class="sb-badge" id="nav-jobs-count" style="display:none;">0</span>
      </button>
      <button class="sb-link" onclick="sv('subscriptions',this);closeSidebar()" id="nav-subscriptions">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
        Abonnements
      </button>
      <button class="sb-link" onclick="sv('promos',this);closeSidebar()" id="nav-promos">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
        Promotions
      </button>
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
        <span class="topbar-title" id="page-title">Liens & Onboarding</span>
        <span class="topbar-sep">·</span>
        <span class="topbar-sub" id="page-sub">Génération de liens trackés, séquences d'onboarding</span>
      </div>
      <div class="topbar-right">
        <button class="btn-ghost" onclick="openM('m-broadcast')">
          <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
          <span class="btn-txt">Broadcast</span>
        </button>
        <button class="btn-primary" id="main-cta" onclick="openCTA()">
          <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
          <span class="btn-txt" id="main-cta-label">Créer un lien</span>
        </button>
      </div>
    </header>

    <!-- Contenu vues -->
    <div id="content">

      <!-- ══ VUE LIENS ══ -->
      <div id="v-links" class="view">
        <div class="stat-grid">
          <div class="stat-m"><p class="stat-lbl">Liens actifs</p><p class="stat-val" id="stat-links-active">—</p><p class="stat-sub" id="stat-links-sub">—</p></div>
          <div class="stat-m"><p class="stat-lbl">Inscriptions via lien</p><p class="stat-val" id="stat-links-reg">—</p><p class="stat-sub sky-txt" id="stat-links-conv">—</p></div>
          <div class="stat-m"><p class="stat-lbl">Source #1</p><p class="stat-val" style="font-size:14px;" id="stat-top-source">—</p><p class="stat-sub pos" id="stat-top-source-n">—</p></div>
          <div class="stat-m">
            <p class="stat-lbl">Taux onboarding</p>
            <p class="stat-val sky-txt" id="stat-onboarding-rate">—</p>
            <div class="pbar" style="margin-top:8px;"><div class="pbar-f" id="onboarding-bar" style="width:0%;background:var(--sky);"></div></div>
          </div>
        </div>
        <div class="main-grid">
          <!-- Liste liens -->
          <div class="card" style="overflow:hidden;">
            <div class="card-head">
              <span class="card-title">Liens d'invitation trackés</span>
              <button class="btn-primary" style="font-size:11px;" onclick="openM('m-new-link')">
                <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                <span class="btn-txt">Nouveau lien</span><span style="display:none;" class="mobile-plus">+</span>
              </button>
            </div>
            <div id="links-list" style="padding:10px;display:flex;flex-direction:column;gap:8px;max-height:460px;overflow-y:auto;">
              <div style="padding:40px 20px;text-align:center;color:var(--txt-5);font-size:12px;">Aucun lien créé.</div>
            </div>
          </div>
          <!-- Colonne droite -->
          <div style="display:flex;flex-direction:column;gap:14px;">
            <!-- Déclencheur IA -->
            <div class="card" style="padding:16px;">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
                <p style="font-size:13px;font-weight:500;color:white;">Déclencheur IA</p>
                <span class="badge bdg-t" style="font-size:9px;">Configurable</span>
              </div>
              <p style="font-size:10px;color:var(--txt-4);margin-bottom:8px;">L'agent IA commence à discuter avec un nouveau membre :</p>
              <div style="display:flex;flex-direction:column;gap:4px;">
                <label class="ia-opt"><input type="radio" name="ia-trig" value="form" checked style="accent-color:var(--teal);"><span>Après formulaire onboarding complété</span></label>
                <label class="ia-opt"><input type="radio" name="ia-trig" value="immediate" style="accent-color:var(--teal);"><span>Immédiatement après l'inscription</span></label>
                <label class="ia-opt">
                  <input type="radio" name="ia-trig" value="messages" style="accent-color:var(--teal);">
                  <span>Après <input type="number" value="5" min="1" max="50" class="inp" style="width:40px;padding:2px 5px;font-size:10px;display:inline;"> messages</span>
                </label>
                <label class="ia-opt"><input type="radio" name="ia-trig" value="trade" style="accent-color:var(--teal);"><span>Premier trade journalisé</span></label>
              </div>
              <button class="btn-primary" style="width:100%;justify-content:center;margin-top:10px;font-size:11px;" onclick="saveIATrigger()">Sauvegarder</button>
            </div>
            <!-- Funnel -->
            <div class="card" style="padding:16px;">
              <p style="font-size:12px;font-weight:500;color:white;margin-bottom:12px;">Funnel d'acquisition</p>
              <div style="display:flex;flex-direction:column;gap:10px;" id="funnel-display">
                <div><div style="display:flex;justify-content:space-between;margin-bottom:4px;"><span style="font-size:11px;color:var(--txt-2);">Clics liens</span><span style="font-size:11px;color:var(--txt-2);font-variant-numeric:tabular-nums;" id="f1">0</span></div><div class="funnel-bar"><div class="pbar-f" id="f1b" style="width:0%;background:var(--sky);"></div></div></div>
                <div><div style="display:flex;justify-content:space-between;margin-bottom:4px;"><span style="font-size:11px;color:var(--txt-2);">Inscriptions bot</span><span style="font-size:11px;color:var(--txt-2);font-variant-numeric:tabular-nums;" id="f2">0</span></div><div class="funnel-bar"><div class="pbar-f" id="f2b" style="width:0%;background:var(--sky);"></div></div></div>
                <div><div style="display:flex;justify-content:space-between;margin-bottom:4px;"><span style="font-size:11px;color:var(--txt-2);">Formulaire complété</span><span style="font-size:11px;color:var(--txt-2);font-variant-numeric:tabular-nums;" id="f3">0</span></div><div class="funnel-bar"><div class="pbar-f" id="f3b" style="width:0%;background:var(--violet);"></div></div></div>
                <div><div style="display:flex;justify-content:space-between;margin-bottom:4px;"><span style="font-size:11px;color:var(--txt-2);">Abonnement payant</span><span style="font-size:11px;font-variant-numeric:tabular-nums;color:var(--green);" id="f4">0</span></div><div class="funnel-bar"><div class="pbar-f" id="f4b" style="width:0%;background:var(--green);"></div></div></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ VUE AUTOMATIONS ══ -->
      <div id="v-automations" class="view" style="display:none;">
        <div class="stat-grid">
          <div class="stat-m"><p class="stat-lbl">Jobs actifs</p><p class="stat-val" id="stat-jobs-active">—</p><p class="stat-sub pos" id="stat-jobs-today">—</p></div>
          <div class="stat-m"><p class="stat-lbl">Exécutions (7j)</p><p class="stat-val" id="stat-exec">—</p></div>
          <div class="stat-m"><p class="stat-lbl">Prochain job</p><p class="stat-val" style="font-size:13px;" id="stat-next-job">—</p><p class="stat-sub sky-txt" id="stat-next-time">—</p></div>
          <div class="stat-m"><p class="stat-lbl">Taux de succès</p><p class="stat-val pos" id="stat-success-rate">—</p></div>
        </div>
        <div class="main-grid">
          <div class="card" style="overflow:hidden;">
            <div class="card-head">
              <span class="card-title">Jobs configurés</span>
              <button class="btn-primary" style="font-size:11px;" onclick="openM('m-new-job')">
                <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                <span class="btn-txt">Créer une automation</span>
              </button>
            </div>
            <div id="jobs-list" style="padding:10px;display:flex;flex-direction:column;gap:8px;max-height:460px;overflow-y:auto;">
              <div style="padding:40px 20px;text-align:center;color:var(--txt-5);font-size:12px;">Aucune automation.</div>
            </div>
          </div>
          <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="card" style="padding:16px;">
              <p style="font-size:12px;font-weight:500;color:white;margin-bottom:10px;">Prochaines exécutions</p>
              <div id="schedule-timeline"><p style="font-size:12px;color:var(--txt-5);">—</p></div>
            </div>
            <div class="card" style="padding:16px;">
              <p style="font-size:12px;font-weight:500;color:white;margin-bottom:10px;">Log récent</p>
              <div id="exec-log"><p style="font-size:12px;color:var(--txt-5);">—</p></div>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ VUE ABONNEMENTS ══ -->
      <div id="v-subscriptions" class="view" style="display:none;">
        <div class="stat-grid stat-grid-5">
          <div class="stat-m"><p class="stat-lbl">MRR</p><p class="stat-val pos" id="stat-mrr">$0</p></div>
          <div class="stat-m"><p class="stat-lbl">Abonnés actifs</p><p class="stat-val" id="stat-subs-active">0</p></div>
          <div class="stat-m"><p class="stat-lbl">En essai</p><p class="stat-val sky-txt" id="stat-trials">0</p></div>
          <div class="stat-m"><p class="stat-lbl">Churn rate</p><p class="stat-val" id="stat-churn">—</p></div>
          <div class="stat-m"><p class="stat-lbl">Expirations (7j)</p><p class="stat-val" style="color:var(--amber2);" id="stat-expiring">0</p></div>
        </div>
        <div class="main-grid">
          <!-- Membres abonnés -->
          <div class="card" style="overflow:hidden;">
            <div class="card-head">
              <span class="card-title">Membres abonnés</span>
              <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <select class="inp inp-sm" id="sub-filter-plan" onchange="renderSubscribers()" style="width:120px;">
                  <option value="">Tous les plans</option>
                </select>
                <select class="inp inp-sm" id="sub-filter-status" onchange="renderSubscribers()" style="width:120px;">
                  <option value="">Tous statuts</option>
                  <option value="active">Actif</option>
                  <option value="trial">Essai</option>
                  <option value="expiring">Expire bientôt</option>
                  <option value="expired">Expiré</option>
                </select>
              </div>
            </div>
            <div id="subs-list" style="max-height:420px;overflow-y:auto;">
              <div style="padding:40px 20px;text-align:center;color:var(--txt-5);font-size:12px;">Aucun abonné.</div>
            </div>
          </div>
          <!-- Plans -->
          <div class="card" style="padding:16px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
              <p style="font-size:13px;font-weight:500;color:white;">Plans</p>
              <button class="btn-primary" style="font-size:11px;" onclick="openM('m-new-plan')">
                <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                <span class="btn-txt">Nouveau plan</span>
              </button>
            </div>
            <div id="plans-list" style="display:flex;flex-direction:column;gap:10px;">
              <div style="padding:30px 20px;text-align:center;color:var(--txt-5);font-size:12px;">Aucun plan.</div>
            </div>
          </div>
        </div>
      </div>

      <!-- ══ VUE PROMOTIONS ══ -->
      <div id="v-promos" class="view" style="display:none;">
        <div class="stat-grid">
          <div class="stat-m"><p class="stat-lbl">Codes actifs</p><p class="stat-val" id="stat-promos-active">0</p></div>
          <div class="stat-m"><p class="stat-lbl">Utilisations totales</p><p class="stat-val" id="stat-promo-uses">0</p></div>
          <div class="stat-m"><p class="stat-lbl">Revenu généré</p><p class="stat-val pos" id="stat-promo-rev">$0</p></div>
          <div class="stat-m"><p class="stat-lbl">Win-back rate</p><p class="stat-val sky-txt" id="stat-winback">—</p></div>
        </div>
        <div class="main-grid">
          <!-- Codes promo -->
          <div style="display:flex;flex-direction:column;gap:0;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;">
              <p style="font-size:13px;font-weight:500;color:white;">Codes promo</p>
              <button class="btn-primary" style="font-size:11px;" onclick="openM('m-new-promo')">
                <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
                <span class="btn-txt">Créer un code</span>
              </button>
            </div>
            <div id="promos-list" style="display:flex;flex-direction:column;gap:10px;">
              <div style="padding:40px 20px;text-align:center;color:var(--txt-5);font-size:12px;">Aucun code promo.</div>
            </div>
          </div>
          <!-- Config promos auto + tester -->
          <div style="display:flex;flex-direction:column;gap:14px;">
            <div class="card" style="padding:16px;">
              <p style="font-size:12px;font-weight:500;color:white;margin-bottom:12px;">Offres automatiques</p>
              <div style="display:flex;flex-direction:column;gap:8px;" id="auto-promos-config">
                <div class="auto-promo-block">
                  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;"><p style="font-size:12px;color:var(--txt-2);">Promo anniversaire (J+30)</p><button class="toggle" id="tog-anniversary" onclick="this.classList.toggle('on');saveAutoPromos()"></button></div>
                  <div style="display:flex;align-items:center;gap:8px;"><input type="number" value="15" id="ap-anniversary-pct" class="inp" style="width:60px;" oninput="saveAutoPromos()"><span style="font-size:10px;color:var(--txt-4);">% de réduction pendant 48h</span></div>
                </div>
                <div class="auto-promo-block">
                  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;"><p style="font-size:12px;color:var(--txt-2);">Win-back (expiré J+7)</p><button class="toggle" id="tog-winback" onclick="this.classList.toggle('on');saveAutoPromos()"></button></div>
                  <div style="display:flex;align-items:center;gap:8px;"><input type="number" value="20" id="ap-winback-pct" class="inp" style="width:60px;" oninput="saveAutoPromos()"><span style="font-size:10px;color:var(--txt-4);">% · Code unique par membre</span></div>
                </div>
                <div class="auto-promo-block">
                  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;"><p style="font-size:12px;color:var(--txt-2);">Upgrade Premium (3 mois actif)</p><button class="toggle" id="tog-upgrade" onclick="this.classList.toggle('on');saveAutoPromos()"></button></div>
                  <div style="display:flex;align-items:center;gap:8px;"><input type="number" value="30" id="ap-upgrade-pct" class="inp" style="width:60px;" oninput="saveAutoPromos()"><span style="font-size:10px;color:var(--txt-4);">% sur Premium</span></div>
                </div>
              </div>
            </div>
            <div class="card" style="padding:16px;">
              <p style="font-size:12px;font-weight:500;color:white;margin-bottom:10px;">Tester un code</p>
              <input class="inp inp-mono" type="text" id="test-promo-input" placeholder="ex: TRADING20" style="margin-bottom:8px;text-transform:uppercase;" oninput="this.value=this.value.toUpperCase()">
              <button class="btn-ghost" style="width:100%;justify-content:center;font-size:11px;" onclick="testPromoCode()">Valider le code</button>
              <div id="test-promo-result" style="margin-top:8px;font-size:11px;"></div>
            </div>
          </div>
        </div>
      </div>

    </div><!-- /content -->
  </div><!-- /main -->
</div><!-- /app -->

<!-- ══════════════════════════════════════════
     DRAWERS
     ══════════════════════════════════════════ -->
<div class="dov" id="dov" onclick="closeAllDrw()"></div>

<!-- Drawer lien -->
<div class="drawer" id="d-link">
  <div class="drawer-head">
    <div><p style="font-size:13px;font-weight:500;color:white;" id="dl-name">—</p><p style="font-size:11px;color:var(--sky);font-family:'Geist Mono',monospace;margin-top:2px;" id="dl-param">—</p></div>
    <button class="btn-icon" onclick="closeAllDrw()"><svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
  </div>
  <div class="drawer-body" id="dl-content"></div>
  <div class="drawer-foot">
    <button class="btn-ghost" style="flex:1;justify-content:center;font-size:11px;" onclick="editCurrentLink()">Modifier</button>
    <button class="btn-danger" style="flex:1;justify-content:center;font-size:11px;" onclick="deleteCurrentLink()">Supprimer</button>
  </div>
</div>

<!-- Drawer job -->
<div class="drawer" id="d-job">
  <div class="drawer-head">
    <div><p style="font-size:13px;font-weight:500;color:white;" id="dj-name">—</p><p style="font-size:11px;color:var(--txt-4);margin-top:2px;" id="dj-type">—</p></div>
    <button class="btn-icon" onclick="closeAllDrw()"><svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
  </div>
  <div class="drawer-body" id="dj-content"></div>
  <div class="drawer-foot">
    <button class="btn-ghost" style="flex:1;justify-content:center;font-size:11px;" onclick="runJobNow()">▶ Exécuter maintenant</button>
    <button class="btn-danger" style="flex:1;justify-content:center;font-size:11px;" onclick="deleteCurrentJob()">Supprimer</button>
  </div>
</div>

<!-- ══════════════════════════════════════════
     MODALS
     ══════════════════════════════════════════ -->

<!-- Modal nouveau lien -->
<div class="overlay" id="m-new-link">
  <div class="modal" style="width:min(540px,100%);">
    <div class="modal-head">
      <div><h2 id="link-modal-title">Créer un lien d'invitation</h2><p>Tracké avec UTM · Lié à une séquence et une catégorie</p></div>
      <button class="btn-icon" onclick="closeM('m-new-link')"><svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="modal-body-scroll">
      <input type="hidden" id="link-edit-id">
      <div class="form-grid-2">
        <div><label class="field-label">Nom du lien *</label><input class="inp" type="text" id="link-name" placeholder="ex: Instagram Bio"></div>
        <div><label class="field-label">Paramètre start *</label><input class="inp inp-mono" type="text" id="link-param" placeholder="ex: ref_instagram" oninput="updateLinkPreview()"></div>
      </div>
      <div>
        <label class="field-label">URL générée</label>
        <div class="url-preview" id="link-preview-url">t.me/TradingBot?start=</div>
      </div>
      <div class="form-grid-2">
        <div>
          <label class="field-label">Catégorie d'entrée auto</label>
          <select class="inp" id="link-category"><option value="">Aucune catégorie</option></select>
        </div>
        <div><label class="field-label">Code promo appliqué</label><input class="inp inp-mono" type="text" id="link-promo" placeholder="ex: TRADING20" oninput="this.value=this.value.toUpperCase()"></div>
      </div>
      <div>
        <label class="field-label">Formulaire lié (onboarding)</label>
        <select class="inp" id="link-form"><option value="">Aucun formulaire</option></select>
      </div>
      <div class="form-grid-2">
        <div><label class="field-label">Quota max <span>(optionnel)</span></label><input class="inp" type="number" id="link-quota" placeholder="illimité"></div>
        <div><label class="field-label">Expiration <span>(optionnel)</span></label><input class="inp" type="date" id="link-expires"></div>
      </div>
      <div>
        <label class="field-label">Source (UTM)</label>
        <select class="inp" id="link-source">
          <option value="instagram">Instagram</option>
          <option value="youtube">YouTube</option>
          <option value="partenaire">Partenaire</option>
          <option value="webinaire">Webinaire</option>
          <option value="tiktok">TikTok</option>
          <option value="direct">Direct / Autre</option>
        </select>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeM('m-new-link')">Annuler</button>
      <button class="btn-primary" onclick="saveLink()">Créer le lien</button>
    </div>
  </div>
</div>

<!-- Modal nouveau job -->
<div class="overlay" id="m-new-job">
  <div class="modal" style="width:min(580px,100%);">
    <div class="modal-head">
      <div><h2 id="job-modal-title">Créer une automation</h2><p>Déclencheur + cible (catégorie) + action Telegram</p></div>
      <button class="btn-icon" onclick="closeM('m-new-job')"><svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="modal-body-scroll">
      <input type="hidden" id="job-edit-id">
      <div><label class="field-label">Nom du job *</label><input class="inp" type="text" id="job-name" placeholder="ex: Relance expiration abonnement"></div>
      <div>
        <label class="field-label">Type de déclencheur</label>
        <div style="display:flex;gap:8px;margin-bottom:10px;flex-wrap:wrap;">
          <button class="trigger-pill time" id="tp-time" onclick="selectTriggerType('time')">⏰ Temporel</button>
          <button class="trigger-pill inactive" id="tp-cond" onclick="selectTriggerType('cond')">⚡ Conditionnel</button>
          <button class="trigger-pill inactive" id="tp-event" onclick="selectTriggerType('event')">🎯 Événement</button>
        </div>
        <div id="tconf-time" style="padding:12px;background:rgba(56,189,248,.04);border:1px solid rgba(56,189,248,.15);border-radius:var(--radius);">
          <div class="form-grid-2">
            <div><label class="field-label">Fréquence</label>
              <select class="inp" id="job-freq">
                <option value="daily">Chaque jour</option>
                <option value="weekly_mon">Chaque lundi</option>
                <option value="weekly_fri">Chaque vendredi</option>
                <option value="every3d">Tous les 3 jours</option>
                <option value="monthly_1">Le 1er du mois</option>
                <option value="monthly_15">Le 15 du mois</option>
                <option value="once">Une seule fois</option>
              </select>
            </div>
            <div><label class="field-label">Heure</label><input class="inp" type="time" id="job-time" value="09:00"></div>
          </div>
        </div>
        <div id="tconf-cond" style="display:none;padding:12px;background:rgba(167,139,250,.04);border:1px solid rgba(167,139,250,.15);border-radius:var(--radius);">
          <div style="display:flex;flex-direction:column;gap:8px;">
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
              <span style="font-size:10px;color:var(--violet);font-weight:600;min-width:20px;">SI</span>
              <select class="inp" id="job-cond-field" style="flex:1;font-size:11px;">
                <option value="sub_expires_in">Abonnement expire dans X jours</option>
                <option value="capital_down">Capital baisse de X%</option>
                <option value="inactive_days">Inactif depuis X jours</option>
                <option value="score_lt">Score engagement &lt; X</option>
                <option value="form_not_done">Formulaire non complété après X jours</option>
              </select>
              <input class="inp inp-mono" type="number" id="job-cond-val" placeholder="X" style="width:65px;" value="7">
            </div>
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
              <span style="font-size:10px;color:var(--violet);font-weight:600;min-width:20px;">ET</span>
              <select class="inp" id="job-cond-extra" style="flex:1;font-size:11px;">
                <option value="">Aucune condition supplémentaire</option>
                <option value="not_renewed">N'a pas encore renouvelé</option>
                <option value="is_subscribed">Est abonné actif</option>
                <option value="has_paid">A déjà payé au moins une fois</option>
              </select>
            </div>
          </div>
        </div>
        <div id="tconf-event" style="display:none;padding:12px;background:rgba(52,211,153,.04);border:1px solid rgba(52,211,153,.15);border-radius:var(--radius);">
          <select class="inp" id="job-event-type">
            <option value="new_member">Nouvel inscrit via /start</option>
            <option value="form_completed">Formulaire onboarding complété</option>
            <option value="payment_received">Paiement reçu</option>
            <option value="sub_expired">Abonnement expiré</option>
            <option value="capital_declared">Capital déclaré</option>
          </select>
        </div>
      </div>
      <div>
        <label class="field-label">Cible — Catégorie(s) de membres *</label>
        <select class="inp" id="job-target">
          <option value="all">Tous les membres</option>
          <option value="admin">Admin uniquement</option>
        </select>
      </div>
      <div>
        <label class="field-label">Action à exécuter *</label>
        <select class="inp" id="job-action-type" style="margin-bottom:8px;" onchange="toggleJobActionDetails()">
          <option value="send_message">Envoyer un message Telegram</option>
          <option value="send_form">Envoyer un formulaire</option>
          <option value="send_ia_bilan">Générer & envoyer bilan IA</option>
          <option value="add_to_category">Ajouter à une catégorie</option>
          <option value="remove_from_category">Retirer d'une catégorie</option>
          <option value="notify_admin">Notifier l'admin</option>
          <option value="webhook">Appeler un webhook</option>
        </select>
        <div id="job-action-msg">
          <textarea class="inp" id="job-action-content" style="min-height:72px;" placeholder="Contenu du message (Markdown supporté)&#10;Variables: {prenom}, {capital}, {expiration}" oninput="updateJobTgPreview()"></textarea>
          <div class="tg" style="margin-top:8px;">
            <div class="tg-top">
              <div class="tg-ico"><svg width="9" height="9" fill="white" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.94 8.19l-2.02 9.52c-.14.66-.54.82-1.09.51l-3-2.21-1.45 1.39c-.16.16-.3.3-.61.3l.22-3.1 5.6-5.06c.24-.22-.06-.34-.38-.12L7.03 14.5 4.06 13.6c-.65-.2-.66-.65.14-.96l11.65-4.5c.54-.2 1.01.13.09 2.05z"/></svg></div>
              <p style="font-size:9px;font-weight:600;color:#e2e8f0;margin-left:5px;">TradingBot</p>
            </div>
            <div class="tg-body"><div class="tg-bbl" id="job-tg-preview">Aperçu du message...</div></div>
          </div>
        </div>
        <div id="job-action-form" style="display:none;"><select class="inp" id="job-form-sel"><option value="">Choisir un formulaire...</option></select></div>
        <div id="job-action-cat" style="display:none;"><select class="inp" id="job-cat-sel"><option value="">Choisir une catégorie...</option></select></div>
        <div id="job-action-webhook" style="display:none;"><input class="inp inp-mono" type="text" id="job-webhook-url" placeholder="https://webhook.example.com/endpoint"></div>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeM('m-new-job')">Annuler</button>
      <button class="btn-primary" onclick="saveJob()">Sauvegarder l'automation</button>
    </div>
  </div>
</div>

<!-- Modal nouveau plan -->
<div class="overlay" id="m-new-plan">
  <div class="modal" style="width:min(480px,100%);">
    <div class="modal-head">
      <h2 id="plan-modal-title">Créer un plan d'abonnement</h2>
      <button class="btn-icon" onclick="closeM('m-new-plan')"><svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="plan-edit-id">
      <div class="form-grid-2">
        <div><label class="field-label">Nom du plan *</label><input class="inp" type="text" id="plan-name" placeholder="ex: Premium"></div>
        <div><label class="field-label">Prix ($) *</label><input class="inp" type="number" id="plan-price" placeholder="79"></div>
      </div>
      <div class="form-grid-2">
        <div><label class="field-label">Durée</label>
          <select class="inp" id="plan-duration">
            <option value="30">Mensuel (30j)</option>
            <option value="90">Trimestriel (90j)</option>
            <option value="365">Annuel (365j)</option>
            <option value="0">À vie</option>
          </select>
        </div>
        <div><label class="field-label">Essai gratuit (jours)</label><input class="inp" type="number" id="plan-trial" placeholder="7" value="0"></div>
      </div>
      <div><label class="field-label">Catégories d'accès liées</label><select class="inp" id="plan-categories" multiple style="min-height:80px;"><option value="">Chargement...</option></select></div>
      <div><label class="field-label">Description</label><textarea class="inp" id="plan-desc" style="min-height:52px;" placeholder="Signaux quotidiens · Chat IA · Journal de trading..."></textarea></div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeM('m-new-plan')">Annuler</button>
      <button class="btn-primary" onclick="savePlan()">Créer le plan</button>
    </div>
  </div>
</div>

<!-- Modal nouveau code promo -->
<div class="overlay" id="m-new-promo">
  <div class="modal" style="width:min(480px,100%);">
    <div class="modal-head">
      <h2 id="promo-modal-title">Créer un code promo</h2>
      <button class="btn-icon" onclick="closeM('m-new-promo')"><svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="promo-edit-id">
      <div class="form-grid-2">
        <div><label class="field-label">Code *</label><input class="inp inp-mono" type="text" id="promo-code" placeholder="ex: TRADING20" style="text-transform:uppercase;" oninput="this.value=this.value.toUpperCase()"></div>
        <div><label class="field-label">Réduction</label><div style="display:flex;gap:6px;"><input class="inp" type="number" id="promo-value" placeholder="20" style="flex:1;"><select class="inp" id="promo-type" style="width:55px;"><option value="percent">%</option><option value="fixed">$</option></select></div></div>
      </div>
      <div class="form-grid-2">
        <div><label class="field-label">Plan applicable</label><select class="inp" id="promo-plan"><option value="">Tous les plans</option></select></div>
        <div><label class="field-label">Quota max</label><input class="inp" type="number" id="promo-quota" placeholder="illimité"></div>
      </div>
      <div><label class="field-label">Expiration</label><input class="inp" type="date" id="promo-expires"></div>
      <div style="display:flex;flex-direction:column;gap:6px;">
        <label style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--txt-3);cursor:pointer;"><input type="checkbox" id="promo-first-only" checked style="accent-color:var(--sky);"> Première souscription uniquement</label>
        <label style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--txt-3);cursor:pointer;"><input type="checkbox" id="promo-non-comb" style="accent-color:var(--sky);"> Non combinable avec d'autres offres</label>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeM('m-new-promo')">Annuler</button>
      <button class="btn-primary" onclick="savePromo()">Créer le code</button>
    </div>
  </div>
</div>

<!-- Modal broadcast -->
<div class="overlay" id="m-broadcast">
  <div class="modal" style="width:min(500px,100%);">
    <div class="modal-head">
      <h2>Broadcast rapide</h2>
      <button class="btn-icon" onclick="closeM('m-broadcast')"><svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="modal-body">
      <div>
        <label class="field-label">Destinataires *</label>
        <select class="inp" id="bc-target" onchange="updateBcCount()">
          <option value="all">Tous les membres</option>
          <option value="admin">Admin uniquement (test)</option>
        </select>
        <p style="font-size:10px;color:var(--sky);margin-top:4px;" id="bc-count-label">Tous les membres recevront ce message</p>
      </div>
      <div>
        <label class="field-label">Message *</label>
        <textarea class="inp" id="bc-message" style="min-height:80px;" placeholder="Votre message Telegram..." oninput="updateBcPreview()"></textarea>
      </div>
      <div class="tg">
        <div class="tg-top">
          <div class="tg-ico"><svg width="9" height="9" fill="white" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.94 8.19l-2.02 9.52c-.14.66-.54.82-1.09.51l-3-2.21-1.45 1.39c-.16.16-.3.3-.61.3l.22-3.1 5.6-5.06c.24-.22-.06-.34-.38-.12L7.03 14.5 4.06 13.6c-.65-.2-.66-.65.14-.96l11.65-4.5c.54-.2 1.01.13.09 2.05z"/></svg></div>
          <p style="font-size:9px;font-weight:600;color:#e2e8f0;margin-left:5px;">TradingBot</p>
        </div>
        <div class="tg-body"><div class="tg-bbl" id="bc-preview">Votre message apparaîtra ici...</div></div>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeM('m-broadcast')">Annuler</button>
      <button class="btn-primary" onclick="sendBroadcast()">Envoyer →</button>
    </div>
  </div>
</div>

<!-- Modal abonner un membre -->
<div class="overlay" id="m-add-sub">
  <div class="modal" style="width:min(440px,100%);">
    <div class="modal-head">
      <h2>Ajouter un abonné (simulation)</h2>
      <button class="btn-icon" onclick="closeM('m-add-sub')"><svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="modal-body">
      <div><label class="field-label">Nom du membre</label><input class="inp" type="text" id="sub-member-name" placeholder="ex: Marc Renaud"></div>
      <div><label class="field-label">Plan *</label><select class="inp" id="sub-plan-sel"><option value="">Choisir un plan...</option></select></div>
      <div><label class="field-label">Statut</label><select class="inp" id="sub-status-sel"><option value="active">Actif</option><option value="trial">Essai</option><option value="expiring">Expire bientôt</option><option value="expired">Expiré</option></select></div>
      <div><label class="field-label">Code promo <span>(optionnel)</span></label><input class="inp inp-mono" type="text" id="sub-promo-code" placeholder="ex: TRADING20" style="text-transform:uppercase;" oninput="this.value=this.value.toUpperCase()"></div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeM('m-add-sub')">Annuler</button>
      <button class="btn-primary" onclick="addSubscriber()">Ajouter</button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════
     SCRIPT
     ══════════════════════════════════════════ -->
<script>
/* ═══════════════════════════════════════════
   SIDEBAR / UTILS
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

function openM(id)  { fillForModal(id).then(() => document.getElementById(id)?.classList.add('open')); }
function closeM(id) { document.getElementById(id)?.classList.remove('open'); }
function openDrw(id){ document.getElementById(id)?.classList.add('open'); document.getElementById('dov')?.classList.add('open'); }
function closeAllDrw() { document.querySelectorAll('.drawer').forEach(d=>d.classList.remove('open')); document.getElementById('dov')?.classList.remove('open'); }

document.addEventListener('click', e => { if (e.target.classList.contains('overlay')) e.target.classList.remove('open'); });
document.addEventListener('keydown', e => {
  if (e.key !== 'Escape') return;
  closeAllDrw();
  document.querySelectorAll('.overlay.open').forEach(m=>m.classList.remove('open'));
  if (window.innerWidth <= 768) closeSidebar();
});

function toast(msg, type='info', dur=3000) {
  const colors = {
    success: 'rgba(52,211,153,.12)',
    error:   'rgba(248,113,113,.12)',
    warn:    'rgba(251,191,36,.12)',
    info:    'rgba(56,189,248,.12)',
  };
  const el = document.createElement('div');
  el.className = `toast ${type}`;
  el.textContent = msg;
  document.getElementById('toast-container').appendChild(el);
  setTimeout(() => { el.style.opacity='0'; el.style.transition='opacity .2s'; setTimeout(()=>el.remove(),200); }, dur);
}

function esc(s) { return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }
function initials(n) { return (n||'?').trim().split(' ').map(w=>w[0]).slice(0,2).join('').toUpperCase(); }
function sourceEmoji(s) { return {instagram:'📸',youtube:'🎬',partenaire:'🤝',webinaire:'💻',tiktok:'🎵',direct:'🔗'}[s]||'🔗'; }
function formatTarget(t) {
  if (!t||t==='all') return 'Tous les membres';
  if (t==='admin') return 'Admin uniquement';
  if (t.startsWith('cat:')) return t.replace('cat:','Catégorie: ');
  return t;
}
function formatAction(a) {
  return {send_message:'Envoyer message',send_form:'Envoyer formulaire',send_ia_bilan:'Bilan IA',add_to_category:'Ajouter catégorie',remove_from_category:'Retirer catégorie',notify_admin:'Notifier admin',webhook:'Webhook'}[a]||a;
}

/* ═══════════════════════════════════════════
   CONFIG API
   ═══════════════════════════════════════════ */
const API_G = 'https://fdkvip.com/growth';
const API_B = 'https://fdkvip.com';

async function apiFetch(url, opts={}) {
  const res = await fetch(url, { headers:{'Content-Type':'application/json'}, ...opts });
  if (!res.ok) { const e = await res.json().catch(()=>({})); throw new Error(e.detail||`HTTP ${res.status}`); }
  return res.json();
}

/* ═══════════════════════════════════════════
   STATE
   ═══════════════════════════════════════════ */
const S = {
  categories:[], links:[], jobs:[], plans:[],
  subscribers:[], promos:[], execLogs:[],
  currentLinkId:null, currentJobId:null,
};

/* ═══════════════════════════════════════════
   NAVIGATION
   ═══════════════════════════════════════════ */
const VIEWS_CFG = {
  links:         { title:'Liens & Onboarding',    sub:"Génération de liens trackés, séquences d'onboarding, activation IA",       cta:'Créer un lien',        ctaFn:()=>openM('m-new-link') },
  automations:   { title:'Automations',           sub:'Jobs planifiés et conditionnels — messages et actions bot automatiques',     cta:'Créer une automation', ctaFn:()=>openM('m-new-job')  },
  subscriptions: { title:'Abonnements',           sub:'Plans, cycle de vie, renouvellements, MRR',                                 cta:'+ Abonner un membre',  ctaFn:()=>openAddSub()         },
  promos:        { title:'Promotions & Codes',    sub:'Codes promo, offres flash, win-back automatique',                           cta:'Créer un code',        ctaFn:()=>openM('m-new-promo') },
};

async function sv(view, el) {
  Object.keys(VIEWS_CFG).forEach(v => {
    const e = document.getElementById('v-'+v);
    if (e) e.style.display = 'none';
  });
  const t = document.getElementById('v-'+view);
  if (t) t.style.display = 'flex';

  document.querySelectorAll('.sb-link').forEach(n=>n.classList.remove('active'));
  if (el) el.classList.add('active');

  const cfg = VIEWS_CFG[view];
  if (cfg) {
    document.getElementById('page-title').textContent = cfg.title;
    document.getElementById('page-sub').textContent   = cfg.sub;
    const lbl = document.getElementById('main-cta-label');
    if (lbl) lbl.textContent = cfg.cta;
  }

  if (view==='links')         { await fillCategorySelects(); await renderLinks(); await renderFunnel(); await renderLinkStats(); await loadIATrigger(); }
  if (view==='automations')   { await fillJobTargets(); await renderJobs(); renderTimeline(); renderExecLog(); }
  if (view==='subscriptions') { await fillPlanSelects(); await renderPlans(); await renderSubscribers(); await renderSubStats(); }
  if (view==='promos')        { await fillPromoSelects(); await renderPromos(); renderPromoStats(); await loadAutoPromos(); }
}

function openCTA() {
  const v = Object.keys(VIEWS_CFG).find(v => document.getElementById('v-'+v)?.style.display!=='none');
  if (VIEWS_CFG[v]?.ctaFn) VIEWS_CFG[v].ctaFn();
}

/* ═══════════════════════════════════════════
   FILL SELECTS
   ═══════════════════════════════════════════ */
async function getCatsFromAPI() {
  try {
    const data = await apiFetch(API_B+'/categorie');
    return (data||[]).map(c=>({name:c.name_categorie, color:c.color||'#38bdf8', count:c.member_count||0}));
  } catch { return S.categories; }
}

async function fillCategorySelects() {
  const cats = await getCatsFromAPI();
  S.categories = cats;
  const lc = document.getElementById('link-category');
  if (lc) lc.innerHTML = '<option value="">Aucune catégorie</option>' + cats.map(c=>`<option value="${c.name}">${c.name} (${c.count})</option>`).join('');
  const pc = document.getElementById('plan-categories');
  if (pc) pc.innerHTML = cats.map(c=>`<option value="${c.name}">${c.name}</option>`).join('');
}

async function fillPlanSelects() {
  const plans = await apiFetch(API_G+'/plans').catch(()=>S.plans||[]);
  S.plans = plans;
  ['sub-filter-plan'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.innerHTML = '<option value="">Tous les plans</option>' + plans.map(p=>`<option value="${p.id}">${p.name}</option>`).join('');
  });
  ['promo-plan'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.innerHTML = '<option value="">Tous les plans</option>' + plans.map(p=>`<option value="${p.id}">${p.name}</option>`).join('');
  });
  const sps = document.getElementById('sub-plan-sel');
  if (sps) sps.innerHTML = '<option value="">Choisir un plan...</option>' + plans.map(p=>`<option value="${p.id}">${p.name} — $${p.price_usd}/mois</option>`).join('');
}

async function fillPromoSelects() { await fillPlanSelects(); }

async function fillJobTargets() {
  const cats = await getCatsFromAPI();
  const sel  = document.getElementById('job-target'); if (!sel) return;
  sel.innerHTML = '<option value="all">Tous les membres</option><option value="admin">Admin uniquement</option>'
    + cats.map(c=>`<option value="cat:${c.name}">${c.name} (${c.count})</option>`).join('');
  const jcs = document.getElementById('job-cat-sel');
  if (jcs) jcs.innerHTML = '<option value="">Choisir...</option>' + cats.map(c=>`<option value="${c.name}">${c.name}</option>`).join('');
}

async function fillFormSelect() {
  const forms = await apiFetch(API_B+'/forms').catch(()=>[]);
  const sel = document.getElementById('link-form'); if (!sel) return;
  sel.innerHTML = '<option value="">Aucun formulaire</option>' + forms.map(f=>`<option value="${f.id}">${esc(f.name)}</option>`).join('');
  const jfs = document.getElementById('job-form-sel');
  if (jfs) jfs.innerHTML = '<option value="">Choisir un formulaire...</option>' + forms.map(f=>`<option value="${f.id}">${esc(f.name)}</option>`).join('');
}

async function fillForModal(id) {
  if (id==='m-new-link')     { await fillCategorySelects(); await fillFormSelect(); }
  if (id==='m-new-job')      { await fillJobTargets(); await fillCategorySelects(); }
  if (id==='m-new-plan')     await fillCategorySelects();
  if (id==='m-new-promo')    await fillPlanSelects();
  if (id==='m-add-sub')      await fillPlanSelects();
  if (id==='m-broadcast')    { await populateBcTarget(); updateBcPreview(); updateBcCount(); }
  return Promise.resolve();
}

async function populateBcTarget() {
  const cats = await getCatsFromAPI();
  const sel  = document.getElementById('bc-target'); if (!sel) return;
  const existing = [...sel.options].map(o=>o.value);
  cats.forEach(c => {
    if (!existing.includes(c.name)) {
      const o = document.createElement('option'); o.value=c.name; o.textContent=`${c.name} (${c.count})`; sel.appendChild(o);
    }
  });
}

/* ═══════════════════════════════════════════
   LIENS
   ═══════════════════════════════════════════ */
function updateLinkPreview() {
  const p = document.getElementById('link-param').value.trim().replace(/\s+/g,'_').toLowerCase();
  document.getElementById('link-preview-url').textContent = `t.me/TradingBot?start=${p||'...'}`;
}

async function renderLinks() {
  try { S.links = await apiFetch(API_G+'/links'); } catch(e) { toast('Erreur chargement liens: '+e.message,'error'); }
  const el = document.getElementById('links-list'); if (!el) return;
  if (!S.links.length) { el.innerHTML='<div style="padding:40px 20px;text-align:center;color:var(--txt-5);font-size:12px;">Aucun lien créé.</div>'; return; }
  el.innerHTML = S.links.map(l => {
    const cat     = S.categories.find(c=>c.name===l.auto_category);
    const clicks  = l.clicks||0, regs=l.registrations||0, subs=l.subscribers||0;
    const conv    = clicks>0 ? Math.round(regs/clicks*100) : 0;
    const expired = l.expires_at && new Date(l.expires_at) < new Date();
    return `<div class="link-card fadein${l.is_active&&!expired?' featured':''}" onclick="openLinkDrawer(${l.id})">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;">
        <div style="display:flex;align-items:center;gap:8px;">
          <span style="font-size:18px;">${sourceEmoji(l.source)}</span>
          <div><p style="font-size:12px;font-weight:500;color:white;">${esc(l.name)}</p><p style="font-size:10px;font-family:'Geist Mono',monospace;color:var(--sky);margin-top:1px;">?start=${esc(l.start_param)}</p></div>
        </div>
        <div style="display:flex;align-items:center;gap:6px;">
          ${expired?'<span class="badge bdg-z" style="font-size:9px;">Expiré</span>':l.is_active?'<span class="badge bdg-g" style="font-size:9px;">Actif</span>':'<span class="badge bdg-z" style="font-size:9px;">Inactif</span>'}
          ${l.quota_max?`<span class="badge bdg-a" style="font-size:9px;">${l.quota_used}/${l.quota_max}</span>`:''}
          <button class="btn-icon" style="width:22px;height:22px;" onclick="event.stopPropagation();copyLink('${l.start_param}')">
            <svg viewBox="0 0 24 24" stroke-width="1.5"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg>
          </button>
        </div>
      </div>
      <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:6px;margin-bottom:8px;">
        <div style="text-align:center;"><p style="font-size:13px;font-weight:300;color:white;font-variant-numeric:tabular-nums;">${clicks}</p><p style="font-size:9px;color:var(--txt-4);">Clics</p></div>
        <div style="text-align:center;"><p style="font-size:13px;font-weight:300;color:white;font-variant-numeric:tabular-nums;">${regs}</p><p style="font-size:9px;color:var(--txt-4);">Inscrits</p></div>
        <div style="text-align:center;"><p style="font-size:13px;font-weight:300;color:var(--green);font-variant-numeric:tabular-nums;">${conv}%</p><p style="font-size:9px;color:var(--txt-4);">Conv.</p></div>
        <div style="text-align:center;"><p style="font-size:13px;font-weight:300;color:var(--violet);font-variant-numeric:tabular-nums;">${l.forms_done||0}</p><p style="font-size:9px;color:var(--txt-4);">Forms</p></div>
        <div style="text-align:center;"><p style="font-size:13px;font-weight:300;color:var(--sky);font-variant-numeric:tabular-nums;">${subs}</p><p style="font-size:9px;color:var(--txt-4);">Payants</p></div>
      </div>
      <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
        ${cat?`<span style="font-size:10px;color:var(--txt-4);">Cat:</span><span class="badge" style="font-size:9px;background:${cat.color}18;color:${cat.color};">${cat.name}</span>`:''}
        ${l.promo_code?`<span class="badge bdg-v" style="font-size:9px;">${l.promo_code}</span>`:''}
        ${l.form_name?`<span class="badge bdg-t" style="font-size:9px;">📋 ${esc(l.form_name)}</span>`:''}
        ${l.expires_at?`<span style="font-size:10px;color:${expired?'var(--red)':'var(--txt-4)'};">Expire: ${l.expires_at.slice(0,10)}</span>`:''}
      </div>
    </div>`;
  }).join('');
}

function openLinkDrawer(id) {
  const l = S.links.find(x=>x.id===id); if (!l) return;
  S.currentLinkId = id;
  document.getElementById('dl-name').textContent  = l.name;
  document.getElementById('dl-param').textContent = `?start=${l.start_param}`;
  const cat   = S.categories.find(c=>c.name===l.auto_category);
  const clicks = l.clicks||0, regs=l.registrations||0, subs=l.subscribers||0;
  const conv  = clicks>0 ? Math.round(regs/clicks*100) : 0;
  document.getElementById('dl-content').innerHTML = `
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
      <div class="stat-m" style="text-align:center;"><p class="stat-val">${clicks}</p><p class="stat-lbl" style="margin-top:4px;">Clics</p></div>
      <div class="stat-m" style="text-align:center;"><p class="stat-val pos">${conv}%</p><p class="stat-lbl" style="margin-top:4px;">Conv.</p></div>
      <div class="stat-m" style="text-align:center;"><p class="stat-val">${regs}</p><p class="stat-lbl" style="margin-top:4px;">Inscrits</p></div>
      <div class="stat-m" style="text-align:center;"><p class="stat-val sky-txt">${subs}</p><p class="stat-lbl" style="margin-top:4px;">Payants</p></div>
    </div>
    <div><p class="slbl">Lien</p><div class="url-preview">https://t.me/TradingBot?start=${l.start_param}</div>
      <button class="btn-ghost" style="width:100%;justify-content:center;margin-top:8px;font-size:11px;" onclick="copyLink('${l.start_param}')">Copier le lien</button></div>
    <div><p class="slbl">Config</p>
      <div style="display:flex;flex-direction:column;gap:0;">
        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-3);font-size:11px;"><span style="color:var(--txt-4);">Source</span><span>${sourceEmoji(l.source)} ${l.source}</span></div>
        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-3);font-size:11px;"><span style="color:var(--txt-4);">Catégorie</span><span>${cat?`<span style="color:${cat.color};">${cat.name}</span>`:'—'}</span></div>
        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-3);font-size:11px;"><span style="color:var(--txt-4);">Promo</span><span style="font-family:'Geist Mono',monospace;">${l.promo_code||'—'}</span></div>
        <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--border-3);font-size:11px;"><span style="color:var(--txt-4);">Formulaire</span><span>${esc(l.form_name||'—')}</span></div>
        <div style="display:flex;justify-content:space-between;padding:6px 0;font-size:11px;"><span style="color:var(--txt-4);">Quota</span><span>${l.quota_max?`${l.quota_used}/${l.quota_max}`:'Illimité'}</span></div>
      </div>
    </div>`;
  openDrw('d-link');
}

async function saveLink() {
  const name  = document.getElementById('link-name').value.trim();
  const param = document.getElementById('link-param').value.trim().replace(/\s+/g,'_').toLowerCase();
  const cat   = document.getElementById('link-category').value;
  const promo = document.getElementById('link-promo').value.trim();
  const quota = parseInt(document.getElementById('link-quota').value)||null;
  const exp   = document.getElementById('link-expires').value||null;
  const source= document.getElementById('link-source').value;
  const editId= document.getElementById('link-edit-id').value;
  const formId= parseInt(document.getElementById('link-form').value)||null;
  if (!name||!param) { toast('Nom et paramètre requis','error'); return; }
  const payload = { name, start_param:param, auto_category:cat||null, promo_code:promo||null, form_id:formId, quota_max:quota, expires_at:exp, source };
  try {
    if (editId) { await apiFetch(API_G+'/links/'+editId,{method:'PATCH',body:JSON.stringify(payload)}); toast('Lien mis à jour ✓','success'); }
    else        { await apiFetch(API_G+'/links',{method:'POST',body:JSON.stringify(payload)}); toast('Lien créé ✓','success'); }
    closeM('m-new-link'); resetLinkForm(); await renderLinks(); await renderFunnel(); await renderLinkStats();
  } catch(e) { toast('Erreur: '+e.message,'error'); }
}

function resetLinkForm() {
  ['link-name','link-param','link-promo','link-quota','link-expires'].forEach(id=>{const e=document.getElementById(id);if(e)e.value='';});
  document.getElementById('link-edit-id').value='';
  document.getElementById('link-modal-title').textContent="Créer un lien d'invitation";
  document.getElementById('link-preview-url').textContent='t.me/TradingBot?start=';
  document.getElementById('link-form').value='';
}

async function editCurrentLink() {
  const l = S.links.find(x=>x.id===S.currentLinkId); if (!l) return;
  closeAllDrw();
  setTimeout(async () => {
    await fillCategorySelects();
    document.getElementById('link-edit-id').value  = l.id;
    document.getElementById('link-name').value     = l.name;
    document.getElementById('link-param').value    = l.start_param;
    document.getElementById('link-category').value = l.auto_category||'';
    document.getElementById('link-promo').value    = l.promo_code||'';
    document.getElementById('link-form').value     = l.form_id||'';
    document.getElementById('link-quota').value    = l.quota_max||'';
    document.getElementById('link-expires').value  = l.expires_at?l.expires_at.slice(0,10):'';
    document.getElementById('link-source').value   = l.source||'direct';
    document.getElementById('link-modal-title').textContent='Modifier le lien';
    updateLinkPreview(); openM('m-new-link');
  }, 200);
}

async function deleteCurrentLink() {
  if (!confirm('Supprimer ce lien ?')) return;
  try {
    await apiFetch(API_G+'/links/'+S.currentLinkId,{method:'DELETE'});
    closeAllDrw(); await renderLinks(); await renderFunnel(); await renderLinkStats();
    toast('Lien supprimé','success');
  } catch(e) { toast('Erreur: '+e.message,'error'); }
}

function copyLink(param) {
  navigator.clipboard.writeText(`https://t.me/TradingBot?start=${param}`)
    .then(()=>toast('Lien copié ✓','success')).catch(()=>toast('Copie impossible','error'));
}

async function renderLinkStats() {
  try {
    const src = await apiFetch(API_G+'/analytics/sources');
    const active = S.links.filter(l=>l.is_active).length;
    const totalClicks = S.links.reduce((a,l)=>a+(l.clicks||0),0);
    const totalReg    = S.links.reduce((a,l)=>a+(l.registrations||0),0);
    const conv = totalClicks>0 ? Math.round(totalReg/totalClicks*100) : 0;
    const top  = src[0];
    document.getElementById('stat-links-active').textContent = active;
    document.getElementById('stat-links-sub').textContent    = S.links.length+' créés';
    document.getElementById('stat-links-reg').textContent    = totalReg;
    document.getElementById('stat-links-conv').textContent   = conv+'% conv.';
    document.getElementById('stat-top-source').textContent   = top?top.name:'—';
    document.getElementById('stat-top-source-n').textContent = top?top.registrations+' membres':'—';
  } catch(e) { console.warn('[linkStats]',e); }
}

async function renderFunnel() {
  try {
    const d = await apiFetch(API_G+'/analytics/funnel');
    const mx = Math.max(d.clicks,1);
    document.getElementById('f1').textContent=d.clicks;
    document.getElementById('f2').textContent=d.registered+' ('+(d.clicks>0?Math.round(d.registered/d.clicks*100):0)+'%)';
    document.getElementById('f3').textContent=d.forms_done;
    document.getElementById('f4').textContent=d.paying;
    document.getElementById('f1b').style.width='100%';
    document.getElementById('f2b').style.width=(d.clicks>0?d.registered/mx*100:0)+'%';
    document.getElementById('f3b').style.width=(d.clicks>0?d.forms_done/mx*100:0)+'%';
    document.getElementById('f4b').style.width=(d.clicks>0?d.paying/mx*100:0)+'%';
  } catch(e) { console.warn('[funnel]',e); }
}

async function saveIATrigger() {
  const trigType = document.querySelector('input[name="ia-trig"]:checked')?.value||'form';
  const numInput = document.querySelector('input[name="ia-trig"][value="messages"]')?.closest('label')?.querySelector('input[type="number"]');
  const msgCount = parseInt(numInput?.value)||5;
  try {
    await apiFetch(API_G+'/ia-trigger',{method:'PATCH',body:JSON.stringify({trigger_type:trigType,messages_count:msgCount})});
    toast('Déclencheur IA sauvegardé ✓','success');
  } catch(e) { toast('Erreur: '+e.message,'error'); }
}

async function loadIATrigger() {
  try {
    const d = await apiFetch(API_G+'/ia-trigger');
    const radio = document.querySelector(`input[name="ia-trig"][value="${d.trigger_type}"]`);
    if (radio) radio.checked=true;
    if (d.messages_count) {
      const numInput = document.querySelector('input[name="ia-trig"][value="messages"]')?.closest('label')?.querySelector('input[type="number"]');
      if (numInput) numInput.value=d.messages_count;
    }
  } catch(e) { console.warn('[ia-trigger]',e); }
}

/* ═══════════════════════════════════════════
   JOBS / AUTOMATIONS
   ═══════════════════════════════════════════ */
function selectTriggerType(type) {
  ['time','cond','event'].forEach(t => {
    const btn  = document.getElementById('tp-'+t);
    const conf = document.getElementById('tconf-'+t);
    if (!btn||!conf) return;
    const cls = {time:'trigger-pill time',cond:'trigger-pill cond',event:'trigger-pill event'};
    btn.className  = t===type ? cls[t] : 'trigger-pill inactive';
    conf.style.display = t===type ? 'block' : 'none';
  });
}

function toggleJobActionDetails() {
  const type = document.getElementById('job-action-type').value;
  document.getElementById('job-action-msg').style.display     = ['send_message','send_ia_bilan','notify_admin'].includes(type)?'block':'none';
  document.getElementById('job-action-form').style.display    = type==='send_form'?'block':'none';
  document.getElementById('job-action-cat').style.display     = ['add_to_category','remove_from_category'].includes(type)?'block':'none';
  document.getElementById('job-action-webhook').style.display = type==='webhook'?'block':'none';
  updateJobTgPreview();
}

function updateJobTgPreview() {
  const txt = document.getElementById('job-action-content')?.value||'';
  const el  = document.getElementById('job-tg-preview');
  if (el) el.innerHTML = esc(txt).replace(/\n/g,'<br>')||'<span style="color:var(--txt-5);">Aperçu...</span>';
}

async function renderJobs() {
  try { S.jobs = await apiFetch(API_G+'/jobs'); } catch(e) { toast('Erreur chargement jobs: '+e.message,'error'); }
  const el = document.getElementById('jobs-list'); if (!el) return;
  if (!S.jobs.length) { el.innerHTML='<div style="padding:40px 20px;text-align:center;color:var(--txt-5);font-size:12px;">Aucune automation.</div>'; }
  else el.innerHTML = S.jobs.map(j => {
    const tc = j.trig_type==='time'?'time':j.trig_type==='cond'?'cond':'event';
    const tLabel = j.trig_type==='time'?`⏰ ${j.freq} ${j.run_time}`:j.trig_type==='cond'?`⚡ SI ${j.cond_field} ${j.cond_value}`:`🎯 ${j.event_type}`;
    return `<div class="job-card fadein" onclick="openJobDrawer(${j.id})">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;flex-wrap:wrap;gap:6px;">
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;">
          <span class="trigger-pill ${tc}">${tLabel}</span>
          <p style="font-size:12px;font-weight:500;color:white;">${esc(j.name)}</p>
        </div>
        <button class="toggle ${j.is_active?'on':''}" onclick="event.stopPropagation();toggleJob(${j.id})"></button>
      </div>
      <div style="display:flex;flex-direction:column;gap:2px;font-size:10px;color:var(--txt-3);">
        <p>→ Cible : ${esc(formatTarget(j.target))}</p>
        <p>→ Action : ${esc(formatAction(j.action_type))}</p>
        ${j.action_content?`<p>→ "${esc(j.action_content.substring(0,60))}${j.action_content.length>60?'...':''}"</p>`:''}
      </div>
      <div style="display:flex;align-items:center;gap:8px;margin-top:8px;flex-wrap:wrap;">
        <span class="badge ${j.is_active?'bdg-g':'bdg-z'}" style="font-size:9px;">${j.is_active?'Actif':'Inactif'}</span>
        <span style="font-size:10px;color:var(--txt-4);">Prochain: ${esc(j.next_run_at?j.next_run_at.slice(0,16).replace('T',' '):'—')}</span>
        ${j.exec_count>0?`<span style="font-size:10px;color:var(--txt-4);">${j.exec_count} exec.</span>`:''}
      </div>
    </div>`;
  }).join('');

  // Stats
  const active = (S.jobs||[]).filter(j=>j.is_active).length;
  const execs  = (S.jobs||[]).reduce((a,j)=>a+(j.exec_count||0),0);
  const errs   = (S.jobs||[]).reduce((a,j)=>a+(j.err_count||0),0);
  const rate   = execs>0 ? Math.round((execs-errs)/execs*100) : null;
  const next   = (S.jobs||[]).filter(j=>j.is_active&&j.next_run_at).sort((a,b)=>a.next_run_at>b.next_run_at?1:-1)[0];
  document.getElementById('stat-jobs-active').textContent  = active;
  document.getElementById('stat-jobs-today').textContent   = (S.jobs||[]).filter(j=>j.is_active&&j.trig_type==='time').length+' planifiés';
  document.getElementById('stat-exec').textContent         = execs;
  document.getElementById('stat-success-rate').textContent = rate!=null?rate+'%':'—';
  document.getElementById('stat-next-job').textContent     = next?next.name:'—';
  document.getElementById('stat-next-time').textContent    = next?(next.next_run_at||'').slice(0,16).replace('T',' '):'—';
  const badge = document.getElementById('nav-jobs-count');
  if (badge) { badge.textContent=active; badge.style.display=active>0?'':'none'; }
  renderTimeline();
}

function openJobDrawer(id) {
  const j = S.jobs.find(x=>x.id===id); if (!j) return;
  S.currentJobId = id;
  document.getElementById('dj-name').textContent = j.name;
  document.getElementById('dj-type').textContent = `Automation · ${j.trig_type==='time'?'Temporel':j.trig_type==='cond'?'Conditionnel':'Événement'}`;
  document.getElementById('dj-content').innerHTML = `
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
      <div class="stat-m" style="text-align:center;"><p class="stat-val" style="font-size:16px;">${j.exec_count||0}</p><p class="stat-lbl" style="margin-top:4px;">Exécutions</p></div>
      <div class="stat-m" style="text-align:center;"><p class="stat-val pos" style="font-size:16px;">${j.exec_count>0?Math.round((j.exec_count-j.err_count)/j.exec_count*100):0}%</p><p class="stat-lbl" style="margin-top:4px;">Succès</p></div>
      <div class="stat-m" style="text-align:center;"><p class="stat-val neg" style="font-size:16px;">${j.err_count||0}</p><p class="stat-lbl" style="margin-top:4px;">Erreurs</p></div>
    </div>
    <div><p class="slbl">Cible</p><p style="font-size:12px;color:var(--txt-2);">${esc(formatTarget(j.target))}</p></div>
    <div><p class="slbl">Action</p><p style="font-size:12px;color:var(--txt-2);">${esc(formatAction(j.action_type))}</p>
      ${j.action_content?`<div style="padding:8px 10px;background:rgba(255,255,255,.025);border-radius:var(--radius);font-size:11px;color:var(--txt-3);margin-top:6px;">${esc(j.action_content)}</div>`:''}
    </div>
    <div><p class="slbl">Prochain run</p><p style="font-size:12px;color:var(--txt-2);">${j.next_run_at?j.next_run_at.slice(0,16).replace('T',' '):'—'}</p></div>`;
  openDrw('d-job');
}

async function saveJob() {
  const name    = document.getElementById('job-name').value.trim();
  const editId  = document.getElementById('job-edit-id').value;
  const trigType= ['time','cond','event'].find(t=>document.getElementById('tp-'+t)?.className.includes(t+' ')||document.getElementById('tp-'+t)?.className.endsWith(t))||'time';
  const target  = document.getElementById('job-target').value;
  const actType = document.getElementById('job-action-type').value;
  let content = '';
  if (['send_message','send_ia_bilan','notify_admin'].includes(actType)) content = document.getElementById('job-action-content')?.value||'';
  else if (actType==='send_form')   content = document.getElementById('job-form-sel')?.value||'';
  else if (['add_to_category','remove_from_category'].includes(actType)) content = document.getElementById('job-cat-sel')?.value||'';
  else if (actType==='webhook')     content = document.getElementById('job-webhook-url')?.value||'';
  if (!name) { toast('Nom requis','error'); return; }
  const payload = {
    name, trig_type:trigType, target, action_type:actType, action_content:content,
    ...(trigType==='time'  ? {freq:document.getElementById('job-freq').value, run_time:document.getElementById('job-time').value} : {}),
    ...(trigType==='cond'  ? {cond_field:document.getElementById('job-cond-field').value, cond_value:document.getElementById('job-cond-val').value, cond_extra:document.getElementById('job-cond-extra').value} : {}),
    ...(trigType==='event' ? {event_type:document.getElementById('job-event-type').value} : {}),
  };
  try {
    if (editId) { await apiFetch(API_G+'/jobs/'+editId,{method:'PATCH',body:JSON.stringify(payload)}); toast('Automation mise à jour ✓','success'); }
    else        { await apiFetch(API_G+'/jobs',{method:'POST',body:JSON.stringify(payload)}); toast('Automation créée ✓','success'); }
    closeM('m-new-job');
    document.getElementById('job-name').value=''; document.getElementById('job-action-content').value=''; document.getElementById('job-edit-id').value='';
    document.getElementById('job-modal-title').textContent='Créer une automation';
    selectTriggerType('time'); toggleJobActionDetails();
    await renderJobs();
  } catch(e) { toast('Erreur: '+e.message,'error'); }
}

async function toggleJob(id) {
  const j = (S.jobs||[]).find(x=>x.id===id); if (!j) return;
  try {
    await apiFetch(API_G+'/jobs/'+id,{method:'PATCH',body:JSON.stringify({is_active:j.is_active?0:1})});
    toast(j.is_active?'Job désactivé':'Job activé ✓','success'); await renderJobs();
  } catch(e) { toast('Erreur: '+e.message,'error'); }
}

async function runJobNow() {
  if (!S.currentJobId) return;
  try {
    const r = await apiFetch(API_G+'/jobs/'+S.currentJobId+'/run',{method:'POST',body:'{}'});
    closeAllDrw(); toast(`Exécuté ✓ — ${r.sent} envois, ${r.errors} erreurs`,'success'); await renderJobs();
  } catch(e) { toast('Erreur: '+e.message,'error'); }
}

async function deleteCurrentJob() {
  if (!confirm('Supprimer cette automation ?')) return;
  try {
    await apiFetch(API_G+'/jobs/'+S.currentJobId,{method:'DELETE'});
    closeAllDrw(); await renderJobs(); toast('Automation supprimée','success');
  } catch(e) { toast('Erreur: '+e.message,'error'); }
}

function renderTimeline() {
  const el = document.getElementById('schedule-timeline'); if (!el) return;
  const active = (S.jobs||[]).filter(j=>j.is_active&&j.next_run_at&&j.trig_type==='time');
  if (!active.length) { el.innerHTML='<p style="font-size:12px;color:var(--txt-5);">Aucun job planifié.</p>'; return; }
  el.innerHTML = active.slice(0,4).map(j=>`
    <div class="timeline-row">
      <div class="timeline-time">${j.run_time||'—'}</div>
      <div class="timeline-sep"></div>
      <div><p style="font-size:12px;color:var(--txt-2);">${esc(j.name)}</p><p style="font-size:10px;color:var(--txt-4);">${esc(formatTarget(j.target))}</p></div>
    </div>`).join('');
}

function renderExecLog() {
  const el = document.getElementById('exec-log'); if (!el) return;
  if (!S.execLogs.length) { el.innerHTML='<p style="font-size:12px;color:var(--txt-5);">Aucune exécution.</p>'; return; }
  el.innerHTML = S.execLogs.slice(-5).reverse().map(l=>`
    <div style="display:flex;align-items:center;gap:8px;padding:6px 0;border-bottom:1px solid var(--border-3);">
      <span style="width:8px;height:8px;border-radius:50%;background:${l.ok?'var(--green)':'var(--red)'};flex-shrink:0;"></span>
      <p style="font-size:12px;color:var(--txt-2);flex:1;">${esc(l.msg)}</p>
      <span style="font-size:10px;color:var(--txt-4);">${l.at}</span>
    </div>`).join('');
}

/* ═══════════════════════════════════════════
   PLANS
   ═══════════════════════════════════════════ */
async function renderPlans() {
  try { S.plans = await apiFetch(API_G+'/plans'); } catch(e) { console.warn('[plans]',e); }
  const el = document.getElementById('plans-list'); if (!el) return;
  if (!S.plans.length) { el.innerHTML='<div style="padding:30px 20px;text-align:center;color:var(--txt-5);font-size:12px;">Aucun plan créé.</div>'; return; }
  const clrs = ['var(--sky)','var(--violet)','var(--amber2)','var(--green)','var(--red)'];
  el.innerHTML = S.plans.map((p,i)=>{
    const clr = clrs[i%clrs.length];
    const dur = p.duration_days===0?'À vie':p.duration_days===30?'Mensuel':p.duration_days===90?'Trimestriel':p.duration_days===365?'Annuel':p.duration_days+'j';
    return `<div style="padding:12px;background:rgba(255,255,255,.025);border:1px solid var(--border-2);border-radius:var(--radius-lg);cursor:pointer;" onclick="editPlan(${p.id})">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
        <p style="font-size:12px;font-weight:500;color:white;">${esc(p.name)}</p>
        <span class="badge" style="font-size:10px;background:rgba(255,255,255,.06);color:${clr};">$${p.price_usd}/${dur}</span>
      </div>
      ${p.description?`<p style="font-size:10px;color:var(--txt-4);margin-bottom:6px;">${esc(p.description)}</p>`:''}
      <div style="display:flex;gap:10px;font-size:10px;">
        <span style="color:var(--green);">${p.active_count||0} actifs</span>
        ${p.trial_count?`<span style="color:var(--sky);">${p.trial_count} essais</span>`:''}
        ${p.trial_days?`<span style="color:var(--txt-4);">${p.trial_days}j essai</span>`:''}
      </div>
      <div style="display:flex;gap:6px;margin-top:8px;">
        <button class="btn-ghost" style="font-size:9px;padding:3px 7px;" onclick="event.stopPropagation();editPlan(${p.id})">Modifier</button>
        <button class="btn-danger" style="font-size:9px;padding:3px 7px;" onclick="event.stopPropagation();deletePlan(${p.id})">Supprimer</button>
      </div>
    </div>`;
  }).join('');
}

async function savePlan() {
  const name  = document.getElementById('plan-name').value.trim();
  const price = parseFloat(document.getElementById('plan-price').value)||0;
  const dur   = parseInt(document.getElementById('plan-duration').value)||30;
  const trial = parseInt(document.getElementById('plan-trial').value)||0;
  const desc  = document.getElementById('plan-desc').value.trim();
  const cats  = [...document.getElementById('plan-categories').selectedOptions].map(o=>o.value);
  const editId= document.getElementById('plan-edit-id').value;
  if (!name||!price) { toast('Nom et prix requis','error'); return; }
  const payload = {name, price_usd:price, duration_days:dur, trial_days:trial, description:desc, categories:cats};
  try {
    if (editId) { await apiFetch(API_G+'/plans/'+editId,{method:'PATCH',body:JSON.stringify(payload)}); toast('Plan mis à jour ✓','success'); }
    else        { await apiFetch(API_G+'/plans',{method:'POST',body:JSON.stringify(payload)}); toast('Plan créé ✓','success'); }
    closeM('m-new-plan');
    ['plan-name','plan-price','plan-desc'].forEach(id=>{const e=document.getElementById(id);if(e)e.value='';});
    document.getElementById('plan-edit-id').value=''; document.getElementById('plan-trial').value='0';
    document.getElementById('plan-modal-title').textContent="Créer un plan d'abonnement";
    await renderPlans(); await fillPlanSelects(); await renderSubStats();
  } catch(e) { toast('Erreur: '+e.message,'error'); }
}

function editPlan(id) {
  const p = S.plans.find(x=>x.id===id); if (!p) return;
  fillCategorySelects().then(()=>{
    document.getElementById('plan-edit-id').value  = p.id;
    document.getElementById('plan-name').value     = p.name;
    document.getElementById('plan-price').value    = p.price_usd;
    document.getElementById('plan-duration').value = p.duration_days;
    document.getElementById('plan-trial').value    = p.trial_days||0;
    document.getElementById('plan-desc').value     = p.description||'';
    document.getElementById('plan-modal-title').textContent='Modifier le plan';
    openM('m-new-plan');
  });
}

async function deletePlan(id) {
  if (!confirm('Désactiver ce plan ?')) return;
  try { await apiFetch(API_G+'/plans/'+id,{method:'DELETE'}); await renderPlans(); await renderSubStats(); toast('Plan désactivé','success'); }
  catch(e) { toast('Erreur: '+e.message,'error'); }
}

function openAddSub() { fillPlanSelects().then(()=>openM('m-add-sub')); }

/* ═══════════════════════════════════════════
   ABONNEMENTS
   ═══════════════════════════════════════════ */
async function renderSubscribers() {
  const pf = document.getElementById('sub-filter-plan')?.value||'';
  const sf = document.getElementById('sub-filter-status')?.value||'';
  const p  = new URLSearchParams();
  if (pf) p.set('plan_id',pf);
  if (sf) p.set('status',sf);
  try { S.subscribers = await apiFetch(API_G+'/subscriptions?'+p.toString()); } catch(e) { console.warn('[subs]',e); }
  const el = document.getElementById('subs-list'); if (!el) return;
  if (!S.subscribers.length) { el.innerHTML='<div style="padding:40px 20px;text-align:center;color:var(--txt-5);font-size:12px;">Aucun abonné.</div>'; return; }
  const sc = {active:{cls:'bdg-g',lbl:'Actif'},trial:{cls:'bdg-b',lbl:'Essai'},expiring:{cls:'bdg-a',lbl:'⚠ Expire'},expired:{cls:'bdg-r',lbl:'Expiré'},cancelled:{cls:'bdg-z',lbl:'Annulé'}};
  el.innerHTML = S.subscribers.map(s=>{
    const ss = sc[s.status]||{cls:'bdg-z',lbl:s.status};
    return `<div class="mrow">
      <div class="av" style="font-size:9px;">${initials(s.member_name||'?')}</div>
      <div style="flex:1;min-width:0;"><p style="font-size:12px;color:var(--txt-2);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${esc(s.member_name||'—')}</p><p style="font-size:10px;color:var(--txt-4);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${esc(s.plan_name||'?')} · ${(s.started_at||'').slice(0,10)}</p></div>
      <span class="badge ${ss.cls}" style="font-size:9px;flex-shrink:0;">${ss.lbl}</span>
      <span class="mrow-price" style="font-size:10px;color:var(--txt-4);min-width:40px;text-align:right;">$${(s.price_paid||0).toFixed(0)}</span>
      <span class="mrow-date" style="font-size:10px;color:var(--txt-4);min-width:70px;text-align:right;">${(s.expires_at||'').slice(0,10)}</span>
      <button class="btn-icon" style="width:22px;height:22px;" onclick="deleteSubscriber(${s.id})"><svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke="var(--red)" stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>`;
  }).join('');
}

async function renderSubStats() {
  try {
    const d = await apiFetch(API_G+'/subscriptions/stats');
    document.getElementById('stat-mrr').textContent         = '$'+(d.mrr||0).toFixed(0);
    document.getElementById('stat-subs-active').textContent = d.actifs||0;
    document.getElementById('stat-trials').textContent      = d.essais||0;
    document.getElementById('stat-churn').textContent       = d.churn_rate!=null?d.churn_rate+'%':'—';
    document.getElementById('stat-expiring').textContent    = d.expiring_soon||0;
    document.getElementById('stat-churn').className = ((d.churn_rate||0)>5?'stat-val neg':'stat-val pos');
  } catch(e) { console.warn('[subStats]',e); }
}

async function addSubscriber() {
  const name   = document.getElementById('sub-member-name').value.trim();
  const planId = parseInt(document.getElementById('sub-plan-sel').value);
  const status = document.getElementById('sub-status-sel').value;
  const promo  = document.getElementById('sub-promo-code').value.trim();
  if (!name)   { toast('Nom requis','error'); return; }
  if (!planId) { toast('Choisir un plan','error'); return; }
  try {
    await apiFetch(API_G+'/subscriptions',{method:'POST',body:JSON.stringify({member_name:name,plan_id:planId,status,promo_code:promo||null})});
    closeM('m-add-sub');
    document.getElementById('sub-member-name').value=''; document.getElementById('sub-promo-code').value='';
    await renderSubscribers(); await renderSubStats(); await renderPlans();
    toast(name+' ajouté ✓','success');
  } catch(e) { toast('Erreur: '+e.message,'error'); }
}

async function deleteSubscriber(id) {
  if (!confirm('Supprimer cet abonné ?')) return;
  try {
    await apiFetch(API_G+'/subscriptions/'+id,{method:'DELETE'});
    await renderSubscribers(); await renderSubStats(); await renderPlans();
    toast('Abonné supprimé','success');
  } catch(e) { toast('Erreur: '+e.message,'error'); }
}

/* ═══════════════════════════════════════════
   PROMOS
   ═══════════════════════════════════════════ */
async function renderPromos() {
  try { S.promos = await apiFetch(API_G+'/promos'); } catch(e) { console.warn('[promos]',e); }
  const el = document.getElementById('promos-list'); if (!el) return;
  if (!S.promos.length) { el.innerHTML='<div style="padding:40px 20px;text-align:center;color:var(--txt-5);font-size:12px;">Aucun code promo.</div>'; return; }
  el.innerHTML = S.promos.map(p=>{
    const expired = p.expires_at && p.expires_at < new Date().toISOString();
    const disc = p.discount_type==='percent'?`-${p.discount_value}%`:`-$${p.discount_value}`;
    return `<div class="promo-card ${p.is_active&&!expired?'active-p':''} fadein">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;">
        <div><p style="font-size:15px;font-weight:600;color:white;font-family:'Geist Mono',monospace;">${esc(p.code)}</p><p style="font-size:10px;color:var(--txt-4);">${p.plan_name||'Tous les plans'}${p.first_time_only?' · 1ère sous.':''}</p></div>
        <div style="text-align:right;">
          <p style="font-size:18px;font-weight:300;color:var(--green);">${disc}</p>
          <div style="display:flex;gap:6px;margin-top:4px;justify-content:flex-end;">
            ${expired?'<span class="badge bdg-z" style="font-size:9px;">Expiré</span>':p.is_active?'<span class="badge bdg-g" style="font-size:9px;">Actif</span>':'<span class="badge bdg-z" style="font-size:9px;">Inactif</span>'}
            ${p.quota_max?`<span class="badge bdg-a" style="font-size:9px;">${p.current_uses}/${p.quota_max}</span>`:''}
          </div>
        </div>
      </div>
      <div style="display:flex;gap:6px;flex-wrap:wrap;">
        <button class="btn-ghost" style="font-size:9px;padding:3px 8px;" onclick="copyCode('${p.code}')">Copier</button>
        <button class="btn-ghost" style="font-size:9px;padding:3px 8px;" onclick="editPromo(${p.id})">Modifier</button>
        <button class="btn-icon" style="width:22px;height:22px;" onclick="togglePromo(${p.id})" title="${p.is_active?'Désactiver':'Activer'}">
          <svg viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10" stroke="${p.is_active?'var(--amber2)':'var(--green)'}" fill="none"/><line x1="12" y1="8" x2="12" y2="12" stroke="${p.is_active?'var(--amber2)':'var(--green)'}" fill="none"/></svg>
        </button>
        <button class="del-row-btn" onclick="deletePromo(${p.id})"><svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
      </div>
    </div>`;
  }).join('');
  renderPromoStats();
}

function renderPromoStats() {
  const active = (S.promos||[]).filter(p=>p.is_active).length;
  const uses   = (S.promos||[]).reduce((a,p)=>a+(p.current_uses||0),0);
  document.getElementById('stat-promos-active').textContent = active;
  document.getElementById('stat-promo-uses').textContent    = uses;
}

async function savePromo() {
  const code   = document.getElementById('promo-code').value.trim().toUpperCase();
  const value  = parseFloat(document.getElementById('promo-value').value)||0;
  const type   = document.getElementById('promo-type').value;
  const planId = parseInt(document.getElementById('promo-plan').value)||null;
  const quota  = parseInt(document.getElementById('promo-quota').value)||null;
  const exp    = document.getElementById('promo-expires').value||null;
  const first  = document.getElementById('promo-first-only').checked?1:0;
  const editId = document.getElementById('promo-edit-id').value;
  if (!code||!value) { toast('Code et réduction requis','error'); return; }
  const payload = {code, discount_type:type, discount_value:value, plan_id:planId, quota_max:quota, first_time_only:first, expires_at:exp};
  try {
    if (editId) { await apiFetch(API_G+'/promos/'+editId,{method:'PATCH',body:JSON.stringify(payload)}); toast('Code mis à jour ✓','success'); }
    else        { await apiFetch(API_G+'/promos',{method:'POST',body:JSON.stringify(payload)}); toast('Code créé ✓','success'); }
    closeM('m-new-promo');
    ['promo-code','promo-value','promo-quota','promo-expires'].forEach(id=>{const e=document.getElementById(id);if(e)e.value='';});
    document.getElementById('promo-edit-id').value=''; document.getElementById('promo-modal-title').textContent='Créer un code promo';
    await renderPromos(); await fillPromoSelects();
  } catch(e) { toast('Erreur: '+e.message,'error'); }
}

function editPromo(id) {
  const p = S.promos.find(x=>x.id===id); if (!p) return;
  fillPromoSelects().then(()=>{
    document.getElementById('promo-edit-id').value     = p.id;
    document.getElementById('promo-code').value        = p.code;
    document.getElementById('promo-value').value       = p.discount_value;
    document.getElementById('promo-type').value        = p.discount_type;
    document.getElementById('promo-plan').value        = p.plan_id||'';
    document.getElementById('promo-quota').value       = p.quota_max||'';
    document.getElementById('promo-expires').value     = p.expires_at?p.expires_at.slice(0,10):'';
    document.getElementById('promo-first-only').checked= !!p.first_time_only;
    document.getElementById('promo-modal-title').textContent='Modifier le code';
    openM('m-new-promo');
  });
}

function copyCode(code) { navigator.clipboard.writeText(code).then(()=>toast('Code copié ✓','success')); }

async function togglePromo(id) {
  const p = (S.promos||[]).find(x=>x.id===id); if (!p) return;
  try { await apiFetch(API_G+'/promos/'+id,{method:'PATCH',body:JSON.stringify({is_active:p.is_active?0:1})}); toast(p.is_active?'Code désactivé':'Code activé ✓','success'); await renderPromos(); }
  catch(e) { toast('Erreur: '+e.message,'error'); }
}

async function deletePromo(id) {
  if (!confirm('Supprimer ce code promo ?')) return;
  try { await apiFetch(API_G+'/promos/'+id,{method:'DELETE'}); await renderPromos(); await fillPromoSelects(); toast('Code supprimé','success'); }
  catch(e) { toast('Erreur: '+e.message,'error'); }
}

async function testPromoCode() {
  const code = document.getElementById('test-promo-input').value.trim().toUpperCase();
  const el   = document.getElementById('test-promo-result');
  if (!code) { el.innerHTML=''; return; }
  try {
    const r = await apiFetch(API_G+'/promos/validate',{method:'POST',body:JSON.stringify({code})});
    if (r.valid) {
      const disc = r.discount_type==='percent'?`-${r.discount_value}%`:`-$${r.discount_value}`;
      el.innerHTML=`<span style="color:var(--green);">✅ Valide — ${disc}${r.plan_name?' · '+r.plan_name:''}</span>`;
    } else {
      el.innerHTML=`<span style="color:var(--red);">❌ ${r.error}</span>`;
    }
  } catch(e) { el.innerHTML=`<span style="color:var(--red);">Erreur: ${e.message}</span>`; }
}

async function saveAutoPromos() {
  const payload = {
    anniversary_active: document.getElementById('tog-anniversary').classList.contains('on')?1:0,
    anniversary_pct:    parseFloat(document.getElementById('ap-anniversary-pct').value)||15,
    winback_active:     document.getElementById('tog-winback').classList.contains('on')?1:0,
    winback_pct:        parseFloat(document.getElementById('ap-winback-pct').value)||20,
    upgrade_active:     document.getElementById('tog-upgrade').classList.contains('on')?1:0,
    upgrade_pct:        parseFloat(document.getElementById('ap-upgrade-pct').value)||30,
  };
  try { await apiFetch(API_G+'/promos/auto-config',{method:'PATCH',body:JSON.stringify(payload)}); }
  catch(e) { console.warn('[autoPromos save]',e); }
}

async function loadAutoPromos() {
  try {
    const d = await apiFetch(API_G+'/promos/auto-config');
    if (d.anniversary_active) document.getElementById('tog-anniversary').classList.add('on');
    document.getElementById('ap-anniversary-pct').value = d.anniversary_pct||15;
    if (d.winback_active) document.getElementById('tog-winback').classList.add('on');
    document.getElementById('ap-winback-pct').value = d.winback_pct||20;
    if (d.upgrade_active) document.getElementById('tog-upgrade').classList.add('on');
    document.getElementById('ap-upgrade-pct').value = d.upgrade_pct||30;
  } catch(e) { console.warn('[autoPromos load]',e); }
}

/* ═══════════════════════════════════════════
   BROADCAST
   ═══════════════════════════════════════════ */
function updateBcPreview() {
  const txt = document.getElementById('bc-message')?.value||'';
  const el  = document.getElementById('bc-preview');
  if (el) el.innerHTML = esc(txt).replace(/\n/g,'<br>')||'<span style="color:var(--txt-5);">Votre message...</span>';
}

function updateBcCount() {
  const t  = document.getElementById('bc-target')?.value;
  const el = document.getElementById('bc-count-label');
  if (!el) return;
  const cat = S.categories.find(c=>c.name===t);
  if (cat)      el.textContent = cat.count+' membres recevront ce message';
  else if (t==='admin') el.textContent = 'Admin uniquement (test)';
  else          el.textContent = 'Tous les membres';
}

async function sendBroadcast() {
  const msg = document.getElementById('bc-message').value.trim();
  const t   = document.getElementById('bc-target').value;
  if (!msg) { toast('Message requis','error'); return; }
  const payload = {message:msg, format:'text', tag:'broadcast_rapide_'+Date.now()};
  if (t==='all')   payload.category='all';
  else if (t==='admin') payload.user_ids=[571718066];
  else payload.category=t;
  try {
    await apiFetch(API_B+'/broadcast',{method:'POST',body:JSON.stringify(payload)});
    closeM('m-broadcast'); document.getElementById('bc-message').value=''; updateBcPreview();
    toast('Broadcast envoyé ✓','success');
  } catch(e) { toast('Erreur broadcast: '+e.message,'error'); }
}

/* ═══════════════════════════════════════════
   INIT
   ═══════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  sv('links', document.getElementById('nav-links'));
});
</script>
</body>
</html>