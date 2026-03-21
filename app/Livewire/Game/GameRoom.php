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
        if (! Auth::check() || ! $this->room) {
            $this->redirectRoute('join-game');
            return;
        }

        $this->players = Player::with('user')->whereHas('room', function ($query) {
            $query->where('code', $this->room->code);
        })->get();

        $player = $this->players->firstWhere('user_id', Auth::id());

        if (!$player) {
            // If player record is missing but user is authed, redirect to join
            $this->redirectRoute('join-game');
            return;
        }

        $this->isDrawer = $player->is_drawer;
        $this->isHost = (bool) session('is_host');
    }

    #[On('player-joined')]
    #[On('player-left')]
    public function refreshPlayers(): void
    {
        $this->players = Player::with('user')->whereHas('room', function ($query) {
            $query->where('code', $this->room->code);
        })->get();
    }

    #[On('remove-player')]
    public function removePlayer(): void
    {
        $user = Auth::user();

        Player::where('room_id', $this->room->id)
            ->where('user_id', $user->id)
            ->delete();

        if (Player::query()->where('room_id', $this->room->id)->count() === 0) {
            Room::query()->find($this->room->id)->delete();
        }

        if ($user->is_guest == true) {
            Auth::logout();
            $user->delete();
        }

        event(new PlayerLeft($this->room->code));

        $this->redirectRoute('join-game');
    }

    // Start the game and broadcast the game-started event
    #[On('request-start-game')]
    public function startGame($max_players, $rounds, $drawtime): void
    {
        if (! $this->isHost) {
            return;
        }

        if ($this->players->count() < 2) {
            $this->js('alert("Need at least 2 players")');

            return;
        }

        $drawer = $this->pickRandomDrawer();

        $endsAt = now()->addSeconds($drawtime);

        $this->room->update([
            'status' => RoomStatus::PLAYING,
            'max_players' => $max_players,
            'rounds' => $rounds,
            'round_time' => $drawtime,
            'current_drawer_id' => $drawer->user_id,
            'round_ends_at' => $endsAt,
        ]);

        broadcast(new GameStarted(roomCode: $this->room->code));
    }

    // After listening the event through ws start the game for all players
    #[On('game-started')]
    public function handleGameStarted(): void
    {
        $this->room->refresh();

        $player = Player::where('room_id', $this->room->id)
            ->where('user_id', auth()->id())
            ->first();

        $this->isDrawer = $player?->is_drawer ?? false;

        if (! $this->isDrawer) {
            return;
        }

        $words = $this->pickRandomWords();

        $this->dispatch('show-word-picker', words: $words);
    }

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
