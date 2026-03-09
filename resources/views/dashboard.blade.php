@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Bonjour, {{ auth()->user()->name }} 👋</h1>
            <p class="text-slate-500 mt-1 text-sm">Voici un aperçu de votre activité</p>
        </div>
        @php
            $pendingCount = \App\Models\Invitation::where('email', auth()->user()->email)
                ->whereNull('accepted_at')->where('expires_at', '>', now())->count();
        @endphp
        @if($pendingCount > 0)
        <a href="{{ route('invitations.index') }}"
           class="flex items-center gap-2.5 px-4 py-3 bg-blue-50 border border-blue-200 hover:bg-blue-100 rounded-xl transition-colors">
            <div class="relative">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4 items-center justify-center rounded-full bg-blue-600 text-white text-xs font-bold">
                    {{ $pendingCount }}
                </span>
            </div>
            <div>
                <p class="text-sm font-semibold text-blue-700">
                    {{ $pendingCount }} invitation{{ $pendingCount > 1 ? 's' : '' }} en attente
                </p>
                <p class="text-xs text-blue-500">Cliquez pour voir</p>
            </div>
        </a>
        @endif
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4">
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $stats['spaces'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Spaces</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $stats['files'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Fichiers partagés</p>
        </div>
        <div class="bg-white rounded-xl border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-slate-800">{{ $stats['tags'] }}</p>
            <p class="text-sm text-slate-500 mt-1">Tags créés</p>
        </div>
    </div>

    <div class="grid grid-cols-5 gap-6">
        {{-- My Spaces --}}
        <div class="col-span-3">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-slate-800">Mes Spaces</h2>
                <a href="{{ route('spaces.index') }}" class="text-sm text-blue-600 hover:underline">Voir tout</a>
            </div>

            @if($allSpaces->isEmpty())
            <div class="bg-white rounded-xl border border-slate-200 border-dashed p-12 text-center">
                <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </div>
                <p class="text-slate-500 text-sm font-medium">Aucun space pour l'instant</p>
                <p class="text-slate-400 text-xs mt-1 mb-4">Créez votre premier space collaboratif</p>
                <a href="{{ route('spaces.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Créer un Space
                </a>
            </div>
            @else
            <div class="space-y-3">
                @foreach($allSpaces->take(5) as $space)
                <a href="{{ route('spaces.show', $space) }}" class="block bg-white rounded-xl border border-slate-200 p-4 hover:border-blue-200 hover:shadow-sm transition-all">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-semibold text-sm flex-shrink-0" style="background-color: {{ $space->color }}">
                            {{ strtoupper(substr($space->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-medium text-slate-800 text-sm truncate">{{ $space->name }}</h3>
                            <p class="text-xs text-slate-400 truncate mt-0.5">{{ $space->description ?: 'Aucune description' }}</p>
                        </div>
                        <div class="flex items-center gap-4 text-xs text-slate-400 flex-shrink-0">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ $space->files_count ?? 0 }}
                            </span>
                            <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Recent files --}}
        <div class="col-span-2">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-semibold text-slate-800">Fichiers récents</h2>
            </div>

            @if($recentFiles->isEmpty())
            <div class="bg-white rounded-xl border border-slate-200 p-8 text-center">
                <p class="text-slate-400 text-sm">Aucun fichier partagé</p>
            </div>
            @else
            <div class="space-y-2">
                @foreach($recentFiles as $file)
                <div class="bg-white rounded-xl border border-slate-200 p-3 flex items-center gap-3 hover:border-slate-300 transition-colors">
                    <div class="w-9 h-9 rounded-lg bg-slate-100 flex items-center justify-center flex-shrink-0">
                        @include('components.file-icon', ['type' => $file->icon])
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-700 truncate">{{ $file->title }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ $file->space->name }}</p>
                    </div>
                    <a href="{{ route('files.download', $file) }}" class="p-1.5 text-slate-400 hover:text-blue-600 rounded transition-colors flex-shrink-0">
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
