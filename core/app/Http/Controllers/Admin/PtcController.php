<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PtcAd;
use Illuminate\Http\Request;

class PtcController extends Controller
{
    public function index()
    {
        $pageTitle = 'PTC Ads Manager';
        $ads = PtcAd::orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.ptc.index', compact('pageTitle', 'ads'));
    }

    public function create()
    {
        $pageTitle = 'Create PTC Ad';
        return view('admin.ptc.create', compact('pageTitle'));
    }

    public function edit($id)
    {
        $pageTitle = 'Edit PTC Ad';
        $ad = PtcAd::findOrFail($id);
        return view('admin.ptc.edit', compact('pageTitle', 'ad'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'amount'   => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'max_show' => 'required|integer|min:1',
            'ad_type'  => 'required|in:1,2,3',
            'url'      => 'required_if:ad_type,1,2|nullable|string',
            'image'    => 'required_if:ad_type,3|nullable|image|mimes:jpg,jpeg,png',
        ]);

        $ad = new PtcAd();
        $ad->title    = $request->title;
        $ad->amount   = $request->amount;
        $ad->duration = $request->duration;
        $ad->max_show = $request->max_show;
        $ad->ad_type  = $request->ad_type;

        if ($request->ad_type == 1 || $request->ad_type == 2) {
            $ad->url = $request->url;
        } elseif ($request->ad_type == 3) {
            if ($request->hasFile('image')) {
                try {
                    $path = getFilePath('ptc');
                    $size = getFileSize('ptc');
                    $ad->image = fileUploader($request->image, $path, $size);
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Impossible to upload image'];
                    return back()->withNotify($notify);
                }
            }
        }

        $ad->save();
        $notify[] = ['success', 'PTC Ad created successfully'];
        return to_route('admin.ptc.index')->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'    => 'required|string|max:255',
            'amount'   => 'required|numeric|min:0',
            'duration' => 'required|integer|min:1',
            'max_show' => 'required|integer|min:1',
            'ad_type'  => 'required|in:1,2,3',
            'url'      => 'required_if:ad_type,1,2|nullable|string',
            'image'    => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        $ad = PtcAd::findOrFail($id);
        $ad->title    = $request->title;
        $ad->amount   = $request->amount;
        $ad->duration = $request->duration;
        $ad->max_show = $request->max_show;
        $ad->ad_type  = $request->ad_type;

        if ($request->ad_type == 1 || $request->ad_type == 2) {
            $ad->url = $request->url;
            $ad->image = null;
        } elseif ($request->ad_type == 3) {
            if ($request->hasFile('image')) {
                try {
                    $path = getFilePath('ptc');
                    $size = getFileSize('ptc');
                    $ad->image = fileUploader($request->image, $path, $size, $ad->image);
                } catch (\Exception $exp) {
                    $notify[] = ['error', 'Impossible to upload image'];
                    return back()->withNotify($notify);
                }
            }
            $ad->url = null;
        }

        $ad->save();
        $notify[] = ['success', 'PTC Ad updated successfully'];
        return to_route('admin.ptc.index')->withNotify($notify);
    }

    public function status($id)
    {
        $ad = PtcAd::findOrFail($id);
        $ad->status = $ad->status == 1 ? 0 : 1;
        $ad->save();

        $notify[] = ['success', 'PTC Ad status updated successfully'];
        return back()->withNotify($notify);
    }
}
