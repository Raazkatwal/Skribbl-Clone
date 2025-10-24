<?php

namespace App\Livewire;

use App\Models\Room;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;
use Livewire\Component;

class JoinGame extends Component
{
    #[Validate('required|string|min:3|max:20')]
    public string $username = '';

    #[Validate('required|string|min:3|max:20')]
    public string $room = '';

    public function mount(){
        if (session('room') && session('username')) {
            redirect()->route('whiteboard');
        }
    }

    public function join(){
        $this->validate();

        $room = Room::firstOrCreate(['name' => $this->room], [
            'code' => strtoupper(Str::random(5))
        ]);

        session([
            'username' => $this->username,
            'room_code' => $this->room,
            'user_id' => Str::uuid()->toString(),
        ]);

        return redirect()->route('whiteboard', ['room' => $room->code]);
    }

    public function render()
    {
        return view('livewire.join-game');
    }
}
