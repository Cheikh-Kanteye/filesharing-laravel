<?php

namespace App\Http\Controllers;

use App\Events\FileDeleted as FileDeletedEvent;
use App\Events\FileUploaded as FileUploadedEvent;
use App\Models\File;
use App\Models\Space;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    use AuthorizesRequests;
    private const ALLOWED_TYPES = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/zip',
        'application/x-zip-compressed',
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'text/plain',
    ];

    public function upload(Request $request, Space $space)
    {
        $this->authorize('view', $space);

        $request->validate([
            'file'        => 'required|file|max:51200|mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip,jpg,jpeg,png,gif,webp,txt',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'folder_id'   => 'nullable|exists:folders,id',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
        ]);

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->store("spaces/{$space->id}", 'private');

        $file = File::create([
            'user_id'       => Auth::id(),
            'space_id'      => $space->id,
            'folder_id'     => $request->folder_id,
            'title'         => $request->title,
            'description'   => $request->description,
            'original_name' => $uploadedFile->getClientOriginalName(),
            'file_path'     => $path,
            'mime_type'     => $uploadedFile->getMimeType(),
            'size'          => $uploadedFile->getSize(),
        ]);

        if ($request->tags) {
            $file->tags()->sync($request->tags);
        }

        try { broadcast(new FileUploadedEvent($file))->toOthers(); } catch (\Throwable) {}

        return redirect()->route('spaces.show', $space)->with('success', 'Fichier uploadé avec succès !');
    }

    public function download(File $file)
    {
        $space = $file->space;
        $user  = Auth::user();

        // Space public → tout le monde peut télécharger
        if (!$space->is_public) {
            // Space privé : doit être connecté et membre
            if (!$user) {
                return redirect()->route('login')
                    ->with('error', 'Connectez-vous pour télécharger ce fichier.');
            }
            if (!$space->isMember($user)) {
                abort(403, 'Accès refusé.');
            }
        }

        $file->increment('downloads_count');

        return Storage::disk('private')->download($file->file_path, $file->original_name);
    }

    public function destroy(File $file)
    {
        $this->authorize('delete', $file);

        Storage::disk('private')->delete($file->file_path);
        $space = $file->space;
        $fileId = $file->id;
        $spaceId = $file->space_id;
        $file->delete();

        try { broadcast(new FileDeletedEvent($fileId, $spaceId))->toOthers(); } catch (\Throwable) {}

        return redirect()->route('spaces.show', $space)->with('success', 'Fichier supprimé.');
    }

    public function copy(Request $request, File $file)
    {
        $this->authorize('view', $file->space);

        $data = $request->validate([
            'target_space_id' => 'required|exists:spaces,id',
        ]);

        $targetSpace = Space::findOrFail($data['target_space_id']);
        $this->authorize('view', $targetSpace);

        $newPath = "spaces/{$targetSpace->id}/" . basename($file->file_path);
        Storage::disk('private')->copy($file->file_path, $newPath);

        $newFile = File::create([
            'user_id'       => Auth::id(),
            'space_id'      => $targetSpace->id,
            'title'         => $file->title . ' (copie)',
            'description'   => $file->description,
            'original_name' => $file->original_name,
            'file_path'     => $newPath,
            'mime_type'     => $file->mime_type,
            'size'          => $file->size,
        ]);

        return back()->with('success', "Fichier copié vers « {$targetSpace->name} ».");
    }

    public function share(File $file)
    {
        $this->authorize('update', $file);
        $file->generateShareToken();
        return back()->with('success', 'Lien de partage généré (valide 7 jours).');
    }

    public function publicShare(string $token)
    {
        $file = File::where('share_token', $token)->firstOrFail();

        if (!$file->isShareValid()) {
            abort(410, 'Ce lien de partage a expiré.');
        }

        $file->increment('downloads_count');
        return Storage::disk('private')->download($file->file_path, $file->original_name);
    }

    public function updateTags(Request $request, File $file)
    {
        $this->authorize('update', $file);

        $data = $request->validate([
            'tags'   => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        $file->tags()->sync($data['tags'] ?? []);

        return back()->with('success', 'Tags mis à jour.');
    }
}
