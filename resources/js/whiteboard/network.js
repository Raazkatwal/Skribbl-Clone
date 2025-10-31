import { floodFill, rgbaToCss } from "./helpers";

const canvas = document.getElementById("board");
const ctx = canvas.getContext("2d");
window.userId = Math.random().toString(36).substring(2, 9);
const urlParams = new URLSearchParams(window.location.search);
const roomCode = urlParams.get("room");

const userPaths = {}; // store per-user drawing state

window.Echo.channel(`room.${roomCode}`)
    .listen(".whiteboard.draw", (e) => {
        const data = e.data;
        if (data.userId === window.userId) return;
        drawRemoteStroke(data);
    })
    .listen(".player.joined", (e) => {
        Livewire.dispatch("player-joined", { name: e.username });
    })
    .listen(".player.left", (e) => {
        Livewire.dispatch("player-left", { name: e.username });
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
