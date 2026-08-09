<?php

namespace App\Http\Controllers\Gateway\Dummy;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Models\Deposit;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    /*
     * Dummy Gateway
     */

    public static function process($deposit)
    {
        $send['view'] = 'user.payment.Dummy';
        $send['track'] = $deposit->trx;
        $send['amount'] = showAmount($deposit->final_amount, currencyFormat: false);
        $send['currency'] = $deposit->method_currency;
        $send['deposit'] = $deposit; // Passing the whole deposit object to show details
        
        return json_encode($send);
    }

    public function ipn(Request $request)
    {
        $deposit = Deposit::where('trx', $request->track)->where('status', Status::PAYMENT_INITIATE)->first();
        if ($deposit) {
            PaymentController::userDataUpdate($deposit);
            $notify[] = ['success', 'Dummy Payment Successful'];
            return to_route('user.deposit.history')->withNotify($notify);
        }
        $notify[] = ['error', 'Invalid Transaction'];
        return to_route('user.deposit.history')->withNotify($notify);
    }
}
