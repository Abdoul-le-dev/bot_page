/* resources/css/app.css */
@tailwind base;
@tailwind components;
@tailwind utilities;

/* ── Fonts ─────────────────────────────────────────────────────────── */
@layer base {
    html { font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif; }
}

/* ── Utilitaires custom ──────────────────────────────────────────────── */
@layer utilities {
    /* Fond légèrement teinté sans classe arbitraire répétée */
    .bg-white\/3  { background-color: rgb(255 255 255 / 0.03); }
    .bg-white\/8  { background-color: rgb(255 255 255 / 0.08); }
    .border-white\/4 { border-color: rgb(255 255 255 / 0.04); }

    /* Scrollbar discrète */
    .overflow-y-auto {
        scrollbar-width: thin;
        scrollbar-color: rgb(255 255 255 / 0.08) transparent;
    }
    .overflow-y-auto::-webkit-scrollbar { width: 4px; }
    .overflow-y-auto::-webkit-scrollbar-track { background: transparent; }
    .overflow-y-auto::-webkit-scrollbar-thumb { background: rgb(255 255 255 / 0.08); border-radius: 4px; }
}

/* ── Composants réutilisables ─────────────────────────────────────── */
@layer components {

    /* Carte de base */
    .card {
        @apply bg-zinc-900 border border-white/5 rounded-xl p-5;
    }

    /* Titre de section */
    .card-title {
        @apply text-sm font-medium text-white;
    }

    /* Ligne de tableau légère */
    .table-row {
        @apply flex items-center gap-3 py-2.5 border-b border-white/4 last:border-0;
    }

    /* Badge de statut */
    .badge {
        @apply text-[10px] px-1.5 py-0.5 rounded-md font-medium;
    }
    .badge-green  { @apply bg-emerald-500/15 text-emerald-400; }
    .badge-blue   { @apply bg-sky-500/15 text-sky-400; }
    .badge-amber  { @apply bg-amber-500/15 text-amber-400; }
    .badge-red    { @apply bg-red-500/15 text-red-400; }
    .badge-purple { @apply bg-violet-500/15 text-violet-400; }
    .badge-gray   { @apply bg-white/8 text-zinc-400; }
    .badge-ai     { @apply bg-emerald-500/15 text-emerald-400; }

    /* Avatar initiales */
    .avatar {
        @apply w-7 h-7 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-medium bg-white/8 text-zinc-400;
    }

    /* Bouton primaire */
    .btn-primary {
        @apply flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-sky-500 hover:bg-sky-400 text-white rounded-lg transition-colors;
    }

    /* Bouton secondaire */
    .btn-secondary {
        @apply flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-white/6 hover:bg-white/10 text-zinc-300 rounded-lg transition-colors border border-white/8;
    }

    /* Input / select standard */
    .form-input {
        @apply w-full px-3 py-2 text-sm bg-zinc-800 border border-white/8 rounded-lg text-zinc-200 placeholder-zinc-600
               focus:outline-none focus:border-sky-500/50 focus:ring-1 focus:ring-sky-500/30 transition-all;
    }

    /* Barre de progression fine */
    .progress-track {
        @apply h-0.5 bg-white/6 rounded-full overflow-hidden;
    }
    .progress-fill {
        @apply h-full rounded-full;
    }

    /* Lien de nav actif */
    .nav-link.active {
        @apply bg-white/8 text-white;
    }
}