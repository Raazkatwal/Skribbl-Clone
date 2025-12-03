<?php

namespace App\Livewire;

use App\Enums\RoomStatus;
use App\Events\DrawEvent;
use App\Events\GameStarted;
use App\Events\PlayerLeft;
use App\Models\Player;
use App\Models\Room;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Whiteboard extends Component
{
    public Room $room;

    public $players;

    public int $drawtime = 80;

    public int $max_players = 5;

    public int $rounds = 3;

    public function mount(): void
    {
        $this->players = Player::with('user')->whereHas('room', function ($query) {
            $query->where('code', $this->room->code);
        })->get();

        if (! Auth::check() || ! $this->room) {
            redirect()->route('join-game');
        }
    }

    /**
     * @param  mixed  $type
     * @param  mixed  $x
     * @param  mixed  $y
     * @param  mixed  $color
     * @param  mixed  $mode
     * @param  mixed  $userId
     */
    #[On('whiteboard-draw')]
    public function handleDraw($type, $x, $y, $color, $mode, $userId): void
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
    public function refreshPlayers(): void
    {
        $this->players = Player::with('user')->whereHas('room', function ($query) {
            $query->where('code', $this->room->code);
        })->get();
    }

    public function removePlayer(): void
    {
        $user = Auth::user();

        Player::where('room_id', $this->room->id)
            ->where('user_id', $user->id)
            ->delete();

        if (Player::where('room_id', $this->room->id)->count() === 0) {
            Room::find($this->room->id)->delete();
        }

        if ($user->is_guest == true) {
            Auth::logout();
            $user->delete();
        }

        event(new PlayerLeft($this->room->code));

        $this->redirectRoute('join-game');
    }

    public function startGame(): void
    {
        if (! session('is_host') || $this->room->status !== RoomStatus::WAITING) {
            return;
        }

        if ($this->players->count() < 2) {
            // $this->dispatch('toast', message: "Need at least 2 players");
            $this->js('alert("Need at least 2 players")');

            return;
        }

        $drawer = $this->players->random();

        Player::where('room_id', $this->room->id)->update(['is_drawer' => false]);
        $drawer->is_drawer = true;
        $drawer->save();

        $word = $this->pickRandomWord();

        $endsAt = now()->addSeconds($this->drawtime);

        $this->room->update([
            'status' => RoomStatus::PLAYING,
            'max_players' => $this->max_players,
            'rounds' => $this->rounds,
            'round_time' => $this->drawtime,
            'current_drawer_id' => $drawer->id,
            'current_word' => $word,
            'round_ends_at' => $endsAt,
        ]);

        event(new GameStarted(
            roomCode: $this->room->code,
        ));
    }

    #[On('game-started')]
    public function handleGameStarted(): void
    {
        // $this->dispatch('$refresh');
        $this->room->refresh();
        $this->dispatch('countdown-start', seconds: $this->room->round_time);
    }

    public function render(): View
    {
        return view('livewire.whiteboard');
    }

    private function pickRandomWord(): string
    {
        $words = config('words');

        return $words[array_rand($words)];
    }
}
