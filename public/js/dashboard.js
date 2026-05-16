tailwind.config = {
  theme: {
    extend: {
      fontFamily: { sans: ['Geist', 'sans-serif'] },
    }
  }
}

document.addEventListener('DOMContentLoaded', () => {

  // Date courante
  const dateEl = document.getElementById('current-date')
  if (dateEl) {
    const d = new Date()
    const opts = { weekday: 'short', day: 'numeric', month: 'long', year: 'numeric' }
    dateEl.textContent = d.toLocaleDateString('fr-FR', opts)
  }

  // Notifications dropdown
  const bell    = document.getElementById('bell-btn')
  const panel   = document.getElementById('notif-panel')
  const clear   = document.getElementById('notif-clear')
  const wrapper = document.getElementById('notif-wrapper')

  if (bell && panel) {
    bell.addEventListener('click', e => {
      e.stopPropagation()
      panel.style.display = panel.style.display === 'none' ? 'block' : 'none'
    })
  }

  if (wrapper && panel) {
    document.addEventListener('click', e => {
      if (!wrapper.contains(e.target)) {
        panel.style.display = 'none'
      }
    })
  }

  if (clear && panel && bell) {
    clear.addEventListener('click', () => {
      panel.style.display = 'none'
      const badge = bell.querySelector('span')
      if (badge) badge.style.display = 'none'
    })
  }

  // Sidebar drawer (mobile)
  const hamburger = document.getElementById('hamburger')
  const sidebar   = document.getElementById('sidebar')
  const overlay   = document.getElementById('sidebar-overlay')

  function openSidebar() {
    if (sidebar) sidebar.classList.add('open')
    if (overlay) overlay.classList.add('open')
    document.body.style.overflow = 'hidden'
  }

  function closeSidebar() {
    if (sidebar) sidebar.classList.remove('open')
    if (overlay) overlay.classList.remove('open')
    document.body.style.overflow = ''
  }

  if (hamburger) {
    hamburger.addEventListener('click', e => {
      e.stopPropagation()
      openSidebar()
    })
  }

  if (overlay) {
    overlay.addEventListener('click', closeSidebar)
  }

  // Close sidebar when a nav item is clicked on mobile
  document.querySelectorAll('.nav-item').forEach(btn => {
    btn.addEventListener('click', () => {
      if (window.innerWidth <= 900) closeSidebar()
    })
  })

})