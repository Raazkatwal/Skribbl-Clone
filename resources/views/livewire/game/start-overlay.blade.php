<div class="absolute inset-0 z-10">
    @if (! $isHost)
        {{-- Non-host waiting screen --}}
        <div class="grid size-full place-items-center bg-gray-300">
            <p class="text-xl capitalize">
                waiting for the host to start the game...
            </p>
        </div>
    @else
        {{-- Host settings screen --}}
        <form
            wire:submit.prevent="startGame"
            class="size-full space-y-4 rounded bg-gray-700 p-4 text-white"
        >
            <div class="flex justify-between items-center">
                <label>Players</label>
                <select
                    wire:model="maxPlayers"
                    class="w-1/2 border border-amber-50 bg-gray-600"
                >
                    @for ($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="flex justify-between items-center">
                <label>Rounds</label>
                <select
                    wire:model="rounds"
                    class="w-1/2 border border-amber-50 bg-gray-600"
                >
                    @for ($i = 1; $i <= 8; $i++)
                        <option value="{{ $i }}">{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <div class="flex justify-between items-center">
                <label>Draw time</label>
                <div>
                    <input
                        type="number"
                        min="10"
                        max="180"
                        wire:model="drawtime"
                        class="w-20 text-black"
                    />
                    seconds
                </div>
            </div>

            <div class="flex h-12 gap-2">
                <button
                    type="submit"
                    class="flex-[3] rounded bg-emerald-500 font-bold hover:bg-emerald-600"
                >
                    Start Game
                </button>

                <button
                    type="button"
                    class="flex-1 rounded bg-cyan-600 font-bold hover:bg-cyan-700"
                >
                    Invite
                </button>
            </div>
        </form>
    @endif
</div>
