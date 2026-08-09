<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Frontend;
use App\Models\Holiday;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;

class GeneralSettingController extends Controller
{
    public function systemSetting()
    {
        $pageTitle = 'System Settings';
        $settings  = json_decode(file_get_contents(resource_path('views/admin/setting/settings.json')));
        return view('admin.setting.system', compact('pageTitle', 'settings'));
    }
    public function general()
    {
        $pageTitle       = 'General Setting';
        $timezones       = timezone_identifiers_list();
        $currentTimezone = array_search(config('app.timezone'), $timezones);
        return view('admin.setting.general', compact('pageTitle', 'timezones', 'currentTimezone'));
    }

    public function generalUpdate(Request $request)
    {
        $request->validate([
            'site_name'           => 'required|string|max:40',
            'cur_text'            => 'required|string|max:40',
            'cur_sym'             => 'required|string|max:40',
            'timezone'            => 'required|integer',
            'signup_bonus_amount' => 'required|numeric|gte:0',
            'f_charge'            => 'required|numeric|gte:0',
            'p_charge'            => 'required|numeric|gte:0',
            'b_transfer'          => 'required|in:1,0',
        ]);


        $timezones = timezone_identifiers_list();
        $timezone  = @$timezones[$request->timezone] ?? 'UTC';

        $general                      = gs();
        $general->site_name           = $request->site_name;
        $general->cur_text            = $request->cur_text;
        $general->cur_sym             = $request->cur_sym;
        $general->signup_bonus_amount = $request->signup_bonus_amount;
        $general->withdraw_off_days   = $request->withdraw_off_days;
        $general->referral_parameter  = $request->referral_parameter ?? 'reference';
        $general->f_charge            = $request->f_charge;
        $general->p_charge            = $request->p_charge;
        $general->b_transfer          = $request->b_transfer;
        $general->save();

        $timezoneFile = config_path('timezone.php');
        $content      = '<?php $timezone = "' . $timezone . '" ?>';
        file_put_contents($timezoneFile, $content);
        $notify[] = ['success', 'General setting updated successfully'];
        return back()->withNotify($notify);
    }

    public function systemConfiguration()
    {
        $pageTitle = 'System Configuration';
        return view('admin.setting.configuration', compact('pageTitle'));
    }

    public function systemConfigurationSubmit(Request $request)
    {
        $general                       = gs();
        $general->kv                   = $request->kv ? Status::ENABLE : Status::DISABLE;
        $general->ev                   = $request->ev ? Status::ENABLE : Status::DISABLE;
        $general->en                   = $request->en ? Status::ENABLE : Status::DISABLE;
        $general->sv                   = $request->sv ? Status::ENABLE : Status::DISABLE;
        $general->sn                   = $request->sn ? Status::ENABLE : Status::DISABLE;
        $general->pn                   = $request->pn ? Status::ENABLE : Status::DISABLE;
        $general->force_ssl            = $request->force_ssl ? Status::ENABLE : Status::DISABLE;
        $general->secure_password      = $request->secure_password ? Status::ENABLE : Status::DISABLE;
        $general->registration         = $request->registration ? Status::ENABLE : Status::DISABLE;
        $general->agree                = $request->agree ? Status::ENABLE : Status::DISABLE;
        $general->multi_language       = $request->multi_language ? Status::ENABLE : Status::DISABLE;
        $general->b_transfer           = $request->b_transfer ? Status::ENABLE : Status::DISABLE;
        $general->promotional_tool     = $request->promotional_tool ? Status::ENABLE : Status::DISABLE;
        $general->signup_bonus_control = $request->signup_bonus_control ? Status::ENABLE : Status::DISABLE;
        $general->holiday_withdraw     = $request->holiday_withdraw ? Status::ENABLE : Status::DISABLE;
        $general->user_ranking         = $request->user_ranking ? Status::ENABLE : Status::DISABLE;
        $general->schedule_invest      = $request->schedule_invest ? Status::ENABLE : Status::DISABLE;
        $general->staking_option       = $request->staking_option ? Status::ENABLE : Status::DISABLE;
        $general->pool_option          = $request->pool_option ? Status::ENABLE : Status::DISABLE;
        $general->metamask_login       = $request->metamask_login ? Status::ENABLE : Status::DISABLE;
        $general->save();
        $notify[] = ['success', 'System configuration updated successfully'];
        return back()->withNotify($notify);
    }

    public function logoIcon()
    {
        $pageTitle = 'Logo & Favicon';
        return view('admin.setting.logo_icon', compact('pageTitle'));
    }

    public function logoIconUpdate(Request $request)
    {
        $request->validate([
            'logo'      => ['image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'logo_dark' => ['image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
            'favicon'   => ['image', new FileTypeValidate(['png'])],
        ]);

        $path = getFilePath('logoIcon');
        if ($request->hasFile('logo')) {
            try {
                fileUploader($request->logo, $path, filename: 'logo.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the logo'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('logo_dark')) {
            try {
                fileUploader($request->logo_dark, $path, filename: 'logo_dark.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the logo'];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('favicon')) {
            try {
                fileUploader($request->favicon, $path, filename: 'favicon.png');
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload the favicon'];
                return back()->withNotify($notify);
            }
        }
        $notify[] = ['success', 'Logo & favicon updated successfully'];
        return back()->withNotify($notify);
    }

    public function customCss()
    {
        $pageTitle   = 'Custom CSS';
        $file        = activeTemplate(true) . 'css/custom.css';
        $fileContent = @file_get_contents($file);
        return view('admin.setting.custom_css', compact('pageTitle', 'fileContent'));
    }

    public function sitemap()
    {
        $pageTitle   = 'Sitemap XML';
        $file        = 'sitemap.xml';
        $fileContent = @file_get_contents($file);
        return view('admin.setting.sitemap', compact('pageTitle', 'fileContent'));
    }

    public function sitemapSubmit(Request $request)
    {
        $file = 'sitemap.xml';
        if (!file_exists($file)) {
            fopen($file, "w");
        }
        file_put_contents($file, $request->sitemap);
        $notify[] = ['success', 'Sitemap updated successfully'];
        return back()->withNotify($notify);
    }

    public function robot()
    {
        $pageTitle   = 'Robots TXT';
        $file        = 'robots.xml';
        $fileContent = @file_get_contents($file);
        return view('admin.setting.robots', compact('pageTitle', 'fileContent'));
    }

    public function robotSubmit(Request $request)
    {
        $file = 'robots.xml';
        if (!file_exists($file)) {
            fopen($file, "w");
        }
        file_put_contents($file, $request->robots);
        $notify[] = ['success', 'Robots txt updated successfully'];
        return back()->withNotify($notify);
    }

    public function customCssSubmit(Request $request)
    {
        $file = activeTemplate(true) . 'css/custom.css';
        if (!file_exists($file)) {
            fopen($file, "w");
        }
        file_put_contents($file, $request->css);
        $notify[] = ['success', 'CSS updated successfully'];
        return back()->withNotify($notify);
    }

    public function maintenanceMode()
    {
        $pageTitle   = 'Maintenance Mode';
        $maintenance = Frontend::where('data_keys', 'maintenance.data')->firstOrFail();
        return view('admin.setting.maintenance', compact('pageTitle', 'maintenance'));
    }

    public function maintenanceModeSubmit(Request $request)
    {
        $request->validate([
            'description' => 'required',
            'image'       => ['nullable', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);
        $general                   = gs();
        $general->maintenance_mode = $request->status ? Status::ENABLE : Status::DISABLE;
        $general->save();

        $maintenance = Frontend::where('data_keys', 'maintenance.data')->firstOrFail();
        $image       = @$maintenance->data_values->image;
        if ($request->hasFile('image')) {
            try {
                $old   = $image;
                $image = fileUploader($request->image, getFilePath('maintenance'), getFileSize('maintenance'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $maintenance->data_values = [
            'description' => $request->description,
            'image'       => $image,
        ];
        $maintenance->save();

        $notify[] = ['success', 'Maintenance mode updated successfully'];
        return back()->withNotify($notify);
    }

    public function cookie()
    {
        $pageTitle = 'GDPR Cookie';
        $cookie    = Frontend::where('data_keys', 'cookie.data')->firstOrFail();
        return view('admin.setting.cookie', compact('pageTitle', 'cookie'));
    }

    public function cookieSubmit(Request $request)
    {
        $request->validate([
            'short_desc'  => 'required|string|max:255',
            'description' => 'required',
        ]);
        $cookie              = Frontend::where('data_keys', 'cookie.data')->firstOrFail();
        $cookie->data_values = [
            'short_desc'  => $request->short_desc,
            'description' => $request->description,
            'status'      => $request->status ? Status::ENABLE : Status::DISABLE,
        ];
        $cookie->save();
        $notify[] = ['success', 'Cookie policy updated successfully'];
        return back()->withNotify($notify);
    }

    public function socialiteCredentials()
    {
        $pageTitle = 'Social Login Credentials';
        return view('admin.setting.social_credential', compact('pageTitle'));
    }

    public function updateSocialiteCredentialStatus($key)
    {
        $general     = gs();
        $credentials = $general->socialite_credentials;
        try {
            $credentials->$key->status = $credentials->$key->status == Status::ENABLE ? Status::DISABLE : Status::ENABLE;
        } catch (\Throwable $th) {
            abort(404);
        }

        $general->socialite_credentials = $credentials;
        $general->save();

        $notify[] = ['success', 'Status changed successfully'];
        return back()->withNotify($notify);
    }

    public function updateSocialiteCredential(Request $request, $key)
    {
        $general     = gs();
        $credentials = $general->socialite_credentials;
        try {
            @$credentials->$key->client_id     = $request->client_id;
            @$credentials->$key->client_secret = $request->client_secret;
        } catch (\Throwable $th) {
            abort(404);
        }
        $general->socialite_credentials = $credentials;
        $general->save();

        $notify[] = ['success', ucfirst($key) . ' credential updated successfully'];
        return back()->withNotify($notify);
    }

    public function holiday()
    {
        $holidays  = Holiday::paginate(getPaginate());
        $pageTitle = 'Holidays';
        return view('admin.setting.holidays', compact('holidays', 'pageTitle'));
    }

    public function offDaySubmit(Request $request)
    {
        $totalOffDay = count($request->off_day ?? []);

        if ($totalOffDay == 7) {
            $notify[] = ['error', 'You couldn\'t set all days as holiday'];
            return back()->withNotify($notify);
        }
        $general          = gs();
        $general->off_day = $request->off_day;
        $general->save();

        $notify[] = ['success', 'Weekly Holiday Setting Updated'];
        return back()->withNotify($notify);
    }

    public function holidaySubmit(Request $request)
    {
        $request->validate([
            'date'  => 'required|date',
            'title' => 'required',
        ]);
        $holiday        = new Holiday();
        $holiday->date  = $request->date;
        $holiday->title = $request->title;
        $holiday->save();
        $notify[] = ['success', 'Holiday added successfully'];
        return back()->withNotify($notify);
    }

    public function remove($id)
    {
        $holiday = Holiday::findOrFail($id);
        $holiday->delete();
        $notify[] = ['success', 'Holiday deleted successfully'];
        return back()->withNotify($notify);
    }

    public function deleteAllData(Request $request)
    {
        $request->validate([
            'admin_password' => 'required'
        ]);

        $admin = auth()->guard('admin')->user();
        if (!\Illuminate\Support\Facades\Hash::check($request->admin_password, $admin->password)) {
            $notify[] = ['error', 'Invalid password. Data reset aborted.'];
            return back()->withNotify($notify);
        }

     $tables = [
    // Core user data
    'users',
    'user_logins',
    'personal_access_tokens',
    'password_resets',
    'device_tokens',
    'admin_logins',
    'transactions',
    'deposits',
    'withdrawals',

    // Trading & Earning Modules (User Data)
    'trades',
    'invests',
    'pool_invests',
    'staking_invests',
    'user_minings',
    'user_ai_bots',
    'crash_games',
    'aviator_upcoming_results',
    'aviator_rounds',
    'aviator_bets',
    'ptc_ad_logs',
    'page_visits',

    // System Logs & History
    'admin_notifications',
    'notification_logs',
    'daily_reward_logs',
    'promocodes',
    'promocode_logs',
    'user_salaries',
    'user_salary_logs',
    'cron_job_logs',
    'user_rankings',
    'subscribers',
    'admin_ip_bans',
    'admin_password_resets',

    // Support System
    'support_tickets',
    'support_messages',
    'support_attachments',
];



        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        foreach ($tables as $table) {
            if (\Illuminate\Support\Facades\Schema::hasTable($table)) {
                \Illuminate\Support\Facades\DB::table($table)->truncate();
            }
        }
        \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        \Illuminate\Support\Facades\Artisan::call('optimize:clear');

        $notify[] = ['success', 'All specified tables have been cleaned and reset to ID 1.'];
        return back()->withNotify($notify);
    }
}
