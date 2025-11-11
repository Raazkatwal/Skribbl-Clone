import { floodFill, rgbaToCss } from "./helpers";

const canvas = document.getElementById("board");
const ctx = canvas.getContext("2d");
const userPaths = {}; // store per-user drawing state

document.addEventListener('DOMContentLoaded', () => {
    window.Echo.channel(`room.${window.roomCode}`)
        .listen(".whiteboard.draw", (e) => {
            const data = e.data;
            if (data.userId === window.userId) return;
            drawRemoteStroke(data);
        })
        .listen(".player.joined", () => {
            Livewire.dispatch("player-joined");
        })
        .listen(".player.left", () => {
            Livewire.dispatch("player-left");
        });
});

function drawRemoteStroke(data) {
    const { userId, type, x, y, color } = data;
    ctx.strokeStyle = rgbaToCss(color);
    ctx.lineWidth = 3;
    ctx.lineCap = "round";

    if (type === "start") {
        ctx.beginPath();
        userPaths[userId] = { lastX: x, lastY: y };
        ctx.moveTo(x, y);
    } else if (type === "move") {
        const last = userPaths[userId];
        if (!last) return;
        ctx.lineTo(x, y);
        ctx.stroke();
        userPaths[userId] = { lastX: x, lastY: y };
    } else if (type === "end") {
        delete userPaths[userId];
        ctx.beginPath();
    } else if (type === "fill") {
        floodFill(x, y, color, ctx, canvas);
    }
}
