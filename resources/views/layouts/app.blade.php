<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FileShare') — FileShare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 font-sans antialiased">

<div class="flex h-full">

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- SIDEBAR                                                     --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <aside class="fixed inset-y-0 left-0 z-50 w-60 flex flex-col" style="background-color: var(--sidebar-bg);">

        {{-- Logo --}}
        <div class="flex items-center gap-3 px-5 py-5 flex-shrink-0">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0" style="background-color: var(--accent);">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            <span class="font-bold text-white text-lg tracking-tight">File<span style="color: var(--accent);">Share</span></span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-2 overflow-y-auto sidebar-scroll space-y-0.5">

            {{-- Group: Principal --}}
            <p class="px-3 pt-3 pb-1.5 text-xs font-semibold uppercase tracking-widest text-slate-500">Principal</p>

            <a href="{{ route('dashboard') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('dashboard') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg class="w-4.5 h-4.5 flex-shrink-0" style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v5a1 1 0 01-1 1H5a1 1 0 01-1-1V5zm10 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zm10-2a1 1 0 011-1h4a1 1 0 011 1v6a1 1 0 01-1 1h-4a1 1 0 01-1-1v-6z"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('spaces.index') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('spaces.index') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Mes Spaces
            </a>

            <a href="{{ route('spaces.explore') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('spaces.explore') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Explorer
            </a>

            {{-- Group: Fichiers --}}
            <p class="px-3 pt-5 pb-1.5 text-xs font-semibold uppercase tracking-widest text-slate-500">Fichiers</p>

            <a href="{{ route('search') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('search') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Recherche
            </a>

            @auth
            <a href="{{ route('invitations.index') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('invitations.*') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Invitations
                <span id="invitation-badge"
                      class="ml-auto flex h-5 w-5 items-center justify-center rounded-full text-xs font-bold text-white {{ $pendingInvitationsCount > 0 ? '' : 'hidden' }}"
                      style="background-color: var(--accent);">
                    {{ $pendingInvitationsCount }}
                </span>
            </a>
            @endauth

            {{-- Group: Compte --}}
            <p class="px-3 pt-5 pb-1.5 text-xs font-semibold uppercase tracking-widest text-slate-500">Compte</p>

            @auth
            <a href="{{ route('profile') }}"
               class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ request()->routeIs('profile') ? 'nav-active' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                <svg style="width:18px;height:18px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Profil
            </a>
            @endauth

            {{-- Recent spaces --}}
            @auth
            <div class="pt-4">
                <p class="px-3 pb-1.5 text-xs font-semibold uppercase tracking-widest text-slate-500">Récents</p>
                @foreach($sidebarSpaces as $sideSpace)
                <a href="{{ route('spaces.show', $sideSpace) }}"
                   class="nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all {{ request()->is('spaces/'.$sideSpace->id.'*') ? 'text-orange-400' : 'text-slate-400 hover:text-white hover:bg-white/5' }}">
                    <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: {{ $sideSpace->color }}"></span>
                    <span class="truncate">{{ $sideSpace->name }}</span>
                </a>
                @endforeach
            </div>
            @endauth
        </nav>

        {{-- User footer --}}
        @auth
        <div class="flex-shrink-0 border-t px-3 py-3" style="border-color: rgba(255,255,255,0.06);" data-user-id="{{ auth()->id() }}">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0"
                     style="background-color: var(--accent);">
                    {{ auth()->user()->initials }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ auth()->user()->email }}</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 rounded-md text-slate-500 hover:text-red-400 hover:bg-white/5 transition-colors" title="Déconnexion">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </aside>

    {{-- ═══════════════════════════════════════════════════════════ --}}
    {{-- MAIN CONTENT                                                --}}
    {{-- ═══════════════════════════════════════════════════════════ --}}
    <div class="flex-1 flex flex-col ml-60 min-h-screen bg-white/80 backdrop-blur-sm">

        {{-- Top bar --}}
        <header class="bg-white border-b border-slate-100  flex items-center px-8 py-3 gap-6 sticky top-0 z-40 shadow-sm">

            {{-- Page title / breadcrumb --}}
            <div class="flex-shrink-0">
                <span class="text-sm font-semibold text-slate-800">@yield('title', 'Dashboard')</span>
            </div>

            {{-- Search (center) --}}
            <div class="flex-1 max-w-md mx-auto" id="search-container">
                <form action="{{ route('search') }}" method="GET" class="relative" id="search-form" autocomplete="off">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="search" name="q" id="global-search" value="{{ request('q') }}"
                           placeholder="Rechercher des fichiers... (↵ pour tout voir)"
                           class="w-full pl-10 pr-10 py-2 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:border-transparent transition-all"
                           style="--tw-ring-color: var(--accent);"
                           spellcheck="false">
                    <span id="search-spinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="w-4 h-4 animate-spin" style="color: var(--accent);" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                    </span>
                </form>

                {{-- Dropdown results --}}
                <div id="search-dropdown"
                     class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-2xl shadow-xl overflow-hidden z-50 max-h-96 overflow-y-auto">
                    <div id="search-results"></div>
                </div>
            </div>

            {{-- Right actions --}}
            <div class="flex items-center gap-3 flex-shrink-0">
                {{-- Notification bell --}}
                @auth
                <a href="{{ route('invitations.index') }}" class="relative p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if(isset($pendingInvitationsCount) && $pendingInvitationsCount > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 rounded-full" style="background-color: var(--accent);"></span>
                    @endif
                </a>
                @endauth

                {{-- New space button --}}
                <a href="{{ route('spaces.create') }}"
                   class="flex items-center gap-2 px-4 py-2 text-white text-sm font-medium rounded-xl transition-colors"
                   style="background-color: var(--accent);"
                   onmouseover="this.style.backgroundColor='var(--accent-hover)'"
                   onmouseout="this.style.backgroundColor='var(--accent)'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau Space
                </a>

                {{-- User avatar --}}
                @auth
                <a href="{{ route('profile') }}"
                   class="w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0 ring-2 ring-offset-1 transition-all hover:ring-offset-2"
                   style="background-color: var(--accent); ring-color: var(--accent);"
                   title="{{ auth()->user()->name }}">
                    {{ auth()->user()->initials }}
                </a>
                @endauth
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="mx-8 mt-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm" id="flash-msg">
            <svg class="w-4 h-4 flex-shrink-0 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error') || $errors->any())
        <div class="mx-8 mt-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm">
            <svg class="w-4 h-4 flex-shrink-0 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') ?? $errors->first() }}
        </div>
        @endif

        {{-- Page content --}}
        <main class="flex-1 px-8 py-6">
            @yield('content')
        </main>
    </div>
</div>

<script>
    // Auto-dismiss flash messages
    setTimeout(() => {
        const msg = document.getElementById('flash-msg');
        if (msg) msg.style.transition = 'opacity 0.5s', msg.style.opacity = '0', setTimeout(() => msg.remove(), 500);
    }, 4000);

    // Live search
    (function () {
        const input    = document.getElementById('global-search');
        const dropdown = document.getElementById('search-dropdown');
        const results  = document.getElementById('search-results');
        const spinner  = document.getElementById('search-spinner');
        const form     = document.getElementById('search-form');
        if (!input) return;

        let timer = null;
        let ctrl  = null;

        const icons = {
            pdf:   '<svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>',
            image: '<svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>',
            zip:   '<svg class="w-5 h-5 text-yellow-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h2v2H7V5zm0 4h2v2H7V9zm0 4h2v2H7v-2z" clip-rule="evenodd"/></svg>',
            doc:   '<svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>',
        };

        function getIcon(mime) {
            if (!mime) return icons.doc;
            if (mime.includes('pdf'))   return icons.pdf;
            if (mime.includes('image')) return icons.image;
            if (mime.includes('zip') || mime.includes('compressed')) return icons.zip;
            return icons.doc;
        }

        function escHtml(str) {
            return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        function renderResults(data) {
            if (!data.length) {
                results.innerHTML = '<p class="px-4 py-6 text-sm text-slate-400 text-center">Aucun résultat</p>';
                dropdown.classList.remove('hidden');
                return;
            }
            results.innerHTML = data.map(f => {
                const tags = f.tags.map(t =>
                    `<span class="inline-block px-1.5 py-0.5 rounded text-xs font-medium" style="background:${escHtml(t.color)}20;color:${escHtml(t.color)}">${escHtml(t.name)}</span>`
                ).join(' ');
                return `
                <div class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 border-b border-slate-100 last:border-0 group">
                    ${getIcon(f.mime_type)}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate">${escHtml(f.title || f.original_name)}</p>
                        <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                            <a href="${escHtml(f.space.url)}" class="inline-flex items-center gap-1 text-xs text-slate-500 hover:text-orange-600">
                                <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:${escHtml(f.space.color)}"></span>
                                ${escHtml(f.space.name)}
                            </a>
                            <span class="text-xs text-slate-400">${escHtml(f.size)}</span>
                            ${tags}
                        </div>
                    </div>
                    <a href="${escHtml(f.download_url)}" class="opacity-0 group-hover:opacity-100 p-1.5 rounded-lg text-slate-400 hover:text-orange-600 hover:bg-orange-50 transition-all" title="Télécharger">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                </div>`;
            }).join('');
            dropdown.classList.remove('hidden');
        }

        async function doSearch(q) {
            if (ctrl) ctrl.abort();
            ctrl = new AbortController();
            spinner.classList.remove('hidden');
            try {
                const res  = await fetch(`/api/search?q=${encodeURIComponent(q)}`, { signal: ctrl.signal });
                const data = await res.json();
                renderResults(data);
            } catch (e) {
                if (e.name !== 'AbortError') results.innerHTML = '';
            } finally {
                spinner.classList.add('hidden');
            }
        }

        input.addEventListener('input', () => {
            clearTimeout(timer);
            const q = input.value.trim();
            if (q.length < 2) { dropdown.classList.add('hidden'); return; }
            timer = setTimeout(() => doSearch(q), 300);
        });

        input.addEventListener('keydown', e => {
            if (e.key === 'Escape') { dropdown.classList.add('hidden'); input.blur(); }
            if (e.key === 'Enter')  { dropdown.classList.add('hidden'); form.submit(); }
        });

        document.addEventListener('click', e => {
            if (!document.getElementById('search-container').contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });

        input.addEventListener('focus', () => {
            if (input.value.trim().length >= 2 && results.innerHTML) {
                dropdown.classList.remove('hidden');
            }
        });
    })();
</script>

@auth
<script>
// ── Toast helper ─────────────────────────────────────────────────────────────
function escHtml(str) {
    return String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
window.showToast = function (html, type = 'success') {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        container.className = 'fixed bottom-4 right-4 z-[9999] flex flex-col gap-2';
        document.body.appendChild(container);
    }
    const colors = { success: 'bg-emerald-600', error: 'bg-red-600', info: 'bg-orange-500' };
    const toast = document.createElement('div');
    toast.className = `flex items-center gap-3 px-4 py-3 rounded-xl shadow-lg text-white text-sm max-w-xs translate-y-2 opacity-0 transition-all duration-300 ${colors[type] ?? colors.info}`;
    toast.innerHTML = html;
    container.appendChild(toast);
    requestAnimationFrame(() => toast.classList.remove('translate-y-2', 'opacity-0'));
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 350);
    }, 4500);
};

// ── Notifications temps réel ──────────────────────────────────────────────────
function _initEchoNotifications() {
    const log = (...a) => console.log('%c[Echo]', 'color:#f97316;font-weight:bold', ...a);
    const userFooter = document.querySelector('[data-user-id]');
    if (!userFooter) return;
    const userId = userFooter.dataset.userId;
    log('Echo prêt — User ID:', userId);

    window.Echo.private('user.' + userId)
        .listen('.invitation.received', (data) => {
            const badge = document.getElementById('invitation-badge');
            if (badge) {
                const current = parseInt(badge.textContent.trim()) || 0;
                badge.textContent = current + 1;
                badge.classList.remove('hidden');
            }
            showToast(
                `<strong>${escHtml(data.inviter)}</strong> vous invite dans <strong>${escHtml(data.space_name)}</strong>`,
                'info'
            );
        });
}

if (typeof window.Echo !== 'undefined') {
    _initEchoNotifications();
} else {
    window.addEventListener('echo-ready', _initEchoNotifications, { once: true });
}
</script>
@endauth

@stack('scripts')
</body>
</html>
