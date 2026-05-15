<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TradingBot — Catégories</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400" rel="stylesheet">
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Geist','sans-serif'],mono:['Geist Mono','monospace']}}}}</script>
<link rel="stylesheet" href="../css/categories.css">
<link rel="stylesheet" href="../css/dashboard.css">
</head>
<body class="h-screen overflow-hidden text-zinc-200">

<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<div style="display:flex;height:100vh;overflow:hidden;">

<!-- ─── SIDEBAR  categorie─── -->
<aside id="sidebar">
  
  <nav class="flex-1 px-2 py-3 overflow-y-auto flex flex-col gap-0.5">
    <div class="nav-section" style="margin-top:6px;">Membres</div>
    <button class="nav-item active"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>Catégories</button>
  </nav>
  
</aside>

<!-- ─── MAIN ─── -->
<div style="flex:1;display:flex;flex-direction:column;min-width:0;overflow:hidden;">

  <!-- Topbar -->
  <header class="topbar" style="flex-shrink:0;display:flex;align-items:center;justify-content:space-between;padding:0 20px;height:52px;border-bottom:1px solid rgba(255,255,255,.05);gap:12px;">
    <div style="display:flex;align-items:center;gap:10px;min-width:0;">
      <button id="hamburger" onclick="openSidebar()" aria-label="Menu">
        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <h1 class="text-sm font-medium text-white" style="white-space:nowrap;">Catégories</h1>
      <span style="color:#27272a;">·</span>
      <span id="topbar-label" class="text-xs" style="color:#3f3f46;white-space:nowrap;">Chargement...</span>
    </div>
    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
      <div id="topbar-search" style="position:relative;width:200px;">
        <svg width="12" height="12" fill="none" stroke="#3f3f46" viewBox="0 0 24 24" stroke-width="2" style="position:absolute;left:9px;top:50%;transform:translateY(-50%);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input class="input" type="text" placeholder="Rechercher..." style="padding-left:28px;font-size:12px;" oninput="filterCats(this.value)">
      </div>
      <button id="topbar-import" class="btn-ghost" onclick="App.openImportModal()" style="font-size:12px;">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        <span class="topbar-label">Importer IDs</span>
      </button>
      <button class="btn-primary" onclick="openModal('modal-create')">
        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
        <span class="topbar-label">Nouvelle catégorie</span>
      </button>
    </div>
  </header>

  <!-- Layout 3 colonnes -->
  <div style="display:flex;flex:1;overflow:hidden;position:relative;" class="cat-layout">

    <!-- ══ COLONNE GAUCHE ══ -->
    <div id="cat-left-panel">
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;padding:12px;border-bottom:1px solid rgba(255,255,255,.05);flex-shrink:0;" id="cat-stats-bar">
        <div class="stat-mini" style="text-align:center;padding:10px 8px;">
          <p class="text-lg font-light text-white tabular-nums">—</p>
          <p class="text-[10px] mt-0.5" style="color:#52525b;">Catégories</p>
        </div>
        <div class="stat-mini" style="text-align:center;padding:10px 8px;">
          <p class="text-lg font-light text-white tabular-nums">—</p>
          <p class="text-[10px] mt-0.5" style="color:#52525b;">Membres tagués</p>
        </div>
        <div class="stat-mini" style="text-align:center;padding:10px 8px;">
          <p class="text-lg font-light tabular-nums" style="color:#34d399;">—</p>
          <p class="text-[10px] mt-0.5" style="color:#52525b;">Tags / membre</p>
        </div>
      </div>
      <div style="flex:1;overflow-y:auto;padding:10px;" id="cat-list">
        <div style="padding:10px 16px;"><div style="height:60px;background:rgba(255,255,255,.04);border-radius:10px;"></div></div>
        <div style="padding:4px 16px;"><div style="height:60px;background:rgba(255,255,255,.03);border-radius:10px;"></div></div>
        <div style="padding:4px 16px;"><div style="height:60px;background:rgba(255,255,255,.02);border-radius:10px;"></div></div>
      </div>
    </div>

    <!-- ══ COLONNE CENTRALE ══ -->
    <div id="cat-detail-col" style="flex:1;display:flex;flex-direction:column;min-width:0;overflow:hidden;">

      <!-- Header détail -->
      <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.05);flex-shrink:0;flex-wrap:wrap;gap:10px;">
        <div style="display:flex;align-items:center;gap:10px;min-width:0;">
          <button id="btn-back" onclick="showList()">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="m15 18-6-6 6-6"/></svg>
          </button>
          <span id="detail-dot" style="width:10px;height:10px;border-radius:50%;background:#38bdf8;flex-shrink:0;"></span>
          <div>
            <h2 class="text-sm font-medium text-white" id="detail-name">—</h2>
            <p class="text-xs mt-0.5" style="color:#52525b;" id="detail-meta">Sélectionnez une catégorie</p>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap;">
          <button class="btn-ghost" style="font-size:11px;" onclick="App.openAddIdsModal()">
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
            Ajouter IDs
          </button>
          <button class="btn-ghost" style="font-size:11px;" onclick="App.openMoveModal()">
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
            Déplacer
          </button>
          <button class="btn-ghost" style="font-size:11px;" onclick="App.openEditModal(App.getSelected()?.name_categorie)">
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Modifier
          </button>
        </div>
      </div>

      <!-- Filtres membres -->
      <div style="display:flex;align-items:center;gap:8px;padding:10px 16px;border-bottom:1px solid rgba(255,255,255,.05);flex-shrink:0;flex-wrap:wrap;">
        <div style="position:relative;flex:1;max-width:220px;min-width:120px;">
          <svg width="11" height="11" fill="none" stroke="#3f3f46" viewBox="0 0 24 24" stroke-width="2" style="position:absolute;left:9px;top:50%;transform:translateY(-50%);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          <input class="input" id="member-search-input" type="text" placeholder="Chercher un membre..." style="padding-left:26px;font-size:12px;">
        </div>
        <div style="display:flex;align-items:center;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:8px;padding:2px;flex-shrink:0;">
          <button class="tab active" data-tab="all"      onclick="App.switchTab(this)">Tous</button>
          <button class="tab"        data-tab="active"   onclick="App.switchTab(this)">Actifs</button>
          <button class="tab"        data-tab="inactive" onclick="App.switchTab(this)">Inactifs</button>
        </div>
        <button id="export-csv-btn" class="btn-ghost" style="font-size:11px;margin-left:auto;flex-shrink:0;" onclick="App.exportCSV()">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          Export CSV
        </button>
      </div>

      <!-- Table membres -->
      <div style="flex:1;overflow-y:auto;" id="members-list"
           ondragover="event.preventDefault();this.classList.add('drag-over')"
           ondragleave="this.classList.remove('drag-over')"
           ondrop="dropMember(event)">
        <div style="padding:40px 20px;text-align:center;color:#3f3f46;">
          <p class="text-sm">Sélectionnez une catégorie</p>
        </div>
      </div>
    </div>

    <!-- ══ COLONNE DROITE ══ -->
    <div id="cat-right-panel">

      <!-- Règles d'attribution -->
      <div style="padding:16px;border-bottom:1px solid rgba(255,255,255,.05);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
          <p class="text-xs font-medium text-zinc-300">Règles d'attribution</p>
          <button class="btn-icon" style="width:22px;height:22px;" onclick="openModal('modal-rule')">
            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
          </button>
        </div>
        <div id="rules-list" style="display:flex;flex-direction:column;gap:6px;">
          <p class="text-[11px]" style="color:#3f3f46;">—</p>
        </div>
      </div>

      <!-- Statistiques -->
      <div style="padding:16px;border-bottom:1px solid rgba(255,255,255,.05);">
        <p class="text-xs font-medium text-zinc-300" style="margin-bottom:12px;">Statistiques</p>
        <div id="cat-stats-detail" style="display:flex;flex-direction:column;gap:8px;">
          <p class="text-[11px]" style="color:#3f3f46;">—</p>
        </div>
      </div>

      <!-- Présents aussi dans -->
      <div style="padding:16px;border-bottom:1px solid rgba(255,255,255,.05);">
        <p class="text-xs font-medium text-zinc-300" style="margin-bottom:12px;">Présents aussi dans</p>
        <div id="intersections-list" style="display:flex;flex-direction:column;gap:6px;">
          <p class="text-[11px]" style="color:#3f3f46;">—</p>
        </div>
      </div>

      <!-- Actions -->
      <div style="padding:16px;">
        <p class="text-xs font-medium text-zinc-300" style="margin-bottom:10px;">Actions</p>
        <div style="display:flex;flex-direction:column;gap:6px;">
          <button class="btn-ghost" style="font-size:11px;justify-content:flex-start;width:100%;" onclick="App.openAddIdsModal()">
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>Ajouter des IDs
          </button>
          <button class="btn-ghost" style="font-size:11px;justify-content:flex-start;width:100%;" onclick="App.openMoveModal()">
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>Déplacer des membres
          </button>
          <button class="btn-ghost" style="font-size:11px;justify-content:flex-start;width:100%;" onclick="App.openMergeModal()">
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M8 6H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-3"/><polyline points="15 3 12 6 9 3"/></svg>Fusionner
          </button>
          <button class="btn-ghost" style="font-size:11px;justify-content:flex-start;width:100%;" onclick="App.exportCSV()">
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>Exporter CSV
          </button>
          <button class="btn-danger" style="font-size:11px;justify-content:flex-start;width:100%;margin-top:4px;" onclick="App.openDeleteModal()">
            <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>Supprimer la catégorie
          </button>
        </div>
      </div>

    </div>
  </div>
</div>
</div>

<!-- ═══════════════════════════════════════════
     MODALS
     ═══════════════════════════════════════════ -->

<!-- ── Créer catégorie ── -->
<div class="modal-overlay" id="modal-create">
  <div class="modal">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <p class="text-sm font-medium text-white">Nouvelle catégorie</p>
      <button class="btn-icon" onclick="closeModal('modal-create')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div style="padding:18px 20px;display:flex;flex-direction:column;gap:14px;overflow-y:auto;flex:1;">
      <div>
        <p class="text-xs mb-2" style="color:#52525b;">Nom</p>
        <input class="input" id="create-name-input" type="text" placeholder="ex: Prospects Webinar Juin">
      </div>
      <div>
        <p class="text-xs mb-2" style="color:#52525b;">Description <span style="color:#3f3f46;">(optionnel)</span></p>
        <textarea class="input" id="create-desc-input" style="min-height:52px;" placeholder="À quoi sert cette catégorie..."></textarea>
      </div>
      <div>
        <p class="text-xs mb-2" style="color:#52525b;">Couleur</p>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <span class="color-dot selected" style="background:#34d399;" onclick="selectColor(this)"></span>
          <span class="color-dot" style="background:#38bdf8;" onclick="selectColor(this)"></span>
          <span class="color-dot" style="background:#fbbf24;" onclick="selectColor(this)"></span>
          <span class="color-dot" style="background:#f87171;" onclick="selectColor(this)"></span>
          <span class="color-dot" style="background:#a78bfa;" onclick="selectColor(this)"></span>
          <span class="color-dot" style="background:#2dd4bf;" onclick="selectColor(this)"></span>
          <span class="color-dot" style="background:#fb923c;" onclick="selectColor(this)"></span>
          <span class="color-dot" style="background:#71717a;" onclick="selectColor(this)"></span>
        </div>
      </div>
      <div>
        <p class="text-xs mb-2" style="color:#52525b;">Règle automatique <span style="color:#3f3f46;">(optionnel)</span></p>
        <select class="input mb-2" id="create-rule-type">
          <option value="">Manuelle uniquement</option>
          <option value="link">Rejoint via un lien spécifique</option>
          <option value="inactivity">Inactif depuis X jours</option>
          <option value="survey">A répondu à un sondage</option>
          <option value="subscription">Abonnement actif</option>
          <option value="trade_perf">Trade avec perf ≥ X%</option>
          <option value="keyword">Mot-clé dans un message</option>
          <option value="no_open">N'a pas lu le dernier message</option>
        </select>
        <input class="input" id="create-rule-value" type="text" placeholder="Valeur / condition (ex: forex-pro, 14, intéressé...)" style="font-size:12px;font-family:'Geist Mono',monospace;">
      </div>
      <div>
        <p class="text-xs mb-2" style="color:#52525b;">Ajouter des IDs maintenant <span style="color:#3f3f46;">(optionnel)</span></p>
        <textarea class="input" id="create-ids-input" style="min-height:56px;font-family:'Geist Mono',monospace;font-size:12px;" placeholder="123, 456, 789&#10;ou un ID par ligne"></textarea>
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:12px 20px;border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <button class="btn-ghost" onclick="closeModal('modal-create')">Annuler</button>
      <button class="btn-primary" id="btn-create-confirm">Créer la catégorie</button>
    </div>
  </div>
</div>

<!-- ── Éditer catégorie ── -->
<div class="modal-overlay" id="modal-edit">
  <div class="modal" style="max-width:400px;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <p class="text-sm font-medium text-white">Modifier la catégorie</p>
      <button class="btn-icon" onclick="closeModal('modal-edit')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div style="padding:18px 20px;display:flex;flex-direction:column;gap:14px;">
      <div>
        <p class="text-xs mb-2" style="color:#52525b;">Nom</p>
        <input class="input" id="edit-name-input" type="text">
      </div>
      <div>
        <p class="text-xs mb-2" style="color:#52525b;">Couleur</p>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
          <span class="color-dot selected" style="background:#34d399;" onclick="selectColor(this)"></span>
          <span class="color-dot" style="background:#38bdf8;" onclick="selectColor(this)"></span>
          <span class="color-dot" style="background:#fbbf24;" onclick="selectColor(this)"></span>
          <span class="color-dot" style="background:#f87171;" onclick="selectColor(this)"></span>
          <span class="color-dot" style="background:#a78bfa;" onclick="selectColor(this)"></span>
          <span class="color-dot" style="background:#2dd4bf;" onclick="selectColor(this)"></span>
          <span class="color-dot" style="background:#fb923c;" onclick="selectColor(this)"></span>
          <span class="color-dot" style="background:#71717a;" onclick="selectColor(this)"></span>
        </div>
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:12px 20px;border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <button class="btn-ghost" onclick="closeModal('modal-edit')">Annuler</button>
      <button class="btn-primary" id="btn-edit-confirm">Enregistrer</button>
    </div>
  </div>
</div>

<!-- ── Ajouter IDs ── -->
<div class="modal-overlay" id="modal-add-ids">
  <div class="modal" style="max-width:440px;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <div>
        <p class="text-sm font-medium text-white">Ajouter des IDs</p>
        <p class="text-[11px] mt-0.5" style="color:#52525b;" id="add-ids-modal-sub">Catégorie : —</p>
      </div>
      <button class="btn-icon" onclick="closeModal('modal-add-ids')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div style="padding:18px 20px;display:flex;flex-direction:column;gap:14px;">
      <div>
        <p class="text-xs mb-2" style="color:#52525b;">IDs Telegram — séparés par virgule ou un par ligne</p>
        <textarea class="input" id="add-ids-textarea" style="min-height:90px;font-family:'Geist Mono',monospace;font-size:12px;" placeholder="123456&#10;789012&#10;345678, 901234"></textarea>
      </div>
      <div style="padding:10px 12px;background:rgba(56,189,248,.05);border:1px solid rgba(56,189,248,.15);border-radius:9px;display:flex;gap:10px;align-items:flex-start;">
        <svg width="13" height="13" fill="none" stroke="#38bdf8" viewBox="0 0 24 24" stroke-width="1.5" style="flex-shrink:0;margin-top:1px;"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
        <p class="text-xs" style="color:#38bdf8;">Un membre peut appartenir à plusieurs catégories. Les IDs déjà présents seront ignorés.</p>
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:12px 20px;border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <button class="btn-ghost" onclick="closeModal('modal-add-ids')">Annuler</button>
      <button class="btn-primary" id="btn-add-ids-confirm">Ajouter les IDs</button>
    </div>
  </div>
</div>

<!-- ── Importer CSV ── -->
<div class="modal-overlay" id="modal-import">
  <div class="modal" style="max-width:440px;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <p class="text-sm font-medium text-white">Importer depuis un CSV</p>
      <button class="btn-icon" onclick="closeModal('modal-import')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div style="padding:18px 20px;display:flex;flex-direction:column;gap:14px;">
      <div id="import-drop-zone"
           style="border:1px dashed rgba(255,255,255,.12);border-radius:10px;padding:24px;text-align:center;cursor:pointer;transition:border-color .15s;color:#52525b;"
           onclick="document.getElementById('import-file-input').click()"
           onmouseover="this.style.borderColor='rgba(56,189,248,.35)';this.style.color='#38bdf8'"
           onmouseout="this.style.borderColor='rgba(255,255,255,.12)';this.style.color='#52525b'"
           ondragover="event.preventDefault();this.style.borderColor='rgba(56,189,248,.5)'"
           ondragleave="this.style.borderColor='rgba(255,255,255,.12)'"
           ondrop="handleImportDrop(event)">
        <svg width="26" height="26" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5" style="margin:0 auto 8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
        <p class="text-xs" id="import-file-label">Glisser un fichier CSV ou <span style="color:#38bdf8;">parcourir</span></p>
        <p class="text-[10px] mt-1" style="color:#3f3f46;">Colonne attendue : <span style="font-family:'Geist Mono',monospace;">user_id</span></p>
      </div>
      <input type="file" id="import-file-input" accept=".csv" style="display:none;"
             onchange="document.getElementById('import-file-label').textContent = this.files[0]?.name || 'Aucun fichier'">
      <div>
        <p class="text-xs mb-2" style="color:#52525b;">Catégorie cible</p>
        <select class="input" id="import-category-select"></select>
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:12px 20px;border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <button class="btn-ghost" onclick="closeModal('modal-import')">Annuler</button>
      <button class="btn-primary" id="btn-import-confirm">Importer</button>
    </div>
  </div>
</div>

<!-- ── Fusionner ── -->
<div class="modal-overlay" id="modal-merge">
  <div class="modal" style="max-width:420px;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <p class="text-sm font-medium text-white">Fusionner des catégories</p>
      <button class="btn-icon" onclick="closeModal('modal-merge')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div style="padding:18px 20px;display:flex;flex-direction:column;gap:14px;">
      <div>
        <p class="text-xs mb-2" style="color:#52525b;" id="merge-title">Fusionner dans "—"</p>
        <div id="merge-sources-list" style="display:flex;flex-direction:column;gap:8px;"></div>
      </div>
      <div style="padding:10px 12px;background:rgba(251,191,36,.05);border:1px solid rgba(251,191,36,.15);border-radius:9px;">
        <p class="text-xs" style="color:#fbbf24;">Les catégories sources seront supprimées après fusion. Les doublons seront ignorés.</p>
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:12px 20px;border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <button class="btn-ghost" onclick="closeModal('modal-merge')">Annuler</button>
      <button class="btn-primary" id="btn-merge-confirm">Fusionner</button>
    </div>
  </div>
</div>

<!-- ── Déplacer ── -->
<div class="modal-overlay" id="modal-move">
  <div class="modal" style="max-width:400px;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <p class="text-sm font-medium text-white">Déplacer vers une autre catégorie</p>
      <button class="btn-icon" onclick="closeModal('modal-move')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div style="padding:18px 20px;display:flex;flex-direction:column;gap:14px;">
      <div>
        <p class="text-xs mb-2" style="color:#52525b;">Catégorie destination</p>
        <select class="input" id="move-destination-select"></select>
      </div>
      <div>
        <p class="text-xs mb-2" style="color:#52525b;">Membres concernés</p>
        <div style="display:flex;gap:16px;">
          <label style="display:flex;align-items:center;gap:8px;font-size:12px;color:#a1a1aa;cursor:pointer;">
            <input type="radio" name="move-scope" value="selected" checked style="accent-color:#38bdf8;">
            <span data-scope="selected">Sélectionnés (0)</span>
          </label>
          <label style="display:flex;align-items:center;gap:8px;font-size:12px;color:#a1a1aa;cursor:pointer;">
            <input type="radio" name="move-scope" value="all" style="accent-color:#38bdf8;"> Tous
          </label>
        </div>
      </div>
      <div>
        <p class="text-xs mb-2" style="color:#52525b;">Action</p>
        <div style="display:flex;gap:16px;flex-wrap:wrap;">
          <label style="display:flex;align-items:center;gap:8px;font-size:12px;color:#a1a1aa;cursor:pointer;">
            <input type="radio" name="move-action" value="copy" checked style="accent-color:#38bdf8;"> Copier (garder aussi ici)
          </label>
          <label style="display:flex;align-items:center;gap:8px;font-size:12px;color:#a1a1aa;cursor:pointer;">
            <input type="radio" name="move-action" value="move" style="accent-color:#38bdf8;"> Déplacer (retirer ici)
          </label>
        </div>
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:12px 20px;border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <button class="btn-ghost" onclick="closeModal('modal-move')">Annuler</button>
      <button class="btn-primary" id="btn-move-confirm">Confirmer</button>
    </div>
  </div>
</div>

<!-- ── Nouvelle règle ── -->
<div class="modal-overlay" id="modal-rule">
  <div class="modal" style="max-width:440px;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <p class="text-sm font-medium text-white">Nouvelle règle d'attribution</p>
      <button class="btn-icon" onclick="closeModal('modal-rule')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div style="padding:18px 20px;display:flex;flex-direction:column;gap:14px;">
      <div>
        <p class="text-xs mb-2" style="color:#52525b;">Déclencheur</p>
        <select class="input" id="rule-trigger-type">
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
        <p class="text-xs mb-2" style="color:#52525b;">Valeur / condition</p>
        <input class="input" id="rule-trigger-value" type="text" placeholder="ex: forex-pro, 21, intéressé..." style="font-family:'Geist Mono',monospace;font-size:12px;">
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:12px 20px;border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <button class="btn-ghost" onclick="closeModal('modal-rule')">Annuler</button>
      <button class="btn-primary" id="btn-rule-confirm">Ajouter la règle</button>
    </div>
  </div>
</div>

<!-- ── Supprimer ── -->
<div class="modal-overlay" id="modal-delete">
  <div class="modal" style="max-width:360px;">
    <div style="padding:20px;">
      <p class="text-sm font-medium text-white mb-2" id="delete-modal-title">Supprimer cette catégorie ?</p>
      <p class="text-xs leading-relaxed" style="color:#71717a;" id="delete-modal-desc">Cette action est irréversible.</p>
    </div>
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:12px 20px;border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-ghost" onclick="closeModal('modal-delete')">Annuler</button>
      <button class="btn-danger" style="padding:7px 14px;font-size:12px;" id="btn-delete-confirm">Supprimer définitivement</button>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════
     DRAWER MEMBRE
     ═══════════════════════════════════════════ -->
<div class="drawer-overlay" id="drawer-overlay" onclick="closeDrawer()"></div>
<div class="drawer" id="member-drawer">
  <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
    <p class="text-sm font-medium text-white">Profil membre</p>
    <button class="btn-icon" onclick="closeDrawer()"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
  </div>
  <div style="flex:1;overflow-y:auto;padding:18px 20px;display:flex;flex-direction:column;gap:18px;">
    <div style="display:flex;align-items:center;gap:12px;">
      <div class="av av-sky" id="drawer-av" style="width:44px;height:44px;font-size:14px;">—</div>
      <div>
        <p class="text-sm font-medium text-white" id="drawer-name">—</p>
        <p class="text-xs mt-0.5" style="color:#52525b;font-family:'Geist Mono',monospace;" id="drawer-sub">—</p>
      </div>
    </div>
    <div>
      <p class="text-xs font-medium text-zinc-300 mb-2">Catégories actives</p>
      <div id="drawer-categories" style="display:flex;flex-wrap:wrap;gap:6px;">
        <span style="color:#3f3f46;font-size:11px;">—</span>
      </div>
    </div>
    <div style="background:rgba(255,255,255,.025);border:1px solid rgba(255,255,255,.05);border-radius:10px;padding:14px;">
      <div id="drawer-info"></div>
    </div>
    <div>
      <p class="text-xs font-medium text-zinc-300 mb-2">Ajouter à une catégorie</p>
      <select class="input" id="drawer-add-cat" style="font-size:12px;"></select>
    </div>
  </div>
  <div style="display:flex;align-items:center;gap:8px;padding:12px 20px;border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;">
    <button class="btn-primary" id="btn-drawer-add-cat" style="flex:1;justify-content:center;">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      Ajouter à la catégorie
    </button>
    <button class="btn-danger" id="btn-drawer-remove" title="Retirer de la catégorie actuelle">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
    </button>
  </div>
</div>

<!-- ═══════════════════════════════════════════
     SCRIPTS
     ═══════════════════════════════════════════ -->
<script src="../js/categories_api.js"></script>
<script src="../js/categories_render.js"></script>
<script src="../js/categories_app.js"></script>
<script src="../js/categories.js"></script>
<script src="../js/dashboard.js"></script>

<script>
function handleImportDrop(e) {
  e.preventDefault()
  const file = e.dataTransfer.files?.[0]
  if (!file) return
  const dt = new DataTransfer()
  dt.items.add(file)
  document.getElementById('import-file-input').files = dt.files
  document.getElementById('import-file-label').textContent = file.name
  e.currentTarget.style.borderColor = 'rgba(52,211,153,.4)'
}
</script>

</body>
</html>