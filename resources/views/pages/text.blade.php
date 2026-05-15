@extends('components.app')

@section('title', 'TradingBot — IA Config')
@section('page-title', 'Agent IA')

@section('head')
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400,500" rel="stylesheet">
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Geist','sans-serif'],mono:['Geist Mono','monospace']}}}}</script>
<style>
::-webkit-scrollbar{width:3px;height:3px;}
::-webkit-scrollbar-track{background:transparent;}
::-webkit-scrollbar-thumb{background:rgba(255,255,255,.08);border-radius:99px;}

.nav-item{display:flex;align-items:center;gap:9px;padding:6px 10px;border-radius:7px;font-size:13px;color:#52525b;cursor:pointer;transition:all .15s;border:none;background:none;width:100%;text-align:left;}
.nav-item:hover{color:#d4d4d8;background:rgba(255,255,255,.04);}
.nav-item.active{color:#fafafa;background:rgba(255,255,255,.07);}
.nav-item svg{width:14px;height:14px;flex-shrink:0;opacity:.7;}
.nav-item.active svg{opacity:1;}

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
.toast{background:#111113;border:1px solid rgba(255,255,255,.12);color:#e4e4e7;padding:9px 18px;border-radius:9px;font-size:12px;white-space:nowrap;animation:toastin .2s ease;pointer-events:none;}
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
@endsection

@section('content')
<div id="toast-container"></div>

<div style="display:flex;flex-direction:column;height:100%;overflow:hidden;">

  {{-- Header --}}
  <header style="height:52px;border-bottom:1px solid rgba(255,255,255,.05);backdrop-filter:blur(14px);background:rgba(9,9,11,.88);flex-shrink:0;display:flex;align-items:center;justify-content:space-between;padding:0 20px;">
    <div style="display:flex;align-items:center;gap:12px;">
      <h1 style="font-size:14px;font-weight:500;color:#fff;" id="page-title">Prompts IA</h1>
      <span style="color:#27272a;">·</span>
      <p style="font-size:12px;color:#52525b;" id="page-sub">Gérer les prompts injectés dans l'intelligence artificielle</p>
    </div>
    <div style="display:flex;align-items:center;gap:8px;">
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

  {{-- Contenu principal --}}
  <main style="flex:1;overflow-y:auto;padding:20px;">

    {{-- VUE PROMPTS --}}
    <div id="v-prompts" style="display:flex;flex-direction:column;gap:16px;">
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
        <div class="stat-m"><p style="font-size:10px;color:#52525b;margin-bottom:6px;">Total prompts</p><p style="font-size:20px;font-weight:300;color:#fff;" id="stat-p-total">0</p></div>
        <div class="stat-m"><p style="font-size:10px;color:#52525b;margin-bottom:6px;">Actifs</p><p style="font-size:20px;font-weight:300;color:#34d399;" id="stat-p-active">0</p></div>
        <div class="stat-m"><p style="font-size:10px;color:#52525b;margin-bottom:6px;">Formats</p><p style="font-size:13px;font-weight:500;color:#fff;" id="stat-p-formats">—</p></div>
        <div class="stat-m"><p style="font-size:10px;color:#52525b;margin-bottom:6px;">Dernier ajout</p><p style="font-size:13px;font-weight:500;color:#fff;" id="stat-p-last">—</p></div>
      </div>
      <div class="card" style="overflow:hidden;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.05);">
          <div style="display:flex;align-items:center;gap:8px;">
            <p style="font-size:13px;font-weight:500;color:#fff;">Bibliothèque de prompts</p>
            <span class="badge bdg-z" style="font-size:10px;" id="prompts-count-badge">0</span>
          </div>
          <select class="input" style="width:130px;padding:5px 8px;font-size:11px;" onchange="filterPrompts(this.value)">
            <option value="">Tous les formats</option>
            <option value="text">Texte</option>
            <option value="json">JSON</option>
            <option value="list">Liste</option>
            <option value="markdown">Markdown</option>
          </select>
        </div>
        <div id="prompts-list" style="padding:16px;display:flex;flex-direction:column;gap:12px;"></div>
        <div id="prompts-empty" style="display:none;padding:40px;text-align:center;"><p style="font-size:13px;color:#3f3f46;">Aucun prompt — créez le premier</p></div>
      </div>
    </div>

    {{-- VUE FONCTIONS --}}
    <div id="v-functions" style="display:none;flex-direction:column;gap:16px;">
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;">
        <div class="stat-m"><p style="font-size:10px;color:#52525b;margin-bottom:6px;">Total fonctions</p><p style="font-size:20px;font-weight:300;color:#fff;" id="stat-f-total">0</p></div>
        <div class="stat-m"><p style="font-size:10px;color:#52525b;margin-bottom:6px;">Actives</p><p style="font-size:20px;font-weight:300;color:#a78bfa;" id="stat-f-active">0</p></div>
        <div class="stat-m"><p style="font-size:10px;color:#52525b;margin-bottom:6px;">Lignes de code</p><p style="font-size:20px;font-weight:300;color:#38bdf8;" id="stat-f-lines">0</p></div>
        <div class="stat-m"><p style="font-size:10px;color:#52525b;margin-bottom:6px;">Dernier ajout</p><p style="font-size:13px;font-weight:500;color:#fff;" id="stat-f-last">—</p></div>
      </div>
      <div class="card" style="overflow:hidden;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.05);">
          <div style="display:flex;align-items:center;gap:8px;">
            <p style="font-size:13px;font-weight:500;color:#fff;">Fonctions injectables</p>
            <span class="badge bdg-z" style="font-size:10px;" id="functions-count-badge">0</span>
          </div>
        </div>
        <div id="functions-list" style="padding:16px;display:flex;flex-direction:column;gap:12px;"></div>
        <div id="functions-empty" style="display:none;padding:40px;text-align:center;"><p style="font-size:13px;color:#3f3f46;">Aucune fonction — créez la première</p></div>
      </div>
    </div>

    {{-- VUE TABLES DB --}}
    <div id="v-tables" style="display:none;flex-direction:column;gap:16px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div class="card" style="overflow:hidden;">
          <div style="padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.05);">
            <p style="font-size:13px;font-weight:500;color:#fff;">Importer un fichier Tables</p>
            <p style="font-size:12px;color:#52525b;margin-top:4px;">Fichier JSON décrivant la structure des tables DB</p>
          </div>
          <div style="padding:16px;display:flex;flex-direction:column;gap:12px;">
            <div class="drop-zone" id="drop-tables" onclick="document.getElementById('file-tables').click()">
              <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="color:#3f3f46;margin:0 auto 8px;display:block;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              <p style="font-size:12px;color:#52525b;">Glisser un fichier <span class="mono" style="color:#38bdf8;">.json</span> ou cliquer</p>
              <input type="file" id="file-tables" accept=".json" style="display:none;" onchange="loadTablesFile(this)">
            </div>
            <div id="tables-file-info" style="display:none;" class="file-row">
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="color:#34d399;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>
              <span style="font-size:12px;flex:1;color:#a1a1aa;" id="tables-file-name"></span>
              <button class="btn-i" onclick="clearTablesFile()"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
            </div>
            <button class="btn-g" style="width:100%;justify-content:center;" onclick="downloadTablesTemplate()">
              <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Télécharger le template
            </button>
          </div>
        </div>
        <div class="card" style="overflow:hidden;">
          <div style="padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.05);">
            <p style="font-size:13px;font-weight:500;color:#fff;">Aperçu du fichier</p>
          </div>
          <div style="padding:16px;">
            <div id="tables-preview" class="code-block" style="max-height:280px;color:#52525b;">Aucun fichier importé</div>
          </div>
        </div>
      </div>
      <div class="card" style="overflow:hidden;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.05);">
          <p style="font-size:13px;font-weight:500;color:#fff;">Tables importées</p>
          <span class="badge bdg-z" style="font-size:10px;" id="tables-count">0 tables</span>
        </div>
        <div id="tables-rendered" style="padding:16px;display:grid;grid-template-columns:1fr 1fr;gap:12px;"></div>
        <div id="tables-empty" style="padding:32px;text-align:center;"><p style="font-size:12px;color:#3f3f46;">Importez un fichier JSON pour voir les tables</p></div>
      </div>
    </div>

    {{-- VUE ENDPOINTS --}}
    <div id="v-endpoints" style="display:none;flex-direction:column;gap:16px;">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
        <div class="card" style="overflow:hidden;">
          <div style="padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.05);">
            <p style="font-size:13px;font-weight:500;color:#fff;">Importer un fichier Endpoints</p>
            <p style="font-size:12px;color:#52525b;margin-top:4px;">Fichier JSON décrivant les routes API disponibles</p>
          </div>
          <div style="padding:16px;display:flex;flex-direction:column;gap:12px;">
            <div class="drop-zone" id="drop-endpoints" onclick="document.getElementById('file-endpoints').click()">
              <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="color:#3f3f46;margin:0 auto 8px;display:block;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
              <p style="font-size:12px;color:#52525b;">Glisser un fichier <span class="mono" style="color:#38bdf8;">.json</span> ou cliquer</p>
              <input type="file" id="file-endpoints" accept=".json" style="display:none;" onchange="loadEndpointsFile(this)">
            </div>
            <div id="endpoints-file-info" style="display:none;" class="file-row">
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="color:#34d399;flex-shrink:0;"><polyline points="20 6 9 17 4 12"/></svg>
              <span style="font-size:12px;flex:1;color:#a1a1aa;" id="endpoints-file-name"></span>
              <button class="btn-i" onclick="clearEndpointsFile()"><svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
            </div>
            <button class="btn-g" style="width:100%;justify-content:center;" onclick="downloadEndpointsTemplate()">
              <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
              Télécharger le template
            </button>
          </div>
        </div>
        <div class="card" style="overflow:hidden;">
          <div style="padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.05);">
            <p style="font-size:13px;font-weight:500;color:#fff;">Aperçu du fichier</p>
          </div>
          <div style="padding:16px;">
            <div id="endpoints-preview" class="code-block" style="max-height:280px;color:#52525b;">Aucun fichier importé</div>
          </div>
        </div>
      </div>
      <div class="card" style="overflow:hidden;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.05);">
          <p style="font-size:13px;font-weight:500;color:#fff;">Endpoints importés</p>
          <span class="badge bdg-z" style="font-size:10px;" id="endpoints-count">0 routes</span>
        </div>
        <div id="endpoints-rendered" style="padding:16px;display:flex;flex-direction:column;gap:8px;"></div>
        <div id="endpoints-empty" style="padding:32px;text-align:center;"><p style="font-size:12px;color:#3f3f46;">Importez un fichier JSON pour voir les endpoints</p></div>
      </div>
    </div>

  </main>
</div>

{{-- ════ MODAL PROMPT ════ --}}
<div class="overlay" id="m-prompt" onclick="closeModal('m-prompt',event)">
  <div class="modal" style="width:600px;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid rgba(255,255,255,.07);">
      <p style="font-size:13px;font-weight:500;color:#fff;" id="m-prompt-title">Nouveau prompt</p>
      <button class="btn-i" onclick="closeModal('m-prompt')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div style="overflow-y:auto;display:flex;flex-direction:column;gap:16px;padding:20px;">
      <input type="hidden" id="p-id">
      <div><p class="slbl">Nom du prompt</p><input class="input" id="p-name" placeholder="ex: Analyse comportement trader"></div>
      <div><p class="slbl">Description courte</p><input class="input" id="p-desc" placeholder="À quoi sert ce prompt ?"></div>
      <div>
        <p class="slbl">Format de retour attendu</p>
        <select class="input" id="p-format">
          <option value="text">Texte libre</option><option value="json">JSON</option><option value="list">Liste</option><option value="markdown">Markdown</option>
        </select>
      </div>
      <div><p class="slbl">Contenu du prompt</p><textarea class="input" id="p-content" rows="10" placeholder="Tu es un assistant spécialisé en trading..."></textarea></div>
      <div style="display:flex;align-items:center;gap:8px;">
        <button class="toggle on" id="p-active-toggle" onclick="this.classList.toggle('on')"></button>
        <span style="font-size:12px;color:#71717a;">Actif</span>
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-danger" id="p-del-btn" style="display:none;" onclick="deletePrompt()">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>Supprimer
      </button>
      <div style="display:flex;gap:8px;margin-left:auto;">
        <button class="btn-g" onclick="closeModal('m-prompt')">Annuler</button>
        <button class="btn-p" onclick="savePrompt()">Enregistrer</button>
      </div>
    </div>
  </div>
</div>

{{-- ════ MODAL FONCTION ════ --}}
<div class="overlay" id="m-function" onclick="closeModal('m-function',event)">
  <div class="modal" style="width:660px;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid rgba(255,255,255,.07);">
      <p style="font-size:13px;font-weight:500;color:#fff;" id="m-function-title">Nouvelle fonction</p>
      <button class="btn-i" onclick="closeModal('m-function')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div style="overflow-y:auto;display:flex;flex-direction:column;gap:16px;padding:20px;">
      <input type="hidden" id="f-id">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div><p class="slbl">Nom de la fonction</p><input class="input mono" id="f-name" placeholder="ex: compute_win_rate"></div>
        <div><p class="slbl">Description</p><input class="input" id="f-desc" placeholder="Ce que fait la fonction"></div>
      </div>
      <div><p class="slbl">Code Python</p><textarea class="input" id="f-code" rows="14" placeholder="async def compute_win_rate(user_id: int) -> float:&#10;    pass"></textarea></div>
      <div style="display:flex;align-items:center;gap:8px;">
        <button class="toggle on" id="f-active-toggle" onclick="this.classList.toggle('on')"></button>
        <span style="font-size:12px;color:#71717a;">Active</span>
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-danger" id="f-del-btn" style="display:none;" onclick="deleteFunction()">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/></svg>Supprimer
      </button>
      <div style="display:flex;gap:8px;margin-left:auto;">
        <button class="btn-g" onclick="closeModal('m-function')">Annuler</button>
        <button class="btn-p" onclick="saveFunction()">Enregistrer</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
const API = 'http://54.226.165.244:8000/ia-config'

let currentView   = 'prompts'
let tablesData    = null
let endpointsData = null

// ── API ────────────────────────────────────────────────────
async function api(url, opts={}) {
  try {
    const r = await fetch(url, {headers:{'Content-Type':'application/json'}, ...opts})
    if (!r.ok) { const e = await r.json().catch(()=>({detail:'Erreur'})); throw new Error(e.detail||`HTTP ${r.status}`) }
    return r.json()
  } catch(e) { toast(e.message,'error'); throw e }
}

// ── Nav locale (sous-vues de cette page) ──────────────────
const VIEWS = {
  prompts:   {title:'Prompts IA',    sub:"Gérer les prompts injectés dans l'intelligence artificielle", cta:'Nouveau prompt',   export:'Exporter .py'},
  functions: {title:'Fonctions',     sub:'Fonctions Python injectables dans le bot',                    cta:'Nouvelle fonction', export:'Exporter .py'},
  tables:    {title:'Tables DB',     sub:'Importer le fichier de définition des tables',                cta:null,               export:'Exporter JSON'},
  endpoints: {title:'Endpoints API', sub:'Importer le fichier de définition des routes',               cta:null,               export:'Exporter JSON'},
}

function sv(v, btn) {
  currentView = v
  // Retire active sur TOUS les nav-item visibles (sidebar unifiée incluse)
  document.querySelectorAll('.nav-item').forEach(b => b.classList.remove('active'))
  if (btn) btn.classList.add('active')
  // Cache toutes les vues
  ;['prompts','functions','tables','endpoints'].forEach(id => {
    document.getElementById('v-' + id).style.display = 'none'
  })
  // Affiche la vue demandée
  document.getElementById('v-' + v).style.display = 'flex'
  // Met à jour le header
  const cfg = VIEWS[v]
  document.getElementById('page-title').textContent = cfg.title
  document.getElementById('page-sub').textContent   = cfg.sub
  document.getElementById('btn-export').textContent = cfg.export || 'Exporter'
  const cta = document.getElementById('main-cta')
  if (cfg.cta) { cta.style.display = ''; document.getElementById('cta-label').textContent = cfg.cta }
  else cta.style.display = 'none'
}

function openCreate() {
  if (currentView === 'prompts')   openPromptModal()
  if (currentView === 'functions') openFunctionModal()
}

function exportCurrent() {
  if (currentView === 'prompts')   window.open(API+'/export/prompts','_blank')
  if (currentView === 'functions') window.open(API+'/export/functions','_blank')
  if (currentView === 'tables')    exportJSON(tablesData,'ia_db_tables.json')
  if (currentView === 'endpoints') exportJSON(endpointsData,'ia_endpoints.json')
}

// ── Toast ──────────────────────────────────────────────────
function toast(msg, type='success') {
  const t = document.createElement('div')
  t.className = `toast ${type}`; t.textContent = msg
  document.getElementById('toast-container').appendChild(t)
  setTimeout(() => t.remove(), 2800)
}

function closeModal(id, e) {
  if (e && e.target.id !== id) return
  document.getElementById(id).classList.remove('open')
}

// ══════════════════════════════════════════════════════
// PROMPTS
// ══════════════════════════════════════════════════════
let _pFilter = ''

async function loadPrompts() {
  const data = await api(API+'/prompts').catch(() => [])
  renderPrompts(data)
  updatePromptStats(data)
  document.getElementById('nav-prompts-count').textContent = data.length
}

function renderPrompts(data) {
  const fmtClass = {text:'fmt-text',json:'fmt-json',list:'fmt-list',markdown:'fmt-markdown'}
  const fmtLabel = {text:'Texte',json:'JSON',list:'Liste',markdown:'Markdown'}
  const filtered = _pFilter ? data.filter(p => p.return_format === _pFilter) : data
  const list  = document.getElementById('prompts-list')
  const empty = document.getElementById('prompts-empty')
  document.getElementById('prompts-count-badge').textContent = filtered.length
  if (!filtered.length) { list.innerHTML = ''; empty.style.display = 'block'; return }
  empty.style.display = 'none'
  list.innerHTML = filtered.map(p => `
    <div class="ia-card fadein ${p.is_active ? '' : 'opacity-50'}" onclick="openPromptModal(${p.id})">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
        <div style="flex:1;min-width:0;">
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
            <p style="font-size:13px;font-weight:500;color:#fff;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${p.name}</p>
            <span class="fmt-pill ${fmtClass[p.return_format]||'fmt-text'}">${fmtLabel[p.return_format]||p.return_format}</span>
            ${!p.is_active ? '<span class="badge bdg-z" style="font-size:9px;">Inactif</span>' : ''}
          </div>
          ${p.description ? `<p style="font-size:12px;color:#52525b;margin-bottom:8px;">${p.description}</p>` : ''}
          <div class="code-block" style="max-height:70px;">${escHtml(p.content||'').substring(0,300)}${(p.content||'').length>300?'…':''}</div>
        </div>
        <button class="btn-i" style="flex-shrink:0;" onclick="event.stopPropagation();openPromptModal(${p.id})">
          <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </button>
      </div>
      <p style="font-size:10px;color:#3f3f46;margin-top:8px;">${fmtDate(p.created_at)}</p>
    </div>
  `).join('')
}

function updatePromptStats(data) {
  document.getElementById('stat-p-total').textContent  = data.length
  document.getElementById('stat-p-active').textContent = data.filter(p => p.is_active).length
  const fmts = [...new Set(data.map(p => p.return_format))].join(', ')
  document.getElementById('stat-p-formats').textContent = fmts || '—'
  document.getElementById('stat-p-last').textContent    = data[0] ? fmtDate(data[0].created_at) : '—'
}

function filterPrompts(v) { _pFilter = v; loadPrompts() }

async function openPromptModal(id=null) {
  document.getElementById('p-id').value = ''
  document.getElementById('p-name').value = ''
  document.getElementById('p-desc').value = ''
  document.getElementById('p-content').value = ''
  document.getElementById('p-format').value = 'text'
  document.getElementById('p-active-toggle').classList.add('on')
  document.getElementById('p-del-btn').style.display = 'none'
  document.getElementById('m-prompt-title').textContent = 'Nouveau prompt'
  if (id) {
    const p = await api(API+'/prompts').then(d => d.find(x => x.id === id)).catch(() => null)
    if (!p) return
    document.getElementById('p-id').value      = p.id
    document.getElementById('p-name').value    = p.name
    document.getElementById('p-desc').value    = p.description || ''
    document.getElementById('p-content').value = p.content || ''
    document.getElementById('p-format').value  = p.return_format || 'text'
    if (!p.is_active) document.getElementById('p-active-toggle').classList.remove('on')
    document.getElementById('p-del-btn').style.display = ''
    document.getElementById('m-prompt-title').textContent = 'Modifier le prompt'
  }
  document.getElementById('m-prompt').classList.add('open')
}

async function savePrompt() {
  const id = document.getElementById('p-id').value
  const payload = {
    name:          document.getElementById('p-name').value.trim(),
    description:   document.getElementById('p-desc').value.trim(),
    content:       document.getElementById('p-content').value,
    return_format: document.getElementById('p-format').value,
    is_active:     document.getElementById('p-active-toggle').classList.contains('on') ? 1 : 0,
  }
  if (!payload.name) { toast('Nom requis','warn'); return }
  if (id) await api(API+'/prompts/'+id, {method:'PATCH', body:JSON.stringify(payload)})
  else    await api(API+'/prompts',     {method:'POST',  body:JSON.stringify(payload)})
  toast(id ? 'Prompt mis à jour' : 'Prompt créé')
  closeModal('m-prompt')
  loadPrompts()
}

async function deletePrompt() {
  const id = document.getElementById('p-id').value
  if (!id || !confirm('Supprimer ce prompt ?')) return
  await api(API+'/prompts/'+id, {method:'DELETE'})
  toast('Prompt supprimé','warn')
  closeModal('m-prompt')
  loadPrompts()
}

// ══════════════════════════════════════════════════════
// FONCTIONS
// ══════════════════════════════════════════════════════
async function loadFunctions() {
  const data = await api(API+'/functions').catch(() => [])
  renderFunctions(data)
  updateFunctionStats(data)
  document.getElementById('nav-functions-count').textContent = data.length
}

function renderFunctions(data) {
  const list  = document.getElementById('functions-list')
  const empty = document.getElementById('functions-empty')
  document.getElementById('functions-count-badge').textContent = data.length
  if (!data.length) { list.innerHTML = ''; empty.style.display = 'block'; return }
  empty.style.display = 'none'
  list.innerHTML = data.map(f => `
    <div class="ia-card fadein ${f.is_active ? '' : 'opacity-50'}" onclick="openFunctionModal(${f.id})">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:12px;">
        <div style="flex:1;min-width:0;">
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:4px;">
            <p style="font-size:13px;font-weight:500;font-family:'Geist Mono',monospace;color:#fff;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${f.name}</p>
            ${!f.is_active ? '<span class="badge bdg-z" style="font-size:9px;">Inactive</span>' : '<span class="badge bdg-v" style="font-size:9px;">Active</span>'}
          </div>
          ${f.description ? `<p style="font-size:12px;color:#52525b;margin-bottom:8px;">${f.description}</p>` : ''}
          <div class="code-block" style="max-height:90px;">${escHtml(f.code||'').substring(0,400)}${(f.code||'').length>400?'…':''}</div>
          <p style="font-size:10px;color:#3f3f46;margin-top:6px;">${(f.code||'').split('\n').length} lignes · ${fmtDate(f.created_at)}</p>
        </div>
        <button class="btn-i" style="flex-shrink:0;" onclick="event.stopPropagation();openFunctionModal(${f.id})">
          <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
        </button>
      </div>
    </div>
  `).join('')
}

function updateFunctionStats(data) {
  document.getElementById('stat-f-total').textContent  = data.length
  document.getElementById('stat-f-active').textContent = data.filter(f => f.is_active).length
  const lines = data.reduce((a,f) => a + (f.code||'').split('\n').length, 0)
  document.getElementById('stat-f-lines').textContent = lines
  document.getElementById('stat-f-last').textContent  = data[0] ? fmtDate(data[0].created_at) : '—'
}

async function openFunctionModal(id=null) {
  document.getElementById('f-id').value   = ''
  document.getElementById('f-name').value = ''
  document.getElementById('f-desc').value = ''
  document.getElementById('f-code').value = ''
  document.getElementById('f-active-toggle').classList.add('on')
  document.getElementById('f-del-btn').style.display = 'none'
  document.getElementById('m-function-title').textContent = 'Nouvelle fonction'
  if (id) {
    const f = await api(API+'/functions').then(d => d.find(x => x.id === id)).catch(() => null)
    if (!f) return
    document.getElementById('f-id').value   = f.id
    document.getElementById('f-name').value = f.name
    document.getElementById('f-desc').value = f.description || ''
    document.getElementById('f-code').value = f.code || ''
    if (!f.is_active) document.getElementById('f-active-toggle').classList.remove('on')
    document.getElementById('f-del-btn').style.display = ''
    document.getElementById('m-function-title').textContent = 'Modifier la fonction'
  }
  document.getElementById('m-function').classList.add('open')
}

async function saveFunction() {
  const id = document.getElementById('f-id').value
  const payload = {
    name:        document.getElementById('f-name').value.trim(),
    description: document.getElementById('f-desc').value.trim(),
    code:        document.getElementById('f-code').value,
    is_active:   document.getElementById('f-active-toggle').classList.contains('on') ? 1 : 0,
  }
  if (!payload.name) { toast('Nom requis','warn'); return }
  if (id) await api(API+'/functions/'+id, {method:'PATCH', body:JSON.stringify(payload)})
  else    await api(API+'/functions',     {method:'POST',  body:JSON.stringify(payload)})
  toast(id ? 'Fonction mise à jour' : 'Fonction créée')
  closeModal('m-function')
  loadFunctions()
}

async function deleteFunction() {
  const id = document.getElementById('f-id').value
  if (!id || !confirm('Supprimer cette fonction ?')) return
  await api(API+'/functions/'+id, {method:'DELETE'})
  toast('Fonction supprimée','warn')
  closeModal('m-function')
  loadFunctions()
}

// ══════════════════════════════════════════════════════
// TABLES DB
// ══════════════════════════════════════════════════════
function loadTablesFile(input) {
  const file = input.files[0]; if (!file) return
  const reader = new FileReader()
  reader.onload = e => {
    try {
      tablesData = JSON.parse(e.target.result)
      document.getElementById('tables-file-name').textContent = file.name
      document.getElementById('tables-file-info').style.display = 'flex'
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
  document.getElementById('tables-file-info').style.display = 'none'
  document.getElementById('tables-preview').textContent = 'Aucun fichier importé'
  document.getElementById('tables-rendered').innerHTML = ''
  document.getElementById('tables-empty').style.display = 'block'
  document.getElementById('tables-count').textContent = '0 tables'
}

function renderTables(data) {
  const tables    = Array.isArray(data) ? data : (data.tables || [])
  const container = document.getElementById('tables-rendered')
  const empty     = document.getElementById('tables-empty')
  document.getElementById('tables-count').textContent = tables.length + ' tables'
  if (!tables.length) { container.innerHTML = ''; empty.style.display = 'block'; return }
  empty.style.display = 'none'
  container.innerHTML = tables.map(t => `
    <div class="card fadein" style="padding:12px;">
      <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="color:#38bdf8;"><path d="M3 3h18v4H3zM3 11h18v4H3zM3 19h18v2H3z"/></svg>
        <p style="font-size:13px;font-weight:500;font-family:'Geist Mono',monospace;color:#fff;">${t.name}</p>
        <span class="badge bdg-z" style="font-size:9px;">${(t.columns||[]).length} cols</span>
      </div>
      <div style="display:flex;flex-wrap:wrap;gap:6px;">
        ${(t.columns||[]).map(c=>`
          <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:5px;padding:2px 7px;">
            <span style="font-family:'Geist Mono',monospace;font-size:10px;color:#a1a1aa;">${c.name}</span>
            <span style="font-size:9px;margin-left:4px;color:#3f3f46;">${c.type||''}</span>
          </div>
        `).join('')}
      </div>
    </div>
  `).join('')
}

function downloadTablesTemplate() {
  const tpl = {tables:[
    {name:"users",columns:[{name:"id",type:"INTEGER",pk:true},{name:"name",type:"TEXT"},{name:"telegram_id",type:"INTEGER"},{name:"created_at",type:"TEXT"}]},
    {name:"messages",columns:[{name:"id",type:"INTEGER",pk:true},{name:"user_id",type:"INTEGER"},{name:"message_text",type:"TEXT"},{name:"created_at",type:"TEXT"}]}
  ]}
  exportJSON(tpl,'ia_db_tables_template.json')
}

// ══════════════════════════════════════════════════════
// ENDPOINTS API
// ══════════════════════════════════════════════════════
function loadEndpointsFile(input) {
  const file = input.files[0]; if (!file) return
  const reader = new FileReader()
  reader.onload = e => {
    try {
      endpointsData = JSON.parse(e.target.result)
      document.getElementById('endpoints-file-name').textContent = file.name
      document.getElementById('endpoints-file-info').style.display = 'flex'
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
  document.getElementById('endpoints-file-info').style.display = 'none'
  document.getElementById('endpoints-preview').textContent = 'Aucun fichier importé'
  document.getElementById('endpoints-rendered').innerHTML = ''
  document.getElementById('endpoints-empty').style.display = 'block'
  document.getElementById('endpoints-count').textContent = '0 routes'
}

const METHOD_COLORS = {GET:'bdg-g',POST:'bdg-b',PATCH:'bdg-a',DELETE:'bdg-r',PUT:'bdg-v'}

function renderEndpoints(data) {
  const eps   = Array.isArray(data) ? data : (data.endpoints || [])
  const cont  = document.getElementById('endpoints-rendered')
  const empty = document.getElementById('endpoints-empty')
  document.getElementById('endpoints-count').textContent = eps.length + ' routes'
  if (!eps.length) { cont.innerHTML = ''; empty.style.display = 'block'; return }
  empty.style.display = 'none'
  cont.innerHTML = eps.map(ep => `
    <div class="file-row fadein">
      <span class="badge ${METHOD_COLORS[ep.method]||'bdg-z'}" style="font-size:10px;min-width:52px;justify-content:center;">${ep.method}</span>
      <span style="font-family:'Geist Mono',monospace;font-size:12px;color:#fff;flex:1;">${ep.route}</span>
      <span style="font-size:12px;color:#52525b;">${ep.description||''}</span>
      ${ep.payload ? '<span class="badge bdg-z" style="font-size:9px;">payload</span>' : ''}
    </div>
  `).join('')
}

function downloadEndpointsTemplate() {
  const tpl = {endpoints:[
    {method:"GET",  route:"/categorie",        description:"Liste des catégories",  payload:null},
    {method:"POST", route:"/broadcast",         description:"Envoyer un message",   payload:{message:"string"}},
    {method:"GET",  route:"/ia-config/prompts", description:"Liste des prompts IA", payload:null},
    {method:"POST", route:"/ia-config/prompts", description:"Créer un prompt IA",   payload:{name:"string",content:"string"}}
  ]}
  exportJSON(tpl,'ia_endpoints_template.json')
}

// ── Drag & Drop ────────────────────────────────────────────
function setupDrop(zoneId, inputId, loadFn) {
  const zone = document.getElementById(zoneId)
  zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('over') })
  zone.addEventListener('dragleave', ()  => zone.classList.remove('over'))
  zone.addEventListener('drop', e => {
    e.preventDefault(); zone.classList.remove('over')
    const file = e.dataTransfer.files[0]; if (!file) return
    const input = document.getElementById(inputId)
    const dt = new DataTransfer(); dt.items.add(file); input.files = dt.files
    loadFn(input)
  })
}

// ── Helpers ────────────────────────────────────────────────
function fmtDate(d) {
  if (!d) return '—'
  try { return new Date(d).toLocaleDateString('fr-FR',{day:'2-digit',month:'short',year:'numeric'}) }
  catch { return d }
}
function escHtml(s) {
  return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;')
}
function exportJSON(data, filename) {
  if (!data) { toast('Aucune donnée à exporter','warn'); return }
  const blob = new Blob([JSON.stringify(data,null,2)], {type:'application/json'})
  const a = Object.assign(document.createElement('a'), {href:URL.createObjectURL(blob), download:filename})
  a.click(); URL.revokeObjectURL(a.href)
}

// ══════════════════════════════════════════════════════
// NAVIGATE-THEN-ACTIVATE
// ══════════════════════════════════════════════════════
window._sbActivator = function (view) {
  if (VIEWS[view]) sv(view, document.getElementById('nav-' + view))
}

// ── Init ───────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
  setupDrop('drop-tables',    'file-tables',    loadTablesFile)
  setupDrop('drop-endpoints', 'file-endpoints', loadEndpointsFile)
  loadPrompts()
  loadFunctions()

  // Vue demandée via ?view= ou vue par défaut
  const startView = (window._sbPendingView && VIEWS[window._sbPendingView])
    ? window._sbPendingView
    : 'prompts'
  window._sbPendingView = null
  sv(startView, document.getElementById('nav-' + startView))
})
</script>
@endsection