<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TradingBot — Chat direct</title>

<!-- Tailwind CDN uniquement -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        fontFamily: { sans: ['Geist', 'sans-serif'], mono: ['Geist Mono', 'monospace'] },
        colors: {
          zinc: { 950: '#09090b' },
        },
        keyframes: {
          fadein:  { from: { opacity: '0', transform: 'translateY(4px)' }, to: { opacity: '1', transform: 'translateY(0)' } },
          pulse3:  { '0%,100%': { opacity: '.3', transform: 'scale(.8)' }, '50%': { opacity: '1', transform: 'scale(1)' } },
          slideIn: { from: { transform: 'translateX(-100%)' }, to: { transform: 'translateX(0)' } },
        },
        animation: {
          fadein:  'fadein .18s ease',
          pulse3:  'pulse3 1.2s ease infinite',
          slideIn: 'slideIn .25s ease',
        }
      }
    }
  }
</script>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400" rel="stylesheet">

<!-- Minimal : uniquement ce que Tailwind ne peut pas faire -->
<style>
  html, body { height: 100dvh; overflow: hidden; background: #0c0c0e; color: #e4e4e7; font-family: 'Geist', sans-serif; }

  /* Toggle switch */
  .toggle { position: relative; width: 30px; height: 17px; border-radius: 99px; background: rgba(255,255,255,.1); border: none; cursor: pointer; transition: background .2s; flex-shrink: 0; }
  .toggle::after { content: ''; position: absolute; top: 2px; left: 2px; width: 13px; height: 13px; border-radius: 50%; background: #71717a; transition: all .2s; }
  .toggle.on { background: rgba(45,212,191,.25); }
  .toggle.on::after { left: 15px; background: #2dd4bf; }

  /* Textarea auto-resize */
  textarea { field-sizing: content; }

  /* Date séparateur */
  .date-sep { display: flex; align-items: center; gap: 10px; margin: 12px 0; }
  .date-sep::before, .date-sep::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,.06); }

  /* Bulles */
  .bubble-in    { background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.06); border-bottom-left-radius: 4px; }
  .bubble-admin { background: rgba(245,158,11,.10); border: 1px solid rgba(245,158,11,.20); border-bottom-right-radius: 4px; }
  .bubble-ia    { background: rgba(45,212,191,.07); border: 1px solid rgba(45,212,191,.15); border-bottom-left-radius: 4px; }

  /* Reply quote */
  .reply-quote { border-left: 2px solid #f59e0b; background: rgba(255,255,255,.04); border-radius: 0 6px 6px 0; }

  /* Reply btn — visible au hover */
  .reply-btn { opacity: 0; transition: opacity .15s; }
  .msg-group:hover .reply-btn { opacity: 1; }

  /* Dots loader */
  .dot1 { animation: pulse3 1.2s ease infinite; }
  .dot2 { animation: pulse3 1.2s ease .15s infinite; }
  .dot3 { animation: pulse3 1.2s ease .3s infinite; }

  /* Scrollbar discrète */
  ::-webkit-scrollbar { width: 4px; }
  ::-webkit-scrollbar-track { background: transparent; }
  ::-webkit-scrollbar-thumb { background: rgba(255,255,255,.08); border-radius: 99px; }

  /* Compose textarea */
  #compose-input { min-height: 38px; max-height: 120px; resize: none; outline: none; line-height: 1.5; }
  #compose-input:focus { border-color: rgba(245,158,11,.35) !important; }

  /* Focus input */
  .inp:focus { border-color: rgba(245,158,11,.4) !important; outline: none; }
</style>
</head>

<body class="flex h-screen overflow-hidden bg-[#0c0c0e] text-zinc-200">

<!-- ═══════════════════════════════════════════════
     OVERLAY SIDEBAR (mobile)
     ═══════════════════════════════════════════════ -->
<div id="sb-overlay"
     onclick="closeSidebar()"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[99] hidden"></div>

<!-- ═══════════════════════════════════════════════
     SIDEBAR PRINCIPALE
     ═══════════════════════════════════════════════ -->
<aside id="sidebar"
       class="fixed md:static top-0 left-0 h-full w-[200px] z-[100]
              bg-[#0d0d0f] border-r border-white/[0.06]
              flex flex-col flex-shrink-0
              -translate-x-full md:translate-x-0
              transition-transform duration-[250ms] ease-in-out">

  <!-- Logo + fermeture -->
  <div class="flex items-center justify-between px-4 py-[14px] border-b border-white/[0.06] flex-shrink-0">
    <div class="flex items-center gap-2">
      <div class="w-6 h-6 rounded-md bg-amber-400/10 border border-amber-400/25 flex items-center justify-center text-[11px]">⚡</div>
      <span class="text-[13px] font-medium text-zinc-50">TradingBot</span>
    </div>
    <!-- Bouton ✕ — visible sur mobile uniquement -->
    <button onclick="closeSidebar()"
            class="md:hidden flex items-center justify-center w-7 h-7 rounded-md
                   bg-white/[0.05] border border-white/[0.08]
                   text-zinc-500 hover:text-zinc-200 hover:bg-white/10
                   transition-all text-xs cursor-pointer">✕</button>
  </div>

  <!-- Nav -->
  <nav class="flex-1 p-2 flex flex-col gap-0.5 overflow-y-auto">

    <p class="text-[10px] font-medium text-zinc-700 uppercase tracking-wider px-2.5 pt-2.5 pb-1">Principal</p>

    <a href="/dashboard"
       class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px]
              text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04] transition-all">
      <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
        <rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>
      </svg>
      Dashboard
    </a>

    <a href="/categories"
       class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px]
              text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04] transition-all">
      <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/>
        <line x1="7" y1="7" x2="7.01" y2="7"/>
      </svg>
      Catégories
    </a>

    <a href="/chat"
       class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px]
              text-zinc-50 bg-white/[0.07] transition-all">
      <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
      </svg>
      Chat direct
      <span id="nav-unread-badge"
            class="ml-auto text-[10px] px-1.5 py-0.5 rounded bg-sky-400/10 text-sky-400 hidden"></span>
    </a>

    <a href="/message"
       class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px]
              text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04] transition-all">
      <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path d="M22 2 11 13"/><path d="m22 2-7 20-4-9-9-4 20-7z"/>
      </svg>
      Broadcast
    </a>

    <p class="text-[10px] font-medium text-zinc-700 uppercase tracking-wider px-2.5 pt-3 pb-1">Trading</p>

    <a href="/trade"
       class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px]
              text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04] transition-all">
      <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/>
        <path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
      </svg>
      Trade
    </a>

    <p class="text-[10px] font-medium text-zinc-700 uppercase tracking-wider px-2.5 pt-3 pb-1">Outils</p>

    <a href="/form"
       class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px]
              text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04] transition-all">
      <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
      </svg>
      Formulaires
    </a>

    <a href="/ai"
       class="flex items-center gap-2.5 px-2.5 py-1.5 rounded-lg text-[13px]
              text-zinc-500 hover:text-zinc-300 hover:bg-white/[0.04] transition-all">
      <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
        <circle cx="12" cy="12" r="3"/>
        <path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/>
      </svg>
      Agent IA
    </a>
  </nav>

  <!-- Footer -->
  <div class="px-3 py-3 border-t border-white/[0.06] flex-shrink-0">
    <div class="flex items-center gap-2">
      <div class="w-6 h-6 rounded-full bg-white/[0.07] flex items-center justify-center text-[9px] font-semibold text-zinc-400">AD</div>
      <div>
        <p class="text-[11px] font-medium text-zinc-300">Admin</p>
        <p class="text-[10px] text-zinc-700">fdkvip.com</p>
      </div>
    </div>
  </div>
</aside>

<!-- ═══════════════════════════════════════════════
     ZONE PRINCIPALE
     ═══════════════════════════════════════════════ -->
<div class="flex-1 flex flex-col min-w-0 overflow-hidden">

  <!-- Topbar -->
  <header class="flex-shrink-0 flex items-center justify-between
                 px-5 h-[52px] bg-[#0f0f11]
                 border-b border-white/[0.06] gap-3">

    <div class="flex items-center gap-2.5 min-w-0">

      <!-- ★ HAMBURGER — toujours visible, ouvre sidebar en overlay -->
      <button id="hamburger"
              onclick="openSidebar()"
              aria-label="Ouvrir le menu"
              class="flex items-center justify-center w-[30px] h-[30px] flex-shrink-0
                     rounded-lg bg-white/[0.05] border border-white/[0.08]
                     text-zinc-500 hover:text-zinc-200 hover:bg-white/10
                     transition-all cursor-pointer">
        <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>

      <h1 class="text-sm font-medium text-white whitespace-nowrap">Chat direct</h1>
      <span class="text-zinc-800 hidden sm:inline">·</span>
      <span class="text-xs text-zinc-700 hidden sm:inline whitespace-nowrap">Timeline unifiée</span>
    </div>

    <div class="flex items-center gap-2 flex-shrink-0">
      <button onclick="openModal('modal-new-conv')"
              class="flex items-center gap-1.5 px-3 py-1.5
                     bg-amber-400/[0.12] border border-amber-400/[0.28]
                     rounded-lg text-amber-400 text-xs font-medium
                     hover:bg-amber-400/20 hover:border-amber-400/50
                     transition-all cursor-pointer whitespace-nowrap">
        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
          <path stroke-linecap="round" d="M12 4v16m8-8H4"/>
        </svg>
        <span class="hidden sm:inline">Nouvelle conversation</span>
        <span class="sm:hidden">Nouveau</span>
      </button>
    </div>
  </header>

  <!-- ── CHAT ROOT 3 colonnes ── -->
  <div class="flex flex-1 overflow-hidden relative">

    <!-- COL 1 — Liste conversations -->
    <div id="conv-col"
         class="w-[280px] flex-shrink-0 flex flex-col
                bg-[#0f0f11] border-r border-white/[0.06]
                overflow-hidden
                absolute md:static inset-0 md:inset-auto z-10
                md:flex">

      <!-- Recherche + tabs -->
      <div class="flex-shrink-0 p-2.5 border-b border-white/[0.06] flex flex-col gap-2">
        <div class="relative">
          <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-3 h-3 text-zinc-700"
               fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
          </svg>
          <input type="text"
                 placeholder="Rechercher…"
                 oninput="App.filterConvs(this.value)"
                 class="inp w-full bg-white/[0.03] border border-white/[0.06] rounded-lg
                        pl-7 pr-3 py-1.5 text-xs text-zinc-200 placeholder-zinc-700
                        transition-all">
        </div>

        <!-- Tabs -->
        <div class="flex items-center gap-0.5 bg-white/[0.03] border border-white/[0.05] rounded-lg p-0.5 overflow-x-auto">
          <button class="tab active flex-1 py-1 px-1.5 rounded-md text-[11px] text-zinc-400
                         transition-all whitespace-nowrap cursor-pointer"
                  onclick="App.switchConvTab(this,'all')">Tous</button>
          <button class="tab flex-1 py-1 px-1.5 rounded-md text-[11px] text-zinc-400
                         transition-all whitespace-nowrap cursor-pointer"
                  onclick="App.switchConvTab(this,'requires_admin')">
            ⚡<span id="tab-admin-count" class="ml-0.5 text-orange-400"></span>
          </button>
          <button class="tab flex-1 py-1 px-1.5 rounded-md text-[11px] text-zinc-400
                         transition-all whitespace-nowrap cursor-pointer"
                  onclick="App.switchConvTab(this,'unread')">
            Lus<span id="tab-unread-count" class="ml-0.5 text-sky-400"></span>
          </button>
          <button class="tab flex-1 py-1 px-1.5 rounded-md text-[11px] text-zinc-400
                         transition-all whitespace-nowrap cursor-pointer"
                  onclick="App.switchConvTab(this,'ia')">IA</button>
          <button class="tab flex-1 py-1 px-1.5 rounded-md text-[11px] text-zinc-400
                         transition-all whitespace-nowrap cursor-pointer"
                  onclick="App.switchConvTab(this,'blocked')">🚫</button>
        </div>
      </div>

      <!-- Liste -->
      <div id="conv-list" class="flex-1 overflow-y-auto">
        <div class="flex items-center justify-center gap-1.5 py-10">
          <span class="dot1 w-1.5 h-1.5 rounded-full bg-zinc-700 inline-block"></span>
          <span class="dot2 w-1.5 h-1.5 rounded-full bg-zinc-700 inline-block"></span>
          <span class="dot3 w-1.5 h-1.5 rounded-full bg-zinc-700 inline-block"></span>
        </div>
      </div>
    </div>

    <!-- COL 2 — Messages -->
    <div id="messages-col"
         class="flex-1 flex flex-col min-w-0 overflow-hidden
                hidden md:flex
                absolute md:static inset-0 md:inset-auto z-10
                bg-[#0c0c0e]">

      <!-- Chat header -->
      <div class="flex-shrink-0 flex items-center justify-between
                  px-4 py-2.5 bg-[#0f0f11]
                  border-b border-white/[0.06] gap-2.5 flex-wrap">
        <div class="flex items-center gap-2.5 min-w-0">

          <!-- Bouton retour (mobile uniquement) -->
          <button id="btn-back-conv"
                  onclick="App.backToList()"
                  class="md:hidden flex items-center justify-center w-7 h-7 flex-shrink-0
                         rounded-lg bg-white/[0.05] border border-white/[0.08]
                         text-zinc-500 hover:text-zinc-200 transition-all cursor-pointer">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" d="m15 18-6-6 6-6"/>
            </svg>
          </button>

          <div id="chat-av"
               class="w-8 h-8 rounded-full bg-white/[0.07] flex items-center justify-center
                      text-[11px] font-semibold text-zinc-400 flex-shrink-0">—</div>
          <div class="min-w-0">
            <p class="text-[13px] font-medium text-white truncate" id="chat-name">Sélectionner une conversation</p>
            <p class="text-[11px] text-zinc-600 truncate" id="chat-handle"></p>
          </div>
        </div>

        <div class="flex items-center gap-2 flex-shrink-0">
          <div class="flex items-center gap-1.5">
            <span class="text-[11px] text-zinc-600 hidden sm:inline">Agent IA</span>
            <button class="toggle" id="ia-toggle" onclick="App.toggleIA(this)"></button>
          </div>
          <div class="w-px h-4 bg-white/[0.08]"></div>
          <button onclick="App.openProfilePanel()"
                  class="flex items-center justify-center w-7 h-7 rounded-lg
                         bg-white/[0.04] border border-white/[0.06]
                         text-zinc-500 hover:text-zinc-200 hover:bg-white/[0.08]
                         transition-all cursor-pointer">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
            </svg>
          </button>
          <button onclick="openModal('modal-actions')"
                  class="flex items-center justify-center w-7 h-7 rounded-lg
                         bg-white/[0.04] border border-white/[0.06]
                         text-zinc-500 hover:text-zinc-200 hover:bg-white/[0.08]
                         transition-all cursor-pointer">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/>
            </svg>
          </button>
        </div>
      </div>

      <!-- Bannière bloqué -->
      <div id="blocked-banner"
           class="hidden flex-shrink-0 items-center gap-2
                  px-4 py-2 text-xs text-red-400
                  bg-red-400/[0.06] border-b border-white/[0.06]">
        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/>
        </svg>
        Ce membre a <strong class="font-medium">bloqué le bot</strong> — les messages ne peuvent plus lui être envoyés.
      </div>

      <!-- Bannière IA -->
      <div id="ia-banner"
           class="hidden flex-shrink-0 items-center gap-2
                  px-4 py-2 text-xs text-teal-400
                  bg-teal-400/[0.05] border-b border-white/[0.06]">
        <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <circle cx="12" cy="12" r="3"/>
          <path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/>
        </svg>
        <span>L'Agent IA gère cette conversation.</span>
        <button onclick="App.toggleIA(document.getElementById('ia-toggle'))"
                class="ml-auto text-[10px] underline text-teal-300 cursor-pointer bg-transparent border-none">
          Reprendre manuellement
        </button>
      </div>

      <!-- Fil messages -->
      <div id="messages-feed"
           class="flex-1 overflow-y-auto px-5 py-4 flex flex-col gap-0">
        <div class="flex items-center justify-center gap-1.5 m-auto py-10">
          <span class="dot1 w-1.5 h-1.5 rounded-full bg-zinc-700 inline-block"></span>
          <span class="dot2 w-1.5 h-1.5 rounded-full bg-zinc-700 inline-block"></span>
          <span class="dot3 w-1.5 h-1.5 rounded-full bg-zinc-700 inline-block"></span>
        </div>
      </div>

      <!-- Zone de saisie -->
      <div id="compose-area"
           class="hidden flex-shrink-0
                  border-t border-white/[0.06]
                  bg-[#0f0f11] px-4 py-3">

        <!-- Reply preview -->
        <div id="reply-preview" class="hidden items-center gap-2 mb-2">
          <div class="reply-quote flex-1 overflow-hidden text-ellipsis whitespace-nowrap
                      px-2 py-1 text-[11px] text-zinc-500" id="reply-text">—</div>
          <button onclick="App.clearReply()"
                  class="flex items-center justify-center w-5 h-5 flex-shrink-0
                         rounded-md bg-white/[0.04] border border-white/[0.06]
                         text-zinc-500 hover:text-zinc-200 transition-all cursor-pointer">
            <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
              <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
            </svg>
          </button>
        </div>

        <!-- Upload preview -->
        <div id="upload-preview" class="hidden mb-2"></div>

        <div class="flex items-end gap-2">
          <button onclick="App.triggerUpload()"
                  class="flex items-center justify-center w-7 h-7 mb-0.5 flex-shrink-0
                         rounded-lg bg-white/[0.04] border border-white/[0.06]
                         text-zinc-500 hover:text-zinc-200 hover:bg-white/[0.08]
                         transition-all cursor-pointer">
            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
              <path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/>
            </svg>
          </button>
          <input type="file" id="file-input" class="hidden">
          <textarea id="compose-input"
                    placeholder="Sélectionner une conversation…"
                    rows="1"
                    onkeydown="App.handleKey(event)"
                    oninput="App.autoResize(this)"
                    class="flex-1 bg-white/[0.04] border border-white/[0.06] rounded-xl
                           px-3 py-2 text-[13px] text-zinc-200 placeholder-zinc-700
                           font-sans transition-all"></textarea>
          <button onclick="App.sendMessage()"
                  class="flex items-center gap-1.5 px-3 py-1.5 mb-0.5 flex-shrink-0
                         bg-amber-400/[0.12] border border-amber-400/[0.28]
                         rounded-lg text-amber-400 text-xs font-medium
                         hover:bg-amber-400/20 hover:border-amber-400/50
                         transition-all cursor-pointer">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="m22 2-7 20-4-9-9-4 20-7z"/>
            </svg>
            <span class="hidden sm:inline">Envoyer</span>
          </button>
        </div>

        <div class="flex items-center justify-between mt-1.5">
          <span class="text-[10px] text-zinc-800">Enter · Shift+Enter saut de ligne</span>
          <span class="text-[10px] text-zinc-800" id="compose-count">0 / 4096</span>
        </div>
      </div>
    </div><!-- /messages-col -->

    <!-- COL 3 — Profil (drawer tablet / bottom sheet mobile) -->
    <div id="profile-col"
         class="fixed md:static top-0 right-0 h-full
                w-[min(320px,90vw)] md:w-[260px]
                bg-[#0f0f11] border-l border-white/[0.06]
                flex-shrink-0 overflow-y-auto
                translate-x-full md:translate-x-0
                transition-transform duration-[250ms] ease-in-out
                z-[90]">
      <div class="flex justify-center pt-2.5 pb-1">
        <div class="w-8 h-1 rounded-full bg-white/10"></div>
      </div>
      <div class="text-center text-[12px] text-zinc-700 py-10 px-4">
        Sélectionnez une conversation
      </div>
    </div>

    <!-- Overlay profil -->
    <div id="profile-overlay"
         onclick="App.closeProfilePanel()"
         class="fixed inset-0 bg-black/40 z-[89] hidden md:hidden"></div>

  </div><!-- /chat-root -->
</div><!-- /zone principale -->


<!-- ═══════════════════════════════════════════════
     MODALS
     ═══════════════════════════════════════════════ -->

<!-- Modal Actions -->
<div id="modal-actions"
     onclick="if(event.target===this)closeModal('modal-actions')"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[200]
            hidden items-center justify-center p-4">
  <div class="bg-[#141416] border border-white/[0.06] rounded-xl w-[min(320px,calc(100vw-32px))] flex flex-col overflow-hidden">
    <div class="flex items-center justify-between px-5 py-3.5 border-b border-white/[0.06] flex-shrink-0">
      <p class="text-[13px] font-medium text-white">Actions</p>
      <button onclick="closeModal('modal-actions')"
              class="flex items-center justify-center w-7 h-7 rounded-lg
                     bg-white/[0.04] border border-white/[0.06]
                     text-zinc-500 hover:text-zinc-200 hover:bg-white/[0.08]
                     transition-all cursor-pointer">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div class="p-2.5 flex flex-col gap-1">
      <button onclick="closeModal('modal-actions')"
              class="flex items-center gap-2 w-full px-3 py-2 rounded-lg text-[12px] text-zinc-400
                     bg-white/[0.04] border border-white/[0.06] hover:text-zinc-200 hover:bg-white/[0.08] transition-all cursor-pointer">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
        Envoyer un broadcast ciblé
      </button>
      <button onclick="closeModal('modal-actions')"
              class="flex items-center gap-2 w-full px-3 py-2 rounded-lg text-[12px] text-zinc-400
                     bg-white/[0.04] border border-white/[0.06] hover:text-zinc-200 hover:bg-white/[0.08] transition-all cursor-pointer">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg>
        Ajouter à une catégorie
      </button>
      <button onclick="App.exportConv('json');closeModal('modal-actions')"
              class="flex items-center gap-2 w-full px-3 py-2 rounded-lg text-[12px] text-zinc-400
                     bg-white/[0.04] border border-white/[0.06] hover:text-zinc-200 hover:bg-white/[0.08] transition-all cursor-pointer">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/></svg>
        Exporter la conversation
      </button>
      <hr class="border-white/[0.06] my-1">
      <button onclick="closeModal('modal-actions')"
              class="flex items-center gap-2 w-full px-3 py-2 rounded-lg text-[12px] text-red-400
                     bg-white/[0.04] border border-white/[0.06] hover:bg-red-400/10 transition-all cursor-pointer">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        Signaler comme bloqué
      </button>
    </div>
  </div>
</div>

<!-- Modal Nouvelle conversation -->
<div id="modal-new-conv"
     onclick="if(event.target===this)closeModal('modal-new-conv')"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[200]
            hidden items-center justify-center p-4">
  <div class="bg-[#141416] border border-white/[0.06] rounded-xl w-[min(480px,calc(100vw-32px))] max-h-[90dvh] flex flex-col overflow-hidden">
    <div class="flex items-center justify-between px-5 py-3.5 border-b border-white/[0.06] flex-shrink-0">
      <p class="text-[13px] font-medium text-white">Nouvelle conversation</p>
      <button onclick="closeModal('modal-new-conv')"
              class="flex items-center justify-center w-7 h-7 rounded-lg
                     bg-white/[0.04] border border-white/[0.06]
                     text-zinc-500 hover:text-zinc-200 hover:bg-white/[0.08]
                     transition-all cursor-pointer">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div class="p-5 flex flex-col gap-3">
      <div>
        <p class="text-xs text-zinc-500 mb-2">Rechercher un membre</p>
        <input type="text" placeholder="Nom, @handle ou ID Telegram…"
               class="inp w-full bg-white/[0.04] border border-white/[0.06] rounded-lg
                      px-3 py-2 text-xs text-zinc-200 placeholder-zinc-700 font-sans transition-all">
      </div>
      <p class="text-[10px] text-zinc-700">Les membres apparaîtront ici lors de la connexion à l'API.</p>
    </div>
    <div class="flex items-center justify-end gap-2 px-5 py-3 border-t border-white/[0.06] flex-shrink-0">
      <button onclick="closeModal('modal-new-conv')"
              class="px-3 py-1.5 rounded-lg text-xs text-zinc-400
                     bg-white/[0.04] border border-white/[0.06]
                     hover:text-zinc-200 hover:bg-white/[0.08] transition-all cursor-pointer">Annuler</button>
      <button onclick="closeModal('modal-new-conv')"
              class="px-3 py-1.5 rounded-lg text-xs font-medium text-amber-400
                     bg-amber-400/[0.12] border border-amber-400/[0.28]
                     hover:bg-amber-400/20 transition-all cursor-pointer">Ouvrir →</button>
    </div>
  </div>
</div>

<!-- Modal Abonnement -->
<div id="modal-subscription"
     onclick="if(event.target===this)closeModal('modal-subscription')"
     class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[200]
            hidden items-center justify-center p-4">
  <div class="bg-[#141416] border border-white/[0.06] rounded-xl w-[min(480px,calc(100vw-32px))] max-h-[90dvh] flex flex-col overflow-hidden">
    <div class="flex items-center justify-between px-5 py-3.5 border-b border-white/[0.06] flex-shrink-0">
      <div>
        <p class="text-[13px] font-medium text-white">Gérer l'abonnement</p>
        <p class="text-[11px] text-zinc-500 mt-0.5" id="sub-modal-name">—</p>
      </div>
      <button onclick="closeModal('modal-subscription')"
              class="flex items-center justify-center w-7 h-7 rounded-lg
                     bg-white/[0.04] border border-white/[0.06]
                     text-zinc-500 hover:text-zinc-200 hover:bg-white/[0.08]
                     transition-all cursor-pointer">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
          <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
        </svg>
      </button>
    </div>
    <div class="p-5 flex flex-col gap-3.5 overflow-y-auto">
      <div>
        <p class="text-xs text-zinc-500 mb-2">Plan</p>
        <select class="inp w-full bg-white/[0.04] border border-white/[0.06] rounded-lg
                       px-3 py-2 text-xs text-zinc-200 font-sans cursor-pointer">
          <option value="mensuel">Mensuel — 30 jours</option>
          <option value="trimestriel">Trimestriel — 90 jours</option>
          <option value="semestriel">Semestriel — 180 jours</option>
          <option value="annuel">Annuel — 270 jours</option>
        </select>
      </div>
      <div>
        <p class="text-xs text-zinc-500 mb-2">Note interne (optionnel)</p>
        <textarea placeholder="Ex : offre spéciale avril…"
                  class="inp w-full bg-white/[0.04] border border-white/[0.06] rounded-lg
                         px-3 py-2 text-xs text-zinc-200 placeholder-zinc-700 font-sans
                         min-h-[60px] resize-none"></textarea>
      </div>
      <div class="bg-teal-400/[0.05] border border-teal-400/[0.12] rounded-lg px-3 py-2.5 text-[11px] text-teal-400">
        Les durées s'additionnent — si un abonnement actif existe, le nouveau repart de sa date d'expiration.
      </div>
    </div>
    <div class="flex items-center justify-end gap-2 px-5 py-3 border-t border-white/[0.06] flex-shrink-0">
      <button onclick="closeModal('modal-subscription')"
              class="px-3 py-1.5 rounded-lg text-xs text-zinc-400
                     bg-white/[0.04] border border-white/[0.06]
                     hover:text-zinc-200 hover:bg-white/[0.08] transition-all cursor-pointer">Annuler</button>
      <button onclick="App.createSubscription()"
              class="px-3 py-1.5 rounded-lg text-xs font-medium text-amber-400
                     bg-amber-400/[0.12] border border-amber-400/[0.28]
                     hover:bg-amber-400/20 transition-all cursor-pointer">Créer →</button>
    </div>
  </div>
</div>


<!-- ═══════════════════════════════════════════════
     SCRIPTS
     ═══════════════════════════════════════════════ -->
<script>
/* ─── Sidebar ─────────────────────────────────────────────── */
function openSidebar() {
  document.getElementById('sidebar').classList.add('translate-x-0')
  document.getElementById('sidebar').classList.remove('-translate-x-full')
  document.getElementById('sb-overlay').classList.remove('hidden')
  document.body.style.overflow = 'hidden'
}

function closeSidebar() {
  document.getElementById('sidebar').classList.remove('translate-x-0')
  document.getElementById('sidebar').classList.add('-translate-x-full')
  document.getElementById('sb-overlay').classList.add('hidden')
  document.body.style.overflow = ''
}

/* ─── Modals ──────────────────────────────────────────────── */
function openModal(id) {
  const el = document.getElementById(id)
  if (!el) return
  el.classList.remove('hidden')
  el.classList.add('flex')
}

function closeModal(id) {
  const el = document.getElementById(id)
  if (!el) return
  el.classList.add('hidden')
  el.classList.remove('flex')
}

document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    // Fermer modals ouvertes
    document.querySelectorAll('[id^="modal-"]').forEach(m => {
      if (!m.classList.contains('hidden')) closeModal(m.id)
    })
    // Fermer sidebar sur mobile
    if (window.innerWidth < 768) closeSidebar()
  }
})

/* ─── Tabs styling ────────────────────────────────────────── */
document.querySelectorAll('.tab').forEach(tab => {
  tab.addEventListener('click', function() {
    this.closest('div').querySelectorAll('.tab').forEach(t => {
      t.classList.remove('bg-white/[0.08]', 'text-zinc-200')
      t.classList.add('text-zinc-400')
    })
    this.classList.add('bg-white/[0.08]', 'text-zinc-200')
    this.classList.remove('text-zinc-400')
  })
})

/* ─── Resize handler ──────────────────────────────────────── */
window.addEventListener('resize', () => {
  if (window.innerWidth >= 768) {
    // Sur desktop : sidebar toujours visible, pas de translate
    const sb = document.getElementById('sidebar')
    sb.classList.remove('-translate-x-full', 'translate-x-0')
    document.getElementById('sb-overlay').classList.add('hidden')
    document.body.style.overflow = ''
  }
})
</script>

<script type="module" src="../js/chats.js" defer></script>

</body>
</html>