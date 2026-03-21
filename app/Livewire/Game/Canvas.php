<?php

namespace App\Livewire\Game;

use App\Events\DrawEvent;
use App\Models\Player;
use App\Models\Room;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Canvas extends Component
{
    public Room $room;

    public bool $isDrawer;

    public bool $isHost = false;

    public int $maxPlayers;
    public int $rounds;
    public int $drawtime;

    #[On('drawer-changed')]
    public function updateDrawer(): void
    {
        $player = Player::where('room_id', $this->room->id)
            ->where('user_id', auth()->id())
            ->first();

        $this->isDrawer = $player?->is_drawer ?? false;
    }

    #[On('whiteboard-draw')]
    public function handleDraw($type, $x, $y, $color, $mode, $userId): void
    {
        if (! $this->isDrawer) {
            return;
        }

        $payload = [
            'type' => $type,
            'x' => $x,
            'y' => $y,
            'color' => $color,
            'mode' => $mode,
            'userId' => $userId,
            'room' => $this->room->code,
        ];

        broadcast(new DrawEvent($payload))->toOthers();
    }

    public function render(): View
    {
        return view('livewire.game.canvas');
    }
}
