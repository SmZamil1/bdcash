<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$r = \App\Models\AviatorRound::find(1);
echo "Round 1 Status: " . $r->status . "\n";
echo "Round 1 Started At (DB String): " . $r->getRawOriginal('started_at') . "\n";
echo "Round 1 Started At (Timestamp): " . $r->started_at->timestamp . "\n";

$ch = curl_init("https://google.com");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
$resp = curl_exec($ch);
curl_close($ch);
if (preg_match('/Date: (.*)/i', $resp, $matches)) {
    $syncTime = strtotime(trim($matches[1]));
    echo "Current Sync Time: " . $syncTime . " (" . date('Y-m-d H:i:s', $syncTime) . ")\n";
    echo "Elapsed: " . ($syncTime - $r->started_at->timestamp) . " seconds\n";
}
