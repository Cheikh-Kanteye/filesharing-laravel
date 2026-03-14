@extends('layouts.app')
@section('title', 'Créer un Space')

@section('content')
<div class="max-w-2xl">

    {{-- ── Breadcrumb + Title ───────────────────────────────────────── --}}
    <div class="mb-7">
        <a href="{{ route('spaces.index') }}" class="inline-flex items-center gap-1.5 text-sm text-slate-400 hover:text-slate-600 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour aux Spaces
        </a>
        <h1 class="text-2xl font-bold text-slate-900 mt-3">Créer un Space</h1>
        <p class="text-slate-500 text-sm mt-1">Un space est un espace collaboratif pour partager des documents</p>
    </div>

    {{-- ── Form card ────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm p-7">
        <form method="POST" action="{{ route('spaces.store') }}" class="space-y-6">
            @csrf

            {{-- Name --}}
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-1.5">Nom du Space <span style="color: var(--accent);">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:border-transparent bg-slate-50 transition-all"
                       style="--tw-ring-color: var(--accent);"
                       placeholder="Ex: Licence 3 Info – Algorithmique">
                @error('name')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-1.5">Description</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:border-transparent bg-slate-50 resize-none transition-all"
                          style="--tw-ring-color: var(--accent);"
                          placeholder="Décrivez l'objectif de ce space...">{{ old('description') }}</textarea>
            </div>

            {{-- Color picker --}}
            <div>
                <label class="block text-sm font-semibold text-slate-800 mb-3">Couleur du Space</label>
                <div class="flex flex-wrap gap-3">
                    @foreach(['#f97316','#3b82f6','#6366f1','#8b5cf6','#ec4899','#ef4444','#eab308','#10b981','#14b8a6','#06b6d4'] as $color)
                    <label class="cursor-pointer group">
                        <input type="radio" name="color" value="{{ $color }}" class="sr-only peer"
                               {{ old('color', '#f97316') === $color ? 'checked' : '' }}>
                        <div class="w-9 h-9 rounded-full ring-2 ring-offset-2 ring-transparent peer-checked:ring-current transition-all shadow-sm group-hover:scale-110"
                             style="background-color: {{ $color }}; color: {{ $color }}"></div>
                    </label>
                    @endforeach
                </div>
                <p class="text-xs text-slate-400 mt-2">Cette couleur identifie votre space dans la sidebar</p>
            </div>

            {{-- Public toggle --}}
            <div class="flex items-start gap-4 p-4 bg-slate-50 rounded-xl border border-slate-200">
                <div class="flex items-center h-5 mt-0.5">
                    <input type="checkbox" name="is_public" id="is_public" value="1"
                           class="rounded border-slate-300 w-4 h-4 cursor-pointer"
                           style="accent-color: var(--accent);"
                           {{ old('is_public') ? 'checked' : '' }}>
                </div>
                <div>
                    <label for="is_public" class="text-sm font-semibold text-slate-800 cursor-pointer">Space public</label>
                    <p class="text-xs text-slate-500 mt-0.5">Les utilisateurs pourront accéder aux fichiers sans invitation</p>
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 text-white text-sm font-bold rounded-xl transition-colors shadow-sm"
                        style="background-color: var(--accent);"
                        onmouseover="this.style.backgroundColor='var(--accent-hover)'"
                        onmouseout="this.style.backgroundColor='var(--accent)'">
                    Créer le Space
                </button>
                <a href="{{ route('spaces.index') }}"
                   class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
