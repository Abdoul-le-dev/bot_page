{{-- resources/views/partials/_notifications_dropdown.blade.php --}}
<div class="flex items-center justify-between px-4 py-3 border-b border-white/5">
    <p class="text-xs font-medium text-white">Notifications</p>
    <button id="notif-clear" class="text-[10px] text-zinc-500 hover:text-sky-400 transition-colors">
        Tout marquer lu
    </button>
</div>
<div class="max-h-80 overflow-y-auto">
    @forelse($notifications as $notif)
    <div class="notif-item flex items-start gap-3 px-4 py-3 border-b border-white/4 last:border-0 hover:bg-white/3 transition-colors cursor-pointer">
        <div class="notif-dot w-1.5 h-1.5 rounded-full mt-1.5 flex-shrink-0
            {{ match($notif->level) {
                'danger'  => 'bg-red-400',
                'warning' => 'bg-amber-400',
                default   => 'bg-sky-400'
            } }}"></div>
        <div class="flex-1 min-w-0">
            <p class="text-xs font-medium text-zinc-200 leading-snug">{{ $notif->title }}</p>
            <p class="text-[10px] text-zinc-600 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
        </div>
    </div>
    @empty
    <p class="text-xs text-zinc-600 text-center py-6">Aucune notification</p>
    @endforelse
</div>

{{-- ──────────────────────────────────────────────────────────────────── --}}
{{-- resources/views/components/nav-icon.blade.php                       --}}
{{-- Usage: <x-nav-icon name="grid" class="w-4 h-4" />                   --}}

{{-- NOTE: ce composant est simplifié ici en inline SVG switch.           --}}
{{-- En production, utiliser un package comme blade-heroicons ou          --}}
{{-- créer un composant Blade dédié par icône.                            --}}

{{-- resources/views/partials/_flash.blade.php --}}
@if(session('success'))
<div data-auto-close="4000"
     class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 bg-zinc-900 border border-emerald-500/30 rounded-xl text-sm text-emerald-300 shadow-xl shadow-black/40">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
</div>
@endif
@if(session('error'))
<div data-auto-close="5000"
     class="fixed bottom-6 right-6 z-50 flex items-center gap-3 px-4 py-3 bg-zinc-900 border border-red-500/30 rounded-xl text-sm text-red-300 shadow-xl shadow-black/40">
    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    {{ session('error') }}
</div>
@endif