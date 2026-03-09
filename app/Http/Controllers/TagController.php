<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:50',
            'color' => 'nullable|string|max:7',
        ]);

        $tag = Tag::firstOrCreate(
            ['name' => $data['name'], 'user_id' => Auth::id()],
            ['color' => $data['color'] ?? '#6366f1']
        );

        return response()->json(['id' => $tag->id, 'name' => $tag->name, 'color' => $tag->color]);
    }

    public function destroy(Tag $tag)
    {
        abort_unless($tag->user_id === Auth::id(), 403);
        $tag->delete();
        return back()->with('success', 'Tag supprimé.');
    }

    public function index()
    {
        $tags = Auth::user()->tags()->withCount('files')->get();
        return response()->json($tags);
    }
}
