<?php

namespace App\Livewire;

use Livewire\Component;

class Whiteboard extends Component
{
    public string $username;
    public string $room;

    public function mount()
    {
        $this->username = session('username');
        $this->room = session('room');

        if (!$this->username || !$this->room) {
            redirect()->route('join-game');
        }
    }

    public function render()
    {
        return view('livewire.whiteboard');
    }
}
