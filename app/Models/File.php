<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'space_id',
        'folder_id',
        'title',
        'description',
        'original_name',
        'file_path',
        'mime_type',
        'size',
        'share_token',
        'share_expires_at',
        'downloads_count',
    ];

    protected $casts = [
        'share_expires_at' => 'datetime',
        'size' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'file_tag');
    }

    public function getFormattedSizeAttribute(): string
    {
        $bytes = $this->size;
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 1) . ' KB';
        if ($bytes < 1073741824) return round($bytes / 1048576, 1) . ' MB';
        return round($bytes / 1073741824, 1) . ' GB';
    }

    public function getIconAttribute(): string
    {
        return match (true) {
            str_contains($this->mime_type, 'pdf') => 'pdf',
            str_contains($this->mime_type, 'word') || str_contains($this->original_name, '.docx') => 'word',
            str_contains($this->mime_type, 'presentation') || str_contains($this->original_name, '.pptx') => 'ppt',
            str_contains($this->mime_type, 'spreadsheet') || str_contains($this->original_name, '.xlsx') => 'excel',
            str_contains($this->mime_type, 'image') => 'image',
            str_contains($this->mime_type, 'zip') || str_contains($this->mime_type, 'compressed') => 'archive',
            str_contains($this->mime_type, 'text') => 'text',
            default => 'file',
        };
    }

    public function generateShareToken(): void
    {
        $this->update([
            'share_token' => Str::random(32),
            'share_expires_at' => now()->addDays(7),
        ]);
    }

    public function isShareValid(): bool
    {
        return $this->share_token && $this->share_expires_at?->isFuture();
    }
}
