const http = require('http');
const crypto = require('crypto');

// Configuration
const PORT = 8081;
const UPDATE_PATH = '/update-state';

// State Store
let gameState = {
    type: 'state',
    round_id: 0,
    round_number: 0,
    status: 'waiting',
    multiplier: 1.00,
    crash_point: 0,
    countdown: 0,
    server_time: Date.now() / 1000,
    flying_start: 0
};

// Connected Clients
const clients = new Set();

// Create HTTP Server for both WebSockets and JSON Updates
const server = http.createServer((req, res) => {
    // Handle POST updates from Laravel
    if (req.method === 'POST' && req.url === UPDATE_PATH) {
        let body = '';
        req.on('data', chunk => { body += chunk.toString(); });
        req.on('end', () => {
            try {
                const data = JSON.parse(body);
                gameState = { ...gameState, ...data, server_time: Date.now() / 1000 };
                broadcastState();
                res.writeHead(200, { 'Content-Type': 'application/json' });
                res.end(JSON.stringify({ status: 'success' }));
            } catch (e) {
                res.writeHead(400);
                res.end('Invalid Payload');
            }
        });
        return;
    }

    // Health check or simple status
    res.writeHead(200);
    res.end('Aviator WSS Server is Running');
});

// WebSocket Handshake Logic
server.on('upgrade', (req, socket, head) => {
    if (req.headers['upgrade'] !== 'websocket') {
        socket.destroy();
        return;
    }

    const key = req.headers['sec-websocket-key'];
    const acceptKey = crypto
        .createHash('sha1')
        .update(key + '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')
        .digest('base64');

    const headers = [
        'HTTP/1.1 101 Switching Protocols',
        'Upgrade: websocket',
        'Connection: Upgrade',
        `Sec-WebSocket-Accept: ${acceptKey}`,
        ''
    ].join('\r\n');

    socket.write(headers + '\r\n');

    // Add to clients
    clients.add(socket);
    console.log('Client connected. Total:', clients.size);

    // Send current state immediately
    sendFrame(socket, JSON.stringify(gameState));

    socket.on('data', buffer => {
        // We don't expect data from clients in this game, but handle close frames
        const byte1 = buffer[0];
        if ((byte1 & 0x0F) === 0x08) { // Connection Close
            clients.delete(socket);
            socket.destroy();
        }
    });

    socket.on('error', () => {
        clients.delete(socket);
    });

    socket.on('end', () => {
        clients.delete(socket);
    });
});

function broadcastState() {
    const payload = JSON.stringify(gameState);
    for (let socket of clients) {
        try {
            sendFrame(socket, payload);
        } catch (e) {
            clients.delete(socket);
        }
    }
}

function sendFrame(socket, text) {
    const payload = Buffer.from(text);
    const length = payload.length;
    let header;

    if (length <= 125) {
        header = Buffer.alloc(2);
        header[0] = 0x81; // FIN + Opcode 1 (Text)
        header[1] = length;
    } else if (length <= 65535) {
        header = Buffer.alloc(4);
        header[0] = 0x81;
        header[1] = 126;
        header.writeUInt16BE(length, 2);
    } else {
        header = Buffer.alloc(10);
        header[0] = 0x81;
        header[1] = 127;
        header.writeBigUInt64BE(BigInt(length), 2);
    }

    socket.write(Buffer.concat([header, payload]));
}

server.listen(PORT, '0.0.0.0', () => {
    console.log(`🚀 Aviator WSS Server started on port ${PORT}`);
    console.log(`📡 Update state via POST to http://localhost:${PORT}${UPDATE_PATH}`);
});
