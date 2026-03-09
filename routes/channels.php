<?php

use Illuminate\Support\Facades\Broadcast;

// Canal privé d'un space : accessible aux membres et au propriétaire
Broadcast::channel('space.{spaceId}', function ($user, $spaceId) {
    $space = \App\Models\Space::find($spaceId);
    return $space && $space->isMember($user);
});

// Canal privé d'un utilisateur
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
