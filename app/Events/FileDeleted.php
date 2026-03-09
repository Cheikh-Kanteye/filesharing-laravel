<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileDeleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public int $fileId,
        public int $spaceId
    ) {}

    public function broadcastOn(): array
    {
        return [new PrivateChannel('space.' . $this->spaceId)];
    }

    public function broadcastAs(): string
    {
        return 'file.deleted';
    }

    public function broadcastWith(): array
    {
        return ['id' => $this->fileId];
    }
}
