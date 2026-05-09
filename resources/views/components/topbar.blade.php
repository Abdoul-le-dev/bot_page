<!-- Topbar -->
    <header class="topbar" style="flex-shrink:0; display:flex; align-items:center; justify-content:space-between; padding:0 20px; height:52px; border-bottom:1px solid rgba(255,255,255,.05); gap:12px;">
      <div style="display:flex; align-items:center; gap:10px; min-width:0;">
        <!-- Hamburger -->
        <button id="hamburger" aria-label="Menu">
          <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
            <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
          </svg>
        </button>
        <h1 class="text-sm font-medium text-white" style="white-space:nowrap;">Dashboard</h1>
        <span class="topbar-dot" style="color:#27272a;">·</span>
        <span class="text-xs" style="color:#3f3f46; white-space:nowrap;" id="current-date"></span>
      </div>
      <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;">

        <!-- Cloche notifs -->
        <div style="position:relative;" id="notif-wrapper">
          <button id="bell-btn"
                  style="width:32px;height:32px;border-radius:8px;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.07);"
                  class="flex items-center justify-center text-zinc-500 hover:text-zinc-300 transition-colors relative">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
              <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
            </svg>
            <span style="position:absolute;top:7px;right:7px;width:6px;height:6px;border-radius:50%;background:#f87171;border:1.5px solid #09090b;"></span>
          </button>

          <div id="notif-panel" class="notif-panel" style="display:none;">
            <div style="display:flex; align-items:center; justify-content:space-between; padding:12px 16px; border-bottom:1px solid rgba(255,255,255,.06);">
              <span class="text-xs font-medium text-white">Notifications</span>
              <button id="notif-clear" class="text-xs transition-colors"
                      style="color:#38bdf8;" onmouseover="this.style.color='#7dd3fc'" onmouseout="this.style.color='#38bdf8'">
                Tout lire
              </button>
            </div>
            <div style="max-height:300px; overflow-y:auto;">
              <div style="padding:12px 16px; display:flex; align-items:flex-start; gap:12px; cursor:pointer; border-bottom:1px solid rgba(255,255,255,.04); border-left:2px solid #f87171;">
                <span style="width:6px;height:6px;border-radius:50%;background:#f87171;margin-top:4px;flex-shrink:0;"></span>
                <div><p class="text-xs font-medium text-zinc-200">23 abonnements expirent</p>
                  <p class="text-[11px] mt-0.5" style="color:#52525b;">Dans 7 jours · il y a 2h</p></div>
              </div>
              <div style="padding:12px 16px; display:flex; align-items:flex-start; gap:12px; cursor:pointer; border-bottom:1px solid rgba(255,255,255,.04); border-left:2px solid #fbbf24;">
                <span style="width:6px;height:6px;border-radius:50%;background:#fbbf24;margin-top:4px;flex-shrink:0;"></span>
                <div><p class="text-xs font-medium text-zinc-200">Agent IA — 4 escalades</p>
                  <p class="text-[11px] mt-0.5" style="color:#52525b;">Intervention requise · il y a 35min</p></div>
              </div>
              <div style="padding:12px 16px; display:flex; align-items:flex-start; gap:12px; cursor:pointer; border-bottom:1px solid rgba(255,255,255,.04); border-left:2px solid #38bdf8;">
                <span style="width:6px;height:6px;border-radius:50%;background:#38bdf8;margin-top:4px;flex-shrink:0;"></span>
                <div><p class="text-xs font-medium text-zinc-200">Témoignage vidéo reçu · Lucie B.</p>
                  <p class="text-[11px] mt-0.5" style="color:#52525b;">Score 94% · il y a 1h</p></div>
              </div>
              <div style="padding:12px 16px; display:flex; align-items:flex-start; gap:12px; cursor:pointer; border-bottom:1px solid rgba(255,255,255,.04); border-left:2px solid #38bdf8;">
                <span style="width:6px;height:6px;border-radius:50%;background:#38bdf8;margin-top:4px;flex-shrink:0;"></span>
                <div><p class="text-xs font-medium text-zinc-200">Formulaire complété · Nicolas M.</p>
                  <p class="text-[11px] mt-0.5" style="color:#52525b;">Onboarding Forex · il y a 2h</p></div>
              </div>
              <div style="padding:12px 16px; display:flex; align-items:flex-start; gap:12px; cursor:pointer; border-left:2px solid #fbbf24;">
                <span style="width:6px;height:6px;border-radius:50%;background:#fbbf24;margin-top:4px;flex-shrink:0;"></span>
                <div><p class="text-xs font-medium text-zinc-200">67 membres inactifs +21j</p>
                  <p class="text-[11px] mt-0.5" style="color:#52525b;">Relance recommandée · hier</p></div>
              </div>
            </div>
          </div>
        </div>

        <button class="btn-primary">
          <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
          </svg>
          <span class="btn-primary-label">Nouveau message</span>
        </button>
      </div>
    </header>