<?php

use App\Livewire\JoinGame;
use App\Livewire\Whiteboard;
use Illuminate\Support\Facades\Route;

Route::get('/', JoinGame::class)
    ->name('join-game');

Route::get('/whiteboard/{room:code}', Whiteboard::class)->name('whiteboard');
