<?php

use App\Http\Controllers\TestController;
use App\Livewire\JoinGame;
use App\Livewire\Whiteboard;
use Illuminate\Support\Facades\Route;

Route::get('/', JoinGame::class)
    ->name('join-game');

Route::get('/whiteboard', Whiteboard::class)->name('whiteboard');
