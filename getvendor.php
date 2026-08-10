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

function fetchUrl($url) {
    // Try curl first
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
        $data = curl_exec($ch);
        $err  = curl_error($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if ($data !== false && $code == 200) return $data;
        return false;
    }
    // Try file_get_contents with stream context
    $ctx = stream_context_create(['http' => [
        'timeout' => 60,
        'user_agent' => 'Mozilla/5.0',
        'follow_location' => true,
    ]]);
    return @file_get_contents($url, false, $ctx);
}

echo "<pre style='font-family:monospace;background:#111;color:#0f0;padding:20px;font-size:12px;'>";

// Check what functions are available
echo "curl available: " . (function_exists('curl_init') ? 'YES' : 'NO') . "\n";
echo "allow_url_fopen: " . (ini_get('allow_url_fopen') ? 'YES' : 'NO') . "\n\n";
flush();

echo "Step 1: Downloading vendor chunks...\n"; flush();
$out = fopen($vendorZip, 'wb');
foreach ($chunks as $chunk) {
    $url = $base . $chunk;
    echo "  Fetching $chunk ... "; flush();
    $data = fetchUrl($url);
    if ($data === false || strlen($data) < 1000) {
        echo "FAILED (got " . strlen($data) . " bytes)\n";
        fclose($out);
        @unlink($vendorZip);
        echo "\nINFO: InfinityFree is blocking outbound HTTP.\n";
        echo "Trying alternative: download via GitHub API...\n";
        die();
    }
    fwrite($out, $data);
    echo round(strlen($data)/1024) . "KB OK\n"; flush();
}
fclose($out);
$zipSize = filesize($vendorZip);
echo "\nvendor.zip assembled: " . round($zipSize/1024/1024,1) . " MB\n\n"; flush();

if ($zipSize < 50000000) {
    echo "WARNING: zip seems too small (" . $zipSize . " bytes). Aborting.\n";
    unlink($vendorZip);
    die();
}

echo "Step 2: Extracting vendor files...\n"; flush();
$zip = new ZipArchive();
if ($zip->open($vendorZip) !== true) {
    die("Failed to open vendor.zip");
}
$total = $zip->numFiles;
echo "  Files in zip: $total\n"; flush();

for ($i = 0; $i < $total; $i++) {
    $name = $zip->getNameIndex($i);
    $dest = $coreDir . '/' . substr($name, strlen('core/'));
    $dir  = dirname($dest);
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    if (substr($name, -1) !== '/') {
        file_put_contents($dest, $zip->getFromIndex($i));
    }
    if ($i % 2000 === 0) { echo "  ... $i / $total\n"; flush(); }
}
$zip->close();
unlink($vendorZip);

$autoload = $coreDir . '/vendor/autoload.php';
if (file_exists($autoload)) {
    echo "\n✅ SUCCESS! vendor/autoload.php exists.\n";
    echo "✅ <a href='/'>Click here to open your site!</a>\n";
} else {
    echo "\n❌ autoload.php missing after extraction.\n";
}
unlink(__FILE__);
echo "\n(Script deleted for security)\n</pre>";
?>