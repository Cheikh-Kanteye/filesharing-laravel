@extends('layouts.auth')
@section('title', 'Inscription')

@section('content')
<h2 class="text-xl font-semibold text-slate-800 mb-6">Créer un compte</h2>

@if($errors->any())
<div class="mb-4 flex items-center gap-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg px-4 py-3">
    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nom complet</label>
        <input type="text" name="name" value="{{ old('name') }}" required autofocus
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
               placeholder="Jean Dupont">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Adresse email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
               placeholder="vous@exemple.com">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Mot de passe</label>
        <input type="password" name="password" required minlength="8"
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
               placeholder="Minimum 8 caractères">
    </div>
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirmer le mot de passe</label>
        <input type="password" name="password_confirmation" required
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
               placeholder="••••••••">
    </div>
    <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors">
        Créer mon compte
    </button>
</form>

<p class="mt-6 text-center text-sm text-slate-500">
    Déjà inscrit ?
    <a href="{{ route('login') }}" class="text-blue-600 font-medium hover:underline">Se connecter</a>
</p>
@endsection
