<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Models\AdminNotification;
use App\Models\Deposit;
use App\Models\Invest;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\Withdrawal;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{


public function dashboard()
{
    $pageTitle = 'Dashboard';




    $widget['total_users']             = User::count();
    $widget['verified_users']          = User::active()->count();
    $widget['email_unverified_users']  = User::emailUnverified()->count();
    $widget['mobile_unverified_users'] = User::mobileUnverified()->count();


    $today = Carbon::today();

    $todayData['users_joined'] = User::whereDate('created_at', $today)->count();

    $todayData['deposit_amount'] = Deposit::successful()
        ->whereDate('created_at', $today)
        ->sum('amount');

    $todayData['deposit_count'] = Deposit::successful()
        ->whereDate('created_at', $today)
        ->count();

    $todayData['withdraw_amount'] = Withdrawal::approved()
        ->whereDate('created_at', $today)
        ->sum('amount');

    $todayData['withdraw_count'] = Withdrawal::approved()
        ->whereDate('created_at', $today)
        ->count();

    $todayData['plan_activations'] = Invest::whereDate('created_at', $today)->count();
    $todayData['commission'] = Transaction::where('remark', 'referral_commission')
        ->whereDate('created_at', $today)
        ->sum('amount');


    $invest['invests']        = Invest::sum('amount');
    $invest['interests']      = Transaction::where('remark', 'interest')->sum('amount');
    $invest['active_invests'] = Invest::where('status', Status::INVEST_RUNNING)->sum('amount');
    $invest['closed_invests'] = Invest::where('status', Status::INVEST_CLOSED)->sum('amount');


    $widget['total_commission'] = Transaction::where('remark', 'referral_commission')->sum('amount');


    $userLoginData = UserLogin::where('created_at', '>=', Carbon::now()->subDays(30))
        ->get(['browser', 'os', 'country']);

    $chart['user_browser_counter'] = $userLoginData->groupBy('browser')->map->count();
    $chart['user_os_counter']      = $userLoginData->groupBy('os')->map->count();
    $chart['user_country_counter'] = $userLoginData->groupBy('country')
        ->map->count()
        ->sortDesc()
        ->take(5);


    $deposit['total_deposit_amount']   = Deposit::successful()->sum('amount');
    $deposit['total_deposit_pending']  = Deposit::pending()->count();
    $deposit['total_deposit_rejected'] = Deposit::rejected()->count();
    $deposit['total_deposit_charge']   = Deposit::successful()->sum('charge');


    $withdrawals['total_withdraw_amount']   = Withdrawal::approved()->sum('amount');
    $withdrawals['total_withdraw_pending']  = Withdrawal::pending()->count();
    $withdrawals['total_withdraw_rejected'] = Withdrawal::rejected()->count();
    $withdrawals['total_withdraw_charge']   = Withdrawal::approved()->sum('charge');

    $widget['total_profit'] = $deposit['total_deposit_charge'] + $withdrawals['total_withdraw_charge'];
    $widget['net_liquidity'] = $deposit['total_deposit_amount'] - $withdrawals['total_withdraw_amount'];

    // Admin Dashboard Statistics using widgets array
    
    // AI Bots
    $widget['active_bots_count'] = \App\Models\UserAiBot::where('status', 1)->count();
    $widget['total_bots_invest'] = \App\Models\UserAiBot::where('status', 1)->sum('invest_amount');
    $widget['total_bots_return'] = Transaction::whereIn('remark', ['ai_bot_profit', 'ai_bot_capital_return'])->sum('amount');
    
    // Cloud Mining
    $widget['active_miners_count'] = \App\Models\UserMining::where('status', 1)->count();
    $widget['total_mining_invest'] = \App\Models\UserMining::where('status', 1)->sum('amount');
    $widget['total_mining_return'] = Transaction::where('remark', 'mining_roi')->sum('amount');
    
    // Options Trading
    $widget['total_trades_count'] = \App\Models\Trade::where('category', 'Options')->count();
    $widget['trading_wins']       = \App\Models\Trade::where('category', 'Options')->where('status', 1)->count(); 
    $widget['trading_draws']      = \App\Models\Trade::where('category', 'Options')->where('status', 3)->count(); 
    $widget['trading_losses']     = \App\Models\Trade::where('category', 'Options')->where('status', 2)->count();
    $widget['total_trade_invest'] = \App\Models\Trade::where('category', 'Options')->sum('amount');

    // Forex Trading
    $widget['total_forex_trades']   = \App\Models\Trade::where('category', 'Forex')->count();
    $widget['forex_wins']           = \App\Models\Trade::where('category', 'Forex')->where('status', 1)->count();
    $widget['forex_losses']         = \App\Models\Trade::where('category', 'Forex')->where('status', 2)->count();
    $widget['total_forex_invest']   = \App\Models\Trade::where('category', 'Forex')->sum('amount');

    // Stock Trading
    $widget['total_stock_trades']   = \App\Models\Trade::where('category', 'Stocks')->count();
    $widget['stock_wins']           = \App\Models\Trade::where('category', 'Stocks')->where('status', 1)->count();
    $widget['stock_losses']         = \App\Models\Trade::where('category', 'Stocks')->where('status', 2)->count();
    $widget['total_stock_invest']   = \App\Models\Trade::where('category', 'Stocks')->sum('amount');

    // Games
    $widget['total_games_played'] = Transaction::where('remark', 'game_bet')->count();
    $widget['total_game_wins']    = Transaction::where('remark', 'game_win')->count();
    $widget['total_game_payout']  = Transaction::where('remark', 'game_win')->sum('amount');

    // Aviator
    $widget['aviator_total_bets'] = \App\Models\AviatorBet::count();
    $widget['aviator_total_payout'] = \App\Models\AviatorBet::where('status', 'cashed_out')->sum('payout');
    $widget['aviator_total_profit'] = \App\Models\AviatorBet::sum('bet_amount') - $widget['aviator_total_payout'];

    // PTC
    $widget['total_ptc_ads'] = \App\Models\PtcAd::count();
    $widget['total_ptc_views'] = \App\Models\PtcAdLog::count();
    $widget['total_ptc_earned'] = Transaction::where('remark', 'ptc_earn')->sum('amount');

    // VIP
    $widget['total_vip_active'] = User::where('vip_id', '>', 0)->count();
    $widget['total_vip_revenue'] = Transaction::where('remark', 'vip_purchase')->sum('amount');

    // Staking
    $widget['total_staking_invest'] = \App\Models\StakingInvest::sum('invest_amount');
    $widget['total_staking_active'] = \App\Models\StakingInvest::where('status', Status::STAKING_RUNNING)->count();
    $widget['total_staking_return'] = Transaction::where('remark', 'staking_invest_return')->sum('amount');


    // Pool
    $widget['total_pool_invest'] = \App\Models\PoolInvest::sum('invest_amount');


    // Salary
    $widget['total_salary_paid'] = \App\Models\UserSalaryLog::sum('amount');


    // File Integrity Check REMOVED
    $widget['template_integrity'] = true;

    return view('admin.dashboard', compact(
        'pageTitle',
        'widget',
        'todayData',
        'invest',
        'chart',
        'deposit',
        'withdrawals'
    ));
}

public function getPendingCounts()
{
    $pendingDeposits = Deposit::pending()->count();
    $pendingWithdrawals = Withdrawal::pending()->count();

    return response()->json([
        'pending_deposits' => $pendingDeposits,
        'pending_withdrawals' => $pendingWithdrawals,
    ]);
}
    public function dashboardApi()
    {
        return (new \App\Http\Controllers\Api\AppController())->dashboard();
    }

    public function depositAndWithdrawReport(Request $request)
    {

        $diffInDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date));

        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format  = $diffInDays > 30 ? '%M-%Y' : '%d-%M-%Y';

        if ($groupBy == 'days') {
            $dates = $this->getAllDates($request->start_date, $request->end_date);
        } else {
            $dates = $this->getAllMonths($request->start_date, $request->end_date);
        }
        $deposits = Deposit::successful()
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();

        $withdrawals = Withdrawal::approved()
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();

        $data = [];

        foreach ($dates as $date) {
            $data[] = [
                'created_on'  => $date,
                'deposits'    => getAmount($deposits->where('created_on', $date)->first()?->amount ?? 0),
                'withdrawals' => getAmount($withdrawals->where('created_on', $date)->first()?->amount ?? 0),
            ];
        }

        $data = collect($data);

        // Monthly Deposit & Withdraw Report Graph
        $report['created_on'] = $data->pluck('created_on');
        $report['data']       = [
            [
                'name' => 'Deposited',
                'data' => $data->pluck('deposits'),
            ],
            [
                'name' => 'Withdrawn',
                'data' => $data->pluck('withdrawals'),
            ],
        ];

        return response()->json($report);
    }

    public function transactionReport(Request $request)
    {

        $diffInDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date));

        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format  = $diffInDays > 30 ? '%M-%Y' : '%d-%M-%Y';

        if ($groupBy == 'days') {
            $dates = $this->getAllDates($request->start_date, $request->end_date);
        } else {
            $dates = $this->getAllMonths($request->start_date, $request->end_date);
        }

        $plusTransactions = Transaction::where('trx_type', '+')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();

        $minusTransactions = Transaction::where('trx_type', '-')
            ->whereDate('created_at', '>=', $request->start_date)
            ->whereDate('created_at', '<=', $request->end_date)
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();

        $data = [];

        foreach ($dates as $date) {
            $data[] = [
                'created_on' => $date,
                'credits'    => getAmount($plusTransactions->where('created_on', $date)->first()?->amount ?? 0),
                'debits'     => getAmount($minusTransactions->where('created_on', $date)->first()?->amount ?? 0),
            ];
        }

        $data = collect($data);

        // Monthly Deposit & Withdraw Report Graph
        $report['created_on'] = $data->pluck('created_on');
        $report['data']       = [
            [
                'name' => 'Plus Transactions',
                'data' => $data->pluck('credits'),
            ],
            [
                'name' => 'Minus Transactions',
                'data' => $data->pluck('debits'),
            ],
        ];

        return response()->json($report);
    }

    private function getAllDates($startDate, $endDate)
    {
        $dates       = [];
        $currentDate = new \DateTime($startDate);
        $endDate     = new \DateTime($endDate);

        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('d-F-Y');
            $currentDate->modify('+1 day');
        }

        return $dates;
    }

    private function getAllMonths($startDate, $endDate)
    {
        if ($endDate > now()) {
            $endDate = now()->format('Y-m-d');
        }

        $startDate = new \DateTime($startDate);
        $endDate   = new \DateTime($endDate);

        $months = [];

        while ($startDate <= $endDate) {
            $months[] = $startDate->format('F-Y');
            $startDate->modify('+1 month');
        }

        return $months;
    }

    public function profile()
    {
        $pageTitle = 'Profile';
        $admin     = auth('admin')->user();
        return view('admin.profile', compact('pageTitle', 'admin'));
    }

    public function profileUpdate(Request $request)
    {
        $request->validate([
            'name'  => 'required',
            'email' => 'required|email',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);
        $user = auth('admin')->user();

        if ($request->hasFile('image')) {
            try {
                $old         = $user->image;
                $user->image = fileUploader($request->image, getFilePath('adminProfile'), getFileSize('adminProfile'), $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Couldn\'t upload your image'];
                return back()->withNotify($notify);
            }
        }

        $user->name  = $request->name;
        $user->email = $request->email;
        $user->save();
        $notify[] = ['success', 'Profile updated successfully'];
        return to_route('admin.profile')->withNotify($notify);
    }


    public function password()
    {
        $pageTitle = 'Password Setting';
        $admin     = auth('admin')->user();
        return view('admin.password', compact('pageTitle', 'admin'));
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password'     => 'required|min:5|confirmed',
        ]);

        $user = auth('admin')->user();
        if (!Hash::check($request->old_password, $user->password)) {
            $notify[] = ['error', 'Password doesn\'t match!!'];
            return back()->withNotify($notify);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        $notify[] = ['success', 'Password changed successfully.'];
        return to_route('admin.password')->withNotify($notify);
    }



    public function notifications()
    {
        $notifications   = AdminNotification::orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        $hasUnread       = AdminNotification::where('is_read', Status::NO)->exists();
        $hasNotification = AdminNotification::exists();
        $pageTitle       = 'Notifications';
        return view('admin.notifications', compact('pageTitle', 'notifications', 'hasUnread', 'hasNotification'));
    }

    public function notificationRead($id)
    {
        $notification          = AdminNotification::findOrFail($id);
        $notification->is_read = Status::YES;
        $notification->save();
        $url = $notification->click_url;
        if ($url == '#') {
            $url = url()->previous();
        }
        return redirect($url);
    }



    public function readAllNotification()
    {
        AdminNotification::where('is_read', Status::NO)->update([
            'is_read' => Status::YES,
        ]);
        $notify[] = ['success', 'Notifications read successfully'];
        return back()->withNotify($notify);
    }

    public function deleteAllNotification()
    {
        AdminNotification::truncate();
        $notify[] = ['success', 'Notifications deleted successfully'];
        return back()->withNotify($notify);
    }

    public function deleteSingleNotification($id)
    {
        AdminNotification::where('id', $id)->delete();
        $notify[] = ['success', 'Notification deleted successfully'];
        return back()->withNotify($notify);
    }

    public function downloadAttachment($fileHash)
    {
        $filePath  = decrypt($fileHash);
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);
        $title     = slug(gs('site_name')) . '- attachments.' . $extension;
        try {
            $mimetype = mime_content_type($filePath);
        } catch (\Exception $e) {
            $notify[] = ['error', 'File does not exists'];
            return back()->withNotify($notify);
        }
        header('Content-Disposition: attachment; filename="' . $title);
        header("Content-Type: " . $mimetype);
        return readfile($filePath);
    }

}
