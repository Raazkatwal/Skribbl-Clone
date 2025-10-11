
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

	Livewire.dispatch('whiteboard-draw', {
		x: x,
		y: y,
		color: getSelectedColor(),
		mode: getMode(),
		userId: userId
	});
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
