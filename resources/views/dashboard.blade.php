<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TradingBot — Dashboard</title>
<script src="https://cdn.tailwindcss.com"></script>

<link rel="stylesheet" href="../css/dashboard.css">
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600" rel="stylesheet">

</head>
<body class="text-zinc-200" style="height:100vh; overflow:hidden;">

@include('components.sidebar_mobile')  

<div style="display:flex; height:100vh; overflow:hidden;">
    
  @include('components.sidebar')  
  

  <!-- ─── MAIN ─────────────────────────────────────────────────────── -->
  <div style="flex:1; display:flex; flex-direction:column; min-width:0; overflow:hidden;">

    @include('components.topbar') 

    <!-- Page content -->
    <main style="flex:1; overflow-y:auto; padding:16px; display:flex; flex-direction:column; gap:14px;">

      <!-- ── MÉTRIQUES ── -->
      <div class="metrics-grid">
        <div class="card">
          <p class="text-xs mb-3" style="color:#52525b;">Total membres</p>
          <p class="text-[28px] font-light text-white tabular-nums leading-none">3 247</p>
          <p class="text-xs mt-2" style="color:#34d399;">↑ +48 cette semaine</p>
        </div>
        <div class="card">
          <p class="text-xs mb-3" style="color:#52525b;">Actifs (7 jours)</p>
          <p class="text-[28px] font-light text-white tabular-nums leading-none">1 892</p>
          <p class="text-xs mt-2" style="color:#34d399;">58% du total</p>
        </div>
        <div class="card">
          <p class="text-xs mb-3" style="color:#52525b;">Abonnements actifs</p>
          <p class="text-[28px] font-light text-white tabular-nums leading-none">847</p>
          <p class="text-xs mt-2" style="color:#f87171;">↓ 12 expirations proches</p>
        </div>
        <div class="card">
          <p class="text-xs mb-3" style="color:#52525b;">Trades journalisés</p>
          <p class="text-[28px] font-light text-white tabular-nums leading-none">124</p>
          <p class="text-xs mt-2" style="color:#34d399;">↑ +31 aujourd'hui</p>
        </div>
      </div>

      <!-- ── LIGNE 2 ── -->
      <div class="row2-grid">

        <!-- Segments -->
        <div class="card">
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <p class="text-sm font-medium text-white">Segments</p>
            <span class="text-xs cursor-pointer transition-colors" style="color:#52525b;"
                  onmouseover="this.style.color='#38bdf8'" onmouseout="this.style.color='#52525b'">
              Voir tout →
            </span>
          </div>
          <div style="display:flex; flex-direction:column; gap:12px;">
            <div>
              <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                <span class="text-xs" style="color:#a1a1aa;">Clients actifs</span>
                <span class="text-xs tabular-nums" style="color:#52525b;">847</span>
              </div>
              <div class="pbar"><div class="pbar-fill" style="width:72%;background:#34d399;"></div></div>
            </div>
            <div>
              <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                <span class="text-xs" style="color:#a1a1aa;">Prospects</span>
                <span class="text-xs tabular-nums" style="color:#52525b;">643</span>
              </div>
              <div class="pbar"><div class="pbar-fill" style="width:55%;background:#38bdf8;"></div></div>
            </div>
            <div>
              <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                <span class="text-xs" style="color:#a1a1aa;">Inactifs</span>
                <span class="text-xs tabular-nums" style="color:#52525b;">441</span>
              </div>
              <div class="pbar"><div class="pbar-fill" style="width:38%;background:#fbbf24;"></div></div>
            </div>
            <div>
              <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                <span class="text-xs" style="color:#a1a1aa;">Bloqués</span>
                <span class="text-xs tabular-nums" style="color:#52525b;">178</span>
              </div>
              <div class="pbar"><div class="pbar-fill" style="width:15%;background:#f87171;"></div></div>
            </div>
            <div>
              <div style="display:flex; justify-content:space-between; margin-bottom:6px;">
                <span class="text-xs" style="color:#a1a1aa;">Nouveaux (7j)</span>
                <span class="text-xs tabular-nums" style="color:#52525b;">324</span>
              </div>
              <div class="pbar"><div class="pbar-fill" style="width:28%;background:#71717a;"></div></div>
            </div>
          </div>
        </div>

        <!-- Alertes -->
        <div class="card">
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <p class="text-sm font-medium text-white">Alertes</p>
            <span class="badge badge-red">3 actives</span>
          </div>
          <div style="display:flex; flex-direction:column; gap:10px;">
            <div class="alert-row">
              <span style="width:7px;height:7px;border-radius:50%;background:#f87171;flex-shrink:0;"></span>
              <div style="flex:1; min-width:0;">
                <p class="text-xs font-medium text-zinc-200">Expirations imminentes</p>
                <p class="text-[11px] mt-0.5" style="color:#52525b;">dans les 7 prochains jours</p>
              </div>
              <p class="text-xl font-light tabular-nums" style="color:#f87171;">23</p>
            </div>
            <div class="alert-row">
              <span style="width:7px;height:7px;border-radius:50%;background:#fbbf24;flex-shrink:0;"></span>
              <div style="flex:1; min-width:0;">
                <p class="text-xs font-medium text-zinc-200">Membres inactifs</p>
                <p class="text-[11px] mt-0.5" style="color:#52525b;">pas d'activité depuis 21j</p>
              </div>
              <p class="text-xl font-light tabular-nums" style="color:#fbbf24;">67</p>
            </div>
            <div class="alert-row">
              <span style="width:7px;height:7px;border-radius:50%;background:#2dd4bf;flex-shrink:0;"></span>
              <div style="flex:1; min-width:0;">
                <p class="text-xs font-medium text-zinc-200">Escalades IA</p>
                <p class="text-[11px] mt-0.5" style="color:#52525b;">conversations à reprendre</p>
              </div>
              <p class="text-xl font-light tabular-nums" style="color:#2dd4bf;">4</p>
            </div>
          </div>
        </div>

        <!-- Agent IA -->
        <div class="card">
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <p class="text-sm font-medium text-white">Agent IA</p>
            <span style="display:flex; align-items:center; gap:6px; font-size:12px; color:#34d399;">
              <span class="pulse" style="width:6px;height:6px;border-radius:50%;background:#34d399;display:block;"></span>
              Actif
            </span>
          </div>
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:16px;">
            <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);border-radius:9px;padding:12px;text-align:center;">
              <p class="text-xl font-light text-white tabular-nums">1 284</p>
              <p class="text-[10px] mt-1" style="color:#52525b;">Msgs traités</p>
            </div>
            <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);border-radius:9px;padding:12px;text-align:center;">
              <p class="text-xl font-light text-white tabular-nums">87%</p>
              <p class="text-[10px] mt-1" style="color:#52525b;">Résolution auto</p>
            </div>
          </div>
          <button style="width:100%; display:flex; align-items:center; justify-content:space-between; padding:10px 12px; font-size:12px; background:rgba(251,191,36,.07); border:1px solid rgba(251,191,36,.18); border-radius:8px; color:#fbbf24; cursor:pointer; font-family:'Geist',sans-serif; transition:background .15s;"
                  onmouseover="this.style.background='rgba(251,191,36,.12)'"
                  onmouseout="this.style.background='rgba(251,191,36,.07)'">
            <span>4 escalades en attente</span>
            <span>→</span>
          </button>
        </div>

      </div>

      <!-- ── LIGNE 3 ── -->
      <div class="row3-grid">

        <!-- Activité récente -->
        <div class="card">
          <p class="text-sm font-medium text-white" style="margin-bottom:16px;">Activité récente</p>

          <div class="t-row">
            <div class="av av-green">MR</div>
            <div style="flex:1; min-width:0;">
              <p class="text-xs text-zinc-300 truncate">Marc R. — Trade +4.2% journalisé</p>
              <p class="text-[11px] mt-0.5" style="color:#52525b;">il y a 12 min</p>
            </div>
            <span class="badge badge-green">Perf</span>
          </div>
          <div class="t-row">
            <div class="av av-teal" style="font-size:9px;">IA</div>
            <div style="flex:1; min-width:0;">
              <p class="text-xs text-zinc-300 truncate">Agent IA — réponse automatique à Sophie A.</p>
              <p class="text-[11px] mt-0.5" style="color:#52525b;">il y a 28 min</p>
            </div>
            <span class="badge badge-teal" style="font-size:10px;">Auto</span>
          </div>
          <div class="t-row">
            <div class="av av-sky">NM</div>
            <div style="flex:1; min-width:0;">
              <p class="text-xs text-zinc-300 truncate">Nicolas M. — formulaire onboarding complété</p>
              <p class="text-[11px] mt-0.5" style="color:#52525b;">il y a 1h</p>
            </div>
            <span class="badge badge-sky">Form</span>
          </div>
          <div class="t-row">
            <div class="av av-violet">LB</div>
            <div style="flex:1; min-width:0;">
              <p class="text-xs text-zinc-300 truncate">Lucie B. — témoignage vidéo soumis</p>
              <p class="text-[11px] mt-0.5" style="color:#52525b;">il y a 2h</p>
            </div>
            <span class="badge badge-violet">Testi</span>
          </div>
          <div class="t-row">
            <div class="av av-amber">TK</div>
            <div style="flex:1; min-width:0;">
              <p class="text-xs text-zinc-300 truncate">Thomas K. — abonnement expiré</p>
              <p class="text-[11px] mt-0.5" style="color:#52525b;">il y a 3h</p>
            </div>
            <span class="badge badge-red">Expiré</span>
          </div>
        </div>

        <!-- Expirations proches -->
        <div class="card">
          <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <p class="text-sm font-medium text-white">Expirations proches</p>
            <span class="text-xs cursor-pointer transition-colors" style="color:#52525b;"
                  onmouseover="this.style.color='#38bdf8'" onmouseout="this.style.color='#52525b'">
              Voir tout →
            </span>
          </div>

          <div class="t-row">
            <div class="av av-violet">LB</div>
            <div style="flex:1; min-width:0;">
              <p class="text-xs text-zinc-300">Lucie Bernard</p>
              <p class="text-[11px] mt-0.5" style="color:#52525b;">Premium</p>
            </div>
            <p class="text-xs tabular-nums" style="color:#fbbf24; margin-right:8px;">11j</p>
            <button class="btn-ghost" style="font-size:11px; padding:4px 10px;">Relancer</button>
          </div>
          <div class="t-row">
            <div class="av av-green">MR</div>
            <div style="flex:1; min-width:0;">
              <p class="text-xs text-zinc-300">Marc Renaud</p>
              <p class="text-[11px] mt-0.5" style="color:#52525b;">Annuel</p>
            </div>
            <p class="text-xs tabular-nums" style="color:#fbbf24; margin-right:8px;">5j</p>
            <button class="btn-ghost" style="font-size:11px; padding:4px 10px;">Relancer</button>
          </div>
          <div class="t-row">
            <div class="av av-amber">TK</div>
            <div style="flex:1; min-width:0;">
              <p class="text-xs text-zinc-300">Thomas Klein</p>
              <p class="text-[11px] mt-0.5" style="color:#52525b;">Mensuel</p>
            </div>
            <p class="text-xs tabular-nums font-medium" style="color:#f87171; margin-right:8px;">3j</p>
            <button class="btn-primary" style="font-size:11px; padding:5px 10px;">Relancer</button>
          </div>
          <div class="t-row">
            <div class="av av-sky">SA</div>
            <div style="flex:1; min-width:0;">
              <p class="text-xs text-zinc-300">Sophie Amar</p>
              <p class="text-[11px] mt-0.5" style="color:#52525b;">Premium</p>
            </div>
            <p class="text-xs tabular-nums font-medium" style="color:#f87171; margin-right:8px;">2j</p>
            <button class="btn-primary" style="font-size:11px; padding:5px 10px;">Relancer</button>
          </div>
          <div class="t-row">
            <div class="av av-default">PM</div>
            <div style="flex:1; min-width:0;">
              <p class="text-xs text-zinc-300">Pierre M.</p>
              <p class="text-[11px] mt-0.5" style="color:#52525b;">Mensuel</p>
            </div>
            <p class="text-xs tabular-nums font-medium" style="color:#f87171; margin-right:8px;">1j</p>
            <button class="btn-primary" style="font-size:11px; padding:5px 10px;">Relancer</button>
          </div>
        </div>

      </div>
    </main>
  </div>
</div>

<script src="../js/dashboard.js"></script>

</body>
</html>