<?php

namespace App\Livewire;

use App\Events\DrawEvent;
use Livewire\Attributes\On;
use Livewire\Component;

class Whiteboard extends Component
{
    public string $username;

    public string $room;

    public function mount()
    {
        $this->username = session('username');
        $this->room = session('room');

        if (! $this->username || ! $this->room) {
            redirect()->route('join-game');
        }
    }

    #[On('whiteboard-draw')]
    public function handleDraw($x, $y, $color, $mode, $userId)
    {
        // dd('draw event received in backend', $x, $y, $color, $mode);
        $data = (object) [
            'x' => $x,
            'y' => $y,
            'color' => $color,
            'mode' => $mode,
            'userId' => $userId
        ];
        event(new DrawEvent($data));
    }

    public function render()
    {
        return view('livewire.whiteboard');
    }
}
