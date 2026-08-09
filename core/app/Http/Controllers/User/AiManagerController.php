<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserAiManager;
use App\Models\Transaction;
use Illuminate\Http\Request;

class AiManagerController extends Controller
{
    public function index()
    {
        $pageTitle = 'AI Auto-Withdraw Manager';
        $user = auth()->user();
        
        // Ensure manager record exists
        $manager = UserAiManager::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );
        
        $withdrawMethods = \App\Models\WithdrawMethod::where('status', 1)->with('form')->get();
        
        // Check for Auto-Withdraw on Load
        $this->checkAutoWithdraw($manager);

        return view('Template::user.ai_manager.index', compact('pageTitle', 'manager', 'withdrawMethods'));
    }

    public function checkAutoWithdraw($manager) {
        if (!$manager->auto_withdraw || !$manager->next_withdraw_at || $manager->withdraw_amount <= 0 || !$manager->withdraw_method_id) {
            return;
        }

        if (now()->greaterThanOrEqualTo($manager->next_withdraw_at)) {
            
            // Determine user
            $user = $manager->user;
            if(!$user) return; 

            // Check User Interest Wallet Balance (instead of Manager Balance)
            if ($user->interest_wallet >= $manager->withdraw_amount) {
                
                // Create Withdrawal Request
                $method = \App\Models\WithdrawMethod::where('id', $manager->withdraw_method_id)->where('status', 1)->with('form')->first();
                if (!$method) return; // Method disabled or deleted

                // Format Withdraw Details for Admin
                $withdrawInformation = [];
                if ($method->form && $method->form->form_data) {
                    $formData = $method->form->form_data;
                    $savedDetails = json_decode($manager->withdraw_details, true) ?? [];
                    
                    foreach ($formData as $fieldName => $fieldData) {
                         $fieldData = (object)$fieldData;
                         $withdrawInformation[] = [
                             'name' => $fieldData->name ?? $fieldData->label ?? $fieldName,
                             'type' => $fieldData->type ?? 'text',
                             'value' => $savedDetails[$fieldName] ?? '',
                         ];
                    }
                } else {
                    // Fallback if no form data (shouldn't happen for active methods usually)
                    $withdrawInformation = json_decode($manager->withdraw_details, true);
                }

                $charge = $method->fixed_charge + ($manager->withdraw_amount * $method->percent_charge / 100);
                $finalAmount = $manager->withdraw_amount - $charge;

                // Create Withdrawal
                $withdrawal = new \App\Models\Withdrawal();
                $withdrawal->method_id = $method->id;
                $withdrawal->user_id = $user->id;
                $withdrawal->amount = $manager->withdraw_amount;
                $withdrawal->currency = $method->currency;
                $withdrawal->rate = $method->rate;
                $withdrawal->charge = $charge;
                $withdrawal->final_amount = $finalAmount;
                $withdrawal->after_charge = $finalAmount;
                $withdrawal->trx = getTrx();
                $withdrawal->withdraw_information = $withdrawInformation; // Assign array directly, model casts to object/json
                $withdrawal->status = 2; // Pending
                $withdrawal->admin_feedback = 'Auto-Withdraw via AI Manager';
                $withdrawal->save();

                // Deduct from User Interest Wallet
                $user->interest_wallet -= $manager->withdraw_amount;
                $user->save();
                
                // Update Next Time
                $this->setNextWithdrawTime($manager);

                // Save Manager
                $manager->save();
                
                // Transaction Log
                $transaction = new Transaction();
                $transaction->user_id = $user->id;
                $transaction->amount = $manager->withdraw_amount;
                $transaction->post_balance = $user->interest_wallet;
                $transaction->charge = 0;
                $transaction->trx_type = '-';
                $transaction->details = 'Auto-Withdrawal Request Created via AI Manager';
                $transaction->trx = $withdrawal->trx;
                $transaction->remark = 'ai_auto_withdraw_request';
                $transaction->wallet_type = 'interest_wallet'; 
                $transaction->save();
                
                // Notify Admin
                notify($user, 'WITHDRAW_REQUEST', [
                    'method_name' => $method->name,
                    'method_currency' => $method->currency,
                    'method_amount' => showAmount($withdrawal->final_amount),
                    'amount' => showAmount($withdrawal->amount),
                    'charge' => showAmount($withdrawal->charge),
                    'rate' => showAmount($withdrawal->rate),
                    'trx' => $withdrawal->trx,
                    'post_balance' => showAmount($user->interest_wallet),
                ]);
            }
        }
    }
    
    private function setNextWithdrawTime($manager) {
        $now = now();
        if ($manager->withdraw_frequency == 'daily') {
            $manager->next_withdraw_at = $now->addDay();
        } elseif ($manager->withdraw_frequency == 'weekly') {
            $manager->next_withdraw_at = $now->addWeek();
        } elseif ($manager->withdraw_frequency == 'monthly') {
            $manager->next_withdraw_at = $now->addMonth();
        }
    }


    public function update(Request $request)
    {
        if ($request->action == 'update_settings') {
            
            $request->validate([
                'withdraw_method_id' => 'required_with:auto_withdraw|exists:withdraw_methods,id',
                'withdraw_details' => 'required_with:auto_withdraw',
            ]);

            $manager = UserAiManager::where('user_id', auth()->id())->firstOrFail();

            $newAutoWithdrawState = $request->has('auto_withdraw');
            $manager->auto_withdraw = $newAutoWithdrawState ? 1 : 0;
            $manager->withdraw_amount = $request->withdraw_amount ?? 0;
            
            // Auto Withdraw Details
            // IMPORTANT: withdraw_details passed as array from form, need to json_encode or format it
            if($request->has('withdraw_method_id')) {
                $manager->withdraw_method_id = $request->withdraw_method_id;
            }
            if($request->has('withdraw_details')) {
                 $inputDetails = $request->withdraw_details;
                 // Ensure we store strictly as JSON, even if single string (wrap in object if needed? No, dynamic fields implies array)
                 // If array, encode. If simple string, maybe user is using API? We assume array from web form.
                 $manager->withdraw_details = is_array($inputDetails) ? json_encode($inputDetails) : json_encode(['details' => $inputDetails]);
            }

            // Reset timer if frequency changes or turned on first time
            if (($manager->withdraw_frequency != $request->withdraw_frequency) || ($newAutoWithdrawState && !$manager->next_withdraw_at)) {
                $manager->withdraw_frequency = $request->withdraw_frequency ?? 'daily';
                $this->setNextWithdrawTime($manager);
            } elseif (!$newAutoWithdrawState) {
                $manager->next_withdraw_at = null;
            }

            $manager->save();

            $notify[] = ['success', 'Auto-Withdraw Settings updated successfully'];
        }

        return back()->withNotify($notify);
    }
}
