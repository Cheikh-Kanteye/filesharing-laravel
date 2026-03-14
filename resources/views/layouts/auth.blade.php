<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — FileShare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased">
<div class="min-h-screen grid grid-cols-1 md:grid-cols-2">

    {{-- Panel gauche branding --}}
    <div class="hidden md:flex flex-col justify-between p-12 relative overflow-hidden" style="background-color:#0f172a">
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full opacity-10" style="background:var(--accent)"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 rounded-full opacity-5" style="background:var(--accent);transform:translate(30%,30%)"></div>

        <div class="relative z-10 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background-color:var(--accent)">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
            <span class="text-white font-bold text-xl">FileShare</span>
        </div>

        <div class="relative z-10 space-y-8">
            <div>
                <h2 class="text-4xl font-bold text-white leading-tight">Partagez vos<br><span style="color:var(--accent)">documents</span><br>en toute simplicité</h2>
                <p class="text-slate-400 text-base mt-4 leading-relaxed max-w-sm">Une plateforme collaborative pour les étudiants. Organisez, partagez et collaborez sur vos fichiers en temps réel.</p>
            </div>
            <div class="space-y-4">
                <div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg flex-shrink-0 flex items-center justify-center" style="background:rgba(249,115,22,0.15)"><svg class="w-4 h-4" style="color:var(--accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div><span class="text-slate-300 text-sm">Spaces collaboratifs avec membres</span></div>
                <div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg flex-shrink-0 flex items-center justify-center" style="background:rgba(249,115,22,0.15)"><svg class="w-4 h-4" style="color:var(--accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></div><span class="text-slate-300 text-sm">Mises à jour en temps réel</span></div>
                <div class="flex items-center gap-3"><div class="w-8 h-8 rounded-lg flex-shrink-0 flex items-center justify-center" style="background:rgba(249,115,22,0.15)"><svg class="w-4 h-4" style="color:var(--accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg></div><span class="text-slate-300 text-sm">Partage sécurisé par lien</span></div>
            </div>
        </div>

        <p class="relative z-10 text-slate-600 text-xs">© {{ date('Y') }} FileShare — Plateforme étudiante</p>
    </div>

    {{-- Panel droit formulaire --}}
    <div class="flex flex-col justify-center px-8 py-12 sm:px-12 md:px-16 xl:px-24 bg-slate-50">
        <div class="md:hidden flex items-center gap-2 mb-10">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color:var(--accent)">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            </div>
            <span class="font-bold text-slate-900 text-lg">FileShare</span>
        </div>
        <div class="w-full max-w-sm mx-auto">
            @yield('content')
        </div>
    </div>

</div>
</body>
</html>
