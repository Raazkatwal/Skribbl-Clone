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
                    [255, 255, 255, 255],
                    [193, 193, 193, 255],
                    [239, 19, 11, 255],
                    [255, 113, 0, 255],
                    [255, 228, 0, 255],
                    [0, 204, 0, 255],
                    [0, 255, 145, 255],
                    [0, 178, 255, 255],
                    [35, 31, 211, 255],
                    [163, 0, 186, 255],
                    [223, 105, 167, 255],
                    [255, 172, 142, 255],
                    [160, 82, 45, 255],
                    [0, 0, 0, 255],
                    [80, 80, 80, 255],
                    [116, 11, 7, 255],
                    [194, 56, 0, 255],
                    [232, 162, 0, 255],
                    [0, 70, 25, 255],
                    [0, 120, 93, 255],
                    [0, 86, 158, 255],
                    [14, 8, 101, 255],
                    [85, 0, 105, 255],
                    [135, 53, 84, 255],
                    [204, 119, 77, 255],
                    [99, 48, 13, 255],
                ],
                selectedColor: [0, 0, 0, 255],
                'mode': 'pen',
                init() {
                    window.getSelectedColor = () => this.selectedColor;
                    window.getMode = () => this.mode;
                    window.setMode = (mode) => this.mode = mode;
                    setupCanvas();
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
@script
    <script>
        const canvas = document.getElementById('board');
        const ctx = canvas.getContext('2d');

        let drawing = false;

        canvas.addEventListener('mousedown', (e) => {

            const x = e.offsetX;
            const y = e.offsetY;

            if (getMode() === 'pen') {
                drawing = true;
                draw(e);
            } else if (getMode() === 'fill') {
                const fillColor = getSelectedColor();
                floodFill(x, y, fillColor, ctx, canvas);
            }
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
            ctx.strokeStyle = rgbaToCss(getSelectedColor());

            ctx.lineTo(x, y);
            ctx.stroke();
            ctx.beginPath();
            ctx.moveTo(x, y);

            // TODO: Broadcast to others via Reverb
        }

        function rgbaToCss([r, g, b, a]) {
            return `rgba(${r}, ${g}, ${b}, ${a / 255})`;
        }

        function fillCanvas(color) {
            ctx.fillStyle = color;
            ctx.fillRect(0, 0, canvas.width, canvas.height);
        }

        function floodFill(x, y, fillColor, ctx, canvas) {
            const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
            const data = imageData.data;
            const width = canvas.width;

            const startPos = (y * width + x) * 4;
            const targetColor = data.slice(startPos, startPos + 4);
            if (colorMatch(targetColor, fillColor)) return;

            const stack = [
                [x, y]
            ];

            while (stack.length) {
                const [cx, cy] = stack.pop();
                const pos = (cy * width + cx) * 4;
                const currentColor = data.slice(pos, pos + 4);
                if (!colorMatch(currentColor, targetColor)) continue;

                data[pos] = fillColor[0];
                data[pos + 1] = fillColor[1];
                data[pos + 2] = fillColor[2];
                data[pos + 3] = fillColor[3];

                if (cx > 0) stack.push([cx - 1, cy]);
                if (cx < width - 1) stack.push([cx + 1, cy]);
                if (cy > 0) stack.push([cx, cy - 1]);
                if (cy < canvas.height - 1) stack.push([cx, cy + 1]);
            }

            ctx.putImageData(imageData, 0, 0);
        }

        function colorMatch(a, b, tolerance = 16) {
            return (
                Math.abs(a[0] - b[0]) <= tolerance &&
                Math.abs(a[1] - b[1]) <= tolerance &&
                Math.abs(a[2] - b[2]) <= tolerance &&
                Math.abs(a[3] - b[3]) <= tolerance
            );
        }
    </script>
@endscript
