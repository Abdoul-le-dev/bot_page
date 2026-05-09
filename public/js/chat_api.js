/* ══════════════════════════════════════════════════════════════════════
   chat_api.js — Couche réseau Chat Direct
   Tous les fetch vers l'API. Aucune logique UI ici.
   ══════════════════════════════════════════════════════════════════════ */

const API_BASE = 'http://54.226.165.244:8000/chat'

async function _request(method, path, body = null, isFormData = false) {
  const opts = {
    method,
    headers: isFormData ? {} : { 'Content-Type': 'application/json' },
  }
  if (body) opts.body = isFormData ? body : JSON.stringify(body)
  try {
    const res  = await fetch(`${API_BASE}${path}`, opts)
    const data = await res.json()
    if (!res.ok) throw new Error(data.detail || 'Erreur API')
    return data
  } catch (err) {
    console.error(`[chat_api] ${method} ${path}`, err)
    throw err
  }
}

// ── Conversations ────────────────────────────────────────────────────

export const apiGetConversations = (tab = 'all', search = '', limit = 50, offset = 0) =>
  _request('GET', `/conversations?tab=${tab}&search=${encodeURIComponent(search)}&limit=${limit}&offset=${offset}`)

export const apiGetConversation = (userId) =>
  _request('GET', `/conversations/${userId}`)

export const apiGetConversationStats = () =>
  _request('GET', `/conversations/stats`)

export const apiSearchConversations = (q) =>
  _request('GET', `/conversations/search?q=${encodeURIComponent(q)}`)

export const apiSetIA = (userId, enabled) =>
  _request('PATCH', `/conversations/${userId}/ia`, { enabled })

export const apiMarkRead = (userId) =>
  _request('PATCH', `/conversations/${userId}/read`)

export const apiPinConversation = (userId, pinned) =>
  _request('PATCH', `/conversations/${userId}/pin`, { pinned })

export const apiSetNote = (userId, note) =>
  _request('PATCH', `/conversations/${userId}/note`, { note })

export const apiSetBlocked = (userId, blocked) =>
  _request('PATCH', `/conversations/${userId}/block`, { blocked })

// ── Messages ─────────────────────────────────────────────────────────

export const apiGetMessages = (userId, limit = 50, beforeId = null, afterId = null) => {
  let qs = `?limit=${limit}`
  if (beforeId) qs += `&before_id=${beforeId}`
  if (afterId)  qs += `&after_id=${afterId}`
  return _request('GET', `/conversations/${userId}/messages${qs}`)
}

export const apiSendMessage = (userId, payload) =>
  _request('POST', `/conversations/${userId}/messages`, { ...payload, user_id: userId })

export const apiGetTimeline = (userId) =>
  _request('GET', `/conversations/${userId}/timeline`)

export const apiDeleteMessage = (messageId, userId) =>
  _request('DELETE', `/messages/${messageId}`, { user_id: userId })

// ── Upload média ──────────────────────────────────────────────────────

export const apiUploadMedia = async (userId, file) => {
  const fd = new FormData()
  fd.append('user_id', userId)
  fd.append('file', file)
  return _request('POST', `/media/upload`, fd, true)
}

// ── Profil ────────────────────────────────────────────────────────────

export const apiGetProfile = (userId) =>
  _request('GET', `/conversations/${userId}/profile`)

export const apiGetBroadcasts = (userId, limit = 5) =>
  _request('GET', `/conversations/${userId}/broadcasts?limit=${limit}`)

// ── Abonnements ───────────────────────────────────────────────────────

export const apiGetSubscriptions = (userId) =>
  _request('GET', `/conversations/${userId}/subscriptions`)

export const apiGetSubscriptionSummary = (userId) =>
  _request('GET', `/conversations/${userId}/subscriptions/summary`)

export const apiCreateSubscription = (userId, plan, note = '') =>
  _request('POST', `/conversations/${userId}/subscriptions`, { plan, note })

export const apiCancelSubscription = (subId) =>
  _request('PATCH', `/subscriptions/${subId}/cancel`)

// ── IA stats ──────────────────────────────────────────────────────────

export const apiGetIAStats = (userId) =>
  _request('GET', `/conversations/${userId}/ia/stats`)

// ── Export ────────────────────────────────────────────────────────────

export const apiExportConversation = (userId, fmt = 'json') =>
  `${API_BASE}/conversations/${userId}/export?fmt=${fmt}`

export const apiMarkRequiresAdmin = (messageId, value) =>
  _request('PATCH', `/messages/${messageId}/requires-admin`, { value })

export const apiMarkTestimonial = (messageId, value) =>
  _request('PATCH', `/messages/${messageId}/testimonial`, { value })