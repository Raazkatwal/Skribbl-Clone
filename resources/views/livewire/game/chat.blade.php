<div class="flex h-full flex-col rounded bg-white">
    {{-- Messages --}}
    <div class="flex-1 space-y-1 overflow-y-auto p-2">
        @foreach ($messages as $msg)
            @if ($msg['is_system'])
                <div class="text-center text-xs font-semibold text-green-600">
                    {{ $msg['player'] }} {{ $msg['message'] }}
                </div>
            @else
                <div class="text-sm">
                    <span class="font-bold text-gray-800">{{ $msg['player'] }}:</span>
                    <span class="text-gray-600">{{ $msg['message'] }}</span>
                </div>
            @endif
        @endforeach
    </div>

    {{-- Input --}}
    <div class="border-t p-2">
        @if ($isDrawer)
            <input type="text" disabled
                class="w-full rounded border bg-gray-100 px-3 py-2 text-sm text-gray-400"
                placeholder="You are drawing..." />
        @else
            <form wire:submit="submitGuess">
                <input type="text" wire:model="message"
                    class="w-full rounded border px-3 py-2 text-sm"
                    placeholder="Type your guess..." autocomplete="off" />
            </form>
        @endif
    </div>
</div>
