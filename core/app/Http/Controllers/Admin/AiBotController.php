<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiBot;
use App\Models\TimeSetting;
use Illuminate\Http\Request;

class AiBotController extends Controller
{
    public function index()
    {
        $pageTitle = 'AI Bots';
        $bots = AiBot::orderBy('price', 'asc')->paginate(getPaginate());
        $timeSettings = TimeSetting::where('status', 1)->get();
        $botCount = AiBot::count();
        return view('admin.ai_bots.index', compact('pageTitle', 'bots', 'timeSettings', 'botCount'));
    }

    public function store(Request $request)
    {
        if (AiBot::count() >= 1) {
            $notify[] = ['error', 'You can only add one AI Bot plan.'];
            return back()->withNotify($notify);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_invest' => 'required|numeric|gte:price',
            'daily_profit' => 'required|numeric|min:0',
            'daily_trade_limit' => 'required|integer|min:0',
            'time_setting_id' => 'required|exists:time_settings,id',
            'duration' => 'required|integer|min:1',
        ]);

        $bot = new AiBot();
        $bot->name = $request->name;
        $bot->price = $request->price;
        $bot->max_invest = $request->max_invest;
        $bot->duration = $request->duration;
        $bot->time_setting_id = $request->time_setting_id;
        $bot->profit_type = 2;
        $bot->daily_profit = $request->daily_profit;
        $bot->daily_trade_limit = $request->daily_trade_limit;

        // sync old columns to avoid db errors if not nullable
        $bot->min_profit = $request->daily_profit;
        $bot->max_profit = $request->daily_profit;

        $bot->status = 1;
        $bot->save();

        $notify[] = ['success', 'AI Bot created successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'max_invest' => 'required|numeric|gte:price',
            'daily_profit' => 'required|numeric|min:0',
            'daily_trade_limit' => 'required|integer|min:0',
            'time_setting_id' => 'required|exists:time_settings,id',
            'duration' => 'required|integer|min:1',
        ]);

        $bot = AiBot::findOrFail($id);
        $bot->name = $request->name;
        $bot->price = $request->price;
        $bot->max_invest = $request->max_invest;
        $bot->duration = $request->duration;
        $bot->time_setting_id = $request->time_setting_id;
        $bot->profit_type = 2;
        $bot->daily_profit = $request->daily_profit;
        $bot->daily_trade_limit = $request->daily_trade_limit;

        // sync old columns
        $bot->min_profit = $request->daily_profit;
        $bot->max_profit = $request->daily_profit;

        $bot->save();

        $notify[] = ['success', 'AI Bot updated successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return AiBot::changeStatus($id);
    }

    public function delete($id)
    {
        $bot = AiBot::findOrFail($id);
        $bot->delete();

        $notify[] = ['success', 'AI Bot deleted successfully'];
        return back()->withNotify($notify);
    }
}
