<?php
// Check all available methods
echo "<pre>";
echo "curl: " . (function_exists('curl_init') ? 'YES' : 'NO') . "
";
echo "allow_url_fopen: " . ini_get('allow_url_fopen') . "
";
echo "exec: " . (function_exists('exec') ? 'YES' : 'NO') . "
";
echo "shell_exec: " . (function_exists('shell_exec') ? 'YES' : 'NO') . "
";
echo "ZipArchive: " . (class_exists('ZipArchive') ? 'YES' : 'NO') . "
";
echo "PHP version: " . phpversion() . "
";
echo "Disabled functions: " . ini_get('disable_functions') . "
";
echo "Open basedir: " . ini_get('open_basedir') . "
";
echo "upload_max_filesize: " . ini_get('upload_max_filesize') . "
";
echo "post_max_size: " . ini_get('post_max_size') . "
";
echo "max_execution_time: " . ini_get('max_execution_time') . "
";
echo "memory_limit: " . ini_get('memory_limit') . "
";

// Test actual curl to github
if (function_exists('curl_init')) {
    $ch = curl_init('https://raw.githubusercontent.com/SmZamil1/bdcash/main/vendor_chunks/vendor_part_aa');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_NOBODY, true); // HEAD request only
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    echo "curl to GitHub: HTTP $code | Error: " . ($err ?: 'none') . "
";
}

// Check if we can write to core/vendor
$testfile = __DIR__ . '/core/vendor/test_write.txt';
$write = @file_put_contents($testfile, 'test');
echo "Can write to core/vendor: " . ($write !== false ? 'YES' : 'NO') . "
";
@unlink($testfile);

unlink(__FILE__);
echo "</pre>";
?>