
/* ══════════════════════════════════════════════════════════════════════
   forms_api.js — Couche réseau Formulaires
   Même structure que chat_api.js.
   Aucune logique UI ici — uniquement les fetch vers FastAPI.
   ══════════════════════════════════════════════════════════════════════ */

const API_BASE = 'https://fdkvip.com/forms'

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
    console.error(`[forms_api] ${method} ${path}`, err)
    throw err
  }
}

// ── CRUD Formulaires ─────────────────────────────────────────────────

/**
 * Lister tous les formulaires actifs.
 * Utilisé par : loadFormsList() → vue Liste
 * Retourne : [ { id, name, command, type, fields, stats, actif, … } ]
 */
export const apiGetForms = (actifOnly = true) =>
  _request('GET', `?actif_only=${actifOnly}`)

/**
 * Récupérer un formulaire complet par son ID.
 * Utilisé par : editForm(id) → charge le builder
 */
export const apiGetForm = (formId) =>
  _request('GET', `/${formId}`)

/**
 * Créer ou mettre à jour un formulaire (upsert sur la commande).
 * Utilisé par : publish() → bouton Publier / ⌘S
 * Retourne : { ok, form_id, message }
 */
export const apiSaveForm = (payload) =>
  _request('POST', '', payload)

/**
 * Désactiver (soft-delete) un formulaire.
 * Utilisé par : deleteForm(id)
 * Retourne : { ok, message }
 */
export const apiDeleteForm = (formId) =>
  _request('DELETE', `/${formId}`)

/**
 * Réactiver un formulaire désactivé.
 * Retourne : { ok, message }
 */
export const apiActivateForm = (formId) =>
  _request('POST', `/${formId}/activate`)

// ── Stats ────────────────────────────────────────────────────────────

/**
 * Stats d'un formulaire (total, complétion, score moyen).
 * Utilisé par : KPIs de la vue Liste
 * Retourne : { form_id, total, completed, completion_pct, avg_score }
 */
export const apiGetFormStats = (formId) =>
  _request('GET', `/${formId}/stats`)

// ── Réponses ─────────────────────────────────────────────────────────

/**
 * Liste des soumissions complètes d'un formulaire.
 * Utilisé par : loadResponsesForForm(formId) → vue Réponses
 * Retourne : [ { telegram_id, prenom, score_final, score_max, pct, submitted_at } ]
 */
export const apiGetResponses = (formId, limit = 100) =>
  _request('GET', `/${formId}/responses?limit=${limit}`)

/**
 * Détail des réponses d'un utilisateur précis.
 * Utilisé par : openResponseDetail(telegramId) → modal détail
 * Retourne : [ { field_id, field_type, value, is_correct, points, answered_at } ]
 */
export const apiGetUserResponses = (formId, telegramId) =>
  _request('GET', `/${formId}/responses/${telegramId}`)

// ── Envoi manuel / Broadcast ──────────────────────────────────────────

/**
 * Envoyer manuellement un formulaire à une liste d'utilisateurs ou une catégorie.
 * Utilisé par : bouton "Envoyer" dans le dashboard
 * payload : { user_ids?: [123, 456] } OU { category: "Prospect Inscrit" }
 * Retourne : { ok, queued, message }
 */
export const apiSendForm = (formId, payload) =>
  _request('POST', `/${formId}/send`, payload)

// ── Scheduler ─────────────────────────────────────────────────────────

/**
 * Voir tous les jobs planifiés actifs (debug / dashboard).
 * Retourne : [ { id, next_run, trigger } ]
 */
export const apiGetSchedulerJobs = () =>
  _request('GET', `/scheduler/jobs`)