{{-- ═══════════════════════════════════════════════════════════════
     components/messages_modals.blade.php
     ═══════════════════════════════════════════════════════════════ --}}

<!-- Modal: Confirmation envoi -->
<div class="modal-overlay" id="modal-confirm">
    <div class="modal">
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
            <p class="text-sm font-medium text-white">Confirmer l'envoi</p>
            <button class="btn-icon" onclick="closeModal('modal-confirm')">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="px-6 py-5">
            <div class="flex flex-col gap-3 mb-5">
                <div class="flex items-center gap-3 px-3 py-2.5"
                    style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:9px;">
                    <div>
                        <p class="text-xs font-medium text-zinc-200">Destinataires</p>
                        <p class="text-[11px] mt-0.5" style="color:#52525b;" id="confirm-dest">—</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-3 py-2.5"
                    style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.06);border-radius:9px;">
                    <div>
                        <p class="text-xs font-medium text-zinc-200">Paramètres</p>
                        <p class="text-[11px] mt-0.5" style="color:#52525b;" id="confirm-meta">—</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 px-3 py-2.5"
                    style="background:rgba(251,191,36,.05);border:1px solid rgba(251,191,36,.15);border-radius:9px;">
                    <svg width="14" height="14" fill="none" stroke="#fbbf24" viewBox="0 0 24 24" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/>
                        <path d="M12 8v4M12 16h.01"/>
                    </svg>
                    <p class="text-xs" style="color:#fbbf24;">
                        Cette action lancera broadcast_engine en tâche asynchrone. L'envoi sera non-bloquant.
                    </p>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end gap-2 px-6 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
            <button class="btn-ghost" onclick="closeModal('modal-confirm')">Annuler</button>
            <button class="btn-primary" id="btn-confirm" onclick="sendBroadcast()">Lancer l'envoi →</button>
        </div>
    </div>
</div>

<!-- Modal: Planifier -->
<div class="modal-overlay" id="modal-schedule">
    <div class="modal" style="max-width:420px;">
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
            <p class="text-sm font-medium text-white">Planifier l'envoi</p>
            <button class="btn-icon" onclick="closeModal('modal-schedule')">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="px-6 py-5 flex flex-col gap-4">
            <div>
                <p class="text-xs mb-2" style="color:#52525b;">Date et heure d'envoi (scheduled_at)</p>
                <input type="datetime-local" class="input" id="schedule-input">
                <p class="text-[10px] mt-1.5" style="color:#3f3f46;">Format envoyé : "2026-04-20 14:30:00"</p>
            </div>
            <div class="px-3 py-2.5"
                style="background:rgba(56,189,248,.05);border:1px solid rgba(56,189,248,.15);border-radius:9px;">
                <p class="text-xs" style="color:#38bdf8;">
                    L'envoi démarrera à l'heure exacte via asyncio.sleep dans broadcast_engine.
                </p>
            </div>
        </div>
        <div class="flex items-center justify-end gap-2 px-6 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
            <button class="btn-ghost" onclick="closeModal('modal-schedule')">Annuler</button>
            <button class="btn-primary" onclick="confirmSchedule()">Planifier</button>
        </div>
    </div>
</div>

<!-- Modal: Aperçu -->
<div class="modal-overlay" id="modal-preview">
    <div class="modal" style="max-width:360px;">
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
            <p class="text-sm font-medium text-white">Aperçu du message</p>
            <button class="btn-icon" onclick="closeModal('modal-preview')">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="px-6 py-5">
            <p class="text-[10px] mb-3" style="color:#52525b;">
                Rendu avec variables résolues (+prenom → Marc, +offre → 50%)
            </p>
            <div class="tg-preview">
                <div class="tg-header">
                    <div style="width:28px;height:28px;border-radius:50%;background:#0ea5e9;" class="flex-shrink-0"></div>
                    <div>
                        <p class="text-xs font-medium" style="color:#e2e8f0;">TradingBot</p>
                        <p style="font-size:10px;color:#4a6478;">bot</p>
                    </div>
                </div>
                <div class="tg-body">
                    <div class="tg-bubble" id="modal-preview-text">
                        Bonjour <span style="color:#7dd3fc;">Marc</span>, votre message apparaîtra ici...
                    </div>
                    <p class="tg-time">14:30 ✓✓</p>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-end gap-2 px-6 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
            <button class="btn-ghost" onclick="closeModal('modal-preview')">Fermer</button>
        </div>
    </div>
</div>

<!-- Modal: Détail campagne -->
<div class="modal-overlay" id="modal-detail">
    <div class="modal" style="max-width:520px;">
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid rgba(255,255,255,.06);">
            <div>
                <p class="text-sm font-medium text-white" id="modal-detail-title">—</p>
                <p class="text-[11px] mt-0.5" style="color:#52525b;" id="modal-detail-dates">—</p>
            </div>
            <button class="btn-icon" onclick="closeModal('modal-detail')">
                <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="px-6 py-5 flex flex-col gap-4">
            <div class="grid grid-cols-4 gap-2">
                <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);border-radius:9px;padding:12px;text-align:center;">
                    <p class="text-lg font-light text-white tabular-nums" id="detail-sent">—</p>
                    <p class="text-[10px] mt-1" style="color:#52525b;">Envoyés</p>
                </div>
                <div style="background:rgba(52,211,153,.06);border:1px solid rgba(52,211,153,.12);border-radius:9px;padding:12px;text-align:center;">
                    <p class="text-lg font-light tabular-nums" style="color:#34d399;" id="detail-taux">—</p>
                    <p class="text-[10px] mt-1" style="color:#34d399;opacity:.7;">Taux envoi</p>
                </div>
                <div style="background:rgba(56,189,248,.06);border:1px solid rgba(56,189,248,.12);border-radius:9px;padding:12px;text-align:center;">
                    <p class="text-lg font-light tabular-nums" style="color:#38bdf8;" id="detail-total">—</p>
                    <p class="text-[10px] mt-1" style="color:#38bdf8;opacity:.7;">Total</p>
                </div>
                <div style="background:rgba(248,113,113,.06);border:1px solid rgba(248,113,113,.12);border-radius:9px;padding:12px;text-align:center;">
                    <p class="text-lg font-light tabular-nums" style="color:#f87171;" id="detail-errors">—</p>
                    <p class="text-[10px] mt-1" style="color:#f87171;opacity:.7;">Erreurs</p>
                </div>
            </div>
            <div>
                <p class="text-[10px] mb-2" style="color:#52525b;">Rapport</p>
                <pre id="detail-payload"
                    style="background:#0d0d0f;border:1px solid rgba(255,255,255,.07);border-radius:8px;padding:12px;font-size:11px;color:#71717a;font-family:'Geist Mono',monospace;overflow-x:auto;line-height:1.7;"></pre>
            </div>
        </div>
        <div class="flex items-center justify-end gap-2 px-6 py-4" style="border-top:1px solid rgba(255,255,255,.06);">
            <button class="btn-ghost" onclick="closeModal('modal-detail')">Fermer</button>
        </div>
    </div>
</div>