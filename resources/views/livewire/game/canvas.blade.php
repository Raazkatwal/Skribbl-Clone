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
                <div x-data="{
                    open: false,
                    words: [],
                    choose(word) {
                        this.open = false;
                        $wire.call('selectWord', word);
                    }
                }" x-init="console.log('wire on initialized');$wire.on('show-word-picker', e => {
                    words = e.words;
                    open = true;
                });">
                    <!-- Overlay -->
                    <div x-show="open" x-transition.opacity
                        class="absolute inset-0 grid place-items-center bg-black/60">
                        <div class="w-96 rounded-lg bg-white p-6 shadow-xl">
                            <h2 class="mb-4 text-center text-lg font-bold">
                                Choose a word
                            </h2>

                            <div class="grid grid-cols-3 gap-3">
                                <template x-for="word in words" :key="word">
                                    <button
                                        class="rounded border border-blue-500 px-4 py-2 font-semibold cursor-pointer text-black hover:bg-blue-600 hover:text-white"
                                        @click="choose(word)" x-text="word"></button>
                                </template>
                            </div>

                            <p class="mt-4 text-center text-sm text-gray-500">
                                You are the drawer 🎨
                            </p>
                        </div>
                    </div>
                </div>

                <canvas class="rounded bg-white" id="board" width="750" height="540" x-init="$wire.$watch('isDrawer', value => {
                    window.canDraw = value;
                });"
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

