@extends('layouts.app')
@section('title', 'Modifier le Space')

@section('content')
<div class="max-w-2xl space-y-6">
    <div>
        <a href="{{ route('spaces.show', $space) }}" class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-slate-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour au Space
        </a>
        <h1 class="text-2xl font-bold text-slate-800 mt-3">Paramètres du Space</h1>
    </div>

    {{-- Infos générales --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <h2 class="text-sm font-semibold text-slate-700 mb-5">Informations générales</h2>
        <form method="POST" action="{{ route('spaces.update', $space) }}" class="space-y-5">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nom du Space *</label>
                <input type="text" name="name" value="{{ old('name', $space->name) }}" required
                       class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                <textarea name="description" rows="3"
                          class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description', $space->description) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Couleur</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['#3b82f6','#6366f1','#8b5cf6','#ec4899','#ef4444','#f97316','#eab308','#10b981','#14b8a6','#06b6d4'] as $color)
                    <label class="cursor-pointer">
                        <input type="radio" name="color" value="{{ $color }}" class="sr-only peer" {{ old('color', $space->color) === $color ? 'checked' : '' }}>
                        <div class="w-8 h-8 rounded-full ring-2 ring-offset-2 ring-transparent peer-checked:ring-current transition-all" style="background-color: {{ $color }}; color: {{ $color }}"></div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_public" id="is_public" value="1" class="rounded border-slate-300 text-blue-600" {{ old('is_public', $space->is_public) ? 'checked' : '' }}>
                <div>
                    <label for="is_public" class="text-sm font-medium text-slate-700">Space public</label>
                    <p class="text-xs text-slate-400">Tout le monde peut accéder aux fichiers sans invitation</p>
                </div>
            </div>

            <div class="flex gap-3 pt-1">
                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Sauvegarder
                </button>
                <a href="{{ route('spaces.show', $space) }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition-colors">
                    Annuler
                </a>
            </div>
        </form>
    </div>

    {{-- Lien de partage --}}
    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-start justify-between mb-2">
            <div>
                <h2 class="text-sm font-semibold text-slate-700">Lien de partage</h2>
                <p class="text-xs text-slate-400 mt-0.5">Toute personne avec ce lien peut rejoindre le space directement</p>
            </div>
            @if($space->join_token)
            <span class="inline-flex items-center gap-1 px-2 py-1 bg-emerald-50 text-emerald-600 text-xs font-medium rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                Actif
            </span>
            @endif
        </div>

        @if($space->join_token)
        <div class="mt-4 flex items-center gap-2">
            <div class="flex-1 px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-600 font-mono truncate">
                {{ $space->join_url }}
            </div>
            <button onclick="copyJoinLink('{{ $space->join_url }}')"
                    class="flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-medium rounded-lg transition-colors flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                Copier
            </button>
        </div>
        <div class="flex gap-2 mt-3">
            <form method="POST" action="{{ route('spaces.join-link.generate', $space) }}">
                @csrf
                <button type="submit" class="text-xs text-slate-500 hover:text-slate-700 underline">
                    Regénérer le lien
                </button>
            </form>
            <span class="text-slate-300">·</span>
            <form method="POST" action="{{ route('spaces.join-link.revoke', $space) }}" onsubmit="return confirm('Révoquer le lien ? Les personnes ne pourront plus rejoindre via ce lien.')">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs text-red-400 hover:text-red-600 underline">
                    Révoquer le lien
                </button>
            </form>
        </div>
        @else
        <div class="mt-4">
            <form method="POST" action="{{ route('spaces.join-link.generate', $space) }}">
                @csrf
                <button type="submit"
                        class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    Générer un lien de partage
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Zone dangereuse --}}
    <div class="bg-white rounded-xl border border-red-100 p-6">
        <h2 class="text-sm font-semibold text-red-600 mb-4">Zone dangereuse</h2>
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-slate-700">Supprimer ce Space</p>
                <p class="text-xs text-slate-400 mt-0.5">Cette action est irréversible. Tous les fichiers seront perdus.</p>
            </div>
            <form method="POST" action="{{ route('spaces.destroy', $space) }}" onsubmit="return confirm('Supprimer définitivement ce space et tous ses fichiers ?')">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg transition-colors">
                    Supprimer le Space
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function copyJoinLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        const btn = event.currentTarget;
        const original = btn.innerHTML;
        btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Copié !';
        btn.classList.replace('bg-slate-800', 'bg-emerald-600');
        setTimeout(() => { btn.innerHTML = original; btn.classList.replace('bg-emerald-600', 'bg-slate-800'); }, 2000);
    });
}
</script>
@endsection
