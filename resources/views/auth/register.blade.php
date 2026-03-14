@extends('layouts.auth')
@section('title', 'Inscription')
@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900">Créer un compte</h2>
    <p class="text-slate-500 text-sm mt-1">Rejoignez FileShare et commencez à collaborer</p>
</div>

@if($errors->any())
<div class="mb-5 flex items-center gap-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-xl px-4 py-3">
    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('register') }}" class="space-y-4">
    @csrf
    <div>
        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nom complet</label>
        <input type="text" name="name" value="{{ old('name') }}" required autofocus
               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:border-transparent transition-colors"
               style="--tw-ring-color: var(--accent)"
               placeholder="Jean Dupont">
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Adresse email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:border-transparent transition-colors"
               style="--tw-ring-color: var(--accent)"
               placeholder="vous@exemple.com">
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Mot de passe</label>
        <input type="password" name="password" required minlength="8"
               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:border-transparent transition-colors"
               style="--tw-ring-color: var(--accent)"
               placeholder="Minimum 8 caractères">
    </div>
    <div>
        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Confirmation</label>
        <input type="password" name="password_confirmation" required
               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:border-transparent transition-colors"
               style="--tw-ring-color: var(--accent)"
               placeholder="••••••••">
    </div>
    <button type="submit"
            class="w-full py-3 text-white font-semibold rounded-xl text-sm transition-all hover:opacity-90 active:scale-95 shadow-sm mt-2"
            style="background-color: var(--accent)">
        Créer mon compte
    </button>
</form>

<p class="mt-6 text-center text-sm text-slate-500">
    Déjà inscrit ?
    <a href="{{ route('login') }}" class="font-semibold hover:underline" style="color:var(--accent)">Se connecter</a>
</p>
@endsection
