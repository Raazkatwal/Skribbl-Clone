<?php

namespace App\Livewire\Game;

use Livewire\Component;
use App\Models\Room;
use App\Enums\RoomStatus;

class StartOverlay extends Component
{
    public Room $room;

    public bool $isHost = false;

    public int $maxPlayers;
    public int $rounds;
    public int $drawtime;

    public function mount(
        Room $room,
        bool $isHost,
        int $maxPlayers,
        int $rounds,
        int $drawtime
    ) {
        $this->room = $room;
        $this->isHost = $isHost;

        $this->maxPlayers = $maxPlayers;
        $this->rounds = $rounds;
        $this->drawtime = $drawtime;
    }

    public function startGame()
    {
        if (! $this->isHost) {
            return;
        }

        $this->dispatch('start-game', [
            'max_players' => $this->maxPlayers,
            'rounds' => $this->rounds,
            'drawtime' => $this->drawtime,
        ]);
    }

    public function render()
    {
        if ($this->room->status !== RoomStatus::WAITING) {
            return '';
        }

        return view('livewire.game.start-overlay');
    }
}
