<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat', function () {
    return true;
});

Broadcast::channel('room.{roomCode}', function ($user, $roomCode) {
    return [
        'id' => $user->id ?? session('user_id'),
        'name' => $user->name ?? session('username', 'Guest'),
    ];
});
