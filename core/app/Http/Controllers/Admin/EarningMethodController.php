<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GeneralSetting;
use Illuminate\Http\Request;

class EarningMethodController extends Controller
{
    public function index()
    {
        $pageTitle = 'Earning Methods Manager';
        return view('admin.earning_methods.index', compact('pageTitle'));
    }

    public function update(Request $request)
    {
        // ... (Existing bulk update logic if needed, or we can rely solely on status)
        // For buttons, we use the status method below.
        // We can keep this for bulk form if ever needed.
        $setting = GeneralSetting::first();
        $modules = [
            'games_module' => $request->has('games_module') ? 1 : 0,
            'trading_module' => $request->has('trading_module') ? 1 : 0,
            'ai_bot_module' => $request->has('ai_bot_module') ? 1 : 0,
            'mining_module' => $request->has('mining_module') ? 1 : 0,
            'investment_module' => $request->has('investment_module') ? 1 : 0,
            'forex_module' => $request->has('forex_module') ? 1 : 0,
            'ptc_module' => $request->has('ptc_module') ? 1 : 0,
        ];
        foreach($modules as $key => $value) {
            $setting->$key = $value;
        }
        $setting->save();
        $notify[] = ['success', 'Earning modules updated successfully'];
        return back()->withNotify($notify);
    }

    public function status($module)
    {
        $allowed = ['games_module', 'trading_module', 'ai_bot_module', 'mining_module', 'investment_module', 'forex_module', 'ptc_module', 'binary_module', 'stock_module'];
        if (!in_array($module, $allowed)) {
            $notify[] = ['error', 'Invalid module'];
            return back()->withNotify($notify);
        }

        $setting = GeneralSetting::first();
        $setting->$module = $setting->$module ? 0 : 1;
        $setting->save();

        $notify[] = ['success', 'Module status updated successfully'];
        return back()->withNotify($notify);
    }
}
