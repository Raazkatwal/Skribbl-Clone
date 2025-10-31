<?php

use App\Models\Player;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat', function () {
    return true;
});

Broadcast::channel('room.{roomCode}', function () {
    return true;
});
