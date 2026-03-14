<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $uid  = $user->id;

        // Cache la liste des spaces (invalidée sur create/update/delete space)
        [$ownedSpaces, $joinedSpaces] = Cache::remember("user:{$uid}:spaces", 300, function () use ($user) {
            return [
                $user->ownedSpaces()->withCount(['files', 'members'])->latest()->get(),
                $user->joinedSpaces()->withCount(['files', 'members'])->latest()->get(),
            ];
        });
        $allSpaces = $ownedSpaces->concat($joinedSpaces);

        // Fichiers récents : pas mis en cache (dépend de l'activité temps réel)
        $recentFiles = File::whereHas('space', function ($q) use ($user) {
            $q->where('owner_id', $user->id)
              ->orWhereHas('members', fn($m) => $m->where('user_id', $user->id));
        })
        ->with(['space', 'user'])
        ->latest()
        ->take(8)
        ->get();

        // Cache les stats agrégées (invalidées sur upload/delete/tag)
        $stats = Cache::remember("user:{$uid}:stats", 300, function () use ($user, $allSpaces) {
            return [
                'spaces'    => $allSpaces->count(),
                'files'     => $user->files()->count(),
                'tags'      => $user->tags()->count(),
                'downloads' => $user->files()->sum('downloads_count'),
            ];
        });

        return view('dashboard', compact('allSpaces', 'recentFiles', 'stats'));
    }

    // ── Page de recherche classique (résultats complets paginés) ──────────────

    public function search(Request $request)
    {
        $query = $request->get('q', '');
        $user  = Auth::user();

        $files = $this->buildSearchQuery($query, $user)->with(['space', 'tags'])->paginate(15);

        return view('search', compact('files', 'query'));
    }

    // ── API JSON : retourne uniquement les métadonnées (pas le contenu fichier) ─

    public function apiSearch(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json([]);
        }

        $user     = Auth::user();
        $cacheKey = 'search:' . $user->id . ':' . md5($query);

        $results = Cache::remember($cacheKey, 180, function () use ($query, $user) {
            return $this->buildSearchQuery($query, $user)
                ->select([
                    'files.id',
                    'files.title',
                    'files.original_name',
                    'files.mime_type',
                    'files.size',
                    'files.space_id',
                    'files.created_at',
                ])
                ->with(['space:id,name,color', 'tags:id,name,color'])
                ->limit(10)
                ->get()
                ->map(fn($file) => [
                    'id'            => $file->id,
                    'title'         => $file->title,
                    'original_name' => $file->original_name,
                    'icon'          => $file->icon,
                    'size'          => $file->formatted_size,
                    'download_url'  => route('files.download', $file),
                    'space'         => [
                        'id'    => $file->space->id,
                        'name'  => $file->space->name,
                        'color' => $file->space->color,
                        'url'   => route('spaces.show', $file->space),
                    ],
                    'tags' => $file->tags->map(fn($t) => [
                        'name'  => $t->name,
                        'color' => $t->color,
                    ]),
                ]);
        });

        return response()->json($results);
    }

    // ── Requête commune avec full-text PostgreSQL ─────────────────────────────

    private function buildSearchQuery(string $query, $user)
    {
        $base = File::whereHas('space', function ($q) use ($user) {
            $q->where('owner_id', $user->id)
              ->orWhereHas('members', fn($m) => $m->where('user_id', $user->id));
        });

        if (empty($query)) {
            return $base->latest();
        }

        // Recherche full-text PostgreSQL via l'index GIN (rapide)
        // + fallback ilike sur les tags
        return $base->where(function ($q) use ($query) {
            $safe = DB::connection()->getPdo()->quote($query);
            $q->whereRaw("search_vector @@ plainto_tsquery('french', {$safe})")
              ->orWhereHas('tags', fn($t) => $t->where('name', 'ilike', "%{$query}%"));
        })
        ->orderByRaw("ts_rank(search_vector, plainto_tsquery('french', ?)) DESC", [$query]);
    }
}
