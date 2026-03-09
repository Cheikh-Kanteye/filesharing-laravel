@extends('layouts.auth')
@section('title', 'Réinitialiser le mot de passe')

@section('content')
<div class="mb-6">
    <h2 class="text-xl font-semibold text-slate-800">Nouveau mot de passe</h2>
    <p class="text-sm text-slate-500 mt-1">Choisissez un mot de passe sécurisé d'au moins 8 caractères.</p>
</div>

@if($errors->any())
<div class="mb-5 flex items-center gap-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-xl px-4 py-3">
    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
    </svg>
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('password.update') }}" class="space-y-5">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Adresse email</label>
        <input type="email" name="email" value="{{ old('email', $email) }}" required autofocus
               class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50"
               placeholder="vous@exemple.com">
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nouveau mot de passe</label>
        <div class="relative">
            <input type="password" name="password" id="password" required minlength="8"
                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
                   placeholder="Minimum 8 caractères">
            <button type="button" onclick="togglePassword('password', 'eye1')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                <svg id="eye1" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </button>
        </div>
        {{-- Indicateur de force --}}
        <div id="strength-bar" class="mt-2 h-1 rounded-full bg-slate-100 overflow-hidden">
            <div id="strength-fill" class="h-full rounded-full transition-all duration-300 w-0"></div>
        </div>
        <p id="strength-label" class="text-xs text-slate-400 mt-1"></p>
    </div>

    <div>
        <label class="block text-sm font-medium text-slate-700 mb-1.5">Confirmer le mot de passe</label>
        <div class="relative">
            <input type="password" name="password_confirmation" id="password_confirmation" required
                   class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10"
                   placeholder="Répéter le mot de passe">
            <button type="button" onclick="togglePassword('password_confirmation', 'eye2')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                <svg id="eye2" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            </button>
        </div>
        <p id="match-msg" class="text-xs mt-1 hidden"></p>
    </div>

    <button type="submit"
            class="w-full py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors">
        Réinitialiser le mot de passe
    </button>
</form>

<p class="mt-6 text-center text-sm text-slate-500">
    <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-blue-600 font-medium hover:underline">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Retour à la connexion
    </a>
</p>

<script>
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    input.type = input.type === 'password' ? 'text' : 'password';
}

// Indicateur de force du mot de passe
document.getElementById('password').addEventListener('input', function () {
    const val = this.value;
    const fill = document.getElementById('strength-fill');
    const label = document.getElementById('strength-label');
    let score = 0;
    if (val.length >= 8) score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { width: '0%',   color: '',                  text: '' },
        { width: '25%',  color: 'bg-red-400',        text: 'Très faible' },
        { width: '50%',  color: 'bg-orange-400',     text: 'Faible' },
        { width: '75%',  color: 'bg-yellow-400',     text: 'Moyen' },
        { width: '100%', color: 'bg-emerald-500',    text: 'Fort' },
    ];
    const lvl = val.length === 0 ? levels[0] : levels[score] || levels[1];
    fill.className = `h-full rounded-full transition-all duration-300 ${lvl.color}`;
    fill.style.width = lvl.width;
    label.textContent = lvl.text;
    label.className = `text-xs mt-1 ${score >= 3 ? 'text-emerald-600' : 'text-slate-400'}`;
});

// Vérification de correspondance
document.getElementById('password_confirmation').addEventListener('input', function () {
    const pwd = document.getElementById('password').value;
    const msg = document.getElementById('match-msg');
    if (!this.value) { msg.classList.add('hidden'); return; }
    msg.classList.remove('hidden');
    if (this.value === pwd) {
        msg.textContent = '✓ Les mots de passe correspondent';
        msg.className = 'text-xs mt-1 text-emerald-600';
    } else {
        msg.textContent = '✗ Les mots de passe ne correspondent pas';
        msg.className = 'text-xs mt-1 text-red-500';
    }
});
</script>
@endsection
