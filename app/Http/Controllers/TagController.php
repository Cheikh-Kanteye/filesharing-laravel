<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TagController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:50',
            'color' => 'nullable|string|max:7',
        ]);

        $uid = Auth::id();
        $tag = Tag::firstOrCreate(
            ['name' => $data['name'], 'user_id' => $uid],
            ['color' => $data['color'] ?? '#6366f1']
        );

        Cache::forget("user:{$uid}:tags");
        Cache::forget("user:{$uid}:stats");

        return response()->json(['id' => $tag->id, 'name' => $tag->name, 'color' => $tag->color]);
    }

    public function destroy(Tag $tag)
    {
        $uid = Auth::id();
        abort_unless($tag->user_id === $uid, 403);
        $tag->delete();

        Cache::forget("user:{$uid}:tags");
        Cache::forget("user:{$uid}:stats");

        return back()->with('success', 'Tag supprimé.');
    }

    public function index()
    {
        $uid  = Auth::id();
        $user = Auth::user();

        $tags = Cache::remember("user:{$uid}:tags", 900, function () use ($user) {
            return $user->tags()->withCount('files')->get();
        });

        return response()->json($tags);
    }
}
