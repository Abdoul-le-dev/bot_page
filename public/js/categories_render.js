/* ═══════════════════════════════════════════════════════════════
   categories.render.js  —  construction HTML depuis les données
   ═══════════════════════════════════════════════════════════════ */

// ── Helpers ──────────────────────────────────────────────────

function relativeTime(dateStr) {
  if (!dateStr) return '—'
  const diff = Date.now() - new Date(dateStr).getTime()
  const m = Math.floor(diff / 60000)
  if (m < 1)   return "À l'instant"
  if (m < 60)  return `Il y a ${m}min`
  const h = Math.floor(m / 60)
  if (h < 24)  return `Il y a ${h}h`
  const d = Math.floor(h / 24)
  if (d === 1) return 'Hier'
  if (d < 30)  return `Il y a ${d}j`
  return new Date(dateStr).toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' })
}

function initials(name) {
  if (!name) return '??'
  return name.trim().split(/\s+/).slice(0, 2).map(w => w[0].toUpperCase()).join('')
}

// Choisit une classe av- selon l'index pour varier les couleurs
const AV_COLORS = ['av-green', 'av-sky', 'av-violet', 'av-teal', 'av-amber', 'av-red']
function avColor(telegramId) {
  return AV_COLORS[Math.abs(telegramId || 0) % AV_COLORS.length]
}

// Map trigger_type → label + icône SVG
const TRIGGER_MAP = {
  link:         { label: 'Lien',    icon: `<svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>` },
  inactivity:   { label: 'Inactif', icon: `<svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>` },
  survey:       { label: 'Sondage', icon: `<svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>` },
  subscription: { label: 'Abo',     icon: `<svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/></svg>` },
  trade_perf:   { label: 'Trade',   icon: `<svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M3 3v18h18"/><path d="m7 16 4-4 4 4 4-6"/></svg>` },
  keyword:      { label: 'Msg',     icon: `<svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>` },
  no_open:      { label: 'Auto',    icon: `<svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path d="m13 2-3 6h-7l6 4-2 7 6-4 6 4-2-7 6-4h-7z"/></svg>` },
}

function triggerPill(rules) {
  if (!rules || rules.length === 0) return ''
  const t = TRIGGER_MAP[rules[0].trigger_type] || { label: 'Auto', icon: '' }
  return `<span class="trigger-pill">${t.icon}${t.label}</span>`
}

// ────────────────────────────────────────────────────────────
// STATS GLOBALES (topbar + stats bar)
// ────────────────────────────────────────────────────────────

function renderGlobalStats(stats) {
  // Topbar label
  const lbl = document.getElementById('topbar-label')
  if (lbl) lbl.textContent = `${stats.total_categories ?? 0} catégories · ${(stats.tagged_members ?? 0).toLocaleString('fr-FR')} membres`

  // Stats bar (3 cases)
  const bar = document.getElementById('cat-stats-bar')
  if (!bar) return
  bar.innerHTML = `
    <div class="stat-mini" style="text-align:center;padding:10px 8px;">
      <p class="text-lg font-light text-white tabular-nums">${stats.total_categories}</p>
      <p class="text-[10px] mt-0.5" style="color:#52525b;">Catégories</p>
    </div>
    <div class="stat-mini" style="text-align:center;padding:10px 8px;">
      <p class="text-lg font-light text-white tabular-nums">${(stats.tagged_members ?? 0).toLocaleString('fr-FR')}</p>
      <p class="text-[10px] mt-0.5" style="color:#52525b;">Membres tagués</p>
    </div>
    <div class="stat-mini" style="text-align:center;padding:10px 8px;">
      <p class="text-lg font-light tabular-nums" style="color:#34d399;">${stats.avg_tags_per_member}</p>
      <p class="text-[10px] mt-0.5" style="color:#52525b;">Tags / membre</p>
    </div>
  `
}

// ────────────────────────────────────────────────────────────
// LISTE CATÉGORIES (colonne gauche)
// ────────────────────────────────────────────────────────────

function renderCatCard(cat, maxCount) {
  const count = cat.member_count  ?? 0
  const pct   = maxCount > 0 ? Math.round((count / maxCount) * 100) : 0
  const color = cat.color || '#38bdf8'
  const trend = (cat.new_this_month ?? 0) > 0
    ? `<span style="color:${color};font-size:10px;">+${cat.new_this_month} ce mois</span>`
    : `<span style="color:#52525b;font-size:10px;">—</span>`

  return `
    <div class="cat-card fadein"
         id="cat-${cat.name_categorie}"
         data-name="${cat.name_categorie}"
         onclick="App.selectCat(this)">
      <div class="cat-card-accent" style="background:${color};"></div>
      <div class="px-4 py-3">
        <div class="flex items-center justify-between mb-2">
          <div class="flex items-center gap-2">
            <span style="width:8px;height:8px;border-radius:50%;background:${color};flex-shrink:0;"></span>
            <p class="text-xs font-medium text-zinc-200">${cat.name_categorie}</p>
          </div>
          <div class="flex items-center gap-1.5">
            ${triggerPill(cat.rules)}
            <button class="btn-icon" style="width:22px;height:22px;"
              onclick="event.stopPropagation();App.openEditModal('${cat.name_categorie}')">
              <svg width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
              </svg>
            </button>
          </div>
        </div>
        <div class="flex items-center justify-between">
          <span class="text-xl font-light text-white tabular-nums">${count.toLocaleString('fr-FR')}</span>
          ${trend}
        </div>
        <div class="pbar mt-2">
          <div class="pbar-fill" style="width:${pct}%;background:${color};"></div>
        </div>
      </div>
    </div>
  `
}

function renderCatList(categories) {
  const list = document.getElementById('cat-list')
  if (!list) return

  if (categories.length === 0) {
    list.innerHTML = `
      <div style="padding:40px 20px;text-align:center;color:#3f3f46;">
        <p class="text-sm">Aucune catégorie</p>
        <p class="text-xs mt-1">Créez votre première catégorie</p>
      </div>`
    return
  }

  const maxCount = Math.max(...categories.map(c => c.member_count ?? 0), 1)
  list.innerHTML = categories
    .map(cat => renderCatCard(cat, maxCount) + '<div style="height:6px;"></div>')
    .join('')
}

// ────────────────────────────────────────────────────────────
// HEADER DÉTAIL (colonne centrale)
// ────────────────────────────────────────────────────────────

function renderDetailHeader(cat) {
  const color = cat.color || '#38bdf8'
  const dot   = document.getElementById('detail-dot')
  const name  = document.getElementById('detail-name')
  const meta  = document.getElementById('detail-meta')
  if (dot)  dot.style.background = color
  if (name) name.textContent = cat.name_categorie
  if (meta) meta.textContent = `${(cat.member_count ?? 0).toLocaleString('fr-FR')} membres`
}

// ────────────────────────────────────────────────────────────
// TABLE MEMBRES (colonne centrale)
// ────────────────────────────────────────────────────────────

function renderMemberRow(member) {
  const ini    = initials(member.name || String(member.telegram_id))
  const avCls  = avColor(member.telegram_id)
  const handle = member.username ? `@${member.username} · ` : ''
  const time   = relativeTime(member.last_activity)

  return `
    <div class="member-row" data-id="${member.telegram_id}">
      <input type="checkbox" style="accent-color:#38bdf8;flex-shrink:0;"
        onchange="App.toggleSelect(${member.telegram_id}, this.checked)">
      <div class="av ${avCls}">${ini}</div>
      <div style="flex:1;min-width:0;">
        <p class="text-xs font-medium text-zinc-200">${member.name || '—'}</p>
        <p class="text-[11px] mt-0.5" style="color:#52525b;font-family:'Geist Mono',monospace;">
          ${handle}ID ${member.telegram_id}
        </p>
      </div>
      <p class="member-col-date text-[11px] tabular-nums" style="color:#52525b;min-width:70px;text-align:right;flex-shrink:0;">
        ${time}
      </p>
      <button class="btn-icon" style="width:24px;height:24px;"
        onclick="App.openMemberDrawer(${member.telegram_id})">
        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
          <circle cx="12" cy="12" r="3"/>
        </svg>
      </button>
      <button class="btn-icon" style="width:24px;height:24px;" title="Retirer"
        onclick="App.removeMember(${member.telegram_id})">
        <svg width="11" height="11" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
          <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
        </svg>
      </button>
    </div>
  `
}

function renderMembersList(data) {
  const list = document.getElementById('members-list')
  if (!list) return

  const { members, total, limit, offset } = data

  if (members.length === 0) {
    list.innerHTML = `
      <div style="padding:40px 20px;text-align:center;color:#3f3f46;">
        <p class="text-sm">Aucun membre</p>
      </div>`
    return
  }

  const from  = offset + 1
  const to    = Math.min(offset + members.length, total)
  const hasMore = to < total

  list.innerHTML = members.map(renderMemberRow).join('') + `
    <div style="padding:8px 16px;border-top:1px solid rgba(255,255,255,.04);">
      <p class="text-[11px]" style="color:#3f3f46;">
        Affichage ${from}–${to} sur ${total.toLocaleString('fr-FR')}
        ${hasMore ? `· <span style="color:#38bdf8;cursor:pointer;" onclick="App.loadMoreMembers()">Voir plus →</span>` : ''}
      </p>
    </div>
  `
}

// ────────────────────────────────────────────────────────────
// COLONNE DROITE — RÈGLES
// ────────────────────────────────────────────────────────────

function renderRules(rules) {
  const container = document.getElementById('rules-list')
  if (!container) return

  if (!rules || rules.length === 0) {
    container.innerHTML = `<p class="text-[11px]" style="color:#3f3f46;">Aucune règle active</p>`
    return
  }

  container.innerHTML = rules.map(rule => {
    const t = TRIGGER_MAP[rule.trigger_type] || { label: rule.trigger_type, icon: '' }
    const val = rule.trigger_value
      ? `<span style="color:#38bdf8;font-family:'Geist Mono',monospace;font-size:11px;">${rule.trigger_value}</span>`
      : ''
    return `
      <div class="rule-row text-zinc-400" style="justify-content:space-between;">
        <div style="display:flex;align-items:center;gap:8px;">
          ${t.icon}
          <span>${t.label} ${val}</span>
        </div>
        <button class="btn-icon" style="width:20px;height:20px;" onclick="App.deleteRule(${rule.id})">
          <svg width="9" height="9" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/>
          </svg>
        </button>
      </div>`
  }).join('')
}

// ────────────────────────────────────────────────────────────
// COLONNE DROITE — STATS CATÉGORIE
// ────────────────────────────────────────────────────────────

function renderCategoryStats(stats) {
  const el = document.getElementById('cat-stats-detail')
  if (!el) return

  const winRate      = stats.win_rate     != null ? `<span style="color:#34d399;">${stats.win_rate}%</span>`  : `<span style="color:#3f3f46;">—</span>`
  const lastBroadcast = stats.last_broadcast
    ? relativeTime(stats.last_broadcast)
    : '—'

  el.innerHTML = `
    <div style="display:flex;justify-content:space-between;align-items:center;">
      <span class="text-[11px]" style="color:#52525b;">Win rate moyen</span>
      <span class="text-[11px] font-medium">${winRate}</span>
    </div>
    <div style="display:flex;justify-content:space-between;align-items:center;">
      <span class="text-[11px]" style="color:#52525b;">Actifs (7j)</span>
      <span class="text-[11px] font-medium text-zinc-300">${stats.active_7d ?? '—'}</span>
    </div>
    <div style="display:flex;justify-content:space-between;align-items:center;">
      <span class="text-[11px]" style="color:#52525b;">Multi-catégories</span>
      <span class="text-[11px] font-medium text-zinc-300">${stats.multi_categories ?? '—'}</span>
    </div>
    <div style="display:flex;justify-content:space-between;align-items:center;">
      <span class="text-[11px]" style="color:#52525b;">Dernière campagne</span>
      <span class="text-[11px]" style="color:#52525b;">${lastBroadcast}</span>
    </div>
  `
}

// ────────────────────────────────────────────────────────────
// COLONNE DROITE — INTERSECTIONS
// ────────────────────────────────────────────────────────────

function renderIntersections(intersections) {
  const el = document.getElementById('intersections-list')
  if (!el) return

  if (!intersections || intersections.length === 0) {
    el.innerHTML = `<p class="text-[11px]" style="color:#3f3f46;">Aucune intersection</p>`
    return
  }

  el.innerHTML = intersections.map(i => `
    <div style="display:flex;align-items:center;justify-content:space-between;">
      <div style="display:flex;align-items:center;gap:6px;">
        <span style="width:6px;height:6px;border-radius:50%;background:${i.color || '#52525b'};"></span>
        <span class="text-[11px] text-zinc-400">${i.name_categorie}</span>
      </div>
      <span class="text-[10px] tabular-nums" style="color:#52525b;">${i.shared_count}</span>
    </div>
  `).join('')
}

// ────────────────────────────────────────────────────────────
// DRAWER MEMBRE
// ────────────────────────────────────────────────────────────

function renderMemberDrawer(profile, allCategories) {
  const av   = document.getElementById('drawer-av')
  const name = document.getElementById('drawer-name')
  const sub  = document.getElementById('drawer-sub')

  if (av) {
    av.textContent = initials(profile.name || String(profile.telegram_id))
    av.className   = `av ${avColor(profile.telegram_id)}`
    av.style.cssText = 'width:44px;height:44px;font-size:14px;'
  }
  if (name) name.textContent = profile.name || '—'
  if (sub)  sub.textContent  = `${profile.phone || ''} · ID ${profile.telegram_id}`

  // Catégories actives
  const catContainer = document.getElementById('drawer-categories')
  if (catContainer) {
    catContainer.innerHTML = (profile.categories || []).map(c =>
      `<span class="badge" style="background:${c.color}22;color:${c.color};font-size:10px;">${c.name_categorie}</span>`
    ).join('') || '<span style="color:#3f3f46;font-size:11px;">Aucune catégorie</span>'
  }

  // Infos
  const infoEl = document.getElementById('drawer-info')
  if (infoEl) {
    const ts = profile.trading_stats
    infoEl.innerHTML = `
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
        <div>
          <p class="text-[10px] mb-1" style="color:#52525b;">Inscrit le</p>
          <p class="text-xs text-zinc-300">${profile.created_at
            ? new Date(profile.created_at).toLocaleDateString('fr-FR', { day:'numeric', month:'short', year:'numeric' })
            : '—'}</p>
        </div>
        <div>
          <p class="text-[10px] mb-1" style="color:#52525b;">Dernière activité</p>
          <p class="text-xs text-zinc-300">${relativeTime(profile.last_activity)}</p>
        </div>
        <div>
          <p class="text-[10px] mb-1" style="color:#52525b;">Trades</p>
          <p class="text-xs text-zinc-300">${ts?.total_trades ?? '—'}</p>
        </div>
        <div>
          <p class="text-[10px] mb-1" style="color:#52525b;">Win rate</p>
          <p class="text-xs" style="color:${ts?.win_rate ? '#34d399' : '#52525b'};">
            ${ts?.win_rate != null ? ts.win_rate + '%' : '—'}
          </p>
        </div>
      </div>
    `
  }

  // Select "ajouter à une catégorie"
  const sel = document.getElementById('drawer-add-cat')
  if (sel && allCategories) {
    const memberCatNames = (profile.categories || []).map(c => c.name_categorie)
    sel.innerHTML = `<option value="">Ajouter à une catégorie...</option>` +
      allCategories
        .filter(c => !memberCatNames.includes(c.name_categorie))
        .map(c => `<option value="${c.name_categorie}">${c.name_categorie}</option>`)
        .join('')
  }
}

// ────────────────────────────────────────────────────────────
// MODAL EDIT — pré-remplissage
// ────────────────────────────────────────────────────────────

function renderEditModal(cat) {
  const inp = document.getElementById('edit-name-input')
  if (inp) inp.value = cat.name_categorie

  // Color dots
  document.querySelectorAll('#modal-edit .color-dot').forEach(dot => {
    dot.classList.toggle('selected', dot.style.background === cat.color)
  })
}

// ────────────────────────────────────────────────────────────
// MODAL MERGE — liste des catégories sources
// ────────────────────────────────────────────────────────────

function renderMergeModal(currentName, categories) {
  const title = document.getElementById('merge-title')
  if (title) title.textContent = `Fusionner dans "${currentName}"`

  const list = document.getElementById('merge-sources-list')
  if (!list) return

  list.innerHTML = categories
    .filter(c => c.name_categorie !== currentName)
    .map(c => `
      <label style="display:flex;align-items:center;gap:10px;font-size:12px;color:#a1a1aa;cursor:pointer;">
        <input type="checkbox" value="${c.name_categorie}" style="accent-color:#38bdf8;">
        ${c.name_categorie} (${(c.member_count ?? 0).toLocaleString('fr-FR')})
      </label>`)
    .join('')
}

// ────────────────────────────────────────────────────────────
// MODAL MOVE — liste destinations
// ────────────────────────────────────────────────────────────

function renderMoveModal(currentName, categories) {
  const sel = document.getElementById('move-destination-select')
  if (!sel) return

  sel.innerHTML = categories
    .filter(c => c.name_categorie !== currentName)
    .map(c => `<option value="${c.name_categorie}">${c.name_categorie} (${(c.member_count ?? 0).toLocaleString('fr-FR')})</option>`)
    .join('')
}

// ────────────────────────────────────────────────────────────
// MODAL DELETE — texte dynamique
// ────────────────────────────────────────────────────────────

function renderDeleteModal(cat) {
  const title = document.getElementById('delete-modal-title')
  const desc  = document.getElementById('delete-modal-desc')
  if (title) title.textContent = `Supprimer "${cat.name_categorie}" ?`
  if (desc)  desc.textContent  =
    `Les ${(cat.member_count ?? 0).toLocaleString('fr-FR')} membres ne seront pas supprimés — ils perdront uniquement ce tag. Cette action est irréversible.`
}

// ────────────────────────────────────────────────────────────
// MODAL BROADCAST — titre dynamique
// ────────────────────────────────────────────────────────────

function renderBroadcastModal(cat) {
  const sub = document.getElementById('broadcast-modal-sub')
  if (sub) sub.textContent = `Vers : ${cat.name_categorie} · ${(cat.member_count ?? 0).toLocaleString('fr-FR')} membres`
}

// ────────────────────────────────────────────────────────────
// MODAL IMPORT — select catégories
// ────────────────────────────────────────────────────────────

function renderImportModal(categories) {
  const sel = document.getElementById('import-category-select')
  if (!sel) return
  sel.innerHTML = categories
    .map(c => `<option value="${c.name_categorie}">${c.name_categorie} (${c.member_count})</option>`)
    .join('') + `<option value="__new__">+ Créer une nouvelle catégorie</option>`
}

// ────────────────────────────────────────────────────────────
// TOAST notifications légères
// ────────────────────────────────────────────────────────────

function toast(message, type = 'success') {
  const existing = document.getElementById('app-toast')
  if (existing) existing.remove()

  const colors = {
    success: { bg: 'rgba(52,211,153,.1)',  border: 'rgba(52,211,153,.25)',  text: '#34d399' },
    error:   { bg: 'rgba(248,113,113,.1)', border: 'rgba(248,113,113,.25)', text: '#f87171' },
    info:    { bg: 'rgba(56,189,248,.1)',  border: 'rgba(56,189,248,.25)',  text: '#38bdf8' },
  }
  const c = colors[type] || colors.info

  const el = document.createElement('div')
  el.id = 'app-toast'
  el.style.cssText = `
    position:fixed;bottom:24px;right:24px;z-index:9999;
    padding:10px 16px;border-radius:10px;font-size:12px;font-family:'Geist',sans-serif;
    background:${c.bg};border:1px solid ${c.border};color:${c.text};
    animation:fadein .2s ease;pointer-events:none;max-width:320px;
  `
  el.textContent = message
  document.body.appendChild(el)
  setTimeout(() => el.remove(), 3000)
}

// ── Loading skeleton ─────────────────────────────────────────
function renderSkeleton(containerId, rows = 4) {
  const el = document.getElementById(containerId)
  if (!el) return
  el.innerHTML = Array(rows).fill(`
    <div style="padding:10px 16px;border-bottom:1px solid rgba(255,255,255,.04);">
      <div style="height:10px;width:60%;background:rgba(255,255,255,.05);border-radius:4px;margin-bottom:6px;"></div>
      <div style="height:8px;width:40%;background:rgba(255,255,255,.03);border-radius:4px;"></div>
    </div>`).join('')
}