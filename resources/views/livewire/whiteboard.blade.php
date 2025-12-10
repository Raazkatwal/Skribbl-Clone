<div class="grid h-screen w-screen place-items-center">
    <div class="size-11/12">
        <div class="logo m-auto"></div>
        <div class="grid size-full grid-cols-[20%_60%_20%] grid-rows-[10%_90%] gap-1.5">
            <div
                class="col-span-3 flex items-center justify-between rounded bg-gradient-to-r from-blue-500 to-purple-500 px-2 shadow-md">
                <div class="flex items-center gap-4"
                    x-data="{ remaining: 0, interval: null }"
                    x-init="
                        $wire.on('countdown-start', e => {
                            remaining = e.seconds;

                            if (interval) clearInterval(interval); // stop any existing interval

                            interval = setInterval(() => {
                                if (remaining > 0) {
                                    remaining--;
                                } else {
                                    clearInterval(interval);
                                }
                            }, 1000);
                        });
                    ">
                     <div class="grid size-12 place-items-center bg-cover bg-no-repeat font-extrabold"
                        style="background-image: url('{{ asset('images/clock.gif') }}');
                        background-position: 0 -3px;"
                        x-text="remaining"
                    >
                    </div>

                    <span class="font-bold">Round 1 of 8</span>
                </div>
                <span class="font-semibold tracking-widest text-white uppercase">waiting</span>
                <div class="flex gap-4">
                    <div class="size-12 cursor-pointer bg-cover bg-no-repeat"
                        style="background-image: url('{{ asset('images/settings.gif') }}')"></div>
                    <button wire:click="removePlayer"
                        class="rounded bg-red-500 px-3 py-1 text-white transition hover:bg-red-600">
                        Leave Game
                    </button>
                </div>
            </div>
            <div>
                @forelse ($players as $player)
                    <div class="flex h-12 items-center justify-between bg-gray-200 px-3 odd:bg-gray-300">
                        <div># {{ $loop->iteration }}</div>
                        <div class="flex flex-col items-center gap-0.5">
                            <span class="text-sm text-blue-600">
                                {{ $player->user->name }}
                                {{ $player->user_id === auth()->id() ? '(You)' : '' }}
                            </span>
                            <span class="text-xs">0 Points</span>
                        </div>
                        <div class="size-12 bg-cover bg-no-repeat"
                            style="
                                background-image: url('{{ asset('images/color_atlas.gif') }}');
                                background-position: 0px -2px;
                                background-size: 480px 480px;
                            ">
                        </div>
                    </div>
                @empty
                    <div class="flex h-12 items-center justify-between bg-gray-200 px-3 odd:bg-gray-300">
                        <div># 999</div>
                        <div class="flex flex-col items-center gap-0.5">
                            <span class="text-sm text-blue-600">
                                No Players Found
                            </span>
                            <span class="text-xs">0 Points</span>
                        </div>
                        <div class="size-12 bg-cover bg-no-repeat"
                            style="
                                background-image: url('{{ asset('images/color_atlas.gif') }}');
                                background-position: 0px -2px;
                                background-size: 480px 480px;
                            ">
                        </div>
                    </div>
                @endforelse
            </div>

            <div x-data="{
                colors: window.colors,
                selectedColor: [0, 0, 0, 255],
                'mode': 'pen',
                init() {
                    window.getSelectedColor = () => this.selectedColor;
                    window.getMode = () => this.mode;
                    window.setMode = (mode) => this.mode = mode;
                },
                selectColor(color) {
                    this.selectedColor = color;
                }
            }" class="relative">
                @if ($room->status === \App\Enums\RoomStatus::WAITING)
                    @if (!session('is_host'))
                        <div class="absolute top-0 z-10 grid size-full place-items-center bg-gray-300">
                            <p class="text-xl leading-1.5 capitalize">
                                waiting for the host to start the game...
                            </p>
                        </div>
                    @else
                        <form wire:submit="startGame"
                            class="absolute top-0 z-10 size-full space-y-4 rounded bg-gray-700 p-4 text-white">
                            <div class="flex justify-between">
                                <label for="name">Players</label>
                                <select class="w-1/2 border border-amber-50" name="players_count"
                                    wire:model="max_players">
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" class="bg-gray-500">
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="flex justify-between">
                                <label for="name">Rounds</label>
                                <select class="w-1/2 border border-amber-50" name="rounds" wire:model="rounds">
                                    @for ($i = 1; $i <= 8; $i++)
                                        <option value="{{ $i }}" class="bg-gray-500">
                                            {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="flex justify-between">
                                <label for="name">Draw time</label>
                                <div>
                                    <input type="number" name="drawtime" wire:model="drawtime" min="10"
                                        max="180" />
                                    seconds
                                </div>
                                {{--
                        <select
                            class="w-1/2 border border-amber-50"
                            name="drawtime"
                        >
                            @for ($i = 1; $i <= 8; $i++)
                            <option value="{{ $i }}" class="bg-gray-500">
                                {{ $i }} Seconds
                            </option>
                            @endfor
                        </select>
                        --}}
                            </div>

                            <div class="flex h-12 gap-2">
                                <button type="submit"
                                    class="flex-9/12 cursor-pointer rounded-sm bg-emerald-500 font-bold hover:bg-emerald-600">
                                    Start Game
                                </button>
                                <button type="submit"
                                    class="flex-1/4 cursor-pointer rounded-sm bg-cyan-600 font-bold hover:bg-cyan-700">
                                    Invite
                                </button>
                            </div>
                        </form>
                    @endif
                @endif
                    <canvas class="rounded bg-white" id="board" width="750" height="540"
                    x-init="
                    $wire.$watch('isDrawer', value => {
                        window.canDraw = value;
                    });
                    "
                    <!-- @contextmenu.prevent -->
                ></canvas>

                <div class="mt-4 flex w-full justify-between">
                    <div class="grid grid-cols-[repeat(13,1fr)] gap-1">
                        <template x-for="color in colors" :key="color.toString()">
                            <div class="size-5 cursor-pointer rounded border-2"
                                :style="{
                                    backgroundColor: `rgba(${color[0]}, ${color[1]}, ${color[2]}, ${color[3] / 255})`,
                                    borderColor: JSON.stringify(selectedColor) === JSON.stringify(color) ? '#323232' :
                                        'transparent'
                                }"
                                @click="selectColor(color)"></div>
                        </template>
                    </div>

                    <img src="{{ asset('images/pen.gif') }}" alt="Pen"
                        class="cursor-pointer rounded border-2 bg-white hover:border-black hover:opacity-85"
                        :class="mode === 'pen' ? 'border-black opacity-100' : 'border-transparent opacity-70'"
                        @click="setMode('pen')" />

                    <img src="{{ asset('images/fill.gif') }}" alt="Fill"
                        class="cursor-pointer rounded border-2 bg-white hover:border-black hover:opacity-85"
                        :class="mode === 'fill' ? 'border-black opacity-100' : 'border-transparent opacity-70'"
                        @click="setMode('fill')" />
                </div>
            </div>

            <div class="relative size-full rounded bg-white p-3">
                <input type="text" class="relative top-full w-full -translate-y-full rounded border px-4 py-2"
                    placeholder="Enter your guess here" />
            </div>
        </div>
    </div>
</div>
@script
    <script>
        window.userId = @js(auth()->user()->id);
        window.roomCode = @js($room->code);
        window.canDraw = $wire.isDrawer;

        window.addEventListener("pagehide", e => {
            if (!e.persisted) {
                $wire.call('removePlayer');
            }
        });
    </script>
@endscript
