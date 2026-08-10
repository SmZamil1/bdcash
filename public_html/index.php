<?php
// Railway public entry point
// Serve the HyipX app from repo root
$root = dirname(__DIR__);
chdir($root);
require $root . '/index.php';
