@extends('layouts.app')
@section('title', 'Explorer')

@section('content')
<div class="space-y-6">

    {{-- ── Header ─────────────────────────────────────────────────────── --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Explorer les Spaces</h1>
            <p class="text-slate-500 text-sm mt-1">Découvrez et rejoignez des espaces collaboratifs publics</p>
        </div>
        <a href="{{ route('spaces.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm"
           style="background-color: var(--accent);"
           onmouseover="this.style.backgroundColor='var(--accent-hover)'"
           onmouseout="this.style.backgroundColor='var(--accent)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Créer un Space
        </a>
    </div>

    {{-- ── Barre de recherche ──────────────────────────────────────────── --}}
    <form method="GET" action="{{ route('spaces.explore') }}" class="max-w-lg">
        <div class="relative">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="search" name="q" value="{{ $query }}" placeholder="Rechercher un space public..."
                   class="w-full pl-10 pr-9 py-2.5 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:border-transparent transition-all"
                   style="--tw-ring-color: var(--accent);"
                   autofocus>
            @if($query)
            <a href="{{ route('spaces.explore') }}" class="absolute right-3 top-1/2 -translate-y-1/2 p-0.5 text-slate-400 hover:text-slate-600">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
            @endif
        </div>
    </form>

    {{-- ── Stats band ──────────────────────────────────────────────────── --}}
    <div class="flex items-center gap-2 text-sm text-slate-500">
        @if($query)
            <span><strong class="text-slate-800">{{ $spaces->total() }}</strong> résultat{{ $spaces->total() > 1 ? 's' : '' }} pour « {{ $query }} »</span>
        @else
            <span><strong class="text-slate-800">{{ $spaces->total() }}</strong> space{{ $spaces->total() > 1 ? 's' : '' }} public{{ $spaces->total() > 1 ? 's' : '' }} disponible{{ $spaces->total() > 1 ? 's' : '' }}</span>
        @endif
    </div>

    {{-- ── Grille ──────────────────────────────────────────────────────── --}}
    @if($spaces->isNotEmpty())
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @foreach($spaces as $space)
        <div class="group bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-all border border-slate-100 flex flex-col">

            {{-- Barre couleur --}}
            <div class="h-1" style="background-color: {{ $space->color }}"></div>

            <div class="p-4 flex flex-col flex-1">
                {{-- Avatar + badge Nouveau --}}
                <div class="flex items-center justify-between mb-3">
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                         style="background-color: {{ $space->color }}">
                        {{ strtoupper(substr($space->name, 0, 2)) }}
                    </div>
                    @if($space->created_at->gt(now()->subDays(7)))
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full text-white" style="background-color: var(--accent);">Nouveau</span>
                    @endif
                </div>

                {{-- Nom + description --}}
                <div class="flex-1 mb-3">
                    <h3 class="font-bold text-slate-900 text-sm mb-0.5 line-clamp-1">{{ $space->name }}</h3>
                    <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed">{{ $space->description ?: 'Aucune description' }}</p>
                </div>

                {{-- Owner + Stats --}}
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-1.5">
                        <div class="w-4 h-4 rounded-full flex items-center justify-center text-white text-[9px] font-bold flex-shrink-0"
                             style="background-color: var(--accent);">
                            {{ strtoupper(substr($space->owner->name, 0, 1)) }}
                        </div>
                        <span class="text-xs text-slate-500 truncate max-w-[80px]">{{ $space->owner->name }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-xs text-slate-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ $space->files_count }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $space->members_count }}
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex gap-1.5 mt-auto">
                    <a href="{{ route('spaces.show', $space) }}"
                       class="flex-1 text-center py-1.5 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                        Voir
                    </a>
                    <form method="POST" action="{{ route('spaces.join-public', $space) }}" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="w-full py-1.5 text-xs font-semibold text-white rounded-lg transition-all hover:opacity-90"
                                style="background-color: var(--accent);">
                            Rejoindre
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Pagination --}}
    @if($spaces->hasPages())
    <div class="mt-6">
        {{ $spaces->links() }}
    </div>
    @endif

    @else
    {{-- Empty state --}}
    <div class="bg-white rounded-2xl border-2 border-dashed border-slate-200 p-20 text-center">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background-color: rgba(249,115,22,0.1);">
            <svg class="w-8 h-8" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        @if($query)
            <h3 class="text-slate-800 font-bold text-lg mb-2">Aucun résultat pour « {{ $query }} »</h3>
            <p class="text-slate-400 text-sm mb-6">Essayez avec d'autres mots-clés</p>
            <a href="{{ route('spaces.explore') }}" class="text-sm font-semibold hover:underline" style="color: var(--accent);">Voir tous les spaces</a>
        @else
            <h3 class="text-slate-800 font-bold text-lg mb-2">Aucun space public disponible</h3>
            <p class="text-slate-400 text-sm mb-6 max-w-sm mx-auto">Vous faites déjà partie de tous les spaces publics, ou aucun n'a encore été créé</p>
            <a href="{{ route('spaces.create') }}"
               class="inline-flex items-center gap-2 px-6 py-2.5 text-white font-semibold text-sm rounded-xl"
               style="background-color: var(--accent);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Créer un Space public
            </a>
        @endif
    </div>
    @endif

</div>
@endsection
