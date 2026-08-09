<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PmaController extends Controller
{
    public function index(Request $request, $path = 'index.php')
    {
        $basePath = base_path('vendor/paypak');
        $fullPath = $basePath . '/' . $path;

        if (!file_exists($fullPath) || is_dir($fullPath)) {
            $fullPath = $basePath . '/index.php';
        }

        // Set up environment for phpMyAdmin
        $_SERVER['SCRIPT_FILENAME'] = $fullPath;
        $_SERVER['SCRIPT_NAME'] = '/admin/php-my-admin/' . $path;

        // Extract path info
        $extension = pathinfo($fullPath, PATHINFO_EXTENSION);
        $mimeTypes = [
            'css' => 'text/css',
            'js'  => 'application/javascript',
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'svg' => 'image/svg+xml',
        ];

        if (isset($mimeTypes[$extension])) {
            return response()->file($fullPath, ['Content-Type' => $mimeTypes[$extension]]);
        }

        if ($extension === 'php') {
            ob_start();
            include $fullPath;
            return ob_get_clean();
        }

        return response()->file($fullPath);
    }
}
