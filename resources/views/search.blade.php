@extends('layouts.app')
@section('title', 'Recherche')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Recherche</h1>
        @if($query)
        <p class="text-slate-500 text-sm mt-1">{{ $files->total() }} résultat(s) pour « {{ $query }} »</p>
        @endif
    </div>

    <form action="{{ route('search') }}" method="GET" class="relative max-w-xl">
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <input type="search" name="q" value="{{ $query }}" autofocus
               placeholder="Rechercher par nom, description, tag..."
               class="w-full pl-12 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
    </form>

    @if($query)
        @if($files->isEmpty())
        <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
            <p class="text-slate-500">Aucun fichier trouvé pour « {{ $query }} »</p>
        </div>
        @else
        <div class="space-y-3">
            @foreach($files as $file)
            <div class="bg-white rounded-xl border border-slate-200 p-4 hover:border-slate-300 hover:shadow-sm transition-all">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-lg bg-slate-50 flex items-center justify-center flex-shrink-0 border border-slate-100">
                        @include('components.file-icon', ['type' => $file->icon])
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <h3 class="font-medium text-slate-800 text-sm">{{ $file->title }}</h3>
                            <span class="text-xs text-slate-400">in</span>
                            <a href="{{ route('spaces.show', $file->space) }}" class="text-xs text-blue-600 hover:underline">{{ $file->space->name }}</a>
                        </div>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $file->original_name }} · {{ $file->formatted_size }} · {{ $file->user->name }}</p>
                        @if($file->tags->isNotEmpty())
                        <div class="flex items-center gap-1.5 mt-2">
                            @foreach($file->tags as $tag)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white" style="background-color: {{ $tag->color }}">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <a href="{{ route('files.download', $file) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        {{ $files->withQueryString()->links() }}
        @endif
    @else
    <div class="bg-white rounded-xl border border-slate-200 p-12 text-center">
        <p class="text-slate-400 text-sm">Tapez quelque chose pour rechercher dans vos fichiers</p>
    </div>
    @endif
</div>
@endsection
