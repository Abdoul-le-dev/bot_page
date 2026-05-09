/* ═══════════════════════════════════════════════════════════════
   categories.app.js  —  orchestration, state global, events
   Dépend de : categories.api.js + categories.render.js
   ═══════════════════════════════════════════════════════════════ */

const App = (() => {

  // ── State ─────────────────────────────────────────────────
  const State = {
    categories:      [],     // liste complète des catégories
    selected:        null,   // catégorie active (objet complet)
    members:         [],     // membres chargés
    membersTotal:    0,
    selectedMembers: [],     // telegram_ids cochés
    filters: {
      search:       '',
      tab:          'all',   // all | active | inactive
      offset:       0,
      limit:        50
    },
    currentMemberProfile: null
  }

  // ────────────────────────────────────────────────────────
  // INIT
  // ────────────────────────────────────────────────────────

  async function init() {
    await loadGlobalStats()
    await loadCategories()
    bindModalActions()
    bindFilterEvents()
  }

  // ────────────────────────────────────────────────────────
  // CHARGEMENT DONNÉES
  // ────────────────────────────────────────────────────────

  async function loadGlobalStats() {
    try {
      const stats = await apiGetStats()
      renderGlobalStats(stats)
    } catch (e) {
      console.error('[Stats]', e.message)
    }
  }

  async function loadCategories() {
    renderSkeleton('cat-list', 5)
    try {
      State.categories = await apiGetCategories()
      renderCatList(State.categories)

      // Sélectionner la première par défaut
      if (State.categories.length > 0 && !State.selected) {
        const firstCard = document.querySelector('.cat-card')
        if (firstCard) selectCat(firstCard)
      }
    } catch (e) {
      toast('Erreur chargement catégories', 'error')
      console.error(e)
    }
  }

  async function loadMembers() {
    if (!State.selected) return
    renderSkeleton('members-list', 5)

    const filters = {
      search:        State.filters.search   || undefined,
      active_only:   State.filters.tab === 'active',
      inactive_only: State.filters.tab === 'inactive',
      limit:         State.filters.limit,
      offset:        State.filters.offset
    }

    try {
      const data = await apiGetMembers(State.selected.name_categorie, filters)
      State.members      = data.members
      State.membersTotal = data.total
      renderMembersList(data)
    } catch (e) {
      toast('Erreur chargement membres', 'error')
    }
  }

  async function loadRightPanel() {
    if (!State.selected) return
    const name = State.selected.name_categorie

    // Règles
    try {
      const rules = await apiGetRules(name)
      renderRules(rules)
    } catch (e) { console.error('[Rules]', e) }

    // Stats catégorie
    try {
      const stats = await apiGetCategoryStats(name)
      renderCategoryStats(stats)
    } catch (e) { console.error('[CatStats]', e) }

    // Intersections
    try {
      const inter = await apiGetIntersections(name)
      renderIntersections(inter)
    } catch (e) { console.error('[Intersections]', e) }
  }

  // ────────────────────────────────────────────────────────
  // SÉLECTION CATÉGORIE
  // ────────────────────────────────────────────────────────

  function selectCat(cardEl) {
    const name = cardEl.dataset.name
    State.selected = State.categories.find(c => c.name_categorie === name) || null
    if (!State.selected) return

    // UI highlight
    document.querySelectorAll('.cat-card').forEach(c => c.classList.remove('selected'))
    cardEl.classList.add('selected')

    // Header détail
    renderDetailHeader(State.selected)
    renderBroadcastModal(State.selected)

    // Reset filtres membres
    State.filters.offset        = 0
    State.filters.search        = ''
    State.selectedMembers       = []

    // Charger membres + panneau droit
    loadMembers()
    loadRightPanel()

    // Mobile : passer en vue détail
    if (window.innerWidth <= 700) {
      document.getElementById('cat-left-panel')?.classList.add('hidden-mobile')
      document.getElementById('cat-detail-col')?.classList.add('visible-mobile')
    }
  }

  // ────────────────────────────────────────────────────────
  // FILTRES MEMBRES
  // ────────────────────────────────────────────────────────

  function bindFilterEvents() {
    // Recherche dans les membres
    const memberSearch = document.getElementById('member-search-input')
    if (memberSearch) {
      let debounce
      memberSearch.addEventListener('input', e => {
        clearTimeout(debounce)
        debounce = setTimeout(() => {
          State.filters.search = e.target.value.trim()
          State.filters.offset = 0
          loadMembers()
        }, 350)
      })
    }

    // Export CSV
    const exportBtn = document.getElementById('export-csv-btn')
    if (exportBtn) exportBtn.addEventListener('click', exportCSV)
  }

  function switchTab(el) {
    el.closest('div').querySelectorAll('.tab').forEach(t => t.classList.remove('active'))
    el.classList.add('active')
    State.filters.tab    = el.dataset.tab || 'all'
    State.filters.offset = 0
    loadMembers()
  }

  function loadMoreMembers() {
    State.filters.offset += State.filters.limit
    loadMembers()
  }

  // ────────────────────────────────────────────────────────
  // SÉLECTION MEMBRES (checkboxes)
  // ────────────────────────────────────────────────────────

  function toggleSelect(telegramId, checked) {
    if (checked) {
      if (!State.selectedMembers.includes(telegramId))
        State.selectedMembers.push(telegramId)
    } else {
      State.selectedMembers = State.selectedMembers.filter(id => id !== telegramId)
    }

    // Mettre à jour le compteur dans modal-move
    const scopeLabel = document.querySelector('[data-scope="selected"]')
    if (scopeLabel) scopeLabel.textContent = `Sélectionnés (${State.selectedMembers.length})`
  }

  // ────────────────────────────────────────────────────────
  // ACTIONS MEMBRES
  // ────────────────────────────────────────────────────────

  async function removeMember(telegramId) {
    if (!State.selected) return
    if (!confirm('Retirer ce membre de la catégorie ?')) return
    try {
      await apiRemoveMember(State.selected.name_categorie, telegramId)
      toast('Membre retiré')
      await loadMembers()
      await loadGlobalStats()
    } catch (e) {
      toast(e.message, 'error')
    }
  }

  async function openMemberDrawer(telegramId) {
    try {
      const profile = await apiGetMemberProfile(telegramId)
      State.currentMemberProfile = profile
      renderMemberDrawer(profile, State.categories)
      openDrawer('member-drawer')
    } catch (e) {
      toast('Impossible de charger le profil', 'error')
    }
  }

  // Ajouter membre à une catégorie depuis le drawer
  async function addMemberToCategory() {
    const sel = document.getElementById('drawer-add-cat')
    const name = sel?.value
    if (!name || name === '' || !State.currentMemberProfile) return

    try {
      await apiAddMembers(name, [State.currentMemberProfile.telegram_id])
      toast(`Ajouté à ${name}`)
      // Rafraîchir le profil
      const updated = await apiGetMemberProfile(State.currentMemberProfile.telegram_id)
      renderMemberDrawer(updated, State.categories)
      State.currentMemberProfile = updated
      // Rafraîchir liste si c'est la catégorie active
      if (State.selected?.name_categorie === name) loadMembers()
    } catch (e) {
      toast(e.message, 'error')
    }
  }

  // Retirer membre depuis le drawer
  async function removeMemberFromDrawer() {
    if (!State.selected || !State.currentMemberProfile) return
    if (!confirm('Retirer ce membre de la catégorie actuelle ?')) return
    try {
      await apiRemoveMember(State.selected.name_categorie, State.currentMemberProfile.telegram_id)
      toast('Membre retiré')
      closeDrawer('member-drawer')
      loadMembers()
    } catch (e) {
      toast(e.message, 'error')
    }
  }

  // Export CSV côté client
  function exportCSV() {
    if (!State.members.length) return
    const rows   = [['telegram_id', 'name', 'phone', 'last_activity']]
    State.members.forEach(m => rows.push([m.telegram_id, m.name || '', m.phone || '', m.last_activity || '']))
    const csv    = rows.map(r => r.join(',')).join('\n')
    const blob   = new Blob([csv], { type: 'text/csv' })
    const url    = URL.createObjectURL(blob)
    const a      = document.createElement('a')
    a.href       = url
    a.download   = `${State.selected?.name_categorie || 'membres'}.csv`
    a.click()
    URL.revokeObjectURL(url)
  }

  // ────────────────────────────────────────────────────────
  // MODAL ACTIONS
  // ────────────────────────────────────────────────────────

  function bindModalActions() {

    // ── Créer catégorie ──────────────────────────────────
    document.getElementById('btn-create-confirm')?.addEventListener('click', async () => {
      const name  = document.getElementById('create-name-input')?.value.trim()
      const desc  = document.getElementById('create-desc-input')?.value.trim()
      const color = document.querySelector('#modal-create .color-dot.selected')?.style.background || '#38bdf8'
      const ruleType  = document.getElementById('create-rule-type')?.value
      const ruleValue = document.getElementById('create-rule-value')?.value.trim()
      const idsRaw    = document.getElementById('create-ids-input')?.value.trim()

      if (!name) { toast('Nom requis', 'error'); return }

      const payload = { name_categorie: name, color, description: desc }
      if (ruleType)  payload.rule       = { trigger_type: ruleType, trigger_value: ruleValue }
      if (idsRaw)    payload.member_ids = parseIds(idsRaw)

      try {
        await apiCreateCategory(payload)
        toast(`Catégorie "${name}" créée`)
        closeModal('modal-create')
        await loadCategories()
        await loadGlobalStats()
      } catch (e) { toast(e.message, 'error') }
    })

    // ── Éditer catégorie ─────────────────────────────────
    document.getElementById('btn-edit-confirm')?.addEventListener('click', async () => {
      if (!State.selected) return
      const newName = document.getElementById('edit-name-input')?.value.trim()
      const color   = document.querySelector('#modal-edit .color-dot.selected')?.style.background

      const payload = {}
      if (newName && newName !== State.selected.name_categorie) payload.new_name = newName
      if (color)   payload.color = color

      if (!Object.keys(payload).length) { closeModal('modal-edit'); return }

      try {
        const res = await apiUpdateCategory(State.selected.name_categorie, payload)
        if (res.status === 'error') { toast(res.detail, 'error'); return }
        toast('Catégorie mise à jour')
        closeModal('modal-edit')
        await loadCategories()
        await loadGlobalStats()
      } catch (e) { toast(e.message, 'error') }
    })

    // ── Ajouter IDs ──────────────────────────────────────
    document.getElementById('btn-add-ids-confirm')?.addEventListener('click', async () => {
      if (!State.selected) return
      const raw = document.getElementById('add-ids-textarea')?.value.trim()
      if (!raw) return
      const ids = parseIds(raw)
      if (!ids.length) { toast('Aucun ID valide', 'error'); return }

      try {
        const res = await apiAddMembers(State.selected.name_categorie, ids)
        toast(`${res.added} ajoutés · ${res.ignored} ignorés`)
        closeModal('modal-add-ids')
        await loadMembers()
        await loadGlobalStats()
        await loadCategories()
      } catch (e) { toast(e.message, 'error') }
    })

    // ── Importer CSV ─────────────────────────────────────
    document.getElementById('btn-import-confirm')?.addEventListener('click', async () => {
      const file    = document.getElementById('import-file-input')?.files?.[0]
      const catName = document.getElementById('import-category-select')?.value
      if (!file)    { toast('Fichier requis', 'error'); return }
      if (!catName) { toast('Catégorie requise', 'error'); return }

      try {
        const res = await apiImportCSV(catName, file)
        toast(`Import terminé : ${res.added} ajoutés`)
        closeModal('modal-import')
        await loadCategories()
        await loadGlobalStats()
        if (State.selected?.name_categorie === catName) loadMembers()
      } catch (e) { toast(e.message, 'error') }
    })

    // ── Déplacer membres ─────────────────────────────────
    document.getElementById('btn-move-confirm')?.addEventListener('click', async () => {
      if (!State.selected) return
      const dest   = document.getElementById('move-destination-select')?.value
      const scope  = document.querySelector('input[name="move-scope"]:checked')?.value
      const action = document.querySelector('input[name="move-action"]:checked')?.value || 'copy'
      if (!dest) { toast('Destination requise', 'error'); return }

      const ids = scope === 'all' ? 'all' : State.selectedMembers
      if (ids !== 'all' && ids.length === 0) { toast('Aucun membre sélectionné', 'error'); return }

      try {
        const res = await apiMoveMembers(State.selected.name_categorie, dest, ids, action)
        toast(`${res.count} membres ${action === 'move' ? 'déplacés' : 'copiés'} vers ${dest}`)
        closeModal('modal-move')
        State.selectedMembers = []
        await loadMembers()
        await loadCategories()
      } catch (e) { toast(e.message, 'error') }
    })

    // ── Fusionner catégories ─────────────────────────────
    document.getElementById('btn-merge-confirm')?.addEventListener('click', async () => {
      if (!State.selected) return
      const checked = [...document.querySelectorAll('#merge-sources-list input:checked')]
        .map(cb => cb.value)
      if (!checked.length) { toast('Sélectionnez au moins une source', 'error'); return }

      try {
        await apiMergeCategories(State.selected.name_categorie, checked)
        toast(`Fusion terminée dans "${State.selected.name_categorie}"`)
        closeModal('modal-merge')
        await loadCategories()
        await loadGlobalStats()
        loadMembers()
      } catch (e) { toast(e.message, 'error') }
    })

    // ── Supprimer catégorie ──────────────────────────────
    document.getElementById('btn-delete-confirm')?.addEventListener('click', async () => {
      if (!State.selected) return
      try {
        await apiDeleteCategory(State.selected.name_categorie)
        toast(`"${State.selected.name_categorie}" supprimée`)
        closeModal('modal-delete')
        State.selected = null
        await loadCategories()
        await loadGlobalStats()
      } catch (e) { toast(e.message, 'error') }
    })

    // ── Nouvelle règle ───────────────────────────────────
    document.getElementById('btn-rule-confirm')?.addEventListener('click', async () => {
      if (!State.selected) return
      const type  = document.getElementById('rule-trigger-type')?.value
      const value = document.getElementById('rule-trigger-value')?.value.trim()
      if (!type) { toast('Déclencheur requis', 'error'); return }

      try {
        await apiAddRule(State.selected.name_categorie, type, value)
        toast('Règle ajoutée')
        closeModal('modal-rule')
        const rules = await apiGetRules(State.selected.name_categorie)
        renderRules(rules)
      } catch (e) { toast(e.message, 'error') }
    })

    // ── Broadcast rapide ─────────────────────────────────
    document.getElementById('btn-broadcast-confirm')?.addEventListener('click', async () => {
      if (!State.selected) return
      const message = document.getElementById('broadcast-message-input')?.value.trim()
      const format  = document.getElementById('broadcast-format-select')?.value || 'text'
      if (!message) { toast('Message requis', 'error'); return }

      try {
        await apiBroadcast({
          message,
          format,
          category:  State.selected.name_categorie,
          delay:     0.1,
          retry:     true
        })
        toast('Broadcast lancé ✓', 'success')
        closeModal('modal-broadcast')
      } catch (e) { toast(e.message, 'error') }
    })

    // ── Drawer : ajouter à catégorie ────────────────────
    document.getElementById('btn-drawer-add-cat')?.addEventListener('click', addMemberToCategory)

    // ── Drawer : retirer de la catégorie ────────────────
    document.getElementById('btn-drawer-remove')?.addEventListener('click', removeMemberFromDrawer)

    // ── Drag & drop zone membres ─────────────────────────
    const membersList = document.getElementById('members-list')
    if (membersList) {
      membersList.addEventListener('drop', async e => {
        e.preventDefault()
        membersList.classList.remove('drag-over')
        const raw = e.dataTransfer.getData('text/plain')
        if (!raw || !State.selected) return
        const ids = parseIds(raw)
        if (!ids.length) return
        try {
          const res = await apiAddMembers(State.selected.name_categorie, ids)
          toast(`${res.added} membre(s) ajouté(s)`)
          loadMembers()
        } catch (err) { toast(err.message, 'error') }
      })
    }
  }

  // ────────────────────────────────────────────────────────
  // OPEN EDIT MODAL (pré-remplissage)
  // ────────────────────────────────────────────────────────

  function openEditModal(name) {
    const cat = State.categories.find(c => c.name_categorie === name)
    if (cat) renderEditModal(cat)
    openModal('modal-edit')
  }

  // ────────────────────────────────────────────────────────
  // OPEN MODALS avec contexte
  // ────────────────────────────────────────────────────────

  function openMergeModal() {
    if (!State.selected) return
    renderMergeModal(State.selected.name_categorie, State.categories)
    openModal('modal-merge')
  }

  function openMoveModal() {
    if (!State.selected) return
    renderMoveModal(State.selected.name_categorie, State.categories)
    openModal('modal-move')
  }

  function openDeleteModal() {
    if (!State.selected) return
    renderDeleteModal(State.selected)
    openModal('modal-delete')
  }

  function openImportModal() {
    renderImportModal(State.categories)
    openModal('modal-import')
  }

  function openAddIdsModal() {
    const sub = document.getElementById('add-ids-modal-sub')
    if (sub && State.selected) sub.textContent = `Catégorie : ${State.selected.name_categorie}`
    openModal('modal-add-ids')
  }

  function openBroadcastModal() {
    if (State.selected) renderBroadcastModal(State.selected)
    openModal('modal-broadcast')
  }

  // ────────────────────────────────────────────────────────
  // DELETE RULE
  // ────────────────────────────────────────────────────────

  async function deleteRule(ruleId) {
    if (!confirm('Supprimer cette règle ?')) return
    try {
      await apiDeleteRule(ruleId)
      toast('Règle supprimée')
      const rules = await apiGetRules(State.selected.name_categorie)
      renderRules(rules)
    } catch (e) { toast(e.message, 'error') }
  }

  // ────────────────────────────────────────────────────────
  // HELPER — parse IDs depuis textarea
  // ────────────────────────────────────────────────────────

  function parseIds(raw) {
    return raw
      .split(/[\n,]+/)
      .map(s => parseInt(s.trim(), 10))
      .filter(n => !isNaN(n) && n > 0)
  }

  // ────────────────────────────────────────────────────────
  // EXPOSE API PUBLIQUE
  // ────────────────────────────────────────────────────────

  return {
    init,
    selectCat,
    switchTab,
    toggleSelect,
    loadMoreMembers,
    removeMember,
    openMemberDrawer,
    openEditModal,
    openMergeModal,
    openMoveModal,
    openDeleteModal,
    openImportModal,
    openAddIdsModal,
    openBroadcastModal,
    deleteRule,
    exportCSV,
    addMemberToCategory,
    removeMemberFromDrawer,
    getSelected: () => State.selected,
    getSelectedMembers: () => State.selectedMembers
  }

})()

// ── Boot ────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => App.init())