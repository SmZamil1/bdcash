<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Models\AviatorRound;

class AviatorSocket extends Command
{
    protected $signature = 'aviator:socket {port=8080}';
    protected $description = 'Run a simple WebSocket server for Aviator Game';

    private $clients = [];
    private $master;

    public function handle()
    {
        $port = $this->argument('port');
        $address = '0.0.0.0';

        $this->master = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_option($this->master, SOL_SOCKET, SO_REUSEADDR, 1);
        socket_bind($this->master, $address, $port);
        socket_listen($this->master);

        $this->info("WebSocket Server started on ws://{$address}:{$port}");

        $this->clients = [$this->master];

        while (true) {
            $read = $this->clients;
            $write = null;
            $except = null;

            // Use a small timeout to allow periodic broadcasting
            if (socket_select($read, $write, $except, 0, 100000) > 0) {
                foreach ($read as $socket) {
                    if ($socket == $this->master) {
                        $client = socket_accept($this->master);
                        $this->clients[] = $client;
                        $this->info("New client connected!");
                    } else {
                        $bytes = @socket_recv($socket, $buffer, 2048, 0);
                        if ($bytes === 0) {
                            $this->disconnect($socket);
                        } else {
                            $this->handleMessage($socket, $buffer);
                        }
                    }
                }
            }

            // Periodically broadcast state
            $this->broadcastState();
            usleep(100000); // 100ms broadcast interval
        }
    }

    private function handleMessage($socket, $buffer)
    {
        if (strpos($buffer, 'GET / ') !== false || strpos($buffer, 'Upgrade: websocket') !== false) {
            $this->handshake($socket, $buffer);
        }
    }

    private function handshake($socket, $buffer)
    {
        $headers = [];
        $lines = preg_split("/\r\n/", $buffer);
        foreach ($lines as $line) {
            if (preg_match('/\A(\S+): (.*)\z/', $line, $matches)) {
                $headers[$matches[1]] = $matches[2];
            }
        }

        $key = $headers['Sec-WebSocket-Key'];
        $hash = base64_encode(sha1($key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11', true));

        $upgrade = "HTTP/1.1 101 Switching Protocols\r\n" .
                   "Upgrade: websocket\r\n" .
                   "Connection: Upgrade\r\n" .
                   "Sec-WebSocket-Accept: $hash\r\n\r\n";

        socket_write($socket, $upgrade, strlen($upgrade));
    }

    private function broadcastState()
    {
        $roundId = Cache::get('aviator_current_round');
        $round = $roundId ? AviatorRound::find($roundId) : null;
        
        $gameState = Cache::get('aviator_game_state', 'waiting');
        $multiplier = Cache::get('aviator_multiplier', 1.00);
        $countdown = Cache::get('aviator_countdown', 0);
        $flyingStart = Cache::get('aviator_flying_start');

        $data = [
            'type' => 'state',
            'round_id' => $roundId,
            'round_number' => $round ? $round->round_number : 0,
            'status' => $gameState,
            'multiplier' => $multiplier,
            'crash_point' => $round ? $round->crash_point : 0,
            'countdown' => $countdown,
            'server_time' => microtime(true),
            'flying_start' => $flyingStart
        ];

        $payload = preg_replace('/\s+/', '', json_encode($data));
        $frame = $this->mask($payload);

        foreach ($this->clients as $client) {
            if ($client != $this->master) {
                @socket_write($client, $frame, strlen($frame));
            }
        }
    }

    private function disconnect($socket)
    {
        $index = array_search($socket, $this->clients);
        if ($index !== false) {
            unset($this->clients[$index]);
        }
        socket_close($socket);
        $this->info("Client disconnected");
    }

    private function mask($text)
    {
        $b1 = 0x80 | (0x1 & 0x0f);
        $length = strlen($text);

        if ($length <= 125) {
            $header = pack('CC', $b1, $length);
        } elseif ($length > 125 && $length < 65536) {
            $header = pack('CCn', $b1, 126, $length);
        } elseif ($length >= 65536) {
            $header = pack('CCNN', $b1, 127, $length >> 32, $length);
        }
        return $header . $text;
    }
}
