<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Felipe Bot')</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400,500" rel="stylesheet">
  <script>tailwind.config={theme:{extend:{fontFamily:{sans:['Geist','sans-serif'],mono:['Geist Mono','monospace']}}}}</script>
  @yield('head')
  <style>
    /* ═══════════════════════════════════════════
       SIDEBAR UNIFIÉE — styles globaux
       Inclus une seule fois via le layout
    ═══════════════════════════════════════════ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body { background: #09090b; color: #d4d4d8; font-family: system-ui, sans-serif; display: flex; height: 100vh; overflow: hidden; }

    /* ── Sidebar ── */
    #sidebar {
      width: 208px;
      flex-shrink: 0;
      background: #0d0d0f;
      border-right: 1px solid rgba(255,255,255,.05);
      display: flex;
      flex-direction: column;
      height: 100%;
      position: relative;
      z-index: 70;
      transition: transform .25s cubic-bezier(.4,0,.2,1);
    }

    /* ── Overlay mobile ── */
    #sb-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,.6);
      z-index: 65;
    }

    /* ── Nav items ── */
    .nav-item {
      display: flex;
      align-items: center;
      gap: 8px;
      width: 100%;
      padding: 7px 10px;
      border-radius: 7px;
      border: none;
      background: transparent;
      color: #71717a;
      font-size: 13px;
      cursor: pointer;
      text-align: left;
      text-decoration: none;
      transition: background .15s, color .15s;
      white-space: nowrap;
      overflow: hidden;
    }
    .nav-item svg { width: 15px; height: 15px; flex-shrink: 0; }
    .nav-item:hover { background: rgba(255,255,255,.05); color: #e4e4e7; }
    .nav-item.active { background: rgba(14,165,233,.12); color: #0ea5e9; }

    /* ── Section label ── */
    .nav-section {
      font-size: 10px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: #3f3f46;
      padding: 0 10px;
      margin-bottom: 2px;
    }

    /* ── Badges ── */
    .badge {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 1px 5px;
      border-radius: 10px;
      font-size: 9px;
      font-weight: 600;
      line-height: 1;
      flex-shrink: 0;
    }
    .bdg-r { background: rgba(239,68,68,.15);  color: #ef4444; }
    .bdg-b { background: rgba(14,165,233,.15); color: #0ea5e9; }
    .bdg-g { background: rgba(52,211,153,.15); color: #34d399; }
    .bdg-v { background: rgba(99,102,241,.15); color: #6366f1; }
    .ml-auto { margin-left: auto; }

    /* ── Pulse animation ── */
    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

    /* ── Main content wrapper ── */
    #main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    /* ── Topbar mobile ── */
    #topbar-mobile {
      display: none;
      align-items: center;
      gap: 12px;
      padding: 10px 16px;
      background: #0d0d0f;
      border-bottom: 1px solid rgba(255,255,255,.05);
      flex-shrink: 0;
    }
    #topbar-mobile button {
      width: 34px; height: 34px;
      background: rgba(255,255,255,.05);
      border: none; border-radius: 8px;
      color: #71717a; cursor: pointer;
      display: flex; align-items: center; justify-content: center;
    }

    @media (max-width: 1023px) {
      #sidebar {
        position: fixed;
        top: 0; left: 0; bottom: 0;
        transform: translateX(-100%);
      }
      #topbar-mobile { display: flex; }
      #sb-close-btn  { display: flex !important; }
    }
  </style>
</head>
<body>


{{-- JS spécifique à chaque page --}}
@yield('scripts')

</body>
</html>