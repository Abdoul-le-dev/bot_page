// resources/js/dashboard.js
// ── JS natif minimal — aucun framework, aucune abstraction inutile ──────

document.addEventListener('DOMContentLoaded', () => {
    initNotifications()
    initAutoClose()
    initCharts()
})

// ── Notifications dropdown ────────────────────────────────────────────
function initNotifications() {
    const bell  = document.getElementById('bell-btn')
    const panel = document.getElementById('notif-panel')
    if (!bell || !panel) return

    bell.addEventListener('click', (e) => {
        e.stopPropagation()
        panel.classList.toggle('hidden')
    })

    document.addEventListener('click', () => panel.classList.add('hidden'))
    panel.addEventListener('click', (e) => e.stopPropagation())

    // Marquer tout lu
    document.getElementById('notif-clear')?.addEventListener('click', () => {
        document.querySelectorAll('.notif-dot').forEach(d => d.remove())
        document.getElementById('bell-dot')?.remove()
        panel.classList.add('hidden')
    })
}

// ── Flash messages auto-close ─────────────────────────────────────────
function initAutoClose() {
    document.querySelectorAll('[data-auto-close]').forEach(el => {
        setTimeout(() => el.remove(), parseInt(el.dataset.autoClose) || 4000)
    })
}

// ── Charts (Chart.js — chargé via CDN dans le layout si besoin) ───────
function initCharts() {
    if (typeof Chart === 'undefined') return

    // Couleurs communes
    const grid  = 'rgba(255,255,255,0.04)'
    const tick  = { color: '#52525b', font: { size: 11, family: 'Instrument Sans' } }
    const base  = { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }

    // ── Graphique croissance (page stats) ─────────────────────────────
    const ctxGrowth = document.getElementById('chart-growth')
    if (ctxGrowth) {
        const labels = ctxGrowth.dataset.labels ? JSON.parse(ctxGrowth.dataset.labels) : []
        const news   = ctxGrowth.dataset.new    ? JSON.parse(ctxGrowth.dataset.new)    : []
        const active = ctxGrowth.dataset.active ? JSON.parse(ctxGrowth.dataset.active) : []

        new Chart(ctxGrowth, {
            data: {
                labels,
                datasets: [
                    {
                        type: 'bar',
                        label: 'Nouveaux',
                        data: news,
                        backgroundColor: 'rgba(14,165,233,0.25)',
                        borderColor: 'rgba(14,165,233,0.6)',
                        borderWidth: 1,
                        borderRadius: 3,
                    },
                    {
                        type: 'line',
                        label: 'Actifs',
                        data: active,
                        yAxisID: 'y2',
                        borderColor: 'rgba(52,211,153,0.7)',
                        pointBackgroundColor: 'rgba(52,211,153,0.8)',
                        pointRadius: 3,
                        tension: 0.35,
                        borderWidth: 1.5,
                        fill: false,
                    }
                ]
            },
            options: {
                ...base,
                scales: {
                    x:  { ticks: tick, grid: { display: false } },
                    y:  { ticks: tick, grid: { color: grid }, beginAtZero: true },
                    y2: { position: 'right', ticks: tick, grid: { display: false } },
                }
            }
        })
    }

    // ── Donut segments (page stats) ────────────────────────────────────
    const ctxSeg = document.getElementById('chart-segments')
    if (ctxSeg) {
        const labels = JSON.parse(ctxSeg.dataset.labels || '[]')
        const values = JSON.parse(ctxSeg.dataset.values || '[]')

        new Chart(ctxSeg, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data: values,
                    backgroundColor: [
                        'rgba(52,211,153,0.75)',
                        'rgba(14,165,233,0.75)',
                        'rgba(251,191,36,0.75)',
                        'rgba(161,161,170,0.45)',
                    ],
                    borderWidth: 0,
                    hoverOffset: 4,
                }]
            },
            options: { ...base, cutout: '68%' }
        })
    }

    // ── Barres messages (page stats) ───────────────────────────────────
    const ctxMsg = document.getElementById('chart-messages')
    if (ctxMsg) {
        const labels = JSON.parse(ctxMsg.dataset.labels || '[]')
        const sent   = JSON.parse(ctxMsg.dataset.sent   || '[]')
        const seen   = JSON.parse(ctxMsg.dataset.seen   || '[]')
        const clicks = JSON.parse(ctxMsg.dataset.clicks || '[]')

        new Chart(ctxMsg, {
            data: {
                labels,
                datasets: [
                    { type: 'bar',  label: 'Envoyés', data: sent,   backgroundColor: 'rgba(14,165,233,0.2)',  borderColor: 'rgba(14,165,233,0.5)',  borderWidth: 1, borderRadius: 3 },
                    { type: 'bar',  label: 'Vus',     data: seen,   backgroundColor: 'rgba(52,211,153,0.25)', borderColor: 'rgba(52,211,153,0.6)',  borderWidth: 1, borderRadius: 3 },
                    { type: 'line', label: 'Clics',   data: clicks, borderColor: 'rgba(251,191,36,0.7)', pointRadius: 3, tension: 0.3, borderWidth: 1.5, fill: false, borderDash: [4,3] },
                ]
            },
            options: {
                ...base,
                scales: {
                    x: { ticks: tick, grid: { display: false } },
                    y: { ticks: tick, grid: { color: grid }, beginAtZero: true },
                }
            }
        })
    }
}

// ── Helpers globaux ───────────────────────────────────────────────────

// Confirmation suppression
window.confirmDelete = (message = 'Confirmer la suppression ?') => confirm(message)

// Copier dans le presse-papier
window.copyToClipboard = (text, btn) => {
    navigator.clipboard.writeText(text).then(() => {
        const original = btn.textContent
        btn.textContent = 'Copié !'
        setTimeout(() => btn.textContent = original, 1500)
    })
}
