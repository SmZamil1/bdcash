<?php

// Path to the installed file in the current directory
$installedFilePath = __DIR__ . '/installed';

// If not installed, redirect to install folder in current path
if (!file_exists($installedFilePath)) {

    // Get current directory URL path
    $currentPath = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

    header("Location: {$currentPath}/install");
    exit;
}

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Check maintenance mode
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register Composer autoloader
require __DIR__ . '/core/vendor/autoload.php';

// Bootstrap Laravel and handle request
(require_once __DIR__ . '/core/bootstrap/app.php')
    ->handleRequest(Request::capture());
