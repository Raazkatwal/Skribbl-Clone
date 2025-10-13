import { rgbaToCss } from "./helpers";

const canvas = document.getElementById('board');
const ctx = canvas.getContext('2d');
window.userId = Math.random().toString(36).substring(2, 9);

const userPaths = {}; // store per-user drawing state

window.Echo.channel('chat').listen('.whiteboard.draw', (e) => {
    const data = e.data;
    if (data.userId === window.userId) return;
    drawRemoteStroke(data);
});

function drawRemoteStroke(data) {
    const { userId, type, x, y, color } = data;
    ctx.strokeStyle = rgbaToCss(color);
    ctx.lineWidth = 3;
    ctx.lineCap = 'round';

    if (type === 'start') {
        ctx.beginPath();
        userPaths[userId] = { lastX: x, lastY: y };
        ctx.moveTo(x, y);
    } else if (type === 'move') {
        const last = userPaths[userId];
        if (!last) return;
        ctx.lineTo(x, y);
        ctx.stroke();
        userPaths[userId] = { lastX: x, lastY: y };
    } else if (type === 'end') {
        delete userPaths[userId];
        ctx.beginPath();
    }
}
