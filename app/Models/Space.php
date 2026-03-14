<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class Space extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'owner_id',
        'color',
        'is_public',
        'join_token',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'space_members')
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }

    public function folders(): HasMany
    {
        return $this->hasMany(Folder::class);
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function isMember(User $user): bool
    {
        if ($this->owner_id === $user->id) {
            return true;
        }

        return Cache::remember("space:{$this->id}:member:{$user->id}", 600, function () use ($user) {
            return $this->members()->where('user_id', $user->id)->exists();
        });
    }

    public function clearMemberCache(?int $userId = null): void
    {
        if ($userId) {
            Cache::forget("space:{$this->id}:member:{$userId}");
        }
        // Invalide aussi la liste des spaces de chaque membre concerné
        Cache::forget("user:{$userId}:spaces");
    }

    public function getMembersCountAttribute(): int
    {
        return $this->members()->count() + 1;
    }

    public function getFilesCountAttribute(): int
    {
        return $this->files()->count();
    }

    public function generateJoinToken(): string
    {
        $token = Str::random(16);
        $this->update(['join_token' => $token]);
        return $token;
    }

    public function revokeJoinToken(): void
    {
        $this->update(['join_token' => null]);
    }

    public function getJoinUrlAttribute(): ?string
    {
        return $this->join_token
            ? url('/spaces/join/' . $this->join_token)
            : null;
    }
}
