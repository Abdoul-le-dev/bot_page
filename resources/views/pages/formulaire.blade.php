{{-- resources/views/forms/index.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TradingBot — Formulaires</title>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/form.css') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js" defer></script>

<style>
/* ── Corrections CORS local ── */
/* API_BASE pointe sur localhost en dev */

/* ── Modal réponses détail ── */
.resp-detail-grid {
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 0;
  height: 100%;
  min-height: 520px;
  max-height: 78vh;
  overflow: hidden;
}
.resp-sidebar {
  border-right: 1px solid rgba(255,255,255,.07);
  overflow-y: auto;
  padding: 0;
}
.resp-sidebar-head {
  padding: 14px 16px 10px;
  border-bottom: 1px solid rgba(255,255,255,.06);
  position: sticky;
  top: 0;
  background: #111113;
  z-index: 1;
}
.resp-sidebar-title {
  font-size: 11px;
  color: #52525b;
  text-transform: uppercase;
  letter-spacing: .07em;
  font-weight: 500;
  margin-bottom: 6px;
}
.resp-search {
  width: 100%;
  background: rgba(255,255,255,.05);
  border: 1px solid rgba(255,255,255,.08);
  border-radius: 6px;
  padding: 5px 9px;
  font-size: 11px;
  color: #e4e4e7;
  font-family: 'Geist', sans-serif;
  outline: none;
}
.resp-search:focus { border-color: rgba(56,189,248,.35); }
.resp-user-item {
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 9px 14px;
  cursor: pointer;
  border-bottom: 1px solid rgba(255,255,255,.04);
  transition: background .12s;
}
.resp-user-item:hover  { background: rgba(255,255,255,.04); }
.resp-user-item.active { background: rgba(56,189,248,.08); border-left: 2px solid #38bdf8; }
.resp-av {
  width: 30px; height: 30px;
  border-radius: 50%;
  background: rgba(56,189,248,.15);
  color: #38bdf8;
  display: flex; align-items: center; justify-content: center;
  font-size: 10px; font-weight: 600; flex-shrink: 0;
}
.resp-user-name  { font-size: 12px; color: #e4e4e7; font-weight: 500; }
.resp-user-meta  { font-size: 10px; color: #52525b; margin-top: 1px; }
.resp-user-score { margin-left: auto; font-size: 11px; font-weight: 600; flex-shrink: 0; }

/* ── Panneau droit — détail réponses ── */
.resp-content {
  display: flex;
  flex-direction: column;
  overflow: hidden;
}
.resp-content-head {
  padding: 14px 18px 12px;
  border-bottom: 1px solid rgba(255,255,255,.06);
  display: flex;
  align-items: center;
  gap: 10px;
  flex-shrink: 0;
}
.resp-content-body {
  flex: 1;
  overflow-y: auto;
  padding: 14px 18px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}
.resp-empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  height: 100%;
  color: #3f3f46;
  gap: 8px;
}

/* ── Cartes de réponse individuelle ── */
.ans-card {
  background: rgba(255,255,255,.03);
  border: 1px solid rgba(255,255,255,.06);
  border-radius: 8px;
  padding: 10px 13px;
}
.ans-card-head {
  display: flex;
  align-items: center;
  gap: 7px;
  margin-bottom: 6px;
}
.ans-step {
  font-size: 9px;
  font-weight: 600;
  color: #38bdf8;
  background: rgba(56,189,248,.12);
  padding: 2px 6px;
  border-radius: 3px;
  font-family: 'Geist Mono', monospace;
}
.ans-type-lbl {
  font-size: 10px;
  color: #71717a;
}
.ans-q {
  font-size: 11px;
  color: #71717a;
  margin-bottom: 5px;
  font-style: italic;
}
.ans-val {
  font-size: 13px;
  color: #e4e4e7;
  line-height: 1.5;
  word-break: break-word;
}
.ans-correct   { border-left: 2px solid #34d399; }
.ans-incorrect { border-left: 2px solid #f87171; }

/* ── Médias dans les réponses ── */
.ans-media-wrap {
  margin-top: 8px;
  border-radius: 7px;
  overflow: hidden;
  background: rgba(0,0,0,.2);
  border: 1px solid rgba(255,255,255,.07);
}
.ans-media-wrap img {
  width: 100%;
  max-height: 280px;
  object-fit: contain;
  display: block;
  background: #000;
}
.ans-media-wrap video {
  width: 100%;
  max-height: 260px;
  display: block;
  background: #000;
}
.ans-media-wrap audio {
  width: 100%;
  padding: 8px;
  display: block;
}
.ans-file-dl {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 12px;
  color: #38bdf8;
  font-size: 12px;
  text-decoration: none;
  cursor: pointer;
  transition: background .12s;
}
.ans-file-dl:hover { background: rgba(56,189,248,.07); }
.ans-file-dl svg { flex-shrink: 0; }
.ans-media-loading {
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  color: #52525b;
  font-size: 12px;
  gap: 8px;
}
.ans-media-error {
  padding: 12px;
  color: #f87171;
  font-size: 11px;
  text-align: center;
}

/* ── Score panel ── */
.score-panel {
  background: rgba(52,211,153,.05);
  border: 1px solid rgba(52,211,153,.15);
  border-radius: 8px;
  padding: 12px 14px;
  display: flex;
  align-items: center;
  gap: 14px;
  margin-bottom: 4px;
}
.score-big {
  font-size: 22px;
  font-weight: 300;
  color: #34d399;
}
.score-detail { font-size: 11px; color: #71717a; line-height: 1.6; }

/* ── Overlay modal large ── */
.modal-xl {
  width: min(900px, 95vw);
  max-height: 88vh;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}
.modal-xl .modal-body {
  padding: 0;
  flex: 1;
  overflow: hidden;
}

/* ── Bouton activer ── */
.btn-activate {
  background: rgba(52,211,153,.1);
  color: #34d399;
  border: 1px solid rgba(52,211,153,.2);
  border-radius: 5px;
  padding: 3px 7px;
  font-size: 10px;
  cursor: pointer;
  font-family: 'Geist', sans-serif;
  transition: background .12s;
}
.btn-activate:hover { background: rgba(52,211,153,.2); }

/* ── Champ trigger_value ── */
.trigger-date-wrap {
  display: none;
  margin-top: 6px;
}
.trigger-date-wrap.show { display: block; }

/* ── Skeleton loader ── */
.sk-user { height: 50px; margin: 4px 10px; border-radius: 6px; }
</style>
</head>
<body>

<div id="sb-overlay" onclick="closeSidebar()"></div>

{{-- ════════════════ SIDEBAR ═form═══════════════ --}}
<aside id="sidebar">
  <div class="sb-logo">
    
    <button class="sb-close" onclick="closeSidebar()" aria-label="Fermer">✕</button>
  </div>

  <nav class="sb-nav">
    <span class="nav-s">Membres</span>
     <button class="nav-item active"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>Formulaires</button>
  </nav>

  
</aside>

{{-- ════════════════ MAIN ════════════════ --}}
<div id="main">

  {{-- HEADER --}}
  <header id="hdr">
    <div class="hdr-l">
      <button class="burger" onclick="openSidebar()" aria-label="Menu">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <span class="hdr-title">Formulaires</span>
      <span class="hdr-sep">·</span>
      <div class="tabs-bar" id="tabs-desktop" role="tablist">
        <button class="tab active" data-view="list"      role="tab">Mes formulaires</button>
        <button class="tab"        data-view="builder"   role="tab">Builder</button>
        <button class="tab"        data-view="responses" role="tab">Réponses</button>
      </div>
    </div>
    <div class="hdr-r">
      <div id="save-ui" class="save-ui" style="display:none" aria-live="polite">
        <span class="save-dot" id="save-dot"></span>
        <span id="save-txt">Sauvegardé</span>
      </div>
      <div id="undo-ui" class="undo-ui" style="display:none">
        <button class="icon-btn" id="ubtn" onclick="undo()" disabled title="Annuler (⌘Z)">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3"/></svg>
        </button>
        <button class="icon-btn" id="rbtn" onclick="redo()" disabled title="Rétablir (⌘⇧Z)">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-.49-3"/></svg>
        </button>
      </div>
      <button class="btn sky" onclick="newForm();goView('builder')">
        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
        <span class="btn-txt">Nouveau formulaire</span>
      </button>
    </div>
  </header>

  {{-- TABS MOBILE --}}
  <div class="tabs-mobile" id="tabs-mobile" role="tablist">
    <button class="tab active" data-view="list"      role="tab">Formulaires</button>
    <button class="tab"        data-view="builder"   role="tab">Builder</button>
    <button class="tab"        data-view="responses" role="tab">Réponses</button>
  </div>

  {{-- ════ VUE LISTE ════ --}}
  <div id="view-list" class="view scroll-y pad" role="tabpanel">

    <div class="kpi-grid" style="margin-bottom:20px">
      <div class="card kpi">
        <p class="kpi-l">Formulaires actifs</p>
        <p class="kpi-v" id="kpi-total">—</p>
      </div>
      <div class="card kpi">
        <p class="kpi-l">Réponses totales</p>
        <p class="kpi-v" id="kpi-reponses">—</p>
      </div>
      <div class="card kpi">
        <p class="kpi-l">Complétion moy.</p>
        <p class="kpi-v" id="kpi-completion" style="color:#34d399">—</p>
      </div>
      <div class="card kpi">
        <p class="kpi-l">Score moy. quiz</p>
        <p class="kpi-v" id="kpi-score" style="color:#a78bfa">—</p>
      </div>
    </div>

    {{-- Templates --}}
    <p class="sec-ttl" style="margin-bottom:10px">Templates</p>
    <div class="tpl-grid" style="margin-bottom:20px">
      <div class="card tpl" onclick="loadTpl('inscription');goView('builder')" role="button" tabindex="0">
        <div class="tpl-ico" style="background:rgba(56,189,248,.12)"><svg width="12" height="12" fill="none" stroke="#38bdf8" viewBox="0 0 24 24" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
        <p class="tpl-n">Inscription</p><p class="tpl-c">/start</p>
      </div>
      <div class="card tpl" onclick="loadTpl('sondage');goView('builder')" role="button" tabindex="0">
        <div class="tpl-ico" style="background:rgba(251,191,36,.12)"><svg width="12" height="12" fill="none" stroke="#fbbf24" viewBox="0 0 24 24" stroke-width="1.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div>
        <p class="tpl-n">Sondage</p><p class="tpl-c">/sondage</p>
      </div>
      <div class="card tpl" onclick="loadTpl('quiz');goView('builder')" role="button" tabindex="0">
        <div class="tpl-ico" style="background:rgba(167,139,250,.12)"><svg width="12" height="12" fill="none" stroke="#a78bfa" viewBox="0 0 24 24" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>
        <p class="tpl-n">Quiz / QCM</p><p class="tpl-c">/quiz</p>
      </div>
      <div class="card tpl" onclick="loadTpl('journal');goView('builder')" role="button" tabindex="0">
        <div class="tpl-ico" style="background:rgba(45,212,191,.12)"><svg width="12" height="12" fill="none" stroke="#2dd4bf" viewBox="0 0 24 24" stroke-width="1.5"><path d="M3 3v18h18"/><path d="m7 16 4-4 4 4 4-6"/></svg></div>
        <p class="tpl-n">Journal trade</p><p class="tpl-c">/journal</p>
      </div>
      <div class="card tpl" onclick="loadTpl('temoignage');goView('builder')" role="button" tabindex="0">
        <div class="tpl-ico" style="background:rgba(244,114,182,.12)"><svg width="12" height="12" fill="none" stroke="#f472b6" viewBox="0 0 24 24" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg></div>
        <p class="tpl-n">Témoignage</p><p class="tpl-c">/temoignage</p>
      </div>
    </div>

    {{-- Liste formulaires --}}
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:8px">
      <p class="sec-ttl">Formulaires existants</p>
      <div style="display:flex;gap:6px">
        <button class="btn ghost sm" onclick="loadFormsList()">
          <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3"/></svg>
          Actualiser
        </button>
      </div>
    </div>

    <div class="card" style="overflow:hidden">
      <div class="tbl-head">
        <span style="flex:1">Nom & commande</span>
        <span class="c-type">Type</span>
        <span class="c-num">Champs</span>
        <span class="c-num">Rép.</span>
        <span class="c-comp">Complétion</span>
        <span class="c-stat">Statut</span>
        <span style="width:90px"></span>
      </div>
      <div id="forms-tbody">
        <div class="tbl-row"><div style="flex:1"><div class="skeleton" style="height:12px;width:55%;margin-bottom:5px"></div><div class="skeleton" style="height:10px;width:35%"></div></div></div>
        <div class="tbl-row"><div style="flex:1"><div class="skeleton" style="height:12px;width:45%;margin-bottom:5px"></div><div class="skeleton" style="height:10px;width:30%"></div></div></div>
        <div class="tbl-row"><div style="flex:1"><div class="skeleton" style="height:12px;width:60%;margin-bottom:5px"></div><div class="skeleton" style="height:10px;width:40%"></div></div></div>
      </div>
    </div>

    <div style="margin-top:16px;display:flex;gap:6px;flex-wrap:wrap">
      <span style="font-size:10px;color:#3f3f46">Raccourcis builder :</span>
      <span style="font-size:10px;color:#52525b"><kbd style="background:rgba(255,255,255,.06);padding:1px 5px;border-radius:3px;font-family:'Geist Mono',monospace">⌘S</kbd> Publier</span>
      <span style="font-size:10px;color:#52525b"><kbd style="background:rgba(255,255,255,.06);padding:1px 5px;border-radius:3px;font-family:'Geist Mono',monospace">⌘Z</kbd> Annuler</span>
      <span style="font-size:10px;color:#52525b"><kbd style="background:rgba(255,255,255,.06);padding:1px 5px;border-radius:3px;font-family:'Geist Mono',monospace">⌘↩</kbd> Palette</span>
      <span style="font-size:10px;color:#52525b"><kbd style="background:rgba(255,255,255,.06);padding:1px 5px;border-radius:3px;font-family:'Geist Mono',monospace">Esc</kbd> Fermer</span>
    </div>
  </div>

  {{-- ════ VUE BUILDER ════ --}}
  <div id="view-builder" class="view" style="display:none;overflow:hidden" role="tabpanel">
    <div class="builder-wrap">

      {{-- ── COL LEFT ── --}}
      <div class="col-l">

        <div class="scroll" style="flex-shrink:0;max-height:46%;border-bottom:1px solid rgba(255,255,255,.05);padding:12px 14px">
          <p class="lbl-sec" style="margin-bottom:10px">Configuration</p>

          <div class="g2 mb8">
            <div>
              <p class="lbl">Nom du formulaire *</p>
              <input class="inp" id="f-name" placeholder="Ex: Quiz Forex" oninput="scheduleSave();updateMeta()" autocomplete="off">
            </div>
            <div>
              <p class="lbl">Commande Telegram *</p>
              <div style="display:flex;align-items:center;gap:4px">
                <span style="color:#a78bfa;font-family:'Geist Mono',monospace;font-size:14px;flex-shrink:0">/</span>
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
              {{-- Champ date/heure pour trigger planifié — MANQUAIT --}}
              <div class="trigger-date-wrap" id="trigger-date-wrap">
                <p class="lbl" style="margin-top:6px">Date & heure d'envoi</p>
                <input class="inp" type="datetime-local" id="f-trigger-value" oninput="scheduleSave()" style="font-family:'Geist Mono',monospace;font-size:11px">
                <p style="font-size:10px;color:#52525b;margin-top:3px">Ou cron : "lundi 09:00" / "0 9 * * 1"</p>
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
              <p class="lbl" style="margin-bottom:0">Intro <span style="color:#34d399;font-size:9px;margin-left:4px">● premier message</span></p>
              <div>
                <span class="vchip" onclick="insertVar('f-intro','+prenom')" title="Insérer le prénom">+prenom</span>
                <span class="vchip" onclick="insertVar('f-intro','+date')" title="Insérer la date">+date</span>
              </div>
            </div>
            <textarea class="inp" id="f-intro" style="min-height:48px" placeholder="Bonjour +prenom ! 👋&#10;&#10;Prêt pour le quiz ?" oninput="scheduleSave();renderStep(curStep)"></textarea>
          </div>

          <div class="mb8">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px">
              <p class="lbl" style="margin-bottom:0">Outro <span style="color:#a78bfa;font-size:9px;margin-left:4px">● après la dernière question</span></p>
              <div>
                <span class="vchip" onclick="insertVar('f-outro','+prenom')">+prenom</span>
                <span class="vchip" onclick="insertVar('f-outro','+score')">+score</span>
                <span class="vchip" onclick="insertVar('f-outro','+total')">+total</span>
              </div>
            </div>
            <textarea class="inp" id="f-outro" style="min-height:40px" placeholder="✅ Merci +prenom !&#10;Score : +score / +total" oninput="scheduleSave();renderStep(curStep)"></textarea>
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
              <p style="font-size:11px;color:#a1a1aa">Correction immédiate</p>
              <button class="toggle on" onclick="this.classList.toggle('on')"></button>
            </div>
          </div>

          <div style="padding-top:10px;border-top:1px solid rgba(255,255,255,.05)">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
              <p class="lbl-sec">Actions après soumission</p>
              <button class="btn ghost sm" onclick="addAction()">+ Action</button>
            </div>
            <div id="end-actions">
              <div class="act-row">
                <svg width="10" height="10" fill="none" stroke="#38bdf8" viewBox="0 0 24 24" stroke-width="1.5" style="flex-shrink:0"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg>
                <select class="inp" style="font-size:11px;padding:3px 6px;flex:0 0 120px">
                  <option>Ajouter catégorie</option>
                  <option>Envoyer message</option>
                  <option>Notifier admin</option>
                  <option>Broadcast</option>
                </select>
                <input class="inp" type="text" value="Prospect Inscrit" placeholder="valeur..." style="font-size:11px;padding:3px 6px;flex:1">
                <button class="icon-btn del" onclick="this.closest('.act-row').remove()" title="Supprimer">
                  <svg width="8" height="8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
                </button>
              </div>
            </div>
          </div>

          <div style="padding-top:10px;border-top:1px solid rgba(255,255,255,.05);margin-top:10px">
            <p class="lbl-sec" style="margin-bottom:8px">Options</p>
            <div style="display:flex;flex-direction:column;gap:8px">
              <div class="tog-row">
                <div><p class="opt-p">Permettre de reprendre</p><p class="opt-sub">Continue là où il s'est arrêté</p></div>
                <button class="toggle on" id="opt-resume" onclick="this.classList.toggle('on')"></button>
              </div>
              <div class="tog-row">
                <div><p class="opt-p">Barre de progression</p><p class="opt-sub">Étape X/Y visible dans le bot</p></div>
                <button class="toggle on" id="opt-progress" onclick="this.classList.toggle('on')"></button>
              </div>
              <div class="tog-row">
                <div><p class="opt-p">Une réponse par utilisateur</p></div>
                <button class="toggle on" id="opt-one-per-user" onclick="this.classList.toggle('on')"></button>
              </div>
              <div class="tog-row">
                <div><p class="opt-p">Notifier l'admin</p></div>
                <button class="toggle" id="opt-notify" onclick="this.classList.toggle('on')"></button>
              </div>
            </div>
          </div>
        </div>

        {{-- CHAMPS --}}
        <div class="scroll" style="flex:1;padding:12px 14px">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px">
            <p class="lbl-sec">Questions / Champs <span id="field-count" style="color:#52525b;font-weight:400;text-transform:none;letter-spacing:0;font-size:10px">(0)</span></p>
            <div style="display:flex;gap:4px">
              <button class="btn ghost sm" onclick="collapseAll()" title="Replier tous les champs">Replier</button>
              <button class="btn ghost sm" onclick="togglePal()" id="pal-btn" title="⌘↩">
                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>Ajouter
              </button>
            </div>
          </div>

          <div id="palette" style="display:none;margin-bottom:10px">
            <div class="palette-box">
              <p class="pal-s">Réponse texte libre</p>
              <div class="pal-grid">
                <button class="pal-btn" onclick="addF('text');hidePal()"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M4 6h16M4 12h8"/></svg>Texte court</button>
                <button class="pal-btn" onclick="addF('long');hidePal()"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Texte long</button>
                <button class="pal-btn" onclick="addF('email');hidePal()"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>Email</button>
                <button class="pal-btn" onclick="addF('number');hidePal()"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><line x1="4" y1="9" x2="20" y2="9"/><line x1="4" y1="15" x2="20" y2="15"/></svg>Nombre</button>
              </div>
              <p class="pal-s">Boutons Telegram (InlineKeyboard)</p>
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
            <button class="btn ghost sm" style="width:100%;justify-content:center;margin-top:5px" onclick="hidePal()">Fermer la palette</button>
          </div>

          <div id="fc"></div>

          <div style="padding-top:12px;border-top:1px solid rgba(255,255,255,.05);margin-top:8px">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px">
              <div>
                <p class="lbl-sec" style="margin-bottom:1px">Logique conditionnelle</p>
                <p style="font-size:10px;color:#3f3f46">Saut, catégorie ou message selon les réponses</p>
              </div>
              <button class="btn ghost sm" onclick="addCond()">+ Règle</button>
            </div>
            <div id="conds"></div>
          </div>

          <div style="height:40px"></div>
        </div>
      </div>

      {{-- ── FAB preview mobile ── --}}
      <button class="fab-preview" id="fab-prev" onclick="togglePreview()">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="5" y="2" width="14" height="20" rx="2"/></svg>
        Preview
      </button>

      {{-- ── COL RIGHT (preview) ── --}}
      <div class="col-r" id="col-r">
        <button class="prev-close" onclick="togglePreview()">✕ Fermer la preview</button>

        <div class="prev-bar">
          <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
            <p style="font-size:13px;font-weight:500;color:#fff">Simulation Telegram</p>
            <span class="badge teal">Live</span>
            <span id="cmd-pill" class="cmd-pill">/quiz</span>
          </div>
          <div style="display:flex;align-items:center;gap:6px;flex-shrink:0">
            <div id="step-dots" style="display:flex;gap:4px;align-items:center"></div>
            <button class="btn ghost sm" onclick="prevStep()" title="Étape précédente">← Préc.</button>
            <button class="btn sky sm"   onclick="nextStep()" title="Étape suivante">Suivant →</button>
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
                  <p id="prev-name" style="font-size:11px;font-weight:500;color:#e2e8f0">TradingBot</p>
                  <p style="font-size:9px;color:#4a6478">bot · en ligne</p>
                </div>
                <div style="min-width:55px">
                  <div class="pbar"><div id="prev-prog" class="pbar-f" style="background:#38bdf8;width:0%"></div></div>
                  <p id="prev-prog-txt" style="font-size:8px;color:#4a6478;text-align:right;margin-top:2px"></p>
                </div>
              </div>
              <div class="tg-msgs" id="tg-feed"></div>
              <div class="tg-rk" id="tg-rk" style="display:none"></div>
              <div class="tg-bar">
                <div class="tg-inp" id="tg-hint">Tape ta réponse...</div>
                <div class="tg-send" onclick="nextStep()" title="Envoyer">
                  <svg width="13" height="13" fill="none" stroke="white" viewBox="0 0 24 24" stroke-width="2"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="prev-foot">
          <span id="step-info" style="font-size:11px;color:#52525b">Étape 1 / 1</span>
          <div style="display:flex;gap:6px">
            <button class="btn ghost sm" onclick="resetPrev()" title="Recommencer la simulation">
              <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3"/></svg>Reset
            </button>
            <button class="btn sky sm" onclick="publish()" title="Publier (⌘S)">
              <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13"/></svg>
              Publier
            </button>
          </div>
        </div>
      </div>

    </div>
  </div>

  {{-- ════ VUE RÉPONSES ════ --}}
  <div id="view-responses" class="view scroll-y pad" style="display:none" role="tabpanel">

    <div class="responses-filters">
      <select class="inp" id="resp-form-sel" style="width:260px;max-width:100%" onchange="loadResponsesForForm(this.value)">
        <option>Chargement...</option>
      </select>
      <select class="inp" id="resp-status-sel" style="width:130px" onchange="filterResponses(this.value)">
        <option value="">Toutes</option>
        <option value="completed">Complètes</option>
        <option value="abandoned">Incomplètes</option>
      </select>
      <button class="btn ghost sm" style="margin-left:auto" onclick="exportCSV()">
        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Exporter CSV
      </button>
    </div>

    {{-- KPIs dynamiques — plus de valeurs en dur --}}
    <div class="kpi-grid" style="margin-bottom:16px">
      <div class="card kpi"><p class="kpi-l">Réponses</p><p class="kpi-v" id="r-total">—</p></div>
      <div class="card kpi"><p class="kpi-l">Complétées</p><p class="kpi-v" id="r-completed" style="color:#34d399">—</p></div>
      <div class="card kpi"><p class="kpi-l">Score moyen</p><p class="kpi-v" id="r-score" style="color:#a78bfa">—</p></div>
      <div class="card kpi"><p class="kpi-l">Taux complétion</p><p class="kpi-v" id="r-time" style="color:#38bdf8">—</p></div>
    </div>

    <div class="card" style="overflow:hidden">
      <div class="tbl-head">
        <input type="checkbox" id="check-all" style="accent-color:#38bdf8;flex-shrink:0" onchange="toggleAllChecks(this.checked)">
        <span style="flex:1">Membre</span>
        <span class="c-num">Rép.</span>
        <span class="c-sc">Score</span>
        <span style="width:90px;font-size:11px;color:#52525b">Soumis le</span>
        <span style="width:30px"></span>
      </div>
      <div id="resp-tbody">
        <div class="tbl-row"><div style="flex:1"><div class="skeleton" style="height:12px;width:40%"></div></div></div>
        <div class="tbl-row"><div style="flex:1"><div class="skeleton" style="height:12px;width:50%"></div></div></div>
      </div>
    </div>
  </div>

</div>{{-- /main --}}

{{-- ════ MODAL DÉTAIL RÉPONSE (nouveau design large) ════ --}}
<div class="overlay" id="m-detail">
  <div class="modal modal-xl">
    <div class="modal-head">
      <div style="display:flex;align-items:center;gap:10px;flex:1;min-width:0">
        <div class="av-sm" id="det-av" style="background:rgba(56,189,248,.15);color:#38bdf8;font-size:11px">—</div>
        <div style="min-width:0">
          <p style="font-size:13px;font-weight:500;color:#fff" id="det-name">Chargement…</p>
          <p style="font-size:11px;color:#52525b;margin-top:1px" id="det-meta">—</p>
        </div>
        <div id="det-score-pill" style="margin-left:auto;flex-shrink:0"></div>
      </div>
      <button class="icon-btn" onclick="closeModal('m-detail')" aria-label="Fermer" style="flex-shrink:0">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>

    <div class="modal-body">
      <div class="resp-detail-grid">

        {{-- Sidebar : liste des utilisateurs --}}
        <div class="resp-sidebar">
          <div class="resp-sidebar-head">
            <p class="resp-sidebar-title">Participants</p>
            <input class="resp-search" id="resp-user-search" type="text" placeholder="Rechercher…" oninput="filterUserList(this.value)">
          </div>
          <div id="resp-user-list">
            <div class="skeleton sk-user"></div>
            <div class="skeleton sk-user"></div>
            <div class="skeleton sk-user"></div>
          </div>
        </div>

        {{-- Contenu : réponses de l'utilisateur sélectionné --}}
        <div class="resp-content">
          <div class="resp-content-head" id="det-content-head" style="display:none">
            <div>
              <p style="font-size:12px;color:#e4e4e7;font-weight:500" id="det-content-name">—</p>
              <p style="font-size:10px;color:#52525b;margin-top:2px" id="det-content-date">—</p>
            </div>
            <div style="margin-left:auto;display:flex;gap:6px">
              <a id="det-export-link" href="#" style="display:none" class="btn ghost sm">
                <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/></svg>
                Export
              </a>
            </div>
          </div>
          <div class="resp-content-body" id="resp-detail-body">
            <div class="resp-empty-state">
              <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
              <p style="font-size:12px">Sélectionne un participant</p>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="modal-foot">
      <span id="det-total-count" style="font-size:11px;color:#52525b"></span>
      <button class="btn ghost" onclick="closeModal('m-detail')">Fermer</button>
    </div>
  </div>
</div>

<script src="{{ asset('js/form.js') }}" type="module"></script>

<script>
/* ═══════════════════════════════════════════════════════
   PATCH blade.php — fonctions additionnelles injectées ici
   (seront à migrer dans forms.js au besoin)
═══════════════════════════════════════════════════════ */

/* ── Correction : réponses API locale ── */
/* form_api.js doit utiliser l'URL locale, pas l'IP publique.
   On surcharge window.API_BASE si le module est déjà chargé. */
window.__API_BASE_OVERRIDE = 'http://54.226.165.244:8000/forms';

/* ── Trigger change : afficher le champ date si planifié ── */
window.onTriggerChange = function() {
  const sel  = document.getElementById('f-trigger');
  const wrap = document.getElementById('trigger-date-wrap');
  if (!wrap) return;
  const show = sel && sel.value === 'Planifié (date/heure)';
  wrap.classList.toggle('show', show);
  if (show) _loadCategoriesForTrigger();
};

async function _loadCategoriesForTrigger() {
  const sel = document.getElementById('f-target-cat');
  if (!sel || sel.dataset.loaded) return;
  try {
    const r = await fetch('http://54.226.165.244:8000/categorie');
    const data = await r.json();
    sel.innerHTML = '<option value="">Tous les utilisateurs</option>' +
      (Array.isArray(data) ? data : []).map(c =>
        `<option value="${_esc(c.name_categorie || c)}">${_esc(c.name_categorie || c)}</option>`
      ).join('');
    sel.dataset.loaded = '1';
  } catch(e) { /* silencieux */ }
}

/* ── KPIs vue Réponses : mis à jour dynamiquement ── */
window._updateRespKPIs = function(data) {
  const total     = data.length;
  const completed = data.filter(r => r.status !== 'abandoned').length;
  const avgScore  = total
    ? Math.round(data.reduce((a, r) => a + (r.pct || 0), 0) / total)
    : 0;
  const compPct   = total ? Math.round(completed / total * 100) : 0;

  const el = id => document.getElementById(id);
  if (el('r-total'))     el('r-total').textContent     = total.toLocaleString('fr');
  if (el('r-completed')) el('r-completed').textContent = completed.toLocaleString('fr');
  if (el('r-score'))     el('r-score').textContent     = avgScore + '%';
  if (el('r-time'))      el('r-time').textContent      = compPct + '%';
};

/* ── Filtre statut réponses ── */
window._allResponses = [];
window.filterResponses = function(status) {
  const data = status
    ? window._allResponses.filter(r => status === 'completed'
        ? r.status !== 'abandoned'
        : r.status === 'abandoned')
    : window._allResponses;
  if (typeof window.renderResponsesTable === 'function') {
    window.renderResponsesTable(data);
  }
};

/* ── Toggle all checkboxes ── */
window.toggleAllChecks = function(checked) {
  document.querySelectorAll('#resp-tbody input[type=checkbox]').forEach(cb => cb.checked = checked);
};

/* ── Activer formulaire ── */
window.activateForm = async function(id, e) {
  e.stopPropagation();
  try {
    const r = await fetch(`http://54.226.165.244:8000/forms/${id}/activate`, { method: 'POST' });
    if (!r.ok) throw new Error();
    if (typeof window.toast === 'function') window.toast('Formulaire réactivé', 'success');
    if (typeof window.loadFormsList === 'function') window.loadFormsList();
  } catch(e) {
    if (typeof window.toast === 'function') window.toast('Erreur lors de la réactivation', 'error');
  }
};

/* ── Utilitaires ── */
function _esc(s) {
  return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

/* ══════════════════════════════════════════════════════
   MODAL DÉTAIL RÉPONSES — Logique complète
══════════════════════════════════════════════════════ */

let _detailFormId   = null;
let _detailUsers    = [];
let _currentUserId  = null;

/* Ouvre la modal et charge tous les participants */
window.openResponseDetail = async function(formId) {
  _detailFormId  = formId;
  _detailUsers   = [];
  _currentUserId = null;

  /* Reset UI */
  document.getElementById('resp-user-list').innerHTML =
    ['','',''].map(() => '<div class="skeleton sk-user"></div>').join('');
  document.getElementById('resp-detail-body').innerHTML =
    `<div class="resp-empty-state">
       <svg width="28" height="28" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
       <p style="font-size:12px">Sélectionne un participant</p>
     </div>`;
  document.getElementById('det-content-head').style.display = 'none';
  document.getElementById('det-name').textContent  = 'Chargement…';
  document.getElementById('det-meta').textContent  = '—';
  document.getElementById('det-score-pill').innerHTML = '';
  document.getElementById('det-total-count').textContent = '';
  document.getElementById('resp-user-search').value = '';

  openModal('m-detail');

  try {
    const r    = await fetch(`http://54.226.165.244:8000/forms/${formId}/responses?limit=500`);
    const data = await r.json();
    _detailUsers = data;
    _renderUserList(data);
    document.getElementById('det-total-count').textContent =
      `${data.length} participant${data.length > 1 ? 's' : ''}`;
    if (data.length > 0) {
      _selectUser(data[0]);
    }
  } catch(e) {
    document.getElementById('resp-user-list').innerHTML =
      '<p style="font-size:12px;color:#52525b;padding:14px 16px">Erreur de chargement</p>';
  }
};

/* Rend la liste des utilisateurs dans la sidebar */
function _renderUserList(users) {
  const el = document.getElementById('resp-user-list');
  if (!users.length) {
    el.innerHTML = '<p style="font-size:12px;color:#52525b;padding:14px 16px">Aucune réponse</p>';
    return;
  }
  el.innerHTML = users.map(u => {
    const initials = (u.prenom || '?').substring(0, 2).toUpperCase();
    const pct      = u.pct || 0;
    const color    = pct >= 70 ? '#34d399' : pct >= 50 ? '#fbbf24' : '#f87171';
    return `<div class="resp-user-item" id="uitem-${u.telegram_id}"
              onclick="window._selectUser(${JSON.stringify(u).replace(/"/g,'&quot;')})">
      <div class="resp-av">${_esc(initials)}</div>
      <div style="min-width:0;flex:1">
        <p class="resp-user-name">${_esc(u.prenom || 'User ' + u.telegram_id)}</p>
        <p class="resp-user-meta">${u.submitted_at ? new Date(u.submitted_at).toLocaleDateString('fr') : '—'}</p>
      </div>
      ${u.score_max ? `<span class="resp-user-score" style="color:${color}">${u.score_final}/${u.score_max}</span>` : ''}
    </div>`;
  }).join('');
}

/* Filtre la liste utilisateurs */
window.filterUserList = function(q) {
  const filtered = q
    ? _detailUsers.filter(u =>
        (u.prenom || '').toLowerCase().includes(q.toLowerCase()) ||
        String(u.telegram_id).includes(q))
    : _detailUsers;
  _renderUserList(filtered);
};

/* Sélectionne un utilisateur et charge ses réponses */
window._selectUser = async function(u) {
  _currentUserId = u.telegram_id;

  /* Highlight sidebar */
  document.querySelectorAll('.resp-user-item').forEach(el => el.classList.remove('active'));
  const item = document.getElementById('uitem-' + u.telegram_id);
  if (item) item.classList.add('active');

  /* Header modal */
  const initials = (u.prenom || '?').substring(0, 2).toUpperCase();
  document.getElementById('det-av').textContent   = initials;
  document.getElementById('det-name').textContent = u.prenom || 'User ' + u.telegram_id;
  document.getElementById('det-meta').textContent = `ID Telegram : ${u.telegram_id}`;

  /* Score pill */
  const pill = document.getElementById('det-score-pill');
  if (u.score_max) {
    const pct   = u.pct || 0;
    const color = pct >= 70 ? '#34d399' : pct >= 50 ? '#fbbf24' : '#f87171';
    pill.innerHTML = `<span style="font-size:13px;font-weight:600;color:${color}">${u.score_final}/${u.score_max} — ${pct}%</span>`;
  } else { pill.innerHTML = ''; }

  /* Panneau contenu */
  document.getElementById('det-content-head').style.display = 'flex';
  document.getElementById('det-content-name').textContent   = u.prenom || 'User ' + u.telegram_id;
  document.getElementById('det-content-date').textContent   = u.submitted_at
    ? 'Soumis le ' + new Date(u.submitted_at).toLocaleString('fr')
    : 'Date inconnue';

  /* Loading */
  const body = document.getElementById('resp-detail-body');
  body.innerHTML = `<div class="ans-media-loading"><div class="spinner"></div> Chargement des réponses…</div>`;

  try {
    const r    = await fetch(`http://54.226.165.244:8000/forms/${_detailFormId}/responses/${u.telegram_id}`);
    const data = await r.json();
    _renderUserAnswers(body, data, u);
  } catch(e) {
    body.innerHTML = '<div class="ans-media-error">Impossible de charger les réponses.</div>';
  }
};

/* Rend les réponses détaillées d'un utilisateur */
function _renderUserAnswers(container, answers, user) {
  if (!answers.length) {
    container.innerHTML = '<div class="resp-empty-state"><p style="font-size:12px">Aucune réponse enregistrée.</p></div>';
    return;
  }

  /* Score summary si quiz */
  let html = '';
  const hasScore = user.score_max > 0;
  if (hasScore) {
    const pct   = user.pct || 0;
    const color = pct >= 70 ? '#34d399' : pct >= 50 ? '#fbbf24' : '#f87171';
    html += `<div class="score-panel">
      <div>
        <p class="score-big">${user.score_final} <span style="font-size:14px;color:#52525b">/ ${user.score_max}</span></p>
      </div>
      <div class="score-detail">
        Score final<br>
        <span style="color:${color};font-weight:600">${pct}%</span>
      </div>
    </div>`;
  }

  /* Chaque réponse */
  answers.forEach((ans, idx) => {
    const correct    = ans.is_correct;
    const cardClass  = correct === 1 ? 'ans-correct' : correct === 0 ? 'ans-incorrect' : '';
    const typeLabels = {
      text:'Texte', long:'Texte long', email:'Email', number:'Nombre',
      qcm:'QCM', multi:'Multi-choix', oui_non:'Oui/Non',
      note5:'Note', nps:'NPS', photo:'Photo', video:'Vidéo',
      audio:'Vocal', document:'Document', contact:'Contact', info:'Info'
    };
    const typeLabel  = typeLabels[ans.field_type] || ans.field_type;
    const isMedia    = ['photo','video','audio','document'].includes(ans.field_type);
    const value      = ans.value || '';

    let valueHtml = '';
    if (isMedia && value && value !== '__skip__' && value !== '__media__') {
      valueHtml = _renderMedia(ans.field_type, value);
    } else if (value === '__skip__') {
      valueHtml = '<span style="font-size:11px;color:#52525b;font-style:italic">Passé (optionnel)</span>';
    } else {
      valueHtml = `<p class="ans-val">${_esc(value) || '<span style="color:#52525b;font-style:italic">—</span>'}</p>`;
    }

    let scoreBadge = '';
    if (correct !== null && correct !== undefined) {
      const ok = correct === 1 || correct === true;
      scoreBadge = `<span class="badge ${ok ? 'green' : 'red'}" style="margin-left:auto;font-size:9px">
        ${ok ? '✓ Correct' : '✗ Incorrect'} ${ans.points ? '(+' + ans.points + ' pts)' : ''}
      </span>`;
    }

    html += `<div class="ans-card ${cardClass}">
      <div class="ans-card-head">
        <span class="ans-step">Q${idx + 1}</span>
        <span class="ans-type-lbl">${_esc(typeLabel)}</span>
        ${scoreBadge}
      </div>
      ${ans.field_label ? `<p class="ans-q">${_esc(ans.field_label)}</p>` : ''}
      ${valueHtml}
      ${ans.answered_at ? `<p style="font-size:9px;color:#3f3f46;margin-top:5px;text-align:right">${new Date(ans.answered_at).toLocaleTimeString('fr')}</p>` : ''}
    </div>`;
  });

  container.innerHTML = html;
}

/* Rendu des médias (photo, vidéo, audio, document) */
function _renderMedia(type, fileId) {
  /* On tente d'abord de construire une URL de téléchargement via l'API Telegram */
  const dlUrl = `http://54.226.165.244:8000/forms/media/${_esc(fileId)}`;

  if (type === 'photo') {
    return `<div class="ans-media-wrap">
      <img src="${dlUrl}" alt="Photo" loading="lazy"
           onerror="this.parentElement.innerHTML='<div class=\\'ans-media-error\\'>Image non disponible — file_id : <code style=\\'font-family:monospace;font-size:10px\\'>${_esc(fileId)}</code></div>'">
    </div>`;
  }
  if (type === 'video') {
    return `<div class="ans-media-wrap">
      <video controls preload="none">
        <source src="${dlUrl}">
        Vidéo non disponible
      </video>
    </div>`;
  }
  if (type === 'audio') {
    return `<div class="ans-media-wrap">
      <audio controls preload="none">
        <source src="${dlUrl}">
        Audio non disponible
      </audio>
    </div>`;
  }
  if (type === 'document') {
    return `<div class="ans-media-wrap">
      <a class="ans-file-dl" href="${dlUrl}" target="_blank" rel="noopener">
        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        Télécharger le document
        <span style="font-size:10px;color:#52525b;margin-left:6px;font-family:monospace">${_esc(fileId.substring(0,20))}…</span>
      </a>
    </div>`;
  }
  return `<p class="ans-val">${_esc(fileId)}</p>`;
}
</script>
</body>
</html>