<?php

namespace App\Http\View\Composers;

use App\Models\Invitation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class SidebarComposer
{
    public function compose(View $view): void
    {
        if (!Auth::check()) {
            $view->with('pendingInvitationsCount', 0);
            $view->with('sidebarSpaces', collect());
            return;
        }

        $uid   = Auth::id();
        $email = Auth::user()->email;

        $pendingInvitationsCount = Cache::remember("user:{$uid}:sidebar:invitations", 300, function () use ($email) {
            return Invitation::where('email', $email)
                ->whereNull('accepted_at')
                ->where('expires_at', '>', now())
                ->count();
        });

        $sidebarSpaces = Cache::remember("user:{$uid}:sidebar:recent_spaces", 300, function () {
            return Auth::user()->ownedSpaces()->latest()->take(5)->get(['id', 'name', 'color']);
        });

        $view->with(compact('pendingInvitationsCount', 'sidebarSpaces'));
    }
}
