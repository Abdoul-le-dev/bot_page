/* ═══════════════════════════════════════════════════════════════
   categories.api.js  —  couche API, tous les fetch serveur
   Base URL : http://54.226.165.244:8000
   ═══════════════════════════════════════════════════════════════ */

const API_URL = 'https://fdkvip.com'

// ── Helper fetch ─────────────────────────────────────────────
async function apiFetch(path, options = {}) {
  const res = await fetch(API_URL + path, {
    headers: { 'Content-Type': 'application/json', ...(options.headers || {}) },
    ...options
  })
  if (!res.ok) {
    const err = await res.json().catch(() => ({ detail: res.statusText }))
    throw new Error(err.detail || `Erreur ${res.status}`)
  }
  return res.json()
}

// ────────────────────────────────────────────────────────────
// STATS GLOBALES
// ────────────────────────────────────────────────────────────

async function apiGetStats() {
  return apiFetch('/categories/stats')
}

// ────────────────────────────────────────────────────────────
// CRUD CATÉGORIES
// ────────────────────────────────────────────────────────────

async function apiGetCategories() {
  return apiFetch('/categorie')
}

async function apiCreateCategory(payload) {
  // payload: { name_categorie, color, description, rule?, member_ids? }
  return apiFetch('/categories', {
    method: 'POST',
    body: JSON.stringify(payload)
  })
}

async function apiUpdateCategory(name, payload) {
  // payload: { new_name?, color?, description? }
  return apiFetch(`/categories/${encodeURIComponent(name)}`, {
    method: 'PUT',
    body: JSON.stringify(payload)
  })
}

async function apiDeleteCategory(name) {
  return apiFetch(`/categories/${encodeURIComponent(name)}`, {
    method: 'DELETE'
  })
}

// ────────────────────────────────────────────────────────────
// MEMBRES
// ────────────────────────────────────────────────────────────

async function apiGetMembers(name, filters = {}) {
  const params = new URLSearchParams()
  if (filters.search)       params.set('search',        filters.search)
  if (filters.active_only)  params.set('active_only',   'true')
  if (filters.inactive_only)params.set('inactive_only', 'true')
  if (filters.limit)        params.set('limit',         filters.limit)
  if (filters.offset)       params.set('offset',        filters.offset)
  const qs = params.toString() ? '?' + params.toString() : ''
  return apiFetch(`/categories/${encodeURIComponent(name)}/members${qs}`)
}

async function apiAddMembers(name, userIds) {
  // userIds: [123, 456, ...]
  return apiFetch(`/categories/${encodeURIComponent(name)}/members`, {
    method: 'POST',
    body: JSON.stringify({ user_ids: userIds, added_by: 'manual' })
  })
}

async function apiRemoveMember(name, telegramId) {
  return apiFetch(`/categories/${encodeURIComponent(name)}/members/${telegramId}`, {
    method: 'DELETE'
  })
}

async function apiMoveMembers(source, destination, userIds, action) {
  // action: 'move' | 'copy'
  return apiFetch('/categories/members/move', {
    method: 'POST',
    body: JSON.stringify({ source, destination, user_ids: userIds, action })
  })
}

async function apiMergeCategories(target, sources) {
  // sources: [name1, name2, ...]
  return apiFetch('/categories/merge', {
    method: 'POST',
    body: JSON.stringify({ target, sources })
  })
}

async function apiImportCSV(name, file) {
  const form = new FormData()
  form.append('file', file)
  return apiFetch(`/categories/${encodeURIComponent(name)}/import`, {
    method: 'POST',
    headers: {},           // laisser le browser poser le Content-Type multipart
    body: form
  })
}

// ────────────────────────────────────────────────────────────
// RÈGLES
// ────────────────────────────────────────────────────────────

async function apiGetRules(name) {
  return apiFetch(`/categories/${encodeURIComponent(name)}/rules`)
}

async function apiAddRule(name, triggerType, triggerValue) {
  return apiFetch(`/categories/${encodeURIComponent(name)}/rules`, {
    method: 'POST',
    body: JSON.stringify({ trigger_type: triggerType, trigger_value: triggerValue })
  })
}

async function apiDeleteRule(ruleId) {
  return apiFetch(`/categories/rules/${ruleId}`, { method: 'DELETE' })
}

// ────────────────────────────────────────────────────────────
// STATS CATÉGORIE + INTERSECTIONS
// ────────────────────────────────────────────────────────────

async function apiGetCategoryStats(name) {
  return apiFetch(`/categories/${encodeURIComponent(name)}/stats`)
}

async function apiGetIntersections(name) {
  return apiFetch(`/categories/${encodeURIComponent(name)}/intersections`)
}

// ────────────────────────────────────────────────────────────
// PROFIL MEMBRE (drawer)
// ────────────────────────────────────────────────────────────

async function apiGetMemberProfile(telegramId) {
  return apiFetch(`/members/${telegramId}/profile`)
}

async function apiGetMemberCategories(telegramId) {
  return apiFetch(`/members/${telegramId}/categories`)
}

// ────────────────────────────────────────────────────────────
// BROADCAST RAPIDE
// ────────────────────────────────────────────────────────────

async function apiBroadcast(payload) {
  // payload: { message, format, category, scheduled_at? }
  return apiFetch('/broadcast', {
    method: 'POST',
    body: JSON.stringify(payload)
  })
}