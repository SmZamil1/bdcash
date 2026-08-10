<?php
set_time_limit(300);
ini_set('memory_limit', '256M');

$base = "https://raw.githubusercontent.com/SmZamil1/bdcash/main/vendor_chunks/";
$chunks = [
    'vendor_part_aa','vendor_part_ab','vendor_part_ac','vendor_part_ad',
    'vendor_part_ae','vendor_part_af','vendor_part_ag','vendor_part_ah',
    'vendor_part_ai','vendor_part_aj','vendor_part_ak','vendor_part_al'
];

$vendorZip = __DIR__ . '/vendor.zip';
$coreDir   = __DIR__ . '/core';

echo "<pre style='font-family:monospace;background:#111;color:#0f0;padding:20px;'>";
flush();

// Step 1: Download & reassemble
echo "Step 1: Downloading vendor chunks...\n"; flush();
$out = fopen($vendorZip, 'wb');
foreach ($chunks as $chunk) {
    $url = $base . $chunk;
    echo "  Fetching $chunk ... "; flush();
    $data = @file_get_contents($url);
    if ($data === false) {
        echo "FAILED\n";
        fclose($out);
        unlink($vendorZip);
        die("Error downloading $chunk");
    }
    fwrite($out, $data);
    echo strlen($data) . " bytes OK\n"; flush();
}
fclose($out);
echo "vendor.zip assembled: " . round(filesize($vendorZip)/1024/1024, 1) . " MB\n\n"; flush();

// Step 2: Extract
echo "Step 2: Extracting to server...\n"; flush();
$zip = new ZipArchive();
if ($zip->open($vendorZip) !== true) {
    die("Failed to open vendor.zip");
}

$total = $zip->numFiles;
echo "  Total files in zip: $total\n"; flush();

for ($i = 0; $i < $total; $i++) {
    $name = $zip->getNameIndex($i);
    // Strip leading "core/" — extract relative to $coreDir
    $dest = $coreDir . '/' . substr($name, strlen('core/'));
    $dir  = dirname($dest);
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    if (substr($name, -1) !== '/') {
        file_put_contents($dest, $zip->getFromIndex($i));
    }
    if ($i % 2000 === 0) { echo "  Extracted $i / $total files...\n"; flush(); }
}
$zip->close();

// Step 3: Cleanup
unlink($vendorZip);
echo "\nStep 3: Cleaned up zip.\n\n";

// Step 4: Verify
$autoload = $coreDir . '/vendor/autoload.php';
if (file_exists($autoload)) {
    echo "✅ vendor/autoload.php EXISTS — vendor installed successfully!\n";
    echo "✅ Visit <a href='/'>bdcash69.42web.io</a> to open your site.\n";
} else {
    echo "❌ autoload.php still missing — something went wrong.\n";
}

// Self-delete
unlink(__FILE__);
echo "\nThis script has been deleted for security.\n";
echo "</pre>";
?>