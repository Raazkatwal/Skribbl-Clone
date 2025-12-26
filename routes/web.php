<?php

use App\Livewire\Game\GameRoom;
use App\Livewire\JoinGame;
use Illuminate\Support\Facades\Route;

Route::get('/', JoinGame::class)
    ->name('join-game');

Route::get('/whiteboard/{room:code}', GameRoom::class)->name('whiteboard');
