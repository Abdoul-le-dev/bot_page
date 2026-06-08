<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Felipe Bot — Dashboard</title>

  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400,500" rel="stylesheet">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['Geist', 'sans-serif'],
            mono: ['Geist Mono', 'monospace'],
          },
          colors: {
            zinc: { 950: '#09090b' }
          }
        }
      }
    }
  </script>

  <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body class="bg-[#09090b] text-zinc-300 font-sans h-screen overflow-hidden">

<!-- Toast -->
<div id="toast-container" class="fixed bottom-5 right-5 z-[999] flex flex-col gap-2"></div>

<!-- Overlay mobile -->
<div id="sidebar-overlay"
     class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[99] hidden opacity-0 transition-opacity duration-200"></div>

<!-- APP SHELL -->
<div class="flex h-screen overflow-hidden">

  <!-- ═══ SIDEBAR ═══ -->
  <!-- Sur mobile : fixed + w-[200px] mais le flex parent ne lui réserve PAS de place (md:flex-shrink-0) -->
  <aside id="sidebar"
         class="fixed md:static top-0 left-0 h-full w-[200px]
                md:flex-shrink-0
                bg-[#0d0d0f] border-r md:border-r border-white/5
                flex flex-col z-[100]
                -translate-x-full md:translate-x-0
                transition-transform duration-[250ms] ease-in-out">

    <!-- Header -->
    <div class="flex items-center justify-between px-4 py-4 border-b border-white/5 flex-shrink-0">
      <div class="flex items-center gap-2">
        <div class="w-6 h-6 bg-amber-400/15 border border-amber-400/30 rounded-md flex items-center justify-center text-[11px]">⚡</div>
        <span class="text-[13px] font-medium text-zinc-50">Felipe Bot</span>
      </div>
      <!-- Bouton fermeture (mobile) -->
      <button id="sidebar-close"
              class="md:hidden flex items-center justify-center w-7 h-7 rounded-md bg-white/5 border border-white/10 text-zinc-500 hover:text-zinc-200 hover:bg-white/10 transition-all text-sm">
        ✕
      </button>
    </div>

    <!-- Nav -->
    <nav class="flex-1 p-2 flex flex-col gap-0.5 overflow-y-auto">

      <p class="text-[10px] font-medium text-zinc-700 uppercase tracking-wider px-2.5 pt-2.5 pb-1">Principal</p>

      <a href="/dashboard"
         class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px] text-zinc-50 bg-white/[0.07] transition-all">
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/>
          <rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/>
        </svg>
        Dashboard
      </a>

      <a href="{{ route('categories') }}"
         class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px] text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04] transition-all">
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
          <line x1="7" y1="7" x2="7.01" y2="7"/>
        </svg>
        Catégories
      </a>

      <a href="/chat"
         class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px] text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04] transition-all">
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
        Chat direct
      </a>

      <a href="/message"
         class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px] text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04] transition-all">
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M22 2 11 13"/><path d="m22 2-7 20-4-9-9-4 20-7z"/>
        </svg>
        Broadcast
      </a>

      <p class="text-[10px] font-medium text-zinc-700 uppercase tracking-wider px-2.5 pt-3 pb-1">Trading</p>

      <a href="/trade"
         class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px] text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04] transition-all">
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
          <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
        </svg>
        Trade
      </a>

      <p class="text-[10px] font-medium text-zinc-700 uppercase tracking-wider px-2.5 pt-3 pb-1">Croissance</p>
      <a href="/tache" class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px] text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04] transition-all">
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
          <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
        </svg>
        Liens & Onboarding
      </a>

      <p class="text-[10px] font-medium text-zinc-700 uppercase tracking-wider px-2.5 pt-3 pb-1">Outils</p>

      <a href="/form"
         class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px] text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04] transition-all">
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
          <polyline points="14 2 14 8 20 8"/>
        </svg>
        Formulaires
      </a>

      <a href="/ai"
         class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px] text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04] transition-all">
        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <circle cx="12" cy="12" r="3"/>
          <path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/>
        </svg>
        Agent IA
      </a>
    </nav>

    <!-- Footer -->
    <div class="px-3 py-3 border-t border-white/5 flex-shrink-0">
      <div class="flex items-center gap-2">
        <div class="w-6 h-6 rounded-full bg-white/[0.07] flex items-center justify-center text-[9px] font-semibold text-zinc-400">AD</div>
        <div>
          <p class="text-[11px] font-medium text-zinc-300">Admin</p>
          <p class="text-[10px] text-zinc-600">fdkvip.com</p>
        </div>
      </div>
    </div>
  </aside>

  <!-- ═══ MAIN ═══ -->
  <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

    <!-- Topbar -->
    <header class="h-[52px] bg-[#09090b]/90 backdrop-blur-md border-b border-white/5 flex-shrink-0 flex items-center px-4 md:px-6 gap-3">

      <!-- Gauche -->
      <div class="flex items-center gap-2 flex-1 min-w-0">
        <!-- Hamburger mobile -->
        <button id="btn-menu"
                class="md:hidden flex items-center justify-center w-8 h-8 rounded-lg bg-white/5 border border-white/10 text-zinc-400 hover:text-zinc-200 hover:bg-white/10 transition-all flex-shrink-0">
          <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <line x1="3" y1="6"  x2="21" y2="6"/>
            <line x1="3" y1="12" x2="21" y2="12"/>
            <line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </button>

        <h1 class="text-[13px] font-medium text-zinc-50 hidden sm:block">Dashboard</h1>
        <span class="text-zinc-700 hidden sm:block">·</span>
        <div id="last-refresh-label" class="flex items-center gap-1.5 text-[11px] text-zinc-600">—</div>
      </div>

      <!-- Droite -->
      <div class="flex items-center gap-2 flex-shrink-0">
        <div class="flex items-center gap-1.5 text-[11px] text-emerald-400">
          <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse inline-block"></span>
          <span class="hidden sm:inline">Live</span>
        </div>

        <button id="btn-refresh"
                onclick="loadDashboard()"
                class="flex items-center gap-1.5 px-2.5 py-1.5 text-[11px] bg-white/5 text-zinc-400 border border-white/10 rounded-lg hover:bg-white/10 hover:text-zinc-200 transition-all font-sans cursor-pointer disabled:opacity-50">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <polyline points="23 4 23 10 17 10"/>
            <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/>
          </svg>
          <span class="hidden sm:inline">Actualiser</span>
        </button>

        <a href="/trade"
           class="flex items-center gap-1.5 px-3 py-1.5 text-[12px] font-medium bg-sky-400 text-sky-950 rounded-lg hover:bg-sky-300 transition-colors whitespace-nowrap">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <line x1="12" y1="4" x2="12" y2="20"/>
            <line x1="4" y1="12" x2="20" y2="12"/>
          </svg>
          <span class="hidden sm:inline">Nouveau trade</span>
          <span class="sm:hidden">+</span>
        </a>
      </div>
    </header>

    <!-- Content -->
    <main id="dashboard-content" class="flex-1 overflow-y-auto p-3 md:p-5 flex flex-col gap-3 md:gap-4">

      <!-- ROW 1 : métriques -->
      <div id="metrics-grid" class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-[#111113] border border-white/[0.06] rounded-xl h-24 skel"></div>
        <div class="bg-[#111113] border border-white/[0.06] rounded-xl h-24 skel"></div>
        <div class="bg-[#111113] border border-white/[0.06] rounded-xl h-24 skel"></div>
        <div class="bg-[#111113] border border-white/[0.06] rounded-xl h-24 skel"></div>
      </div>

      <!-- ROW 2 : Segments · Alertes · IA -->
      <div id="row2-grid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-3">
        <div class="bg-[#111113] border border-white/[0.06] rounded-xl h-[220px] skel"></div>
        <div class="bg-[#111113] border border-white/[0.06] rounded-xl h-[220px] skel"></div>
        <div class="bg-[#111113] border border-white/[0.06] rounded-xl h-[220px] skel md:col-span-2 xl:col-span-1"></div>
      </div>

      <!-- ROW 3 : Trading stats -->
      <div id="row3-trading"></div>

      <!-- ROW 4 : Gold -->
      <div id="row4-gold"></div>

      <!-- ROW 5 : Activité · Expirations -->
      <div id="row5-grid" class="grid grid-cols-1 md:grid-cols-2 gap-3">
        <div class="bg-[#111113] border border-white/[0.06] rounded-xl h-60 skel"></div>
        <div class="bg-[#111113] border border-white/[0.06] rounded-xl h-60 skel"></div>
      </div>

    </main>
  </div>
</div>

<script src="../js/dashboard.js"></script>
</body>
</html>

