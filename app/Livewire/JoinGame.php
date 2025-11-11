<?php

namespace App\Livewire;

use App\Events\PlayerJoined;
use App\Models\Player;
use App\Models\Room;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Validate;
use Livewire\Component;

class JoinGame extends Component
{
    #[Validate('required|string|min:3|max:20|unique:users,name')]
    public string $username = '';

    #[Validate('required|string|min:3|max:20', as: 'room code')]
    public string $room_code = '';

    public function join(): ?RedirectResponse
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $room = Room::firstOrCreate(['code' => $this->room_code]);

            $user = User::firstOrCreate(['name' => $this->username]);

            Player::create(['room_id' => $room->id, 'user_id' => $user->id]);

            Auth::login($user);

            event(new PlayerJoined(room: $this->room_code));

            DB::commit();

            return $this->redirectRoute('whiteboard', ['room' => $room->code]);
        } catch (\Throwable $th) {
            DB::rollBack();
            dd($th->getMessage());

            $this->addError('room', 'Failed to join room. Please try again.');

            return null;
        }
    }

    public function render(): View
    {
        return view('livewire.join-game');
    }
}
