<?php

namespace App\Livewire\Game;

use App\Models\Player;
use App\Models\Room;
use Livewire\Attributes\On;
use Livewire\Component;

class Players extends Component
{
    public Room $room;

    #[On('player-joined')]
    #[On('player-left')]
    public function refresh(): void {}

    public function render()
    {
        $players = Player::with('user')->where('room_id', $this->room->id)->get();

        return view('livewire.game.players', compact('players'));
    }
}
