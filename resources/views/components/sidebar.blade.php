
<!-- Overlay mobile -->
<div id="sb-overlay" onclick="SB.close()"></div>

<!-- ═══════════════ SIDEBAR UNIFIÉE ═══════════════ -->
<aside id="sidebar">

  <!-- Logo -->
  <div style="padding:16px;border-bottom:1px solid rgba(255,255,255,.05);flex-shrink:0;">
    <div style="display:flex;align-items:center;gap:10px;">
      <div style="width:30px;height:30px;background:#0ea5e9;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="15" height="15" fill="white" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.94 8.19l-2.02 9.52c-.14.66-.54.82-1.09.51l-3-2.21-1.45 1.39c-.16.16-.3.3-.61.3l.22-3.1 5.6-5.06c.24-.22-.06-.34-.38-.12L7.03 14.5 4.06 13.6c-.65-.2-.66-.65.14-.96l11.65-4.5c.54-.2 1.01.13.09 2.05z"/></svg>
      </div>
      <div style="min-width:0;">
        <p style="font-size:14px;font-weight:500;color:#fff;line-height:1;">Felipe</p>
        <p style="font-size:11px;color:#3f3f46;margin-top:3px;" id="sb-total">— membres</p>
      </div>
      <button id="sb-close-btn" onclick="SB.close()" style="display:none;margin-left:auto;width:26px;height:26px;background:rgba(255,255,255,.05);border:none;border-radius:6px;color:#71717a;cursor:pointer;align-items:center;justify-content:center;flex-shrink:0;">
        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>
  </div>

  <!-- Nav -->
  <nav style="flex:1;padding:10px 8px;overflow-y:auto;display:flex;flex-direction:column;gap:2px;">

    <div class="nav-section">Vue d'ensemble</div>

    {{-- Dashboard --}}
    <a href="{{ url('/dashboard') }}" class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
      Dashboard
    </a>

    <div class="nav-section" style="margin-top:6px;">Membres</div>

    {{-- Catégories --}}
    <a href="{{ route('categories') }}" class="nav-item {{ request()->is('categorie') ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
      Catégories
    </a>

    {{-- Formulaires (membres) --}}
    <a href="{{ url('/form') }}" class="nav-item {{ request()->is('form') ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>
      Formulaires
    </a>

    <div class="nav-section" style="margin-top:6px;">Messagerie</div>

    {{-- Messages ciblés (pas de route encore) --}}
    <a href="#" class="nav-item">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
      Messages ciblés
    </a>

    {{-- Chat --}}
    <a href="{{ route('chat') }}" class="nav-item {{ request()->is('chat') ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      Chat direct
      <span class="badge bdg-b ml-auto" id="nav-badge-chat" style="display:none;">0</span>
    </a>

    {{-- Agent IA + ses sous-vues --}}
<a href="{{ url('/ai') }}" class="nav-item {{ request()->is('ai') ? 'active' : '' }}">
  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/></svg>
  Agent IA
  <span style="display:flex;align-items:center;gap:4px;font-size:10px;color:#34d399;margin-left:auto;">
    <span style="width:5px;height:5px;border-radius:50%;background:#34d399;animation:pulse 2s ease infinite;display:block;"></span>live
  </span>
</a>

{{-- Sous-vues Agent IA (visibles uniquement sur /ai) --}}
@if(request()->is('ai'))
<a href="{{ url('/ai?view=prompts') }}"   class="nav-item {{ request()->query('view','prompts') === 'prompts'   ? 'active' : '' }}" style="padding-left:28px;font-size:12px;">
  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
  Prompts IA
</a>
<a href="{{ url('/ai?view=functions') }}" class="nav-item {{ request()->query('view') === 'functions' ? 'active' : '' }}" style="padding-left:28px;font-size:12px;">
  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
  Fonctions
</a>
<a href="{{ url('/ai?view=tables') }}"    class="nav-item {{ request()->query('view') === 'tables'    ? 'active' : '' }}" style="padding-left:28px;font-size:12px;">
  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M3 3h18v4H3zM3 11h18v4H3zM3 19h18v2H3z"/></svg>
  Tables DB
</a>
<a href="{{ url('/ai?view=endpoints') }}" class="nav-item {{ request()->query('view') === 'endpoints' ? 'active' : '' }}" style="padding-left:28px;font-size:12px;">
  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/><path d="M3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"/></svg>
  Endpoints API
</a>
@endif

    <div class="nav-section" style="margin-top:6px;">Trading</div>

    {{-- Journal --}}
    <a href="{{ SB::navUrl('/trade', 'journal') }}" class="nav-item {{ request()->is('trade') && (request()->query('view','journal') === 'journal') ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
      Journal
    </a>

    {{-- Paires & Pip --}}
    <a href="{{ SB::navUrl('/trade', 'paires') }}" class="nav-item {{ request()->is('trade') && request()->query('view') === 'paires' ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M3 3v18h18"/><line x1="7" y1="17" x2="7" y2="9"/><line x1="12" y1="17" x2="12" y2="5"/><line x1="17" y1="17" x2="17" y2="12"/></svg>
      Paires & Pip
    </a>

    {{-- Formulaires & Collecte --}}
    <a href="{{ SB::navUrl('/trade', 'formules') }}" class="nav-item {{ request()->is('trade') && request()->query('view') === 'formules' ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="9" y1="13" x2="15" y2="13"/><line x1="9" y1="17" x2="15" y2="17"/></svg>
      Formulaires & Collecte
    </a>

    <div class="nav-section" style="margin-top:6px;">Croissance</div>

    {{-- Liens & Onboarding --}}
    <a href="{{ SB::navUrl('/tache', 'links') }}" class="nav-item {{ request()->is('tache') && (request()->query('view','links') === 'links') ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
      Liens & Onboarding
    </a>

    {{-- Automations --}}
    <a href="{{ SB::navUrl('/tache', 'automations') }}" class="nav-item {{ request()->is('tache') && request()->query('view') === 'automations' ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
      Automations
      <span class="badge bdg-g ml-auto" id="nav-badge-jobs">0</span>
    </a>

    {{-- Abonnements (plans) --}}
    <a href="{{ SB::navUrl('/tache', 'subscriptions') }}" class="nav-item {{ request()->is('tache') && request()->query('view') === 'subscriptions' ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
      Abonnements (plans)
    </a>

    {{-- Promotions --}}
    <a href="{{ SB::navUrl('/tache', 'promos') }}" class="nav-item {{ request()->is('tache') && request()->query('view') === 'promos' ? 'active' : '' }}">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
      Promotions
    </a>

  </nav>

  <!-- Footer admin -->
  <div style="padding:12px 16px;border-top:1px solid rgba(255,255,255,.05);flex-shrink:0;">
    <div style="display:flex;align-items:center;gap:10px;">
      <div style="width:27px;height:27px;border-radius:50%;background:linear-gradient(135deg,#0ea5e9,#6366f1);flex-shrink:0;"></div>
      <div style="flex:1;min-width:0;">
        <p style="font-size:12px;font-weight:500;color:#d4d4d8;">Admin</p>
        <p style="font-size:10px;color:#3f3f46;">admin@tradingbot.io</p>
      </div>
    </div>
  </div>

</aside>
<!-- ═══════════════ FIN SIDEBAR ═══════════════ -->

<!-- Main wrapper -->
<div id="main-content">

  <!-- Topbar mobile (hamburger) -->
  <div id="topbar-mobile">
    <button onclick="SB.open()" aria-label="Menu">
      <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>
    <span style="font-size:14px;font-weight:500;color:#e4e4e7;">@yield('page-title', 'Dashboard')</span>
  </div>

  <!-- Contenu de chaque page -->
  <div style="flex:1;overflow:auto;">
    @yield('content')
  </div>

</div>

<!-- ═══════════════ JS SIDEBAR GLOBAL ═══════════════ -->
<script>
(function () {
  const API_BASE = 'http://54.226.165.244:8000';

  /* ── Compteur membres ── */
  async function loadMemberCount() {
    try {
      const r = await fetch(API_BASE + '/users/count');
      if (!r.ok) throw new Error();
      const d = await r.json();
      const count = d.count ?? d.total ?? d.nb_users ?? Object.values(d)[0];
      const el = document.getElementById('sb-total');
      if (el && count !== undefined)
        el.textContent = Number(count).toLocaleString('fr-FR') + ' membres';
    } catch { /* silencieux */ }
  }

  /* ── Responsive mobile ── */
  function applyMobileLayout() {
    const sb  = document.getElementById('sidebar');
    const ov  = document.getElementById('sb-overlay');
    const btn = document.getElementById('sb-close-btn');
    const isMob = window.innerWidth < 1024;
    if (isMob) {
      sb.style.position  = 'fixed';
      sb.style.top       = '0';
      sb.style.left      = '0';
      sb.style.bottom    = '0';
      sb.style.transform = 'translateX(-100%)';
      if (btn) btn.style.display = 'flex';
    } else {
      sb.style.position  = 'relative';
      sb.style.transform = 'none';
      if (ov)  ov.style.display  = 'none';
      if (btn) btn.style.display = 'none';
    }
  }

  /* ── API publique sidebar ── */
  window.SB = {
    open()  {
      const sb = document.getElementById('sidebar');
      const ov = document.getElementById('sb-overlay');
      sb.style.transform = 'translateX(0)';
      if (ov) ov.style.display = 'block';
    },
    close() {
      const sb = document.getElementById('sidebar');
      const ov = document.getElementById('sb-overlay');
      sb.style.transform = 'translateX(-100%)';
      if (ov) ov.style.display = 'none';
    }
  };

  /* ── Rétrocompat — anciennes pages appellent openSidebar/closeSidebar ── */
  window.openSidebar  = () => window.SB.open();
  window.closeSidebar = () => window.SB.close();
  window.closeMobSidebar = () => window.SB.close();

  window.addEventListener('resize', applyMobileLayout);

  document.addEventListener('DOMContentLoaded', () => {
    applyMobileLayout();
    loadMemberCount();

    /*
     * ── navigate-then-activate ──────────────────────────────────────
     * Si l'URL contient ?view=xxx, on attend que le JS local de la page
     * soit prêt (il s'enregistre via SB.registerActivator), puis on
     * déclenche la vue demandée.
     * ─────────────────────────────────────────────────────────────── */
    const urlView = new URLSearchParams(window.location.search).get('view');
    if (urlView && window._sbActivator) {
      window._sbActivator(urlView);
    } else if (urlView) {
      // Le JS local n'est pas encore prêt — on attend
      window._sbPendingView = urlView;
    }
  });
})();
</script>