<?php
set_time_limit(300);
ini_set('memory_limit', '512M');

$base     = "https://raw.githubusercontent.com/SmZamil1/bdcash/main/vendor_chunks/";
$chunks   = ['vendor_part_aa','vendor_part_ab','vendor_part_ac','vendor_part_ad',
             'vendor_part_ae','vendor_part_af','vendor_part_ag','vendor_part_ah',
             'vendor_part_ai','vendor_part_aj','vendor_part_ak','vendor_part_al'];

$tmpZip   = '/tmp/vendor_' . time() . '.zip';
$htdocs   = __DIR__;          // /home/vol14_1/.../htdocs
$coreVendor = $htdocs . '/core/vendor';

echo "<pre style='background:#111;color:#0f0;padding:15px;font-size:12px;'>";
echo "htdocs path: $htdocs\n";
echo "target: $coreVendor\n";
echo "tmp zip: $tmpZip\n\n"; flush();

// Step 1: Download chunks to /tmp
echo "=== STEP 1: Downloading chunks to /tmp ===\n"; flush();
$out = fopen($tmpZip, 'wb');
if (!$out) die("Cannot write to /tmp — aborting\n");

foreach ($chunks as $chunk) {
    $url = $base . $chunk;
    echo "  $chunk ... "; flush();
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT        => 60,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT      => 'Mozilla/5.0',
    ]);
    $data = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err  = curl_error($ch);
    curl_close($ch);

    if ($data === false || $code !== 200 || strlen($data) < 100) {
        fclose($out); @unlink($tmpZip);
        die("FAILED (HTTP $code, ".strlen($data)." bytes, err: $err)\n</pre>");
    }
    fwrite($out, $data);
    echo round(strlen($data)/1024) . "KB ✓\n"; flush();
}
fclose($out);
$sz = filesize($tmpZip);
echo "\nAssembled: " . round($sz/1024/1024,1) . "MB\n\n"; flush();

if ($sz < 50000000) { @unlink($tmpZip); die("Zip too small — download incomplete\n</pre>"); }

// Step 2: Extract from /tmp straight into htdocs/core/vendor
echo "=== STEP 2: Extracting ===\n"; flush();
$zip = new ZipArchive();
if ($zip->open($tmpZip) !== true) die("Cannot open zip\n</pre>");

$total = $zip->numFiles;
echo "Files in zip: $total\n"; flush();
$done = 0; $fail = 0;

for ($i = 0; $i < $total; $i++) {
    $name = $zip->getNameIndex($i);
    // name is like: core/vendor/autoload.php
    // strip "core/" prefix → vendor/autoload.php
    $rel  = substr($name, strlen('core/'));
    $dest = $htdocs . '/core/' . $rel;
    $dir  = dirname($dest);

    if (substr($name, -1) === '/') {
        @mkdir($dest, 0755, true);
        continue;
    }
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    $bytes = file_put_contents($dest, $zip->getFromIndex($i));
    if ($bytes !== false) $done++; else $fail++;

    if ($i % 3000 === 0) { echo "  Progress: $i/$total (ok:$done fail:$fail)\n"; flush(); }
}
$zip->close();
@unlink($tmpZip);

echo "\nDone! Written: $done | Failed: $fail\n\n";

// Verify
if (file_exists($htdocs . '/core/vendor/autoload.php')) {
    echo "✅ vendor/autoload.php EXISTS!\n";
    echo "✅ <a href='/' style='color:#0ff'>OPEN YOUR SITE</a>\n";
} else {
    echo "❌ autoload.php missing. Failed count: $fail\n";
    echo "Hint: open_basedir may still be blocking writes.\n";
    echo "open_basedir: " . ini_get('open_basedir') . "\n";
}
@unlink(__FILE__);
echo "\n(script deleted)\n</pre>";
?>