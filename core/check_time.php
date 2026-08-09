<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$r = \App\Models\AviatorRound::find(1);
echo "Round 1 Started At: " . $r->started_at . "\n";
echo "Now: " . now() . "\n";
echo "Diff in Secs: " . now()->diffInSeconds($r->started_at) . "\n";
