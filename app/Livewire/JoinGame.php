<?php

namespace App\Livewire;

use Livewire\Attributes\Validate;
use Livewire\Component;

class JoinGame extends Component
{
    #[Validate('required|string|min:3')]
    public string $username = '';

    #[Validate('required|string|min:3')]
    public string $room = '';

    public function mount(){
        if (session('room') && session('username')) {
            redirect()->route('whiteboard');
        }
    }

    public function join(){
        $this->validate();
        session([
            'username' => $this->username,
            'room' => $this->room
        ]);

        return redirect()->route('whiteboard');
    }

    public function render()
    {
        return view('livewire.join-game');
    }
}
