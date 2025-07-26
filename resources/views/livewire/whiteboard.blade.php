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
                <span class="uppercase tracking-widest font-semibold text-white">Waiting</span>
                <div class="size-12 bg-no-repeat bg-cover"
                    style="background-image: url('{{ asset('images/settings.gif') }}')"></div>

            </div>
            <div>
                @for ($i = 0; $i < 6; $i++)
                    <div class="flex items-center justify-between bg-gray-200 odd:bg-gray-300 px-3 h-12">
                        <div># {{ $i + 1 }} </div>
                        <div class="flex flex-col items-center gap-0.5">
                            <span class="text-blue-600 text-sm">
                                {{ $username }} {{ $i === 2 ? '(You)' : '' }}
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
                @endfor
            </div>

            <div x-data="{
                colors: [
                    '#fff', '#c1c1c1', '#ef130b', '#ff7100', '#ffe400',
                    '#00cc00', '#00ff91', '#00b2ff', '#231fd3', '#a300ba',
                    '#df69a7', '#ffac8e', '#a0522d', '#000', '#505050',
                    '#740b07', '#c23800', '#e8a200', '#004619', '#00785d',
                    '#00569e', '#0e0865', '#550069', '#873554', '#cc774d', '#63300d'
                ],
                selectedColor: '#000',
                init() {
                    window.getSelectedColor = () => this.selectedColor;
                },
                selectColor(color) {
                    console.log(color);
                    this.selectedColor = color;
                }
            }">
                <canvas class="rounded bg-white" id="board" width="750" height="540"></canvas>

                <div class="flex w-full mt-4">
                    <div class="grid grid-cols-[repeat(13,1fr)] gap-1">
                        <template x-for="color in colors" :key="color">
                            <div class="size-5 rounded cursor-pointer border-2"
                                :style="{
                                    backgroundColor: color,
                                    borderColor: selectedColor === color ? '#323232' : 'transparent'
                                }"
                                @click="selectColor(color)"></div>
                        </template>
                    </div>
                </div>
            </div>


            <div class="rounded bg-white relative p-3 size-full">
                <input type="text" class="w-full relative top-full -translate-y-full border px-4 py-2 rounded"
                    placeholder="Enter your guess here">
            </div>
        </div>
    </div>
</div>
@script
    <script>
        const canvas = document.getElementById('board');
        const ctx = canvas.getContext('2d');

        let drawing = false;

        canvas.addEventListener('mousedown', () => {
            drawing = true;
        });

        canvas.addEventListener('mouseup', () => {
            drawing = false;
            ctx.beginPath();
        });

        canvas.addEventListener('mousemove', draw);

        function draw(e) {
            if (!drawing) return;

            const x = e.offsetX;
            const y = e.offsetY;

            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.strokeStyle = getSelectedColor();

            ctx.lineTo(x, y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, y);

            // TODO: Broadcast to others via Reverb
        }

        function setColor(color) {

        }
    </script>
@endscript
