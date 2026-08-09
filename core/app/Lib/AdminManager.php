<?php

namespace App\Lib;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminManager
{
    /**
     * Create a new admin user if not exists.
     *
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $name
     * @return array
     */
    public static function createAdmin($username, $password, $email, $name = 'Super Admin')
    {
        $admin = Admin::where('username', $username)->first();
        
        if ($admin) {
            return [
                'status' => 'info',
                'message' => 'Admin already exists!',
                'admin' => $admin
            ];
        }

        $admin = new Admin();
        $admin->name = $name;
        $admin->email = $email;
        $admin->username = $username;
        $admin->password = Hash::make($password);
        $admin->save();

        return [
            'status' => 'success',
            'message' => 'Admin created successfully!',
            'admin' => $admin
        ];
    }
}
