<?php

namespace App\Lib;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Invest;
use App\Models\Holiday;
use App\Models\Referral;
use App\Constants\Status;
use App\Models\Transaction;
use App\Models\ScheduleInvest;
use App\Models\AdminNotification;

class HyipLab
{
    /**
     * Instance of investor user
     *
     * @var object
     */
    private $user;

    /**
     * Plan which is purchasing
     *
     * @var object
     */
    private $plan;

    /**
     * General setting
     *
     * @var object
     */
    private $setting;

    /**
     * Set some properties
     *
     * @param object $user
     * @param object $plan
     * @return void
     */
    public function __construct($user, $plan)
    {
        $this->user    = $user;
        $this->plan    = $plan;
        $this->setting = gs();
    }

    /**
     * Invest process
     *
     * @param float $amount
     * @param string $wallet
     * @return void
     */
    public function invest($amount, $wallet, $compoundTimes = 0, $debitBalance = true)
    {
        $plan = $this->plan;
        $user = $this->user;

        if ($debitBalance) {
            $user->$wallet -= $amount;
            $user->total_invests += $amount;
            $user->save();
        }

        $trx                       = getTrx();
        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $amount;
        $transaction->post_balance = $debitBalance ? $user->$wallet : $user->interest_wallet;
        $transaction->charge       = 0;
        $transaction->trx_type     = $debitBalance ? '-' : '+';
        $transaction->details      = ($debitBalance ? 'Invested on ' : 'Plan activated by Admin: ') . $plan->name;
        $transaction->trx          = $trx;
        $transaction->wallet_type  = $wallet;
        $transaction->remark       = 'invest';
        $transaction->save();

        //start
        if ($plan->interest_type == 1) {
            $interestAmount = ($amount * $plan->interest) / 100;
        } else {
            $interestAmount = $plan->interest;
        }

        $period = ($plan->lifetime == 1) ? -1 : $plan->repeat_time;

        $next = self::nextWorkingDay($plan->timeSetting->time);

        $shouldPay = -1;
        if ($period > 0) {
            $shouldPay = $interestAmount * $period;
        }

        $invest                     = new Invest();
        $invest->user_id            = $user->id;
        $invest->plan_id            = $plan->id;
        $invest->amount             = $amount;
        $invest->initial_amount     = $amount;
        $invest->interest           = $interestAmount;
        $invest->initial_interest   = $interestAmount;
        $invest->period             = $period;
        $invest->time_name          = $plan->timeSetting->name;
        $invest->hours              = $plan->timeSetting->time;
        $invest->next_time          = $next;
        $invest->should_pay         = $shouldPay;
        $invest->status             = 1;
        $invest->wallet_type        = $wallet;
        $invest->capital_status     = $plan->capital_back;
        $invest->trx                = $trx;
        $invest->compound_times     = $compoundTimes ?? 0;
        $invest->rem_compound_times = $compoundTimes ?? 0;
        $invest->hold_capital       = $plan->hold_capital;
        $invest->save();

        if ($this->setting->invest_commission == 1) {
            $commissionType = 'invest_commission';
            self::levelCommission($user, $amount, $commissionType, $trx, $this->setting);
        }

        notify($user, 'INVESTMENT', [
            'trx'             => $invest->trx,
            'amount'          => showAmount($amount, currencyFormat:false),
            'plan_name'       => $plan->name,
            'interest_amount' => showAmount($interestAmount, currencyFormat:false),
            'time'            => $plan->lifetime == Status::YES ? 'lifetime' : $plan->repeat_time . ' times',
            'time_name'       => $plan->timeSetting->name,
            'wallet_type'     => keyToTitle($wallet),
            'post_balance'    => showAmount($user->$wallet, currencyFormat:false),
        ]);

        $adminNotification            = new AdminNotification();
        $adminNotification->user_id   = $user->id;
        $adminNotification->title     = showAmount($amount, currencyFormat:false) . ' invested to ' . $plan->name;
        $adminNotification->click_url = '#';
        $adminNotification->save();

        // --- Immediate First Profit Logic ---
        $invest->return_rec_time += 1;
        $invest->paid += $interestAmount;
        $invest->should_pay -= ($period > 0 ? $interestAmount : 0);
        
        $interestTrx = getTrx();

        // Handle Compounding vs Direct Payout
        if ($invest->rem_compound_times) {
            $newInvestAmount = $invest->amount + $interestAmount;
            $newInterest     = $invest->interest * $newInvestAmount / $invest->amount;
            $newShouldPay    = $invest->should_pay == -1 ? -1 : ($invest->period - $invest->return_rec_time) * $newInterest;

            $invest->amount     = $newInvestAmount;
            $invest->interest   = $newInterest;
            $invest->should_pay = $newShouldPay;
            $invest->rem_compound_times -= 1;
            // Net interest to wallet is 0, so we don't add to net_interest for display usually, or we do? Cron uses "net_interest" for "earnings"?
            // Cron: $invest->net_interest += $invest->rem_compound_times ? 0 : $invest->interest;
            // Since we just decremented rem_compound_times, if it's NOW 0, we add?
            // Cron checks property *before* decrementing? 
            // Cron Line 102: $invest->net_interest += $invest->rem_compound_times ? 0 : $invest->interest; (Pre-decrement check)
            // Here we check $invest->rem_compound_times (current) before decrement.
            // So if it is > 0, we add 0. Correct.
            
            // Transaction: Interest Compound (Debit from interest wallet - strictly bookkeeping here since we didn't credit it)
            // But to make sense in ledger: Credit Interest -> Debit Invest.
            // Cron does: Credit Interest -> Debit Invest.
            // So we must credit interest first even if temporary.
            
            // We use $this->user to ensure we have the correct user object, as $user local var might be modified later or here.
            // Actually $user is safe here before the loop.
            
            // 1. Credit (Virtual)
            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->invest_id    = $invest->id;
            $transaction->amount       = $interestAmount;
            $transaction->charge       = 0;
            $transaction->post_balance = $user->interest_wallet + $interestAmount;
            $transaction->trx_type     = '+';
            $transaction->trx          = $interestTrx;
            $transaction->remark       = 'interest';
            $transaction->wallet_type  = 'interest_wallet';
            $transaction->details      = showAmount($interestAmount) . ' interest from ' . $plan->name;
            $transaction->save();
            
            // 2. Debit (Reinvest)
            $transaction               = new Transaction();
            $transaction->user_id      = $user->id;
            $transaction->invest_id    = $invest->id;
            $transaction->amount       = $interestAmount;
            $transaction->post_balance = $user->interest_wallet; // Back to same
            $transaction->charge       = 0;
            $transaction->trx_type     = '-';
            $transaction->details      = 'Invested Compound on ' . $plan->name;
            $transaction->trx          = getTrx(); // New TRX for debit
            $transaction->wallet_type  = 'interest_wallet';
            $transaction->remark       = 'invest_compound';
            $transaction->save();

        } else {
             // Normal Payout
             $user->interest_wallet += $interestAmount;
             $user->save();
             
             $invest->net_interest += $interestAmount;
             
             $transaction               = new Transaction();
             $transaction->user_id      = $user->id;
             $transaction->invest_id    = $invest->id;
             $transaction->amount       = $interestAmount;
             $transaction->charge       = 0;
             $transaction->post_balance = $user->interest_wallet;
             $transaction->trx_type     = '+';
             $transaction->trx          = $interestTrx;
             $transaction->remark       = 'interest';
             $transaction->wallet_type  = 'interest_wallet';
             $transaction->details      = showAmount($interestAmount) . ' interest from ' . $plan->name;
             $transaction->save();
        }

        // Referral Commission on Interest
        if ($this->setting->invest_return_commission == 1) {
            self::levelCommission($user, $interestAmount, 'invest_return_commission', $interestTrx, $this->setting);
        }

        // Check Completion
        if ($invest->return_rec_time >= $invest->period && $invest->period != -1) {
            $invest->status = 0;
            if ($invest->capital_status == 1 && !$invest->hold_capital) {
                 self::capitalReturn($invest);
            }
        }
        
        $invest->save();
        
        notify($user, 'INTEREST', [
            'trx'             => $interestTrx,
            'amount'          => showAmount($interestAmount, currencyFormat:false),
            'plan_name'       => $plan->name,
            'post_balance'    => showAmount($user->interest_wallet, currencyFormat:false),
        ]);
        // --- End Immediate Profit ---

        while ($user->ref_by) {
            $user = User::find($user->ref_by);
            $user->team_invests += $amount;
            $user->save();
        }
    }

    public static function saveScheduleInvest($request)
    {
        $scheduleInvest                     = new ScheduleInvest();
        $scheduleInvest->user_id            = auth()->id();
        $scheduleInvest->plan_id            = $request->plan_id;
        $scheduleInvest->wallet             = $request->wallet_type;
        $scheduleInvest->amount             = $request->amount;
        $scheduleInvest->schedule_times     = $request->schedule_times;
        $scheduleInvest->rem_schedule_times = $request->schedule_times;
        $scheduleInvest->interval_hours     = $request->hours;
        $scheduleInvest->compound_times     = $request->compound_interest ?? 0;
        $scheduleInvest->next_invest        = now()->addHours((int) $request->hours);
        $scheduleInvest->save();
    }

    /**
     * Get the next working day of the system
     *
     * @param integer $hours
     * @return string
     */
    public static function nextWorkingDay($hours)
    {
        $now     = now();
        $setting = gs();
        $hours = (int) $hours;
        while (0 == 0) {
            $nextPossible = Carbon::parse($now)->addHours($hours)->toDateTimeString();

            if (!self::isHoliDay($nextPossible, $setting)) {
                $next = $nextPossible;
                break;
            }
            $now = $now->addDay();
        }
        return $next;
    }

    /**
     * Check the date is holiday or not
     *
     * @param string $date
     * @param object $setting
     * @return string
     */
    public static function isHoliDay($date, $setting)
    {
        $isHoliday = true;
        $dayName   = strtolower(date('D', strtotime($date)));
        $holiday   = Holiday::where('date', date('Y-m-d', strtotime($date)))->count();
        $offDay    = (array) $setting->off_day;

        if (!array_key_exists($dayName, $offDay)) {
            if ($holiday == 0) {
                $isHoliday = false;
            }
        }

        return $isHoliday;

    }

    /**
     * Give referral commission
     *
     * @param object $user
     * @param float $amount
     * @param string $commissionType
     * @param string $trx
     * @param object $setting
     * @return void
     */
    public static function levelCommission($user, $amount, $commissionType, $trx, $setting)
    {
        $meUser       = $user;
        $i            = 1;
        $level        = Referral::where('commission_type', $commissionType)->count();
        $transactions = [];
        while ($i <= $level) {
            $me    = $meUser;
            $refer = $me->referrer;
            if ($refer == "") {
                break;
            }

            $commission = Referral::where('commission_type', $commissionType)->where('level', $i)->first();
            if (!$commission) {
                break;
            }

            $com = ($amount * $commission->percent) / 100;
            $refer->interest_wallet += $com;
            $refer->save();

            $transactions[] = [
                'user_id'      => $refer->id,
                'amount'       => $com,
                'post_balance' => $refer->interest_wallet,
                'charge'       => 0,
                'trx_type'     => '+',
                'details'      => 'level ' . $i . ' Referral Commission From ' . $user->username,
                'trx'          => $trx,
                'wallet_type'  => 'interest_wallet',
                'remark'       => 'referral_commission',
                'created_at'   => now(),
            ];

            if ($commissionType == 'deposit_commission') {
                $comType = 'Deposit';
            } elseif ($commissionType == 'interest_commission') {
                $comType = 'Interest';
            } else {
                $comType = 'Invest';
            }

            notify($refer, 'REFERRAL_COMMISSION', [
                'amount'       => showAmount($com, currencyFormat:false),
                'post_balance' => showAmount($refer->interest_wallet, currencyFormat:false),
                'trx'          => $trx,
                'level'        => ordinal($i),
                'type'         => $comType,
            ]);

            $meUser = $refer;
            $i++;
        }

        if (!empty($transactions)) {
            Transaction::insert($transactions);
        }
    }

    /**
     * Capital return
     *
     * @param object $invest
     * @param object $user
     * @return void
     */

    public static function capitalReturn($invest, $wallet = 'interest_wallet')
    {
        $user = $invest->user;
        $user->$wallet += $invest->amount;
        $user->save();

        $invest->capital_back = 1;
        $invest->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $user->id;
        $transaction->amount       = $invest->amount;
        $transaction->charge       = 0;
        $transaction->post_balance = $user->$wallet;
        $transaction->trx_type     = '+';
        $transaction->trx          = getTrx();
        $transaction->wallet_type  = $wallet;
        $transaction->remark       = 'capital_return';
        $transaction->details      = showAmount($invest->amount) . ' ' . gs()->cur_text . ' capital back from ' . @$invest->plan->name;
        $transaction->save();
    }
}
