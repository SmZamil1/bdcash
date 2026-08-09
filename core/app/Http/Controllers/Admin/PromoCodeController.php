<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class PromoCodeController extends Controller
{
    public function index()
    {
        $pageTitle = 'Promo Codes';
        $promocodes = PromoCode::latest()->paginate(getPaginate());
        $logs = \App\Models\PromoCodeLog::with(['user', 'promocode'])->latest()->paginate(getPaginate(20), ['*'], 'log_page');
        $emptyMessage = 'No logs found';
        return view('admin.promocode.index', compact('pageTitle', 'promocodes', 'logs', 'emptyMessage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'wallet_type' => 'required|in:interest_wallet',
            'usage_limit' => 'required|integer|min:0',
            'frequency' => 'required|in:one_time,daily,weekly,monthly',
        ]);

        $code = $request->code ?: strtoupper(str()->random(8));

        $promo = new PromoCode();
        $promo->code = $code;
        $promo->amount = $request->amount;
        $promo->wallet_type = $request->wallet_type;
        $promo->usage_limit = $request->usage_limit;
        $promo->frequency = $request->frequency;
        $promo->status = 1;
        $promo->save();

        $notify[] = ['success', 'Promo code created successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'wallet_type' => 'required|in:interest_wallet',
            'usage_limit' => 'required|integer|min:0',
            'frequency' => 'required|in:one_time,daily,weekly,monthly',
        ]);

        $promo = PromoCode::findOrFail($id);
        $promo->amount = $request->amount;
        $promo->wallet_type = $request->wallet_type;
        $promo->usage_limit = $request->usage_limit;
        $promo->frequency = $request->frequency;
        $promo->save();

        $notify[] = ['success', 'Promo code updated successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        $promo = PromoCode::findOrFail($id);
        $promo->status = !$promo->status;
        $promo->save();

        $notify[] = ['success', 'Promo code status changed successfully'];
        return back()->withNotify($notify);
    }

    public function remove($id)
    {
        $promo = PromoCode::findOrFail($id);
        $promo->delete();

        $notify[] = ['success', 'Promo code deleted successfully'];
        return back()->withNotify($notify);
    }
}
