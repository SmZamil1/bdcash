<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AdminLogin;
use App\Models\AdminIpBan;

class AdminSecurityController extends Controller
{
    public function security()
    {
        $pageTitle = 'Security Setting';
        $admin     = auth('admin')->user();
        
        // If no secondary password is set (first time), allow access to set it
        if (!$admin->secondary_password) {
            return view('admin.security', compact('pageTitle', 'admin'));
        }

        // If password is set but not verified in this session, redirect to verify
        if (!session('admin_security_page_auth')) {
             return to_route('admin.security.verify');
        }

        return view('admin.security', compact('pageTitle', 'admin'));
    }

    public function securityStore(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'secondary_password' => 'nullable|confirmed|min:4'
        ]);

        $user = auth('admin')->user();

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            $notify[] = ['error', 'Current password doesn\'t match!!'];
            return back()->withNotify($notify);
        }

        if ($request->secondary_password) {
            $user->secondary_password = \Illuminate\Support\Facades\Hash::make($request->secondary_password);
        }

        if ($request->is_secondary_auth_enabled) {
            if (!$user->secondary_password && !$request->secondary_password) {
                 $notify[] = ['error', 'Please set a secondary password to enable this feature.'];
                 return back()->withNotify($notify);
            }
            $user->is_secondary_auth_enabled = 1;
        } else {
            $user->is_secondary_auth_enabled = 0;
        }

        $user->save();
        
        // Invalidate session to force re-verification with new/current password
        session()->forget('admin_security_page_auth');
        session()->forget('admin_secondary_auth_verified');

        $notify[] = ['success', 'Security settings updated successfully.'];
        return back()->withNotify($notify);
    }

    public function verifySecurityForm() {
        $pageTitle = 'Security Verification';
        return view('admin.verify_security', compact('pageTitle'));
    }

    public function verifySecurity(Request $request) {
        $request->validate([
            'code' => 'required'
        ]);
        
        $user = auth('admin')->user();
        if (\Illuminate\Support\Facades\Hash::check($request->code, $user->secondary_password)) {
            session()->put('admin_security_page_auth', true);
            session()->put('admin_secondary_auth_verified', true);
            return to_route('admin.dashboard');
        }
        
        $notify[] = ['error', 'Invalid security password.'];
        return back()->withNotify($notify);
    }



    public function securityRouteUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'admin_route' => 'required|alpha_dash|min:3'
        ]);

        $user = auth('admin')->user();
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
             $notify[] = ['error', 'Current password doesn\'t match!!'];
             return back()->withNotify($notify);
        }

        $route = trim($request->admin_route);
        if($route == config('app.admin_route')){
             $notify[] = ['info', 'No changes detected.'];
             return back()->withNotify($notify);
        }

        // Check for standard .env first
        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            // Check for the user's strange custom location if standard missing
            $envPath = base_path('vendor/psr/log/.env');
        }
        
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            if (strpos($envContent, 'ADMIN_ROUTE=') !== false) {
                 $envContent = preg_replace('/^ADMIN_ROUTE=.*/m', 'ADMIN_ROUTE=' . $route, $envContent);
            } else {
                 $envContent .= "\nADMIN_ROUTE=" . $route;
            }
            file_put_contents($envPath, $envContent);
            \Illuminate\Support\Facades\Artisan::call('config:clear');
            
            $notify[] = ['success', 'Admin route updated successfully. New Login URL: ' . url('/') . '/' . $route];
            return redirect(url('/') . '/' . $route . '/security')->withNotify($notify);
        }
        
        $notify[] = ['error', 'Unable to update .env file.'];
        return back()->withNotify($notify);
    }

    public function loginHistory()
    {
        $pageTitle = 'Admin Login History';
        $loginLogs = AdminLogin::with('admin')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.security_history', compact('pageTitle', 'loginLogs'));
    }

    public function ipBan(Request $request)
    {
        $request->validate([
            'ip' => 'required|ip',
            'reason' => 'nullable|string|max:255'
        ]);

        $ip = $request->ip;

        if ($ip == getRealIP()) {
             $notify[] = ['error', 'You cannot ban your own IP address!'];
             return back()->withNotify($notify);
        }

        $exist = AdminIpBan::where('ip', $ip)->first();
        if ($exist) {
             $exist->delete();
             $notify[] = ['success', 'IP unbanned successfully.'];
        } else {
             AdminIpBan::create([
                 'ip' => $ip,
                 'reason' => $request->reason ?? 'Admin manual ban'
             ]);
             $notify[] = ['success', 'IP banned successfully.'];
        }

        return back()->withNotify($notify);
    }
}
