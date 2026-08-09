<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TradingSettingController extends Controller
{
    public function index()
    {
        $pageTitle = 'Binary Trading Settings';
        return view('admin.trading_setting.index', compact('pageTitle'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'profit'       => 'required|numeric|min:0|max:100',
            'min_amount'   => 'required|numeric|min:0',
            'max_amount'   => 'required|numeric|gt:min_amount',
            'daily_limit'  => 'required|numeric|min:0',
            'time_setting' => 'required|string', // comma separated like 1,3,5,15
        ]);

        $general = gs();
        $general->trading_setting = [
            'profit'       => $request->profit,
            'min_amount'   => $request->min_amount,
            'max_amount'   => $request->max_amount,
            'daily_limit'  => $request->daily_limit,
            'time_setting' => $request->time_setting,
        ];
        $general->save();

        $notify[] = ['success', 'Trading settings updated successfully'];
        return back()->withNotify($notify);
    }
}
