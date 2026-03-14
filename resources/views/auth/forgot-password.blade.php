@extends('layouts.auth')
@section('title', 'Mot de passe oublié')
@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900">Mot de passe oublié</h2>
    <p class="text-slate-500 text-sm mt-1">Entrez votre email pour recevoir un lien de réinitialisation.</p>
</div>

@if(session('success'))
<div class="mb-5 flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3.5 rounded-xl text-sm">
    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    <div>
        <p class="font-semibold">Email envoyé !</p>
        <p class="text-emerald-600 mt-0.5">{{ session('success') }}</p>
        <p class="text-emerald-500 text-xs mt-1">En dev, le lien est dans <code class="bg-emerald-100 px-1 rounded">storage/logs/laravel.log</code></p>
    </div>
</div>
@endif

@if($errors->any())
<div class="mb-5 flex items-center gap-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-xl px-4 py-3">
    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('password.email') }}" class="space-y-5">
    @csrf
    <div>
        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Adresse email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:border-transparent transition-colors"
               style="--tw-ring-color: var(--accent)"
               placeholder="vous@exemple.com">
    </div>
    <button type="submit"
            class="w-full py-3 text-white font-semibold rounded-xl text-sm transition-all hover:opacity-90 active:scale-95 shadow-sm flex items-center justify-center gap-2"
            style="background-color: var(--accent)">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Envoyer le lien
    </button>
</form>

<p class="mt-6 text-center text-sm text-slate-500">
    <a href="{{ route('login') }}" class="inline-flex items-center gap-1 font-semibold hover:underline" style="color:var(--accent)">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour à la connexion
    </a>
</p>
@endsection
