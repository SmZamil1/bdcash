<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForexPair;
use Illuminate\Http\Request;

class ForexController extends Controller
{
    public function index()
    {
        $pageTitle = 'Forex Pairs Manager';
        $pairs = ForexPair::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.forex.index', compact('pageTitle', 'pairs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'symbol' => 'required|string|max:40',
        ]);

        $pair = new ForexPair();
        $pair->name   = $request->name;
        $pair->symbol = strtoupper($request->symbol);
        $pair->save();

        $notify[] = ['success', 'Forex pair added successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'symbol' => 'required|string|max:40',
        ]);

        $pair = ForexPair::findOrFail($id);
        $pair->name   = $request->name;
        $pair->symbol = strtoupper($request->symbol);
        $pair->save();

        $notify[] = ['success', 'Forex pair updated successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return ForexPair::changeStatus($id);
    }

    public function setting()
    {
        $pageTitle = 'Forex Trading Settings';
        $setting = gs()->forex_setting;
        return view('admin.forex.setting', compact('pageTitle', 'setting'));
    }

    public function settingUpdate(Request $request)
    {
        $request->validate([
            'profit'       => 'required|numeric|min:0|max:100',
            'min_amount'   => 'required|numeric|min:0',
            'max_amount'   => 'required|numeric|gt:min_amount',
            'daily_limit'  => 'required|numeric|min:0',
            'time_setting' => 'required|string',
        ]);

        $general = gs();
        $general->forex_setting = [
            'profit'       => $request->profit,
            'min_amount'   => $request->min_amount,
            'max_amount'   => $request->max_amount,
            'daily_limit'  => $request->daily_limit,
            'time_setting' => $request->time_setting,
        ];
        $general->save();

        $notify[] = ['success', 'Forex trading settings updated successfully'];
        return back()->withNotify($notify);
    }
}
