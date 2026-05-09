{{-- ═══════════════════════════════════════════════════
     components/categories_topbar.blade.php
     ═══════════════════════════════════════════════════ --}}
<header class="topbar" style="flex-shrink:0;display:flex;align-items:center;justify-content:space-between;padding:0 20px;height:52px;border-bottom:1px solid rgba(255,255,255,.05);gap:12px;">
  <div style="display:flex;align-items:center;gap:10px;min-width:0;">
    <button id="hamburger" onclick="openSidebar()" aria-label="Menu">
      <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
    </button>
    <h1 class="text-sm font-medium text-white" style="white-space:nowrap;">Catégories</h1>
    <span style="color:#27272a;">·</span>
    <span id="topbar-label" class="text-xs" style="color:#3f3f46;white-space:nowrap;">8 catégories · 3 247 membres</span>
  </div>
  <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
    <div id="topbar-search" style="position:relative;width:200px;">
      <svg width="12" height="12" fill="none" stroke="#3f3f46" viewBox="0 0 24 24" stroke-width="2" style="position:absolute;left:9px;top:50%;transform:translateY(-50%);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
      <input class="input" type="text" placeholder="Rechercher..." style="padding-left:28px;font-size:12px;" oninput="filterCats(this.value)">
    </div>
    <button id="topbar-import" class="btn-ghost" onclick="openModal('modal-import')" style="font-size:12px;">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
      <span class="topbar-btn-label">Importer IDs</span>
    </button>
    <button class="btn-primary" onclick="openModal('modal-create')">
      <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      <span class="topbar-btn-label">Nouvelle catégorie</span>
    </button>
  </div>
</header>