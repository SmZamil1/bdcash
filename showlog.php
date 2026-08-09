<?php
$log = __DIR__ . '/core/storage/logs/laravel.log';
if (file_exists($log)) {
    $lines = file($log);
    $last = array_slice($lines, -50);
    echo "<pre style='background:#111;color:#0f0;padding:20px;font-size:11px;'>";
    echo htmlspecialchars(implode('', $last));
    echo "</pre>";
} else {
    echo "Log file not found at: $log<br>";
    // Also show PHP errors
    echo "PHP version: " . phpversion() . "<br>";
    echo "Checking .env: " . (file_exists(__DIR__.'/core/.env') ? 'EXISTS' : 'MISSING') . "<br>";
    echo "Checking installed: " . (file_exists(__DIR__.'/installed') ? 'EXISTS' : 'MISSING') . "<br>";
    echo "Checking vendor: " . (file_exists(__DIR__.'/core/vendor/autoload.php') ? 'EXISTS' : 'MISSING') . "<br>";
    echo "Checking bootstrap: " . (file_exists(__DIR__.'/core/bootstrap/app.php') ? 'EXISTS' : 'MISSING') . "<br>";
}
?>