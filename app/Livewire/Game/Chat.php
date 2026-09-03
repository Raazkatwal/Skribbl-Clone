<?php

namespace App\Livewire\Game;

use App\Events\ChatMessage;
use App\Models\Player;
use App\Models\Room;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class Chat extends Component
{
    public Room $room;

    public bool $isDrawer = false;

    public string $message = '';

    public array $messages = [];

    public function mount(Room $room, bool $isDrawer): void
    {
        $this->room = $room;
        $this->isDrawer = $isDrawer;
    }

    public function submitGuess(): void
    {
        $guess = trim($this->message);

        if ($guess === '' || $this->isDrawer) {
            return;
        }

        $this->room->refresh();

        if ($this->room->current_word === null) {
            return;
        }

        $player = Player::where('room_id', $this->room->id)
            ->where('user_id', Auth::id())
            ->first();

        if (! $player || $player->has_guessed) {
            $this->message = '';

            return;
        }

        if (strtolower($guess) === strtolower($this->room->current_word)) {
            $player->update([
                'score' => $player->score + 100,
                'has_guessed' => true,
            ]);

            broadcast(new ChatMessage(
                roomCode: $this->room->code,
                playerName: $player->user->name,
                message: 'guessed the word!',
                isCorrect: true,
                isSystem: true,
            ));
        } else {
            broadcast(new ChatMessage(
                roomCode: $this->room->code,
                playerName: $player->user->name,
                message: $guess,
                isCorrect: false,
                isSystem: false,
            ));
        }

        $this->message = '';
    }

    #[On('countdown-start')]
    public function onCountdownStart(): void
    {
        $this->room->refresh();
    }

    #[On('drawer-changed')]
    public function updateDrawer(): void
    {
        $player = Player::where('room_id', $this->room->id)
            ->where('user_id', Auth::id())
            ->first();

        $this->isDrawer = $player?->is_drawer ?? false;
    }

    #[On('chat-message')]
    public function addMessage(string $playerName, string $message, bool $isCorrect, bool $isSystem): void
    {
        $this->messages[] = [
            'player' => $playerName,
            'message' => $message,
            'is_correct' => $isCorrect,
            'is_system' => $isSystem,
        ];
    }

    public function getWordHintProperty(): ?string
    {
        $word = $this->room->current_word;

        if ($word === null || $this->isDrawer) {
            return null;
        }

        return implode(' ', array_map(fn ($c) => $c === ' ' ? ' ' : '_', str_split($word)));
    }

    public function render()
    {
        return view('livewire.game.chat');
    }
}
