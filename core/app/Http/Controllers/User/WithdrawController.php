<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\FormProcessor;
use App\Lib\HyipLab;
use App\Models\AdminNotification;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WithdrawController extends Controller
{

public function withdrawMoney()
{
    $user = auth()->user();

    if ($user->withdraw_access == 1) {
$notify[] = ['error', 'You cannot withdraw money. Please contact the admin to fix this issue.'];
        return redirect()->route('user.home')->withNotify($notify);
    }

    // Individual Withdrawal Limit Check
    if ($user->withdrawal_limit > 0) {
        $totalWithdrawn = \App\Models\Withdrawal::where('user_id', $user->id)->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
        if ($totalWithdrawn >= $user->withdrawal_limit) {
            $notify[] = ['error', 'You have reached your total account withdrawal limit of ' . showAmount($user->withdrawal_limit) . '. No more withdrawals allowed.'];
            return redirect()->route('user.home')->withNotify($notify);
        }
    }

    if ($user->required_referrals > 0 && $user->referrals()->count() < $user->required_referrals) {
        $notify[] = ['error', 'You must have at least ' . $user->required_referrals . ' referrals to withdraw.'];
        return redirect()->route('user.home')->withNotify($notify);
    }

    $dayName        = strtolower(date('D'));
    $withdrawOffDay = (array) gs('withdraw_off_days');
    if (in_array($dayName, $withdrawOffDay)) {
        $notify[] = ['error', 'Withdrawals are disabled for today.'];
        return redirect()->route('user.home')->withNotify($notify);
    }

    $pageTitle      = 'Withdraw Money';
    $withdrawMethod = WithdrawMethod::active()->get();
    $isHoliday      = HyipLab::isHoliDay(now()->toDateTimeString(), gs());
    $nextWorkingDay = now()->toDateString();

    if ($isHoliday && !gs()->holiday_withdraw) {
        $nextWorkingDay = HyipLab::nextWorkingDay(24);
        $nextWorkingDay = Carbon::parse($nextWorkingDay)->toDateString();
    }

    return view('Template::user.withdraw.methods', compact(
        'pageTitle',
        'withdrawMethod',
        'isHoliday',
        'nextWorkingDay'
    ));
}



    public function withdrawStore(Request $request)
    {
        $dayName        = strtolower(date('D'));
        $withdrawOffDay = (array) gs('withdraw_off_days');
        if (in_array($dayName, $withdrawOffDay)) {
            $notify[] = ['error', 'Withdrawals are disabled for today.'];
            return back()->withNotify($notify);
        }

        $isHoliday = \App\Lib\HyipLab::isHoliDay(now()->toDateTimeString(), gs());
        if ($isHoliday && !gs()->holiday_withdraw) {
            $notify[] = ['error', 'Today is holiday. You\'re unable to withdraw today'];
            return back()->withNotify($notify);
        }

        $request->validate([
            'method_code' => 'required',
            'amount'      => 'required|numeric',
        ]);
        $method = WithdrawMethod::where('id', $request->method_code)->active()->firstOrFail();
        $user   = auth()->user();
        if ($request->amount < $method->min_limit) {
            $notify[] = ['error', 'Your requested amount is smaller than minimum amount'];
            return back()->withNotify($notify)->withInput($request->all());
        }
        if ($request->amount > $method->max_limit) {
            $notify[] = ['error', 'Your requested amount is larger than maximum amount'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        if ($request->amount > $user->interest_wallet) {
            $notify[] = ['error', 'Insufficient balance for withdrawal'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        if ($user->withdrawal_limit > 0) {
            $totalWithdrawn = Withdrawal::where('user_id', $user->id)->where('status', Status::PAYMENT_SUCCESS)->sum('amount');
            if (($totalWithdrawn + $request->amount) > $user->withdrawal_limit) {
                $notify[] = ['error', 'This withdrawal would exceed your account limit. Remaining allowanced: ' . showAmount($user->withdrawal_limit - $totalWithdrawn)];
                return back()->withNotify($notify)->withInput($request->all());
            }
        }

        $charge      = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;

        if ($afterCharge <= 0) {
            $notify[] = ['error', 'Withdraw amount must be sufficient for charges'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        $finalAmount = $afterCharge * $method->rate;

        $withdraw               = new Withdrawal();
        $withdraw->method_id    = $method->id; // wallet method ID
        $withdraw->user_id      = $user->id;
        $withdraw->amount       = $request->amount;
        $withdraw->currency     = $method->currency;
        $withdraw->rate         = $method->rate;
        $withdraw->charge       = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;
        $withdraw->trx          = getTrx();
        $withdraw->save();
        session()->put('wtrx', $withdraw->trx);
        return to_route('user.withdraw.preview');
    }

    public function withdrawPreview()
    {
        $withdraw  = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'desc')->firstOrFail();
        $pageTitle = 'Withdraw Preview';
        return view('Template::user.withdraw.preview', compact('pageTitle', 'withdraw'));
    }

    public function withdrawSubmit(Request $request)
    {
        $withdraw = Withdrawal::with('method', 'user')->where('trx', session()->get('wtrx'))->where('status', Status::PAYMENT_INITIATE)->orderBy('id', 'desc')->firstOrFail();

        $method = $withdraw->method;
        if ($method->status == Status::DISABLE) {
            abort(404);
        }

        $formData = @$method->form->form_data ?? [];

        $formProcessor  = new FormProcessor();
        $validationRule = $formProcessor->valueValidation($formData);
        $request->validate($validationRule);
        $userData = $formProcessor->processFormData($request, $formData);

        $user = auth()->user();
        if ($user->ts) {
            $response = verifyG2fa($user, $request->authenticator_code);
            if (!$response) {
                $notify[] = ['error', 'Wrong verification code'];
                return back()->withNotify($notify)->withInput($request->all());
            }
        }

        if ($withdraw->amount > $user->interest_wallet) {
            $notify[] = ['error', 'Your request amount is larger then your current balance.'];
            return back()->withNotify($notify)->withInput($request->all());
        }





        if ($request->amount < $method->min_limit) {
            $notify[] = ['error', 'Your requested amount is smaller than minimum amount'];
            return back()->withNotify($notify)->withInput($request->all());
        }
        if ($request->amount > $method->max_limit) {
            $notify[] = ['error', 'Your requested amount is larger than maximum amount'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        if ($request->amount > $user->interest_wallet) {
            $notify[] = ['error', 'Insufficient balance for withdrawal'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        $charge      = $method->fixed_charge + ($request->amount * $method->percent_charge / 100);
        $afterCharge = $request->amount - $charge;

        if ($afterCharge <= 0) {
            $notify[] = ['error', 'Withdraw amount must be sufficient for charges'];
            return back()->withNotify($notify)->withInput($request->all());
        }

        $finalAmount = $afterCharge * $method->rate;

        $withdraw->amount       = $request->amount;
        $withdraw->charge       = $charge;
        $withdraw->final_amount = $finalAmount;
        $withdraw->after_charge = $afterCharge;

        $withdraw->status               = Status::PAYMENT_PENDING;
        $withdraw->withdraw_information = $userData;
        $withdraw->save();
        $user->interest_wallet -= $withdraw->amount;
        $user->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $withdraw->user_id;
        $transaction->amount       = $withdraw->amount;
        $transaction->post_balance = $user->interest_wallet;
        $transaction->charge       = $withdraw->charge;
        $transaction->trx_type     = '-';
        $transaction->details      = 'Withdraw request via ' . $withdraw->method->name;
        $transaction->trx          = $withdraw->trx;
        $transaction->remark       = 'withdraw';
        $transaction->save();

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = 'New withdraw request from ' . $user->username;
        $adminNotification->click_url = urlPath('admin.withdraw.data.details', $withdraw->id);
        $adminNotification->save();

        notify($user, 'WITHDRAW_REQUEST', [
            'method_name'     => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount'   => showAmount($withdraw->final_amount, currencyFormat: false),
            'amount'          => showAmount($withdraw->amount, currencyFormat: false),
            'charge'          => showAmount($withdraw->charge, currencyFormat: false),
            'rate'            => showAmount($withdraw->rate, currencyFormat: false),
            'trx'             => $withdraw->trx,
            'post_balance'    => showAmount($user->interest_wallet, currencyFormat: false),
        ]);

        $notify[] = ['success', 'Withdraw request sent successfully'];
        return to_route('user.withdraw.history')->withNotify($notify);
    }

    public function withdrawLog(Request $request)
    {
        $pageTitle = "Withdrawal Log";
        $withdraws = Withdrawal::where('user_id', auth()->id())->where('status', '!=', Status::PAYMENT_INITIATE);
        if ($request->search) {
            $withdraws = $withdraws->where('trx', $request->search);
        }
        $withdraws = $withdraws->with('method')->orderBy('id', 'desc')->paginate(getPaginate());
        $data['pageTitle'] = $pageTitle;
        $data['withdraws'] = $withdraws;
        return view('Template::user.withdraw.log', $data);
    }
}
