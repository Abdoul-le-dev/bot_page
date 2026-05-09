tailwind.config = {
  theme: {
    extend: {
      fontFamily: { sans: ['Geist', 'sans-serif'] },
    }
  }
}

// Date courante
  const d = new Date()
  const opts = { weekday:'short', day:'numeric', month:'long', year:'numeric' }
  document.getElementById('current-date').textContent = d.toLocaleDateString('fr-FR', opts)

  // Notifications dropdown
  const bell  = document.getElementById('bell-btn')
  const panel = document.getElementById('notif-panel')
  const clear = document.getElementById('notif-clear')

  bell.addEventListener('click', e => {
    e.stopPropagation()
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none'
  })
  document.addEventListener('click', e => {
    if (!document.getElementById('notif-wrapper').contains(e.target))
      panel.style.display = 'none'
  })
  clear.addEventListener('click', () => {
    panel.style.display = 'none'
    bell.querySelector('span').style.display = 'none'
  })

  // Sidebar drawer (mobile)
  const hamburger = document.getElementById('hamburger')
  const sidebar   = document.getElementById('sidebar')
  const overlay   = document.getElementById('sidebar-overlay')

  function openSidebar() {
    sidebar.classList.add('open')
    overlay.classList.add('open')
    document.body.style.overflow = 'hidden'
  }
  function closeSidebar() {
    sidebar.classList.remove('open')
    overlay.classList.remove('open')
    document.body.style.overflow = ''
  }

  hamburger.addEventListener('click', e => { e.stopPropagation(); openSidebar() })
  overlay.addEventListener('click', closeSidebar)

  // Close sidebar when a nav item is clicked on mobile
  document.querySelectorAll('.nav-item').forEach(btn => {
    btn.addEventListener('click', () => {
      if (window.innerWidth <= 900) closeSidebar()
    })
  })