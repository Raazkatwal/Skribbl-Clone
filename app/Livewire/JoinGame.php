<?php

namespace App\Livewire;

use App\Events\PlayerJoined;
use App\Models\Player;
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

    public function mount()
    {
        if (session('room') && session('username')) {
            redirect()->route('whiteboard');
        }
    }

    public function join()
    {
        $this->validate();

        $room = Room::firstOrCreate(['code' => $this->room]);

        session([
            'username' => $this->username,
            'room_code' => $this->room,
            'user_id' => Str::uuid()->toString(),
        ]);

        Player::firstOrCreate(
            [
                'room_id' => $room->id,
                'user_id' => session('user_id'),
            ],
            [
                'name' => $this->username
            ]
        );

        event(new PlayerJoined(room: $this->room));

        return redirect()->route('whiteboard', ['room' => $room->code]);
    }

    public function render()
    {
        return view('livewire.join-game');
    }
}
