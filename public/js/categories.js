/* ═══════════════════════════════════════════════════════════════
   categories.js  —  logique JS spécifique à la page Catégories
   ═══════════════════════════════════════════════════════════════ */

// ── Sidebar mobile (dashboard.js gère déjà ça en général,
//    on le redéfinit ici pour autonomie de la page) ──────────────
function openSidebar() {
  document.getElementById('sidebar')?.classList.add('open')
  document.getElementById('sidebar-overlay')?.classList.add('open')
}
function closeSidebar() {
  document.getElementById('sidebar')?.classList.remove('open')
  document.getElementById('sidebar-overlay')?.classList.remove('open')
}

// ── Sélection catégorie ───────────────────────────────────────
function selectCat(idx, el, name, color, count, meta) {
  document.querySelectorAll('.cat-card').forEach(c => c.classList.remove('selected'))
  el.classList.add('selected')

  document.getElementById('detail-dot').style.background = color
  document.getElementById('detail-name').textContent     = name
  document.getElementById('detail-meta').textContent     = count + ' membres · ' + meta

  // Mobile : passer en vue détail
  if (window.innerWidth <= 700) {
    document.getElementById('cat-left-panel').classList.add('hidden-mobile')
    document.getElementById('cat-detail-col').classList.add('visible-mobile')
  }
}

// ── Retour à la liste (mobile) ────────────────────────────────
function showList() {
  document.getElementById('cat-left-panel').classList.remove('hidden-mobile')
  document.getElementById('cat-detail-col').classList.remove('visible-mobile')
}

// ── Filtrer catégories (topbar search) ───────────────────────
function filterCats(q) {
  document.querySelectorAll('.cat-card').forEach(c => {
    const name = (c.dataset.name || '').toLowerCase()
    c.style.display = name.includes(q.toLowerCase()) ? '' : 'none'
  })
}

// ── Tabs membres ──────────────────────────────────────────────
function switchTab(el) {
  el.closest('div').querySelectorAll('.tab').forEach(t => t.classList.remove('active'))
  el.classList.add('active')
}

// ── Color picker ──────────────────────────────────────────────
function selectColor(el) {
  el.closest('div').querySelectorAll('.color-dot').forEach(d => d.classList.remove('selected'))
  el.classList.add('selected')
}

// ── Drag & drop ───────────────────────────────────────────────
function dropMember(e) {
  e.preventDefault()
  document.getElementById('members-list')?.classList.remove('drag-over')
  // Brancher sur POST /api/categories/{id}/members
}

// ── Drawer membre ─────────────────────────────────────────────
function openDrawer(initials, name, sub, avClass) {
  const av = document.getElementById('drawer-av')
  if (av) {
    av.textContent  = initials
    av.className    = 'av ' + avClass
    av.style.cssText = 'width:44px;height:44px;font-size:14px;'
  }
  const drawerName = document.getElementById('drawer-name')
  const drawerSub  = document.getElementById('drawer-sub')
  if (drawerName) drawerName.textContent = name
  if (drawerSub)  drawerSub.textContent  = sub

  document.getElementById('member-drawer')?.classList.add('open')
  document.getElementById('drawer-overlay')?.classList.add('open')
}
function closeDrawer() {
  document.getElementById('member-drawer')?.classList.remove('open')
  document.getElementById('drawer-overlay')?.classList.remove('open')
}

// ── Modals ────────────────────────────────────────────────────
function openModal(id)  { document.getElementById(id)?.classList.add('open') }
function closeModal(id) { document.getElementById(id)?.classList.remove('open') }

document.addEventListener('DOMContentLoaded', () => {
  // Fermer modals en cliquant overlay
  document.querySelectorAll('.modal-overlay').forEach(o => {
    o.addEventListener('click', e => { if (e.target === o) o.classList.remove('open') })
  })

  // Échap ferme tout
  document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return
    closeDrawer()
    closeSidebar()
    document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('open'))
  })

  // Resize : reset mobile states
  window.addEventListener('resize', () => {
    if (window.innerWidth > 700) {
      document.getElementById('cat-left-panel')?.classList.remove('hidden-mobile')
      document.getElementById('cat-detail-col')?.classList.remove('visible-mobile')
    }
    if (window.innerWidth > 900) closeSidebar()
  })
})