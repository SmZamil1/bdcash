<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \App\Models\Trade::truncate();
    \App\Models\Transaction::whereIn('remark', ['binary_trade', 'binary_trade_win'])->delete();
    echo "Done";
}
catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
