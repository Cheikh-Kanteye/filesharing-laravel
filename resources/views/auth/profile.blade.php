@extends('layouts.app')
@section('title', 'Mon Profil')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-900">Mon Profil</h1>
        <p class="text-slate-500 text-sm mt-1">Gérez vos informations personnelles</p>
    </div>

    <div class="grid grid-cols-3 gap-6">

        {{-- Avatar card --}}
        <div class="bg-white rounded-2xl shadow-sm p-6 flex flex-col items-center text-center gap-4">
            <div class="w-24 h-24 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg"
                 style="background: linear-gradient(135deg, var(--accent) 0%, #ea580c 100%)">
                {{ $user->initials }}
            </div>
            <div>
                <p class="font-bold text-slate-900 text-lg">{{ $user->name }}</p>
                <p class="text-slate-500 text-sm truncate max-w-[180px]">{{ $user->email }}</p>
                <p class="text-slate-400 text-xs mt-2">Membre depuis {{ $user->created_at->translatedFormat('F Y') }}</p>
            </div>

            {{-- Stats --}}
            <div class="w-full grid grid-cols-2 gap-3 pt-4 border-t border-slate-100">
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xl font-bold text-slate-900">{{ $user->files()->count() }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Fichiers</p>
                </div>
                <div class="bg-slate-50 rounded-xl p-3">
                    <p class="text-xl font-bold text-slate-900">{{ $user->ownedSpaces()->count() }}</p>
                    <p class="text-xs text-slate-500 mt-0.5">Spaces</p>
                </div>
            </div>
        </div>

        {{-- Form card --}}
        <div class="col-span-2 space-y-5">

            {{-- Success / Errors --}}
            @if(session('success'))
            <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl">
                <svg class="w-4 h-4 flex-shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
            @endif

            {{-- Informations --}}
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h2 class="font-semibold text-slate-900 mb-5">Informations générales</h2>
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf @method('PUT')

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nom complet</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:bg-white transition-colors"
                                   style="--tw-ring-color: var(--accent)">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Adresse email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:bg-white transition-colors"
                                   style="--tw-ring-color: var(--accent)">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Bio</label>
                        <textarea name="bio" rows="3"
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:bg-white transition-colors resize-none"
                                  style="--tw-ring-color: var(--accent)"
                                  placeholder="Parlez un peu de vous...">{{ old('bio', $user->bio) }}</textarea>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                                class="px-5 py-2.5 text-white text-sm font-semibold rounded-xl transition-all hover:opacity-90 active:scale-95"
                                style="background-color: var(--accent)">
                            Sauvegarder
                        </button>
                    </div>
                </form>
            </div>

            {{-- Mot de passe --}}
            <div class="bg-white rounded-2xl shadow-sm p-6">
                <h2 class="font-semibold text-slate-900 mb-1">Mot de passe</h2>
                <p class="text-slate-500 text-sm mb-5">Utilisez un mot de passe fort d'au moins 8 caractères.</p>
                <form method="POST" action="{{ route('profile.update') }}" class="space-y-4">
                    @csrf @method('PUT')
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nouveau mot de passe</label>
                            <input type="password" name="password"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:bg-white transition-colors"
                                   style="--tw-ring-color: var(--accent)"
                                   placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Confirmation</label>
                            <input type="password" name="password_confirmation"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:bg-white transition-colors"
                                   style="--tw-ring-color: var(--accent)"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit"
                                class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-semibold rounded-xl transition-all active:scale-95">
                            Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>

            {{-- Danger zone --}}
            <div class="bg-white rounded-2xl shadow-sm p-6 border border-red-100">
                <h2 class="font-semibold text-red-600 mb-1">Zone dangereuse</h2>
                <p class="text-slate-500 text-sm mb-4">La suppression de votre compte est irréversible. Toutes vos données seront perdues.</p>
                <button type="button"
                        onclick="confirm('Supprimer définitivement votre compte ?') && document.getElementById('delete-account-form').submit()"
                        class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold rounded-xl border border-red-200 transition-colors">
                    Supprimer mon compte
                </button>
                <form id="delete-account-form" method="POST" action="{{ route('logout') }}" class="hidden">@csrf</form>
            </div>

        </div>
    </div>
</div>
@endsection
