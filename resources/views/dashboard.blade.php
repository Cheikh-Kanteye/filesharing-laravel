@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">

    {{-- ── Page Header ─────────────────────────────────────────────── --}}
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Vue d'ensemble</h1>
            <p class="text-slate-500 mt-1 text-sm">Bonjour, <span class="font-medium text-slate-700">{{ auth()->user()->name }}</span> — voici votre activité</p>
        </div>
        <a href="{{ route('spaces.create') }}"
           class="flex items-center gap-2 px-4 py-2.5 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm"
           style="background-color: var(--accent);"
           onmouseover="this.style.backgroundColor='var(--accent-hover)'"
           onmouseout="this.style.backgroundColor='var(--accent)'">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau Space
        </a>
    </div>

    {{-- ── Pending invitations banner ───────────────────────────────── --}}
    @php
        $pendingCount = \App\Models\Invitation::where('email', auth()->user()->email)
            ->whereNull('accepted_at')->where('expires_at', '>', now())->count();
    @endphp
    @if($pendingCount > 0)
    <div class="flex items-center gap-4 px-5 py-4 rounded-2xl border text-sm" style="background-color: rgba(249,115,22,0.06); border-color: rgba(249,115,22,0.3);">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0" style="background-color: var(--accent);">
            <svg class="w-4.5 h-4.5 text-white" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <div class="flex-1">
            <p class="font-semibold" style="color: var(--accent);">
                {{ $pendingCount }} invitation{{ $pendingCount > 1 ? 's' : '' }} en attente
            </p>
            <p class="text-slate-500 text-xs mt-0.5">Des espaces collaboratifs vous attendent</p>
        </div>
        <a href="{{ route('invitations.index') }}"
           class="px-4 py-2 text-white text-xs font-semibold rounded-lg transition-colors"
           style="background-color: var(--accent);"
           onmouseover="this.style.backgroundColor='var(--accent-hover)'"
           onmouseout="this.style.backgroundColor='var(--accent)'">
            Voir les invitations
        </a>
    </div>
    @endif

    {{-- ── Stats row ────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-4 gap-4">
        {{-- Spaces --}}
        <div class="bg-white rounded-2xl border-0 shadow-sm p-5 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background-color: rgba(249,115,22,0.1);">
                    <svg class="w-5 h-5" style="color: var(--accent);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full" style="background-color: rgba(249,115,22,0.1); color: var(--accent);">Spaces</span>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ $stats['spaces'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Espaces actifs</p>
        </div>

        {{-- Fichiers --}}
        <div class="bg-white rounded-2xl border-0 shadow-sm p-5 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-blue-50">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full bg-blue-50 text-blue-600">Fichiers</span>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ $stats['files'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Fichiers partagés</p>
        </div>

        {{-- Tags --}}
        <div class="bg-white rounded-2xl border-0 shadow-sm p-5 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-emerald-50">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full bg-emerald-50 text-emerald-600">Tags</span>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ $stats['tags'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Tags créés</p>
        </div>

        {{-- Downloads --}}
        <div class="bg-white rounded-2xl border-0 shadow-sm p-5 hover:shadow-md transition-shadow">
            <div class="flex items-start justify-between mb-4">
                <div class="w-11 h-11 rounded-xl flex items-center justify-center bg-violet-50">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </div>
                <span class="text-xs font-medium px-2 py-1 rounded-full bg-violet-50 text-violet-600">Télécharg.</span>
            </div>
            <p class="text-3xl font-bold text-slate-900">{{ $stats['downloads'] ?? '—' }}</p>
            <p class="text-sm text-slate-500 mt-1">Téléchargements</p>
        </div>
    </div>

    {{-- ── Main grid ────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-5 gap-6">

        {{-- Mes Spaces (3 cols) --}}
        <div class="col-span-3">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-slate-900">Mes Spaces</h2>
                <a href="{{ route('spaces.index') }}" class="text-sm font-medium hover:underline" style="color: var(--accent);">Voir tout →</a>
            </div>

            @if($allSpaces->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border-0 border-dashed p-12 text-center" style="border: 2px dashed #e2e8f0;">
                <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <p class="text-slate-600 font-medium text-sm">Aucun space pour l'instant</p>
                <p class="text-slate-400 text-xs mt-1 mb-5">Créez votre premier space collaboratif</p>
                <a href="{{ route('spaces.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 text-white text-sm font-semibold rounded-xl transition-colors"
                   style="background-color: var(--accent);"
                   onmouseover="this.style.backgroundColor='var(--accent-hover)'"
                   onmouseout="this.style.backgroundColor='var(--accent)'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Créer un Space
                </a>
            </div>
            @else
            <div class="grid grid-cols-1 gap-3">
                @foreach($allSpaces->take(6) as $space)
                <a href="{{ route('spaces.show', $space) }}"
                   class="group bg-white rounded-2xl shadow-sm border-0 p-4 hover:shadow-md transition-all flex items-center gap-4">
                    {{-- Color avatar --}}
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-base flex-shrink-0 shadow-sm"
                         style="background-color: {{ $space->color }}">
                        {{ strtoupper(substr($space->name, 0, 2)) }}
                    </div>
                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-slate-900 text-sm truncate">{{ $space->name }}</h3>
                        <p class="text-xs text-slate-400 truncate mt-0.5">{{ $space->description ?: 'Aucune description' }}</p>
                    </div>
                    {{-- Meta --}}
                    <div class="flex items-center gap-3 text-xs text-slate-400 flex-shrink-0">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ $space->files_count ?? 0 }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            {{ $space->members_count ?? 1 }}
                        </span>
                        <span class="text-slate-300 group-hover:text-slate-400 transition-colors">→</span>
                    </div>
                </a>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Fichiers récents (2 cols) --}}
        <div class="col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-slate-900">Fichiers récents</h2>
            </div>

            @if($recentFiles->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm p-8 text-center">
                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-slate-400 text-sm">Aucun fichier partagé</p>
            </div>
            @else
            <div class="space-y-2">
                @foreach($recentFiles as $file)
                <div class="group bg-white rounded-xl shadow-sm border-0 p-3 flex items-center gap-3 hover:shadow-md transition-all">
                    {{-- File icon --}}
                    <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0
                        @if($file->icon === 'pdf') file-thumb-pdf
                        @elseif($file->icon === 'image') file-thumb-image
                        @elseif($file->icon === 'zip') file-thumb-zip
                        @elseif($file->icon === 'doc') file-thumb-doc
                        @else file-thumb-other @endif">
                        @include('components.file-icon', ['type' => $file->icon])
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate">{{ $file->title }}</p>
                        <p class="text-xs text-slate-400 truncate mt-0.5">
                            <span class="font-medium" style="color: {{ $file->space->color ?? 'var(--accent)' }}">{{ $file->space->name }}</span>
                            · {{ $file->formatted_size }}
                        </p>
                    </div>
                    <a href="{{ route('files.download', $file) }}"
                       class="opacity-0 group-hover:opacity-100 p-1.5 rounded-lg text-slate-400 hover:text-orange-600 hover:bg-orange-50 transition-all flex-shrink-0"
                       title="Télécharger">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
