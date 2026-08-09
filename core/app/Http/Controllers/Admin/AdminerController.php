<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminerController extends Controller
{
    public function index()
    {
        // Define Adminer configuration before including it
        function adminer_object() {
            class AdminerCustom extends \Adminer {
                function name() {
                    return 'Database Manager';
                }

                function credentials() {
                    // Automatically use credentials from Laravel config
                    return [
                        config('database.connections.mysql.host'),
                        config('database.connections.mysql.username'),
                        config('database.connections.mysql.password')
                    ];
                }

                function database() {
                    // Automatically use database name from Laravel config
                    return config('database.connections.mysql.database');
                }
                
                function login($login, $password) {
                    // Since it's protected by Laravel Admin Auth, we can auto-login
                    return true;
                }
            }
            return new AdminerCustom;
        }

        // Include the Adminer file
        require_once base_path('database/adminer.php');
    }
}
