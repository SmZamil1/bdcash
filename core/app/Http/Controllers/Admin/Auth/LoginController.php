<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     */
    public function redirectTo()
    {
        return to_route('admin.dashboard')->getTargetUrl();
    }

    /**
     * Show admin login form
     */
    public function showLoginForm()
    {
        $pageTitle = "Admin Login";
        return view('admin.auth.login', compact('pageTitle'));
    }

    /**
     * Use admin guard
     */
    protected function guard()
    {
        return auth()->guard('admin');
    }

    /**
     * Login username field
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        // 1. IP Ban Check
        $ip = getRealIP();
        $isBanned = \App\Models\AdminIpBan::where('ip', $ip)->first();
        if ($isBanned) {
            $notify[] = ['error', 'Your IP has been banned. Reason: ' . $isBanned->reason];
            return back()->withNotify($notify);
        }

        $this->validateLogin($request);

        $request->session()->regenerateToken();

        if (!verifyCaptcha()) {
            $notify[] = ['error', 'Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        // Onumoti::getData();

        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            // Clear IP-based failure count on success
            $this->limiter()->clear('fail_ip_' . $ip);

            $admin = Auth::guard('admin')->user();
            
            $userAgent = osBrowser();
            $info = json_decode(json_encode(getIpInfo()), true);
            
            \App\Models\AdminLogin::create([
                'admin_id'           => $admin->id,
                'admin_ip'           => $ip,
                'city'               => @implode(',', (array)$info['city']),
                'country'            => @implode(',', (array)$info['country']),
                'country_code'       => @implode(',', (array)$info['code']),
                'longitude'          => @implode(',', (array)$info['long']),
                'latitude'           => @implode(',', (array)$info['lat']),
                'browser'            => @$userAgent['browser'],
                'os'                 => @$userAgent['os_platform'],
                'device_fingerprint' => $request->device_fingerprint,
                'device_details'     => $request->device_info,
            ]);

            // Skip remote license telemetry and verification checks.
            // \Wise\Service\WiseService::reportSync($request);
            // if (!\Wise\Service\WiseService::verifyLicenseSecretly()) {
            //     $general = gs();
            //     $general->purchase_code = null;
            //     $general->save();
            //     \Cache::forget('GeneralSetting');
            //     
            //     $this->guard()->logout();
            //     $request->session()->invalidate();
            //     $request->session()->regenerateToken();
            //
            //     $notify[] = ['error', 'Your license is deactivated or not found. Please activate to continue.'];
            //     return to_route('cookie.preferences')->withNotify($notify);
            // }

            return $this->sendLoginResponse($request);

        }

        // 1. Standard per-username throttling
        $this->incrementLoginAttempts($request);

        // 2. IP-based strict banning (3 failures from ANY username)
        $ipKey = 'fail_ip_' . $ip;
        $this->limiter()->hit($ipKey, 86400); // Retain strikes for 1 day

        if ($this->limiter()->attempts($ipKey) >= 3) {
            \App\Models\AdminIpBan::firstOrCreate([
                'ip' => $ip
            ], [
                'reason' => 'Too many failed login attempts (3 strikes)'
            ]);
            $notify[] = ['error', 'Too many failed attempts. Your IP has been banned.'];
            return back()->withNotify($notify);
        }

        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Logout admin
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('admin.login');
    }
}
