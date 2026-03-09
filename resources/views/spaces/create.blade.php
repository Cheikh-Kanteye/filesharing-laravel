@extends('layouts.app')
@section('title', 'Créer un Space')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('spaces.index') }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour aux Spaces
        </a>
        <h1 class="text-2xl font-bold text-slate-800 mt-3">Créer un Space</h1>
        <p class="text-slate-500 text-sm mt-1">Un space est un espace collaboratif pour partager des documents</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <form method="POST" action="{{ route('spaces.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nom du Space *</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                       class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="Ex: Licence 3 Info – Algorithmique">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                <textarea name="description" rows="3"
                          class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                          placeholder="Décrivez l'objectif de ce space...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Couleur du Space</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['#3b82f6','#6366f1','#8b5cf6','#ec4899','#ef4444','#f97316','#eab308','#10b981','#14b8a6','#06b6d4'] as $color)
                    <label class="cursor-pointer">
                        <input type="radio" name="color" value="{{ $color }}" class="sr-only peer" {{ old('color', '#3b82f6') === $color ? 'checked' : '' }}>
                        <div class="w-8 h-8 rounded-full ring-2 ring-offset-2 ring-transparent peer-checked:ring-current transition-all" style="background-color: {{ $color }}; color: {{ $color }}"></div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_public" id="is_public" value="1" class="rounded border-slate-300 text-blue-600" {{ old('is_public') ? 'checked' : '' }}>
                <div>
                    <label for="is_public" class="text-sm font-medium text-slate-700">Space public</label>
                    <p class="text-xs text-slate-400">Les utilisateurs pourront accéder aux fichiers sans invitation</p>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Créer le Space
                </button>
                <a href="{{ route('spaces.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
