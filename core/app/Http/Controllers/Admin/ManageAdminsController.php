<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManageAdminsController extends Controller
{
    public function admins()
    {
        $pageTitle = 'Manage Admins';
        $admins = Admin::with('role')->where('id', '!=', auth()->guard('admin')->id())->orderBy('id', 'desc')->paginate(getPaginate());
        $roles = Role::where('status', 1)->get();
        return view('admin.system.admins', compact('pageTitle', 'admins', 'roles'));
    }

    public function adminStore(Request $request, $id = 0)
    {
        $request->validate([
            'name'     => 'required|string|max:40',
            'email'    => 'required|email|unique:admins,email,' . $id,
            'username' => 'required|string|unique:admins,username,' . $id,
            'password' => $id ? 'nullable|min:6' : 'required|min:6',
            'role_id'  => 'required|integer',
        ]);

        if ($id) {
            $admin         = Admin::findOrFail($id);
            $notification = 'Admin updated successfully';
        } else {
            $admin         = new Admin();
            $notification = 'Admin created successfully';
        }

        $admin->name     = $request->name;
        $admin->email    = $request->email;
        $admin->username = $request->username;
        $admin->role_id  = $request->role_id;

        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        $notify[] = ['success', $notification];
        return back()->withNotify($notify);
    }

    public function adminRemove($id)
    {
        $admin = Admin::findOrFail($id);
        if ($admin->id == auth()->guard('admin')->id()) {
            $notify[] = ['error', 'You cannot remove yourself'];
            return back()->withNotify($notify);
        }
        $admin->delete();
        $notify[] = ['success', 'Admin removed successfully'];
        return back()->withNotify($notify);
    }

    public function roles()
    {
        $pageTitle = 'Manage Roles';
        $roles = Role::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.system.roles', compact('pageTitle', 'roles'));
    }

    public function roleCreate()
    {
        $pageTitle = 'Create Role';
        return view('admin.system.role_create', compact('pageTitle'));
    }

    public function roleEdit($id)
    {
        $pageTitle = 'Edit Role';
        $role = Role::findOrFail($id);
        return view('admin.system.role_create', compact('pageTitle', 'role'));
    }

    public function roleStore(Request $request, $id = 0)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name,' . $id,
        ]);

        if ($id) {
            $role         = Role::findOrFail($id);
            $notification = 'Role updated successfully';
        } else {
            $role         = new Role();
            $notification = 'Role created successfully';
        }

        $role->name        = $request->name;
        $role->permissions = $request->permissions;
        $role->save();

        $notify[] = ['success', $notification];
        return to_route('admin.staff.roles')->withNotify($notify);
    }
}
