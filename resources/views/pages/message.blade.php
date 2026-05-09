<!DOCTYPE html>
<html lang="fr" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradingBot — Messages ciblés</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link rel="stylesheet" href="../css/messages.css">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=geist:300,400,500,600&family=geist-mono:400" rel="stylesheet">
</head>

<body class="text-zinc-200" style="height:100vh; overflow:hidden;">

    @include('components.sidebar_mobile')

    <div style="display:flex; height:100vh; overflow:hidden;">

        @include('components.sidebar')

        <!-- ─── MAIN ─────────────────────────────────────────────────────── -->
        <div style="flex:1; display:flex; flex-direction:column; min-width:0; overflow:hidden;">

            @include('components.messages_topbar')

            <!-- Page content -->
            <main style="flex:1; overflow-y:auto; padding:16px; display:flex; flex-direction:column; gap:14px;">

                <!-- ════ VUE : COMPOSER ════ -->
                <div id="view-compose" class="compose-layout">

                    <!-- Colonne gauche: formulaire -->
                    <div class="compose-form">

                        <!-- 1. Destinataires -->
                        <div class="card msg-card">
                            <p class="section-label">1 · Destinataires</p>

                            <div class="dest-grid mb-4">
                                <button class="format-btn active" id="dest-category"
                                    onclick="switchDest('category',this)">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg>
                                    Par catégorie
                                </button>
                                <button class="format-btn" id="dest-ids" onclick="switchDest('ids',this)">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                        <circle cx="12" cy="7" r="4" />
                                    </svg>
                                    IDs manuels
                                </button>
                                <button class="format-btn" id="dest-all" onclick="switchDest('all',this)">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <circle cx="12" cy="12" r="10" />
                                        <path
                                            d="M2 12h20M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                    </svg>
                                    Tous
                                </button>
                            </div>

                            <!-- Catégorie -->
                            <div id="dest-block-category">
                                <select class="input mb-3" onchange="updateSummary()">
                                    <option value="">Sélectionner une catégorie...</option>
                                </select>

                                <div>
                                    <button class="btn-ghost" style="font-size:11px;margin-bottom:10px;"
                                        onclick="toggleFilters()">
                                        <svg width="11" height="11" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" stroke-width="2">
                                            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3" />
                                        </svg>
                                        Filtres avancés
                                        <span id="filter-count" class="badge badge-sky"
                                            style="font-size:10px;display:none;">2</span>
                                    </button>
                                    <div id="filters-panel" style="display:none;" class="flex flex-col gap-3">
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <p class="text-[10px] mb-1.5" style="color:#52525b;">Inscrit après</p>
                                                <input type="date" class="input" style="font-size:12px;">
                                            </div>
                                            <div>
                                                <p class="text-[10px] mb-1.5" style="color:#52525b;">Inscrit avant</p>
                                                <input type="date" class="input" style="font-size:12px;">
                                            </div>
                                        </div>
                                        <div class="flex flex-wrap gap-2" id="active-filters">
                                            <span class="filter-tag">inscrit après 01/01/2025 <button
                                                    onclick="removeFilter(this)">✕</button></span>
                                            <span class="filter-tag">inscrit avant 01/01/2026 <button
                                                    onclick="removeFilter(this)">✕</button></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.05);">
                                    <p class="text-[10px] mb-1.5" style="color:#52525b;">Exclure des user_ids
                                        (exclude_user_ids)</p>
                                    <input class="input" type="text" placeholder="ex: 789, 1042, 2310"
                                        style="font-size:12px;font-family:'Geist Mono',monospace;">
                                </div>
                            </div>

                            <!-- IDs manuels -->
                            <div id="dest-block-ids" style="display:none;">
                                <p class="text-[10px] mb-1.5" style="color:#52525b;">user_ids — séparés par des virgules
                                </p>
                                <textarea class="input"
                                    style="min-height:64px;font-family:'Geist Mono',monospace;font-size:12px;"
                                    placeholder="123, 456, 789, 1042..."></textarea>
                            </div>

                            <!-- Tous -->
                            <div id="dest-block-all" style="display:none;">
                                <div class="flex items-center gap-3 px-3 py-2.5"
                                    style="background:rgba(251,191,36,.07);border:1px solid rgba(251,191,36,.18);border-radius:8px;">
                                    <svg width="14" height="14" fill="none" stroke="#fbbf24" viewBox="0 0 24 24"
                                        stroke-width="1.5">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M12 8v4M12 16h.01" />
                                    </svg>
                                    <p class="text-xs" style="color:#fbbf24;">Message envoyé à tous
                                        (category: null, user_ids: null)</p>
                                </div>
                            </div>

                            
                        </div>

                        <!-- 2. Format & Contenu -->
                        <div class="card msg-card">
                            <p class="section-label">2 · Format & contenu</p>

                            <div class="format-grid mb-4">
                                <button class="format-btn active" onclick="switchFormat('text',this)" id="fmt-text">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                    Texte
                                </button>
                                <button class="format-btn" onclick="switchFormat('image',this)" id="fmt-image">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <path d="m21 15-5-5L5 21" />
                                    </svg>
                                    Image
                                </button>
                                <button class="format-btn" onclick="switchFormat('video',this)" id="fmt-video">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path d="m22 8-6 4 6 4V8z" />
                                        <rect x="2" y="6" width="14" height="12" rx="2" />
                                    </svg>
                                    Vidéo
                                </button>
                                <button class="format-btn" onclick="switchFormat('image+text',this)" id="fmt-imagetext">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <rect x="3" y="3" width="18" height="18" rx="2" />
                                        <circle cx="8.5" cy="8.5" r="1.5" />
                                        <path d="m21 15-5-5L5 21" />
                                    </svg>
                                    Img + texte
                                </button>
                                <button class="format-btn" onclick="switchFormat('video+text',this)" id="fmt-videotext">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path d="m22 8-6 4 6 4V8z" />
                                        <rect x="2" y="6" width="14" height="12" rx="2" />
                                    </svg>
                                    Vid + texte
                                </button>
                            </div>

                            <div id="media-upload" style="display:none;margin-bottom:14px;">
                                <p class="text-[10px] mb-2" style="color:#52525b;">media_url — file_id Telegram ou
                                    fichier local</p>
                                <div class="upload-zone" onclick="triggerUpload()">
                                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        stroke-width="1.5" style="margin:0 auto 6px;">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                                        <polyline points="17 8 12 3 7 8" />
                                        <line x1="12" y1="3" x2="12" y2="15" />
                                    </svg>
                                    <p class="text-xs">Glisser un fichier ou <span
                                            style="color:#38bdf8;">parcourir</span></p>
                                    <p class="text-[10px] mt-1" style="color:#3f3f46;">ou coller un file_id Telegram
                                        directement</p>
                                </div>
                                <input class="input mt-2" type="text"
                                    placeholder="file_id Telegram (ex: AgACAgIAAxkBAAI...)"
                                    style="font-size:12px;font-family:'Geist Mono',monospace;">
                            </div>

                            <div id="text-block">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-[10px]" style="color:#52525b;">message — supporte les variables
                                        +prenom, +offre, +lien...</p>
                                    <span class="text-[10px] tabular-nums" id="char-count" style="color:#3f3f46;">0 /
                                        4096</span>
                                </div>
                                <textarea class="input" id="msg-textarea"
                                    style="min-height:110px;font-size:13px;line-height:1.6;"
                                    placeholder="Bonjour +prenom, ..."
                                    oninput="updatePreview();updateCount(this)"></textarea>
                            </div>

                            <div class="mt-3">
                                <p class="text-[10px] mb-2" style="color:#52525b;">Variables disponibles — cliquer pour
                                    insérer</p>
                                <div class="flex flex-wrap gap-1.5">
                                    <span class="var-chip" onclick="insertVar('+prenom')">+prenom</span>
                                    <span class="var-chip" onclick="insertVar('+offre')">+offre</span>
                                    <span class="var-chip" onclick="insertVar('+lien')">+lien</span>
                                    <span class="var-chip" onclick="insertVar('+perf')">+perf</span>
                                    <span class="var-chip" onclick="insertVar('+date')">+date</span>
                                    <span class="var-chip" onclick="insertVar('+plan')">+plan</span>
                                </div>
                            </div>

                            <div class="mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.05);">
                                <div class="flex items-center justify-between mb-2">
                                    <p class="text-[10px]" style="color:#52525b;">Variables personnalisées (variables)
                                    </p>
                                    <button class="btn-ghost" style="font-size:10px;padding:3px 8px;"
                                        onclick="addVarRow()">+ Ajouter</button>
                                </div>
                                <div id="custom-vars" class="flex flex-col gap-2">
                                    <div class="flex items-center gap-2">
                                        <input class="input" type="text" placeholder="+offre"
                                            style="width:110px;font-size:12px;font-family:'Geist Mono',monospace;flex-shrink:0;">
                                        <span style="color:#3f3f46;font-size:12px;">→</span>
                                        <input class="input" type="text" placeholder="50%" style="font-size:12px;">
                                        <button class="btn-icon" style="width:24px;height:24px;"
                                            onclick="this.closest('div').remove()">
                                            <svg width="10" height="10" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 3. Options d'envoi -->
                        <div class="card msg-card">
                            <p class="section-label">3 · Options d'envoi</p>
                            <div class="options-grid">
                                <div>
                                    <p class="text-[10px] mb-1.5" style="color:#52525b;">Délai entre envois (delay) en
                                        secondes</p>
                                    <input class="input" type="number" value="0.1" step="0.1" min="0.05"
                                        style="font-size:13px;">
                                    <p class="text-[10px] mt-1" style="color:#3f3f46;">Recommandé : 0.1s — minimum 0.05s
                                    </p>
                                </div>
                                <div>
                                    <p class="text-[10px] mb-1.5" style="color:#52525b;">Tag de campagne (tag)</p>
                                    <input class="input" type="text" placeholder="ex: promo_avril"
                                        style="font-size:13px;font-family:'Geist Mono',monospace;">
                                </div>
                                <div class="flex items-center justify-between py-2 px-3"
                                    style="background:rgba(255,255,255,.025);border:1px solid rgba(255,255,255,.05);border-radius:8px;">
                                    <div>
                                        <p class="text-xs text-zinc-300">Retry automatique (retry)</p>
                                        <p class="text-[10px] mt-0.5" style="color:#52525b;">Réessaie en cas d'échec
                                            Telegram</p>
                                    </div>
                                    <button class="toggle on" id="toggle-retry" onclick="toggleSwitch(this)"></button>
                                </div>
                                <div>
                                    <p class="text-[10px] mb-1.5" style="color:#52525b;">Webhook de fin (callback_url)
                                    </p>
                                    <input class="input" type="text" placeholder="https://monsite.com/webhook"
                                        style="font-size:12px;">
                                </div>
                            </div>
                        </div>

                        <!-- Preview inline (visible seulement mobile) -->
                        <div class="card msg-card preview-mobile-only">
                            <p class="section-label">Aperçu Telegram</p>
                            <div class="tg-preview">
                                <div class="tg-header">
                                    <div style="width:28px;height:28px;border-radius:50%;background:#0ea5e9;"
                                        class="flex-shrink-0"></div>
                                    <div>
                                        <p class="text-xs font-medium" style="color:#e2e8f0;">TradingBot</p>
                                        <p style="font-size:10px;color:#4a6478;">bot</p>
                                    </div>
                                </div>
                                <div class="tg-body">
                                    <div id="preview-media-mobile" style="display:none;margin-bottom:4px;">
                                        <div
                                            style="background:rgba(255,255,255,.07);border-radius:8px;height:80px;display:flex;align-items:center;justify-content:center;">
                                            <svg width="24" height="24" fill="none" stroke="#4a6478" viewBox="0 0 24 24"
                                                stroke-width="1.5">
                                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                                <circle cx="8.5" cy="8.5" r="1.5" />
                                                <path d="m21 15-5-5L5 21" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="tg-bubble" id="preview-text-mobile">Bonjour <span
                                            style="color:#7dd3fc;">Marc</span>, votre message apparaîtra ici...</div>
                                    <p class="tg-time">14:30 ✓✓</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Colonne droite: preview (desktop only) -->
                    <div class="compose-sidebar">

                        <div class="card msg-card">
                            <p class="section-label">Aperçu Telegram</p>
                            <div class="tg-preview">
                                <div class="tg-header">
                                    <div style="width:28px;height:28px;border-radius:50%;background:#0ea5e9;"
                                        class="flex-shrink-0"></div>
                                    <div>
                                        <p class="text-xs font-medium" style="color:#e2e8f0;">TradingBot</p>
                                        <p style="font-size:10px;color:#4a6478;">bot</p>
                                    </div>
                                </div>
                                <div class="tg-body">
                                    <div id="preview-media" style="display:none;margin-bottom:4px;">
                                        <div
                                            style="background:rgba(255,255,255,.07);border-radius:8px;height:80px;display:flex;align-items:center;justify-content:center;">
                                            <svg width="24" height="24" fill="none" stroke="#4a6478" viewBox="0 0 24 24"
                                                stroke-width="1.5">
                                                <rect x="3" y="3" width="18" height="18" rx="2" />
                                                <circle cx="8.5" cy="8.5" r="1.5" />
                                                <path d="m21 15-5-5L5 21" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="tg-bubble" id="preview-text">Bonjour <span
                                            style="color:#7dd3fc;">Marc</span>, votre message apparaîtra ici...</div>
                                    <p class="tg-time">14:30 ✓✓</p>
                                </div>
                            </div>
                        </div>

                        <div class="card msg-card">
                            <p class="section-label">Résumé de l'envoi</p>
                            <div class="flex flex-col gap-2.5">
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px]" style="color:#52525b;">Destinataires</span>
                                    <span class="text-[11px] font-medium text-zinc-300" id="summary-dest">Clients
                                        actifs</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px]" style="color:#52525b;">Estimé</span>
                                    <span class="text-[11px] font-medium" style="color:#38bdf8;" id="summary-count">847
                                        msgs</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px]" style="color:#52525b;">Format</span>
                                    <span class="text-[11px] font-medium text-zinc-300" id="summary-format">text</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px]" style="color:#52525b;">Delay</span>
                                    <span class="text-[11px] font-mono text-zinc-300">0.1s</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px]" style="color:#52525b;">Retry</span>
                                    <span class="badge badge-green" style="font-size:10px;">activé</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-[11px]" style="color:#52525b;">Planifié</span>
                                    <span class="text-[11px]" style="color:#52525b;"
                                        id="summary-schedule">Maintenant</span>
                                </div>
                            </div>
                        </div>

                        <div class="card msg-card">
                            <p class="section-label">Durée estimée</p>
                            <p class="text-xl font-light text-white tabular-nums" id="est-duration">~1m 25s</p>
                            <p class="text-[10px] mt-1" style="color:#52525b;">avec delay 0.1s · 847 destinataires</p>
                            <div class="mt-3 pt-3" style="border-top:1px solid rgba(255,255,255,.05);">
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="text-[10px]" style="color:#52525b;">Taux d'ouverture moyen</span>
                                    <span class="text-[10px]" style="color:#34d399;">67%</span>
                                </div>
                                <div class="stat-bar-track">
                                    <div class="stat-bar-fill" style="width:67%;background:#34d399;"></div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- ════ VUE : HISTORIQUE ════ -->
                <div id="view-history" style="display:none;" class="flex flex-col gap-4">

                    <div class="history-stats-grid">
                        <div class="card p-4">
                            <p class="text-[10px] mb-2" style="color:#52525b;">Campagnes ce mois</p>
                            <p class="text-2xl font-light text-white tabular-nums" id="stat-campagnes">—</p>
                        </div>
                        <div class="card p-4">
                            <p class="text-[10px] mb-2" style="color:#52525b;">Messages envoyés</p>
                            <p class="text-2xl font-light text-white tabular-nums" id="stat-messages">—</p>
                        </div>
                        <div class="card p-4">
                            <p class="text-[10px] mb-2" style="color:#52525b;">Taux envoi moy.</p>
                            <p class="text-2xl font-light tabular-nums" style="color:#34d399;" id="stat-taux">—</p>
                        </div>
                        <div class="card p-4">
                            <p class="text-[10px] mb-2" style="color:#52525b;">Erreurs cumulées</p>
                            <p class="text-2xl font-light tabular-nums" style="color:#f87171;" id="stat-erreurs">—</p>
                        </div>
                    </div>

                    <!-- Table desktop -->
                    <div class="card overflow-hidden camp-table-desktop">
                        <div class="camp-table-header">
                            <span class="camp-col-name">Campagne</span>
                            <span class="camp-col">Envoyés</span>
                            <span class="camp-col">Taux</span>
                            <span class="camp-col">Total</span>
                            <span class="camp-col">Erreurs</span>
                            <span class="camp-col">Statut</span>
                            <span style="width:28px;"></span>
                        </div>
                        {{-- Les lignes sont injectées par renderHistory() --}}
                    </div>

                    <!-- Cards mobile -->
                    <div class="camp-cards-mobile flex flex-col gap-3" style="display:none;">
                        {{-- Les cards sont injectées par renderHistoryMobile() --}}
                    </div>
                </div>

            </main>
        </div>
    </div>

    @include('components.messages_modals')

    <script src="../js/dashboard.js"></script>
    <script src="../js/messages.js"></script>
    <script src="../js/message_config_api.js"></script>

</body>

</html>