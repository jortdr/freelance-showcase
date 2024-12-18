<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Broadcast;

//Broadcast::channel('chat.{id}', function ($user, $id) {
//    return (int)$user->id === (int)$id;
//});

Broadcast::channel('chat.{user1}.{user2}', function ($user, $user1, $user2) {
    return (int) $user->id === (int) $user1 || (int) $user->id === (int) $user2;
});
