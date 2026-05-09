/* ══════════════════════════════════════════════════════════════════════
   chat_render.js — Construction HTML depuis les données
   Aucun fetch, aucun state global ici. Fonctions pures : data → HTML.
   ══════════════════════════════════════════════════════════════════════ */

// ── Utilitaires ───────────────────────────────────────────────────────

export function escapeHtml(str) {
  if (!str) return ''
  return str
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
}

export function formatTime(iso) {
  if (!iso) return ''
  const d    = new Date(iso)
  const now  = new Date()
  const diff = now - d

  if (diff < 60 * 1000)               return 'À l\'instant'
  if (diff < 60 * 60 * 1000) {
    const m = Math.floor(diff / 60000)
    return `Il y a ${m}min`
  }
  if (d.toDateString() === now.toDateString())
    return d.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })

  const yesterday = new Date(now)
  yesterday.setDate(yesterday.getDate() - 1)
  if (d.toDateString() === yesterday.toDateString()) return 'Hier'

  return d.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' })
}

export function nowTime() {
  return new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
}

// Détermine la classe avatar selon le prénom (cohérence couleurs)
export function avatarClass(prenom) {
  const classes = ['av-sky', 'av-green', 'av-amber', 'av-violet', 'av-teal', 'av-coral']
  if (!prenom) return 'av-default'
  return classes[prenom.charCodeAt(0) % classes.length]
}

export function avatarText(prenom, username) {
  if (prenom) return prenom.slice(0, 2).toUpperCase()
  if (username) return username.replace('@', '').slice(0, 2).toUpperCase()
  return '??'
}

// ── Icônes médias selon le type ───────────────────────────────────────

const MEDIA_ICONS = {
  image:       `<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>`,
  video:       `<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2"/></svg>`,
  pdf:         `<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>`,
  word:        `<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>`,
  excel:       `<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="8" y1="13" x2="16" y2="17"/><line x1="16" y1="13" x2="8" y2="17"/></svg>`,
  powerpoint:  `<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>`,
  archive:     `<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>`,
  text:        `<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><line x1="17" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="17" y1="18" x2="3" y2="18"/></svg>`,
  document:    `<svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>`,
}

function mediaIcon(type) {
  return MEDIA_ICONS[type] || MEDIA_ICONS.document
}

// ── Liste conversations ───────────────────────────────────────────────

export function renderConvItem(conv) {
  const avClass  = avatarClass(conv.name)
  const avTxt    = avatarText(conv.name, conv.name)
  const time     = formatTime(conv.last_activity)
  const preview  = conv.last_message ? escapeHtml(conv.last_message.slice(0, 50)) : '—'
  const isBlocked = conv.is_blocked
  const unread   = conv.unread_count || 0

  const badges = (conv.categories || []).slice(0, 2).map(c =>
    `<span class="badge badge-sky" style="font-size:9px;padding:1px 5px;">${escapeHtml(c)}</span>`
  ).join('')

  const unreadBadge = unread > 0
    ? `<span style="margin-left:auto;width:16px;height:16px;border-radius:50%;background:#38bdf8;color:#082f49;font-size:10px;font-weight:600;display:flex;align-items:center;justify-content:center;">${unread}</span>`
    : ''

  const iaChip = conv.last_answered_by === 'ia'
    ? `<span class="ai-chip" style="font-size:9px;padding:1px 5px;">IA</span>`
    : ''

  return `
    <div class="conv-item fadein${isBlocked ? ' blocked' : ''}"
         id="conv-${conv.user_id}"
         data-uid="${conv.user_id}"
         onclick="App.selectConv(${conv.user_id}, this)">
      <div class="av ${avClass} av-sm">${avTxt}</div>
      <div style="flex:1;min-width:0;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2px;">
          <p style="font-size:12px;font-weight:500;color:#e4e4e7;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${escapeHtml(conv.name || conv.name || 'Inconnu')}</p>
          <span style="font-size:10px;color:#52525b;flex-shrink:0;margin-left:8px;">${time}</span>
        </div>
        <div style="display:flex;align-items:center;gap:4px;margin-bottom:2px;">
          ${iaChip}
          <p style="font-size:11px;color:#52525b;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${preview}</p>
        </div>
        <div style="display:flex;align-items:center;gap:6px;margin-top:4px;">
          ${badges}
          ${isBlocked ? '<span class="badge badge-red" style="font-size:9px;padding:1px 5px;">A bloqué le bot</span>' : ''}
          ${unreadBadge}
        </div>
      </div>
    </div>`
}

// ── Messages ──────────────────────────────────────────────────────────

export function renderDateSep(date) {
  const d   = new Date(date)
  const now = new Date()
  let label = d.toLocaleDateString('fr-FR', { weekday: 'long', day: 'numeric', month: 'long' })
  if (d.toDateString() === now.toDateString()) label = 'Aujourd\'hui'
  const yesterday = new Date(now)
  yesterday.setDate(yesterday.getDate() - 1)
  if (d.toDateString() === yesterday.toDateString()) label = 'Hier'
  return `<div class="date-sep"><span>${label}</span></div>`
}

export function renderMessage(msg, conv) {
  // Normaliser message_type en minuscule (anciens messages en majuscule)
  msg = { ...msg, message_type: (msg.message_type || 'text').toLowerCase() }

  const time      = formatTime(msg.created_at)
  const isInbound = msg.direction === 'inbound'
  const isIA      = msg.answered_by === 'ia'
  const isBcast   = msg.message_type === 'broadcast' || msg.broadcast_id

  if (isBcast)   return renderBroadcastBubble(msg, time)
  if (isInbound) return renderInboundMessage(msg, conv, time)
  if (isIA)      return renderIAMessage(msg, time)
  // outbound admin — answered_by peut être 'admin' ou null
  return renderAdminMessage(msg, time)
}

function renderReplyQuote(msg) {
  if (!msg.replied_to_id || !msg.replied_to_text) return ''
  return `
    <div class="reply-quote" style="margin-bottom:6px;font-size:11px;">
      ${escapeHtml(msg.replied_to_text.slice(0, 80))}${msg.replied_to_text.length > 80 ? '…' : ''}
    </div>`
}

const MEDIA_BASE = 'http://54.226.165.244:8000'

function renderMediaContent(msg) {
  if (!msg.media_url) return ''
  const type     = msg.message_type
  // Construire l'URL absolue si relative
  const mediaUrl = msg.media_url.startsWith('http')
    ? msg.media_url
    : `${MEDIA_BASE}${msg.media_url}`
  msg = { ...msg, media_url: mediaUrl }

  if (type === 'image') {
    return `
      <div class="media-thumb" style="margin-bottom:${msg.message_text ? '6px' : '0'};">
        <img src="${escapeHtml(msg.media_url)}"
             alt="image"
             style="max-width:220px;border-radius:8px;display:block;cursor:pointer;"
             onclick="window.open('${escapeHtml(msg.media_url)}','_blank')"
             onerror="this.style.display='none'">
      </div>`
  }

  if (type === 'video') {
    return `
      <div style="margin-bottom:${msg.message_text ? '6px' : '0'};">
        <video controls style="max-width:260px;border-radius:8px;display:block;">
          <source src="${escapeHtml(msg.media_url)}">
        </video>
      </div>`
  }

  // Documents (pdf, word, excel, powerpoint, archive, text)
  const filename = msg.media_url.split('/').pop()
  const sizeTxt  = msg.size_mb ? `${msg.size_mb} MB` : ''
  return `
    <div class="media-doc">
      ${mediaIcon(type)}
      <div>
        <p style="font-size:12px;color:#d4d4d8;">${escapeHtml(filename)}</p>
        ${sizeTxt ? `<p style="font-size:10px;color:#52525b;">${sizeTxt}</p>` : ''}
      </div>
      <a href="${escapeHtml(msg.media_url)}" download class="btn-icon" style="margin-left:auto;width:22px;height:22px;">
        <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      </a>
    </div>`
}

function renderInboundMessage(msg, conv, time) {
  const avClass  = avatarClass(conv?.name)
  const avTxt    = avatarText(conv?.name, conv?.name)
  const hasMedia = !!msg.media_url
  const padding  = hasMedia ? 'padding:6px;' : ''

  // Bannière ⚡ si l'IA a marqué ce message comme nécessitant l'admin
  const adminBanner = msg.requires_admin
    ? `<div style="display:flex;align-items:center;gap:5px;font-size:10px;color:#fb923c;margin-bottom:4px;">
        <svg width="10" height="10" fill="none" stroke="#fb923c" viewBox="0 0 24 24" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
        Intervention admin requise
       </div>`
    : ''

  // Badge témoignage
  const testiColor = msg.is_testimonial ? '#fbbf24' : '#3f3f46'
  const testiTitle = msg.is_testimonial ? 'Retirer témoignage' : 'Marquer comme témoignage'

  return `
    <div class="msg-group fadein">
      ${adminBanner}
      <div class="msg-row" style="display:flex;align-items:flex-end;gap:8px;">
        <div class="av ${avClass}" style="width:24px;height:24px;font-size:9px;align-self:flex-end;">${avTxt}</div>
        <div>
          ${renderReplyQuote(msg)}
          <div class="bubble bubble-in" style="${padding}${msg.requires_admin ? 'border-left:2px solid #fb923c;' : ''}">
            ${renderMediaContent(msg)}
            ${msg.message_text ? `<p style="padding:${hasMedia ? '0 4px 2px' : '0'};font-size:13px;">${escapeHtml(msg.message_text)}</p>` : ''}
          </div>
          <div class="msg-meta">
            ${time}
            ${msg.is_testimonial ? '<span style="color:#fbbf24;margin-left:4px;">⭐ Témoignage</span>' : ''}
          </div>
        </div>
        <div style="display:flex;flex-direction:column;gap:3px;">
          <button class="btn-icon reply-btn" style="width:22px;height:22px;"
                  onclick="App.setReply(${msg.id}, '${escapeHtml((msg.message_text || '').slice(0, 40))}')">
            <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="m9 14-5-5 5-5"/><path d="M4 9h10.5a5.5 5.5 0 0 1 0 11H11"/></svg>
          </button>
          <button class="btn-icon reply-btn" style="width:22px;height:22px;color:${testiColor};"
                  title="${testiTitle}"
                  onclick="App.markTestimonial(${msg.id}, ${msg.is_testimonial ? 0 : 1})">
            <svg width="10" height="10" fill="${msg.is_testimonial ? '#fbbf24' : 'none'}" stroke="${testiColor}" viewBox="0 0 24 24" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
          </button>
        </div>
      </div>
    </div>`
}

function renderAdminMessage(msg, time) {
  const hasMedia = !!msg.media_url
  const padding  = hasMedia ? 'padding:6px;' : ''
  const status   = msg.status === 'read' ? '<span class="status-read">✓✓</span>'
                 : msg.status === 'delivered' ? '<span class="status-read">✓✓</span>'
                 : '<span class="status-sent">✓</span>'

  return `
    <div class="msg-group fadein">
      <div style="display:flex;align-items:flex-end;justify-content:flex-end;gap:8px;">
        <div>
          ${renderReplyQuote(msg)}
          <div class="bubble bubble-admin" style="${padding}">
            ${renderMediaContent(msg)}
            ${msg.message_text ? `<p style="font-size:13px;">${escapeHtml(msg.message_text).replace(/\n/g, '<br>')}</p>` : ''}
          </div>
          <div class="msg-meta" style="justify-content:flex-end;">
            ${time} ${status} <span style="color:#a1a1aa;">· Admin</span>
          </div>
        </div>
      </div>
    </div>`
}

function renderIAMessage(msg, time) {
  return `
    <div class="msg-group fadein">
      <div style="display:flex;align-items:flex-end;gap:8px;">
        <div class="av av-teal" style="width:24px;height:24px;font-size:9px;align-self:flex-end;">IA</div>
        <div>
          <span class="ai-chip">Agent IA</span>
          <div class="bubble bubble-ia">${escapeHtml(msg.message_text || '').replace(/\n/g, '<br>')}</div>
          <div class="msg-meta">${time} <span style="color:#2dd4bf;">· IA auto</span></div>
        </div>
      </div>
    </div>`
}

function renderBroadcastBubble(msg, time) {
  return `
    <div class="msg-group fadein">
      <div class="bubble-broadcast" style="max-width:82%;">
        <div class="bc-header">
          <svg width="13" height="13" fill="none" stroke="#71717a" viewBox="0 0 24 24" stroke-width="1.5"><path d="m22 2-7 20-4-9-9-4 20-7z"/></svg>
          <span style="font-size:11px;font-weight:500;color:#71717a;">Campagne broadcast</span>
          ${msg.broadcast_tag ? `<span class="badge badge-zinc" style="margin-left:auto;font-size:10px;">tag: ${escapeHtml(msg.broadcast_tag)}</span>` : ''}
        </div>
        <div class="bc-body">${escapeHtml(msg.message_text || '')}</div>
        <div class="bc-footer">
          ${msg.broadcast_total ? `<span style="font-size:10px;color:#52525b;">Envoyé à ${msg.broadcast_total} membres</span>` : ''}
          <span class="badge badge-${msg.status === 'read' ? 'green' : 'zinc'}" style="margin-left:auto;font-size:10px;">
            ${msg.status === 'read' ? 'Vu ✓✓' : 'Envoyé ✓'}
          </span>
          <span style="font-size:10px;color:#3f3f46;">${time}</span>
        </div>
      </div>
    </div>`
}

// ── Upload preview ────────────────────────────────────────────────────

export function renderUploadPreview(file) {
  const isImage = file.type.startsWith('image/')
  const isVideo = file.type.startsWith('video/')
  const icon    = isImage ? MEDIA_ICONS.image
                : isVideo ? MEDIA_ICONS.video
                : MEDIA_ICONS.document
  const size    = file.size > 1024 * 1024
    ? `${(file.size / 1024 / 1024).toFixed(1)} MB`
    : `${Math.round(file.size / 1024)} KB`

  return `
    <div style="display:flex;align-items:center;gap:8px;padding:8px 10px;
                background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.07);
                border-radius:8px;font-size:12px;color:#a1a1aa;">
      ${icon}
      <span style="flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${escapeHtml(file.name)}</span>
      <span style="font-size:10px;color:#52525b;flex-shrink:0;">${size}</span>
      <button onclick="App.clearUpload()" class="btn-icon" style="width:18px;height:18px;flex-shrink:0;">
        <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
      </button>
    </div>`
}

// ── Profil membre ─────────────────────────────────────────────────────

export function renderProfile(profile) {
  if (!profile) return ''

  const avClass = avatarClass(profile.name)
  const avTxt   = avatarText(profile.name, profile.name)
  const sub     = profile.subscription || {}
  const trading = profile.trading || {}

  const badges = (profile.categories || []).map(c =>
    `<span class="badge badge-sky" style="font-size:10px;">${escapeHtml(c)}</span>`
  ).join('')

  // Abonnement
  let subHtml = `<span class="badge badge-zinc" style="font-size:10px;">Aucun</span>`
  if (sub.has_active) {
    const plans = (sub.plans_active || []).join(', ')
    const days  = sub.days_remaining || 0
    const color = days <= 7 ? '#f87171' : '#34d399'
    subHtml = `
      <span class="badge badge-green" style="font-size:10px;">${escapeHtml(plans)}</span>
      <span style="font-size:10px;color:${color};margin-left:4px;">${days}j restants</span>`
  }

  // Trading
  const tradesHtml = trading.total_trades ? `
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;margin-bottom:12px;">
      <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);border-radius:7px;padding:8px 6px;text-align:center;">
        <p style="font-size:15px;font-weight:300;color:white;">${trading.total_trades}</p>
        <p style="font-size:9px;color:#52525b;margin-top:2px;">Trades</p>
      </div>
      <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);border-radius:7px;padding:8px 6px;text-align:center;">
        <p style="font-size:15px;font-weight:300;color:#34d399;">${trading.win_rate || 0}%</p>
        <p style="font-size:9px;color:#52525b;margin-top:2px;">Win</p>
      </div>
      <div style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.05);border-radius:7px;padding:8px 6px;text-align:center;">
        <p style="font-size:15px;font-weight:300;color:#34d399;">+${trading.avg_result_percent || 0}%</p>
        <p style="font-size:9px;color:#52525b;margin-top:2px;">Moy.</p>
      </div>
    </div>
    <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
      <span style="font-size:10px;color:#52525b;">Win rate</span>
      <span style="font-size:10px;color:#34d399;">${trading.win_rate || 0}%</span>
    </div>
    <div class="pbar"><div class="pbar-fill" style="width:${trading.win_rate || 0}%;background:#34d399;"></div></div>` : ''

  // Broadcasts
  const bcastHtml = (profile.broadcasts_received || []).map(b => `
    <div style="padding:7px 10px;background:rgba(255,255,255,.025);border-radius:7px;border:1px solid rgba(255,255,255,.05);">
      <p style="font-size:11px;font-weight:500;color:#e4e4e7;">${escapeHtml(b.tag || 'Broadcast')}</p>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-top:4px;">
        <span style="font-size:10px;color:#52525b;">${formatTime(b.started_at)}</span>
        <span class="badge badge-${b.status === 'read' ? 'green' : 'zinc'}" style="font-size:9px;">
          ${b.status === 'read' ? 'Vu ✓✓' : 'Envoyé ✓'}
        </span>
      </div>
    </div>`).join('')

  return `
    <div class="profile-section">
      <div style="display:flex;flex-direction:column;align-items:center;gap:8px;padding:8px 0 4px;text-align:center;">
        <div class="av ${avClass}" style="width:44px;height:44px;font-size:15px;">${avTxt}</div>
        <div>
          <p style="font-size:13px;font-weight:500;color:white;">${escapeHtml(profile.name || '')}</p>
          <p style="font-size:11px;color:#52525b;margin-top:2px;">${escapeHtml(profile.name || '')} · ID ${profile.telegram_id}</p>
        </div>
        <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:4px;">${badges}</div>
      </div>
    </div>

    <div class="profile-section">
      <p class="profile-label">Infos</p>
      <div style="display:flex;flex-direction:column;gap:6px;">
        <div class="stat-row">
          <span class="stat-label">Abonnement</span>
          <span style="display:flex;align-items:center;">${subHtml}</span>
        </div>
        <div class="stat-row">
          <span class="stat-label">Inscrit le</span>
          <span class="stat-val">${profile.registered_at ? new Date(profile.registered_at).toLocaleDateString('fr-FR') : '—'}</span>
        </div>
        <div class="stat-row">
          <span class="stat-label">Dernière activité</span>
          <span class="stat-val" style="color:#34d399;">${formatTime(profile.last_activity)}</span>
        </div>
        <div class="stat-row">
          <span class="stat-label">Messages</span>
          <span class="stat-val">${profile.total_messages || 0}</span>
        </div>
      </div>
    </div>

    ${tradesHtml ? `<div class="profile-section"><p class="profile-label">Trading</p>${tradesHtml}</div>` : ''}

    ${bcastHtml ? `
    <div class="profile-section">
      <p class="profile-label">Campagnes reçues</p>
      <div style="display:flex;flex-direction:column;gap:8px;">${bcastHtml}</div>
    </div>` : ''}

    <div class="profile-section">
      <p class="profile-label">Actions rapides</p>
      <div style="display:flex;flex-direction:column;gap:6px;">
        <button class="btn-ghost" style="font-size:11px;justify-content:flex-start;width:100%;"
                onclick="App.openSubscriptionModal()">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>
          Gérer l'abonnement
        </button>
        <button class="btn-ghost" style="font-size:11px;justify-content:flex-start;width:100%;"
                onclick="App.exportConv()">
          <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/></svg>
          Exporter la conversation
        </button>
      </div>
    </div>`
}

// ── Toast ─────────────────────────────────────────────────────────────

export function toast(message, type = 'info') {
  const colors = { info: '#38bdf8', success: '#34d399', error: '#f87171' }
  const el = document.createElement('div')
  el.style.cssText = `
    position:fixed;bottom:20px;right:20px;z-index:9999;
    background:#1a1a1c;border:1px solid rgba(255,255,255,.1);
    border-left:3px solid ${colors[type] || colors.info};
    padding:10px 16px;border-radius:8px;font-size:13px;
    color:#e4e4e7;box-shadow:0 4px 20px rgba(0,0,0,.4);
    animation:fadein .2s ease;
  `
  el.textContent = message
  document.body.appendChild(el)
  setTimeout(() => el.remove(), 3000)
}