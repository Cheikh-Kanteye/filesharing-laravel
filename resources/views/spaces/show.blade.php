@extends('layouts.app')
@section('title', $space->name)

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-start justify-between">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-lg" style="background-color: {{ $space->color }}">
                {{ strtoupper(substr($space->name, 0, 2)) }}
            </div>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">{{ $space->name }}</h1>
                @if($space->description)
                <p class="text-slate-500 text-sm mt-0.5">{{ $space->description }}</p>
                @endif
                <div class="flex items-center gap-3 mt-1 text-xs text-slate-400">
                    <span>Par {{ $space->owner->name }}</span>
                    <span>·</span>
                    <span>{{ $space->members_count }} membre(s)</span>
                    <span>·</span>
                    <span>{{ $files->total() }} fichier(s)</span>
                    @if($space->is_public)
                    <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded-full font-medium">Public</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @auth
                @if($space->owner_id === auth()->id())
                <a href="{{ route('spaces.edit', $space) }}" class="px-3 py-2 text-sm text-slate-600 hover:text-slate-800 bg-white border border-slate-200 hover:border-slate-300 rounded-lg transition-colors">
                    Paramètres
                </a>
                @elseif($space->isMember(auth()->user()))
                <form method="POST" action="{{ route('spaces.leave', $space) }}" onsubmit="return confirm('Quitter ce space ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-3 py-2 text-sm text-red-500 hover:text-red-600 bg-white border border-slate-200 hover:border-red-200 rounded-lg transition-colors">
                        Quitter
                    </button>
                </form>
                @endif
                <button onclick="document.getElementById('upload-modal').classList.remove('hidden')"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Uploader
                </button>
            @else
                {{-- Visiteur non connecté sur un space public --}}
                <a href="{{ route('login') }}"
                   class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Se connecter pour participer
                </a>
            @endauth
        </div>
    </div>

    {{-- Bandeau visiteur --}}
    @guest
    <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-xl px-5 py-3.5 text-sm">
        <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="text-amber-800">
            Vous consultez ce space en tant que visiteur.
            <a href="{{ route('login') }}" class="font-semibold underline hover:text-amber-900">Connectez-vous</a>
            ou <a href="{{ route('register') }}" class="font-semibold underline hover:text-amber-900">créez un compte</a>
            pour uploader des fichiers et collaborer.
        </p>
    </div>
    @endguest

    {{-- Filters & Search --}}
    <div class="flex items-center gap-3 flex-wrap">
        <form action="{{ route('spaces.show', $space) }}" method="GET" class="flex items-center gap-3 flex-1">
            <div class="relative flex-1 max-w-sm">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Rechercher dans ce space..."
                       class="w-full pl-10 pr-4 py-2 text-sm bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <select name="type" class="px-3 py-2 text-sm bg-white border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Tous les types</option>
                <option value="application/pdf" {{ request('type') === 'application/pdf' ? 'selected' : '' }}>PDF</option>
                <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Images</option>
                <option value="application/vnd" {{ request('type') === 'application/vnd' ? 'selected' : '' }}>Documents Office</option>
                <option value="application/zip" {{ request('type') === 'application/zip' ? 'selected' : '' }}>Archives</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 text-sm rounded-lg hover:bg-slate-50 transition-colors">
                Filtrer
            </button>
            @if(request('search') || request('type') || request('tag'))
            <a href="{{ route('spaces.show', $space) }}" class="text-sm text-blue-600 hover:underline">Réinitialiser</a>
            @endif
        </form>

        {{-- Tags filter --}}
        @if($userTags->isNotEmpty())
        <div class="flex items-center gap-2">
            @foreach($userTags->take(5) as $tag)
            <a href="{{ route('spaces.show', $space) }}?tag={{ $tag->name }}"
               class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium border transition-colors {{ request('tag') === $tag->name ? 'text-white border-transparent' : 'text-slate-600 border-slate-200 bg-white hover:border-slate-300' }}"
               style="{{ request('tag') === $tag->name ? 'background-color:'.$tag->color.';border-color:'.$tag->color : '' }}">
                {{ $tag->name }}
            </a>
            @endforeach
        </div>
        @endif
    </div>

    <div class="grid grid-cols-4 gap-6">
        {{-- Files list --}}
        <div class="col-span-3 space-y-3" id="file-list" data-space-id="{{ $space->id }}">
            @if($files->isEmpty())
            <div class="bg-white rounded-xl border border-slate-200 border-dashed p-16 text-center" id="empty-state">
                <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-slate-500 font-medium text-sm">Aucun fichier {{ request('search') ? 'trouvé' : 'dans ce space' }}</p>
                <p class="text-slate-400 text-xs mt-1">{{ request('search') ? 'Essayez une autre recherche' : 'Uploadez le premier fichier !' }}</p>
            </div>
            @else
            @foreach($files as $file)
            <div id="file-card-{{ $file->id }}" class="bg-white rounded-xl border border-slate-200 p-4 hover:border-slate-300 hover:shadow-sm transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-lg bg-slate-50 flex items-center justify-center flex-shrink-0 border border-slate-100">
                        @include('components.file-icon', ['type' => $file->icon])
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0">
                                <h3 class="font-medium text-slate-800 text-sm truncate">{{ $file->title }}</h3>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    {{ $file->original_name }} · {{ $file->formatted_size }} · Partagé par {{ $file->user->name }}
                                </p>
                                @if($file->tags->isNotEmpty())
                                <div class="flex items-center gap-1.5 mt-2">
                                    @foreach($file->tags as $tag)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white" style="background-color: {{ $tag->color }}">
                                        {{ $tag->name }}
                                    </span>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0">
                                <a href="{{ route('files.download', $file) }}"
                                   class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                   title="Télécharger">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>

                                {{-- Actions membres connectés seulement --}}
                                @auth
                                <button onclick="openCopyModal({{ $file->id }})"
                                        class="p-2 text-slate-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-colors"
                                        title="Copier vers">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                    </svg>
                                </button>
                                @endauth

                                {{-- Share link --}}
                                @if(auth()->check() && ($file->user_id === auth()->id() || $space->owner_id === auth()->id()))
                                <form method="POST" action="{{ route('files.share', $file) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Générer lien de partage">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                        </svg>
                                    </button>
                                </form>

                                @if($file->isShareValid())
                                <button onclick="copyLink('{{ url('/share/'.$file->share_token) }}')"
                                        class="p-2 text-emerald-500 hover:bg-emerald-50 rounded-lg transition-colors" title="Copier le lien">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                </button>
                                @endif

                                <form method="POST" action="{{ route('files.destroy', $file) }}" onsubmit="return confirm('Supprimer ce fichier ?')" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            {{ $files->withQueryString()->links() }}
            @endif
        </div>

        {{-- Sidebar: members + invite + folders --}}
        <div class="space-y-5">
            {{-- Invite form + join link --}}
            @if(auth()->check() && $space->owner_id === auth()->id())
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Inviter par email</h3>
                <form method="POST" action="{{ route('spaces.invite', $space) }}">
                    @csrf
                    <input type="email" name="email" placeholder="email@exemple.com" required
                           class="w-full px-3 py-2 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2">
                    <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                        Envoyer l'invitation
                    </button>
                </form>

                {{-- Pending invitations list --}}
                @php
                    $pendingInvitations = $space->invitations()->whereNull('accepted_at')->where('expires_at', '>', now())->get();
                @endphp
                @if($pendingInvitations->isNotEmpty())
                <div class="mt-3 pt-3 border-t border-slate-100">
                    <p class="text-xs text-slate-400 mb-2">En attente ({{ $pendingInvitations->count() }})</p>
                    @foreach($pendingInvitations as $inv)
                    <div class="flex items-center justify-between py-1">
                        <span class="text-xs text-slate-600 truncate flex-1">{{ $inv->email }}</span>
                        <span class="text-xs text-slate-400 ml-2 flex-shrink-0">{{ $inv->expires_at->diffForHumans(null, true) }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Join link --}}
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex items-center justify-between mb-2">
                    <h3 class="text-sm font-semibold text-slate-700">Lien de partage</h3>
                    @if($space->join_token)
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    @endif
                </div>

                @if($space->join_token)
                <div class="flex items-center gap-1.5">
                    <div class="flex-1 px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded text-xs text-slate-500 font-mono truncate">
                        {{ $space->join_url }}
                    </div>
                    <button onclick="copyJoinLink('{{ $space->join_url }}')"
                            class="p-1.5 bg-slate-800 hover:bg-slate-900 text-white rounded transition-colors flex-shrink-0" title="Copier">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                        </svg>
                    </button>
                </div>
                <div class="flex gap-3 mt-2">
                    <form method="POST" action="{{ route('spaces.join-link.generate', $space) }}">
                        @csrf
                        <button type="submit" class="text-xs text-slate-400 hover:text-slate-600 underline">Regénérer</button>
                    </form>
                    <form method="POST" action="{{ route('spaces.join-link.revoke', $space) }}" onsubmit="return confirm('Révoquer ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:text-red-600 underline">Révoquer</button>
                    </form>
                </div>
                @else
                <form method="POST" action="{{ route('spaces.join-link.generate', $space) }}">
                    @csrf
                    <button type="submit" class="w-full py-2 text-sm text-slate-600 border border-dashed border-slate-300 rounded-lg hover:border-blue-400 hover:text-blue-600 transition-colors">
                        + Générer un lien
                    </button>
                </form>
                @endif
            </div>
            @endif

            {{-- Members --}}
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <h3 class="text-sm font-semibold text-slate-700 mb-3">Membres ({{ $space->members_count }})</h3>
                <div class="space-y-2.5">
                    {{-- Owner --}}
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                            {{ $space->owner->initials }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-slate-700 truncate">{{ $space->owner->name }}</p>
                        </div>
                        <span class="text-xs text-blue-600 font-medium">Admin</span>
                    </div>
                    @foreach($space->members as $member)
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-slate-300 flex items-center justify-center text-white text-xs font-semibold flex-shrink-0">
                            {{ $member->initials }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-slate-700 truncate">{{ $member->name }}</p>
                        </div>
                        @if(auth()->check() && $space->owner_id === auth()->id())
                        <form method="POST" action="{{ route('spaces.members.remove', [$space, $member]) }}">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-slate-300 hover:text-red-400 transition-colors">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Folders --}}
            <div class="bg-white rounded-xl border border-slate-200 p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-slate-700">Dossiers</h3>
                    <button onclick="document.getElementById('folder-form').classList.toggle('hidden')" class="text-blue-600 hover:text-blue-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </button>
                </div>
                <form id="folder-form" method="POST" action="{{ route('folders.store', $space) }}" class="hidden mb-3">
                    @csrf
                    <input type="text" name="name" placeholder="Nom du dossier" required
                           class="w-full px-3 py-1.5 text-xs border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2">
                    <button type="submit" class="w-full py-1.5 bg-blue-600 text-white text-xs font-medium rounded-lg">Créer</button>
                </form>
                @if($folders->isEmpty())
                <p class="text-xs text-slate-400">Aucun dossier</p>
                @else
                <div class="space-y-1">
                    @foreach($folders as $folder)
                    <div class="flex items-center gap-2 py-1">
                        <svg class="w-4 h-4 text-yellow-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"/>
                        </svg>
                        <span class="text-xs text-slate-700 flex-1 truncate">{{ $folder->name }}</span>
                        <span class="text-xs text-slate-400">{{ $folder->files->count() }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Upload Modal --}}
<div id="upload-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Uploader un fichier</h2>
            <button onclick="document.getElementById('upload-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form method="POST" action="{{ route('files.upload', $space) }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            <div class="border-2 border-dashed border-slate-200 rounded-xl p-8 text-center hover:border-blue-300 transition-colors cursor-pointer" onclick="document.getElementById('file-input').click()">
                <svg class="w-10 h-10 text-slate-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                <p class="text-sm text-slate-500">Cliquez ou glissez un fichier</p>
                <p class="text-xs text-slate-400 mt-1">PDF, DOCX, PPTX, XLSX, ZIP, Images · Max 50MB</p>
                <input type="file" id="file-input" name="file" required class="hidden"
                       accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.jpg,.jpeg,.png,.gif,.webp,.txt"
                       onchange="document.getElementById('file-name').textContent = this.files[0]?.name || ''">
                <p id="file-name" class="text-xs text-blue-600 mt-2 font-medium"></p>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Titre *</label>
                <input type="text" name="title" required placeholder="Titre du fichier"
                       class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                <textarea name="description" rows="2" placeholder="Description optionnelle"
                          class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>

            @if($userTags->isNotEmpty())
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Tags</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($userTags as $tag)
                    <label class="cursor-pointer">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="sr-only peer">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border border-transparent peer-checked:ring-2 peer-checked:ring-offset-1 cursor-pointer transition-all text-white" style="background-color: {{ $tag->color }}; --tw-ring-color: {{ $tag->color }}">
                            {{ $tag->name }}
                        </span>
                    </label>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex gap-3 pt-2">
                <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                    Uploader le fichier
                </button>
                <button type="button" onclick="document.getElementById('upload-modal').classList.add('hidden')"
                        class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-lg transition-colors">
                    Annuler
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Copy Modal --}}
<div id="copy-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-sm mx-4">
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Copier vers un Space</h2>
            <button onclick="document.getElementById('copy-modal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <form id="copy-form" method="POST" action="" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Space de destination</label>
                <select name="target_space_id" required class="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(auth()->user()->ownedSpaces as $s)
                    @if($s->id !== $space->id)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endif
                    @endforeach
                    @foreach(auth()->user()->joinedSpaces as $s)
                    @if($s->id !== $space->id)
                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endif
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">Copier</button>
                <button type="button" onclick="document.getElementById('copy-modal').classList.add('hidden')"
                        class="px-4 py-2.5 bg-slate-100 text-slate-700 text-sm font-medium rounded-lg hover:bg-slate-200 transition-colors">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
function copyJoinLink(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('Lien de partage copié dans le presse-papiers !');
    });
}
function openCopyModal(fileId) {
    document.getElementById('copy-form').action = '/files/' + fileId + '/copy';
    document.getElementById('copy-modal').classList.remove('hidden');
}
function copyLink(url) {
    navigator.clipboard.writeText(url).then(() => alert('Lien copié dans le presse-papiers !'));
}
// Close modals on backdrop click
['upload-modal', 'copy-modal'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });
});
</script>

@push('scripts')
@auth
<script>
function _initSpaceWS() {
    const log = (...args) => console.log('%c[WS]', 'color:#6366f1;font-weight:bold', ...args);
    const err = (...args) => console.error('%c[WS]', 'color:#ef4444;font-weight:bold', ...args);

    log('✅ Echo prêt, broadcaster:', window.Echo.options?.broadcaster);

    // spaceId
    const fileList = document.getElementById('file-list');
    const spaceId  = fileList ? fileList.dataset.spaceId : null;
    if (!spaceId) { err('Élément #file-list introuvable'); return; }
    log('Space ID:', spaceId);

    // État connexion WebSocket
    const connector = window.Echo.connector?.pusher;
    if (connector) {
        connector.connection.bind('connecting',   () => log('Connexion en cours...'));
        connector.connection.bind('connected',    () => log('✅ Connecté (Reverb)'));
        connector.connection.bind('disconnected', () => err('❌ Déconnecté'));
        connector.connection.bind('failed',       () => err('❌ Connexion échouée'));
        connector.connection.bind('error',        (e) => err('Erreur:', e));
        log('État actuel:', connector.connection.state);
    }

    const fileIcons = {
        pdf:   `<svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>`,
        image: `<svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/></svg>`,
        zip:   `<svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm3 1h2v2H7V5zm0 4h2v2H7V9zm0 4h2v2H7v-2z" clip-rule="evenodd"/></svg>`,
        doc:   `<svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/></svg>`,
    };

    function esc(s) {
        return String(s ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function renderFileCard(f) {
        const icon  = fileIcons[f.icon] ?? fileIcons.doc;
        const tags  = (f.tags || []).map(t =>
            `<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white" style="background-color:${esc(t.color)}">${esc(t.name)}</span>`
        ).join('');

        return `
        <div id="file-card-${f.id}" class="bg-white rounded-xl border border-slate-200 p-4 hover:border-slate-300 hover:shadow-sm transition-all group animate-pulse-once ring-2 ring-blue-200">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 rounded-lg bg-slate-50 flex items-center justify-center flex-shrink-0 border border-slate-100">
                    ${icon}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h3 class="font-medium text-slate-800 text-sm truncate">${esc(f.title)}</h3>
                            <p class="text-xs text-slate-400 mt-0.5">
                                ${esc(f.original_name)} · ${esc(f.size)} · Partagé par ${esc(f.user.name)}
                            </p>
                            ${tags ? `<div class="flex items-center gap-1.5 mt-2">${tags}</div>` : ''}
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <a href="${esc(f.download_url)}"
                               class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                               title="Télécharger">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }

    // 4. Souscription au canal
    log('Souscription au canal privé: space.' + spaceId);
    const channel = window.Echo.private('space.' + spaceId);

    channel.subscribed(() => log('✅ Canal space.' + spaceId + ' souscrit'));
    channel.error((e)     => err('Erreur canal:', e));

    channel
        .listen('.file.uploaded', (data) => {
            log('📥 file.uploaded reçu:', data);
            // Retirer l'état vide si présent
            const empty = document.getElementById('empty-state');
            if (empty) empty.remove();

            // Insérer la carte en haut de la liste
            fileList.insertAdjacentHTML('afterbegin', renderFileCard(data));

            // Retirer le ring après 3s
            setTimeout(() => {
                const card = document.getElementById('file-card-' + data.id);
                if (card) card.classList.remove('ring-2', 'ring-blue-200');
            }, 3000);

            // Toast
            if (typeof window.showToast === 'function') {
                window.showToast(
                    `<strong>${esc(data.user.name)}</strong> a ajouté <strong>${esc(data.title)}</strong>`,
                    'success'
                );
            }
        })
        .listen('.file.deleted', (data) => {
            log('🗑️ file.deleted reçu:', data);
            const card = document.getElementById('file-card-' + data.id);
            if (card) {
                card.style.transition = 'opacity 0.4s, transform 0.4s';
                card.style.opacity    = '0';
                card.style.transform  = 'scale(0.97)';
                setTimeout(() => card.remove(), 400);
            }
        });
}

if (typeof window.Echo !== 'undefined') {
    _initSpaceWS();
} else {
    window.addEventListener('echo-ready', _initSpaceWS, { once: true });
}
</script>
@endauth
@endpush
@endsection
