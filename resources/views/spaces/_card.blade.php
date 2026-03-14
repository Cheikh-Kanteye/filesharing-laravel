<div class="group bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition-all">
    {{-- Color accent bar --}}
    <div class="h-1.5" style="background-color: {{ $space->color }}"></div>

    <div class="p-5">
        <div class="flex items-start justify-between mb-4">
            {{-- Color avatar --}}
            <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white text-sm font-bold flex-shrink-0 shadow-sm"
                 style="background-color: {{ $space->color }}">
                {{ strtoupper(substr($space->name, 0, 2)) }}
            </div>

            {{-- Owner actions --}}
            @if($owned)
            <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                <a href="{{ route('spaces.edit', $space) }}"
                   class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
                <form method="POST" action="{{ route('spaces.destroy', $space) }}" onsubmit="return confirm('Supprimer ce space ?')">
                    @csrf @method('DELETE')
                    <button type="submit"
                            class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </div>
            @endif
        </div>

        <a href="{{ route('spaces.show', $space) }}" class="block">
            <h3 class="font-bold text-slate-900 mb-1 line-clamp-1 text-sm">{{ $space->name }}</h3>
            <p class="text-xs text-slate-400 line-clamp-2 mb-4 min-h-[2rem]">{{ $space->description ?: 'Aucune description' }}</p>

            <div class="flex items-center gap-3 text-xs text-slate-500">
                <span class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ $space->files_count }} fichier(s)
                </span>
                <span class="flex items-center gap-1.5 bg-slate-50 px-2.5 py-1 rounded-lg">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    {{ $space->members_count }} membre(s)
                </span>
                @if($space->is_public)
                <span class="flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-600 font-semibold">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Public
                </span>
                @endif
            </div>
        </a>
    </div>
</div>
