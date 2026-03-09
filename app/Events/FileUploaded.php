<?php

namespace App\Events;

use App\Models\File;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileUploaded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public File $file) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('space.' . $this->file->space_id)];
    }

    public function broadcastAs(): string
    {
        return 'file.uploaded';
    }

    public function broadcastWith(): array
    {
        $this->file->load(['user', 'tags']);

        return [
            'id'            => $this->file->id,
            'title'         => $this->file->title,
            'original_name' => $this->file->original_name,
            'mime_type'     => $this->file->mime_type,
            'icon'          => $this->file->icon,
            'size'          => $this->file->formatted_size,
            'created_at'    => $this->file->created_at->diffForHumans(),
            'download_url'  => route('files.download', $this->file),
            'delete_url'    => route('files.destroy', $this->file),
            'user' => [
                'id'       => $this->file->user->id,
                'name'     => $this->file->user->name,
                'initials' => $this->file->user->initials,
            ],
            'tags' => $this->file->tags->map(fn($t) => [
                'name'  => $t->name,
                'color' => $t->color,
            ]),
        ];
    }
}
