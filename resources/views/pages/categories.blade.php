<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Catégories — TradingBot Admin</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400" rel="stylesheet">
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
.topbar-left {
  display: flex; align-items: center; gap: 8px;
  flex: 1 1 auto; min-width: 0; overflow: hidden;
}
.topbar-right { display: flex; align-items: center; gap: 6px; flex-shrink: 0; }

/* Hamburger — visible uniquement mobile */
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
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex-shrink: 1;
}
.topbar-sub { font-size: 12px; color: var(--txt-5); white-space: nowrap; }
.topbar-sep { font-size: 12px; color: var(--txt-5); flex-shrink: 0; }

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
.btn-icon:hover     { color: var(--txt); background: rgba(255,255,255,.09); }
.btn-icon.del:hover { background: var(--red-bg); color: var(--red); }
.btn-icon svg       { width: 13px; height: 13px; stroke: currentColor; fill: none; }

.btn-danger {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 6px 11px; background: var(--red-bg); border: 1px solid rgba(248,113,113,.25);
  border-radius: var(--radius); color: var(--red); font-size: 12px;
  transition: all .15s; cursor: pointer; white-space: nowrap;
}
.btn-danger:hover { background: rgba(248,113,113,.18); }
.btn-danger svg   { width: 11px; height: 11px; stroke: currentColor; fill: none; flex-shrink: 0; }

/* ═══════════════════════════════════════════
   INPUT / SELECT / TEXTAREA
   ═══════════════════════════════════════════ */
.inp, .inp-search, select.inp {
  width: 100%; padding: 8px 10px;
  background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.08);
  border-radius: var(--radius); color: var(--txt); font-size: 12px; font-family: inherit;
  outline: none; transition: border-color .15s;
}
.inp:focus, .inp-search:focus, select.inp:focus { border-color: var(--focus); }
.inp::placeholder, .inp-search::placeholder { color: var(--txt-5); }
textarea.inp { resize: vertical; line-height: 1.5; }
select.inp   { appearance: none; -webkit-appearance: none; cursor: pointer; }

/* Champ avec icône de recherche */
.search-wrap { position: relative; }
.search-wrap .inp-search { padding-left: 30px; }
.search-wrap svg {
  position: absolute; left: 9px; top: 50%; transform: translateY(-50%);
  width: 12px; height: 12px; stroke: var(--txt-5); fill: none; pointer-events: none;
}

/* ═══════════════════════════════════════════
   BADGES
   ═══════════════════════════════════════════ */
.badge { display: inline-flex; align-items: center; padding: 2px 7px; border-radius: 99px; font-size: 10px; font-weight: 500; }
.badge-sky    { background: var(--sky-bg);    color: var(--sky);    }
.badge-green  { background: var(--green-bg);  color: var(--green);  }
.badge-amber  { background: var(--amber-bg);  color: var(--amber);  }
.badge-red    { background: var(--red-bg);    color: var(--red);    }
.badge-violet { background: var(--violet-bg); color: var(--violet); }
.badge-teal   { background: var(--teal-bg);   color: var(--teal);   }
.badge-zinc   { background: rgba(255,255,255,.06); color: var(--txt-3); }

/* ═══════════════════════════════════════════
   MODAL
   ═══════════════════════════════════════════ */
.modal-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.6); z-index: 300;
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
.modal-sm { width: min(400px, 100%); }
.modal-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 20px; border-bottom: 1px solid var(--border); flex-shrink: 0;
}
.modal-head h2 { font-size: 13px; font-weight: 500; color: white; }
.modal-head p  { font-size: 11px; color: var(--txt-4); margin-top: 2px; }
.modal-body { padding: 18px 20px; overflow-y: auto; min-height: 0; display: flex; flex-direction: column; gap: 14px; }
.modal-foot {
  display: flex; align-items: center; justify-content: flex-end;
  gap: 8px; padding: 12px 20px; border-top: 1px solid var(--border); flex-shrink: 0;
}
.field-label { font-size: 11px; color: var(--txt-4); margin-bottom: 6px; display: block; }
.field-label span { color: var(--txt-5); }

/* ═══════════════════════════════════════════
   TOAST
   ═══════════════════════════════════════════ */
#toast-container {
  position: fixed; bottom: 20px; right: 20px;
  display: flex; flex-direction: column; gap: 8px;
  z-index: 9999; pointer-events: none;
}
.toast {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 14px; border-radius: var(--radius);
  background: var(--bg-3); border: 1px solid var(--border);
  font-size: 12px; color: var(--txt);
  box-shadow: 0 8px 32px rgba(0,0,0,.4);
  pointer-events: auto; animation: fadein .2s; max-width: 300px;
}
.toast.success { border-color: rgba(52,211,153,.3); }
.toast.error   { border-color: rgba(248,113,113,.3); }
.toast.info    { border-color: rgba(56,189,248,.3);  }

/* ═══════════════════════════════════════════
   SCROLLBAR
   ═══════════════════════════════════════════ */
::-webkit-scrollbar       { width: 3px; height: 3px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(255,255,255,.08); border-radius: 99px; }

/* ═══════════════════════════════════════════
   ANIMATIONS
   ═══════════════════════════════════════════ */
@keyframes fadein  { from { opacity:0; transform:translateY(4px); } to { opacity:1; transform:translateY(0); } }
@keyframes spin    { to { transform: rotate(360deg); } }
@keyframes shimmer { 0%{background-position:-400px 0} 100%{background-position:400px 0} }
.fadein   { animation: fadein .18s var(--ease); }
.skeleton {
  background: linear-gradient(90deg, rgba(255,255,255,.04) 25%, rgba(255,255,255,.07) 50%, rgba(255,255,255,.04) 75%);
  background-size: 400px 100%; animation: shimmer 1.4s ease infinite; border-radius: var(--radius);
}
.spinner  { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,.1); border-top-color: var(--sky); border-radius: 50%; animation: spin .6s linear infinite; flex-shrink: 0; }

/* ═══════════════════════════════════════════
   LAYOUT CATÉGORIES — 3 colonnes
   ═══════════════════════════════════════════ */
#cat-workspace {
  flex: 1; display: flex; min-height: 0; overflow: hidden;
  align-items: stretch;
}

/* ── Colonne gauche (liste catégories) ── */
#cat-left {
  width: 240px; min-width: 240px; max-width: 240px;
  display: flex; flex-direction: column;
  min-height: 0; max-height: 100%; overflow: hidden;
  border-right: 1px solid var(--border);
  background: var(--bg-1);
}
#cat-stats-bar {
  display: grid; grid-template-columns: repeat(3,1fr);
  gap: 1px; background: var(--border); flex-shrink: 0;
  border-bottom: 1px solid var(--border);
}
.stat-mini {
  text-align: center; padding: 10px 6px;
  background: var(--bg-1);
}
.stat-mini .stat-val  { font-size: 16px; font-weight: 300; color: white; font-variant-numeric: tabular-nums; }
.stat-mini .stat-lbl  { font-size: 10px; color: var(--txt-4); margin-top: 2px; }
.stat-mini .stat-green { color: var(--green); }

#cat-list { flex: 1; min-height: 0; overflow-y: auto; padding: 8px; display: flex; flex-direction: column; gap: 4px; }

/* ── Carte catégorie ── */
.cat-card {
  border-radius: var(--radius-lg);
  background: var(--bg-2); border: 1px solid var(--border-2);
  cursor: pointer; transition: all .15s;
  position: relative; overflow: hidden;
  flex-shrink: 0;
}
.cat-card:hover    { border-color: rgba(255,255,255,.1); background: rgba(255,255,255,.04); }
.cat-card.selected { border-color: rgba(56,189,248,.35); background: rgba(56,189,248,.05); }
.cat-card-accent   { position: absolute; left: 0; top: 0; bottom: 0; width: 3px; }
.cat-card-body     { padding: 10px 10px 10px 16px; }

/* Ligne nom + actions : les deux dans la largeur disponible */
.cat-card-top { display: flex; align-items: center; gap: 6px; margin-bottom: 5px; }
.cat-card-name-wrap {
  display: flex; align-items: center; gap: 5px;
  flex: 1; min-width: 0; /* CRITIQUE : permet text-overflow:ellipsis */
}
.cat-card-dot  { width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0; }
.cat-card-name {
  font-size: 12px; font-weight: 500; color: #e4e4e7;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
  flex: 1; min-width: 0;
}
.cat-card-actions {
  display: flex; align-items: center; gap: 3px;
  flex-shrink: 0; margin-left: 2px;
}

/* Ligne compteur + tendance */
.cat-card-bottom { display: flex; align-items: baseline; justify-content: space-between; gap: 4px; }
.cat-card-count  { font-size: 17px; font-weight: 300; color: white; font-variant-numeric: tabular-nums; flex-shrink: 0; }
.cat-card-trend  { font-size: 10px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

.pbar      { height: 2px; background: rgba(255,255,255,.06); border-radius: 99px; margin-top: 6px; overflow: hidden; }
.pbar-fill { height: 100%; border-radius: 99px; transition: width .3s var(--ease); }

/* Mobile : masquer les actions sur la carte (édition/suppression accessibles via header détail) */
@media (max-width: 768px) {
  .cat-card-actions { display: none; }
  .cat-card-body    { padding: 9px 10px 9px 14px; }
  .cat-card-count   { font-size: 16px; }
}

/* Trigger pill */
.trigger-pill {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 2px 6px; border-radius: 99px;
  background: rgba(255,255,255,.05); color: var(--txt-4); font-size: 10px;
}
.trigger-pill svg { width: 9px; height: 9px; stroke: currentColor; fill: none; }

/* ── Colonne centrale (detail + membres) ── */
#cat-detail {
  flex: 1; min-width: 0; display: flex; flex-direction: column; min-height: 0; overflow: hidden;
  background: var(--bg);
}
.detail-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 12px 16px; border-bottom: 1px solid var(--border); flex-shrink: 0;
  flex-wrap: wrap; gap: 8px;
}
.detail-header-left  { display: flex; align-items: center; gap: 8px; min-width: 0; }
.detail-header-right { display: flex; align-items: center; gap: 6px; flex-wrap: wrap; }
#detail-dot  { width: 10px; height: 10px; border-radius: 50%; background: var(--sky); flex-shrink: 0; }
#detail-name { font-size: 13px; font-weight: 500; color: white; }
#detail-meta { font-size: 11px; color: var(--txt-4); margin-top: 1px; }
#btn-back {
  display: none; /* visible mobile */
  align-items: center; justify-content: center;
  width: 26px; height: 26px; border-radius: var(--radius);
  background: rgba(255,255,255,.04); border: 1px solid var(--border);
  color: var(--txt-4); cursor: pointer; transition: all .15s;
}
#btn-back:hover { color: var(--txt); background: rgba(255,255,255,.08); }
#btn-back svg   { width: 13px; height: 13px; stroke: currentColor; fill: none; }

/* Filtres membres */
.members-filters {
  display: flex; align-items: center; gap: 8px;
  padding: 8px 12px; border-bottom: 1px solid var(--border);
  flex-shrink: 0; flex-wrap: wrap;
}
.members-filters .search-wrap { flex: 1; max-width: 220px; min-width: 120px; }

/* Tab group */
.tab-group {
  display: flex; align-items: center;
  background: rgba(255,255,255,.03); border: 1px solid var(--border-2);
  border-radius: var(--radius); padding: 2px; flex-shrink: 0;
}
.tab {
  display: flex; align-items: center; gap: 5px;
  padding: 5px 10px; border-radius: 6px;
  font-size: 12px; color: var(--txt-4);
  background: none; border: none;
  transition: all .15s; white-space: nowrap; cursor: pointer;
}
.tab:hover  { color: var(--txt-2); }
.tab.active { background: rgba(255,255,255,.08); color: var(--txt); }

/* Table membres */
#members-list { flex: 1; overflow-y: auto; }
.member-row {
  display: flex; align-items: center; gap: 10px;
  padding: 9px 12px; border-bottom: 1px solid var(--border-3);
  transition: background .12s;
}
.member-row:hover { background: var(--hover); }

.av {
  width: 30px; height: 30px; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 10px; font-weight: 600; flex-shrink: 0;
}
.av-green  { background: rgba(52,211,153,.15);  color: var(--green);  }
.av-sky    { background: rgba(56,189,248,.15);   color: var(--sky);    }
.av-violet { background: rgba(167,139,250,.15);  color: var(--violet); }
.av-teal   { background: rgba(45,212,191,.15);   color: var(--teal);   }
.av-amber  { background: rgba(245,158,11,.15);   color: var(--amber);  }
.av-red    { background: rgba(248,113,113,.15);  color: var(--red);    }

.member-name   { font-size: 12px; font-weight: 500; color: #e4e4e7; }
.member-sub    { font-size: 11px; color: var(--txt-4); margin-top: 1px; font-family: 'Geist Mono', monospace; }
.member-info   { flex: 1; min-width: 0; }
.member-date   { font-size: 11px; color: var(--txt-4); font-variant-numeric: tabular-nums; white-space: nowrap; flex-shrink: 0; }

/* Drag-over */
#members-list.drag-over { background: rgba(56,189,248,.04); }

/* ── Colonne droite (règles + stats + actions) ── */
#cat-right {
  width: 220px; min-width: 220px; max-width: 220px;
  display: flex; flex-direction: column; min-height: 0; overflow-y: auto;
  border-left: 1px solid var(--border); background: var(--bg-1);
}
.right-section { padding: 14px; border-bottom: 1px solid var(--border-2); }
.right-section:last-child { border-bottom: none; }
.right-title {
  font-size: 11px; font-weight: 500; color: #d4d4d8;
  margin-bottom: 10px; display: flex; align-items: center; justify-content: space-between;
}
.right-empty { font-size: 11px; color: var(--txt-5); }

/* Règle row */
.rule-row {
  display: flex; align-items: center; justify-content: space-between;
  font-size: 11px; color: var(--txt-3); gap: 6px;
}
.rule-row-left { display: flex; align-items: center; gap: 6px; }
.rule-row svg  { width: 9px; height: 9px; stroke: currentColor; fill: none; flex-shrink: 0; }

/* Stats detail row */
.stat-row {
  display: flex; justify-content: space-between; align-items: center;
  font-size: 11px;
}
.stat-row .stat-key { color: var(--txt-4); }
.stat-row .stat-val { font-weight: 500; color: #d4d4d8; font-variant-numeric: tabular-nums; }

/* Intersection row */
.inter-row {
  display: flex; align-items: center; justify-content: space-between; gap: 6px;
}
.inter-dot  { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.inter-name { font-size: 11px; color: var(--txt-3); flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.inter-cnt  { font-size: 10px; color: var(--txt-5); font-variant-numeric: tabular-nums; }

/* Actions colonne droite */
.right-actions { display: flex; flex-direction: column; gap: 4px; }
.right-actions .btn-ghost { justify-content: flex-start; width: 100%; }
.right-actions .btn-danger { justify-content: flex-start; width: 100%; margin-top: 4px; }

/* ═══════════════════════════════════════════
   DRAWER MEMBRE
   ═══════════════════════════════════════════ */
.drawer-overlay {
  display: none; position: fixed; inset: 0;
  background: rgba(0,0,0,.6); z-index: 250;
  backdrop-filter: blur(2px);
}
.drawer-overlay.open { display: block; }
#member-drawer {
  position: fixed; top: 0; right: 0; height: 100%;
  width: min(360px, 100vw); background: var(--bg-3);
  border-left: 1px solid var(--border); z-index: 251;
  display: flex; flex-direction: column;
  transform: translateX(100%); transition: transform .25s var(--ease);
}
#member-drawer.open { transform: translateX(0); }
.drawer-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 20px; border-bottom: 1px solid var(--border); flex-shrink: 0;
}
.drawer-body { flex: 1; overflow-y: auto; padding: 18px 20px; display: flex; flex-direction: column; gap: 16px; min-height: 0; }
.drawer-foot {
  display: flex; align-items: center; gap: 8px;
  padding: 12px 20px; border-top: 1px solid var(--border); flex-shrink: 0;
}

/* Info card drawer */
.drawer-info-card {
  background: rgba(255,255,255,.025); border: 1px solid var(--border-2);
  border-radius: var(--radius-lg); padding: 14px;
}
.drawer-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.drawer-info-item .di-key { font-size: 10px; color: var(--txt-4); margin-bottom: 3px; }
.drawer-info-item .di-val { font-size: 12px; color: #d4d4d8; }

/* Color dots (modal create/edit) */
.color-dot {
  width: 18px; height: 18px; border-radius: 50%; cursor: pointer;
  border: 2px solid transparent; transition: all .15s; flex-shrink: 0;
}
.color-dot.selected { border-color: white; transform: scale(1.2); }
.color-dot:hover    { transform: scale(1.1); }
.colors-row { display: flex; gap: 8px; flex-wrap: wrap; }

/* Info banner */
.info-banner {
  padding: 10px 12px; border-radius: var(--radius);
  display: flex; gap: 10px; align-items: flex-start;
}
.info-banner svg { flex-shrink: 0; margin-top: 1px; }
.info-banner p   { font-size: 11px; line-height: 1.5; }
.info-banner.sky    { background: rgba(56,189,248,.05);  border: 1px solid rgba(56,189,248,.15); }
.info-banner.sky p  { color: var(--sky); }
.info-banner.sky svg { stroke: var(--sky); }
.info-banner.warn   { background: rgba(251,191,36,.05);  border: 1px solid rgba(251,191,36,.15); }
.info-banner.warn p { color: var(--amber2); }
.info-banner.warn svg { stroke: var(--amber2); }

/* Import drop zone */
#import-drop-zone {
  border: 1px dashed rgba(255,255,255,.12); border-radius: var(--radius-lg);
  padding: 24px; text-align: center; cursor: pointer; transition: all .15s;
  color: var(--txt-4);
}
#import-drop-zone:hover, #import-drop-zone.drag-over {
  border-color: rgba(56,189,248,.35); color: var(--sky);
}
#import-drop-zone svg  { width: 26px; height: 26px; margin: 0 auto 8px; stroke: currentColor; fill: none; display: block; }
#import-drop-zone .dz-main { font-size: 12px; }
#import-drop-zone .dz-hint { font-size: 10px; color: var(--txt-5); margin-top: 4px; font-family: 'Geist Mono', monospace; }

/* ═══════════════════════════════════════════
   RESPONSIVE
   ═══════════════════════════════════════════ */
@media (max-width: 768px) {
  /* Sidebar mobile */
  #hamburger  { display: flex; }
  #sidebar {
    position: fixed; top: 0; left: 0; height: 100%;
    transform: translateX(-100%);
    box-shadow: 4px 0 40px rgba(0,0,0,.6);
  }
  #sidebar.open { transform: translateX(0); }
  #sb-close { display: flex; }

  /* Topbar */
  #topbar { padding: 0 10px; }
  .topbar-sub, .topbar-sep { display: none; }

  /* Colonne droite masquée (apparaît via drawer future) */
  #cat-right  { display: none; }

  /* Colonne gauche + detail : bascule */
  #cat-left   { width: 100%; min-width: 0; max-width: 100%; max-height: 100%; border-right: none; }
  #cat-detail { display: none; }
  #cat-left.hidden-mobile   { display: none; }
  #cat-detail.visible-mobile { display: flex; }

  /* cat-list mobile compact */
  #cat-list  { padding: 6px; gap: 3px; }
  .stat-mini { padding: 8px 4px; }
  .stat-mini .stat-val { font-size: 14px; }

  #btn-back   { display: flex; }

  .detail-header { padding: 10px 12px; }
  .members-filters { padding: 8px; }
  .member-date { display: none; }

  /* Bouton labels masqués */
  .btn-txt { display: none; }
}

@media (max-width: 1100px) and (min-width: 769px) {
  /* Tablette : colonne droite masquée si trop étroit */
  #cat-right  { display: none; }
  #cat-left   { width: 200px; min-width: 200px; }
}

@media (min-width: 769px) {
  .btn-txt { display: inline; }
}

/* Topbar search & boutons */
#topbar-search-wrap { position: relative; width: 180px; }
@media (max-width: 560px) {
  #topbar-search-wrap { display: none; }
}

/* Pagination */
.pagination-bar {
  padding: 8px 12px; border-top: 1px solid var(--border-3);
  font-size: 11px; color: var(--txt-5);
}
.pagination-bar a { color: var(--sky); cursor: pointer; }
</style>
</head>
<body>

<!-- ── Toast container ── -->
<div id="toast-container"></div>

<!-- ── Sidebar overlay ── -->
<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<!-- ══════════════════════════════════════════
     APP
     ══════════════════════════════════════════ -->
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
      <a href="/categories" class="sb-link active">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
        Catégories
      </a>
      <a href="/chat" class="sb-link">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
        Chat direct
        <span id="nav-unread-badge" class="sb-badge" style="display:none;"></span>
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
        <span class="topbar-title">Catégories</span>
        <span class="topbar-sep">·</span>
        <span id="topbar-sub" class="topbar-sub">Chargement…</span>
      </div>
      <div class="topbar-right">
        <div id="topbar-search-wrap" class="search-wrap">
          <svg viewBox="0 0 24 24" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          <input class="inp-search" type="text" placeholder="Rechercher…" oninput="filterCats(this.value)">
        </div>
        <button class="btn-ghost" onclick="App.openImportModal()">
          <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
          <span class="btn-txt">Importer IDs</span>
        </button>
        <button class="btn-primary" onclick="openModal('modal-create')">
          <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
          <span class="btn-txt">Nouvelle catégorie</span>
        </button>
      </div>
    </header>

    <!-- Workspace 3 colonnes -->
    <div id="cat-workspace">

      <!-- ══ COLONNE GAUCHE ══ -->
      <div id="cat-left">
        <!-- Stats bar -->
        <div id="cat-stats-bar">
          <div class="stat-mini">
            <p class="stat-val">—</p>
            <p class="stat-lbl">Catégories</p>
          </div>
          <div class="stat-mini">
            <p class="stat-val">—</p>
            <p class="stat-lbl">Tagués</p>
          </div>
          <div class="stat-mini">
            <p class="stat-val stat-green">—</p>
            <p class="stat-lbl">Tags/mbr</p>
          </div>
        </div>
        <!-- Liste -->
        <div id="cat-list">
          <!-- Skeleton initial -->
          <div class="skeleton" style="height:64px;border-radius:var(--radius-lg);"></div>
          <div class="skeleton" style="height:64px;border-radius:var(--radius-lg);margin-top:4px;opacity:.7;"></div>
          <div class="skeleton" style="height:64px;border-radius:var(--radius-lg);margin-top:4px;opacity:.4;"></div>
        </div>
      </div>

      <!-- ══ COLONNE CENTRALE ══ -->
      <div id="cat-detail">

        <!-- Header détail -->
        <div class="detail-header">
          <div class="detail-header-left">
            <button id="btn-back" onclick="showList()">
              <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="m15 18-6-6 6-6"/></svg>
            </button>
            <span id="detail-dot"></span>
            <div>
              <p id="detail-name">—</p>
              <p id="detail-meta">Sélectionnez une catégorie</p>
            </div>
          </div>
          <div class="detail-header-right">
            <button class="btn-ghost" onclick="App.openAddIdsModal()">
              <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
              Ajouter IDs
            </button>
            <button class="btn-ghost" onclick="App.openMoveModal()">
              <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              Déplacer
            </button>
            <button class="btn-ghost" onclick="App.openEditModal(App.getSelected()?.name_categorie)">
              <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
              Modifier
            </button>
          </div>
        </div>

        <!-- Filtres membres -->
        <div class="members-filters">
          <div class="search-wrap" style="flex:1;max-width:220px;min-width:100px;">
            <svg viewBox="0 0 24 24" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            <input class="inp-search" id="member-search-input" type="text" placeholder="Chercher un membre…">
          </div>
          <div class="tab-group">
            <button class="tab active" data-tab="all"      onclick="App.switchTab(this)">Tous</button>
            <button class="tab"        data-tab="active"   onclick="App.switchTab(this)">Actifs</button>
            <button class="tab"        data-tab="inactive" onclick="App.switchTab(this)">Inactifs</button>
          </div>
          <button class="btn-ghost" style="margin-left:auto;" id="export-csv-btn" onclick="App.exportCSV()">
            <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            <span class="btn-txt">Export CSV</span>
          </button>
        </div>

        <!-- Table membres -->
        <div id="members-list"
             ondragover="event.preventDefault();this.classList.add('drag-over')"
             ondragleave="this.classList.remove('drag-over')"
             ondrop="dropMember(event)">
          <div style="padding:40px 20px;text-align:center;color:var(--txt-5);">
            <p style="font-size:13px;">Sélectionnez une catégorie</p>
          </div>
        </div>

      </div><!-- /cat-detail -->

      <!-- ══ COLONNE DROITE ══ -->
      <div id="cat-right">

        <!-- Règles -->
        <div class="right-section">
          <div class="right-title">
            Règles d'attribution
            <button class="btn-icon" style="width:22px;height:22px;" onclick="openModal('modal-rule')">
              <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
            </button>
          </div>
          <div id="rules-list">
            <p class="right-empty">—</p>
          </div>
        </div>

        <!-- Stats -->
        <div class="right-section">
          <p class="right-title">Statistiques</p>
          <div id="cat-stats-detail" style="display:flex;flex-direction:column;gap:8px;">
            <p class="right-empty">—</p>
          </div>
        </div>

        <!-- Intersections -->
        <div class="right-section">
          <p class="right-title">Présents aussi dans</p>
          <div id="intersections-list" style="display:flex;flex-direction:column;gap:6px;">
            <p class="right-empty">—</p>
          </div>
        </div>

        <!-- Actions -->
        <div class="right-section">
          <p class="right-title">Actions</p>
          <div class="right-actions">
            <button class="btn-ghost" onclick="App.openAddIdsModal()">
              <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
              Ajouter des IDs
            </button>
            <button class="btn-ghost" onclick="App.openMoveModal()">
              <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
              Déplacer des membres
            </button>
            <button class="btn-ghost" onclick="App.openMergeModal()">
              <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M8 6H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-3"/><polyline points="15 3 12 6 9 3"/></svg>
              Fusionner
            </button>
            <button class="btn-ghost" onclick="App.exportCSV()">
              <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Exporter CSV
            </button>
            <button class="btn-danger" onclick="App.openDeleteModal()">
              <svg viewBox="0 0 24 24" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
              Supprimer la catégorie
            </button>
          </div>
        </div>

      </div><!-- /cat-right -->
    </div><!-- /cat-workspace -->

  </div><!-- /main -->
</div><!-- /app -->

<!-- ══════════════════════════════════════════
     MODALS
     ══════════════════════════════════════════ -->

<!-- ── Créer catégorie ── -->
<div class="modal-overlay" id="modal-create">
  <div class="modal">
    <div class="modal-head">
      <h2>Nouvelle catégorie</h2>
      <button class="btn-icon" onclick="closeModal('modal-create')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div>
        <label class="field-label">Nom</label>
        <input class="inp" id="create-name-input" type="text" placeholder="ex: Prospects Webinar Juin">
      </div>
      <div>
        <label class="field-label">Description <span>(optionnel)</span></label>
        <textarea class="inp" id="create-desc-input" style="min-height:52px;" placeholder="À quoi sert cette catégorie…"></textarea>
      </div>
      <div>
        <label class="field-label">Couleur</label>
        <div class="colors-row" id="create-colors">
          <span class="color-dot selected" style="background:#34d399;" onclick="selectColor(this,'create-colors')"></span>
          <span class="color-dot" style="background:#38bdf8;" onclick="selectColor(this,'create-colors')"></span>
          <span class="color-dot" style="background:#fbbf24;" onclick="selectColor(this,'create-colors')"></span>
          <span class="color-dot" style="background:#f87171;" onclick="selectColor(this,'create-colors')"></span>
          <span class="color-dot" style="background:#a78bfa;" onclick="selectColor(this,'create-colors')"></span>
          <span class="color-dot" style="background:#2dd4bf;" onclick="selectColor(this,'create-colors')"></span>
          <span class="color-dot" style="background:#fb923c;" onclick="selectColor(this,'create-colors')"></span>
          <span class="color-dot" style="background:#71717a;" onclick="selectColor(this,'create-colors')"></span>
        </div>
      </div>
      <div>
        <label class="field-label">Règle automatique <span>(optionnel)</span></label>
        <select class="inp" id="create-rule-type" style="margin-bottom:8px;">
          <option value="">Manuelle uniquement</option>
          <option value="link">Rejoint via un lien spécifique</option>
          <option value="inactivity">Inactif depuis X jours</option>
          <option value="survey">A répondu à un sondage</option>
          <option value="subscription">Abonnement actif</option>
          <option value="trade_perf">Trade avec perf ≥ X%</option>
          <option value="keyword">Mot-clé dans un message</option>
          <option value="no_open">N'a pas lu le dernier message</option>
        </select>
        <input class="inp" id="create-rule-value" type="text" placeholder="Valeur / condition (ex: forex-pro, 14…)" style="font-family:'Geist Mono',monospace;">
      </div>
      <div>
        <label class="field-label">Ajouter des IDs maintenant <span>(optionnel)</span></label>
        <textarea class="inp" id="create-ids-input" style="min-height:56px;font-family:'Geist Mono',monospace;" placeholder="123, 456, 789&#10;ou un ID par ligne"></textarea>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeModal('modal-create')">Annuler</button>
      <button class="btn-primary" id="btn-create-confirm">Créer la catégorie</button>
    </div>
  </div>
</div>

<!-- ── Éditer catégorie ── -->
<div class="modal-overlay" id="modal-edit">
  <div class="modal modal-sm">
    <div class="modal-head">
      <h2>Modifier la catégorie</h2>
      <button class="btn-icon" onclick="closeModal('modal-edit')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div>
        <label class="field-label">Nom</label>
        <input class="inp" id="edit-name-input" type="text">
      </div>
      <div>
        <label class="field-label">Couleur</label>
        <div class="colors-row" id="edit-colors">
          <span class="color-dot selected" style="background:#34d399;" onclick="selectColor(this,'edit-colors')"></span>
          <span class="color-dot" style="background:#38bdf8;" onclick="selectColor(this,'edit-colors')"></span>
          <span class="color-dot" style="background:#fbbf24;" onclick="selectColor(this,'edit-colors')"></span>
          <span class="color-dot" style="background:#f87171;" onclick="selectColor(this,'edit-colors')"></span>
          <span class="color-dot" style="background:#a78bfa;" onclick="selectColor(this,'edit-colors')"></span>
          <span class="color-dot" style="background:#2dd4bf;" onclick="selectColor(this,'edit-colors')"></span>
          <span class="color-dot" style="background:#fb923c;" onclick="selectColor(this,'edit-colors')"></span>
          <span class="color-dot" style="background:#71717a;" onclick="selectColor(this,'edit-colors')"></span>
        </div>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeModal('modal-edit')">Annuler</button>
      <button class="btn-primary" id="btn-edit-confirm">Enregistrer</button>
    </div>
  </div>
</div>

<!-- ── Ajouter IDs ── -->
<div class="modal-overlay" id="modal-add-ids">
  <div class="modal modal-sm">
    <div class="modal-head">
      <div>
        <h2>Ajouter des IDs</h2>
        <p id="add-ids-modal-sub">Catégorie : —</p>
      </div>
      <button class="btn-icon" onclick="closeModal('modal-add-ids')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div>
        <label class="field-label">IDs Telegram — séparés par virgule ou un par ligne</label>
        <textarea class="inp" id="add-ids-textarea" style="min-height:90px;font-family:'Geist Mono',monospace;" placeholder="123456&#10;789012&#10;345678, 901234"></textarea>
      </div>
      <div class="info-banner sky">
        <svg viewBox="0 0 24 24" stroke-width="1.5" style="width:13px;height:13px;"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <p>Un membre peut appartenir à plusieurs catégories. Les IDs déjà présents seront ignorés.</p>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeModal('modal-add-ids')">Annuler</button>
      <button class="btn-primary" id="btn-add-ids-confirm">Ajouter les IDs</button>
    </div>
  </div>
</div>

<!-- ── Importer CSV ── -->
<div class="modal-overlay" id="modal-import">
  <div class="modal modal-sm">
    <div class="modal-head">
      <h2>Importer depuis un CSV</h2>
      <button class="btn-icon" onclick="closeModal('modal-import')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div id="import-drop-zone"
           onclick="document.getElementById('import-file-input').click()"
           ondragover="event.preventDefault();this.classList.add('drag-over')"
           ondragleave="this.classList.remove('drag-over')"
           ondrop="handleImportDrop(event)">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        <p class="dz-main" id="import-file-label">Glisser un fichier CSV ou <span style="color:var(--sky);">parcourir</span></p>
        <p class="dz-hint">Colonne attendue : user_id</p>
      </div>
      <input type="file" id="import-file-input" accept=".csv" style="display:none;"
             onchange="document.getElementById('import-file-label').textContent=this.files[0]?.name||'Aucun fichier'">
      <div>
        <label class="field-label">Catégorie cible</label>
        <select class="inp" id="import-category-select"></select>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeModal('modal-import')">Annuler</button>
      <button class="btn-primary" id="btn-import-confirm">Importer</button>
    </div>
  </div>
</div>

<!-- ── Fusionner ── -->
<div class="modal-overlay" id="modal-merge">
  <div class="modal modal-sm">
    <div class="modal-head">
      <h2>Fusionner des catégories</h2>
      <button class="btn-icon" onclick="closeModal('modal-merge')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div>
        <label class="field-label" id="merge-title">Fusionner dans "—"</label>
        <div id="merge-sources-list" style="display:flex;flex-direction:column;gap:8px;"></div>
      </div>
      <div class="info-banner warn">
        <svg viewBox="0 0 24 24" stroke-width="1.5" style="width:13px;height:13px;"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <p>Les catégories sources seront supprimées après fusion. Les doublons seront ignorés.</p>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeModal('modal-merge')">Annuler</button>
      <button class="btn-primary" id="btn-merge-confirm">Fusionner</button>
    </div>
  </div>
</div>

<!-- ── Déplacer ── -->
<div class="modal-overlay" id="modal-move">
  <div class="modal modal-sm">
    <div class="modal-head">
      <h2>Déplacer vers une autre catégorie</h2>
      <button class="btn-icon" onclick="closeModal('modal-move')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div>
        <label class="field-label">Catégorie destination</label>
        <select class="inp" id="move-destination-select"></select>
      </div>
      <div>
        <label class="field-label">Membres concernés</label>
        <div style="display:flex;gap:16px;">
          <label style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--txt-3);cursor:pointer;">
            <input type="radio" name="move-scope" value="selected" checked style="accent-color:var(--sky);">
            <span data-scope="selected">Sélectionnés (0)</span>
          </label>
          <label style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--txt-3);cursor:pointer;">
            <input type="radio" name="move-scope" value="all" style="accent-color:var(--sky);"> Tous
          </label>
        </div>
      </div>
      <div>
        <label class="field-label">Action</label>
        <div style="display:flex;gap:16px;flex-wrap:wrap;">
          <label style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--txt-3);cursor:pointer;">
            <input type="radio" name="move-action" value="copy" checked style="accent-color:var(--sky);"> Copier (garder aussi ici)
          </label>
          <label style="display:flex;align-items:center;gap:8px;font-size:12px;color:var(--txt-3);cursor:pointer;">
            <input type="radio" name="move-action" value="move" style="accent-color:var(--sky);"> Déplacer (retirer ici)
          </label>
        </div>
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeModal('modal-move')">Annuler</button>
      <button class="btn-primary" id="btn-move-confirm">Confirmer</button>
    </div>
  </div>
</div>

<!-- ── Nouvelle règle ── -->
<div class="modal-overlay" id="modal-rule">
  <div class="modal modal-sm">
    <div class="modal-head">
      <h2>Nouvelle règle d'attribution</h2>
      <button class="btn-icon" onclick="closeModal('modal-rule')">
        <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
    <div class="modal-body">
      <div>
        <label class="field-label">Déclencheur</label>
        <select class="inp" id="rule-trigger-type">
          <option value="link">Rejoint via un lien spécifique</option>
          <option value="inactivity">Inactif depuis X jours</option>
          <option value="survey">A répondu à un sondage</option>
          <option value="subscription">Abonnement actif / expiré</option>
          <option value="trade_perf">Trade avec perf ≥ X%</option>
          <option value="keyword">Mot-clé dans un message</option>
          <option value="no_open">N'a pas ouvert le dernier message</option>
        </select>
      </div>
      <div>
        <label class="field-label">Valeur / condition</label>
        <input class="inp" id="rule-trigger-value" type="text" placeholder="ex: forex-pro, 21, intéressé…" style="font-family:'Geist Mono',monospace;">
      </div>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeModal('modal-rule')">Annuler</button>
      <button class="btn-primary" id="btn-rule-confirm">Ajouter la règle</button>
    </div>
  </div>
</div>

<!-- ── Supprimer ── -->
<div class="modal-overlay" id="modal-delete">
  <div class="modal" style="width:min(360px,100%);">
    <div class="modal-body" style="padding:20px;">
      <p id="delete-modal-title" style="font-size:13px;font-weight:500;color:white;margin-bottom:6px;">Supprimer cette catégorie ?</p>
      <p id="delete-modal-desc" style="font-size:12px;color:var(--txt-3);line-height:1.6;">Cette action est irréversible.</p>
    </div>
    <div class="modal-foot">
      <button class="btn-ghost" onclick="closeModal('modal-delete')">Annuler</button>
      <button class="btn-danger" id="btn-delete-confirm">Supprimer définitivement</button>
    </div>
  </div>
</div>

<!-- ══════════════════════════════════════════
     DRAWER MEMBRE
     ══════════════════════════════════════════ -->
<div class="drawer-overlay" id="drawer-overlay" onclick="closeDrawer()"></div>
<div id="member-drawer">
  <div class="drawer-head">
    <p style="font-size:13px;font-weight:500;color:white;">Profil membre</p>
    <button class="btn-icon" onclick="closeDrawer()">
      <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
  </div>
  <div class="drawer-body">
    <div style="display:flex;align-items:center;gap:12px;">
      <div class="av av-sky" id="drawer-av" style="width:44px;height:44px;font-size:14px;">—</div>
      <div>
        <p style="font-size:13px;font-weight:500;color:white;" id="drawer-name">—</p>
        <p style="font-size:11px;color:var(--txt-4);font-family:'Geist Mono',monospace;margin-top:2px;" id="drawer-sub">—</p>
      </div>
    </div>
    <div>
      <p style="font-size:11px;font-weight:500;color:#d4d4d8;margin-bottom:8px;">Catégories actives</p>
      <div id="drawer-categories" style="display:flex;flex-wrap:wrap;gap:6px;"></div>
    </div>
    <div class="drawer-info-card">
      <div id="drawer-info"></div>
    </div>
    <div>
      <p style="font-size:11px;font-weight:500;color:#d4d4d8;margin-bottom:8px;">Ajouter à une catégorie</p>
      <select class="inp" id="drawer-add-cat"></select>
    </div>
  </div>
  <div class="drawer-foot">
    <button class="btn-primary" id="btn-drawer-add-cat" style="flex:1;justify-content:center;">
      <svg viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      Ajouter à la catégorie
    </button>
    <button class="btn-icon del" id="btn-drawer-remove" title="Retirer de la catégorie actuelle">
      <svg viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
  </div>
</div>

<!-- ══════════════════════════════════════════
     SCRIPTS
     ══════════════════════════════════════════ -->
<script>
/* ═══════════════════════════════════════════
   SIDEBAR / TOPBAR JS
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
   UTILS — MODALS / TOAST / DRAWER
   ═══════════════════════════════════════════ */
function openModal(id)  { document.getElementById(id)?.classList.add('open'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('open'); }
function openDrawer()   {
  document.getElementById('member-drawer').classList.add('open');
  document.getElementById('drawer-overlay').classList.add('open');
}
function closeDrawer()  {
  document.getElementById('member-drawer').classList.remove('open');
  document.getElementById('drawer-overlay').classList.remove('open');
}

document.addEventListener('click', e => {
  if (e.target.classList.contains('modal-overlay')) e.target.classList.remove('open');
});
document.addEventListener('keydown', e => {
  if (e.key !== 'Escape') return;
  document.querySelectorAll('.modal-overlay.open').forEach(m => m.classList.remove('open'));
  closeDrawer();
  if (window.innerWidth <= 768) closeSidebar();
});

function showToast(msg, type = 'info', dur = 3000) {
  const colors = {
    success: { bg:'rgba(52,211,153,.12)',  bd:'rgba(52,211,153,.3)',  txt:'#34d399' },
    error:   { bg:'rgba(248,113,113,.12)', bd:'rgba(248,113,113,.3)', txt:'#f87171' },
    info:    { bg:'rgba(56,189,248,.12)',  bd:'rgba(56,189,248,.3)',  txt:'#38bdf8' },
  };
  const c = colors[type] || colors.info;
  const t = document.createElement('div');
  t.className = `toast ${type}`;
  t.style.cssText = `background:${c.bg};border-color:${c.bd};color:${c.txt};`;
  t.textContent = msg;
  document.getElementById('toast-container').appendChild(t);
  setTimeout(() => { t.style.opacity='0'; t.style.transition='opacity .2s'; setTimeout(()=>t.remove(),200); }, dur);
}
// Alias utilisé dans l'ancienne API
const toast = (msg, type='success') => showToast(msg, type);

/* ── Color dots ── */
function selectColor(dot, groupId) {
  const group = groupId
    ? document.getElementById(groupId)
    : dot.closest('[id*="colors"]') || dot.parentElement;
  group?.querySelectorAll('.color-dot').forEach(d => d.classList.remove('selected'));
  dot.classList.add('selected');
}

/* ── Mobile : bascule liste ↔ détail ── */
function showList() {
  document.getElementById('cat-left')?.classList.remove('hidden-mobile');
  document.getElementById('cat-detail')?.classList.remove('visible-mobile');
}

/* ── Filtre catégories depuis topbar ── */
function filterCats(q) {
  const term = q.toLowerCase();
  document.querySelectorAll('.cat-card').forEach(card => {
    const name = (card.dataset.name || '').toLowerCase();
    card.style.display = name.includes(term) ? '' : 'none';
  });
}

/* ── Import CSV drop ── */
function handleImportDrop(e) {
  e.preventDefault();
  const file = e.dataTransfer.files?.[0];
  if (!file) return;
  const dt = new DataTransfer(); dt.items.add(file);
  document.getElementById('import-file-input').files = dt.files;
  document.getElementById('import-file-label').textContent = file.name;
  e.currentTarget.classList.remove('drag-over');
}

/* ── Drag & drop dans members-list ── */
function dropMember(e) {
  e.preventDefault();
  document.getElementById('members-list').classList.remove('drag-over');
  const raw = e.dataTransfer.getData('text/plain');
  if (!raw) return;
  const ids = parseIds(raw);
  if (!ids.length || !App.getSelected()) return;
  apiAddMembers(App.getSelected().name_categorie, ids)
    .then(res => { toast(`${res.added} membre(s) ajouté(s)`); App.loadMembersPublic(); })
    .catch(err => toast(err.message, 'error'));
}

/* ═══════════════════════════════════════════
   API — categories_api.js
   ═══════════════════════════════════════════ */
const API_URL = 'https://fdkvip.com';

async function apiFetch(path, options = {}) {
  const res = await fetch(API_URL + path, {
    headers: { 'Content-Type': 'application/json', ...(options.headers || {}) },
    ...options
  });
  if (!res.ok) {
    const err = await res.json().catch(() => ({ detail: res.statusText }));
    throw new Error(err.detail || `Erreur ${res.status}`);
  }
  return res.json();
}

async function apiGetStats()              { return apiFetch('/categories/stats'); }
async function apiGetCategories()         { return apiFetch('/categorie'); }
async function apiCreateCategory(payload) { return apiFetch('/categories', { method:'POST', body:JSON.stringify(payload) }); }
async function apiUpdateCategory(name, payload) {
  return apiFetch(`/categories/${encodeURIComponent(name)}`, { method:'PUT', body:JSON.stringify(payload) });
}
async function apiDeleteCategory(name) {
  return apiFetch(`/categories/${encodeURIComponent(name)}`, { method:'DELETE' });
}
async function apiGetMembers(name, filters = {}) {
  const p = new URLSearchParams();
  if (filters.search)        p.set('search',        filters.search);
  if (filters.active_only)   p.set('active_only',   'true');
  if (filters.inactive_only) p.set('inactive_only', 'true');
  if (filters.limit)         p.set('limit',         filters.limit);
  if (filters.offset)        p.set('offset',        filters.offset);
  return apiFetch(`/categories/${encodeURIComponent(name)}/members${p.toString()?'?'+p:''}`);
}
async function apiAddMembers(name, userIds) {
  return apiFetch(`/categories/${encodeURIComponent(name)}/members`, {
    method:'POST', body:JSON.stringify({ user_ids:userIds, added_by:'manual' })
  });
}
async function apiRemoveMember(name, tid) {
  return apiFetch(`/categories/${encodeURIComponent(name)}/members/${tid}`, { method:'DELETE' });
}
async function apiMoveMembers(source, destination, userIds, action) {
  return apiFetch('/categories/members/move', {
    method:'POST', body:JSON.stringify({ source, destination, user_ids:userIds, action })
  });
}
async function apiMergeCategories(target, sources) {
  return apiFetch('/categories/merge', { method:'POST', body:JSON.stringify({ target, sources }) });
}
async function apiImportCSV(name, file) {
  const form = new FormData(); form.append('file', file);
  return apiFetch(`/categories/${encodeURIComponent(name)}/import`, { method:'POST', headers:{}, body:form });
}
async function apiGetRules(name)          { return apiFetch(`/categories/${encodeURIComponent(name)}/rules`); }
async function apiAddRule(name, type, val) {
  return apiFetch(`/categories/${encodeURIComponent(name)}/rules`, {
    method:'POST', body:JSON.stringify({ trigger_type:type, trigger_value:val })
  });
}
async function apiDeleteRule(ruleId)      { return apiFetch(`/categories/rules/${ruleId}`, { method:'DELETE' }); }
async function apiGetCategoryStats(name)  { return apiFetch(`/categories/${encodeURIComponent(name)}/stats`); }
async function apiGetIntersections(name)  { return apiFetch(`/categories/${encodeURIComponent(name)}/intersections`); }
async function apiGetMemberProfile(tid)   { return apiFetch(`/members/${tid}/profile`); }
async function apiBroadcast(payload)      { return apiFetch('/broadcast', { method:'POST', body:JSON.stringify(payload) }); }

/* ═══════════════════════════════════════════
   RENDER — categories_render.js
   ═══════════════════════════════════════════ */
function relativeTime(dateStr) {
  if (!dateStr) return '—';
  const diff = Date.now() - new Date(dateStr).getTime();
  const m = Math.floor(diff/60000);
  if (m < 1)   return "À l'instant";
  if (m < 60)  return `Il y a ${m}min`;
  const h = Math.floor(m/60);
  if (h < 24)  return `Il y a ${h}h`;
  const d = Math.floor(h/24);
  if (d === 1) return 'Hier';
  if (d < 30)  return `Il y a ${d}j`;
  return new Date(dateStr).toLocaleDateString('fr-FR',{day:'numeric',month:'short'});
}
function initials(name) {
  if (!name) return '??';
  return name.trim().split(/\s+/).slice(0,2).map(w=>w[0].toUpperCase()).join('');
}
const AV_COLORS = ['av-green','av-sky','av-violet','av-teal','av-amber','av-red'];
function avColor(tid) { return AV_COLORS[Math.abs(tid||0)%AV_COLORS.length]; }

const TRIGGER_MAP = {
  link:         { label:'Lien',    icon:`<svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>` },
  inactivity:   { label:'Inactif', icon:`<svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>` },
  survey:       { label:'Sondage', icon:`<svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>` },
  subscription: { label:'Abo',     icon:`<svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>` },
  trade_perf:   { label:'Trade',   icon:`<svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M3 3v18h18"/><path d="m7 16 4-4 4 4 4-6"/></svg>` },
  keyword:      { label:'Msg',     icon:`<svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>` },
  no_open:      { label:'Auto',    icon:`<svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="m13 2-3 6h-7l6 4-2 7 6-4 6 4-2-7 6-4h-7z"/></svg>` },
};

function triggerPill(rules) {
  if (!rules || !rules.length) return '';
  const t = TRIGGER_MAP[rules[0].trigger_type] || { label:'Auto', icon:'' };
  return `<span class="trigger-pill">${t.icon}${t.label}</span>`;
}

function renderGlobalStats(stats) {
  const sub = document.getElementById('topbar-sub');
  if (sub) sub.textContent = `${stats.total_categories??0} catégories · ${(stats.tagged_members??0).toLocaleString('fr-FR')} membres tagués`;

  const bar = document.getElementById('cat-stats-bar');
  if (!bar) return;
  bar.innerHTML = `
    <div class="stat-mini"><p class="stat-val">${stats.total_categories??0}</p><p class="stat-lbl">Catégories</p></div>
    <div class="stat-mini"><p class="stat-val">${(stats.tagged_members??0).toLocaleString('fr-FR')}</p><p class="stat-lbl">Tagués</p></div>
    <div class="stat-mini"><p class="stat-val stat-green">${stats.avg_tags_per_member??'—'}</p><p class="stat-lbl">Tags/mbr</p></div>
  `;
}

function renderCatCard(cat, maxCount) {
  const count = cat.member_count ?? 0;
  const pct   = maxCount > 0 ? Math.round((count/maxCount)*100) : 0;
  const color = cat.color || '#38bdf8';
  const trend = (cat.new_this_month??0) > 0
    ? `<span style="color:${color};font-size:10px;">+${cat.new_this_month} ce mois</span>`
    : `<span class="right-empty">—</span>`;
  return `
    <div class="cat-card fadein" id="cat-${CSS.escape(cat.name_categorie)}" data-name="${cat.name_categorie}" onclick="App.selectCat(this)">
      <div class="cat-card-accent" style="background:${color};"></div>
      <div class="cat-card-body">
        <div class="cat-card-top">
          <div class="cat-card-name-wrap">
            <span class="cat-card-dot" style="background:${color};"></span>
            <span class="cat-card-name">${cat.name_categorie}</span>
          </div>
          <div class="cat-card-actions">
            ${triggerPill(cat.rules)}
            <button class="btn-icon" style="width:22px;height:22px;" onclick="event.stopPropagation();App.openEditModal('${cat.name_categorie}')" title="Modifier">
              <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>
            <button class="btn-icon del" style="width:22px;height:22px;" onclick="event.stopPropagation();App.openDeleteModalByName('${cat.name_categorie}')" title="Supprimer">
              <svg viewBox="0 0 24 24" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
            </button>
          </div>
        </div>
        <div class="cat-card-bottom">
          <span class="cat-card-count">${count.toLocaleString('fr-FR')}</span>
          ${trend}
        </div>
        <div class="pbar"><div class="pbar-fill" style="width:${pct}%;background:${color};"></div></div>
      </div>
    </div>`;
}

function renderCatList(categories) {
  const list = document.getElementById('cat-list');
  if (!list) return;
  if (!categories.length) {
    list.innerHTML = `<div style="padding:40px 16px;text-align:center;color:var(--txt-5);"><p style="font-size:13px;">Aucune catégorie</p><p style="font-size:11px;margin-top:4px;">Créez votre première catégorie</p></div>`;
    return;
  }
  const maxCount = Math.max(...categories.map(c=>c.member_count??0), 1);
  list.innerHTML = categories.map(cat => renderCatCard(cat, maxCount)).join('');
}

function renderDetailHeader(cat) {
  const color = cat.color || '#38bdf8';
  const dot   = document.getElementById('detail-dot');
  const name  = document.getElementById('detail-name');
  const meta  = document.getElementById('detail-meta');
  if (dot)  dot.style.background = color;
  if (name) name.textContent = cat.name_categorie;
  if (meta) meta.textContent = `${(cat.member_count??0).toLocaleString('fr-FR')} membres`;
}

function renderMemberRow(member) {
  const ini   = initials(member.name || String(member.telegram_id));
  const avCls = avColor(member.telegram_id);
  const handle = member.username ? `@${member.username} · ` : '';
  const time  = relativeTime(member.last_activity);
  return `
    <div class="member-row fadein" data-id="${member.telegram_id}">
      <input type="checkbox" style="accent-color:var(--sky);flex-shrink:0;" onchange="App.toggleSelect(${member.telegram_id},this.checked)">
      <div class="av ${avCls}">${ini}</div>
      <div class="member-info">
        <p class="member-name">${member.name||'—'}</p>
        <p class="member-sub">${handle}ID ${member.telegram_id}</p>
      </div>
      <p class="member-date">${time}</p>
      <button class="btn-icon" style="width:24px;height:24px;" onclick="App.openMemberDrawer(${member.telegram_id})" title="Voir profil">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
      </button>
      <button class="btn-icon del" style="width:24px;height:24px;" onclick="App.removeMember(${member.telegram_id})" title="Retirer">
        <svg viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>`;
}

function renderMembersList(data) {
  const list = document.getElementById('members-list');
  if (!list) return;
  const { members, total, limit=50, offset=0 } = data;
  if (!members.length) {
    list.innerHTML = `<div style="padding:40px 20px;text-align:center;color:var(--txt-5);"><p style="font-size:13px;">Aucun membre</p></div>`;
    return;
  }
  const from = offset+1, to = Math.min(offset+members.length, total);
  const hasMore = to < total;
  list.innerHTML = members.map(renderMemberRow).join('') + `
    <div class="pagination-bar">
      Affichage ${from}–${to} sur ${total.toLocaleString('fr-FR')}
      ${hasMore ? ` · <a onclick="App.loadMoreMembers()">Voir plus →</a>` : ''}
    </div>`;
}

function renderSkeleton(containerId, rows=4) {
  const el = document.getElementById(containerId);
  if (!el) return;
  el.innerHTML = Array(rows).fill(`
    <div style="padding:10px 12px;border-bottom:1px solid var(--border-3);">
      <div class="skeleton" style="height:10px;width:60%;margin-bottom:6px;"></div>
      <div class="skeleton" style="height:8px;width:40%;"></div>
    </div>`).join('');
}

function renderRules(rules) {
  const el = document.getElementById('rules-list');
  if (!el) return;
  if (!rules || !rules.length) { el.innerHTML = `<p class="right-empty">Aucune règle active</p>`; return; }
  el.innerHTML = rules.map(rule => {
    const t = TRIGGER_MAP[rule.trigger_type] || { label:rule.trigger_type, icon:'' };
    const val = rule.trigger_value
      ? `<span style="color:var(--sky);font-family:'Geist Mono',monospace;">${rule.trigger_value}</span>`
      : '';
    return `
      <div class="rule-row">
        <div class="rule-row-left">${t.icon}<span>${t.label} ${val}</span></div>
        <button class="btn-icon" style="width:20px;height:20px;" onclick="App.deleteRule(${rule.id})">
          <svg viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
      </div>`;
  }).join('');
}

function renderCategoryStats(stats) {
  const el = document.getElementById('cat-stats-detail');
  if (!el) return;
  const winRate = stats.win_rate != null
    ? `<span style="color:var(--green);">${stats.win_rate}%</span>`
    : `<span class="right-empty">—</span>`;
  const lastBc = stats.last_broadcast ? relativeTime(stats.last_broadcast) : '—';
  el.innerHTML = `
    <div class="stat-row"><span class="stat-key">Win rate moyen</span><span class="stat-val">${winRate}</span></div>
    <div class="stat-row"><span class="stat-key">Actifs (7j)</span><span class="stat-val">${stats.active_7d??'—'}</span></div>
    <div class="stat-row"><span class="stat-key">Multi-catégories</span><span class="stat-val">${stats.multi_categories??'—'}</span></div>
    <div class="stat-row"><span class="stat-key">Dernière campagne</span><span class="stat-val" style="color:var(--txt-4);">${lastBc}</span></div>
  `;
}

function renderIntersections(intersections) {
  const el = document.getElementById('intersections-list');
  if (!el) return;
  if (!intersections || !intersections.length) { el.innerHTML = `<p class="right-empty">Aucune intersection</p>`; return; }
  el.innerHTML = intersections.map(i => `
    <div class="inter-row">
      <span class="inter-dot" style="background:${i.color||'var(--txt-5)'};"></span>
      <span class="inter-name">${i.name_categorie}</span>
      <span class="inter-cnt">${i.shared_count}</span>
    </div>`).join('');
}

function renderMemberDrawer(profile, allCategories) {
  const av   = document.getElementById('drawer-av');
  const name = document.getElementById('drawer-name');
  const sub  = document.getElementById('drawer-sub');
  if (av)   { av.textContent = initials(profile.name||String(profile.telegram_id)); av.className=`av ${avColor(profile.telegram_id)}`; av.style.cssText='width:44px;height:44px;font-size:14px;'; }
  if (name) name.textContent = profile.name || '—';
  if (sub)  sub.textContent  = `${profile.phone||''} · ID ${profile.telegram_id}`;

  const catEl = document.getElementById('drawer-categories');
  if (catEl) {
    catEl.innerHTML = (profile.categories||[]).map(c =>
      `<span class="badge" style="background:${c.color}22;color:${c.color};">${c.name_categorie}</span>`
    ).join('') || '<span class="right-empty">Aucune catégorie</span>';
  }

  const infoEl = document.getElementById('drawer-info');
  if (infoEl) {
    const ts = profile.trading_stats;
    infoEl.innerHTML = `
      <div class="drawer-info-grid">
        <div class="drawer-info-item"><p class="di-key">Inscrit le</p><p class="di-val">${profile.created_at?new Date(profile.created_at).toLocaleDateString('fr-FR',{day:'numeric',month:'short',year:'numeric'}):'—'}</p></div>
        <div class="drawer-info-item"><p class="di-key">Dernière activité</p><p class="di-val">${relativeTime(profile.last_activity)}</p></div>
        <div class="drawer-info-item"><p class="di-key">Trades</p><p class="di-val">${ts?.total_trades??'—'}</p></div>
        <div class="drawer-info-item"><p class="di-key">Win rate</p><p class="di-val" style="color:${ts?.win_rate?'var(--green)':'var(--txt-4)'};">${ts?.win_rate!=null?ts.win_rate+'%':'—'}</p></div>
      </div>`;
  }

  const sel = document.getElementById('drawer-add-cat');
  if (sel && allCategories) {
    const existing = (profile.categories||[]).map(c=>c.name_categorie);
    sel.innerHTML = `<option value="">Ajouter à une catégorie…</option>` +
      allCategories.filter(c=>!existing.includes(c.name_categorie))
        .map(c=>`<option value="${c.name_categorie}">${c.name_categorie}</option>`).join('');
  }
}

function renderEditModal(cat) {
  const inp = document.getElementById('edit-name-input');
  if (inp) inp.value = cat.name_categorie;
  document.querySelectorAll('#edit-colors .color-dot').forEach(dot => {
    dot.classList.toggle('selected', dot.style.background === cat.color);
  });
}

function renderMergeModal(currentName, categories) {
  const title = document.getElementById('merge-title');
  if (title) title.textContent = `Fusionner dans "${currentName}"`;
  const list = document.getElementById('merge-sources-list');
  if (!list) return;
  list.innerHTML = categories.filter(c=>c.name_categorie!==currentName).map(c => `
    <label style="display:flex;align-items:center;gap:10px;font-size:12px;color:var(--txt-3);cursor:pointer;">
      <input type="checkbox" value="${c.name_categorie}" style="accent-color:var(--sky);">
      ${c.name_categorie} (${(c.member_count??0).toLocaleString('fr-FR')})
    </label>`).join('');
}

function renderMoveModal(currentName, categories) {
  const sel = document.getElementById('move-destination-select');
  if (!sel) return;
  sel.innerHTML = categories.filter(c=>c.name_categorie!==currentName)
    .map(c=>`<option value="${c.name_categorie}">${c.name_categorie} (${(c.member_count??0).toLocaleString('fr-FR')})</option>`)
    .join('');
}

function renderDeleteModal(cat) {
  const title = document.getElementById('delete-modal-title');
  const desc  = document.getElementById('delete-modal-desc');
  if (title) title.textContent = `Supprimer "${cat.name_categorie}" ?`;
  if (desc)  desc.textContent  = `Les ${(cat.member_count??0).toLocaleString('fr-FR')} membres ne seront pas supprimés — ils perdront uniquement ce tag. Cette action est irréversible.`;
}

function renderImportModal(categories) {
  const sel = document.getElementById('import-category-select');
  if (!sel) return;
  sel.innerHTML = categories.map(c=>`<option value="${c.name_categorie}">${c.name_categorie} (${c.member_count??0})</option>`).join('')
    + `<option value="__new__">+ Créer une nouvelle catégorie</option>`;
}

/* ═══════════════════════════════════════════
   APP — categories_app.js
   ═══════════════════════════════════════════ */
function parseIds(raw) {
  return raw.split(/[\n,]+/).map(s=>parseInt(s.trim(),10)).filter(n=>!isNaN(n)&&n>0);
}

const App = (() => {
  const State = {
    categories:           [],
    selected:             null,
    members:              [],
    membersTotal:         0,
    selectedMembers:      [],
    filters: { search:'', tab:'all', offset:0, limit:50 },
    currentMemberProfile: null
  };

  async function init() {
    await loadGlobalStats();
    await loadCategories();
    bindModalActions();
    bindFilterEvents();
  }

  async function loadGlobalStats() {
    try { renderGlobalStats(await apiGetStats()); }
    catch(e) { console.error('[Stats]', e.message); }
  }

  async function loadCategories() {
    renderSkeleton('cat-list', 4);
    try {
      State.categories = await apiGetCategories();
      renderCatList(State.categories);
      if (State.categories.length && !State.selected) {
        const first = document.querySelector('.cat-card');
        if (first) selectCat(first);
      }
    } catch(e) { toast('Erreur chargement catégories','error'); console.error(e); }
  }

  async function loadMembers() {
    if (!State.selected) return;
    renderSkeleton('members-list', 6);
    const filters = {
      search:        State.filters.search || undefined,
      active_only:   State.filters.tab === 'active',
      inactive_only: State.filters.tab === 'inactive',
      limit:         State.filters.limit,
      offset:        State.filters.offset
    };
    try {
      const data = await apiGetMembers(State.selected.name_categorie, filters);
      State.members      = data.members;
      State.membersTotal = data.total;
      renderMembersList(data);
    } catch(e) { toast('Erreur chargement membres','error'); }
  }

  async function loadRightPanel() {
    if (!State.selected) return;
    const name = State.selected.name_categorie;
    try { renderRules(await apiGetRules(name)); } catch(e) {}
    try { renderCategoryStats(await apiGetCategoryStats(name)); } catch(e) {}
    try { renderIntersections(await apiGetIntersections(name)); } catch(e) {}
  }

  function selectCat(cardEl) {
    const name = cardEl.dataset.name;
    State.selected = State.categories.find(c=>c.name_categorie===name) || null;
    if (!State.selected) return;

    document.querySelectorAll('.cat-card').forEach(c=>c.classList.remove('selected'));
    cardEl.classList.add('selected');
    renderDetailHeader(State.selected);

    State.filters.offset   = 0;
    State.filters.search   = '';
    State.selectedMembers  = [];

    const inp = document.getElementById('member-search-input');
    if (inp) inp.value = '';

    loadMembers();
    loadRightPanel();

    // Mobile : basculer en vue détail
    if (window.innerWidth <= 768) {
      document.getElementById('cat-left')?.classList.add('hidden-mobile');
      document.getElementById('cat-detail')?.classList.add('visible-mobile');
    }
  }

  function bindFilterEvents() {
    const memberSearch = document.getElementById('member-search-input');
    if (memberSearch) {
      let debounce;
      memberSearch.addEventListener('input', e => {
        clearTimeout(debounce);
        debounce = setTimeout(() => {
          State.filters.search = e.target.value.trim();
          State.filters.offset = 0;
          loadMembers();
        }, 350);
      });
    }
  }

  function switchTab(el) {
    el.closest('.tab-group').querySelectorAll('.tab').forEach(t=>t.classList.remove('active'));
    el.classList.add('active');
    State.filters.tab    = el.dataset.tab || 'all';
    State.filters.offset = 0;
    loadMembers();
  }

  function loadMoreMembers() {
    State.filters.offset += State.filters.limit;
    loadMembers();
  }

  function toggleSelect(telegramId, checked) {
    if (checked) { if (!State.selectedMembers.includes(telegramId)) State.selectedMembers.push(telegramId); }
    else          { State.selectedMembers = State.selectedMembers.filter(id=>id!==telegramId); }
    const scopeLabel = document.querySelector('[data-scope="selected"]');
    if (scopeLabel) scopeLabel.textContent = `Sélectionnés (${State.selectedMembers.length})`;
  }

  async function removeMember(telegramId) {
    if (!State.selected) return;
    if (!confirm('Retirer ce membre de la catégorie ?')) return;
    try {
      await apiRemoveMember(State.selected.name_categorie, telegramId);
      toast('Membre retiré');
      await loadMembers(); await loadGlobalStats();
    } catch(e) { toast(e.message,'error'); }
  }

  async function openMemberDrawer(telegramId) {
    try {
      const profile = await apiGetMemberProfile(telegramId);
      State.currentMemberProfile = profile;
      renderMemberDrawer(profile, State.categories);
      openDrawer();
    } catch(e) { toast('Impossible de charger le profil','error'); }
  }

  async function addMemberToCategory() {
    const sel  = document.getElementById('drawer-add-cat');
    const name = sel?.value;
    if (!name || !State.currentMemberProfile) return;
    try {
      await apiAddMembers(name, [State.currentMemberProfile.telegram_id]);
      toast(`Ajouté à ${name}`);
      const updated = await apiGetMemberProfile(State.currentMemberProfile.telegram_id);
      renderMemberDrawer(updated, State.categories);
      State.currentMemberProfile = updated;
      if (State.selected?.name_categorie === name) loadMembers();
    } catch(e) { toast(e.message,'error'); }
  }

  async function removeMemberFromDrawer() {
    if (!State.selected || !State.currentMemberProfile) return;
    if (!confirm('Retirer ce membre de la catégorie actuelle ?')) return;
    try {
      await apiRemoveMember(State.selected.name_categorie, State.currentMemberProfile.telegram_id);
      toast('Membre retiré');
      closeDrawer();
      loadMembers();
    } catch(e) { toast(e.message,'error'); }
  }

  function exportCSV() {
    if (!State.members.length) return;
    const rows = [['telegram_id','name','phone','last_activity']];
    State.members.forEach(m => rows.push([m.telegram_id, m.name||'', m.phone||'', m.last_activity||'']));
    const csv  = rows.map(r=>r.join(',')).join('\n');
    const blob = new Blob([csv],{type:'text/csv'});
    const url  = URL.createObjectURL(blob);
    const a    = document.createElement('a');
    a.href = url; a.download = `${State.selected?.name_categorie||'membres'}.csv`; a.click();
    URL.revokeObjectURL(url);
  }

  function bindModalActions() {

    // Créer catégorie
    document.getElementById('btn-create-confirm')?.addEventListener('click', async () => {
      const name      = document.getElementById('create-name-input')?.value.trim();
      const desc      = document.getElementById('create-desc-input')?.value.trim();
      const color     = document.querySelector('#create-colors .color-dot.selected')?.style.background || '#38bdf8';
      const ruleType  = document.getElementById('create-rule-type')?.value;
      const ruleValue = document.getElementById('create-rule-value')?.value.trim();
      const idsRaw    = document.getElementById('create-ids-input')?.value.trim();
      if (!name) { toast('Nom requis','error'); return; }
      const payload = { name_categorie:name, color, description:desc };
      if (ruleType)  payload.rule       = { trigger_type:ruleType, trigger_value:ruleValue };
      if (idsRaw)    payload.member_ids = parseIds(idsRaw);
      try {
        await apiCreateCategory(payload);
        toast(`Catégorie "${name}" créée`);
        closeModal('modal-create');
        await loadCategories(); await loadGlobalStats();
      } catch(e) { toast(e.message,'error'); }
    });

    // Éditer catégorie
    document.getElementById('btn-edit-confirm')?.addEventListener('click', async () => {
      if (!State.selected) return;
      const newName = document.getElementById('edit-name-input')?.value.trim();
      const color   = document.querySelector('#edit-colors .color-dot.selected')?.style.background;
      const payload = {};
      if (newName && newName !== State.selected.name_categorie) payload.new_name = newName;
      if (color)   payload.color = color;
      if (!Object.keys(payload).length) { closeModal('modal-edit'); return; }
      try {
        const res = await apiUpdateCategory(State.selected.name_categorie, payload);
        if (res.status === 'error') { toast(res.detail,'error'); return; }
        toast('Catégorie mise à jour');
        closeModal('modal-edit');
        await loadCategories(); await loadGlobalStats();
      } catch(e) { toast(e.message,'error'); }
    });

    // Ajouter IDs
    document.getElementById('btn-add-ids-confirm')?.addEventListener('click', async () => {
      if (!State.selected) return;
      const raw = document.getElementById('add-ids-textarea')?.value.trim();
      if (!raw) return;
      const ids = parseIds(raw);
      if (!ids.length) { toast('Aucun ID valide','error'); return; }
      try {
        const res = await apiAddMembers(State.selected.name_categorie, ids);
        toast(`${res.added} ajoutés · ${res.ignored} ignorés`);
        closeModal('modal-add-ids');
        await loadMembers(); await loadGlobalStats(); await loadCategories();
      } catch(e) { toast(e.message,'error'); }
    });

    // Importer CSV
    document.getElementById('btn-import-confirm')?.addEventListener('click', async () => {
      const file    = document.getElementById('import-file-input')?.files?.[0];
      const catName = document.getElementById('import-category-select')?.value;
      if (!file)    { toast('Fichier requis','error'); return; }
      if (!catName) { toast('Catégorie requise','error'); return; }
      try {
        const res = await apiImportCSV(catName, file);
        toast(`Import terminé : ${res.added} ajoutés`);
        closeModal('modal-import');
        await loadCategories(); await loadGlobalStats();
        if (State.selected?.name_categorie === catName) loadMembers();
      } catch(e) { toast(e.message,'error'); }
    });

    // Déplacer
    document.getElementById('btn-move-confirm')?.addEventListener('click', async () => {
      if (!State.selected) return;
      const dest   = document.getElementById('move-destination-select')?.value;
      const scope  = document.querySelector('input[name="move-scope"]:checked')?.value;
      const action = document.querySelector('input[name="move-action"]:checked')?.value || 'copy';
      if (!dest) { toast('Destination requise','error'); return; }
      const ids = scope==='all' ? 'all' : State.selectedMembers;
      if (ids!=='all' && !ids.length) { toast('Aucun membre sélectionné','error'); return; }
      try {
        const res = await apiMoveMembers(State.selected.name_categorie, dest, ids, action);
        toast(`${res.count} membres ${action==='move'?'déplacés':'copiés'} vers ${dest}`);
        closeModal('modal-move');
        State.selectedMembers = [];
        await loadMembers(); await loadCategories();
      } catch(e) { toast(e.message,'error'); }
    });

    // Fusionner
    document.getElementById('btn-merge-confirm')?.addEventListener('click', async () => {
      if (!State.selected) return;
      const checked = [...document.querySelectorAll('#merge-sources-list input:checked')].map(cb=>cb.value);
      if (!checked.length) { toast('Sélectionnez au moins une source','error'); return; }
      try {
        await apiMergeCategories(State.selected.name_categorie, checked);
        toast(`Fusion terminée dans "${State.selected.name_categorie}"`);
        closeModal('modal-merge');
        await loadCategories(); await loadGlobalStats(); loadMembers();
      } catch(e) { toast(e.message,'error'); }
    });

    // Supprimer
    document.getElementById('btn-delete-confirm')?.addEventListener('click', async () => {
      if (!State.selected) return;
      try {
        await apiDeleteCategory(State.selected.name_categorie);
        toast(`"${State.selected.name_categorie}" supprimée`);
        closeModal('modal-delete');
        State.selected = null;
        await loadCategories(); await loadGlobalStats();
      } catch(e) { toast(e.message,'error'); }
    });

    // Nouvelle règle
    document.getElementById('btn-rule-confirm')?.addEventListener('click', async () => {
      if (!State.selected) return;
      const type  = document.getElementById('rule-trigger-type')?.value;
      const value = document.getElementById('rule-trigger-value')?.value.trim();
      if (!type) { toast('Déclencheur requis','error'); return; }
      try {
        await apiAddRule(State.selected.name_categorie, type, value);
        toast('Règle ajoutée');
        closeModal('modal-rule');
        renderRules(await apiGetRules(State.selected.name_categorie));
      } catch(e) { toast(e.message,'error'); }
    });

    // Drawer : ajouter
    document.getElementById('btn-drawer-add-cat')?.addEventListener('click', addMemberToCategory);
    // Drawer : retirer
    document.getElementById('btn-drawer-remove')?.addEventListener('click', removeMemberFromDrawer);
  }

  function openEditModal(name) {
    const cat = State.categories.find(c=>c.name_categorie===name);
    if (cat) renderEditModal(cat);
    openModal('modal-edit');
  }
  function openMergeModal()  { if (!State.selected) return; renderMergeModal(State.selected.name_categorie, State.categories); openModal('modal-merge'); }
  function openMoveModal()   { if (!State.selected) return; renderMoveModal(State.selected.name_categorie, State.categories);  openModal('modal-move');  }
  function openDeleteModal() { if (!State.selected) return; renderDeleteModal(State.selected); openModal('modal-delete'); }
  function openDeleteModalByName(name) {
    const cat = State.categories.find(c=>c.name_categorie===name);
    if (!cat) return;
    // Sélectionner temporairement pour que btn-delete-confirm fonctionne
    State.selected = cat;
    renderDeleteModal(cat);
    openModal('modal-delete');
  }
  function openImportModal() { renderImportModal(State.categories); openModal('modal-import'); }
  function openAddIdsModal() {
    const sub = document.getElementById('add-ids-modal-sub');
    if (sub && State.selected) sub.textContent = `Catégorie : ${State.selected.name_categorie}`;
    openModal('modal-add-ids');
  }

  async function deleteRule(ruleId) {
    if (!confirm('Supprimer cette règle ?')) return;
    try {
      await apiDeleteRule(ruleId);
      toast('Règle supprimée');
      renderRules(await apiGetRules(State.selected.name_categorie));
    } catch(e) { toast(e.message,'error'); }
  }

  // Expose pour dropMember inline
  function loadMembersPublic() { return loadMembers(); }

  return {
    init, selectCat, switchTab, toggleSelect, loadMoreMembers,
    removeMember, openMemberDrawer, openEditModal, openMergeModal,
    openMoveModal, openDeleteModal, openDeleteModalByName, openImportModal, openAddIdsModal,
    deleteRule, exportCSV, addMemberToCategory, removeMemberFromDrawer,
    getSelected:        () => State.selected,
    getSelectedMembers: () => State.selectedMembers,
    loadMembersPublic
  };
})();

document.addEventListener('DOMContentLoaded', () => App.init());
</script>
</body>
</html>