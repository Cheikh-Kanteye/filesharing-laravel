@extends('layouts.app')
@section('title', 'Mes Invitations')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Mes Invitations</h1>
            <p class="text-slate-500 text-sm mt-1">Invitations en attente pour rejoindre des spaces</p>
        </div>
        @if($invitations->isNotEmpty())
        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-semibold text-white"
              style="background-color: var(--accent)">
            {{ $invitations->count() }} en attente
        </span>
        @endif
    </div>

    @if($invitations->isEmpty())
    {{-- Empty state --}}
    <div class="bg-white rounded-2xl shadow-sm p-16 text-center">
        <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5"
             style="background-color: color-mix(in srgb, var(--accent) 12%, white)">
            <svg class="w-8 h-8" style="color: var(--accent)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="font-semibold text-slate-900">Aucune invitation en attente</p>
        <p class="text-slate-500 text-sm mt-1 max-w-xs mx-auto">Vous serez notifié ici quand quelqu'un vous invite dans un space</p>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 text-sm font-semibold text-white rounded-xl transition-all hover:opacity-90"
           style="background-color: var(--accent)">
            Retour au Dashboard
        </a>
    </div>

    @else
    <div class="space-y-4">
        @foreach($invitations as $invitation)
        <div class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition-shadow">
            <div class="flex items-center gap-4">

                {{-- Space avatar --}}
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0 shadow-sm"
                     style="background-color: {{ $invitation->space->color }}">
                    {{ strtoupper(substr($invitation->space->name, 0, 2)) }}
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h3 class="font-bold text-slate-900 text-base">{{ $invitation->space->name }}</h3>
                        @if($invitation->space->is_public)
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">Public</span>
                        @else
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">Privé</span>
                        @endif
                    </div>
                    @if($invitation->space->description)
                    <p class="text-sm text-slate-500 mt-0.5 truncate">{{ $invitation->space->description }}</p>
                    @endif
                    <div class="flex items-center gap-4 mt-2">
                        <span class="flex items-center gap-1.5 text-xs text-slate-400">
                            <div class="w-5 h-5 rounded-full bg-slate-200 flex items-center justify-center text-slate-600 text-[10px] font-bold">
                                {{ $invitation->inviter->initials }}
                            </div>
                            <span>Invité par <span class="font-medium text-slate-600">{{ $invitation->inviter->name }}</span></span>
                        </span>
                        <span class="flex items-center gap-1 text-xs text-slate-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Expire {{ $invitation->expires_at->diffForHumans() }}
                        </span>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-2 flex-shrink-0">
                    <form method="POST" action="{{ route('invitations.decline', $invitation->token) }}">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 text-sm font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors">
                            Refuser
                        </button>
                    </form>
                    <a href="{{ route('invitations.accept', $invitation->token) }}"
                       class="px-4 py-2 text-sm font-semibold text-white rounded-xl transition-all hover:opacity-90 active:scale-95"
                       style="background-color: var(--accent)">
                        Rejoindre
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
