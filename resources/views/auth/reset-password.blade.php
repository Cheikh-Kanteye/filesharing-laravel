@extends('layouts.auth')
@section('title', 'Nouveau mot de passe')
@section('content')

<div class="mb-8">
    <h2 class="text-2xl font-bold text-slate-900">Nouveau mot de passe</h2>
    <p class="text-slate-500 text-sm mt-1">Choisissez un mot de passe sécurisé d'au moins 8 caractères.</p>
</div>

@if($errors->any())
<div class="mb-5 flex items-center gap-2 text-sm text-red-600 bg-red-50 border border-red-100 rounded-xl px-4 py-3">
    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
    {{ $errors->first() }}
</div>
@endif

<form method="POST" action="{{ route('password.update') }}" class="space-y-5">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div>
        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Adresse email</label>
        <input type="email" name="email" value="{{ old('email', $email) }}" required autofocus
               class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:border-transparent"
               style="--tw-ring-color: var(--accent)"
               placeholder="vous@exemple.com">
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Nouveau mot de passe</label>
        <div class="relative">
            <input type="password" name="password" id="password" required minlength="8"
                   class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:border-transparent pr-12"
                   style="--tw-ring-color: var(--accent)"
                   placeholder="Minimum 8 caractères">
            <button type="button" onclick="togglePwd('password')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
        </div>
        <div id="strength-bar" class="mt-2 h-1.5 rounded-full bg-slate-100 overflow-hidden">
            <div id="strength-fill" class="h-full rounded-full transition-all duration-300 w-0"></div>
        </div>
        <p id="strength-label" class="text-xs text-slate-400 mt-1"></p>
    </div>

    <div>
        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wide mb-1.5">Confirmer</label>
        <div class="relative">
            <input type="password" name="password_confirmation" id="password_confirmation" required
                   class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm text-slate-900 focus:outline-none focus:ring-2 focus:border-transparent pr-12"
                   style="--tw-ring-color: var(--accent)"
                   placeholder="Répéter le mot de passe">
            <button type="button" onclick="togglePwd('password_confirmation')"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 p-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            </button>
        </div>
        <p id="match-msg" class="text-xs mt-1 hidden"></p>
    </div>

    <button type="submit"
            class="w-full py-3 text-white font-semibold rounded-xl text-sm transition-all hover:opacity-90 active:scale-95 shadow-sm"
            style="background-color: var(--accent)">
        Réinitialiser le mot de passe
    </button>
</form>

<p class="mt-6 text-center text-sm text-slate-500">
    <a href="{{ route('login') }}" class="inline-flex items-center gap-1 font-semibold hover:underline" style="color:var(--accent)">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
        Retour à la connexion
    </a>
</p>

<script>
function togglePwd(id) {
    const i = document.getElementById(id);
    i.type = i.type === 'password' ? 'text' : 'password';
}
document.getElementById('password').addEventListener('input', function () {
    const v = this.value, fill = document.getElementById('strength-fill'), label = document.getElementById('strength-label');
    let s = 0;
    if (v.length >= 8) s++;
    if (/[A-Z]/.test(v)) s++;
    if (/[0-9]/.test(v)) s++;
    if (/[^A-Za-z0-9]/.test(v)) s++;
    const levels = [['0%','',''],['25%','bg-red-400','Très faible'],['50%','bg-orange-400','Faible'],['75%','bg-yellow-400','Moyen'],['100%','bg-emerald-500','Fort']];
    const l = v.length === 0 ? levels[0] : (levels[s] || levels[1]);
    fill.className = `h-full rounded-full transition-all duration-300 ${l[1]}`;
    fill.style.width = l[0];
    label.textContent = l[2];
    label.className = `text-xs mt-1 ${s >= 3 ? 'text-emerald-600' : 'text-slate-400'}`;
});
document.getElementById('password_confirmation').addEventListener('input', function () {
    const msg = document.getElementById('match-msg');
    if (!this.value) { msg.classList.add('hidden'); return; }
    msg.classList.remove('hidden');
    if (this.value === document.getElementById('password').value) {
        msg.textContent = '✓ Les mots de passe correspondent';
        msg.className = 'text-xs mt-1 text-emerald-600';
    } else {
        msg.textContent = '✗ Ne correspondent pas';
        msg.className = 'text-xs mt-1 text-red-500';
    }
});
</script>
@endsection
