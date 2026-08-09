<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$rounds = \App\Models\AviatorRound::all();
foreach($rounds as $r) {
    echo "ID: {$r->id} | Num: {$r->round_number} | Status: {$r->status} | Crash: {$r->crash_point}\n";
}
echo "Current Round Info:\n";
$curr = \App\Models\AviatorRound::getCurrentRound();
if($curr) {
    echo "Active Round ID: {$curr->id}\n";
} else {
    echo "No Active Round Found\n";
}
