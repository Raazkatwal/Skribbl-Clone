import { floodFill, rgbaToCss } from "./helpers";

const getCanvas = () => document.getElementById("board");

const getCtx = () => {
    const canvas = getCanvas();

    return canvas ? canvas.getContext("2d") : null;
};

const userPaths = {};

let subscribed = false;

function subscribeToChannel() {
    if (subscribed || !window.Echo || !window.roomCode) return;

    subscribed = true;

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
            console.log("player left");
            Livewire.dispatch("player-left");
        })
        .listen(".game.started", () => {
            Livewire.dispatch("game-started");
        })
        .listen(".word.selected", () => {
            Livewire.dispatch("word-picked");
        })
        .listen(".chat.message", (e) => {
            Livewire.dispatch("chat-message", {
                playerName: e.playerName,
                message: e.message,
                isCorrect: e.isCorrect,
                isSystem: e.isSystem,
            });
            if (e.isCorrect) {
                Livewire.dispatch("score-updated");
            }
        });
}

subscribeToChannel();

document.addEventListener("DOMContentLoaded", subscribeToChannel);

let tries = 0;
const interval = setInterval(() => {
    if (subscribed || tries++ >= 50) {
        clearInterval(interval);

        return;
    }

    subscribeToChannel();
}, 100);

function drawRemoteStroke(data) {
    const canvas = getCanvas();
    const ctx = getCtx();

    if (!canvas || !ctx) return;

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