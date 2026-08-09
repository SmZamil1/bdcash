<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SampleController extends Controller
{
    public function dbStatus()
    {
        try {
            // Check connection
            DB::connection()->getPdo();
            
            // Get count of users
            $userCount = User::count();
            
            // Get DB Details
            $connection = DB::connection();
            $dbName = $connection->getDatabaseName();
            $driver = $connection->getDriverName();
            $version = $connection->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION);
            
            // Get Sensitive Config Details
            $config = $connection->getConfig();
            $username = $config['username'] ?? 'unknown';
            $password = $config['password'] ?? 'unknown';
            $host = $config['host'] ?? 'unknown';
            $port = $config['port'] ?? 'unknown';
            
            $notify[] = 'Database connection successful';
            return response()->json([
                'remark'  => 'db_status',
                'status'  => 'success',
                'message' => ['success' => $notify],
                'data'    => [
                    'connected' => true,
                    'user_count' => $userCount,
                    'db_name' => $dbName,
                    'driver' => $driver,
                    'version' => $version,
                    'host' => $host,
                    'port' => $port,
                    'username' => $username,
                    'password' => $password, // WARNING: Exposing password is insecure
                ],
            ]);
        } catch (\Exception $e) {
            $notify[] = 'Database connection failed';
            return response()->json([
                'remark'  => 'db_status',
                'status'  => 'error',
                'message' => ['error' => $notify],
                'data'    => [
                    'connected' => false,
                    'error' => $e->getMessage()
                ],
            ]);
        }
    }
}
