@extends('layouts.auth')
@section('title', 'Connexion')
@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900">Bon retour 👋</h2>
    <p class="text-slate-500 text-sm mt-1">Connectez-vous à votre compte FileShare</p>
</div>

@if(session('success'))
<div class="mb-5 flex items-center gap-2 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-xl px-4 py-3">
    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-5 flex items-center gap-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-xl px-4 py-3">
    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf
    <div>
        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Adresse email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:border-transparent transition-colors @error('email') border-red-300 @enderror"
               style="--tw-ring-color: var(--accent)"
               placeholder="vous@exemple.com">
    </div>
    <div>
        <div class="flex items-center justify-between mb-1.5">
            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wide">Mot de passe</label>
            <a href="{{ route('password.request') }}" class="text-xs font-medium hover:underline" style="color:var(--accent)">Oublié ?</a>
        </div>
        <input type="password" name="password" required
               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:border-transparent transition-colors"
               style="--tw-ring-color: var(--accent)"
               placeholder="••••••••">
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" name="remember" id="remember" class="rounded border-slate-300" style="accent-color: var(--accent)">
        <label for="remember" class="text-sm text-slate-600">Se souvenir de moi</label>
    </div>
    <button type="submit"
            class="w-full py-3 text-white font-semibold rounded-xl text-sm transition-all hover:opacity-90 active:scale-95 shadow-sm"
            style="background-color: var(--accent)">
        Se connecter
    </button>
</form>

<p class="mt-6 text-center text-sm text-slate-500">
    Pas encore de compte ?
    <a href="{{ route('register') }}" class="font-semibold hover:underline" style="color:var(--accent)">S'inscrire gratuitement</a>
</p>
@endsection
