@extends('layouts.app')
@section('title', 'Mes Spaces')

@section('content')
<div class="space-y-8">

    {{-- ── Header ──────────────────────────────────────────────────── --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Mes Spaces</h1>
            <p class="text-slate-500 text-sm mt-1">Espaces collaboratifs de partage de documents</p>
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

    {{-- ── Owned spaces ─────────────────────────────────────────────── --}}
    @if($ownedSpaces->isNotEmpty())
    <section>
        <div class="flex items-center gap-3 mb-4">
            <h2 class="text-sm font-bold text-slate-500 uppercase tracking-widest">Spaces que je gère</h2>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full text-white" style="background-color: var(--accent);">{{ $ownedSpaces->count() }}</span>
        </div>
        <div class="grid grid-cols-3 gap-4">
            @foreach($ownedSpaces as $space)
                @include('spaces._card', ['space' => $space, 'owned' => true])
            @endforeach
        </div>
    </section>
    @endif

    {{-- ── Joined spaces ────────────────────────────────────────────── --}}
    @if($joinedSpaces->isNotEmpty())
    <section>
        <div class="flex items-center gap-3 mb-4">
            <h2 class="text-sm font-bold text-slate-500 uppercase tracking-widest">Spaces rejoints</h2>
            <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-600">{{ $joinedSpaces->count() }}</span>
        </div>
        <div class="grid grid-cols-3 gap-4">
            @foreach($joinedSpaces as $space)
                @include('spaces._card', ['space' => $space, 'owned' => false])
            @endforeach
        </div>
    </section>
    @endif

    {{-- ── Empty state ──────────────────────────────────────────────── --}}
    @if($ownedSpaces->isEmpty() && $joinedSpaces->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm p-16 text-center" style="border: 2px dashed #e2e8f0;">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background-color: rgba(249,115,22,0.1);">
            <svg class="w-8 h-8" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </div>
        <h3 class="text-slate-800 font-bold text-lg mb-2">Aucun space pour l'instant</h3>
        <p class="text-slate-400 text-sm mb-7 max-w-sm mx-auto">Créez votre premier espace collaboratif ou attendez une invitation de vos collègues</p>
        <a href="{{ route('spaces.create') }}"
           class="inline-flex items-center gap-2 px-6 py-2.5 text-white font-semibold text-sm rounded-xl transition-colors shadow-sm"
           style="background-color: var(--accent);"
           onmouseover="this.style.backgroundColor='var(--accent-hover)'"
           onmouseout="this.style.backgroundColor='var(--accent)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Créer mon premier Space
        </a>
    </div>
    @endif
</div>
@endsection
