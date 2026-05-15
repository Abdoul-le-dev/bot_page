<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TradingBot — Chat direct</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400" rel="stylesheet">
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Geist','sans-serif'],mono:['Geist Mono','monospace']}}}}</script>
<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="../css/chat.css">
</head>
<body class="h-screen overflow-hidden text-zinc-200">

<div id="sidebar-overlay" onclick="closeSidebar()"></div>

<div style="display:flex;height:100vh;overflow:hidden;">

<!-- ─── SIDEBAR CHAT─── -->
<aside id="sidebar">
  
  <nav class="flex-1 px-2 py-3 overflow-y-auto flex flex-col gap-0.5">
    <div class="nav-section" style="margin-top:6px;">Messagerie</div>
    <button class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>Messages ciblés</button>
    <button class="nav-item active"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>Chat direct<span class="badge badge-sky ml-auto" id="nav-unread-badge" style="font-size:10px;">0</span></button>
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
      <h1 class="text-sm font-medium text-white" style="white-space:nowrap;">Chat direct</h1>
      <span style="color:#27272a;" class="hidden sm:inline">·</span>
      <span class="text-xs hidden sm:inline" style="color:#3f3f46;white-space:nowrap;">Timeline unifiée — messages · broadcasts</span>
    </div>
    <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
      <button class="btn-primary" onclick="openModal('modal-new-conv')">
        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
        <span class="topbar-label">Nouvelle conversation</span>
      </button>
    </div>
  </header>

  <!-- ══ CHAT LAYOUT 3 colonnes ══ -->
  <div id="chat-root">

    <!-- ── COL 1 : Liste conversations ── -->
    <div id="conv-col">
      <div style="padding:12px;border-bottom:1px solid rgba(255,255,255,.05);flex-shrink:0;">
        <div style="position:relative;margin-bottom:10px;">
          <svg width="12" height="12" fill="none" stroke="#3f3f46" viewBox="0 0 24 24" stroke-width="2" style="position:absolute;left:9px;top:50%;transform:translateY(-50%);"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          <input class="input" type="text" placeholder="Rechercher…" style="padding-left:28px;font-size:12px;background:rgba(255,255,255,.03);" oninput="App.filterConvs(this.value)">
        </div>
        <div style="display:flex;align-items:center;gap:2px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);border-radius:8px;padding:2px;overflow-x:auto;">
          <button class="tab active" onclick="App.switchConvTab(this,'all')">Tous</button>
          <button class="tab" onclick="App.switchConvTab(this,'requires_admin')">⚡ Admin<span id="tab-admin-count" style="margin-left:4px;font-size:10px;color:#fb923c;"></span></button>
          <button class="tab" onclick="App.switchConvTab(this,'unread')">Non lus<span id="tab-unread-count" style="margin-left:4px;font-size:10px;color:#38bdf8;"></span></button>
          <button class="tab" onclick="App.switchConvTab(this,'ia')">IA active</button>
          <button class="tab" onclick="App.switchConvTab(this,'blocked')">Bloqués</button>
        </div>
      </div>
      <div style="flex:1;overflow-y:auto;" id="conv-list">
        <!-- Chargé dynamiquement par chat_app.js -->
        <div style="padding:20px;text-align:center;color:#52525b;font-size:12px;">Chargement…</div>
      </div>
    </div>

    <!-- ── COL 2 : Fil de messages ── -->
    <div id="messages-col">

      <!-- Header -->
      <div id="chat-header" style="display:flex;align-items:center;justify-content:space-between;padding:12px 16px;border-bottom:1px solid rgba(255,255,255,.05);background:#0f0f11;flex-shrink:0;gap:10px;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:10px;min-width:0;">
          <button id="btn-back-conv" onclick="App.backToList()" aria-label="Retour">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="m15 18-6-6 6-6"/></svg>
          </button>
          <div class="av av-sky" id="chat-av" style="width:32px;height:32px;font-size:12px;">—</div>
          <div>
            <p style="font-size:13px;font-weight:500;color:white;" id="chat-name">Sélectionner une conversation</p>
            <p style="font-size:11px;color:#52525b;" id="chat-handle"></p>
          </div>
        </div>
        <div style="display:flex;align-items:center;gap:8px;flex-shrink:0;">
          <div style="display:flex;align-items:center;gap:8px;" class="hidden-xs">
            <span style="font-size:11px;color:#52525b;">Agent IA</span>
            <button class="toggle" id="ia-toggle" onclick="App.toggleIA(this)" title="Activer / désactiver l'IA"></button>
          </div>
          <div class="hidden-xs" style="width:1px;height:16px;background:rgba(255,255,255,.08);"></div>
          <button id="btn-show-profile" class="btn-icon" onclick="App.openProfilePanel()" title="Voir le profil">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </button>
          <button class="btn-icon" onclick="openModal('modal-actions')" title="Plus d'actions">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
          </button>
        </div>
      </div>

      <!-- Bannière bloqué -->
      <div id="blocked-banner" class="blocked-banner" style="display:none;">
        <svg width="13" height="13" fill="none" stroke="#f87171" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        <span>Ce membre a <strong>bloqué le bot</strong> — les messages ne peuvent plus lui être envoyés.</span>
      </div>

      <!-- Bannière IA active -->
      <div id="ia-banner" class="ia-banner" style="display:none;">
        <svg width="12" height="12" fill="none" stroke="#2dd4bf" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M4.22 4.22l2.12 2.12M17.66 17.66l2.12 2.12M2 12h3M19 12h3M4.22 19.78l2.12-2.12M17.66 6.34l2.12-2.12"/></svg>
        <span>L'Agent IA gère cette conversation — les réponses automatiques sont actives.</span>
        <button style="margin-left:auto;font-size:10px;text-decoration:underline;background:none;border:none;color:#5eead4;cursor:pointer;" onclick="App.toggleIA(document.getElementById('ia-toggle'))">Reprendre manuellement</button>
      </div>

      <!-- Fil messages -->
      <div style="flex:1;overflow-y:auto;padding:16px 20px;background:#0c0c0e;" id="messages-feed">
        <div style="padding:40px;text-align:center;color:#3f3f46;font-size:12px;">Sélectionnez une conversation</div>
      </div>

      <!-- Zone de saisie -->
      <div class="compose-area" id="compose-area" style="display:none;">

        <!-- Reply preview -->
        <div id="reply-preview" style="display:none;margin-bottom:8px;align-items:center;gap:8px;">
          <div class="reply-quote" id="reply-text" style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">—</div>
          <button class="btn-icon" style="width:20px;height:20px;flex-shrink:0;" onclick="App.clearReply()">
            <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Upload preview -->
        <div id="upload-preview" style="display:none;margin-bottom:8px;"></div>

        <div style="display:flex;align-items:flex-end;gap:8px;">
          <div style="display:flex;align-items:center;gap:4px;margin-bottom:2px;">
            <button class="btn-icon" title="Joindre un fichier" onclick="App.triggerUpload()">
              <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
            </button>
          </div>
          <!-- file-input : accept mis à jour dynamiquement par chat_app.js -->
          <input type="file" id="file-input" style="display:none;">
          <textarea class="compose-input" id="compose-input"
                    placeholder="Sélectionner une conversation…"
                    rows="1"
                    onkeydown="App.handleKey(event)"
                    oninput="App.autoResize(this)"></textarea>
          <button class="btn-primary" id="send-btn" style="flex-shrink:0;margin-bottom:2px;" onclick="App.sendMessage()">
            <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
            <span id="send-label">Envoyer</span>
          </button>
        </div>

        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:8px;" id="compose-footer">
          <span style="font-size:10px;color:#3f3f46;">Enter pour envoyer · Shift+Enter saut de ligne</span>
          <span style="font-size:10px;color:#3f3f46;" id="compose-count">0 / 4096</span>
        </div>
      </div>

    </div><!-- /messages-col -->

    <!-- ── COL 3 : Profil ── -->
    <div id="profile-col">
      <div style="display:flex;justify-content:center;padding:12px 0 4px;" class="profile-handle-mobile">
        <div style="width:36px;height:4px;border-radius:99px;background:rgba(255,255,255,.1);"></div>
      </div>
      <!-- Chargé dynamiquement par chat_app.js via renderProfile() -->
      <div style="padding:40px 16px;text-align:center;color:#3f3f46;font-size:12px;">
        Sélectionnez une conversation
      </div>
    </div>

    <!-- Overlay profil -->
    <div id="profile-overlay" onclick="App.closeProfilePanel()"></div>

  </div><!-- /chat-root -->
</div><!-- /main -->
</div><!-- /flex wrapper -->


<!-- ═══════════════════════════════════════════════════════════
     MODALS
     ═══════════════════════════════════════════════════════════ -->

<!-- Actions conversation -->
<div class="modal-overlay" id="modal-actions">
  <div class="modal" style="max-width:320px;">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <p style="font-size:13px;font-weight:500;color:white;">Actions</p>
      <button class="btn-icon" onclick="closeModal('modal-actions')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div style="padding:10px 12px;display:flex;flex-direction:column;gap:4px;">
      <button class="btn-ghost" style="font-size:12px;justify-content:flex-start;width:100%;" onclick="closeModal('modal-actions')">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
        Envoyer un broadcast ciblé
      </button>
      <button class="btn-ghost" style="font-size:12px;justify-content:flex-start;width:100%;" onclick="closeModal('modal-actions')">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"/></svg>
        Ajouter à une catégorie
      </button>
      <button class="btn-ghost" style="font-size:12px;justify-content:flex-start;width:100%;" onclick="App.exportConv('json');closeModal('modal-actions')">
        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/></svg>
        Exporter la conversation
      </button>
      <div style="height:1px;background:rgba(255,255,255,.05);margin:4px 0;"></div>
      <button class="btn-ghost" style="font-size:12px;justify-content:flex-start;width:100%;color:#f87171;" onclick="closeModal('modal-actions')">
        <svg width="13" height="13" fill="none" stroke="#f87171" viewBox="0 0 24 24" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
        Signaler comme bloqué
      </button>
    </div>
  </div>
</div>

<!-- Nouvelle conversation -->
<div class="modal-overlay" id="modal-new-conv">
  <div class="modal">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <p style="font-size:13px;font-weight:500;color:white;">Nouvelle conversation</p>
      <button class="btn-icon" onclick="closeModal('modal-new-conv')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div style="padding:18px 20px;display:flex;flex-direction:column;gap:14px;">
      <div>
        <p style="font-size:12px;color:#52525b;margin-bottom:8px;">Rechercher un membre</p>
        <input class="input" type="text" placeholder="Nom, @handle ou ID Telegram…">
      </div>
      <p style="font-size:10px;color:#3f3f46;">Les membres apparaîtront ici lors de la connexion à l'API.</p>
    </div>
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:12px 20px;border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <button class="btn-ghost" onclick="closeModal('modal-new-conv')">Annuler</button>
      <button class="btn-primary" onclick="closeModal('modal-new-conv')">Ouvrir →</button>
    </div>
  </div>
</div>

<!-- Abonnement -->
<div class="modal-overlay" id="modal-subscription">
  <div class="modal">
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;border-bottom:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <div>
        <p style="font-size:13px;font-weight:500;color:white;">Gérer l'abonnement</p>
        <p style="font-size:11px;color:#52525b;margin-top:2px;" id="sub-modal-name">—</p>
      </div>
      <button class="btn-icon" onclick="closeModal('modal-subscription')"><svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg></button>
    </div>
    <div style="padding:18px 20px;display:flex;flex-direction:column;gap:14px;overflow-y:auto;">
      <div>
        <p style="font-size:12px;color:#52525b;margin-bottom:8px;">Plan</p>
        <select class="input">
          <option value="mensuel">Mensuel — 30 jours</option>
          <option value="trimestriel">Trimestriel — 90 jours</option>
          <option value="semestriel">Semestriel — 180 jours</option>
          <option value="annuel">Annuel — 270 jours</option>
        </select>
      </div>
      <div>
        <p style="font-size:12px;color:#52525b;margin-bottom:8px;">Note interne (optionnel)</p>
        <textarea class="input" style="min-height:60px;" placeholder="Ex : offre spéciale avril…"></textarea>
      </div>
      <div style="background:rgba(45,212,191,.05);border:1px solid rgba(45,212,191,.12);border-radius:8px;padding:10px 13px;font-size:11px;color:#5eead4;">
        Les durées s'additionnent — si un abonnement actif existe, le nouveau repart de sa date d'expiration.
      </div>
    </div>
    <div style="display:flex;align-items:center;justify-content:flex-end;gap:8px;padding:12px 20px;border-top:1px solid rgba(255,255,255,.06);flex-shrink:0;">
      <button class="btn-ghost" onclick="closeModal('modal-subscription')">Annuler</button>
      <button class="btn-primary" onclick="App.createSubscription()">Créer →</button>
    </div>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     SCRIPTS
     ═══════════════════════════════════════════════════════════ -->
<script src="../js/dashboard.js"></script>
<script type="module" src="../js/chats.js"></script>

</body>
</html>