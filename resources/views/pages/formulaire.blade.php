<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Felipe Bot — Formulaires</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js" defer></script>
<style>

/* ════════════════════════════════════════════════════
   RESET & VARIABLES — alignées sur chat-direct/messages
   ════════════════════════════════════════════════════ */
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
  --topbar-h:   50px;
  --radius:     8px;
  --radius-lg:  12px;
  --ease:       cubic-bezier(.4,0,.2,1);
}

html, body {
  height: 100dvh; overflow: hidden;
  font-family: 'Geist', sans-serif; font-size: 13px;
  background: var(--bg); color: var(--txt);
  -webkit-font-smoothing: antialiased;
}
button { font-family: inherit; cursor: pointer; }
a      { text-decoration: none; color: inherit; }
input, textarea, select { font-family: inherit; color: var(--txt); }
::-webkit-scrollbar       { width: 3px; height: 3px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(255,255,255,.08); border-radius: 99px; }

/* ════════════════════════════════════════════════════
   LAYOUT
   ════════════════════════════════════════════════════ */
#app { display: flex; height: 100dvh; overflow: hidden; }
#main { flex: 1; min-width: 0; display: flex; flex-direction: column; overflow: hidden; }

/* ════════════════════════════════════════════════════
   SIDEBAR — identique chat-direct
   ════════════════════════════════════════════════════ */
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
  color: var(--txt-3); font-size: 12px; transition: all .15s;
}
#sb-close:hover { color: var(--txt); background: rgba(255,255,255,.1); }

.sb-nav   { flex: 1; padding: 8px; overflow-y: auto; display: flex; flex-direction: column; gap: 1px; }
.sb-label { font-size: 10px; font-weight: 500; color: var(--txt-5); text-transform: uppercase; letter-spacing: .06em; padding: 10px 10px 4px; display: block; }
.sb-link {
  display: flex; align-items: center; gap: 9px;
  padding: 7px 10px; border-radius: var(--radius);
  font-size: 13px; color: var(--txt-4);
  transition: all .15s; background: none; border: none; width: 100%; text-align: left;
}
.sb-link:hover  { color: #d4d4d8; background: var(--hover); }
.sb-link.active { color: #f4f4f5; background: rgba(255,255,255,.07); }
.sb-link svg    { width: 14px; height: 14px; flex-shrink: 0; stroke: currentColor; fill: none; }

.sb-foot { padding: 10px 12px; border-top: 1px solid var(--border); flex-shrink: 0; }
.sb-user { display: flex; align-items: center; gap: 8px; }
.sb-av   {
  width: 24px; height: 24px; border-radius: 50%;
  background: rgba(255,255,255,.07);
  display: flex; align-items: center; justify-content: center;
  font-size: 9px; font-weight: 600; color: var(--txt-4); flex-shrink: 0;
}

/* ════════════════════════════════════════════════════
   TOPBAR
   ════════════════════════════════════════════════════ */
#topbar {
  flex-shrink: 0; height: var(--topbar-h);
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 16px; background: var(--bg-1);
  border-bottom: 1px solid var(--border);
  gap: 8px; overflow: hidden;
}
.topbar-left {
  display: flex; align-items: center; gap: 8px;
  flex: 1 1 auto; min-width: 0; overflow: hidden;
}
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

.topbar-title {
  font-size: 14px; font-weight: 500; color: white;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}

/* Tabs desktop dans la topbar */
.tabs-bar {
  display: flex; gap: 2px;
  background: rgba(255,255,255,.03);
  border: 1px solid var(--border);
  border-radius: var(--radius); padding: 2px;
  flex-shrink: 0;
}
.tab {
  display: flex; align-items: center; gap: 5px;
  padding: 5px 11px; border-radius: 6px;
  font-size: 12px; color: var(--txt-4);
  background: none; border: none;
  transition: all .15s; white-space: nowrap;
}
.tab:hover  { color: var(--txt-2); }
.tab.active { background: rgba(255,255,255,.08); color: var(--txt); }

/* Tabs mobile sous la topbar */
.tabs-mobile {
  display: none; flex-shrink: 0;
  background: var(--bg-1); border-bottom: 1px solid var(--border);
}
.tabs-mobile .tab {
  flex: 1; justify-content: center; border-radius: 0;
  padding: 8px 4px;
  border-bottom: 2px solid transparent;
}
.tabs-mobile .tab.active { background: none; border-bottom-color: var(--sky); color: var(--sky); }

/* UI save / undo */
.save-ui  { display: flex; align-items: center; gap: 5px; font-size: 11px; color: var(--txt-4); }
.save-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--green); flex-shrink: 0; transition: background .2s; }
.undo-ui  { display: flex; align-items: center; gap: 3px; }

/* ════════════════════════════════════════════════════
   VUES
   ════════════════════════════════════════════════════ */
.view     { flex: 1; display: flex; flex-direction: column; overflow: hidden; }
.scroll-y { overflow-y: auto; }
.pad      { padding: 20px; }

/* ════════════════════════════════════════════════════
   COMPOSANTS COMMUNS
   ════════════════════════════════════════════════════ */
.card {
  background: var(--bg-2);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
}

.btn-primary {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 12px;
  background: var(--amber-bg); border: 1px solid var(--amber-bd);
  border-radius: var(--radius);
  color: var(--amber); font-size: 12px; font-weight: 500;
  white-space: nowrap; transition: all .15s;
}
.btn-primary:hover { background: rgba(245,158,11,.2); }
.btn-primary svg   { width: 11px; height: 11px; stroke: currentColor; fill: none; flex-shrink: 0; }

.btn-sky {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 5px 12px;
  background: var(--sky); border: none;
  border-radius: var(--radius);
  color: #052e46; font-size: 12px; font-weight: 500;
  transition: all .15s;
}
.btn-sky:hover { background: #7dd3fc; box-shadow: 0 0 20px rgba(56,189,248,.25); }
.btn-sky:active { transform: scale(.97); }
.btn-sky svg { width: 10px; height: 10px; stroke: currentColor; fill: none; flex-shrink: 0; }

.btn-ghost {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 5px 10px;
  background: rgba(255,255,255,.04); border: 1px solid var(--border);
  border-radius: var(--radius);
  color: var(--txt-2); font-size: 11px;
  transition: all .15s;
}
.btn-ghost:hover { background: rgba(255,255,255,.08); color: var(--txt); border-color: rgba(255,255,255,.12); }
.btn-ghost svg   { width: 10px; height: 10px; stroke: currentColor; fill: none; flex-shrink: 0; }

.btn-danger {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 5px 10px;
  background: var(--red-bg); border: 1px solid rgba(248,113,113,.2);
  border-radius: var(--radius);
  color: var(--red); font-size: 11px; transition: all .15s;
}
.btn-danger:hover { background: rgba(248,113,113,.18); }

.btn-icon {
  display: inline-flex; align-items: center; justify-content: center;
  width: 26px; height: 26px;
  background: rgba(255,255,255,.04); border: none;
  border-radius: 6px; color: var(--txt-4);
  transition: all .15s; flex-shrink: 0;
}
.btn-icon:hover         { background: rgba(255,255,255,.09); color: #d4d4d8; }
.btn-icon.del:hover     { background: var(--red-bg); color: var(--red); }
.btn-icon:disabled      { opacity: .2; cursor: default; }
.btn-icon svg           { width: 10px; height: 10px; stroke: currentColor; fill: none; }

.inp {
  width: 100%; padding: 6px 10px;
  font-size: 12px;
  background: rgba(255,255,255,.03);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: var(--radius); color: var(--txt);
  outline: none; transition: border-color .15s, background .15s;
}
.inp:hover  { border-color: rgba(255,255,255,.12); background: rgba(255,255,255,.04); }
.inp:focus  { border-color: var(--focus); background: rgba(56,189,248,.03); }
.inp::placeholder { color: var(--txt-5); }
textarea.inp { resize: vertical; line-height: 1.5; }
select.inp   { cursor: pointer; }

.toggle {
  width: 36px; height: 20px;
  background: rgba(255,255,255,.1);
  border-radius: 99px; position: relative;
  cursor: pointer; border: none; flex-shrink: 0;
  transition: background .2s;
}
.toggle.on { background: var(--green); }
.toggle::after {
  content: ''; position: absolute;
  width: 14px; height: 14px; background: #fff;
  border-radius: 50%; top: 3px; left: 3px;
  transition: transform .2s var(--ease);
  box-shadow: 0 1px 4px rgba(0,0,0,.3);
}
.toggle.on::after { transform: translateX(16px); }

.badge {
  display: inline-flex; align-items: center;
  padding: 2px 7px; border-radius: 99px;
  font-size: 10px; font-weight: 500;
}
.badge-sky    { background: var(--sky-bg);    color: var(--sky); }
.badge-green  { background: var(--green-bg);  color: var(--green); }
.badge-amber  { background: var(--amber2-bg); color: var(--amber2); }
.badge-red    { background: var(--red-bg);    color: var(--red); }
.badge-violet { background: var(--violet-bg); color: var(--violet); }
.badge-teal   { background: var(--teal-bg);   color: var(--teal); }
.badge-pink   { background: var(--pink-bg);   color: var(--pink); }
.badge-zinc   { background: rgba(255,255,255,.06); color: var(--txt-3); }

.pbar   { height: 2px; background: rgba(255,255,255,.06); border-radius: 9px; overflow: hidden; }
.pbar-f { height: 100%; border-radius: 9px; transition: width .4s; }

.lbl     { font-size: 10px; color: var(--txt-4); margin-bottom: 4px; display: block; }
.lbl-sec { font-size: 10px; font-weight: 600; color: var(--txt-5); letter-spacing: .07em; text-transform: uppercase; display: block; }
.sec-ttl { font-size: 12px; font-weight: 500; color: #d4d4d8; display: block; }
.g2  { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; }
.g3  { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 7px; }
.mb6 { margin-bottom: 6px; }
.mb8 { margin-bottom: 8px; }
.mb12{ margin-bottom: 12px; }

.vchip {
  display: inline-flex; padding: 2px 7px; border-radius: 4px;
  font-size: 10px; font-family: 'Geist Mono', monospace;
  background: var(--sky-bg); color: var(--sky);
  cursor: pointer; border: 1px solid rgba(56,189,248,.15); margin: 2px;
  transition: background .15s; user-select: none;
}
.vchip:hover { background: rgba(56,189,248,.18); }

.api-note {
  background: rgba(56,189,248,.04); border: 1px solid rgba(56,189,248,.1);
  border-radius: var(--radius); padding: 6px 10px;
  font-size: 10px; color: #64b5f6; margin-bottom: 8px;
  display: flex; align-items: center; gap: 6px;
}
.abox {
  background: rgba(52,211,153,.04); border: 1px solid rgba(52,211,153,.15);
  border-radius: var(--radius); padding: 9px; margin-top: 7px;
}
.tog-row  {
  display: flex; align-items: center; justify-content: space-between;
  gap: 12px; padding: 10px 12px;
  background: rgba(255,255,255,.02);
  border: 1px solid rgba(255,255,255,.05);
  border-radius: var(--radius);
}
.tog-row:hover { background: rgba(255,255,255,.04); border-color: rgba(255,255,255,.08); }
.opt-p    { font-size: 13px; color: #d4d4d8; }
.opt-sub  { font-size: 10px; color: var(--txt-4); margin-top: 2px; line-height: 1.4; }

.spinner {
  width: 14px; height: 14px;
  border: 2px solid rgba(255,255,255,.1); border-top-color: var(--sky);
  border-radius: 50%; animation: spin .6s linear infinite; flex-shrink: 0;
}

.skeleton {
  background: linear-gradient(90deg, rgba(255,255,255,.04) 25%, rgba(255,255,255,.07) 50%, rgba(255,255,255,.04) 75%);
  background-size: 400px 100%; animation: shimmer 1.4s ease infinite;
  border-radius: var(--radius);
}

.empty-state {
  display: flex; flex-direction: column; align-items: center;
  justify-content: center; padding: 48px 24px; gap: 12px; text-align: center;
}
.empty-ico { width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,.04); display: flex; align-items: center; justify-content: center; color: var(--txt-4); }
.empty-ttl { font-size: 13px; font-weight: 500; color: var(--txt-2); }
.empty-sub { font-size: 12px; color: var(--txt-4); max-width: 240px; line-height: 1.5; }

.fadein { animation: fadein .18s var(--ease); }

/* ════════════════════════════════════════════════════
   VUE LISTE
   ════════════════════════════════════════════════════ */
.kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; }
.kpi { padding: 16px; }
.kpi-l { font-size: 10px; color: var(--txt-4); margin-bottom: 8px; }
.kpi-v { font-size: 24px; font-weight: 300; color: white; }

.tpl-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 8px; }
.tpl { padding: 12px; cursor: pointer; transition: border-color .2s, transform .12s, background .15s; }
.tpl:hover { border-color: rgba(255,255,255,.18); transform: translateY(-1px); background: var(--bg-3); }
.tpl-ico { width: 26px; height: 26px; border-radius: 7px; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; }
.tpl-n   { font-size: 12px; font-weight: 500; color: var(--txt); }
.tpl-c   { font-size: 10px; color: var(--txt-4); margin-top: 2px; font-family: 'Geist Mono', monospace; }

/* Table liste */
.tbl-head { display: flex; align-items: center; gap: 10px; padding: 9px 14px; background: rgba(255,255,255,.015); border-bottom: 1px solid var(--border-2); font-size: 10px; font-weight: 600; color: var(--txt-5); letter-spacing: .04em; text-transform: uppercase; }
.tbl-row  { display: flex; align-items: center; gap: 10px; padding: 11px 14px; border-bottom: 1px solid rgba(255,255,255,.035); cursor: pointer; transition: background .12s; }
.tbl-row:hover { background: rgba(255,255,255,.025); }
.tbl-row:last-child { border-bottom: none; }
.row-n { font-size: 12px; font-weight: 500; color: var(--txt); }
.row-c { font-size: 10px; color: var(--txt-4); font-family: 'Geist Mono', monospace; margin-top: 2px; }
.row-actions { display: flex; gap: 3px; opacity: 0; transition: opacity .12s; }
.tbl-row:hover .row-actions { opacity: 1; }
.c-type { width: 78px; flex-shrink: 0; }
.c-num  { width: 55px; flex-shrink: 0; font-size: 12px; color: var(--txt-2); }
.c-comp { width: 80px; flex-shrink: 0; }
.c-stat { width: 58px; flex-shrink: 0; }
.c-sc   { width: 65px; flex-shrink: 0; font-size: 12px; }
.c-date { width: 80px; flex-shrink: 0; font-size: 11px; color: var(--txt-4); }
.av-sm  { width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 9px; font-weight: 600; flex-shrink: 0; }

.btn-activate {
  background: var(--green-bg); color: var(--green);
  border: 1px solid rgba(52,211,153,.2);
  border-radius: 5px; padding: 3px 7px;
  font-size: 10px; cursor: pointer; font-family: inherit; transition: background .12s;
}
.btn-activate:hover { background: rgba(52,211,153,.2); }

/* ════════════════════════════════════════════════════
   BUILDER
   ════════════════════════════════════════════════════ */
.builder-wrap { display: flex; height: calc(100dvh - var(--topbar-h)); position: relative; }

.col-l {
  width: 360px; flex-shrink: 0;
  border-right: 1px solid var(--border-2);
  display: flex; flex-direction: column;
  overflow: hidden; background: var(--bg-1);
}
.col-r {
  flex: 1; display: flex; flex-direction: column;
  overflow: hidden; background: #0a0a0d;
}

.palette-box { background: rgba(255,255,255,.015); border: 1px solid var(--border); border-radius: var(--radius-lg); padding: 10px; }
.pal-s { font-size: 9px; font-weight: 600; color: var(--txt-5); letter-spacing: .07em; text-transform: uppercase; padding: 8px 0 5px; display: block; }
.pal-s:first-child { padding-top: 0; }
.pal-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3px; }
.pal-btn {
  display: flex; align-items: center; gap: 7px; padding: 6px 8px;
  border-radius: var(--radius); border: 1px solid rgba(255,255,255,.06);
  background: rgba(255,255,255,.02); cursor: pointer;
  font-size: 11px; color: var(--txt-2); width: 100%;
  font-family: inherit; transition: all .12s; text-align: left;
}
.pal-btn:hover { border-color: rgba(255,255,255,.14); background: rgba(255,255,255,.05); color: var(--txt); }
.pal-btn svg { width: 11px; height: 11px; stroke: currentColor; fill: none; opacity: .6; flex-shrink: 0; }
.pal-btn:hover svg { opacity: 1; }

/* Field items */
.fi {
  background: rgba(255,255,255,.018); border: 1px solid var(--border);
  border-radius: var(--radius-lg); margin-bottom: 5px; overflow: hidden;
  transition: border-color .12s, background .12s;
}
.fi:hover { border-color: rgba(255,255,255,.12); }
.fi.open  { border-color: rgba(56,189,248,.3); background: rgba(56,189,248,.02); }
.fi.sortable-ghost { opacity: .3; border: 1px dashed var(--sky); }
.fi-head  { display: flex; align-items: center; gap: 7px; padding: 9px 10px; cursor: pointer; user-select: none; }
.fi-body  { padding: 0 10px 12px; display: none; animation: slideDown .15s var(--ease); }
.fi-body.show { display: block; }
.fi-type-ico { width: 22px; height: 22px; border-radius: 5px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.drag { color: var(--txt-5); font-size: 13px; cursor: grab; flex-shrink: 0; transition: color .12s; }
.drag:active { cursor: grabbing; }
.fi:hover .drag { color: var(--txt-4); }
.fi-label { font-size: 12px; font-weight: 500; color: var(--txt); flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.fi-chevron { color: var(--txt-5); transition: transform .2s; flex-shrink: 0; }
.fi.open .fi-chevron { transform: rotate(180deg); }
.opt-row { display: flex; align-items: center; gap: 6px; margin-bottom: 4px; }
.cdot { width: 12px; height: 12px; border-radius: 50%; border: 1.5px solid rgba(255,255,255,.2); flex-shrink: 0; cursor: pointer; transition: all .12s; }
.cdot.on { background: var(--green); border-color: var(--green); box-shadow: 0 0 8px rgba(52,211,153,.3); }

.cond-row { display: flex; align-items: center; gap: 5px; padding: 7px 8px; background: rgba(255,255,255,.02); border: 1px solid rgba(255,255,255,.05); border-radius: var(--radius); margin-bottom: 4px; }
.act-row  { display: flex; align-items: center; gap: 5px; padding: 7px 8px; background: rgba(56,189,248,.03); border: 1px solid rgba(56,189,248,.1); border-radius: var(--radius); margin-bottom: 4px; }
.cond-lbl { font-size: 10px; font-weight: 600; min-width: 26px; letter-spacing: .04em; }

.trigger-date-wrap { display: none; margin-top: 6px; }
.trigger-date-wrap.show { display: block; }

/* FAB preview mobile */
.fab-preview {
  display: none; position: absolute; bottom: 16px; right: 16px; z-index: 10;
  background: var(--sky); color: #052e46; border: none; border-radius: 99px;
  padding: 9px 16px; font-size: 12px; font-weight: 500; font-family: inherit;
  cursor: pointer; gap: 6px; align-items: center;
  box-shadow: 0 4px 24px rgba(56,189,248,.35); transition: all .12s;
}
.fab-preview:hover { box-shadow: 0 6px 32px rgba(56,189,248,.5); transform: translateY(-1px); }
.prev-close { display: none; background: none; border: none; color: var(--txt-4); font-size: 12px; cursor: pointer; padding: 10px 16px 0; font-family: inherit; text-align: left; }

.prev-bar  { display: flex; align-items: center; justify-content: space-between; padding: 10px 16px; border-bottom: 1px solid var(--border-2); flex-shrink: 0; gap: 8px; flex-wrap: wrap; }
.prev-body { flex: 1; display: flex; align-items: center; justify-content: center; padding: 20px; overflow: hidden; }
.prev-foot { display: flex; align-items: center; justify-content: space-between; padding: 9px 16px; border-top: 1px solid var(--border-2); flex-shrink: 0; }
.cmd-pill  { font-family: 'Geist Mono', monospace; font-size: 11px; background: var(--violet-bg); color: var(--violet); padding: 2px 8px; border-radius: 5px; }

.sdot { width: 6px; height: 6px; border-radius: 50%; background: rgba(255,255,255,.12); transition: all .2s; cursor: pointer; }
.sdot.on    { background: var(--sky); width: 20px; border-radius: 3px; }
.sdot.intro { background: rgba(52,211,153,.5); }
.sdot.outro { background: rgba(167,139,250,.5); }

/* ── Telegram phone ── */
.phone   { width: 296px; height: 560px; border-radius: 32px; border: 6px solid #18182a; overflow: hidden; box-shadow: 0 28px 70px rgba(0,0,0,.7), 0 0 0 1px rgba(255,255,255,.05); flex-shrink: 0; }
.tg      { background: #1c2733; height: 100%; display: flex; flex-direction: column; }
.tg-status { background: #17212b; padding: 5px 12px; display: flex; justify-content: space-between; flex-shrink: 0; }
.tg-status span { font-size: 9px; color: #4a6478; }
.tg-head { background: #17212b; padding: 7px 12px; display: flex; align-items: center; gap: 8px; border-bottom: 1px solid rgba(255,255,255,.05); flex-shrink: 0; }
.tg-ava  { width: 28px; height: 28px; border-radius: 50%; background: #0ea5e9; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.tg-msgs { flex: 1; overflow-y: auto; padding: 9px 10px; display: flex; flex-direction: column; gap: 5px; }
.tg-rk   { background: #17212b; border-top: 1px solid rgba(255,255,255,.06); padding: 7px 10px; display: flex; flex-wrap: wrap; gap: 5px; flex-shrink: 0; }
.tg-bar  { background: #17212b; padding: 8px 10px; display: flex; align-items: center; gap: 7px; border-top: 1px solid rgba(255,255,255,.05); flex-shrink: 0; }
.tg-inp  { flex: 1; background: #2b3a4a; border-radius: 18px; padding: 7px 12px; font-size: 11px; color: #4a6478; }
.tg-send { width: 28px; height: 28px; border-radius: 50%; background: #1d7fbf; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0; transition: background .12s; }
.tg-send:hover { background: #2590d8; }
.bot-bbl  { background: #1e3040; border-radius: 11px 11px 11px 2px; padding: 8px 11px; font-size: 11px; line-height: 1.6; color: #e2e8f0; max-width: 92%; align-self: flex-start; }
.user-bbl { background: #2b5278; border-radius: 11px 11px 2px 11px; padding: 8px 11px; font-size: 11px; line-height: 1.6; color: #e2e8f0; max-width: 86%; align-self: flex-end; }
.tg-time  { font-size: 9px; color: #4a6478; margin-top: 2px; }
.tg-time.r { text-align: right; }
.tg-btn   { background: #1e3040; border: 1px solid rgba(56,189,248,.2); color: #64b5f6; border-radius: 7px; padding: 7px 11px; font-size: 11px; text-align: center; cursor: pointer; transition: all .12s; margin-top: 2px; width: 100%; }
.tg-btn:hover { background: rgba(56,189,248,.12); border-color: rgba(56,189,248,.35); }
.tg-rk-btn { background: #1e3040; border: 1px solid rgba(255,255,255,.1); color: #e2e8f0; border-radius: 6px; padding: 6px 10px; font-size: 11px; cursor: pointer; transition: all .12s; }

/* ════════════════════════════════════════════════════
   VUE REPONSES
   ════════════════════════════════════════════════════ */
.responses-filters { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; flex-wrap: wrap; }

/* Modal détail réponses */
.resp-detail-grid { display: grid; grid-template-columns: 260px 1fr; gap: 0; flex: 1; min-height: 0; overflow: hidden; }
.resp-sidebar { border-right: 1px solid rgba(255,255,255,.07); overflow-y: auto; padding: 0; }
.resp-sidebar-head { padding: 14px 16px 10px; border-bottom: 1px solid rgba(255,255,255,.06); position: sticky; top: 0; background: #111113; z-index: 1; }
.resp-sidebar-title { font-size: 11px; color: var(--txt-4); text-transform: uppercase; letter-spacing: .07em; font-weight: 500; margin-bottom: 6px; }
.resp-search { width: 100%; background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.08); border-radius: 6px; padding: 5px 9px; font-size: 11px; color: var(--txt); font-family: inherit; outline: none; }
.resp-search:focus { border-color: rgba(56,189,248,.35); }
.resp-user-item { display: flex; align-items: center; gap: 9px; padding: 9px 14px; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,.04); transition: background .12s; }
.resp-user-item:hover  { background: rgba(255,255,255,.04); }
.resp-user-item.active { background: rgba(56,189,248,.08); border-left: 2px solid var(--sky); }
.resp-av       { width: 30px; height: 30px; border-radius: 50%; background: var(--sky-bg); color: var(--sky); display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 600; flex-shrink: 0; }
.resp-user-name  { font-size: 12px; color: var(--txt); font-weight: 500; }
.resp-user-meta  { font-size: 10px; color: var(--txt-4); margin-top: 1px; }
.resp-user-score { margin-left: auto; font-size: 11px; font-weight: 600; flex-shrink: 0; }
.resp-content      { display: flex; flex-direction: column; overflow: hidden; min-height: 0; }
.resp-content-head { padding: 14px 18px 12px; border-bottom: 1px solid rgba(255,255,255,.06); display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.resp-content-body { flex: 1; overflow-y: auto; padding: 14px 18px; display: flex; flex-direction: column; gap: 10px; }
.resp-empty-state  { display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100%; color: var(--txt-5); gap: 8px; }
.ans-card { background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.06); border-radius: 8px; padding: 10px 13px; }
.ans-card-head { display: flex; align-items: center; gap: 7px; margin-bottom: 6px; }
.ans-step { font-size: 9px; font-weight: 600; color: var(--sky); background: var(--sky-bg); padding: 2px 6px; border-radius: 3px; font-family: 'Geist Mono', monospace; }
.ans-type-lbl { font-size: 10px; color: var(--txt-3); }
.ans-q  { font-size: 11px; color: var(--txt-3); margin-bottom: 5px; font-style: italic; }
.ans-val { font-size: 13px; color: var(--txt); line-height: 1.5; word-break: break-word; }
.ans-correct   { border-left: 2px solid var(--green); }
.ans-incorrect { border-left: 2px solid var(--red); }
.score-panel { background: rgba(52,211,153,.05); border: 1px solid rgba(52,211,153,.15); border-radius: 8px; padding: 12px 14px; display: flex; align-items: center; gap: 14px; margin-bottom: 4px; }
.score-big    { font-size: 22px; font-weight: 300; color: var(--green); }
.score-detail { font-size: 11px; color: var(--txt-3); line-height: 1.6; }
.ans-media-wrap { margin-top: 8px; border-radius: 7px; overflow: hidden; background: rgba(0,0,0,.2); border: 1px solid rgba(255,255,255,.07); }
.ans-media-wrap img   { width: 100%; max-height: 280px; object-fit: contain; display: block; background: #000; }
.ans-media-wrap video { width: 100%; max-height: 260px; display: block; background: #000; }
.ans-media-wrap audio { width: 100%; padding: 8px; display: block; }
.ans-file-dl { display: flex; align-items: center; gap: 10px; padding: 10px 12px; color: var(--sky); font-size: 12px; text-decoration: none; cursor: pointer; transition: background .12s; }
.ans-file-dl:hover { background: rgba(56,189,248,.07); }
.modal-xl { width: min(900px, 95vw); max-height: 88vh; display: flex; flex-direction: column; overflow: hidden; }
.modal-xl .modal-body { padding: 0; flex: 1; overflow: hidden; display: flex; flex-direction: column; min-height: 0; }

/* ════════════════════════════════════════════════════
   MODALS
   ════════════════════════════════════════════════════ */
.modal-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.7); z-index: 300;
  align-items: center; justify-content: center; padding: 16px;
  backdrop-filter: blur(4px);
}
.modal-overlay.open { display: flex; }

.modal {
  background: var(--bg-3); border: 1px solid rgba(255,255,255,.1);
  border-radius: var(--radius-lg);
  width: min(520px, 100%); max-height: 90dvh;
  display: flex; flex-direction: column; overflow: hidden;
  box-shadow: 0 32px 80px rgba(0,0,0,.5);
}
.modal-head { display: flex; align-items: center; justify-content: space-between; padding: 16px 18px; border-bottom: 1px solid var(--border-2); gap: 8px; flex-shrink: 0; }
.modal-head h2 { font-size: 13px; font-weight: 500; color: white; }
.modal-body { padding: 14px 18px; display: flex; flex-direction: column; gap: 9px; overflow-y: auto; }
.modal-foot { display: flex; justify-content: flex-end; gap: 7px; padding: 12px 18px; border-top: 1px solid var(--border-2); flex-shrink: 0; }

/* Confirm */
.confirm-box { background: var(--bg-3); border: 1px solid rgba(255,255,255,.1); border-radius: var(--radius-lg); padding: 18px; width: 300px; max-width: calc(100vw - 32px); box-shadow: 0 24px 60px rgba(0,0,0,.5); }

/* Toast */
#toast-container { position: fixed; bottom: 20px; right: 20px; display: flex; flex-direction: column; gap: 8px; z-index: 9999; pointer-events: none; }
.toast { display: flex; align-items: center; gap: 8px; padding: 10px 14px; border-radius: var(--radius); background: var(--bg-3); border: 1px solid var(--border); font-size: 12px; color: var(--txt); box-shadow: 0 8px 32px rgba(0,0,0,.4); pointer-events: auto; animation: fadein .2s var(--ease); max-width: 300px; }
.toast.success { border-color: rgba(52,211,153,.3); }
.toast.error   { border-color: rgba(248,113,113,.3); }
.toast.info    { border-color: rgba(56,189,248,.3); }
.toast-icon    { flex-shrink: 0; }

/* ════════════════════════════════════════════════════
   ANIMATIONS
   ════════════════════════════════════════════════════ */
@keyframes fadein    { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
@keyframes slideDown { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }
@keyframes spin      { to { transform: rotate(360deg); } }
@keyframes shimmer   { 0% { background-position: -400px 0; } 100% { background-position: 400px 0; } }
@keyframes pulse     { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }

/* ════════════════════════════════════════════════════
   RESPONSIVE
   ════════════════════════════════════════════════════ */
@media (max-width: 1100px) {
  .tpl-grid { grid-template-columns: repeat(3, 1fr); }
  .col-l { width: 310px; }
  .phone { width: 264px; height: 498px; }
  .kpi-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
  #sidebar { position: fixed; top: 0; left: 0; transform: translateX(-100%); box-shadow: 4px 0 40px rgba(0,0,0,.6); }
  #sidebar.open { transform: translateX(0); }
  #sb-close { display: flex; }
  .tabs-bar    { display: none; }
  .tabs-mobile { display: flex; }
  .topbar-right .btn-txt { display: none; }
  .tpl-grid { grid-template-columns: repeat(2, 1fr); }
  .c-date, .c-sc { display: none; }
  .tbl-head .c-date, .tbl-head .c-sc { display: none; }

  /* ── Builder mobile : une seule colonne qui scroll librement ── */
  .builder-wrap { flex-direction: column; height: auto; overflow: visible; }
  #view-builder { overflow-y: auto !important; padding-bottom: 80px; }
  .col-l {
    width: 100%; flex-shrink: 0;
    overflow: visible !important;
    border-right: none;
    border-bottom: 1px solid var(--border);
  }
  /* Les deux divs internes de col-l scrollent librement sur mobile */
  .col-l > div:first-child {
    max-height: none !important;
    overflow-y: visible !important;
    border-bottom: 1px solid rgba(255,255,255,.05);
  }
  .col-l > div:last-child {
    flex: none !important;
    overflow-y: visible !important;
  }

  /* col-r (preview) : slide depuis la droite, inchangé */
  .col-r { position: fixed; inset: 0; z-index: 30; transform: translateX(100%); transition: transform .35s var(--ease); width: 100%; }
  .col-r.open { transform: translateX(0); }
  .prev-close { display: block; }

  /* FAB preview */
  .fab-preview { display: flex; bottom: 72px; }

  /* Barre Publier sticky en bas sur mobile */
  #mobile-publish-bar {
    display: flex !important;
    position: fixed; bottom: 0; left: 0; right: 0; z-index: 20;
    background: var(--bg-1);
    border-top: 1px solid var(--border);
    padding: 10px 16px;
    gap: 8px; align-items: center;
    box-shadow: 0 -4px 20px rgba(0,0,0,.4);
  }

  .phone { width: min(260px, 82vw); height: min(488px, 72dvh); }
  .pad { padding: 14px; }
  .g2  { grid-template-columns: 1fr; }
  .kpi-grid { grid-template-columns: repeat(2, 1fr); gap: 8px; }
  .resp-detail-grid { grid-template-columns: 1fr; }
  .resp-sidebar { border-right: none; border-bottom: 1px solid var(--border); max-height: 240px; }
  .modal-xl { width: min(100vw - 16px, 900px); }
}

@media (max-width: 400px) {
  .kpi-grid { grid-template-columns: 1fr 1fr; }
  .tpl-grid { grid-template-columns: 1fr 1fr; gap: 6px; }
  .phone    { width: 88vw; height: 65dvh; }
}
</style>
</head>
<body>

<div id="toast-container"></div>

<!-- Overlay sidebar -->
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<div id="app">

  <!-- ══════════════════════════════════
       SIDEBAR
       ══════════════════════════════════ -->
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
      <a href="/relances" class="sb-link" id="nav-relances">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 12a9 9 0 1 1-3.5-7.1M21 4v6h-6"/></svg>
        Relances
        <span class="sb-badge" id="sb-relances-badge">—</span>
      </a>
      <p class="sb-label">Outils</p>
      <a href="/form" class="sb-link active">
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

  <!-- ══════════════════════════════════
       MAIN
       ══════════════════════════════════ -->
  <div id="main">

    <!-- Topbar -->
    <header id="topbar">
      <div class="topbar-left">
        <button id="hamburger" onclick="openSidebar()">
          <svg viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <span class="topbar-title">Formulaires</span>
        <!-- Tabs desktop -->
        <div class="tabs-bar" id="tabs-desktop" role="tablist">
          <button class="tab active" data-view="list" onclick="goView('list')">
            <svg style="width:11px;height:11px;stroke:currentColor;fill:none;" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
            Mes formulaires
          </button>
          <button class="tab" data-view="builder" onclick="goView('builder')">
            <svg style="width:11px;height:11px;stroke:currentColor;fill:none;" viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><path d="M14 17h7m-3.5-3.5v7"/></svg>
            Builder
          </button>
          <button class="tab" data-view="responses" onclick="goView('responses')">
            <svg style="width:11px;height:11px;stroke:currentColor;fill:none;" viewBox="0 0 24 24" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            Réponses
          </button>
        </div>
      </div>
      <div class="topbar-right">
        <!-- UI save (builder) -->
        <div class="save-ui" id="save-ui" style="display:none;" aria-live="polite">
          <span class="save-dot" id="save-dot"></span>
          <span id="save-txt">Sauvegardé</span>
        </div>
        <!-- Undo/redo (builder) -->
        <div class="undo-ui" id="undo-ui" style="display:none;">
          <button class="btn-icon" id="ubtn" onclick="undo()" disabled title="Annuler (⌘Z)">
            <svg viewBox="0 0 24 24" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3"/></svg>
          </button>
          <button class="btn-icon" id="rbtn" onclick="redo()" disabled title="Rétablir (⌘⇧Z)">
            <svg viewBox="0 0 24 24" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-3"/></svg>
          </button>
        </div>
        <button class="btn-primary" onclick="newForm();goView('builder')">
          <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
          <span class="btn-txt">Nouveau formulaire</span>
        </button>
      </div>
    </header>

    <!-- Tabs mobile -->
    <div class="tabs-mobile" id="tabs-mobile" role="tablist">
      <button class="tab active" data-view="list"      onclick="goView('list')">Formulaires</button>
      <button class="tab"        data-view="builder"   onclick="goView('builder')">Builder</button>
      <button class="tab"        data-view="responses" onclick="goView('responses')">Réponses</button>
    </div>

    <!-- ══════════════════════════════════
         VUE LISTE
         ══════════════════════════════════ -->
    <div id="view-list" class="view scroll-y pad">

      <div class="kpi-grid" style="margin-bottom:20px">
        <div class="card kpi" id="kpi-actifs-card" onclick="openFormsListModal()" style="cursor:pointer;transition:border-color .15s" onmouseover="this.style.borderColor='rgba(56,189,248,.3)'" onmouseout="this.style.borderColor=''">
          <p class="kpi-l">Formulaires actifs <span id="kpi-mobile-hint" style="display:none;font-size:9px;color:var(--sky);margin-left:4px">· voir liste →</span></p>
          <p class="kpi-v" id="kpi-total">—</p>
        </div>
        <div class="card kpi"><p class="kpi-l">Réponses totales</p><p class="kpi-v" id="kpi-reponses">—</p></div>
        <div class="card kpi"><p class="kpi-l">Complétion moy.</p><p class="kpi-v" id="kpi-completion" style="color:var(--green)">—</p></div>
        <div class="card kpi"><p class="kpi-l">Score moy. quiz</p><p class="kpi-v" id="kpi-score" style="color:var(--violet)">—</p></div>
      </div>

      <p class="sec-ttl" style="margin-bottom:10px">Templates</p>
      <div class="tpl-grid" style="margin-bottom:20px">
        <div class="card tpl" onclick="loadTpl('inscription');goView('builder')">
          <div class="tpl-ico" style="background:var(--sky-bg)"><svg width="12" height="12" fill="none" stroke="var(--sky)" viewBox="0 0 24 24" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
          <p class="tpl-n">Inscription</p><p class="tpl-c">/start</p>
        </div>
        <div class="card tpl" onclick="loadTpl('sondage');goView('builder')">
          <div class="tpl-ico" style="background:var(--amber2-bg)"><svg width="12" height="12" fill="none" stroke="var(--amber2)" viewBox="0 0 24 24" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
          <p class="tpl-n">Sondage</p><p class="tpl-c">/sondage</p>
        </div>
        <div class="card tpl" onclick="loadTpl('quiz');goView('builder')">
          <div class="tpl-ico" style="background:var(--violet-bg)"><svg width="12" height="12" fill="none" stroke="var(--violet)" viewBox="0 0 24 24" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>
          <p class="tpl-n">Quiz / QCM</p><p class="tpl-c">/quiz</p>
        </div>
        <div class="card tpl" onclick="loadTpl('journal');goView('builder')">
          <div class="tpl-ico" style="background:var(--teal-bg)"><svg width="12" height="12" fill="none" stroke="var(--teal)" viewBox="0 0 24 24" stroke-width="1.5"><path d="M3 3v18h18"/><path d="m7 16 4-4 4 4 4-6"/></svg></div>
          <p class="tpl-n">Journal trade</p><p class="tpl-c">/journal</p>
        </div>
        <div class="card tpl" onclick="loadTpl('temoignage');goView('builder')">
          <div class="tpl-ico" style="background:var(--pink-bg)"><svg width="12" height="12" fill="none" stroke="var(--pink)" viewBox="0 0 24 24" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
          <p class="tpl-n">Témoignage</p><p class="tpl-c">/temoignage</p>
        </div>
      </div>

      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:8px">
        <p class="sec-ttl">Formulaires existants</p>
        <button class="btn-ghost" onclick="loadFormsList()">
          <svg viewBox="0 0 24 24" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3"/></svg>
          Actualiser
        </button>
      </div>

      <div class="card" style="overflow:hidden">
        <div style="overflow-x:auto">
        <div class="tbl-head" style="min-width:640px">
          <span style="flex:1;min-width:160px">Nom & commande</span>
          <span class="c-type">Type</span>
          <span class="c-num">Champs</span>
          <span class="c-num">Rép.</span>
          <span class="c-comp">Complétion</span>
          <span class="c-stat">Statut</span>
          <span style="width:90px"></span>
        </div>
        <div id="forms-tbody" style="min-width:640px">
          <div class="tbl-row"><div style="flex:1"><div class="skeleton" style="height:12px;width:55%;margin-bottom:5px"></div><div class="skeleton" style="height:10px;width:35%"></div></div></div>
          <div class="tbl-row"><div style="flex:1"><div class="skeleton" style="height:12px;width:45%;margin-bottom:5px"></div><div class="skeleton" style="height:10px;width:30%"></div></div></div>
          <div class="tbl-row"><div style="flex:1"><div class="skeleton" style="height:12px;width:60%;margin-bottom:5px"></div><div class="skeleton" style="height:10px;width:40%"></div></div></div>
        </div>
        </div>
      </div>

      <div style="margin-top:16px;display:flex;gap:6px;flex-wrap:wrap;align-items:center">
        <span style="font-size:10px;color:var(--txt-5)">Raccourcis :</span>
        <span style="font-size:10px;color:var(--txt-4)"><kbd style="background:rgba(255,255,255,.06);padding:1px 5px;border-radius:3px;font-family:'Geist Mono',monospace">⌘S</kbd> Publier</span>
        <span style="font-size:10px;color:var(--txt-4)"><kbd style="background:rgba(255,255,255,.06);padding:1px 5px;border-radius:3px;font-family:'Geist Mono',monospace">⌘Z</kbd> Annuler</span>
        <span style="font-size:10px;color:var(--txt-4)"><kbd style="background:rgba(255,255,255,.06);padding:1px 5px;border-radius:3px;font-family:'Geist Mono',monospace">⌘↩</kbd> Palette</span>
        <span style="font-size:10px;color:var(--txt-4)"><kbd style="background:rgba(255,255,255,.06);padding:1px 5px;border-radius:3px;font-family:'Geist Mono',monospace">Esc</kbd> Fermer</span>
      </div>
    </div>

    <!-- ══════════════════════════════════
         VUE BUILDER
         ══════════════════════════════════ -->
    <div id="view-builder" class="view" style="display:none;overflow:hidden">
      <div class="builder-wrap">

        <!-- COL LEFT -->
        <div class="col-l">
          <div style="flex-shrink:0;max-height:46%;border-bottom:1px solid rgba(255,255,255,.05);padding:12px 14px;overflow-y:auto">
            <p class="lbl-sec" style="margin-bottom:10px">Configuration</p>

            <div class="g2 mb8">
              <div>
                <p class="lbl">Nom du formulaire *</p>
                <input class="inp" id="f-name" placeholder="Ex: Quiz Forex" oninput="scheduleSave();updateMeta()" autocomplete="off">
              </div>
              <div>
                <p class="lbl">Commande Telegram *</p>
                <div style="display:flex;align-items:center;gap:4px">
                  <span style="color:var(--violet);font-family:'Geist Mono',monospace;font-size:14px;flex-shrink:0">/</span>
                  <input class="inp" id="f-cmd" placeholder="quiz" style="font-family:'Geist Mono',monospace;font-size:12px" oninput="sanitizeCmd(this)" autocomplete="off">
                </div>
              </div>
            </div>

            <div class="g2 mb8">
              <div>
                <p class="lbl">Type de formulaire</p>
                <select class="inp" id="f-type" onchange="onTypeChange()">
                  <option value="inscription">Inscription</option>
                  <option value="sondage">Sondage</option>
                  <option value="quiz">Quiz / QCM corrigé</option>
                  <option value="journal">Journal de trading</option>
                  <option value="temoignage">Témoignage</option>
                  <option value="custom">Personnalisé</option>
                </select>
              </div>
              <div>
                <p class="lbl">Déclencheur</p>
                <select class="inp" id="f-trigger" onchange="onTriggerChange();scheduleSave()">
                  <option value="Commande manuelle">Commande manuelle</option>
                  <option value="À l'inscription (/start)">À l'inscription (/start)</option>
                  <option value="Planifié (date/heure)">Planifié (date/heure)</option>
                  <option value="Automatique (condition)">Automatique (condition)</option>
                </select>
                <div class="trigger-date-wrap" id="trigger-date-wrap">
                  <p class="lbl" style="margin-top:6px">Date & heure d'envoi</p>
                  <input class="inp" type="datetime-local" id="f-trigger-value" oninput="scheduleSave()" style="font-family:'Geist Mono',monospace;font-size:11px">
                  <p style="font-size:10px;color:var(--txt-4);margin-top:3px">Ou cron : "lundi 09:00"</p>
                  <input class="inp" type="text" id="f-trigger-cron" placeholder="lundi 09:00" style="font-family:'Geist Mono',monospace;font-size:11px;margin-top:4px" oninput="scheduleSave()">
                  <p class="lbl" style="margin-top:6px">Catégorie cible</p>
                  <select class="inp" id="f-target-cat" onchange="scheduleSave()">
                    <option value="">Tous les utilisateurs</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="mb8">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px">
                <p class="lbl" style="margin-bottom:0">Intro <span style="color:var(--green);font-size:9px;margin-left:4px">● premier message</span></p>
                <div>
                  <span class="vchip" onclick="insertVar('f-intro','+prenom')">+prenom</span>
                  <span class="vchip" onclick="insertVar('f-intro','+date')">+date</span>
                </div>
              </div>
              <textarea class="inp" id="f-intro" style="min-height:48px" placeholder="Bonjour +prenom ! 👋&#10;&#10;Prêt pour le quiz ?" oninput="scheduleSave();renderStep(curStep)"></textarea>
            </div>

            <div class="mb8">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px">
                <p class="lbl" style="margin-bottom:0">Outro <span style="color:var(--violet);font-size:9px;margin-left:4px">● après la dernière question</span></p>
                <div>
                  <span class="vchip" onclick="insertVar('f-outro','+prenom')">+prenom</span>
                  <span class="vchip" onclick="insertVar('f-outro','+score')">+score</span>
                  <span class="vchip" onclick="insertVar('f-outro','+total')">+total</span>
                </div>
              </div>
              <textarea class="inp" id="f-outro" style="min-height:40px" placeholder="✅ Merci +prenom !" oninput="scheduleSave();renderStep(curStep)"></textarea>
            </div>

            <div id="quiz-cfg" style="display:none;background:rgba(167,139,250,.05);border:1px solid rgba(167,139,250,.15);border-radius:8px;padding:10px;margin-bottom:8px">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
                <p style="font-size:12px;color:#d4d4d8;font-weight:500">⚡ Scoring automatique</p>
                <button class="toggle on" onclick="this.classList.toggle('on')"></button>
              </div>
              <div class="g3 mb8">
                <div><p class="lbl">Pts correct</p><input class="inp" type="number" min="0" value="10" id="q-pts" oninput="scheduleSave()"></div>
                <div><p class="lbl">Pénalité</p><input class="inp" type="number" min="0" value="0" id="q-penalty" oninput="scheduleSave()"></div>
                <div><p class="lbl">Score max</p><input class="inp" type="number" min="0" value="50" id="q-max" oninput="scheduleSave()"></div>
              </div>
              <div class="tog-row">
                <p style="font-size:11px;color:var(--txt-2)">Correction immédiate</p>
                <button class="toggle on" onclick="this.classList.toggle('on')"></button>
              </div>
            </div>

            <div style="padding-top:10px;border-top:1px solid rgba(255,255,255,.05)">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                <p class="lbl-sec">Actions après soumission</p>
                <button class="btn-ghost" onclick="addAction()">+ Action</button>
              </div>
              <div id="end-actions">
                <div class="act-row">
                  <svg width="10" height="10" fill="none" stroke="var(--sky)" viewBox="0 0 24 24" stroke-width="1.5" style="flex-shrink:0"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg>
                  <select class="inp" style="font-size:11px;padding:3px 6px;flex:0 0 120px">
                    <option>Ajouter catégorie</option><option>Envoyer message</option><option>Notifier admin</option><option>Broadcast</option>
                  </select>
                  <input class="inp" type="text" value="Prospect Inscrit" style="font-size:11px;padding:3px 6px;flex:1">
                  <button class="btn-icon del" onclick="this.closest('.act-row').remove()"><svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
                </div>
              </div>
            </div>

            <div style="padding-top:10px;border-top:1px solid rgba(255,255,255,.05);margin-top:10px">
              <p class="lbl-sec" style="margin-bottom:8px">Options</p>
              <div style="display:flex;flex-direction:column;gap:8px">
                <div class="tog-row"><div><p class="opt-p">Permettre de reprendre</p><p class="opt-sub">Continue là où il s'est arrêté</p></div><button class="toggle on" id="opt-resume" onclick="this.classList.toggle('on')"></button></div>
                <div class="tog-row"><div><p class="opt-p">Barre de progression</p><p class="opt-sub">Étape X/Y visible dans le bot</p></div><button class="toggle on" id="opt-progress" onclick="this.classList.toggle('on')"></button></div>
                <div class="tog-row"><div><p class="opt-p">Une réponse par utilisateur</p></div><button class="toggle on" id="opt-one-per-user" onclick="this.classList.toggle('on')"></button></div>
                <div class="tog-row"><div><p class="opt-p">Notifier l'admin</p></div><button class="toggle" id="opt-notify" onclick="this.classList.toggle('on')"></button></div>
              </div>
            </div>
          </div>

          <!-- CHAMPS -->
          <div style="flex:1;padding:12px 14px;overflow-y:auto">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
              <p class="lbl-sec">Questions / Champs <span id="field-count" style="color:var(--txt-4);font-weight:400;text-transform:none;letter-spacing:0;font-size:10px">(0)</span></p>
              <div style="display:flex;gap:4px">
                <button class="btn-ghost" onclick="collapseAll()">Replier</button>
                <button class="btn-ghost" onclick="togglePal()" id="pal-btn" title="⌘↩">
                  <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>Ajouter
                </button>
              </div>
            </div>

            <div id="palette" style="display:none;margin-bottom:10px">
              <div class="palette-box">
                <p class="pal-s">Réponse texte libre</p>
                <div class="pal-grid">
                  <button class="pal-btn" onclick="addF('text');hidePal()"><svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M4 6h16M4 12h8"/></svg>Texte court</button>
                  <button class="pal-btn" onclick="addF('long');hidePal()"><svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Texte long</button>
                  <button class="pal-btn" onclick="addF('email');hidePal()"><svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>Email</button>
                  <button class="pal-btn" onclick="addF('number');hidePal()"><svg viewBox="0 0 24 24" stroke-width="1.5"><line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/></svg>Nombre</button>
                </div>
                <p class="pal-s">Boutons Telegram</p>
                <div class="pal-grid">
                  <button class="pal-btn" onclick="addF('qcm');hidePal()">QCM — 1 réponse</button>
                  <button class="pal-btn" onclick="addF('multi');hidePal()">QCM — plusieurs</button>
                  <button class="pal-btn" onclick="addF('oui_non');hidePal()">Oui / Non</button>
                  <button class="pal-btn" onclick="addF('note5');hidePal()">Note 1–5 ⭐</button>
                  <button class="pal-btn" onclick="addF('nps');hidePal()">NPS 0–10</button>
                </div>
                <p class="pal-s">Fichiers</p>
                <div class="pal-grid">
                  <button class="pal-btn" onclick="addF('photo');hidePal()">Photo</button>
                  <button class="pal-btn" onclick="addF('video');hidePal()">Vidéo</button>
                  <button class="pal-btn" onclick="addF('audio');hidePal()">Vocal</button>
                  <button class="pal-btn" onclick="addF('document');hidePal()">Document</button>
                </div>
                <p class="pal-s">Spécial</p>
                <div class="pal-grid">
                  <button class="pal-btn" onclick="addF('contact');hidePal()">Contact tél.</button>
                  <button class="pal-btn" onclick="addF('info');hidePal()">Info (sans réponse)</button>
                </div>
              </div>
              <button class="btn-ghost" style="width:100%;justify-content:center;margin-top:5px" onclick="hidePal()">Fermer la palette</button>
            </div>

            <div id="fc"></div>

            <div style="padding-top:12px;border-top:1px solid rgba(255,255,255,.05);margin-top:8px">
              <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
                <div>
                  <p class="lbl-sec" style="margin-bottom:1px">Logique conditionnelle</p>
                  <p style="font-size:10px;color:var(--txt-5)">Saut, catégorie ou message selon les réponses</p>
                </div>
                <button class="btn-ghost" onclick="addCond()">+ Règle</button>
              </div>
              <div id="conds"></div>
            </div>
            <div style="height:40px"></div>
          </div>
        </div>

        <!-- FAB preview mobile -->
        <button class="fab-preview" id="fab-prev" onclick="togglePreview()">
          <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/></svg>
          Preview
        </button>

        <!-- COL RIGHT (preview) -->
        <div class="col-r" id="col-r">
          <button class="prev-close" onclick="togglePreview()">✕ Fermer la preview</button>

          <div class="prev-bar">
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
              <p style="font-size:13px;font-weight:500;color:white">Simulation Telegram</p>
              <span class="badge-teal" style="font-size:10px;padding:2px 7px;border-radius:99px;background:var(--teal-bg);color:var(--teal);">Live</span>
              <span id="cmd-pill" class="cmd-pill">/quiz</span>
            </div>
            <div style="display:flex;align-items:center;gap:6px;flex-shrink:0">
              <div id="step-dots" style="display:flex;gap:4px;align-items:center"></div>
              <button class="btn-ghost" onclick="prevStep()">← Préc.</button>
              <button class="btn-sky"   onclick="nextStep()">Suivant →</button>
            </div>
          </div>

          <div class="prev-body">
            <div class="phone">
              <div class="tg">
                <div class="tg-status"><span>9:41</span><span>WiFi ■■■</span></div>
                <div class="tg-head">
                  <div class="tg-ava">
                    <svg width="13" height="13" fill="white" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.94 8.19l-2.02 9.52c-.14.66-.54.82-1.09.51l-3-2.21-1.45 1.39c-.16.16-.3.3-.61.3l.22-3.1 5.6-5.06c.24-.22-.06-.34-.38-.12L7.03 14.5 4.06 13.6c-.65-.2-.66-.65.14-.96l11.65-4.5c.54-.2 1.01.13.09 2.05z"/></svg>
                  </div>
                  <div style="flex:1">
                    <p id="prev-name" style="font-size:11px;font-weight:500;color:#e2e8f0">Felipe Bot</p>
                    <p style="font-size:9px;color:#4a6478">bot · en ligne</p>
                  </div>
                  <div style="min-width:55px">
                    <div style="height:2px;background:rgba(255,255,255,.06);border-radius:99px;overflow:hidden"><div id="prev-prog" style="height:100%;border-radius:99px;background:var(--sky);width:0%;transition:width .4s;"></div></div>
                    <p id="prev-prog-txt" style="font-size:8px;color:#4a6478;text-align:right;margin-top:2px"></p>
                  </div>
                </div>
                <div class="tg-msgs" id="tg-feed"></div>
                <div class="tg-rk" id="tg-rk" style="display:none"></div>
                <div class="tg-bar">
                  <div class="tg-inp" id="tg-hint">Tape ta réponse...</div>
                  <div class="tg-send" onclick="nextStep()">
                    <svg width="13" height="13" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="prev-foot">
            <span id="step-info" style="font-size:11px;color:var(--txt-4)">Étape 1 / 1</span>
            <div style="display:flex;gap:6px">
              <button class="btn-ghost" onclick="resetPrev()">
                <svg viewBox="0 0 24 24" stroke-width="1.5"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3"/></svg>Reset
              </button>
              <button class="btn-sky" onclick="publish()" title="⌘S">
                <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13"/></svg>
                Publier
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Barre Publier sticky — mobile uniquement -->
      <div id="mobile-publish-bar" style="display:none">
        <div style="flex:1;min-width:0">
          <p id="mpb-name" style="font-size:12px;font-weight:500;color:white;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">Sans titre</p>
          <div style="display:flex;align-items:center;gap:5px;margin-top:2px">
            <span id="mpb-dot" style="width:5px;height:5px;border-radius:50%;background:var(--green);flex-shrink:0;display:inline-block"></span>
            <span id="mpb-status" style="font-size:10px;color:var(--txt-4)">Sauvegardé</span>
          </div>
        </div>
        <button class="btn-ghost" onclick="togglePreview()" style="gap:5px;flex-shrink:0;font-size:12px">
          <svg viewBox="0 0 24 24" stroke-width="1.5" style="width:12px;height:12px;stroke:currentColor;fill:none"><rect x="5" y="2" width="14" height="20" rx="2"/></svg>
          Preview
        </button>
        <button onclick="publish()"
                style="display:flex;align-items:center;gap:6px;padding:9px 18px;
                       background:var(--green);border:none;border-radius:var(--radius);
                       color:#052e16;font-size:13px;font-weight:600;font-family:inherit;
                       cursor:pointer;flex-shrink:0;transition:all .15s;
                       box-shadow:0 0 20px rgba(52,211,153,.25)">
          <svg viewBox="0 0 24 24" stroke-width="2.5" style="width:13px;height:13px;stroke:currentColor;fill:none"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
          Publier
        </button>
      </div>

    </div>

    <!-- ══════════════════════════════════
         VUE RÉPONSES
         ══════════════════════════════════ -->
    <div id="view-responses" class="view scroll-y pad" style="display:none">

      <div class="responses-filters">
        <select class="inp" id="resp-form-sel" style="width:260px;max-width:100%" onchange="loadResponsesForForm(this.value)">
          <option>Chargement...</option>
        </select>
        <select class="inp" id="resp-status-sel" style="width:130px" onchange="filterResponses(this.value)">
          <option value="">Toutes</option>
          <option value="completed">Complètes</option>
          <option value="abandoned">Incomplètes</option>
        </select>
        <button class="btn-ghost" style="margin-left:auto" onclick="exportCSV()">
          <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Exporter CSV
        </button>
      </div>

      <div class="kpi-grid" style="margin-bottom:16px">
        <div class="card kpi"><p class="kpi-l">Réponses</p><p class="kpi-v" id="r-total">—</p></div>
        <div class="card kpi"><p class="kpi-l">Complétées</p><p class="kpi-v" id="r-completed" style="color:var(--green)">—</p></div>
        <div class="card kpi"><p class="kpi-l">Score moyen</p><p class="kpi-v" id="r-score" style="color:var(--violet)">—</p></div>
        <div class="card kpi"><p class="kpi-l">Taux complétion</p><p class="kpi-v" id="r-time" style="color:var(--sky)">—</p></div>
      </div>

      <div class="card" style="overflow:hidden">
        <div class="tbl-head">
          <input type="checkbox" id="check-all" style="accent-color:var(--sky);flex-shrink:0" onchange="toggleAllChecks(this.checked)">
          <span style="flex:1">Membre</span>
          <span class="c-num">Rép.</span>
          <span class="c-sc">Score</span>
          <span style="width:90px;font-size:11px;color:var(--txt-4)">Soumis le</span>
          <span style="width:30px"></span>
        </div>
        <div id="resp-tbody">
          <div class="tbl-row"><div style="flex:1"><div class="skeleton" style="height:12px;width:40%"></div></div></div>
          <div class="tbl-row"><div style="flex:1"><div class="skeleton" style="height:12px;width:50%"></div></div></div>
        </div>
      </div>
    </div>

  </div><!-- /main -->
</div><!-- /app -->


<!-- ══════════════════════════════════════════════
     MODAL DÉTAIL RÉPONSE
     ══════════════════════════════════════════════ -->
<div class="modal-overlay" id="m-detail" onclick="if(event.target===this)closeModal('m-detail')">
  <div class="modal modal-xl">
    <div class="modal-head">
      <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0">
        <div class="av-sm resp-av" id="det-av" style="width:30px;height:30px;font-size:11px">—</div>
        <div style="min-width:0">
          <p style="font-size:13px;font-weight:500;color:white" id="det-name">Chargement…</p>
          <p style="font-size:11px;color:var(--txt-4);margin-top:1px" id="det-meta">—</p>
        </div>
        <div id="det-score-pill" style="margin-left:auto;flex-shrink:0"></div>
      </div>
      <button class="btn-icon" onclick="closeModal('m-detail')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body" style="padding:0;flex:1;overflow:hidden;">
      <div class="resp-detail-grid">
        <div class="resp-sidebar">
          <div class="resp-sidebar-head">
            <p class="resp-sidebar-title">Participants</p>
            <input class="resp-search" id="resp-user-search" type="text" placeholder="Rechercher…" oninput="filterUserList(this.value)">
          </div>
          <div id="resp-user-list">
            <div class="skeleton" style="height:50px;margin:4px 10px;border-radius:6px"></div>
            <div class="skeleton" style="height:50px;margin:4px 10px;border-radius:6px"></div>
            <div class="skeleton" style="height:50px;margin:4px 10px;border-radius:6px"></div>
          </div>
        </div>
        <div class="resp-content">
          <div class="resp-content-head" id="det-content-head" style="display:none">
            <div>
              <p style="font-size:12px;color:var(--txt);font-weight:500" id="det-content-name">—</p>
              <p style="font-size:10px;color:var(--txt-4);margin-top:2px" id="det-content-date">—</p>
            </div>
          </div>
          <div class="resp-content-body" id="resp-detail-body">
            <div class="resp-empty-state">
              <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
              <p style="font-size:12px">Sélectionne un participant</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="modal-foot" style="justify-content:space-between">
      <span id="det-total-count" style="font-size:11px;color:var(--txt-4)"></span>
      <button class="btn-ghost" onclick="closeModal('m-detail')">Fermer</button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════════
     MODAL LISTE FORMULAIRES (mobile + accès rapide)
     ══════════════════════════════════════════════ -->
<div class="modal-overlay" id="modal-forms-list"
     onclick="if(event.target===this)closeMobileList()">
  <div class="modal" style="width:min(560px,100%);max-height:85dvh">
    <div class="modal-head">
      <div>
        <h2>Mes formulaires</h2>
        <p style="font-size:11px;color:var(--txt-4);margin-top:2px" id="mfl-count">—</p>
      </div>
      <div style="display:flex;align-items:center;gap:6px">
        <button class="btn-ghost" onclick="loadFormsList();renderMobileList()" style="font-size:11px">
          <svg viewBox="0 0 24 24" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3"/></svg>
          Actualiser
        </button>
        <button class="btn-icon" onclick="closeMobileList()">
          <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>
    </div>

    <!-- Recherche rapide -->
    <div style="padding:10px 18px;border-bottom:1px solid var(--border)">
      <div style="position:relative">
        <svg style="position:absolute;left:9px;top:50%;transform:translateY(-50%);width:12px;height:12px;stroke:var(--txt-5);fill:none;" viewBox="0 0 24 24" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" class="inp" placeholder="Rechercher un formulaire…"
               style="padding-left:28px;font-size:12px"
               oninput="filterMobileList(this.value)">
      </div>
    </div>

    <!-- Liste scrollable -->
    <div id="mfl-list" style="flex:1;overflow-y:auto;padding:8px">
      <div class="empty-state"><div class="spinner"></div></div>
    </div>

    <div class="modal-foot" style="justify-content:space-between">
      <button class="btn-ghost" onclick="closeMobileList()">Fermer</button>
      <button class="btn-primary" onclick="closeMobileList();newForm();goView('builder')">
        <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
        Nouveau
      </button>
    </div>
  </div>
</div>

<style>
/* ── Carte formulaire dans le modal mobile ── */
.mfl-item {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 12px; border-radius: var(--radius);
  border: 1px solid var(--border);
  background: rgba(255,255,255,.02);
  margin-bottom: 6px; cursor: pointer;
  transition: border-color .12s, background .12s;
}
.mfl-item:hover { border-color: rgba(255,255,255,.14); background: rgba(255,255,255,.04); }
.mfl-ico {
  width: 36px; height: 36px; border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  font-size: 16px; flex-shrink: 0;
}
.mfl-info { flex: 1; min-width: 0; overflow: hidden; }
.mfl-name { font-size: 13px; font-weight: 500; color: var(--txt); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.mfl-meta { font-size: 10px; color: var(--txt-4); margin-top: 2px; font-family: 'Geist Mono', monospace; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 100%; }
.mfl-actions { display: flex; gap: 4px; flex-shrink: 0; }

/* Sur mobile, afficher hint sur KPI */
@media (max-width: 768px) {
  #kpi-mobile-hint { display: inline !important; }
}
</style>
<div id="confirm-overlay" onclick="if(event.target===this)this.style.display='none'"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.7);z-index:500;align-items:center;justify-content:center;padding:16px;backdrop-filter:blur(4px);">
  <div class="confirm-box">
    <p style="font-size:13px;font-weight:500;color:var(--txt);margin-bottom:8px">Confirmer</p>
    <p id="confirm-msg" style="font-size:12px;color:var(--txt-2);margin-bottom:16px;line-height:1.5">—</p>
    <div style="display:flex;justify-content:flex-end;gap:7px">
      <button class="btn-ghost" id="confirm-cancel">Annuler</button>
      <button class="btn-danger" id="confirm-ok">Confirmer</button>
    </div>
  </div>
</div>


<script>
'use strict';

/* ════════════════════════════════════════════════════
   API BASE
   ════════════════════════════════════════════════════ */
const API_BASE = window.API_BASE || 'https://fdkvip.com/forms';

async function _req(method, path, body = null, isForm = false) {
  const opts = { method, headers: isForm ? {} : { 'Content-Type': 'application/json' } };
  if (body) opts.body = isForm ? body : JSON.stringify(body);
  try {
    const res  = await fetch(API_BASE + path, opts);
    const data = await res.json();
    if (!res.ok) throw new Error(data.detail || 'Erreur API');
    return data;
  } catch (err) { console.error('[forms_api]', method, path, err); throw err; }
}

const apiGetForms        = (actif = true) => _req('GET', '?actif_only=' + actif);
const apiGetForm         = (id)           => _req('GET', '/' + id);
const apiSaveForm        = (payload)      => _req('POST', '', payload);
const apiDeleteForm      = (id)           => _req('DELETE', '/' + id);
const apiActivateForm    = (id)           => _req('POST', '/' + id + '/activate');
const apiGetFormStats    = (id)           => _req('GET', '/' + id + '/stats');
const apiGetResponses    = (id, lim=9999) => _req('GET', '/' + id + '/responses?limit=' + lim);
const apiGetUserResponses= (fid, tid)     => _req('GET', '/' + fid + '/responses/' + tid);

/* ════════════════════════════════════════════════════
   STATE
   ════════════════════════════════════════════════════ */
let curView = 'list', curStep = 0, fCtr = 0;
let fields = [], hist = [], hIdx = -1, saveTimer = null, sortable = null;
let currentFormId = null, formsList = [];
let _detailFormId = null, _detailAllUsers = [], _detailCurrentId = null;
let _allResponses = [];

/* ════════════════════════════════════════════════════
   FIELD TYPES
   ════════════════════════════════════════════════════ */
const TM = {
  text:    { label:'Texte court',      color:'#38bdf8', bg:'rgba(56,189,248,.12)',  api:'Texte libre',                     opts:false, media:false, hasAns:false },
  long:    { label:'Texte long',       color:'#38bdf8', bg:'rgba(56,189,248,.12)',  api:'Texte libre',                     opts:false, media:false, hasAns:false },
  email:   { label:'Email',            color:'#34d399', bg:'rgba(52,211,153,.12)',  api:'Texte libre (email)',              opts:false, media:false, hasAns:false },
  number:  { label:'Nombre',           color:'#fbbf24', bg:'rgba(251,191,36,.12)',  api:'Texte libre (nombre)',             opts:false, media:false, hasAns:true  },
  qcm:     { label:'QCM — 1 réponse', color:'#fb923c', bg:'rgba(251,146,60,.12)',  api:'InlineKeyboardMarkup',             opts:true,  media:false, hasAns:true  },
  multi:   { label:'QCM — plusieurs', color:'#fb923c', bg:'rgba(251,146,60,.12)',  api:'InlineKeyboardMarkup + Valider',   opts:true,  media:false, hasAns:true  },
  oui_non: { label:'Oui / Non',        color:'#fb923c', bg:'rgba(251,146,60,.12)',  api:'InlineKeyboard [Oui][Non]',        opts:false, media:false, hasAns:true  },
  note5:   { label:'Note 1–5',         color:'#fbbf24', bg:'rgba(251,191,36,.12)',  api:'InlineKeyboard [⭐1]…[⭐5]',      opts:false, media:false, hasAns:false },
  nps:     { label:'NPS 0–10',         color:'#2dd4bf', bg:'rgba(45,212,191,.12)',  api:'InlineKeyboard [0]…[10]',         opts:false, media:false, hasAns:false },
  photo:   { label:'Photo',            color:'#a78bfa', bg:'rgba(167,139,250,.12)', api:'message_handler (photo)',          opts:false, media:true,  hasAns:false },
  video:   { label:'Vidéo',           color:'#a78bfa', bg:'rgba(167,139,250,.12)', api:'message_handler (video)',          opts:false, media:true,  hasAns:false },
  audio:   { label:'Message vocal',    color:'#a78bfa', bg:'rgba(167,139,250,.12)', api:'message_handler (voice)',          opts:false, media:true,  hasAns:false },
  document:{ label:'Document',         color:'#f472b6', bg:'rgba(244,114,182,.12)', api:'message_handler (document)',       opts:false, media:true,  hasAns:false },
  contact: { label:'Contact (tél.)',   color:'#2dd4bf', bg:'rgba(45,212,191,.12)',  api:'KeyboardButton (request_contact)', opts:false, media:false, hasAns:false },
  info:    { label:'Message info',     color:'#71717a', bg:'rgba(113,113,122,.12)', api:'sendMessage — pas de réponse',     opts:false, media:false, hasAns:false },
};

const TYPE_LABELS = {
  text:'Texte', long:'Texte long', email:'Email', number:'Nombre',
  qcm:'QCM', multi:'Multi-choix', oui_non:'Oui/Non',
  note5:'Note ⭐', nps:'NPS', photo:'Photo', video:'Vidéo',
  audio:'Vocal', document:'Document', contact:'Contact', info:'Info'
};

const ICO_SVG = {
  text:    '<svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M4 6h16M4 12h8"/></svg>',
  long:    '<svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>',
  email:   '<svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>',
  number:  '<svg viewBox="0 0 24 24" stroke-width="1.5"><line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/></svg>',
  qcm:     '<svg viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="4"/></svg>',
  multi:   '<svg viewBox="0 0 24 24" stroke-width="1.5"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>',
  oui_non: '<svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
  note5:   '<svg viewBox="0 0 24 24" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>',
  nps:     '<svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M3 3v18h18"/><path d="m7 16 4-4 4 4 4-6"/></svg>',
  photo:   '<svg viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>',
  video:   '<svg viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 8-6 4 6 4V8z"/><rect x="2" y="6" width="14" height="12" rx="2"/></svg>',
  audio:   '<svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/></svg>',
  document:'<svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>',
  contact: '<svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 13z"/></svg>',
  info:    '<svg viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
};

/* ════════════════════════════════════════════════════
   UTILS
   ════════════════════════════════════════════════════ */
function esc(s) {
  return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}
function $(id) { return document.getElementById(id); }
function el(tag, props, ...children) {
  const e = document.createElement(tag);
  Object.assign(e, props);
  children.forEach(c => typeof c === 'string' ? e.insertAdjacentHTML('beforeend', c) : e.appendChild(c));
  return e;
}

/* ════════════════════════════════════════════════════
   TOAST
   ════════════════════════════════════════════════════ */
function toast(msg, type = 'info', dur = 3000) {
  const icons = {
    success: '<svg width="14" height="14" fill="none" stroke="#34d399" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>',
    error:   '<svg width="14" height="14" fill="none" stroke="#f87171" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>',
    info:    '<svg width="14" height="14" fill="none" stroke="#38bdf8" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
    warning: '<svg width="14" height="14" fill="none" stroke="#fbbf24" viewBox="0 0 24 24" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>',
  };
  const t = document.createElement('div');
  t.className = 'toast ' + type;
  t.innerHTML = '<span class="toast-icon">' + (icons[type]||icons.info) + '</span><span>' + esc(msg) + '</span>';
  $('toast-container').appendChild(t);
  setTimeout(() => { t.style.opacity='0'; t.style.transition='opacity .2s'; setTimeout(()=>t.remove(),200); }, dur);
}

/* ════════════════════════════════════════════════════
   CONFIRM DIALOG
   ════════════════════════════════════════════════════ */
function confirmDialog(msg, onOk, danger=false) {
  $('confirm-msg').textContent = msg;
  const ov = $('confirm-overlay');
  ov.style.display = 'flex';
  const ok = $('confirm-ok');
  ok.className = danger ? 'btn-danger' : 'btn-sky';
  const newOk = ok.cloneNode(true);
  ok.replaceWith(newOk);
  newOk.onclick = () => { ov.style.display='none'; onOk(); };
  $('confirm-cancel').onclick = () => { ov.style.display='none'; };
}

/* ════════════════════════════════════════════════════
   SIDEBAR / PREVIEW
   ════════════════════════════════════════════════════ */
function openSidebar()  { $('sidebar').classList.add('open'); $('sidebar-overlay').classList.add('open'); document.body.style.overflow='hidden'; }
function closeSidebar() { $('sidebar').classList.remove('open'); $('sidebar-overlay').classList.remove('open'); document.body.style.overflow=''; }
function togglePreview(){ $('col-r').classList.toggle('open'); }

/* ════════════════════════════════════════════════════
   NAVIGATION
   ════════════════════════════════════════════════════ */
function goView(view) {
  ['list','builder','responses'].forEach(v => {
    const el = $('view-' + v);
    if (el) el.style.display = v === view ? 'flex' : 'none';
  });

  document.querySelectorAll('.tab').forEach(t => {
    t.classList.toggle('active', t.dataset.view === view);
  });

  curView = view;
  const isB = view === 'builder';
  const su = $('save-ui'), uu = $('undo-ui');
  if (su) su.style.display = isB ? 'flex' : 'none';
  if (uu) uu.style.display = isB ? 'flex' : 'none';
  // Barre mobile Publier : visible seulement si builder + mobile
  const mpb = $('mobile-publish-bar');
  if (mpb) mpb.style.display = (isB && window.innerWidth <= 768) ? 'flex' : 'none';

  if (view === 'list')      loadFormsList();
  if (view === 'builder')   { buildDots(); renderStep(0); }
  if (view === 'responses') loadResponses();
}

/* ════════════════════════════════════════════════════
   MODAL
   ════════════════════════════════════════════════════ */
function openModal(id)  { const e=$('m-detail'); if(e) e.classList.add('open'); }
function closeModal(id) { const e=$('m-detail'); if(e) e.classList.remove('open'); }

/* ════════════════════════════════════════════════════
   VUE LISTE
   ════════════════════════════════════════════════════ */
async function loadFormsList() {
  const tbody = $('forms-tbody'); if (!tbody) return;
  tbody.innerHTML = '<div class="tbl-row"><div style="flex:1"><div class="skeleton" style="height:12px;width:55%;margin-bottom:5px"></div><div class="skeleton" style="height:10px;width:35%"></div></div></div>'.repeat(3);
  try {
    const data = await apiGetForms();
    formsList = data;
    renderFormsList(data);
    const active = data.filter(f => f.actif).length;
    const totalR = data.reduce((a,f) => a + (f.stats?.total||0), 0);
    const avgComp= data.length ? Math.round(data.reduce((a,f) => a + (f.stats?.completion_pct||0), 0)/data.length) : 0;
    const avgSc  = data.length ? Math.round(data.reduce((a,f) => a + (f.stats?.avg_score||0), 0)/data.length) : 0;
    const s = (id,v) => { const e=$(id); if(e) e.textContent=v; };
    s('kpi-total', active); s('kpi-reponses', totalR.toLocaleString('fr'));
    s('kpi-completion', avgComp+'%'); s('kpi-score', avgSc+'%');
  } catch(e) {
    toast('Impossible de charger les formulaires', 'error');
    tbody.innerHTML = '<div class="empty-state"><p class="empty-ttl">Erreur de chargement</p></div>';
  }
}

function renderFormsList(forms) {
  const tbody = $('forms-tbody'); if (!tbody) return;
  if (!forms.length) {
    tbody.innerHTML = '<div class="empty-state"><div class="empty-ico"><svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg></div><p class="empty-ttl">Aucun formulaire</p><p class="empty-sub">Crée ton premier formulaire ou charge un template.</p></div>';
    return;
  }
  const BADGE = { inscription:'badge-sky', sondage:'badge-amber', quiz:'badge-violet', journal:'badge-teal', temoignage:'badge-pink', custom:'badge-zinc' };
  tbody.innerHTML = forms.map(f => {
    const st = f.stats||{}, pct=st.completion_pct||0;
    const bc  = BADGE[f.type]||'badge-zinc';
    const pc  = pct>=70 ? '#34d399' : pct>=40 ? '#fbbf24' : '#f87171';
    return `<div class="tbl-row fadein" style="min-width:640px" onclick="editForm(${f.id})">
      <div style="flex:1;min-width:160px"><p class="row-n">${esc(f.name)}</p><p class="row-c">${esc(f.command)} · ${esc(f.trigger_type||'')}</p></div>
      <span class="c-type"><span class="badge ${bc}">${esc(f.type)}</span></span>
      <span class="c-num">${(f.fields||[]).length}</span>
      <span class="c-num">${(st.total||0).toLocaleString('fr')}</span>
      <div class="c-comp"><p style="font-size:12px;color:${pc}">${pct}%</p><div class="pbar"><div class="pbar-f" style="width:${pct}%;background:${pc}"></div></div></div>
      <span class="c-stat"><span class="badge ${f.actif?'badge-green':'badge-zinc'}">${f.actif?'Actif':'Inactif'}</span></span>
      <div class="row-actions" onclick="event.stopPropagation()">
        <button class="btn-icon" title="Modifier" onclick="editForm(${f.id})"><svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg></button>
        <button class="btn-icon" title="Réponses" onclick="openDetailForForm(${f.id});event.stopPropagation()"><svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
        ${!f.actif
          ? `<button class="btn-activate" onclick="doActivateForm(${f.id},event)">Activer</button>`
          : `<button class="btn-icon del" title="Supprimer" onclick="deleteForm(${f.id},event)"><svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>`
        }
      </div>
    </div>`;
  }).join('');
}

async function editForm(id) {
  currentFormId = id;
  try { const f = await apiGetForm(id); loadFromData(f); goView('builder'); }
  catch(e) { toast('Impossible de charger le formulaire', 'error'); }
}

async function deleteForm(id, e) {
  e.stopPropagation();
  confirmDialog('Supprimer ce formulaire ? Cette action est irréversible.', async () => {
    try { await apiDeleteForm(id); toast('Formulaire supprimé','success'); loadFormsList(); }
    catch { toast('Erreur lors de la suppression','error'); }
  }, true);
}

async function doActivateForm(id, e) {
  e.stopPropagation();
  try { await apiActivateForm(id); toast('Formulaire réactivé','success'); loadFormsList(); }
  catch { toast('Erreur lors de la réactivation','error'); }
}

/* ════════════════════════════════════════════════════
   VUE RÉPONSES
   ════════════════════════════════════════════════════ */
async function loadResponses() {
  const sel = $('resp-form-sel'); if (!sel) return;
  try {
    if (!formsList.length) formsList = await apiGetForms();
    sel.innerHTML = formsList.map(f => `<option value="${f.id}">${esc(f.name)} (${f.stats?.total||0} rép.)</option>`).join('') || '<option>Aucun formulaire</option>';
    if (formsList.length) loadResponsesForForm(formsList[0].id);
  } catch { sel.innerHTML = '<option value="">Aucun formulaire disponible</option>'; }
}

async function loadResponsesForForm(formId) {
  const tbody = $('resp-tbody'); if (!tbody) return;
  tbody.innerHTML = '<div class="tbl-row"><div class="skeleton" style="height:12px;width:60%;flex:1"></div></div>'.repeat(4);
  try {
    const data = await apiGetResponses(formId);
    _allResponses = data;
    renderResponsesTable(data);
    _updateRespKPIs(data);
  } catch { tbody.innerHTML = '<div class="empty-state"><p class="empty-ttl">Erreur de chargement</p></div>'; }
}

function renderResponsesTable(data) {
  const tbody = $('resp-tbody'); if (!tbody) return;
  if (!data.length) { tbody.innerHTML = '<div class="empty-state"><p class="empty-ttl">Aucune réponse</p></div>'; return; }
  tbody.innerHTML = data.map(r => {
    const ini = (r.prenom||'?').substring(0,2).toUpperCase();
    const pct = r.pct||0, sc = pct>=70?'#34d399':pct>=50?'#fbbf24':'#f87171';
    const fid = $('resp-form-sel')?.value;
    return `<div class="tbl-row">
      <input type="checkbox" style="accent-color:var(--sky);flex-shrink:0">
      <div style="display:flex;align-items:center;gap:8px;flex:1;cursor:pointer" onclick="openResponseDetail(${r.telegram_id})">
        <div class="av-sm" style="background:var(--sky-bg);color:var(--sky)">${ini}</div>
        <div><p style="font-size:12px;color:var(--txt);font-weight:500">${esc(r.prenom||'User '+r.telegram_id)}</p><p style="font-size:10px;color:var(--txt-4)">ID : ${r.telegram_id}</p></div>
      </div>
      <span class="c-num" style="font-size:11px;color:var(--txt-3)">${r.field_count||'—'}</span>
      ${r.score_max ? `<span class="c-sc" style="color:${sc};font-weight:500">${r.score_final}/${r.score_max}</span>` : '<span class="c-sc" style="color:var(--txt-4)">—</span>'}
      <span style="width:90px;font-size:11px;color:var(--txt-4)">${r.submitted_at ? new Date(r.submitted_at).toLocaleDateString('fr') : '—'}</span>
      <button class="btn-icon" onclick="openResponseDetail(${r.telegram_id})"><svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg></button>
    </div>`;
  }).join('');
}

function _updateRespKPIs(data) {
  const total=data.length, completed=data.filter(r=>r.status!=='abandoned').length;
  const avgScore=total?Math.round(data.reduce((a,r)=>a+(r.pct||0),0)/total):0;
  const compPct=total?Math.round(completed/total*100):0;
  const s=(id,v)=>{const e=$(id);if(e)e.textContent=v;};
  s('r-total',total.toLocaleString('fr')); s('r-completed',completed.toLocaleString('fr'));
  s('r-score',avgScore+'%'); s('r-time',compPct+'%');
}

function filterResponses(status) {
  const data = status ? _allResponses.filter(r => status==='completed'?r.status!=='abandoned':r.status==='abandoned') : _allResponses;
  renderResponsesTable(data);
}
function toggleAllChecks(checked) { document.querySelectorAll('#resp-tbody input[type=checkbox]').forEach(cb=>cb.checked=checked); }

async function exportCSV() {
  const fid = $('resp-form-sel')?.value;
  if (!fid) { toast('Sélectionne un formulaire','warning'); return; }
  try {
    const data = await apiGetResponses(fid);
    if (!data.length) { toast('Aucune donnée à exporter','info'); return; }
    const keys = Object.keys(data[0]);
    const csv  = [keys.join(','), ...data.map(r => keys.map(k=>`"${String(r[k]||'').replace(/"/g,'""')}"`).join(','))].join('\n');
    const a = Object.assign(document.createElement('a'), { href:URL.createObjectURL(new Blob([csv],{type:'text/csv'})), download:'reponses_'+fid+'.csv' });
    a.click(); toast('Export CSV téléchargé','success');
  } catch { toast("Erreur lors de l'export",'error'); }
}

/* ════════════════════════════════════════════════════
   MODAL RÉPONSES DÉTAIL
   ════════════════════════════════════════════════════ */
async function openDetailForForm(formId) {
  _detailFormId = formId; _detailAllUsers = [];
  _resetDetailModal(); openModal('m-detail');
  await _loadAllUsers(formId);
}

async function openResponseDetail(telegramId) {
  const fid = $('resp-form-sel')?.value;
  if (!fid) { toast('Sélectionne un formulaire','warning'); return; }
  _detailFormId = fid; _detailAllUsers = [];
  _resetDetailModal(); openModal('m-detail');
  await _loadAllUsers(fid);
  const u = _detailAllUsers.find(u=>String(u.telegram_id)===String(telegramId));
  if (u) _selectUser(u);
}

function _resetDetailModal() {
  _detailCurrentId = null;
  $('resp-user-list').innerHTML = '<div class="skeleton" style="height:50px;margin:4px 10px;border-radius:6px"></div>'.repeat(3);
  $('resp-detail-body').innerHTML = '<div class="resp-empty-state"><svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg><p style="font-size:12px">Sélectionne un participant</p></div>';
  $('det-content-head').style.display = 'none';
  $('det-name').textContent = 'Chargement…'; $('det-meta').textContent = '—';
  $('det-score-pill').innerHTML = ''; $('det-total-count').textContent = '';
  const s = $('resp-user-search'); if(s) s.value='';
}

async function _loadAllUsers(formId) {
  try {
    const data = await apiGetResponses(formId, 500);
    _detailAllUsers = data;
    _renderUserList(data);
    $('det-total-count').textContent = data.length + ' participant' + (data.length>1?'s':'');
    if (data.length && !_detailCurrentId) _selectUser(data[0]);
  } catch {
    $('resp-user-list').innerHTML = '<p style="font-size:12px;color:var(--txt-4);padding:14px 16px">Erreur de chargement</p>';
  }
}

function _renderUserList(users) {
  const el = $('resp-user-list');
  if (!users.length) { el.innerHTML = '<p style="font-size:12px;color:var(--txt-4);padding:14px 16px">Aucune réponse</p>'; return; }
  el.innerHTML = users.map(u => {
    const ini = (u.prenom||'?').substring(0,2).toUpperCase();
    const pct = u.pct||0, color = pct>=70?'#34d399':pct>=50?'#fbbf24':'#f87171';
    const active = String(u.telegram_id)===String(_detailCurrentId) ? ' active' : '';
    return `<div class="resp-user-item${active}" id="uitem-${u.telegram_id}" onclick="_doSelectUser('${u.telegram_id}')">
      <div class="resp-av">${esc(ini)}</div>
      <div style="min-width:0;flex:1">
        <p class="resp-user-name">${esc(u.prenom||'User '+u.telegram_id)}</p>
        <p class="resp-user-meta">${u.submitted_at?new Date(u.submitted_at).toLocaleDateString('fr'):'En cours'}</p>
      </div>
      ${u.score_max?`<span class="resp-user-score" style="color:${color}">${u.score_final}/${u.score_max}</span>`:''}
    </div>`;
  }).join('');
}

function _doSelectUser(tid) {
  const u = _detailAllUsers.find(u=>String(u.telegram_id)===String(tid));
  if (u) _selectUser(u);
}

function filterUserList(q) {
  const f = q ? _detailAllUsers.filter(u=>(u.prenom||'').toLowerCase().includes(q.toLowerCase())||String(u.telegram_id).includes(q)) : _detailAllUsers;
  _renderUserList(f);
}

async function _selectUser(u) {
  _detailCurrentId = u.telegram_id;
  document.querySelectorAll('.resp-user-item').forEach(e=>e.classList.remove('active'));
  document.getElementById('uitem-'+u.telegram_id)?.classList.add('active');
  const ini = (u.prenom||'?').substring(0,2).toUpperCase();
  $('det-av').textContent = ini; $('det-name').textContent = u.prenom||'User '+u.telegram_id;
  $('det-meta').textContent = 'ID Telegram : '+u.telegram_id;
  const pill = $('det-score-pill');
  if (u.score_max) {
    const pct=u.pct||0, color=pct>=70?'#34d399':pct>=50?'#fbbf24':'#f87171';
    pill.innerHTML = `<span style="font-size:13px;font-weight:600;color:${color}">${u.score_final}/${u.score_max} — ${pct}%</span>`;
  } else pill.innerHTML='';
  $('det-content-head').style.display='flex';
  $('det-content-name').textContent = u.prenom||'User '+u.telegram_id;
  $('det-content-date').textContent = u.submitted_at ? 'Soumis le '+new Date(u.submitted_at).toLocaleString('fr') : 'Soumission en cours';
  const body = $('resp-detail-body');
  body.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;padding:30px;gap:10px;color:var(--txt-4);font-size:12px"><div class="spinner"></div> Chargement…</div>';
  try {
    const answers = await apiGetUserResponses(_detailFormId, u.telegram_id);
    _renderAnswers(body, answers, u);
  } catch { body.innerHTML = '<div style="padding:20px;color:var(--red);font-size:12px;text-align:center">Impossible de charger les réponses.</div>'; }
}

function _renderAnswers(container, answers, user) {
  if (!answers.length) { container.innerHTML = '<div class="resp-empty-state"><p style="font-size:12px">Aucune réponse enregistrée.</p></div>'; return; }
  let html = '';
  if (user.score_max > 0) {
    const pct=user.pct||0, color=pct>=70?'#34d399':pct>=50?'#fbbf24':'#f87171';
    html += `<div class="score-panel"><div><p class="score-big">${user.score_final} <span style="font-size:14px;color:var(--txt-4)">/ ${user.score_max}</span></p></div><div class="score-detail">Score final<br><span style="color:${color};font-weight:600">${pct}%</span></div></div>`;
  }
  answers.forEach((ans,idx) => {
    const cor = ans.is_correct, hasQuiz = cor!==null&&cor!==undefined;
    const isOk = cor===1||cor===true;
    const borderStyle = hasQuiz ? (isOk?'border-left:2px solid #34d399':'border-left:2px solid #f87171') : '';
    const typeLabel = TYPE_LABELS[ans.field_type]||ans.field_type;
    const isMedia = ['photo','video','audio','document'].includes(ans.field_type);
    const value = ans.value||'';
    let valueHtml = '';
    if (value==='__skip__') valueHtml = '<p style="font-size:11px;color:var(--txt-4);font-style:italic">Passé (optionnel)</p>';
    else if (isMedia && value && value!=='__media__') valueHtml = _renderMedia(ans.field_type, value);
    else valueHtml = `<p style="font-size:13px;color:var(--txt);line-height:1.5;word-break:break-word">${value ? esc(value).replace(/\n/g,'<br>') : '<span style="color:var(--txt-4);font-style:italic">—</span>'}</p>`;
    let scoreBadge = '';
    if (hasQuiz) scoreBadge = `<span class="badge ${isOk?'badge-green':'badge-red'}" style="margin-left:auto;font-size:9px;flex-shrink:0">${isOk?'✓ Correct':'✗ Incorrect'}${ans.points>0?' · +'+ans.points+' pts':''}</span>`;
    html += `<div class="ans-card" style="${borderStyle}">
      <div class="ans-card-head"><span class="ans-step">Q${idx+1}</span><span class="ans-type-lbl">${esc(typeLabel)}</span>${scoreBadge}</div>
      ${ans.field_label?`<p class="ans-q">${esc(ans.field_label)}</p>`:''}
      ${valueHtml}
      ${ans.answered_at?`<p style="font-size:9px;color:var(--txt-5);margin-top:6px;text-align:right">${new Date(ans.answered_at).toLocaleTimeString('fr')}</p>`:''}
    </div>`;
  });
  container.innerHTML = html;
}

const MEDIA_BASE = 'https://fdkvip.com';
function _mediaUrl(v) { return v&&v.startsWith('http') ? v : MEDIA_BASE+v; }

function _renderMedia(type, value) {
  if (!value||value==='__skip__'||value==='__media__') return '<p style="font-size:11px;color:var(--txt-4);font-style:italic">Fichier non disponible</p>';
  const url = _mediaUrl(value), fname = value.split('/').pop();
  if (type==='photo') return `<div class="ans-media-wrap"><img src="${esc(url)}" alt="Photo" loading="lazy" style="cursor:pointer;" onclick="window.open('${esc(url)}','_blank')" onerror="this.parentElement.innerHTML='<div style=\\'padding:12px;font-size:11px;color:var(--txt-4);text-align:center\\'>Image introuvable</div>'"></div>`;
  if (type==='video') return `<div class="ans-media-wrap"><video controls preload="metadata"><source src="${esc(url)}"></video></div>`;
  if (type==='audio') return `<div class="ans-media-wrap"><audio controls preload="metadata" style="width:100%;padding:8px"><source src="${esc(url)}"></audio></div>`;
  if (type==='document') return `<div class="ans-media-wrap"><a class="ans-file-dl" href="${esc(url)}" target="_blank" rel="noopener" download><svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>${esc(fname)}</a></div>`;
  return `<p style="font-size:11px;color:var(--txt-3);word-break:break-all">${esc(value)}</p>`;
}

/* ════════════════════════════════════════════════════
   BUILDER — FIELD RENDERING
   ════════════════════════════════════════════════════ */
function renderAll() {
  const c = $('fc'); c.innerHTML='';
  fields.forEach(f => c.appendChild(mkFiEl(f)));
  initSort(); buildDots();
  $('field-count').textContent = '(' + fields.length + ')';
}

function mkFiEl(f) {
  const m=TM[f.type]||TM.text, ic=ICO_SVG[f.type]||ICO_SVG.text;
  const div = document.createElement('div');
  div.className='fi fadein'; div.id='fi-'+f.id; div.dataset.id=f.id;
  div.innerHTML=`<div class="fi-head" onclick="toggleFi('${f.id}')">
    <span class="drag" title="Glisser">⠿</span>
    <div class="fi-type-ico" style="background:${m.bg};color:${m.color};width:22px;height:22px;border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">${ic.replace('viewBox','style="width:11px;height:11px;stroke:currentColor;fill:none" viewBox')}</div>
    <p class="fi-label" id="fl-${f.id}">${esc(f.label||m.label)}</p>
    ${f.quiz?'<span class="badge badge-violet" style="font-size:9px">Quiz</span>':''}
    ${f.required?'<span class="badge badge-zinc" style="font-size:9px">Requis</span>':''}
    <div style="display:flex;gap:3px;margin-left:auto;flex-shrink:0" onclick="event.stopPropagation()">
      <button class="btn-icon" title="Dupliquer" onclick="dupF('${f.id}')"><svg viewBox="0 0 24 24" stroke-width="1.5"><rect x="8" y="8" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"/></svg></button>
      <button class="btn-icon del" title="Supprimer" onclick="confirmDelField('${f.id}')"><svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="fi-chevron"><svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg></div>
  </div>
  <div class="fi-body" id="fb-${f.id}">${mkBody(f)}</div>`;
  return div;
}

function mkBody(f) {
  const m=TM[f.type]||TM.text;
  let h=`<div class="api-note"><span>📡</span> Telegram API : <b>${m.api}</b></div>`;
  h+=`<div class="mb8"><p class="lbl">Question envoyée par le bot</p><textarea class="inp" style="min-height:44px" oninput="setFP('${f.id}','label',this.value)">${esc(f.label||'')}</textarea></div>`;
  if (['text','long','email','number'].includes(f.type)) h+=`<div class="mb8"><p class="lbl">Exemple de réponse (preview)</p><input class="inp" type="text" value="${esc(f.ph||'')}" oninput="setFP('${f.id}','ph',this.value)" placeholder="ex: marc@gmail.com"></div>`;
  if (m.opts) {
    h+=`<p class="lbl" style="margin-bottom:6px">Options (boutons)</p><div id="opts-${f.id}">`;
    (f.opts||[]).forEach((o,i)=>{h+=`<div class="opt-row"><div class="cdot${o.c?' on':''}" onclick="toggleC('${f.id}',${i})"></div><input class="inp" style="flex:1;font-size:12px" type="text" value="${esc(o.t||'')}" oninput="setOpt('${f.id}',${i},'t',this.value)">${f.quiz?`<input class="inp" type="number" min="0" value="${o.pts||10}" style="width:50px;text-align:center" oninput="setOpt('${f.id}',${i},'pts',+this.value)" title="Points">`:'<span></span>'}<button class="btn-icon del" onclick="delOpt('${f.id}',${i})"><svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button></div>`;});
    h+=`</div><button class="btn-ghost" style="margin-top:6px" onclick="addOpt('${f.id}')">+ Option</button>`;
  }
  if (f.type==='oui_non') {
    h+=`<div style="display:flex;gap:7px;margin-bottom:8px"><div style="flex:1;padding:6px;border-radius:7px;border:1px solid rgba(52,211,153,.25);background:rgba(52,211,153,.05);font-size:11px;color:var(--green);text-align:center">✅ Oui</div><div style="flex:1;padding:6px;border-radius:7px;border:1px solid rgba(248,113,113,.25);background:rgba(248,113,113,.05);font-size:11px;color:var(--red);text-align:center">❌ Non</div></div><div class="mb8"><p class="lbl">Réponse correcte (si quiz)</p><select class="inp" oninput="setFP('${f.id}','correctAnswer',this.value)"><option value="">— Pas de correction —</option><option value="oui"${f.correctAnswer==='oui'?' selected':''}>Oui</option><option value="non"${f.correctAnswer==='non'?' selected':''}>Non</option></select></div>`;
  }
  if (f.type==='note5') h+=`<div style="display:flex;gap:4px;margin-bottom:8px">${[1,2,3,4,5].map(n=>`<div style="flex:1;padding:5px;border-radius:6px;border:1px solid rgba(251,191,36,.2);background:rgba(251,191,36,.04);font-size:11px;color:var(--amber2);text-align:center">⭐${n}</div>`).join('')}</div>`;
  if (f.type==='nps')   h+=`<div style="display:flex;flex-wrap:wrap;gap:3px;margin-bottom:8px">${[...Array(11)].map((_,n)=>`<div style="padding:4px 7px;border-radius:5px;border:1px solid rgba(45,212,191,.2);background:rgba(45,212,191,.03);font-size:11px;color:var(--teal)">${n}</div>`).join('')}</div>`;
  if (m.media) { const hints={photo:'image/jpeg, png',video:'video/mp4 (max 20 Mo)',audio:'audio/ogg, mp3',document:'PDF, ZIP, DOCX…'}; h+=`<div class="mb8" style="font-size:11px;color:var(--txt-3);padding:6px 10px;border:1px solid rgba(255,255,255,.06);border-radius:6px;font-family:'Geist Mono',monospace">${hints[f.type]||'*'}</div>`; }
  if (m.hasAns) {
    h+=`<div style="padding-top:8px;border-top:1px solid rgba(255,255,255,.04);margin-top:8px"><div class="tog-row mb8"><div><p class="opt-p">Mode quiz</p><p class="opt-sub">Réponse correcte + points</p></div><button class="toggle${f.quiz?' on':''}" onclick="toggleQuiz('${f.id}')"></button></div>`;
    if (f.quiz&&['text','long','email','number'].includes(f.type)) {
      h+=`<div class="abox mb8"><p class="lbl" style="color:var(--green);font-weight:500;margin-bottom:5px">✓ Réponse correcte attendue</p><input class="inp" type="text" value="${esc(f.correctAnswer||'')}" oninput="setFP('${f.id}','correctAnswer',this.value)" placeholder="Réponse attendue..."><div style="margin-top:6px"><p class="lbl">Points si correct</p><input class="inp" type="number" min="0" value="${f.pts||10}" oninput="setFP('${f.id}','pts',+this.value)"></div></div>`;
    }
    if (f.quiz) h+=`<div class="mb8"><p class="lbl">Explication après correction</p><textarea class="inp" style="min-height:36px" placeholder="Ex: Le RSI > 70 = suracheté." oninput="setFP('${f.id}','expl',this.value)">${esc(f.expl||'')}</textarea></div>`;
    h+=`</div>`;
  }
  h+=`<div class="tog-row" style="margin-top:8px"><div><p class="opt-p">Champ requis</p><p class="opt-sub">Bloque si pas de réponse</p></div><button class="toggle${f.required?' on':''}" onclick="toggleReq('${f.id}')"></button></div>`;
  return h;
}

/* FIELD OPS */
function addF(type) {
  pushH(); const id=++fCtr, m=TM[type]||TM.text;
  fields.push({id,type,label:'',ph:'',required:true,quiz:false,opts:m.opts?[{t:'Option A',c:false,pts:10},{t:'Option B',c:false,pts:10}]:[],correctAnswer:null,pts:10,expl:''});
  const el=mkFiEl(fields[fields.length-1]);
  $('fc').appendChild(el); toggleFi(id,true);
  el.scrollIntoView({behavior:'smooth',block:'nearest'});
  initSort(); buildDots(); scheduleSave();
  $('field-count').textContent='('+fields.length+')';
}
function confirmDelField(id) { confirmDialog('Supprimer ce champ ?', ()=>delF(id), true); }
function delF(id) {
  pushH(); fields=fields.filter(f=>f.id!=id);
  const el=document.getElementById('fi-'+id);
  if(el){el.style.transition='all .15s';el.style.opacity='0';el.style.transform='translateX(-8px)';setTimeout(()=>el.remove(),150);}
  if(curStep>fields.length) curStep=fields.length;
  buildDots(); renderStep(curStep); scheduleSave();
  $('field-count').textContent='('+fields.length+')';
}
function dupF(id) {
  pushH(); const orig=fields.find(f=>f.id==id); if(!orig) return;
  const clone=JSON.parse(JSON.stringify(orig)); clone.id=++fCtr; clone.label+=' (copie)';
  fields.splice(fields.findIndex(f=>f.id==id)+1,0,clone);
  renderAll(); scheduleSave(); toast('Champ dupliqué','info',1500);
}
function toggleFi(id,forceOpen) {
  const b=document.getElementById('fb-'+id), i=document.getElementById('fi-'+id);
  if(!b||!i) return;
  const open=forceOpen!==undefined?forceOpen:!b.classList.contains('show');
  b.classList.toggle('show',open); i.classList.toggle('open',open);
}
function collapseAll() { fields.forEach(f=>{document.getElementById('fb-'+f.id)?.classList.remove('show');document.getElementById('fi-'+f.id)?.classList.remove('open');}); }
function setFP(id,p,v) {
  const f=fields.find(f=>f.id==id); if(!f) return; f[p]=v;
  if(p==='label'){const l=document.getElementById('fl-'+id);if(l)l.textContent=v||(TM[f.type]?.label);}
  scheduleSave(); renderStep(curStep);
}
function toggleReq(id)  { const f=fields.find(f=>f.id==id); if(!f) return; f.required=!f.required; rebuildBody(id); }
function toggleQuiz(id) { pushH(); const f=fields.find(f=>f.id==id); if(!f) return; f.quiz=!f.quiz; rebuildBody(id); scheduleSave(); }
function rebuildBody(id){ const f=fields.find(f=>f.id==id),b=document.getElementById('fb-'+id); if(f&&b)b.innerHTML=mkBody(f); }
function addOpt(fid)         { const f=fields.find(f=>f.id==fid); if(!f) return; f.opts.push({t:'Nouvelle option',c:false,pts:10}); rebuildBody(fid); }
function delOpt(fid,i)       { const f=fields.find(f=>f.id==fid); if(!f) return; f.opts.splice(i,1); rebuildBody(fid); }
function setOpt(fid,i,p,v)   { const f=fields.find(f=>f.id==fid); if(!f||!f.opts[i]) return; f.opts[i][p]=v; scheduleSave(); }
function toggleC(fid,i) {
  const f=fields.find(f=>f.id==fid); if(!f||!f.opts[i]) return;
  if(f.type==='qcm') f.opts.forEach((o,j)=>o.c=j===i); else f.opts[i].c=!f.opts[i].c;
  rebuildBody(fid); scheduleSave();
}

/* PALETTE */
function togglePal() { const p=$('palette'); p.style.display=p.style.display!=='none'?'none':'block'; }
function hidePal()   { const p=$('palette'); if(p) p.style.display='none'; }

/* DRAG & DROP */
function initSort() {
  if(sortable) sortable.destroy();
  if(typeof Sortable==='undefined') return;
  sortable=Sortable.create($('fc'),{handle:'.drag',animation:140,ghostClass:'sortable-ghost',onEnd(e){pushH();const m=fields.splice(e.oldIndex,1)[0];fields.splice(e.newIndex,0,m);buildDots();scheduleSave();}});
}

/* HISTORY */
function pushH() { const s=JSON.stringify(fields); hist=hist.slice(0,hIdx+1); hist.push(s); if(hist.length>60)hist.shift(); hIdx=hist.length-1; updH(); }
function undo()  { if(hIdx<=0)return; hIdx--; fields=JSON.parse(hist[hIdx]); renderAll(); renderStep(Math.min(curStep,fields.length)); updH(); }
function redo()  { if(hIdx>=hist.length-1)return; hIdx++; fields=JSON.parse(hist[hIdx]); renderAll(); renderStep(Math.min(curStep,fields.length)); updH(); }
function updH()  { const u=$('ubtn'),r=$('rbtn'); if(u)u.disabled=hIdx<=0; if(r)r.disabled=hIdx>=hist.length-1; }

/* AUTOSAVE */
function scheduleSave() { setSave('pending'); clearTimeout(saveTimer); saveTimer=setTimeout(()=>{setSave('saved');pushH();},900); }
function setSave(s) {
  const dot=$('save-dot'),txt=$('save-txt'); if(!dot||!txt)return;
  const mpbDot=$('mpb-dot'),mpbTxt=$('mpb-status');
  if(s==='pending'){
    dot.style.background='#fbbf24';txt.textContent='Modifications…';txt.style.color='#fbbf24';
    if(mpbDot) mpbDot.style.background='#fbbf24';
    if(mpbTxt) mpbTxt.textContent='Non sauvegardé…';
  } else {
    dot.style.background='#34d399';txt.textContent='Sauvegardé';txt.style.color='var(--txt-4)';
    if(mpbDot) mpbDot.style.background='#34d399';
    if(mpbTxt) mpbTxt.textContent='Sauvegardé';
  }
}

/* CONDITIONS & ACTIONS */
function getEndActions() {
  return [...document.querySelectorAll('#end-actions .act-row')].map(r=>({type:r.querySelectorAll('select')[0]?.value||'',value:r.querySelectorAll('input')[0]?.value||''}));
}
function getConditions() {
  return [...document.querySelectorAll('#conds > div')].map(w=>{
    const cs=w.querySelector('.cond-row')?.querySelectorAll('select,input')||[];
    const as=w.querySelector('.act-row')?.querySelectorAll('select,input')||[];
    return {if:{field:cs[0]?.value||'',op:cs[1]?.value||'=',value:cs[2]?.value||''},then:{action:as[0]?.value||'',value:as[1]?.value||''}};
  }).filter(c=>c.if.field);
}
function addCond() {
  const opts=fields.map(f=>`<option value="${esc(f.label||'Champ')}">${esc(f.label||TM[f.type]?.label||'Champ')}</option>`).join('');
  const d=document.createElement('div'); d.style.marginBottom='6px';
  d.innerHTML=`<div class="cond-row"><span class="cond-lbl" style="color:var(--txt-4)">SI</span><select class="inp" style="font-size:11px;padding:3px 6px;flex:2">${opts||'<option>—</option>'}</select><select class="inp" style="font-size:11px;padding:3px 6px;width:80px"><option>=</option><option>≠</option><option>contient</option></select><input class="inp" type="text" placeholder="valeur" style="font-size:11px;padding:3px 6px;flex:1"><button class="btn-icon del" onclick="this.closest('.cond-row').closest('div').remove()"><svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button></div><div class="act-row"><span class="cond-lbl" style="color:var(--sky)">ALORS</span><select class="inp" style="font-size:11px;padding:3px 6px;flex:2"><option>Ajouter catégorie</option><option>Passer le champ</option><option>Envoyer message</option></select><input class="inp" type="text" placeholder="valeur..." style="font-size:11px;padding:3px 6px;flex:1"></div>`;
  $('conds').appendChild(d);
}
function addAction() {
  const d=document.createElement('div');
  d.innerHTML=`<div class="act-row" style="margin-top:4px"><svg width="10" height="10" fill="none" stroke="var(--sky)" viewBox="0 0 24 24" stroke-width="1.5" style="flex-shrink:0"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg><select class="inp" style="font-size:11px;padding:3px 6px;flex:0 0 120px"><option>Ajouter catégorie</option><option>Envoyer message</option><option>Notifier admin</option><option>Broadcast</option></select><input class="inp" type="text" placeholder="valeur..." style="font-size:11px;padding:3px 6px;flex:1"><button class="btn-icon del" onclick="this.closest('.act-row').remove()"><svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button></div>`;
  $('end-actions').appendChild(d);
}

/* ════════════════════════════════════════════════════
   PUBLISH
   ════════════════════════════════════════════════════ */
async function publish() {
  const name=$('f-name')?.value?.trim(), cmd=$('f-cmd')?.value?.trim();
  if (!name) { toast('Le nom du formulaire est requis','warning'); return; }
  if (!cmd)  { toast('La commande Telegram est requise','warning'); return; }
  if (!fields.length) { toast('Ajoute au moins un champ','warning'); return; }
  const btn = document.querySelector('[onclick="publish()"]'), orig = btn?.innerHTML;
  if (btn) { btn.innerHTML='<div class="spinner"></div> Publication…'; btn.style.opacity='.7'; btn.style.pointerEvents='none'; }
  const triggerSel=$('f-trigger')?.value, triggerDate=$('f-trigger-value')?.value, triggerCron=$('f-trigger-cron')?.value?.trim();
  const payload = {
    name, command:cmd, type:$('f-type')?.value,
    trigger:triggerSel, trigger_value:triggerCron||triggerDate||null,
    intro:$('f-intro')?.value, outro:$('f-outro')?.value,
    fields:fields.map(f=>({...f,tgApi:TM[f.type]?.api})),
    actions:getEndActions(), conditions:getConditions(),
    quiz_config:{ max:+($('q-max')?.value||0), pts:+($('q-pts')?.value||10), penalty:+($('q-penalty')?.value||0) },
    options:{ resume:$('opt-resume')?.classList.contains('on')??true, progress:$('opt-progress')?.classList.contains('on')??true, one_per_user:$('opt-one-per-user')?.classList.contains('on')??true, notify_admin:$('opt-notify')?.classList.contains('on')??false, target_category:$('f-target-cat')?.value||null },
  };
  try {
    const data=await apiSaveForm(payload); currentFormId=data.form_id; setSave('saved');
    toast('Formulaire "'+name+'" publié !','success');
    if(btn){btn.innerHTML='✓ Publié !';btn.style.background='var(--green)';btn.style.color='#052e16';}
    setTimeout(()=>{if(btn){btn.innerHTML=orig;btn.style.background='';btn.style.color='';btn.style.opacity='';btn.style.pointerEvents='';}},2200);
  } catch(e) {
    toast('Erreur : '+e.message,'error');
    if(btn){btn.innerHTML=orig;btn.style.opacity='';btn.style.pointerEvents='';}
  }
}

/* LOAD FROM API */
function loadFromData(form) {
  $('f-name').value   = form.name   ||'';
  $('f-cmd').value    = (form.command||'').replace(/^\//,'');
  $('f-type').value   = form.type   ||'custom';
  $('f-trigger').value= form.trigger_type==='scheduled'?'Planifié (date/heure)':form.trigger_type==='start'?"À l'inscription (/start)":form.trigger_type==='condition'?'Automatique (condition)':'Commande manuelle';
  $('f-intro').value  = form.intro  ||'';
  $('f-outro').value  = form.outro  ||'';
  $('quiz-cfg').style.display = form.type==='quiz'?'block':'none';
  if(form.trigger_value&&$('f-trigger-cron')) { $('f-trigger-cron').value=form.trigger_value; $('trigger-date-wrap')?.classList.add('show'); }
  updateMeta();
  fields=[]; fCtr=0;
  (form.fields||[]).forEach(f=>{const id=++fCtr;fields.push({id,...f,opts:JSON.parse(JSON.stringify(f.opts||[]))});});
  renderAll(); pushH(); curStep=0; buildDots(); renderStep(0);
  toast('Formulaire "'+form.name+'" chargé','info',2000);
}

/* ════════════════════════════════════════════════════
   TELEGRAM PREVIEW
   ════════════════════════════════════════════════════ */
function steps() { return ['__intro__',...fields,'__outro__']; }

function buildDots() {
  const c=$('step-dots'); if(!c) return;
  c.innerHTML='';
  steps().forEach((_,i)=>{
    const isI=i===0,isO=i===steps().length-1;
    const d=document.createElement('div');
    d.className='sdot'+(i===curStep?' on':'')+(isI?' intro':'')+(isO?' outro':'');
    d.title=isI?'Intro':isO?'Outro':'Champ '+i; d.onclick=()=>renderStep(i);
    c.appendChild(d);
  });
}

function renderStep(idx) {
  const ss=steps(); if(idx<0||idx>=ss.length) return;
  curStep=idx;
  document.querySelectorAll('.sdot').forEach((d,i)=>{const isI=i===0,isO=i===ss.length-1;d.className='sdot'+(i===idx?' on':'')+(isI?' intro':'')+(isO?' outro':'');});
  const pct=ss.length>1?Math.round(idx/(ss.length-1)*100):0;
  const prog=$('prev-prog'),progt=$('prev-prog-txt');
  if(prog) prog.style.width=pct+'%';
  if(progt) progt.textContent=(idx>0&&idx<ss.length-1)?idx+'/'+(ss.length-2):'';
  const si=$('step-info'); if(si) si.textContent='Étape '+(idx+1)+' / '+ss.length;
  const feed=$('tg-feed'),hint=$('tg-hint'),rk=$('tg-rk');
  if(!feed||!hint||!rk) return;
  feed.innerHTML=''; rk.innerHTML=''; rk.style.display='none';
  const step=ss[idx];
  if(step==='__intro__') {
    const cmd=$('f-cmd')?.value||'formulaire', txt=$('f-intro')?.value||'Bonjour ! Le formulaire va démarrer.';
    botMsg(feed,'<code style="background:rgba(167,139,250,.15);color:var(--violet);padding:1px 5px;border-radius:4px;font-family:\'Geist Mono\',monospace;font-size:10px">/'+cmd+'</code>');
    botMsg(feed,esc(txt).replace(/\n/g,'<br>')); feed.appendChild(mkBtn('▶️ Commencer',nextStep));
    hint.textContent='Appuie pour démarrer…'; return;
  }
  if(step==='__outro__') {
    const txt=$('f-outro')?.value||'✅ Merci pour tes réponses !';
    botMsg(feed,esc(txt).replace(/\n/g,'<br>')); hint.textContent='Formulaire terminé !'; return;
  }
  const f=step, m=TM[f.type]||TM.text;
  botMsg(feed,esc(f.label||m.label));
  if(['text','long','email','number'].includes(f.type)) {
    hint.textContent=f.ph||'Tape ta réponse…';
    feed.appendChild(mkBtn('Envoyer ↗',()=>{usrMsg(feed,f.ph||"Réponse");if(f.quiz&&f.correctAnswer){const ok=(f.ph||'').toLowerCase().includes((f.correctAnswer||'').toLowerCase());botMsg(feed,ok?'✅ <b>Correct !</b>'+(f.expl?'<br>'+esc(f.expl):''):'❌ <b>Incorrect.</b> Réponse : <i>'+esc(f.correctAnswer)+'</i>'+(f.expl?'<br>'+esc(f.expl):''));setTimeout(nextStep,900);}else{setTimeout(nextStep,400);}}));
  } else if(f.type==='qcm') {
    hint.textContent='Sélectionne une option…';
    (f.opts||[]).forEach(o=>{feed.appendChild(mkBtn(o.t,()=>{usrMsg(feed,o.t);if(f.quiz)botMsg(feed,o.c?'✅ <b>Correct !</b>'+(f.expl?'<br>'+esc(f.expl):''):'❌ <b>Incorrect.</b>'+(f.expl?'<br>'+esc(f.expl):''));setTimeout(nextStep,f.quiz?900:400);}));});
  } else if(f.type==='multi') {
    hint.textContent='Sélectionne une ou plusieurs options…';
    const sel=new Set(); (f.opts||[]).forEach((o,i)=>{const b=mkBtn(o.t,()=>{sel.has(i)?sel.delete(i):sel.add(i);b.style.background=sel.has(i)?'rgba(56,189,248,.15)':'';b.style.borderColor=sel.has(i)?'rgba(56,189,248,.4)':'';});feed.appendChild(b);});
    const v=mkBtn('✅ Valider',()=>{usrMsg(feed,[...sel].map(i=>f.opts[i].t).join(', ')||'—');setTimeout(nextStep,400);}); v.style.marginTop='5px'; feed.appendChild(v);
  } else if(f.type==='oui_non') {
    hint.textContent='Choisis…'; ['✅ Oui','❌ Non'].forEach(t=>feed.appendChild(mkBtn(t,()=>{usrMsg(feed,t);setTimeout(nextStep,400);})));
  } else if(f.type==='note5') {
    hint.textContent='1 = Mauvais · 5 = Excellent';
    const row=document.createElement('div'); row.style.cssText='display:flex;gap:4px;margin-top:3px';
    for(let i=1;i<=5;i++){const n=i,b=mkBtn('⭐'+n,()=>{usrMsg(feed,'⭐'.repeat(n)+' ('+n+'/5)');setTimeout(nextStep,400);});b.style.flex='1';row.appendChild(b);}
    feed.appendChild(row);
  } else if(f.type==='nps') {
    hint.textContent='0 = Pas du tout · 10 = Absolument';
    const row=document.createElement('div'); row.style.cssText='display:flex;flex-wrap:wrap;gap:3px;margin-top:3px';
    for(let i=0;i<=10;i++){const n=i,b=mkBtn(String(n),()=>{usrMsg(feed,n+'/10');setTimeout(nextStep,400);});b.style.cssText+='padding:5px 8px;min-width:28px;flex:none';row.appendChild(b);}
    feed.appendChild(row);
  } else if(m.media) {
    const lbl={photo:'📸 Envoie ta photo',video:'🎬 Envoie ta vidéo',audio:'🎙️ Envoie un vocal',document:'📄 Envoie ton document'};
    hint.textContent=lbl[f.type]||'Envoie un fichier…';
    const z=document.createElement('div'); z.style.cssText='border:1px dashed rgba(56,189,248,.25);border-radius:8px;padding:14px;text-align:center;cursor:pointer;margin-top:4px;background:rgba(56,189,248,.03);';
    z.innerHTML='<p style="font-size:22px;margin-bottom:5px">'+{photo:'📸',video:'🎬',audio:'🎙️',document:'📄'}[f.type]+'</p><p style="font-size:10px;color:#64b5f6">'+esc(lbl[f.type])+'</p>';
    z.onclick=()=>{z.innerHTML='<p style="font-size:11px;color:var(--green)">✅ Fichier reçu</p>';setTimeout(nextStep,600);};
    feed.appendChild(z);
  } else if(f.type==='contact') {
    hint.textContent='Partage ton numéro…'; rk.style.display='flex';
    const kb=document.createElement('div'); kb.className='tg-rk-btn'; kb.style.width='100%'; kb.textContent='📱 Partager mon numéro';
    kb.onclick=()=>{usrMsg(feed,'📱 +33 6 12 34 56 78');rk.style.display='none';setTimeout(nextStep,400);};
    rk.appendChild(kb);
  } else if(f.type==='info') { hint.textContent=''; feed.appendChild(mkBtn('Continuer →',nextStep)); }
  feed.scrollTop=feed.scrollHeight;
}

function botMsg(feed,html) { const d=document.createElement('div'); d.innerHTML='<div class="bot-bbl">'+html+'</div><p class="tg-time">'+ftime()+'</p>'; feed.appendChild(d); feed.scrollTop=feed.scrollHeight; }
function usrMsg(feed,txt)  { const d=document.createElement('div'); d.style.cssText='align-self:flex-end'; d.innerHTML='<div class="user-bbl">'+esc(txt)+'</div><p class="tg-time r">'+ftime()+'</p>'; feed.appendChild(d); feed.scrollTop=feed.scrollHeight; }
function mkBtn(txt,cb) { const b=document.createElement('div'); b.className='tg-btn'; b.textContent=txt; b.onclick=cb; return b; }
function ftime() { const d=new Date(); return String(d.getHours()).padStart(2,'0')+':'+String(d.getMinutes()).padStart(2,'0'); }
function nextStep() { const ss=steps(); if(curStep<ss.length-1) renderStep(curStep+1); }
function prevStep() { if(curStep>0) renderStep(curStep-1); }
function resetPrev(){ curStep=0; buildDots(); renderStep(0); }

/* META */
function updateMeta() {
  const n=$('prev-name'),nm=$('f-name')?.value; if(n&&nm)n.textContent=nm||'Felipe Bot';
  const p=$('cmd-pill'),c=$('f-cmd')?.value; if(p)p.textContent=c?'/'+c:'/formulaire';
  const mpbName=$('mpb-name'); if(mpbName) mpbName.textContent=nm||'Sans titre';
}
function sanitizeCmd(el){ el.value=el.value.toLowerCase().replace(/[^a-z0-9_]/g,''); updateMeta(); scheduleSave(); }
function onTypeChange()  { $('quiz-cfg').style.display=$('f-type')?.value==='quiz'?'block':'none'; scheduleSave(); }
function onTriggerChange(){ const sel=$('f-trigger'),wrap=$('trigger-date-wrap'); if(!wrap)return; wrap.classList.toggle('show',sel?.value==='Planifié (date/heure)'); }

/* ════════════════════════════════════════════════════
   TEMPLATES
   ════════════════════════════════════════════════════ */
const TPLS = {
  inscription:{ name:'Onboarding Forex', cmd:'start', type:'inscription', intro:'Bonjour +prenom ! 👋\n\nBienvenue dans la communauté Felipe Bot.', outro:'✅ Bienvenue +prenom !', fields:[{type:'text',label:'Quel est ton prénom ?',ph:'Marc',required:true,quiz:false,opts:[],correctAnswer:null,pts:10,expl:''},{type:'email',label:'Ton adresse email ?',ph:'marc@gmail.com',required:true,quiz:false,opts:[],correctAnswer:null,pts:10,expl:''},{type:'qcm',label:'Ton niveau en trading ?',required:true,quiz:false,opts:[{t:'🟢 Débutant',c:false,pts:10},{t:'🟡 Intermédiaire',c:false,pts:10},{t:'🔴 Expert',c:false,pts:10}],correctAnswer:null,pts:10,expl:''},{type:'long',label:'Quel est ton objectif principal ?',ph:'Ex: viser +5% par mois',required:false,quiz:false,opts:[],correctAnswer:null,pts:10,expl:''}]},
  sondage:{ name:'Sondage satisfaction', cmd:'sondage', type:'sondage', intro:'📊 Sondage de satisfaction\n\n3 questions rapides.', outro:'🙏 Merci +prenom !', fields:[{type:'note5',label:'Note la qualité des signaux cette semaine (1 à 5) ?',required:true,quiz:false,opts:[],correctAnswer:null,pts:10,expl:''},{type:'nps',label:'Sur 10, tu recommanderais Felipe Bot à un ami ?',required:true,quiz:false,opts:[],correctAnswer:null,pts:10,expl:''},{type:'qcm',label:"Qu'est-ce que tu apprécies le plus ?",required:true,quiz:false,opts:[{t:'📈 Les signaux',c:false,pts:10},{t:'💬 Le support',c:false,pts:10},{t:'📚 La formation',c:false,pts:10}],correctAnswer:null,pts:10,expl:''}]},
  quiz:{ name:'Quiz Analyse Technique', cmd:'quiz', type:'quiz', intro:'📚 Quiz Analyse Technique\n\nBonjour +prenom ! 4 questions. Bonne chance ! 🎯', outro:'🎉 Quiz terminé +prenom !\n\nTon score : +score / +total', fields:[{type:'qcm',label:"Qu'est-ce qu'un Doji indique ?",required:true,quiz:true,expl:'Le Doji = indécision.',opts:[{t:'Indécision du marché',c:true,pts:10},{t:'Tendance haussière forte',c:false,pts:0},{t:'Signal de vente fiable',c:false,pts:0}],correctAnswer:null,pts:10},{type:'qcm',label:'Le RSI à 75 signifie que le marché est :',required:true,quiz:true,expl:'RSI > 70 = suracheté.',opts:[{t:'Suracheté 🔴',c:true,pts:10},{t:'Survendu 🟢',c:false,pts:0},{t:'Neutre ⚪',c:false,pts:0}],correctAnswer:null,pts:10},{type:'oui_non',label:'Le croisement MA20/MA50 à la hausse est-il haussier ?',required:true,quiz:true,correctAnswer:'oui',expl:'Oui — signal classique.',opts:[],pts:10}]},
  journal:{ name:'Journal de trading hebdo', cmd:'journal', type:'journal', intro:'📓 Journal — Semaine du +date\n\nBonjour +prenom !', outro:'✅ Journal enregistré +prenom !', fields:[{type:'qcm',label:'Quelle paire as-tu principalement tradée ?',required:true,quiz:false,opts:[{t:'EUR/USD',c:false,pts:10},{t:'GBP/USD',c:false,pts:10},{t:'XAU/USD',c:false,pts:10},{t:'BTC/USD',c:false,pts:10}],correctAnswer:null,pts:10,expl:''},{type:'note5',label:'Note ta discipline cette semaine (1 à 5) ?',required:true,quiz:false,opts:[],correctAnswer:null,pts:10,expl:''},{type:'number',label:'Combien de trades as-tu réalisés ?',ph:'5',required:true,quiz:false,opts:[],correctAnswer:null,pts:10,expl:''},{type:'long',label:'Tes notes et observations',ph:"Qu'as-tu appris cette semaine ?",required:false,quiz:false,opts:[],correctAnswer:null,pts:10,expl:''}]},
  temoignage:{ name:'Témoignage performance', cmd:'temoignage', type:'temoignage', intro:'⭐ Partage ton témoignage +prenom !', outro:'✨ Merci +prenom ! 🚀', fields:[{type:'note5',label:'Sur 5, quelle note donnes-tu à notre méthode ?',required:true,quiz:false,opts:[],correctAnswer:null,pts:10,expl:''},{type:'long',label:'Décris ta meilleure performance récente 📈',ph:'Ex: +4.2% sur EUR/USD…',required:true,quiz:false,opts:[],correctAnswer:null,pts:10,expl:''}]},
};

function loadTpl(id) {
  const t=TPLS[id]||TPLS.inscription; currentFormId=null;
  $('f-name').value=$('f-cmd').value=$('f-intro').value=$('f-outro').value='';
  $('f-name').value=t.name||''; $('f-cmd').value=t.cmd||''; $('f-type').value=t.type||'custom'; $('f-intro').value=t.intro||''; $('f-outro').value=t.outro||'';
  $('quiz-cfg').style.display=t.type==='quiz'?'block':'none'; updateMeta();
  fields=[]; fCtr=0; (t.fields||[]).forEach(f=>{const id=++fCtr;fields.push({id,...f,opts:JSON.parse(JSON.stringify(f.opts||[]))});});
  renderAll(); pushH(); curStep=0; buildDots(); renderStep(0);
}

function newForm() {
  currentFormId=null;
  ['f-name','f-cmd','f-intro','f-outro'].forEach(id=>{const e=$(id);if(e)e.value='';});
  $('f-type').value='custom'; $('quiz-cfg').style.display='none';
  $('trigger-date-wrap')?.classList.remove('show');
  const conds=$('conds'); if(conds)conds.innerHTML='';
  const ea=$('end-actions');
  if(ea)ea.innerHTML=`<div class="act-row"><svg width="10" height="10" fill="none" stroke="var(--sky)" viewBox="0 0 24 24" stroke-width="1.5" style="flex-shrink:0"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg><select class="inp" style="font-size:11px;padding:3px 6px;flex:0 0 120px"><option>Ajouter catégorie</option><option>Envoyer message</option><option>Notifier admin</option></select><input class="inp" type="text" placeholder="valeur..." style="font-size:11px;padding:3px 6px;flex:1"><button class="btn-icon del" onclick="this.closest('.act-row').remove()"><svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button></div>`;
  fields=[]; fCtr=0; hist=[]; hIdx=-1; updH();
  renderAll(); buildDots(); renderStep(0); updateMeta();
}

/* UTILS */
function insertVar(id, v) {
  const el=$(id); if(!el)return;
  const p=el.selectionStart||el.value.length;
  el.value=el.value.slice(0,p)+v+el.value.slice(p);
  el.focus(); el.setSelectionRange(p+v.length,p+v.length); scheduleSave();
}

/* ════════════════════════════════════════════════════
   INIT
   ════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  document.addEventListener('keydown', e => {
    if (e.key==='Escape') {
      closeModal('m-detail');
      $('confirm-overlay').style.display='none';
      hidePal(); closeSidebar();
      $('col-r')?.classList.remove('open');
    }
    if (curView!=='builder') return;
    const mod=e.metaKey||e.ctrlKey;
    if(mod&&e.key==='z'&&!e.shiftKey){e.preventDefault();undo();}
    if(mod&&e.key==='z'&&e.shiftKey) {e.preventDefault();redo();}
    if(mod&&e.key==='s')              {e.preventDefault();publish();}
    if(mod&&e.key==='Enter')          {e.preventDefault();togglePal();}
  });

  window.addEventListener('resize', () => {
    if (window.innerWidth > 768) {
      $('sidebar-overlay').classList.remove('open');
      document.body.style.overflow='';
    }
  });

  goView('list');
  setSave('saved');
});

/* ════════════════════════════════════════════════════
   MODAL LISTE MOBILE
   ════════════════════════════════════════════════════ */
let _mflFiltered = [];

function openFormsListModal() {
  document.getElementById('modal-forms-list').classList.add('open');
  renderMobileList();
}

function closeMobileList() {
  document.getElementById('modal-forms-list').classList.remove('open');
}

function renderMobileList() {
  _mflFiltered = formsList;
  _renderMflItems(formsList);
}

function filterMobileList(q) {
  _mflFiltered = q
    ? formsList.filter(f =>
        (f.name||'').toLowerCase().includes(q.toLowerCase()) ||
        (f.command||'').toLowerCase().includes(q.toLowerCase()))
    : formsList;
  _renderMflItems(_mflFiltered);
}

const MFL_ICONS = {
  inscription:'👤', sondage:'📊', quiz:'📚',
  journal:'📓', temoignage:'⭐', custom:'⚙️'
};
const MFL_COLORS = {
  inscription:'var(--sky-bg)', sondage:'var(--amber2-bg)', quiz:'var(--violet-bg)',
  journal:'var(--teal-bg)', temoignage:'var(--pink-bg)', custom:'rgba(255,255,255,.06)'
};

function _renderMflItems(forms) {
  const el = document.getElementById('mfl-list');
  const cnt = document.getElementById('mfl-count');
  if (cnt) cnt.textContent = forms.length + ' formulaire' + (forms.length>1?'s':'');

  if (!forms.length) {
    el.innerHTML = '<div class="empty-state"><p class="empty-ttl">Aucun formulaire</p><p class="empty-sub">Crée ton premier formulaire.</p></div>';
    return;
  }

  el.innerHTML = forms.map(f => {
    const st   = f.stats || {};
    const pct  = st.completion_pct || 0;
    const pc   = pct>=70 ? '#34d399' : pct>=40 ? '#fbbf24' : '#f87171';
    const icon = MFL_ICONS[f.type] || '⚙️';
    const bg   = MFL_COLORS[f.type] || 'rgba(255,255,255,.06)';
    return `
    <div class="mfl-item fadein">
      <div class="mfl-ico" style="background:${bg}">${icon}</div>
      <div class="mfl-info">
        <div style="display:flex;align-items:center;gap:6px;min-width:0">
          <p class="mfl-name" style="flex:1">${esc(f.name)}</p>
          <span class="badge ${f.actif?'badge-green':'badge-zinc'}" style="font-size:9px;flex-shrink:0">${f.actif?'Actif':'Inactif'}</span>
        </div>
        <p class="mfl-meta">${esc((f.command||'').length > 20 ? f.command.slice(0,20)+'…' : f.command)} · ${(f.fields||[]).length} champs · <span style="color:${pc}">${pct}%</span></p>
      </div>
      <div class="mfl-actions">
        <button class="btn-icon" title="Modifier" onclick="closeMobileList();editForm(${f.id})">
          <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4z"/></svg>
        </button>
        <button class="btn-icon" title="Réponses" onclick="closeMobileList();openDetailForForm(${f.id})">
          <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        </button>
        ${!f.actif
          ? `<button class="btn-activate" onclick="doActivateForm(${f.id},event)">Activer</button>`
          : `<button class="btn-icon del" title="Supprimer" onclick="deleteForm(${f.id},event)">
               <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
             </button>`
        }
      </div>
    </div>`;
  }).join('');
}

/* Expose */
window.openFormsListModal = openFormsListModal;
window.closeMobileList    = closeMobileList;
window.renderMobileList   = renderMobileList;
window.filterMobileList   = filterMobileList;

/* Expose globalement */
Object.assign(window, {
  openSidebar, closeSidebar, togglePreview,
  goView, openModal, closeModal,
  loadFormsList, editForm, deleteForm, doActivateForm,
  loadResponsesForForm, filterResponses, toggleAllChecks, exportCSV,
  openDetailForForm, openResponseDetail, filterUserList, _doSelectUser, _selectUser,
  newForm, loadTpl, publish, onTypeChange, onTriggerChange, sanitizeCmd, updateMeta, insertVar,
  addF, confirmDelField, delF, dupF, toggleFi, collapseAll,
  setFP, toggleReq, toggleQuiz, addOpt, delOpt, setOpt, toggleC,
  togglePal, hidePal, addCond, addAction,
  nextStep, prevStep, resetPrev, undo, redo,
  renderResponsesTable, confirmDialog,
});
</script>
</body>
</html>