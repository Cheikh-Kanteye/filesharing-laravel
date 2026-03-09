<?php

namespace App\Events;

use App\Models\Invitation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvitationReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public Invitation $invitation) {}

    public function broadcastOn(): array
    {
        // Chercher l'user par email pour diffuser sur son canal privé
        $user = \App\Models\User::where('email', $this->invitation->email)->first();

        if (!$user) return [];

        return [new PrivateChannel('user.' . $user->id)];
    }

    public function broadcastAs(): string
    {
        return 'invitation.received';
    }

    public function broadcastWith(): array
    {
        $this->invitation->load(['space', 'inviter']);

        return [
            'id'         => $this->invitation->id,
            'space_name' => $this->invitation->space->name,
            'space_color'=> $this->invitation->space->color,
            'inviter'    => $this->invitation->inviter->name,
        ];
    }
}
