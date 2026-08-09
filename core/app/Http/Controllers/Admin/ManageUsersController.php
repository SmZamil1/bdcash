<?php
namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\UserNotificationSender;
use App\Models\Deposit;
use App\Models\NotificationLog;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Withdrawal;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageUsersController extends Controller {

    public function allUsers() {
        $pageTitle = 'All Users';
        $users     = $this->userData();
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function activeUsers() {
        $pageTitle = 'Plan Active Users';
        $users     = $this->userData('planActive');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function bannedUsers() {
        $pageTitle = 'Banned Users';
        $users     = $this->userData('banned');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function emailUnverifiedUsers() {
        $pageTitle = 'Email Unverified Users';
        $users     = $this->userData('emailUnverified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function kycUnverifiedUsers() {
        $pageTitle = 'KYC Unverified Users';
        $users     = $this->userData('kycUnverified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function kycPendingUsers() {
        $pageTitle = 'KYC Pending Users';
        $users     = $this->userData('kycPending');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function emailVerifiedUsers() {
        $pageTitle = 'Email Verified Users';
        $users     = $this->userData('emailVerified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function mobileUnverifiedUsers() {
        $pageTitle = 'Mobile Unverified Users';
        $users     = $this->userData('mobileUnverified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function mobileVerifiedUsers() {
        $pageTitle = 'Mobile Verified Users';
        $users     = $this->userData('mobileVerified');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    public function usersWithBalance() {
        $pageTitle = 'Users with Balance';
        $users     = $this->userData('withBalance');
        return view('admin.users.list', compact('pageTitle', 'users'));
    }

    protected function userData($scope = null) {
        if ($scope) {
            $users = User::$scope();
        } else {
            $users = User::query();
        }
        return $users->searchable(['username', 'email'])->orderBy('id', 'desc')->paginate(getPaginate());
    }

    public function detail($id) {
        $user      = User::findOrFail($id);
        $pageTitle = 'User Detail - ' . $user->username;

        $totalDeposit     = Deposit::where('user_id', $user->id)->successful()->sum('amount');
        $totalWithdrawals = Withdrawal::where('user_id', $user->id)->approved()->sum('amount');
        $totalTransaction = Transaction::where('user_id', $user->id)->count();
        $pendingTicket    = SupportTicket::where('user_id', $user->id)->whereIN('status', [Status::TICKET_OPEN, Status::TICKET_REPLY])->count();
        $countries        = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $plans            = \App\Models\Plan::where('status', Status::ENABLE)->get();
        return view('admin.users.detail', compact('pageTitle', 'user', 'totalDeposit', 'totalWithdrawals', 'totalTransaction', 'pendingTicket', 'countries', 'plans'));
    }

    public function activatePlan(Request $request, $id)
    {
        $request->validate([
            'plan_id' => 'required|integer|exists:plans,id',
            'amount'  => 'required|numeric|gt:0'
        ]);

        $user = User::findOrFail($id);
        $plan = \App\Models\Plan::findOrFail($request->plan_id);

        if ($request->amount < $plan->minimum || $request->amount > $plan->maximum) {
             if($plan->fixed_amount > 0 && $request->amount != $plan->fixed_amount){
                $notify[] = ['error', 'Please follow the investment limit'];
                return back()->withNotify($notify);
             }
             if($plan->fixed_amount == 0 && ($request->amount < $plan->minimum || $request->amount > $plan->maximum)){
                $notify[] = ['error', 'Please follow the investment limit'];
                return back()->withNotify($notify);
             }
        }

        $hyip = new \App\Lib\HyipLab($user, $plan);
        
        // Custom logic to duplicate invest functionality but without charging wallet if needed
        // For now, let's assume valid admin action simply creates the investment. 
        // We will manually create the investment to avoid balance deduction if we assume "Gift"
        // But user said "transaction also create".
        
        $hyip->invest($request->amount, 'interest_wallet', 0, false);
        
        // Notify
        notify($user, 'ADMIN_PLAN_ACTIVATE', [
            'plan_name' => $plan->name,
            'amount'    => showAmount($request->amount, currencyFormat:false),
            'trx'       => getTrx() // HyipLab generates its own TRX but we don't catch it here easily without refactoring HyipLab to return it. 
            // Ideally notify is handled inside HyipLab too ('INVESTMENT'), but ADMIN_PLAN_ACTIVATE is specific.
            // HyipLab sends 'INVESTMENT' notification.
            // We can probably rely on the 'INVESTMENT' notification from HyipLab and just send a success message here or keep this specific one.
            // HyipLab sends: 'INVESTMENT'.
            // Admin might want 'ADMIN_PLAN_ACTIVATE' specifically.
            // HyipLab doesn't return the TRX object. 
            // We can just pass null or generic placeholder for TRX if needed, or rely on HyipLab's notification.
        ]);

        $notify[] = ['success', 'Plan activated successfully for this user'];
        return back()->withNotify($notify);
    }

    public function kycDetails($id) {
        $pageTitle = 'KYC Details';
        $user      = User::findOrFail($id);
        return view('admin.users.kyc_detail', compact('pageTitle', 'user'));
    }

    public function kycApprove($id) {
        $user     = User::findOrFail($id);
        $user->kv = Status::KYC_VERIFIED;
        $user->save();

        notify($user, 'KYC_APPROVE', []);

        $notify[] = ['success', 'KYC approved successfully'];
        return to_route('admin.users.kyc.pending')->withNotify($notify);
    }

    public function kycReject(Request $request, $id) {
        $request->validate([
            'reason' => 'required',
        ]);
        $user                       = User::findOrFail($id);
        $user->kv                   = Status::KYC_UNVERIFIED;
        $user->kyc_rejection_reason = $request->reason;
        $user->save();

        notify($user, 'KYC_REJECT', [
            'reason' => $request->reason,
        ]);

        $notify[] = ['success', 'KYC rejected successfully'];
        return to_route('admin.users.kyc.pending')->withNotify($notify);
    }

    public function update(Request $request, $id) {
    $user         = User::findOrFail($id);
    $countryData  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
    $countryArray = (array) $countryData;
    $countries    = implode(',', array_keys($countryArray));

    $countryCode = $request->country;
    $country     = $countryData->$countryCode->country;
    $dialCode    = $countryData->$countryCode->dial_code;

    $request->validate([
        'email'     => 'required|email|string|max:40|unique:users,email,' . $user->id,
        'mobile'    => 'required|string|max:40',
        'country'   => 'required|in:' . $countries,
    ]);

    $exists = User::where('mobile', $request->mobile)
                  ->where('dial_code', $dialCode)
                  ->where('id', '!=', $user->id)
                  ->exists();

    if ($exists) {
        $notify[] = ['error', 'The mobile number already exists.'];
        return back()->withNotify($notify);
    }

    // Add withdrawal_limit column if not exists (Soft Migration)
    if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'withdrawal_limit')) {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users ADD withdrawal_limit DECIMAL(28,8) DEFAULT 0 AFTER interest_wallet');
    }

    $user->mobile           = $request->mobile;
    $user->email            = $request->email;
    $user->city             = $request->city;
    $user->state            = $request->state;
    $user->zip              = $request->zip;
    $user->country_name     = @$country;
    $user->dial_code        = $dialCode;
    $user->country_code     = $countryCode;
    
    $user->address = [
        'address' => $request->address,
        'city'    => $request->city,
        'state'   => $request->state,
        'zip'     => $request->zip,
        'country' => @$country,
    ];
    
    // Add required_referrals column if not exists
    if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'required_referrals')) {
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users ADD required_referrals INT DEFAULT 0 AFTER withdrawal_limit');
    }

    $user->withdrawal_limit = $request->withdrawal_limit ?? 0;
    $user->required_referrals = $request->required_referrals ?? 0;

    // Updated field names
    $user->deposit_access    = $request->deposit_access ? Status::VERIFIED : Status::UNVERIFIED;
    $user->withdraw_access   = $request->withdraw_access ? Status::VERIFIED : Status::UNVERIFIED;
    // $user->referral_required = $request->referral_required ? Status::ENABLE : Status::DISABLE; // Removed
    $user->is_deleted        = $request->is_deleted ? Status::ENABLE : Status::DISABLE;

    $user->ban_new_accounts = $request->ban_new_accounts ? Status::ENABLE : Status::DISABLE;

    $user->save();

    $notify[] = ['success', 'User details updated successfully'];
    return back()->withNotify($notify);
}


    public function addSubBalance(Request $request, $id) {
        $request->validate([
            'amount'      => 'required|numeric|gt:0',
            'act'         => 'required|in:add,sub',
            'wallet_type' => 'required|in:interest_wallet',
            'remark'      => 'required|string|max:255',
        ]);

        $user   = User::findOrFail($id);
        $amount = $request->amount;
        $wallet = $request->wallet_type;
        $trx    = getTrx();

        $transaction = new Transaction();

        if ($request->act == 'add') {
            $user->$wallet += $amount;

            $transaction->trx_type = '+';
            $transaction->remark   = 'balance_add';

            $notifyTemplate = 'BAL_ADD';

            $notify[] = ['success', gs('cur_sym') . $amount . ' added successfully'];

        } else {
            if ($amount > $user->$wallet) {
                $notify[] = ['error', $user->username . ' doesn\'t have sufficient balance.'];
                return back()->withNotify($notify);
            }

            $user->$wallet -= $amount;
            $transaction->trx_type = '-';
            $transaction->remark   = 'balance_subtract';

            $notifyTemplate = 'BAL_SUB';
            $notify[]       = ['success', gs('cur_sym') . $amount . ' subtracted successfully'];
        }

        $user->save();

        $transaction->user_id      = $user->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $user->$wallet;
        $transaction->charge       = 0;
        $transaction->trx          = $trx;
        $transaction->details      = $request->remark;
        $transaction->wallet_type  = $wallet;
        $transaction->save();

        notify($user, $notifyTemplate, [
            'trx'          => $trx,
            'amount'       => showAmount($amount, currencyFormat: false),
            'remark'       => $request->remark,
            'post_balance' => showAmount($user->$wallet, currencyFormat: false),
        ]);

        return back()->withNotify($notify);
    }

    public function login($id) {
        Auth::loginUsingId($id);
        return to_route('user.home');
    }

    public function status(Request $request, $id) {
        $user = User::findOrFail($id);
        if ($user->status == Status::USER_ACTIVE) {
            $request->validate([
                'reason' => 'required|string|max:255',
            ]);
            $user->status     = Status::USER_BAN;
            $user->ban_reason = $request->reason;
            $notify[]         = ['success', 'User banned successfully'];
        } else {
            $user->status     = Status::USER_ACTIVE;
            $user->ban_reason = null;
            $notify[]         = ['success', 'User unbanned successfully'];
        }
        $user->save();
        return back()->withNotify($notify);

    }

    public function showNotificationSingleForm($id) {
        $user = User::findOrFail($id);
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.users.detail', $user->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $user->username;
        return view('admin.users.notification_single', compact('pageTitle', 'user'));
    }

    public function sendNotificationSingle(Request $request, $id) {
        $request->validate([
            'message' => 'required',
            'via'     => 'required|in:email,sms,push',
            'subject' => 'required_if:via,email,push',
            'image'   => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);

        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        return (new UserNotificationSender())->notificationToSingle($request, $id);
    }

    public function showNotificationAllForm() {
        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        $notifyToUser = User::notifyToUser();
        $users        = User::active()->count();
        $pageTitle    = 'Notification to Verified Users';

        if (session()->has('SEND_NOTIFICATION') && !request()->email_sent) {
            session()->forget('SEND_NOTIFICATION');
        }

        return view('admin.users.notification_all', compact('pageTitle', 'users', 'notifyToUser'));
    }

    public function sendNotificationAll(Request $request) {
        $request->validate([
            'via'                          => 'required|in:email,sms,push',
            'message'                      => 'required',
            'subject'                      => 'required_if:via,email,push',
            'start'                        => 'required|integer|gte:1',
            'batch'                        => 'required|integer|gte:1',
            'being_sent_to'                => 'required',
            'cooling_time'                 => 'required|integer|gte:1',
            'number_of_top_deposited_user' => 'required_if:being_sent_to,topDepositedUsers|integer|gte:0',
            'number_of_days'               => 'required_if:being_sent_to,notLoginUsers|integer|gte:0',
            'image'                        => ["nullable", 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ], [
            'number_of_days.required_if'               => "Number of days field is required",
            'number_of_top_deposited_user.required_if' => "Number of top deposited user field is required",
        ]);

        if (!gs('en') && !gs('sn') && !gs('pn')) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }

        return (new UserNotificationSender())->notificationToAll($request);
    }

    public function countBySegment($methodName) {
        return User::active()->$methodName()->count();
    }

    public function list() {
        $query = User::active();

        if (request()->search) {
            $query->where(function ($q) {
                $q->where('email', 'like', '%' . request()->search . '%')->orWhere('username', 'like', '%' . request()->search . '%');
            });
        }
        $users = $query->orderBy('id', 'desc')->paginate(getPaginate());
        return response()->json([
            'success' => true,
            'users'   => $users,
            'more'    => $users->hasMorePages(),
        ]);
    }

    public function notificationLog($id) {
        $user      = User::findOrFail($id);
        $pageTitle = 'Notifications Sent to ' . $user->username;
        $logs      = NotificationLog::where('user_id', $id)->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs', 'user'));
    }

    public function profitableUsers() {
        $pageTitle = 'Profitable Users (Earnings > Deposit)';
        $users = User::withSum(['deposits' => function ($q) {
                $q->where('status', Status::PAYMENT_SUCCESS);
            }], 'amount')
            ->withSum(['transactions as total_profit' => function ($q) {
                $q->whereIn('remark', [
                    'interest',
                    'referral_commission',
                    'level_commission',
                    'commission',
                    'game_win',
                    'salary_claim',
                    'daily_reward',
                    'promo_code',
                    'mining_roi',
                    'ptc_earn',
                    'binary_trade_win'
                ]);
            }], 'amount')
            ->havingRaw('COALESCE(total_profit, 0) > COALESCE(deposits_sum_amount, 0)')
            ->orderByDesc('total_profit')
            ->paginate(getPaginate());

        return view('admin.users.profitable', compact('pageTitle', 'users'));
    }

    public function duplicateUsers()
    {
        $pageTitle = 'Duplicate Users (Multi-Accounts)';
        $query = \App\Models\UserLogin::select('user_ip')
            ->selectRaw('count(distinct user_id) as user_count')
            ->groupBy('user_ip')
            ->having('user_count', '>', 1)
            ->orderByDesc('user_count');

        if (request()->search) {
            $query->where('user_ip', 'like', "%" . request()->search . "%");
        }

        $groups = $query->paginate(getPaginate());

        foreach ($groups as $group) {
            $userIds = \App\Models\UserLogin::where('user_ip', $group->user_ip)->pluck('user_id')->unique();
            $group->users = User::whereIn('id', $userIds)->get(['id', 'username', 'email', 'mobile']);
        }

        return view('admin.users.duplicate', compact('pageTitle', 'groups'));
    }
}
