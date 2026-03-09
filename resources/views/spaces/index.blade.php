@extends('layouts.app')
@section('title', 'Mes Spaces')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Mes Spaces</h1>
            <p class="text-slate-500 text-sm mt-1">Espaces collaboratifs de partage de documents</p>
        </div>
        <a href="{{ route('spaces.create') }}" class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Créer un Space
        </a>
    </div>

    {{-- Owned spaces --}}
    @if($ownedSpaces->isNotEmpty())
    <section>
        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-3">Spaces que je gère</h2>
        <div class="grid grid-cols-3 gap-4">
            @foreach($ownedSpaces as $space)
                @include('spaces._card', ['space' => $space, 'owned' => true])
            @endforeach
        </div>
    </section>
    @endif

    {{-- Joined spaces --}}
    @if($joinedSpaces->isNotEmpty())
    <section>
        <h2 class="text-sm font-semibold text-slate-500 uppercase tracking-wider mb-3">Spaces rejoints</h2>
        <div class="grid grid-cols-3 gap-4">
            @foreach($joinedSpaces as $space)
                @include('spaces._card', ['space' => $space, 'owned' => false])
            @endforeach
        </div>
    </section>
    @endif

    @if($ownedSpaces->isEmpty() && $joinedSpaces->isEmpty())
    <div class="bg-white rounded-2xl border border-slate-200 border-dashed p-16 text-center">
        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
        </div>
        <h3 class="text-slate-700 font-medium mb-1">Aucun space pour l'instant</h3>
        <p class="text-slate-400 text-sm mb-6">Créez votre premier espace collaboratif ou attendez une invitation</p>
        <a href="{{ route('spaces.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm rounded-lg transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Créer mon premier Space
        </a>
    </div>
    @endif
</div>
@endsection
