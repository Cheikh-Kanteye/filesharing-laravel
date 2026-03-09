@extends('layouts.app')
@section('title', 'Mon Profil')

@section('content')
<div class="max-w-2xl">
    <h1 class="text-2xl font-bold text-slate-800 mb-6">Mon Profil</h1>

    <div class="bg-white rounded-xl border border-slate-200 p-6">
        <div class="flex items-center gap-5 mb-8 pb-8 border-b border-slate-100">
            <div class="w-16 h-16 rounded-full bg-blue-600 flex items-center justify-center text-white text-2xl font-semibold">
                {{ $user->initials }}
            </div>
            <div>
                <h2 class="text-lg font-semibold text-slate-800">{{ $user->name }}</h2>
                <p class="text-slate-500 text-sm">{{ $user->email }}</p>
                <p class="text-slate-400 text-xs mt-1">Membre depuis {{ $user->created_at->format('F Y') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Nom complet</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                       class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                       class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Bio</label>
                <textarea name="bio" rows="3"
                          class="w-full px-3.5 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                          placeholder="Parlez un peu de vous...">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                Sauvegarder les modifications
            </button>
        </form>
    </div>
</div>
@endsection
