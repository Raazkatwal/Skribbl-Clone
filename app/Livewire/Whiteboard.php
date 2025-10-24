<?php

namespace App\Livewire;

use App\Events\DrawEvent;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\Component;

class Whiteboard extends Component
{
    public string $username;

    public string $room;

    public string $userId;

    public function mount()
    {
        $this->room = request()->query('room');
        $this->username = session('username', 'Guest');
        $this->userId = session('user_id', Str::uuid()->toString());
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
            'room' => $this->room
        ];
        event(new DrawEvent($data));
    }

    public function render()
    {
        return view('livewire.whiteboard');
    }
}
