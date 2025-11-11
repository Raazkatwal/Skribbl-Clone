<?php

namespace App\Livewire;

use App\Events\DrawEvent;
use App\Events\PlayerLeft;
use App\Models\Player;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Whiteboard extends Component
{
    public Room $room;

    public $players;

    public function mount()
    {
        $this->players = Player::with('user')->whereHas('room', function ($query) {
            $query->where('code', $this->room->code);
        })->get();

        if (! Auth::check() || ! $this->room) {
            redirect()->route('join-game');
        }
    }

    #[On('whiteboard-draw')]
    public function handleDraw($type, $x, $y, $color, $mode, $userId)
    {
        $data = [
            'x' => $x,
            'y' => $y,
            'color' => $color,
            'mode' => $mode,
            'userId' => $userId,
            'type' => $type,
            'room' => $this->room->code,
        ];
        event(new DrawEvent($data));
    }

    #[On('player-joined')]
    #[On('player-left')]
    public function refreshPlayers()
    {
        $this->players = Player::with('user')->whereHas('room', function ($query) {
            $query->where('code', $this->room->code);
        })->get();
    }

    public function removePlayer()
    {
        $user = Auth::user();

        Player::where('room_id', $this->room->id)
            ->where('user_id', $user->id)
            ->delete();

        Auth::logout();

        if ($user->is_guest == true) {
            $user->delete();
        }

        event(new PlayerLeft($this->room->code));

        $this->redirectRoute('join-game');
    }

    public function render()
    {
        return view('livewire.whiteboard');
    }
}
