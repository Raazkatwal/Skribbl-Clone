<?php

namespace App\Livewire\Game;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class Header extends Component
{
    public function removePlayer(): void
    {
        $this->dispatch('remove-player')->to(GameRoom::class);
    }

    public function render(): View
    {
        return view('livewire.game.header');
    }
}
