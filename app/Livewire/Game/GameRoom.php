<?php

namespace App\Livewire\Game;

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

class GameRoom extends Component
{
    public Room $room;

    public $players;

    public int $drawtime = 80;

    public int $max_players = 5;

    public int $rounds = 3;

    public bool $isHost = false;

    public bool $isDrawer = false;

    public function mount(): void
    {
        $this->players = Player::with('user')->whereHas('room', function ($query) {
            $query->where('code', $this->room->code);
        })->get();

        if (! Auth::check() || ! $this->room) {
            redirect()->route('join-game');
        }

        $this->isDrawer = $this->players->firstWhere('user_id', Auth::id())->is_drawer;
        $this->isHost = (bool) session('is_host');
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
        // $player = Player::where('room_id', $this->room->id)
        //     ->where('user_id', Auth::id())
        //     ->first();
        //
        // if (! $player || ! $player->is_drawer) {
        //     return;
        // }
        if (! $this->isDrawer) {
            return;
        }

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

        $drawer = $this->pickRandomDrawer();

        $endsAt = now()->addSeconds($this->drawtime);

        $this->room->update([
            'status' => RoomStatus::PLAYING,
            'max_players' => $this->max_players,
            'rounds' => $this->rounds,
            'round_time' => $this->drawtime,
            'current_drawer_id' => $drawer->id,
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

        if (! $this->isDrawer) {
            return;
        }

        $words = $this->pickRandomWords();

        // $this->dispatch('countdown-start', seconds: $this->room->round_time);
        $this->dispatch('show-word-picker', words: $words);
    }

    /**
     * @return void
     */
    #[On('drawer-changed')]
    public function updateDrawer(): void
    {
        $player = Player::where('room_id', $this->room->id)
            ->where('user_id', Auth::id())
            ->first();

        $this->isDrawer = $player?->is_drawer ?? false;
    }

    public function selectWord(string $word): void
    {
        if (! $this->isDrawer) {
            return;
        }

        $this->room->update([
            'current_word' => $word,
            'round_ends_at' => now()->addSeconds($this->room->round_time),
        ]);

        // start timer for everyone
        $this->dispatch('countdown-start', seconds: $this->room->round_time);

        // event(new WordSelected($this->room->code));
    }

    public function render(): View
    {
        return view('livewire.game.game-room');
    }

    private function pickRandomWords(): array
    {
        $words = collect(config('words'))
            ->random(3)->toArray();

        return $words;
    }

    private function pickRandomDrawer()
    {
        Player::where('room_id', $this->room->id)->update(['is_drawer' => false]);
        $drawer = $this->players->random();
        $drawer->is_drawer = true;
        $drawer->save();

        if (Auth::id() === $drawer->user_id) {
            $this->isDrawer = true;
            $this->room->current_drawer_id = $drawer->user_id;
        }

        return $drawer;
    }
}
