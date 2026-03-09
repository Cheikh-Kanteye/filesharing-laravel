@extends('layouts.app')
@section('title', 'Mes Invitations')

@section('content')
<div class="max-w-2xl space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Mes Invitations</h1>
        <p class="text-slate-500 text-sm mt-1">Invitations en attente pour rejoindre des spaces</p>
    </div>

    @if($invitations->isEmpty())
    <div class="bg-white rounded-xl border border-slate-200 border-dashed p-14 text-center">
        <div class="w-14 h-14 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
        </div>
        <p class="text-slate-600 font-medium">Aucune invitation en attente</p>
        <p class="text-slate-400 text-sm mt-1">Vous serez notifié ici quand quelqu'un vous invite dans un space</p>
    </div>
    @else
    <div class="space-y-3">
        @foreach($invitations as $invitation)
        <div class="bg-white rounded-xl border border-slate-200 p-5 hover:border-slate-300 transition-colors">
            <div class="flex items-start gap-4">
                {{-- Space color badge --}}
                <div class="w-12 h-12 rounded-xl flex items-center justify-center text-white font-bold text-sm flex-shrink-0"
                     style="background-color: {{ $invitation->space->color }}">
                    {{ strtoupper(substr($invitation->space->name, 0, 2)) }}
                </div>

                <div class="flex-1 min-w-0">
                    <h3 class="font-semibold text-slate-800">{{ $invitation->space->name }}</h3>
                    @if($invitation->space->description)
                    <p class="text-sm text-slate-500 mt-0.5 line-clamp-1">{{ $invitation->space->description }}</p>
                    @endif
                    <div class="flex items-center gap-3 mt-2 text-xs text-slate-400">
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Invité par <span class="font-medium text-slate-600 ml-1">{{ $invitation->inviter->name }}</span>
                        </span>
                        <span>·</span>
                        <span class="flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Expire {{ $invitation->expires_at->diffForHumans() }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    <form method="POST" action="{{ route('invitations.decline', $invitation->token) }}">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors">
                            Refuser
                        </button>
                    </form>
                    <a href="{{ route('invitations.accept', $invitation->token) }}"
                       class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors">
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
