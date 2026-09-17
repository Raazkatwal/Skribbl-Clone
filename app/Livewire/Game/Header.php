<?php

namespace App\Livewire\Game;

use App\Enums\RoomStatus;
use App\Models\Player;
use App\Models\Room;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Header extends Component
{
    public Room $room;

    public bool $isDrawer = false;

    public function mount(Room $room, bool $isDrawer = false): void
    {
        $this->room = $room;
        $this->isDrawer = $isDrawer;
    }

    public function removePlayer(): void
    {
        $this->dispatch('remove-player')->to(GameRoom::class);
    }

    public function getDisplayWordProperty(): ?string
    {
        $word = $this->room->current_word;

        if ($word === null || $this->room->status !== RoomStatus::PLAYING) {
            return null;
        }

        if ($this->revealed) {
            return $word;
        }

        return implode(' ', array_map(fn ($c) => $c === ' ' ? ' ' : '_', str_split($word)));
    }

    public function getRevealedProperty(): bool
    {
        if ($this->isDrawer) {
            return true;
        }

        return (bool) (Player::where('room_id', $this->room->id)
            ->where('user_id', Auth::id())
            ->value('has_guessed') ?? false);
    }

    #[On('word-picked')]
    #[On('game-started')]
    #[On('word-revealed')]
    public function refreshRoom(): void
    {
        $this->room->refresh();
    }

    public function render(): View
    {
        return view('livewire.game.header');
    }
}
