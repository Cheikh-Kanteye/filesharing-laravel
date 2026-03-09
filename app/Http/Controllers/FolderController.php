<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Space;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function store(Request $request, Space $space)
    {
        $this->authorize('view', $space);

        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id',
        ]);

        Folder::create([
            'name'      => $data['name'],
            'space_id'  => $space->id,
            'user_id'   => Auth::id(),
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        return back()->with('success', 'Dossier créé.');
    }

    public function destroy(Folder $folder)
    {
        abort_unless($folder->user_id === Auth::id() || $folder->space->owner_id === Auth::id(), 403);
        $folder->delete();
        return back()->with('success', 'Dossier supprimé.');
    }
}
