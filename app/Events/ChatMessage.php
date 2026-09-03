<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $roomCode,
        public string $playerName,
        public string $message,
        public bool $isCorrect,
        public bool $isSystem,
    ) {}

    public function broadcastOn(): Channel
    {
        return new Channel('room.'.$this->roomCode);
    }

    public function broadcastAs(): string
    {
        return 'chat.message';
    }
}
