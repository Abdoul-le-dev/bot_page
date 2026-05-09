{{-- ═══════════════════════════════════════════════════════════════
     components/messages_topbar.blade.php
     ═══════════════════════════════════════════════════════════════ --}}

<header class="topbar" style="flex-shrink:0;display:flex;align-items:center;justify-content:space-between;padding:0 20px;height:52px;border-bottom:1px solid rgba(255,255,255,.05);gap:12px;">

  <div style="display:flex;align-items:center;gap:10px;min-width:0;overflow:hidden;">
    <button id="hamburger" aria-label="Menu">
      <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
      </svg>
    </button>
    <h1 class="text-sm font-medium text-white" style="white-space:nowrap;">Messages ciblés</h1>
    <span style="color:#27272a;flex-shrink:0;">·</span>
    <div style="display:flex;align-items:center;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:8px;padding:2px;flex-shrink:0;">
      <button class="tab active" id="tab-compose" onclick="switchView('compose', this)">Composer</button>
      <button class="tab"        id="tab-history" onclick="switchView('history', this)">Historique</button>
    </div>
  </div>

  <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">

    <button class="btn-ghost" onclick="openModal('modal-preview')">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
        <circle cx="12" cy="12" r="3"/>
      </svg>
      <span class="topbar-btn-label">Aperçu</span>
    </button>

    <button class="btn-ghost" id="btn-schedule" onclick="openModal('modal-schedule')">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <rect x="3" y="4" width="18" height="18" rx="2"/>
        <path d="M16 2v4M8 2v4M3 10h18"/>
      </svg>
      <span class="topbar-btn-label">Planifier</span>
    </button>

    {{-- ← openConfirmModal() valide ET remplit le modal avant de l'ouvrir --}}
    <button class="btn-primary" id="btn-send" onclick="openConfirmModal()">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="m22 2-7 20-4-9-9-4 20-7z"/>
      </svg>
      <span class="topbar-btn-label">Envoyer maintenant</span>
    </button>

  </div>
</header>