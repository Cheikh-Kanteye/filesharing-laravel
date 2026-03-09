@extends('layouts.auth')
@section('title', 'Connexion')

@section('content')
<h2 class="text-xl font-semibold text-slate-800 mb-6">Connexion</h2>

@if(session('success'))
<div class="mb-4 flex items-center gap-2 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3">
    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div class="mb-4 flex items-center gap-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-lg px-4 py-3">
    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf
    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Adresse email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('email') border-red-300 @enderror"
               placeholder="vous@exemple.com">
    </div>
    <div>
        <div class="flex items-center justify-between mb-1.5">
            <label class="text-sm font-medium text-slate-700">Mot de passe</label>
            <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:underline">Mot de passe oublié ?</a>
        </div>
        <input type="password" name="password" required
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
               placeholder="••••••••">
    </div>
    <div class="flex items-center gap-2">
        <input type="checkbox" name="remember" id="remember" class="rounded border-slate-300 text-blue-600">
        <label for="remember" class="text-sm text-slate-600">Se souvenir de moi</label>
    </div>
    <button type="submit" class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors">
        Se connecter
    </button>
</form>

<p class="mt-6 text-center text-sm text-slate-500">
    Pas encore de compte ?
    <a href="{{ route('register') }}" class="text-blue-600 font-medium hover:underline">S'inscrire</a>
</p>
@endsection
