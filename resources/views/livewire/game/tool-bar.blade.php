<div class="mt-4 flex w-full justify-between" x-data="{
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
}">
    <div class="grid grid-cols-[repeat(13,1fr)] gap-1">
        <template x-for="color in colors" :key="color.toString()">
            <div class="size-5 cursor-pointer rounded border-2"
                :style="{
                    backgroundColor: `rgba(${color[0]}, ${color[1]}, ${color[2]}, ${color[3] / 255})`,
                    borderColor: JSON.stringify(selectedColor) === JSON.stringify(color) ? '#323232' : 'transparent'
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
