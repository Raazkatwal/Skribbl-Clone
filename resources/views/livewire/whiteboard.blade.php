<div class="h-screen w-screen grid place-items-center">
    <div class="size-11/12">
        <div class="logo m-auto"></div>
        <div class="grid grid-cols-[20%_60%_20%] grid-rows-[10%_90%] gap-1.5 size-full">
            <div
                class="bg-gradient-to-r from-blue-500 to-purple-500 shadow-md rounded col-span-3 flex justify-between items-center px-2">
                <div class="flex items-center gap-4">
                    <div class="size-12 bg-no-repeat bg-cover grid place-items-center font-extrabold"
                        style="background-image: url('{{ asset('images/clock.gif') }}');
                        background-position: 0 -3px;
                        ">
                        10
                    </div>
                    <span class="font-bold">Round 1 of 8</span>
                </div>
                <span class="uppercase tracking-widest font-semibold text-white">waiting</span>
                <div class="size-12 bg-no-repeat bg-cover"
                    style="background-image: url('{{ asset('images/settings.gif') }}')"></div>
                <button
                        wire:click="removePlayer"
                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition"
                    >
                    Leave Game
                </button>
            </div>
            <div>
                @foreach ($players as $player)
                    <div class="flex items-center justify-between bg-gray-200 odd:bg-gray-300 px-3 h-12">
                        <div># {{ $loop->iteration }} </div>
                        <div class="flex flex-col items-center gap-0.5">
                            <span class="text-blue-600 text-sm">
                                {{ $player->name }} {{ $player->name === session('username') ? '(You)' : '' }}
                            </span>
                            <span class="text-xs">0 Points</span>
                        </div>
                        <div class="size-12 bg-no-repeat bg-cover"
                            style="
                                background-image: url('{{ asset('images/color_atlas.gif') }}');
                                background-position: 0px -2px;
                                background-size: 480px 480px;
                            ">
                        </div>
                    </div>
                @endforeach
            </div>

            <div x-data="{
                colors: window.colors,
                selectedColor: [0, 0, 0, 255],
                'mode': 'pen',
                init() {
                    window.getSelectedColor = () => this.selectedColor;
                    window.getMode = () => this.mode;
                    window.setMode = (mode) => this.mode = mode;
                    //setupCanvas();
                },
                selectColor(color) {
                    this.selectedColor = color;
                }
            }">
                <canvas class="rounded bg-white" id="board" width="750" height="540"></canvas>

                <div class="flex w-full mt-4 justify-between">
                    <div class="grid grid-cols-[repeat(13,1fr)] gap-1">
                        <template x-for="color in colors" :key="color.toString()">
                            <div class="size-5 rounded cursor-pointer border-2"
                                :style="{
                                    backgroundColor: `rgba(${color[0]}, ${color[1]}, ${color[2]}, ${color[3] / 255})`,
                                    borderColor: JSON.stringify(selectedColor) === JSON.stringify(color) ? '#323232' :
                                        'transparent'
                                }"
                                @click="selectColor(color)">
                            </div>
                        </template>
                    </div>

                    <img src="{{ asset('images/pen.gif') }}" alt="Pen"
                        class="bg-white border-2 rounded cursor-pointer hover:border-black hover:opacity-85"
                        :class="mode === 'pen' ? 'border-black opacity-100' : 'border-transparent opacity-70'"
                        @click="setMode('pen')">

                    <img src="{{ asset('images/fill.gif') }}" alt="Fill"
                        class="bg-white border-2 rounded cursor-pointer hover:border-black hover:opacity-85"
                        :class="mode === 'fill' ? 'border-black opacity-100' : 'border-transparent opacity-70'"
                        @click="setMode('fill')">
                </div>
            </div>


            <div class="rounded bg-white relative p-3 size-full">
                <input type="text" class="w-full relative top-full -translate-y-full border px-4 py-2 rounded"
                    placeholder="Enter your guess here">
            </div>
        </div>
    </div>
</div>
