<!DOCTYPE html>
<html lang="fr" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'FileShare') — FileShare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-50 font-sans antialiased">

<div class="flex h-full">
    {{-- Sidebar --}}
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200 flex flex-col">
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
            </div>
            <span class="font-semibold text-slate-800 text-lg">FileShare</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('spaces.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('spaces.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Mes Spaces
            </a>
            <a href="{{ route('search') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('search') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Recherche
            </a>

            @auth
            @php
                $pendingInvitationsCount = \App\Models\Invitation::where('email', auth()->user()->email)
                    ->whereNull('accepted_at')
                    ->where('expires_at', '>', now())
                    ->count();
            @endphp
            <a href="{{ route('invitations.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('invitations.*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Invitations
                <span id="invitation-badge" class="ml-auto flex h-5 w-5 items-center justify-center rounded-full bg-blue-600 text-xs font-semibold text-white {{ $pendingInvitationsCount > 0 ? '' : 'hidden' }}">
                    {{ $pendingInvitationsCount }}
                </span>
            </a>
            @endauth

            {{-- My Spaces List --}}
            @auth
            <div class="pt-4">
                <p class="px-3 mb-1 text-xs font-semibold text-slate-400 uppercase tracking-wider">Spaces récents</p>
                @foreach(auth()->user()->ownedSpaces()->latest()->take(5)->get() as $sideSpace)
                <a href="{{ route('spaces.show', $sideSpace) }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-colors {{ request()->is('spaces/'.$sideSpace->id.'*') ? 'bg-blue-50 text-blue-700' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                    <span class="w-2 h-2 rounded-full flex-shrink-0" style="background-color: {{ $sideSpace->color }}"></span>
                    <span class="truncate">{{ $sideSpace->name }}</span>
                </a>
                @endforeach
            </div>
            @endauth
        </nav>

        {{-- User footer --}}
        @auth
        <div class="border-t border-slate-100 p-3" data-user-id="{{ auth()->id() }}">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                    {{ auth()->user()->initials }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                </div>
                <div class="flex gap-1">
                    <a href="{{ route('profile') }}" class="p-1.5 rounded-md text-slate-400 hover:text-slate-600 hover:bg-slate-100">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-1.5 rounded-md text-slate-400 hover:text-red-500 hover:bg-red-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endauth
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col ml-64">
        {{-- Top bar --}}
        <header class="bg-white border-b border-slate-200 px-8 py-4 flex items-center justify-between sticky top-0 z-40">
            <div class="flex-1 max-w-md" id="search-container">
                <form action="{{ route('search') }}" method="GET" class="relative" id="search-form" autocomplete="off">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="search" name="q" id="global-search" value="{{ request('q') }}"
                           placeholder="Rechercher des fichiers... (↵ pour tout voir)"
                           class="w-full pl-10 pr-10 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           spellcheck="false">
                    {{-- Spinner --}}
                    <span id="search-spinner" class="hidden absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="w-4 h-4 text-blue-500 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                        </svg>
                    </span>
                </form>

                {{-- Dropdown résultats --}}
                <div id="search-dropdown"
                     class="hidden absolute top-full left-0 right-0 mt-1 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden z-50 max-h-[420px] overflow-y-auto">
                    <div id="search-results"></div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('spaces.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau Space
                </a>
            </div>
        </header>

        {{-- Flash messages --}}
        @if(session('success'))
        <div class="mx-8 mt-4 flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm" id="flash-msg">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
        @endif
        @if(session('error') || $errors->any())
        <div class="mx-8 mt-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
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
                            <a href="${escHtml(f.space.url)}" class="inline-flex items-center gap-1 text-xs text-slate-500 hover:text-blue-600">
                                <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:${escHtml(f.space.color)}"></span>
                                ${escHtml(f.space.name)}
                            </a>
                            <span class="text-xs text-slate-400">${escHtml(f.size)}</span>
                            ${tags}
                        </div>
                    </div>
                    <a href="${escHtml(f.download_url)}" class="opacity-0 group-hover:opacity-100 p-1.5 rounded-md text-slate-400 hover:text-blue-600 hover:bg-blue-50 transition-all" title="Télécharger">
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
// ── Toast helper (disponible immédiatement) ───────────────────────────────
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
    const colors = { success: 'bg-emerald-600', error: 'bg-red-600', info: 'bg-blue-600' };
    const toast = document.createElement('div');
    toast.className = `flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg text-white text-sm max-w-xs translate-y-2 opacity-0 transition-all duration-300 ${colors[type] ?? colors.info}`;
    toast.innerHTML = html;
    container.appendChild(toast);
    requestAnimationFrame(() => toast.classList.remove('translate-y-2', 'opacity-0'));
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-2');
        setTimeout(() => toast.remove(), 350);
    }, 4500);
};

// ── Notifications temps réel (attend que Echo soit prêt) ──────────────────
function _initEchoNotifications() {
    const log = (...a) => console.log('%c[Echo]', 'color:#10b981;font-weight:bold', ...a);
    const userFooter = document.querySelector('[data-user-id]');
    if (!userFooter) return;
    const userId = userFooter.dataset.userId;
    log('✅ Echo prêt — User ID:', userId);

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

// Le module ES (bootstrap.js) dispatche 'echo-ready' quand Echo est initialisé
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
