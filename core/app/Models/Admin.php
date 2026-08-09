<?php

namespace App\Models;


use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasPermission($permissions)
    {
        if ($this->role_id == 0) {
            return true;
        }

        $userPermissions = $this->role->permissions ?? [];

        if (is_array($permissions)) {
            foreach ($permissions as $permission) {
                if ($this->checkPermission($userPermissions, $permission)) {
                    return true;
                }
            }
        } else {
            return $this->checkPermission($userPermissions, $permissions);
        }

        return false;
    }

    protected function checkPermission($userPermissions, $targetPermission)
    {
        foreach ($userPermissions as $permission) {
            if ($permission == $targetPermission) {
                return true;
            }
            if (str_ends_with($permission, '*')) {
                 $permissionPrefix = substr($permission, 0, -1);
                 // If permission is just '*', it matches everything
                 if ($permissionPrefix === '' || $permissionPrefix === false) {
                     return true;
                 }
                 if (str_starts_with($targetPermission, $permissionPrefix)) {
                     return true;
                 }
            }
        }
        return false;
    }

}
