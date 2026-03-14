<?php

namespace App\Http\Controllers;

use App\Events\InvitationReceived as InvitationReceivedEvent;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Invitation;
use App\Models\Space;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SpaceController extends Controller
{
    use AuthorizesRequests;

    public function explore(Request $request)
    {
        $user  = Auth::user();
        $query = trim($request->get('q', ''));

        $memberSpaceIds = $user->ownedSpaces()->pluck('spaces.id')
            ->merge($user->joinedSpaces()->pluck('spaces.id'));

        $spaces = Space::where('is_public', true)
            ->whereNotIn('id', $memberSpaceIds)
            ->withCount(['files', 'members'])
            ->with('owner:id,name')
            ->when($query, fn($q) => $q->where(function ($q) use ($query) {
                $q->where('name', 'ilike', "%{$query}%")
                  ->orWhere('description', 'ilike', "%{$query}%");
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('spaces.explore', compact('spaces', 'query'));
    }

    public function joinPublic(Space $space)
    {
        abort_unless($space->is_public, 403, 'Ce space n\'est pas public.');

        $user = Auth::user();

        if ($space->isMember($user)) {
            return redirect()->route('spaces.show', $space)
                ->with('success', 'Vous êtes déjà membre de ce space.');
        }

        $space->members()->syncWithoutDetaching([$user->id => ['role' => 'member']]);

        Cache::forget("user:{$user->id}:spaces");
        Cache::forget("user:{$user->id}:sidebar:recent_spaces");
        Cache::forget("space:{$space->id}:member:{$user->id}");

        return redirect()->route('spaces.show', $space)
            ->with('success', 'Vous avez rejoint « ' . $space->name . ' » !');
    }

    public function index()
    {
        $user = Auth::user();
        $uid  = $user->id;

        [$ownedSpaces, $joinedSpaces] = Cache::remember("user:{$uid}:spaces", 300, function () use ($user) {
            return [
                $user->ownedSpaces()->withCount(['files', 'members'])->latest()->get(),
                $user->joinedSpaces()->withCount(['files', 'members'])->latest()->get(),
            ];
        });

        return view('spaces.index', compact('ownedSpaces', 'joinedSpaces'));
    }

    public function create()
    {
        return view('spaces.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color'       => 'nullable|string|max:7',
            'is_public'   => 'boolean',
        ]);

        $space = Space::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'owner_id'    => Auth::id(),
            'color'       => $data['color'] ?? '#3b82f6',
            'is_public'   => $request->boolean('is_public'),
        ]);

        $uid = Auth::id();
        Cache::forget("user:{$uid}:spaces");
        Cache::forget("user:{$uid}:stats");
        Cache::forget("user:{$uid}:sidebar:recent_spaces");

        return redirect()->route('spaces.show', $space)->with('success', 'Space créé avec succès !');
    }

    public function show(Space $space)
    {
        $user = Auth::user();

        // Space privé et non connecté → redirection login
        if (!$space->is_public && !$user) {
            return redirect()->route('login')
                ->with('error', 'Connectez-vous pour accéder à ce space privé.');
        }

        // Space privé et connecté → vérifier membership
        if (!$space->is_public && $user && !$space->isMember($user)) {
            abort(403, 'Vous n\'êtes pas membre de ce space.');
        }

        $space->load(['owner', 'members']);

        $query = $space->files()->with(['user', 'tags'])->latest();

        if (request('type')) {
            $query->where('mime_type', 'like', request('type') . '%');
        }

        if (request('tag')) {
            $query->whereHas('tags', fn($t) => $t->where('name', request('tag')));
        }

        if (request('search')) {
            $s = request('search');
            $query->where(fn($q) => $q->where('title', 'ilike', "%{$s}%")->orWhere('original_name', 'ilike', "%{$s}%"));
        }

        $files = $query->paginate(20);
        $folders = $space->folders()->whereNull('parent_id')->with('files')->get();
        $userTags = $user ? $user->tags()->get() : collect();

        return view('spaces.show', compact('space', 'files', 'folders', 'userTags'));
    }

    public function edit(Space $space)
    {
        $this->authorize('update', $space);
        return view('spaces.edit', compact('space'));
    }

    public function update(Request $request, Space $space)
    {
        $this->authorize('update', $space);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'color'       => 'nullable|string|max:7',
            'is_public'   => 'boolean',
        ]);

        $space->update([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'color'       => $data['color'] ?? $space->color,
            'is_public'   => $request->boolean('is_public'),
        ]);

        Cache::forget('user:' . Auth::id() . ':spaces');

        return redirect()->route('spaces.show', $space)->with('success', 'Space mis à jour.');
    }

    public function destroy(Space $space)
    {
        $this->authorize('delete', $space);
        $space->delete();

        $uid = Auth::id();
        Cache::forget("user:{$uid}:spaces");
        Cache::forget("user:{$uid}:stats");
        Cache::forget("user:{$uid}:sidebar:recent_spaces");

        return redirect()->route('spaces.index')->with('success', 'Space supprimé.');
    }

    // ── Invitation par email ──────────────────────────────────────────────────

    public function invite(Request $request, Space $space)
    {
        $this->authorize('update', $space);

        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $invitee = User::where('email', $data['email'])->first();

        if ($space->isMember($invitee)) {
            return back()->withErrors(['email' => 'Cet utilisateur est déjà membre du space.']);
        }

        $invitation = Invitation::updateOrCreate(
            ['space_id' => $space->id, 'email' => $data['email']],
            [
                'invited_by'  => Auth::id(),
                'token'       => Str::random(32),
                'expires_at'  => now()->addDays(7),
                'accepted_at' => null,
            ]
        );

        try { broadcast(new InvitationReceivedEvent($invitation)); } catch (\Throwable) {}

        // Invalide le badge d'invitation pour l'invité
        Cache::forget('invitations:' . md5($data['email']));
        Cache::forget("user:{$invitee->id}:sidebar:invitations");

        return back()->with('success', "Invitation envoyée à {$data['email']}.");
    }

    public function myInvitations()
    {
        $email = Auth::user()->email;

        $invitations = Cache::remember('invitations:' . md5($email), 300, function () use ($email) {
            return Invitation::where('email', $email)
                ->whereNull('accepted_at')
                ->where('expires_at', '>', now())
                ->with(['space.owner', 'inviter'])
                ->latest()
                ->get();
        });

        return view('invitations.index', compact('invitations'));
    }

    public function acceptInvitation(string $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if (!$invitation->isValid()) {
            return redirect()->route('dashboard')->with('error', 'Cette invitation a expiré ou a déjà été utilisée.');
        }

        $user = Auth::user();
        if ($user->email !== $invitation->email) {
            abort(403, 'Cette invitation ne vous est pas destinée.');
        }

        $invitation->space->members()->syncWithoutDetaching([$user->id => ['role' => 'member']]);
        $invitation->update(['accepted_at' => now()]);

        Cache::forget('invitations:' . md5($user->email));
        Cache::forget("user:{$user->id}:spaces");
        Cache::forget("user:{$user->id}:sidebar:recent_spaces");
        Cache::forget("user:{$user->id}:sidebar:invitations");
        Cache::forget("space:{$invitation->space_id}:member:{$user->id}");

        return redirect()->route('spaces.show', $invitation->space)
            ->with('success', 'Vous avez rejoint « ' . $invitation->space->name . ' » !');
    }

    public function declineInvitation(string $token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('email', Auth::user()->email)
            ->firstOrFail();

        $invitation->delete();

        $uid = Auth::id();
        Cache::forget('invitations:' . md5(Auth::user()->email));
        Cache::forget("user:{$uid}:sidebar:invitations");

        return redirect()->route('invitations.index')->with('success', 'Invitation refusée.');
    }

    // ── Lien de partage de space ──────────────────────────────────────────────

    public function generateJoinLink(Space $space)
    {
        $this->authorize('update', $space);
        $space->generateJoinToken();
        return back()->with('success', 'Lien de partage généré.');
    }

    public function revokeJoinLink(Space $space)
    {
        $this->authorize('update', $space);
        $space->revokeJoinToken();
        return back()->with('success', 'Lien de partage révoqué.');
    }

    public function joinViaLink(string $token)
    {
        $space = Space::where('join_token', $token)->firstOrFail();
        $user  = Auth::user();

        if ($space->isMember($user)) {
            return redirect()->route('spaces.show', $space)
                ->with('success', 'Vous êtes déjà membre de ce space.');
        }

        $space->members()->syncWithoutDetaching([$user->id => ['role' => 'member']]);

        Cache::forget("user:{$user->id}:spaces");
        Cache::forget("space:{$space->id}:member:{$user->id}");

        return redirect()->route('spaces.show', $space)
            ->with('success', 'Vous avez rejoint « ' . $space->name . ' » via le lien de partage !');
    }

    // ── Membres ───────────────────────────────────────────────────────────────

    public function leave(Space $space)
    {
        $uid = Auth::id();
        $space->members()->detach($uid);

        Cache::forget("user:{$uid}:spaces");
        Cache::forget("space:{$space->id}:member:{$uid}");

        return redirect()->route('spaces.index')->with('success', 'Vous avez quitté le space.');
    }

    public function removeMember(Space $space, User $user)
    {
        $this->authorize('update', $space);
        $space->members()->detach($user->id);

        Cache::forget("user:{$user->id}:spaces");
        Cache::forget("space:{$space->id}:member:{$user->id}");

        return back()->with('success', 'Membre retiré du space.');
    }
}
