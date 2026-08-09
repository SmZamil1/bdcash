<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $pageTitle = 'Stocks Manager';
        $stocks = Stock::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.stock.index', compact('pageTitle', 'stocks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'symbol' => 'required|string|max:40',
            'image'  => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $stock = new Stock();
        $stock->name   = $request->name;
        $stock->symbol = strtoupper($request->symbol);

        if ($request->hasFile('image')) {
            try {
                $stock->image = fileUploader($request->image, getFilePath('stock'), getFileSize('stock'));
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible to upload image'];
                return back()->withNotify($notify);
            }
        }

        $stock->save();
        $notify[] = ['success', 'Stock added successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'symbol' => 'required|string|max:40',
            'image'  => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $stock = Stock::findOrFail($id);
        $stock->name   = $request->name;
        $stock->symbol = strtoupper($request->symbol);

        if ($request->hasFile('image')) {
            try {
                $stock->image = fileUploader($request->image, getFilePath('stock'), getFileSize('stock'), $stock->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Impossible to upload image'];
                return back()->withNotify($notify);
            }
        }

        $stock->save();
        $notify[] = ['success', 'Stock updated successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        return Stock::changeStatus($id);
    }

    public function setting()
    {
        $pageTitle = 'Stock Trading Settings';
        $setting = gs()->stock_setting;
        return view('admin.stock.setting', compact('pageTitle', 'setting'));
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
        $general->stock_setting = [
            'profit'       => $request->profit,
            'min_amount'   => $request->min_amount,
            'max_amount'   => $request->max_amount,
            'daily_limit'  => $request->daily_limit,
            'time_setting' => $request->time_setting,
        ];
        $general->save();

        $notify[] = ['success', 'Stock trading settings updated successfully'];
        return back()->withNotify($notify);
    }
}
