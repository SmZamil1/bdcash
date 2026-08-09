<?php
$dirs = [
    __DIR__ . '/core/bootstrap/cache',
    __DIR__ . '/core/storage',
    __DIR__ . '/core/storage/app',
    __DIR__ . '/core/storage/app/public',
    __DIR__ . '/core/storage/framework',
    __DIR__ . '/core/storage/framework/cache',
    __DIR__ . '/core/storage/framework/cache/data',
    __DIR__ . '/core/storage/framework/sessions',
    __DIR__ . '/core/storage/framework/testing',
    __DIR__ . '/core/storage/framework/views',
    __DIR__ . '/core/storage/logs',
    __DIR__ . '/core/storage/debugbar',
];

echo "<h2>Fixing Permissions...</h2>";
foreach ($dirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0775, true);
        echo "Created: $dir<br>";
    }
    if (chmod($dir, 0775)) {
        echo "✅ 775 OK: $dir<br>";
    } else {
        echo "❌ Failed: $dir<br>";
    }
}
echo "<h2>Done! <a href='/install'>Go to Installer</a></h2>";
// Delete this file after running for security
unlink(__FILE__);
echo "This file has been deleted automatically.";
?>
