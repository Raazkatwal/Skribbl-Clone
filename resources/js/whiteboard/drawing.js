import { floodFill, rgbaToCss } from "./helpers";
const canvas = document.getElementById("board");
const ctx = canvas.getContext("2d");

let drawing = false;

canvas.addEventListener("mousedown", (e) => {
    const x = e.offsetX;
    const y = e.offsetY;

    if (!window.canDraw) return;
    if (getMode() === "pen") {
        drawing = true;
        drawPoint(x, y, "start");
    } else if (getMode() === "fill") {
        floodFill(x, y, getSelectedColor(), ctx, canvas);

        Livewire.dispatch("whiteboard-draw", {
            type: "fill",
            x,
            y,
            color: getSelectedColor(),
            mode: getMode(),
            userId: window.userId,
        });
    }
});

canvas.addEventListener("mouseup", () => {
    if (!drawing) return;
    drawing = false;
    drawPoint(null, null, "end");
});

canvas.addEventListener("mousemove", (e) => {
    if (!drawing || getMode() !== "pen") return;
    drawPoint(e.offsetX, e.offsetY, "move");
});

canvas.addEventListener("mouseleave", () => {
    if (drawing) {
        drawing = false;
        drawPoint(null, null, "end");
    }
});

function drawPoint(x, y, type) {
    if (type === "start") {
        ctx.beginPath();
        ctx.moveTo(x, y);
    } else if (type === "move") {
        ctx.lineTo(x, y);
        ctx.lineWidth = 3;
        ctx.lineCap = "round";
        ctx.strokeStyle = rgbaToCss(getSelectedColor());
        ctx.stroke();
    }

    Livewire.dispatch("whiteboard-draw", {
        type,
        x,
        y,
        color: getSelectedColor(),
        mode: getMode(),
        userId: window.userId,
    });
}
