<?php

namespace App\Livewire;

use App\Events\DrawEvent;
use App\Events\PlayerLeft;
use App\Models\Player;
use App\Models\Room;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Whiteboard extends Component
{
    public string $username;

    public string $room;

    public string $userId;

    public $players;

    public function mount()
    {
        $this->room = request()->query('room');
        $this->username = session('username', 'Guest');
        $this->userId = session('user_id', Str::uuid()->toString());
        $this->players = Player::whereHas('room', function ($query) {
            $query->where('code', $this->room);
        })->get();
        // $this->room = session('room');

        if (! $this->username || ! $this->room) {
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
            'room' => $this->room,
        ];
        event(new DrawEvent($data));
    }

    #[On('player-joined')]
    #[On('player-left')]
    public function refreshPlayers()
    {
            $this->players = Player::whereHas('room', function ($query) {
            $query->where('code', $this->room);
        })->get();
    }

    public function removePlayer()
    {
        $roomCode = session('room_code');
        $username = session('username');
        if ($roomCode && $username) {
            $room = Room::where('code', $roomCode)->first();
            if ($room) {
                Player::where('room_id', $room->id)
                    ->where('name', $username)
                    ->delete();

                event(new PlayerLeft($roomCode, $username));
                $this->redirectRoute('join-game');
            }
        }
    }

    public function render()
    {
        return view('livewire.whiteboard');
    }
}
