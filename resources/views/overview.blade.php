{{-- resources/views/pages/overview.blade.php --}}
@extends('dashboard')

@section('page-title', 'Dashboard')
@section('page-meta', 'Samedi 18 avril 2026')

@section('topbar-actions')
    <a href="{{ route('messages.create') }}"
       class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium bg-sky-500 hover:bg-sky-400 text-white rounded-lg transition-colors">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Nouveau message
    </a>
@endsection

@section('content')

{{-- ── MÉTRIQUES ────────────────────────────────────────────────── --}}
<div class="grid grid-cols-4 gap-4 mb-6">
    @foreach([
        ['label' => 'Membres total',       'value' => number_format($stats['total_members'], 0, ',', ' '),   'delta' => '+48 cette semaine',  'up' => true,  'sub' => null],
        ['label' => 'Actifs (7 jours)',    'value' => number_format($stats['active_7d'], 0, ',', ' '),       'delta' => '58% du total',       'up' => true,  'sub' => null],
        ['label' => 'Abonnements actifs',  'value' => number_format($stats['subscriptions'], 0, ',', ' '),   'delta' => '-12 expirations',    'up' => false, 'sub' => null],
        ['label' => 'Trades journalisés',  'value' => $stats['trades_today'],                                'delta' => '+31 aujourd\'hui',   'up' => true,  'sub' => null],
    ] as $m)
    <div class="bg-zinc-900 border border-white/5 rounded-xl p-4">
        <p class="text-xs text-zinc-500 mb-2">{{ $m['label'] }}</p>
        <p class="text-2xl font-medium text-white tabular-nums">{{ $m['value'] }}</p>
        <p class="text-xs mt-1.5 {{ $m['up'] ? 'text-emerald-400' : 'text-red-400' }}">{{ $m['delta'] }}</p>
    </div>
    @endforeach
</div>

{{-- ── GRILLE PRINCIPALE ────────────────────────────────────────── --}}
<div class="grid grid-cols-3 gap-4 mb-4">

    {{-- Segments --}}
    <div class="bg-zinc-900 border border-white/5 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-medium text-white">Segments</h2>
            <a href="{{ route('users') }}" class="text-xs text-zinc-500 hover:text-sky-400 transition-colors">Voir tout →</a>
        </div>
        <div class="space-y-3">
            @foreach($segments as $seg)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-zinc-400">{{ $seg['label'] }}</span>
                    <span class="text-xs tabular-nums text-zinc-500">{{ number_format($seg['count'], 0, ',', ' ') }}</span>
                </div>
                <div class="h-1 bg-white/5 rounded-full overflow-hidden">
                    <div class="h-full rounded-full transition-all duration-700 {{ $seg['color'] }}"
                         style="width: {{ $seg['pct'] }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Alertes --}}
    <div class="bg-zinc-900 border border-white/5 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-medium text-white">Alertes</h2>
            <span class="text-[10px] bg-red-500/15 text-red-400 px-2 py-0.5 rounded-md">{{ $alerts->count() }} actives</span>
        </div>
        <div class="space-y-2.5">
            @foreach($alerts as $alert)
            <div class="flex items-start gap-3 p-3 rounded-lg bg-white/3 border border-white/5">
                <div class="w-1.5 h-1.5 rounded-full mt-1.5 flex-shrink-0
                    {{ $alert['level'] === 'danger' ? 'bg-red-400' : ($alert['level'] === 'warning' ? 'bg-amber-400' : 'bg-sky-400') }}"></div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-medium text-zinc-200 leading-snug">{{ $alert['title'] }}</p>
                    <p class="text-[11px] text-zinc-600 mt-0.5">{{ $alert['sub'] }}</p>
                </div>
                <span class="text-lg font-medium tabular-nums
                    {{ $alert['level'] === 'danger' ? 'text-red-400' : ($alert['level'] === 'warning' ? 'text-amber-400' : 'text-sky-400') }}">
                    {{ $alert['count'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Agent IA --}}
    <div class="bg-zinc-900 border border-white/5 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-medium text-white">Agent IA</h2>
            <span class="flex items-center gap-1.5 text-[10px] text-emerald-400">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                Actif
            </span>
        </div>
        <div class="grid grid-cols-2 gap-2 mb-4">
            <div class="bg-white/3 rounded-lg p-3 text-center">
                <p class="text-lg font-medium text-white tabular-nums">{{ $ai['handled'] }}</p>
                <p class="text-[10px] text-zinc-500 mt-0.5">Msgs traités</p>
            </div>
            <div class="bg-white/3 rounded-lg p-3 text-center">
                <p class="text-lg font-medium text-white tabular-nums">{{ $ai['resolution_rate'] }}%</p>
                <p class="text-[10px] text-zinc-500 mt-0.5">Résolution auto</p>
            </div>
        </div>
        @if($ai['escalations'] > 0)
        <a href="{{ route('ai') }}" class="flex items-center justify-between p-2.5 rounded-lg bg-amber-500/10 border border-amber-500/20 text-xs">
            <span class="text-amber-300">{{ $ai['escalations'] }} escalades en attente</span>
            <span class="text-amber-500">→</span>
        </a>
        @endif
    </div>
</div>

{{-- ── LIGNE BAS ─────────────────────────────────────────────────── --}}
<div class="grid grid-cols-2 gap-4">

    {{-- Activité récente --}}
    <div class="bg-zinc-900 border border-white/5 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-medium text-white">Activité récente</h2>
        </div>
        <div class="space-y-1">
            @foreach($recentActivity as $act)
            <div class="flex items-center gap-3 py-2 border-b border-white/4 last:border-0">
                <div class="w-7 h-7 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-medium
                    {{ $act['type'] === 'ai' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-white/8 text-zinc-400' }}">
                    {{ $act['initials'] }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-zinc-300 truncate">{{ $act['description'] }}</p>
                    <p class="text-[10px] text-zinc-600">{{ $act['time'] }}</p>
                </div>
                <span class="text-[10px] px-1.5 py-0.5 rounded-md {{ $act['badge_class'] }}">{{ $act['badge'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Abonnements à surveiller --}}
    <div class="bg-zinc-900 border border-white/5 rounded-xl p-5">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-sm font-medium text-white">Expirations proches</h2>
            <a href="{{ route('subscriptions') }}" class="text-xs text-zinc-500 hover:text-sky-400 transition-colors">Voir tout →</a>
        </div>
        <div class="space-y-1">
            @foreach($expiringSubscriptions as $sub)
            <div class="flex items-center gap-3 py-2 border-b border-white/4 last:border-0">
                <div class="w-7 h-7 rounded-full bg-white/8 flex items-center justify-center text-[10px] font-medium text-zinc-400 flex-shrink-0">
                    {{ $sub->user->initials }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-zinc-300 truncate">{{ $sub->user->name }}</p>
                    <p class="text-[10px] text-zinc-600">{{ $sub->plan_label }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs tabular-nums {{ $sub->days_left <= 3 ? 'text-red-400' : 'text-amber-400' }}">
                        {{ $sub->days_left }}j
                    </p>
                    <form method="POST" action="{{ route('subscriptions.remind', $sub) }}" class="inline">
                        @csrf
                        <button type="submit" class="text-[10px] text-zinc-600 hover:text-sky-400 transition-colors mt-0.5">
                            Relancer
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>

@endsection