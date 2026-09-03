<?php

namespace App\Livewire\Game;

use App\Models\Room;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Header extends Component
{
    public Room $room;

    public function mount(Room $room): void
    {
        $this->room = $room;
    }

    public function removePlayer(): void
    {
        $this->dispatch('remove-player')->to(GameRoom::class);
    }

    public function render(): View
    {
        return view('livewire.game.header');
    }
}
