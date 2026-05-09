<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TradingBot — Growth Hub</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400,500" rel="stylesheet">
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Geist','sans-serif'],mono:['Geist Mono','monospace']}}}}</script>
<link rel="stylesheet" href="../css/link.css">
</head>
<body class="h-screen overflow-hidden">
<div id="toast-container"></div>

<!-- Mobile sidebar overlay -->
<div id="sidebar-overlay" onclick="closeMobSidebar()"></div>

<div class="flex h-full">
<!-- SIDEBAR -->link
<aside id="sidebar" style="width:208px;flex-shrink:0;background:#0d0d0f;border-right:1px solid rgba(255,255,255,.05);" class="flex flex-col h-full">
  <div class="px-4 py-4" style="border-bottom:1px solid rgba(255,255,255,.05);">
    <div class="flex items-center gap-3">
      <div style="width:30px;height:30px;background:#0ea5e9;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg width="15" height="15" fill="white" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.94 8.19l-2.02 9.52c-.14.66-.54.82-1.09.51l-3-2.21-1.45 1.39c-.16.16-.3.3-.61.3l.22-3.1 5.6-5.06c.24-.22-.06-.34-.38-.12L7.03 14.5 4.06 13.6c-.65-.2-.66-.65.14-.96l11.65-4.5c.54-.2 1.01.13.09 2.05z"/></svg></div>
      <div><p class="text-sm font-medium text-white leading-none">TradingBot</p><p class="text-xs mt-0.5" style="color:#3f3f46;" id="sb-total">3 247 membres</p></div>
    </div>
  </div>
  <nav class="flex-1 px-2 py-3 overflow-y-auto flex flex-col gap-0.5">
    <div class="nav-section">Vue d'ensemble</div>
    <button class="nav-item" onclick="closeMobSidebar()"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>Dashboard</button>
    <div class="nav-section" style="margin-top:6px;">Membres</div>
    <button class="nav-item" onclick="closeMobSidebar()"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>Utilisateurs</button>
    <button class="nav-item" onclick="closeMobSidebar()"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>Catégories</button>
    <button class="nav-item" onclick="closeMobSidebar()"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>Formulaires</button>
    <div class="nav-section" style="margin-top:6px;">Messagerie</div>
    <button class="nav-item" onclick="closeMobSidebar()"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>Messages ciblés</button>
    <button class="nav-item" onclick="closeMobSidebar()"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Chat direct</button>
    <div class="nav-section" style="margin-top:6px;">Trading</div>
    <button class="nav-item" onclick="closeMobSidebar()"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>Journal</button>
    <div class="nav-section" style="margin-top:6px;">Croissance</div>
    <button class="nav-item active" id="nav-links"        onclick="sv('links',this);closeMobSidebar()"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>Liens & Onboarding</button>
    <button class="nav-item" id="nav-automations"         onclick="sv('automations',this);closeMobSidebar()"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>Automations<span class="badge bdg-g ml-auto" style="font-size:9px;" id="nav-jobs-count">5</span></button>
    <button class="nav-item" id="nav-subscriptions"       onclick="sv('subscriptions',this);closeMobSidebar()"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>Abonnements</button>
    <button class="nav-item" id="nav-promos"              onclick="sv('promos',this);closeMobSidebar()"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>Promotions</button>
  </nav>
  <div class="px-4 py-3" style="border-top:1px solid rgba(255,255,255,.05);">
    <div class="flex items-center gap-2.5"><div style="width:27px;height:27px;border-radius:50%;background:linear-gradient(135deg,#0ea5e9,#6366f1);flex-shrink:0;"></div><div><p class="text-xs font-medium text-white">Admin</p><p class="text-[10px]" style="color:#3f3f46;">admin@tradingbot.io</p></div></div>
  </div>
</aside>

<!-- MAIN -->
<div class="flex-1 flex flex-col min-w-0 overflow-hidden">
<header style="height:52px;border-bottom:1px solid rgba(255,255,255,.05);backdrop-filter:blur(14px);background:rgba(9,9,11,.88);flex-shrink:0;" class="flex items-center justify-between px-3 md:px-5 gap-2">
  <div class="flex items-center gap-2 md:gap-3 min-w-0">
    <!-- Hamburger mobile -->
    <button id="mob-toggle" onclick="openMobSidebar()" aria-label="Menu">
      <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>
    <h1 class="text-sm font-medium text-white truncate" id="page-title">Liens & Onboarding</h1>
    <span class="hidden md:inline" style="color:#27272a;">·</span>
    <p class="hidden md:inline text-xs truncate" style="color:#52525b;" id="page-sub">Génération de liens trackés, séquences d'onboarding, activation IA</p>
  </div>
  <div class="flex items-center gap-1.5 md:gap-2 flex-shrink-0">
    <button class="btn-g" style="font-size:11px;padding:6px 8px md:6px 12px;" onclick="openM('m-broadcast')">
      <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
      <span class="hidden sm:inline">Broadcast</span>
    </button>
    <button class="btn-p" id="main-cta" onclick="openCTA()" style="padding:7px 10px;">
      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      <span id="main-cta-label" class="hidden sm:inline">Créer un lien</span>
    </button>
  </div>
</header>
<main class="flex-1 overflow-y-auto" style="padding:16px 12px;" id="main-content">

<!-- VUE LIENS -->
<div id="v-links" class="flex flex-col gap-4">
  <div class="stat-grid-4">
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Liens actifs</p><p class="text-xl font-light text-white" id="stat-links-active">0</p><p class="text-[10px] mt-1 pos" id="stat-links-sub">0 créés</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Inscriptions via lien</p><p class="text-xl font-light text-white tabular-nums" id="stat-links-reg">0</p><p class="text-[10px] mt-1" style="color:#38bdf8;" id="stat-links-conv">0% conv.</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Source #1</p><p class="text-sm font-medium text-white" id="stat-top-source">—</p><p class="text-[10px] mt-1 pos" id="stat-top-source-n">—</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Taux onboarding</p><p class="text-xl font-light" style="color:#38bdf8;" id="stat-onboarding-rate">68%</p><div class="pbar mt-2"><div class="pbar-f" style="width:68%;background:#38bdf8;"></div></div></div>
  </div>
  <div class="main-grid">
    <div class="card overflow-hidden">
      <div class="flex items-center justify-between px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,.05);">
        <p class="text-sm font-medium text-white">Liens d'invitation trackés</p>
        <button class="btn-p" style="font-size:11px;" onclick="openM('m-new-link')">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg><span class="hidden sm:inline">Nouveau lien</span><span class="sm:hidden">+</span>
        </button>
      </div>
      <div id="links-list" style="padding:10px;display:flex;flex-direction:column;gap:8px;max-height:500px;overflow-y:auto;">
        <div class="text-center py-8" style="color:#3f3f46;font-size:12px;">Aucun lien créé. Cliquez sur "Nouveau lien".</div>
      </div>
    </div>
    <div class="flex flex-col gap-4">
      <div class="card p-4">
        <div class="flex items-center justify-between mb-3"><p class="text-sm font-medium text-white">Déclencheur IA</p><span class="badge bdg-t" style="font-size:9px;">Configurable</span></div>
        <p class="text-[10px] mb-2" style="color:#52525b;">L'agent IA commence à discuter avec un nouveau membre :</p>
        <div class="flex flex-col gap-1.5" id="ia-trigger-options">
          <label class="flex items-center gap-2 cursor-pointer py-1.5 px-2 rounded-lg" style="border:1px solid transparent;transition:all .15s;" onmouseover="this.style.background='rgba(255,255,255,.03)'" onmouseout="this.style.background=''">
            <input type="radio" name="ia-trig" value="form" checked style="accent-color:#2dd4bf;">
            <span class="text-[11px]" style="color:#a1a1aa;">Après formulaire onboarding complété</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer py-1.5 px-2 rounded-lg" style="border:1px solid transparent;transition:all .15s;" onmouseover="this.style.background='rgba(255,255,255,.03)'" onmouseout="this.style.background=''">
            <input type="radio" name="ia-trig" value="immediate" style="accent-color:#2dd4bf;">
            <span class="text-[11px]" style="color:#a1a1aa;">Immédiatement après l'inscription</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer py-1.5 px-2 rounded-lg" style="border:1px solid transparent;" onmouseover="this.style.background='rgba(255,255,255,.03)'" onmouseout="this.style.background=''">
            <input type="radio" name="ia-trig" value="messages" style="accent-color:#2dd4bf;">
            <span class="text-[11px]" style="color:#a1a1aa;">Après <input type="number" value="5" min="1" max="50" style="width:36px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1);border-radius:4px;color:#e4e4e7;font-size:10px;text-align:center;padding:1px 4px;font-family:'Geist',sans-serif;"> messages</span>
          </label>
          <label class="flex items-center gap-2 cursor-pointer py-1.5 px-2 rounded-lg" onmouseover="this.style.background='rgba(255,255,255,.03)'" onmouseout="this.style.background=''">
            <input type="radio" name="ia-trig" value="trade" style="accent-color:#2dd4bf;">
            <span class="text-[11px]" style="color:#a1a1aa;">Premier trade journalisé</span>
          </label>
        </div>
        <button class="btn-p w-full justify-center mt-3" style="font-size:11px;" onclick="saveIATrigger()">Sauvegarder</button>
      </div>
      <div class="card p-4">
        <p class="text-xs font-medium text-white mb-3">Funnel d'acquisition</p>
        <div class="flex flex-col gap-3" id="funnel-display">
          <div><div class="flex justify-between mb-1"><span class="text-[11px] text-zinc-300">Clics liens</span><span class="text-[11px] tabular-nums text-zinc-300" id="f1">0</span></div><div class="funnel-bar"><div class="pbar-f" id="f1b" style="width:0%;background:#38bdf8;"></div></div></div>
          <div><div class="flex justify-between mb-1"><span class="text-[11px] text-zinc-300">Inscriptions bot</span><span class="text-[11px] tabular-nums text-zinc-300" id="f2">0</span></div><div class="funnel-bar"><div class="pbar-f" id="f2b" style="width:0%;background:#38bdf8;"></div></div></div>
          <div><div class="flex justify-between mb-1"><span class="text-[11px] text-zinc-300">Formulaire complété</span><span class="text-[11px] tabular-nums text-zinc-300" id="f3">0</span></div><div class="funnel-bar"><div class="pbar-f" id="f3b" style="width:0%;background:#a78bfa;"></div></div></div>
          <div><div class="flex justify-between mb-1"><span class="text-[11px] text-zinc-300">Abonnement payant</span><span class="text-[11px] tabular-nums pos" id="f4">0</span></div><div class="funnel-bar"><div class="pbar-f" id="f4b" style="width:0%;background:#34d399;"></div></div></div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- VUE AUTOMATIONS -->
<div id="v-automations" style="display:none;" class="flex flex-col gap-4">
  <div class="stat-grid-4">
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Jobs actifs</p><p class="text-xl font-light text-white" id="stat-jobs-active">0</p><div class="flex items-center gap-1.5 mt-1"><span class="pulse" style="width:5px;height:5px;border-radius:50%;background:#34d399;display:block;"></span><span class="text-[10px] pos" id="stat-jobs-today">0 planifiés</span></div></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Exécutions (7j)</p><p class="text-xl font-light text-white tabular-nums" id="stat-exec">0</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Prochain job</p><p class="text-sm font-medium text-white" id="stat-next-job">—</p><p class="text-[10px] mt-1" style="color:#38bdf8;" id="stat-next-time">—</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Taux de succès</p><p class="text-xl font-light pos" id="stat-success-rate">—</p></div>
  </div>
  <div class="main-grid">
    <div class="card overflow-hidden">
      <div class="flex items-center justify-between px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,.05);">
        <p class="text-sm font-medium text-white">Jobs configurés</p>
        <button class="btn-p" style="font-size:11px;" onclick="openM('m-new-job')">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg><span class="hidden sm:inline">Créer une automation</span><span class="sm:hidden">+</span>
        </button>
      </div>
      <div id="jobs-list" style="padding:10px;max-height:480px;overflow-y:auto;">
        <div class="text-center py-8" style="color:#3f3f46;font-size:12px;">Aucune automation. Créez votre premier job.</div>
      </div>
    </div>
    <div class="flex flex-col gap-3">
      <div class="card p-4">
        <p class="text-xs font-medium text-white mb-3">Prochaines exécutions</p>
        <div id="schedule-timeline" style="color:#3f3f46;font-size:12px;">—</div>
      </div>
      <div class="card p-4">
        <p class="text-xs font-medium text-white mb-3">Log récent</p>
        <div id="exec-log" style="color:#3f3f46;font-size:12px;">—</div>
      </div>
    </div>
  </div>
</div>

<!-- VUE ABONNEMENTS -->
<div id="v-subscriptions" style="display:none;" class="flex flex-col gap-4">
  <div class="stat-grid-5">
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">MRR</p><p class="text-xl font-light pos tabular-nums" id="stat-mrr">$0</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Abonnés actifs</p><p class="text-xl font-light text-white tabular-nums" id="stat-subs-active">0</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">En essai</p><p class="text-xl font-light tabular-nums" style="color:#38bdf8;" id="stat-trials">0</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Churn rate</p><p class="text-xl font-light tabular-nums" id="stat-churn">—</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Expirations (7j)</p><p class="text-xl font-light tabular-nums" style="color:#fbbf24;" id="stat-expiring">0</p></div>
  </div>
  <div class="main-grid">
    <div class="col-span-2 card overflow-hidden">
      <div class="flex items-center justify-between px-4 py-3" style="border-bottom:1px solid rgba(255,255,255,.05);">
        <p class="text-sm font-medium text-white">Membres abonnés</p>
        <div class="flex gap-2 flex-wrap">
          <select class="input" style="width:120px;font-size:11px;padding:4px 8px;" id="sub-filter-plan" onchange="renderSubscribers()">
            <option value="">Tous les plans</option>
          </select>
          <select class="input" style="width:120px;font-size:11px;padding:4px 8px;" id="sub-filter-status" onchange="renderSubscribers()">
            <option value="">Tous statuts</option>
            <option value="active">Actif</option>
            <option value="trial">Essai</option>
            <option value="expiring">Expire bientôt</option>
            <option value="expired">Expiré</option>
          </select>
        </div>
      </div>
      <div id="subs-list" style="max-height:420px;overflow-y:auto;">
        <div class="text-center py-8" style="color:#3f3f46;font-size:12px;">Aucun abonné. Créez d'abord un plan.</div>
      </div>
    </div>
    <div class="card p-4">
      <div class="flex items-center justify-between mb-4">
        <p class="text-sm font-medium text-white">Plans</p>
        <button class="btn-p" style="font-size:11px;" onclick="openM('m-new-plan')">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg><span class="hidden sm:inline">Nouveau plan</span><span class="sm:hidden">+</span>
        </button>
      </div>
      <div id="plans-list" style="display:flex;flex-direction:column;gap:10px;">
        <div class="text-center py-6" style="color:#3f3f46;font-size:12px;">Aucun plan. Créez votre premier plan.</div>
      </div>
    </div>
  </div>
</div>

<!-- VUE PROMOTIONS -->
<div id="v-promos" style="display:none;" class="flex flex-col gap-4">
  <div class="stat-grid-promo">
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Codes actifs</p><p class="text-xl font-light text-white" id="stat-promos-active">0</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Utilisations totales</p><p class="text-xl font-light text-white tabular-nums" id="stat-promo-uses">0</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Revenu généré (promos)</p><p class="text-xl font-light pos tabular-nums" id="stat-promo-rev">$0</p></div>
    <div class="stat-m"><p class="text-[10px] mb-1.5" style="color:#52525b;">Win-back rate</p><p class="text-xl font-light tabular-nums" style="color:#38bdf8;" id="stat-winback">—</p></div>
  </div>
  <div class="main-grid">
    <div>
      <div class="flex items-center justify-between mb-3">
        <p class="text-sm font-medium text-white">Codes promo</p>
        <button class="btn-p" style="font-size:11px;" onclick="openM('m-new-promo')">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg><span class="hidden sm:inline">Créer un code</span><span class="sm:hidden">+</span>
        </button>
      </div>
      <div id="promos-list" style="display:flex;flex-direction:column;gap:10px;">
        <div class="text-center py-8" style="color:#3f3f46;font-size:12px;">Aucun code promo. Créez votre premier code.</div>
      </div>
    </div>
    <div class="flex flex-col gap-3">
      <div class="card p-4">
        <p class="text-xs font-medium text-white mb-3">Offres automatiques</p>
        <div class="flex flex-col gap-2" id="auto-promos-config">
          <div style="padding:10px 12px;background:rgba(255,255,255,.025);border-radius:8px;border:1px solid rgba(255,255,255,.06);">
            <div class="flex items-center justify-between mb-1"><p class="text-xs text-zinc-300">Promo anniversaire (J+30)</p><button class="toggle" id="tog-anniversary" onclick="this.classList.toggle('on');saveAutoPromos()"></button></div>
            <div class="flex gap-2 mt-1"><input type="number" value="15" id="ap-anniversary-pct" class="input" style="width:60px;font-size:11px;padding:4px 7px;" oninput="saveAutoPromos()"><span class="text-[10px] self-center" style="color:#52525b;">% de réduction pendant 48h</span></div>
          </div>
          <div style="padding:10px 12px;background:rgba(255,255,255,.025);border-radius:8px;border:1px solid rgba(255,255,255,.06);">
            <div class="flex items-center justify-between mb-1"><p class="text-xs text-zinc-300">Win-back (expiré J+7)</p><button class="toggle" id="tog-winback" onclick="this.classList.toggle('on');saveAutoPromos()"></button></div>
            <div class="flex gap-2 mt-1"><input type="number" value="20" id="ap-winback-pct" class="input" style="width:60px;font-size:11px;padding:4px 7px;" oninput="saveAutoPromos()"><span class="text-[10px] self-center" style="color:#52525b;">% · Code unique par membre</span></div>
          </div>
          <div style="padding:10px 12px;background:rgba(255,255,255,.025);border-radius:8px;border:1px solid rgba(255,255,255,.06);">
            <div class="flex items-center justify-between mb-1"><p class="text-xs text-zinc-300">Upgrade Premium (3 mois actif)</p><button class="toggle" id="tog-upgrade" onclick="this.classList.toggle('on');saveAutoPromos()"></button></div>
            <div class="flex gap-2 mt-1"><input type="number" value="30" id="ap-upgrade-pct" class="input" style="width:60px;font-size:11px;padding:4px 7px;" oninput="saveAutoPromos()"><span class="text-[10px] self-center" style="color:#52525b;">% sur Premium</span></div>
          </div>
        </div>
      </div>
      <div class="card p-4">
        <p class="text-xs font-medium text-white mb-2">Tester un code</p>
        <input class="input mb-2" type="text" id="test-promo-input" placeholder="ex: TRADING20" style="font-size:12px;font-family:'Geist Mono',monospace;text-transform:uppercase;" oninput="this.value=this.value.toUpperCase()">
        <button class="btn-g w-full justify-center" style="font-size:11px;" onclick="testPromoCode()">Valider le code</button>
        <div id="test-promo-result" style="margin-top:8px;font-size:11px;"></div>
      </div>
    </div>
  </div>
</div>

</main>
</div>
</div>

<!-- DRAWERS + MODALS -->
<div class="dov" id="dov" onclick="closeAllDrw()"></div>

<!-- Drawer: détail lien -->
<div class="drawer" id="d-link" style="width:440px;">
  <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
    <div><p class="text-sm font-medium text-white" id="dl-name">—</p><p class="text-[11px] mt-0.5 mono" style="color:#38bdf8;" id="dl-param">—</p></div>
    <button class="btn-i" onclick="closeAllDrw()"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
  </div>
  <div class="flex-1 overflow-y-auto px-5 py-4 flex flex-col gap-4" id="dl-content"></div>
  <div class="flex gap-2 px-5 py-4" style="border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;">
    <button class="btn-g flex-1 justify-center" style="font-size:11px;" onclick="editCurrentLink()">Modifier</button>
    <button class="btn-danger flex-1 justify-center" style="font-size:11px;" onclick="deleteCurrentLink()">Supprimer</button>
  </div>
</div>

<!-- Drawer: détail job -->
<div class="drawer" id="d-job" style="width:440px;">
  <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
    <div><p class="text-sm font-medium text-white" id="dj-name">—</p><p class="text-[11px] mt-0.5" style="color:#52525b;" id="dj-type">—</p></div>
    <button class="btn-i" onclick="closeAllDrw()"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
  </div>
  <div class="flex-1 overflow-y-auto px-5 py-4 flex flex-col gap-4" id="dj-content"></div>
  <div class="flex gap-2 px-5 py-4" style="border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;">
    <button class="btn-g flex-1 justify-center" style="font-size:11px;" onclick="runJobNow()">▶ Exécuter maintenant</button>
    <button class="btn-danger flex-1 justify-center" style="font-size:11px;" onclick="deleteCurrentJob()">Supprimer</button>
  </div>
</div>

<!-- MODAL: NOUVEAU LIEN -->
<div class="overlay" id="m-new-link">
  <div class="modal" style="max-width:520px;">
    <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
      <div><p class="text-sm font-medium text-white" id="link-modal-title">Créer un lien d'invitation</p><p class="text-[11px] mt-0.5 hidden sm:block" style="color:#52525b;">Tracké avec UTM · Lié à une séquence et une catégorie</p></div>
      <button class="btn-i" onclick="closeM('m-new-link')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="px-5 py-4 flex flex-col gap-3 overflow-y-auto" style="max-height:65vh;">
      <input type="hidden" id="link-edit-id">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Nom du lien *</p><input class="input" type="text" id="link-name" placeholder="ex: Instagram Bio" style="font-size:12px;"></div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Paramètre start *</p><input class="input" type="text" id="link-param" placeholder="ex: ref_instagram" style="font-size:12px;font-family:'Geist Mono',monospace;" oninput="updateLinkPreview()"></div>
      </div>
      <div>
        <p class="text-[10px] mb-1.5" style="color:#52525b;">URL générée</p>
        <div style="padding:8px 12px;background:rgba(56,189,248,.06);border:1px solid rgba(56,189,248,.2);border-radius:8px;font-size:12px;font-family:'Geist Mono',monospace;color:#38bdf8;word-break:break-all;" id="link-preview-url">t.me/TradingBot?start=</div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Catégorie d'entrée auto</p>
          <select class="input" id="link-category" style="font-size:12px;">
            <option value="">Aucune catégorie</option>
          </select>
        </div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Code promo appliqué</p><input class="input" type="text" id="link-promo" placeholder="ex: TRADING20 (optionnel)" style="font-size:12px;font-family:'Geist Mono',monospace;text-transform:uppercase;" oninput="this.value=this.value.toUpperCase()"></div>
      </div>
      <div>
        <p class="text-[10px] mb-1.5" style="color:#52525b;">Formulaire lié (onboarding)</p>
        <select class="input" id="link-form" style="font-size:12px;">
          <option value="">Aucun formulaire</option>
        </select>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Quota max (optionnel)</p><input class="input" type="number" id="link-quota" placeholder="illimité" style="font-size:12px;"></div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Expiration (optionnel)</p><input class="input" type="date" id="link-expires" style="font-size:12px;"></div>
      </div>
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Source (UTM)</p>
        <select class="input" id="link-source" style="font-size:12px;">
          <option value="instagram">Instagram</option>
          <option value="youtube">YouTube</option>
          <option value="partenaire">Partenaire</option>
          <option value="webinaire">Webinaire</option>
          <option value="tiktok">TikTok</option>
          <option value="direct">Direct / Autre</option>
        </select>
      </div>
    </div>
    <div class="flex justify-end gap-2 px-5 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-g" onclick="closeM('m-new-link')">Annuler</button>
      <button class="btn-p" onclick="saveLink()">Créer le lien</button>
    </div>
  </div>
</div>

<!-- MODAL: NOUVEAU JOB -->
<div class="overlay" id="m-new-job">
  <div class="modal" style="max-width:560px;">
    <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
      <div><p class="text-sm font-medium text-white" id="job-modal-title">Créer une automation</p><p class="text-[11px] mt-0.5 hidden sm:block" style="color:#52525b;">Déclencheur + cible (catégorie) + action Telegram</p></div>
      <button class="btn-i" onclick="closeM('m-new-job')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="px-5 py-4 flex flex-col gap-4 overflow-y-auto" style="max-height:68vh;">
      <input type="hidden" id="job-edit-id">
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Nom du job *</p><input class="input" type="text" id="job-name" placeholder="ex: Relance expiration abonnement" style="font-size:12px;"></div>
      <div>
        <p class="text-[10px] mb-2" style="color:#52525b;">Type de déclencheur</p>
        <div class="flex gap-2 mb-3 flex-wrap">
          <button class="trigger-pill time" id="tp-time" onclick="selectTriggerType('time')">⏰ Temporel</button>
          <button class="trigger-pill inactive" id="tp-cond" onclick="selectTriggerType('cond')">⚡ Conditionnel</button>
          <button class="trigger-pill inactive" id="tp-event" onclick="selectTriggerType('event')">🎯 Événement</button>
        </div>
        <div id="tconf-time" style="padding:12px;background:rgba(56,189,248,.04);border:1px solid rgba(56,189,248,.15);border-radius:8px;">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Fréquence</p>
              <select class="input" id="job-freq" style="font-size:12px;">
                <option value="daily">Chaque jour</option>
                <option value="weekly_mon">Chaque lundi</option>
                <option value="weekly_fri">Chaque vendredi</option>
                <option value="every3d">Tous les 3 jours</option>
                <option value="monthly_1">Le 1er du mois</option>
                <option value="monthly_15">Le 15 du mois</option>
                <option value="once">Une seule fois</option>
              </select>
            </div>
            <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Heure</p><input class="input" type="time" id="job-time" value="09:00" style="font-size:12px;"></div>
          </div>
        </div>
        <div id="tconf-cond" style="display:none;padding:12px;background:rgba(167,139,250,.04);border:1px solid rgba(167,139,250,.15);border-radius:8px;">
          <div class="flex flex-col gap-2">
            <div class="flex items-center gap-2 flex-wrap">
              <span style="font-size:10px;color:#a78bfa;min-width:30px;font-weight:600;">SI</span>
              <select class="input flex-1" id="job-cond-field" style="font-size:11px;min-width:0;">
                <option value="sub_expires_in">Abonnement expire dans X jours</option>
                <option value="capital_down">Capital baisse de X%</option>
                <option value="inactive_days">Inactif depuis X jours</option>
                <option value="score_lt">Score engagement &lt; X</option>
                <option value="form_not_done">Formulaire non complété après X jours</option>
              </select>
              <input class="input" type="number" id="job-cond-val" placeholder="X" style="width:65px;font-size:11px;font-family:'Geist Mono',monospace;" value="7">
            </div>
            <div class="flex items-center gap-2 flex-wrap">
              <span style="font-size:10px;color:#a78bfa;min-width:30px;font-weight:600;">ET</span>
              <select class="input flex-1" id="job-cond-extra" style="font-size:11px;min-width:0;">
                <option value="">Aucune condition supplémentaire</option>
                <option value="not_renewed">N'a pas encore renouvelé</option>
                <option value="is_subscribed">Est abonné actif</option>
                <option value="has_paid">A déjà payé au moins une fois</option>
              </select>
            </div>
          </div>
        </div>
        <div id="tconf-event" style="display:none;padding:12px;background:rgba(52,211,153,.04);border:1px solid rgba(52,211,153,.15);border-radius:8px;">
          <select class="input" id="job-event-type" style="font-size:12px;">
            <option value="new_member">Nouvel inscrit via /start</option>
            <option value="form_completed">Formulaire onboarding complété</option>
            <option value="payment_received">Paiement reçu</option>
            <option value="sub_expired">Abonnement expiré</option>
            <option value="capital_declared">Capital déclaré</option>
          </select>
        </div>
      </div>
      <div>
        <p class="text-[10px] mb-1.5" style="color:#52525b;">Cible — Catégorie(s) de membres *</p>
        <select class="input" id="job-target" style="font-size:12px;">
          <option value="all">Tous les membres (3 247)</option>
          <option value="admin">Admin uniquement</option>
        </select>
      </div>
      <div>
        <p class="text-[10px] mb-1.5" style="color:#52525b;">Action à exécuter *</p>
        <select class="input mb-2" id="job-action-type" style="font-size:12px;" onchange="toggleJobActionDetails()">
          <option value="send_message">Envoyer un message Telegram</option>
          <option value="send_form">Envoyer un formulaire</option>
          <option value="send_ia_bilan">Générer & envoyer bilan IA</option>
          <option value="add_to_category">Ajouter à une catégorie</option>
          <option value="remove_from_category">Retirer d'une catégorie</option>
          <option value="notify_admin">Notifier l'admin</option>
          <option value="webhook">Appeler un webhook</option>
        </select>
        <div id="job-action-msg">
          <textarea class="input" id="job-action-content" style="min-height:72px;font-size:12px;" placeholder="Contenu du message Telegram (Markdown supporté)&#10;Variables disponibles: {prenom}, {capital}, {expiration}"></textarea>
          <div class="tg mt-2">
            <div class="tg-top"><div style="width:18px;height:18px;border-radius:50%;background:#0ea5e9;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg width="9" height="9" fill="white" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.94 8.19l-2.02 9.52c-.14.66-.54.82-1.09.51l-3-2.21-1.45 1.39c-.16.16-.3.3-.61.3l.22-3.1 5.6-5.06c.24-.22-.06-.34-.38-.12L7.03 14.5 4.06 13.6c-.65-.2-.66-.65.14-.96l11.65-4.5c.54-.2 1.01.13.09 2.05z"/></svg></div><p style="font-size:9px;font-weight:600;color:#e2e8f0;margin-left:5px;">TradingBot</p></div>
            <div class="tg-body"><div class="tg-bbl" id="job-tg-preview" style="font-size:10px;">Aperçu du message...</div></div>
          </div>
        </div>
        <div id="job-action-form" style="display:none;">
          <select class="input" id="job-form-sel" style="font-size:12px;"><option value="">Choisir un formulaire...</option></select>
        </div>
        <div id="job-action-cat" style="display:none;">
          <select class="input" id="job-cat-sel" style="font-size:12px;"><option value="">Choisir une catégorie...</option></select>
        </div>
        <div id="job-action-webhook" style="display:none;">
          <input class="input" type="text" id="job-webhook-url" placeholder="https://webhook.example.com/endpoint" style="font-size:12px;font-family:'Geist Mono',monospace;">
        </div>
      </div>
    </div>
    <div class="flex justify-end gap-2 px-5 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-g" onclick="closeM('m-new-job')">Annuler</button>
      <button class="btn-p" onclick="saveJob()">Sauvegarder l'automation</button>
    </div>
  </div>
</div>

<!-- MODAL: NOUVEAU PLAN -->
<div class="overlay" id="m-new-plan">
  <div class="modal" style="max-width:460px;">
    <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
      <p class="text-sm font-medium text-white" id="plan-modal-title">Créer un plan d'abonnement</p>
      <button class="btn-i" onclick="closeM('m-new-plan')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="px-5 py-4 flex flex-col gap-3">
      <input type="hidden" id="plan-edit-id">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Nom du plan *</p><input class="input" type="text" id="plan-name" placeholder="ex: Premium" style="font-size:12px;"></div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Prix ($) *</p><input class="input" type="number" id="plan-price" placeholder="79" style="font-size:12px;"></div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Durée</p>
          <select class="input" id="plan-duration" style="font-size:12px;">
            <option value="30">Mensuel (30j)</option>
            <option value="90">Trimestriel (90j)</option>
            <option value="365">Annuel (365j)</option>
            <option value="0">À vie</option>
          </select>
        </div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Essai gratuit (jours)</p><input class="input" type="number" id="plan-trial" placeholder="7" style="font-size:12px;" value="0"></div>
      </div>
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Catégories d'accès liées</p>
        <select class="input" id="plan-categories" style="font-size:12px;" multiple>
          <option value="">Chargement...</option>
        </select>
      </div>
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Description</p><textarea class="input" id="plan-desc" style="min-height:52px;font-size:11px;" placeholder="Signaux quotidiens · Chat IA · Journal de trading..."></textarea></div>
    </div>
    <div class="flex justify-end gap-2 px-5 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-g" onclick="closeM('m-new-plan')">Annuler</button>
      <button class="btn-p" onclick="savePlan()">Créer le plan</button>
    </div>
  </div>
</div>

<!-- MODAL: NOUVEAU CODE PROMO -->
<div class="overlay" id="m-new-promo">
  <div class="modal" style="max-width:460px;">
    <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
      <p class="text-sm font-medium text-white" id="promo-modal-title">Créer un code promo</p>
      <button class="btn-i" onclick="closeM('m-new-promo')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="px-5 py-4 flex flex-col gap-3">
      <input type="hidden" id="promo-edit-id">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Code *</p><input class="input" type="text" id="promo-code" placeholder="ex: TRADING20" style="font-size:12px;font-family:'Geist Mono',monospace;text-transform:uppercase;" oninput="this.value=this.value.toUpperCase()"></div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Réduction</p><div class="flex gap-1.5"><input class="input" type="number" id="promo-value" placeholder="20" style="font-size:12px;flex:1;"><select class="input" id="promo-type" style="width:55px;font-size:12px;"><option value="percent">%</option><option value="fixed">$</option></select></div></div>
      </div>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Plan applicable</p>
          <select class="input" id="promo-plan" style="font-size:12px;"><option value="">Tous les plans</option></select>
        </div>
        <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Quota max</p><input class="input" type="number" id="promo-quota" placeholder="illimité" style="font-size:12px;"></div>
      </div>
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Expiration</p><input class="input" type="date" id="promo-expires" style="font-size:12px;"></div>
      <div class="flex flex-col gap-1.5">
        <label class="flex items-center gap-2 text-xs text-zinc-400 cursor-pointer"><input type="checkbox" id="promo-first-only" checked style="accent-color:#38bdf8;"> Première souscription uniquement</label>
        <label class="flex items-center gap-2 text-xs text-zinc-400 cursor-pointer"><input type="checkbox" id="promo-non-comb" style="accent-color:#38bdf8;"> Non combinable avec d'autres offres</label>
      </div>
    </div>
    <div class="flex justify-end gap-2 px-5 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-g" onclick="closeM('m-new-promo')">Annuler</button>
      <button class="btn-p" onclick="savePromo()">Créer le code</button>
    </div>
  </div>
</div>

<!-- MODAL: BROADCAST RAPIDE -->
<div class="overlay" id="m-broadcast">
  <div class="modal" style="max-width:480px;">
    <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
      <p class="text-sm font-medium text-white">Broadcast rapide</p>
      <button class="btn-i" onclick="closeM('m-broadcast')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="px-5 py-4 flex flex-col gap-3">
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Destinataires *</p>
        <select class="input" id="bc-target" style="font-size:12px;" onchange="updateBcCount()">
          <option value="all">Tous les membres (3 247)</option>
          <option value="admin">Admin uniquement (test)</option>
        </select>
        <p class="text-[10px] mt-1" style="color:#38bdf8;" id="bc-count-label">3 247 membres recevront ce message</p>
      </div>
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Message *</p>
        <textarea class="input" id="bc-message" style="min-height:80px;font-size:12px;" placeholder="Votre message Telegram..." oninput="updateBcPreview()"></textarea>
      </div>
      <div class="tg">
        <div class="tg-top"><div style="width:18px;height:18px;border-radius:50%;background:#0ea5e9;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><svg width="9" height="9" fill="white" viewBox="0 0 24 24"><path d="M12 0C5.37 0 0 5.37 0 12s5.37 12 12 12 12-5.37 12-12S18.63 0 12 0zm5.94 8.19l-2.02 9.52c-.14.66-.54.82-1.09.51l-3-2.21-1.45 1.39c-.16.16-.3.3-.61.3l.22-3.1 5.6-5.06c.24-.22-.06-.34-.38-.12L7.03 14.5 4.06 13.6c-.65-.2-.66-.65.14-.96l11.65-4.5c.54-.2 1.01.13.09 2.05z"/></svg></div><p style="font-size:9px;font-weight:600;color:#e2e8f0;margin-left:5px;">TradingBot</p></div>
        <div class="tg-body"><div class="tg-bbl" id="bc-preview" style="font-size:10px;">Votre message apparaîtra ici...</div></div>
      </div>
    </div>
    <div class="flex justify-end gap-2 px-5 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-g" onclick="closeM('m-broadcast')">Annuler</button>
      <button class="btn-p" onclick="sendBroadcast()">Envoyer →</button>
    </div>
  </div>
</div>

<!-- MODAL: ABONNER UN MEMBRE -->
<div class="overlay" id="m-add-sub">
  <div class="modal" style="max-width:420px;">
    <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
      <p class="text-sm font-medium text-white">Ajouter un abonné (simulation)</p>
      <button class="btn-i" onclick="closeM('m-add-sub')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div class="px-5 py-4 flex flex-col gap-3">
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Nom du membre</p><input class="input" type="text" id="sub-member-name" placeholder="ex: Marc Renaud" style="font-size:12px;"></div>
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Plan *</p><select class="input" id="sub-plan-sel" style="font-size:12px;"><option value="">Choisir un plan...</option></select></div>
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Statut</p><select class="input" id="sub-status-sel" style="font-size:12px;"><option value="active">Actif</option><option value="trial">Essai</option><option value="expiring">Expire bientôt</option><option value="expired">Expiré</option></select></div>
      <div><p class="text-[10px] mb-1.5" style="color:#52525b;">Code promo (optionnel)</p><input class="input" type="text" id="sub-promo-code" placeholder="ex: TRADING20" style="font-size:12px;font-family:'Geist Mono',monospace;text-transform:uppercase;" oninput="this.value=this.value.toUpperCase()"></div>
    </div>
    <div class="flex justify-end gap-2 px-5 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
      <button class="btn-g" onclick="closeM('m-add-sub')">Annuler</button>
      <button class="btn-p" onclick="addSubscriber()">Ajouter</button>
    </div>
  </div>
</div>

<script>
// Mobile sidebar helpers
function openMobSidebar() {
  document.getElementById('sidebar').classList.add('mob-open');
  document.getElementById('sidebar-overlay').style.display = 'block';
}
function closeMobSidebar() {
  document.getElementById('sidebar').classList.remove('mob-open');
  document.getElementById('sidebar-overlay').style.display = 'none';
}
</script>
<script src="../js/link_automat.js"></script>
</body>
</html>