<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VipPlan;
use Illuminate\Http\Request;

class VipPlanController extends Controller
{
    public function index()
    {
        $pageTitle = 'VIP Plans';
        $plans = VipPlan::orderBy('price', 'asc')->paginate(getPaginate());
        return view('admin.vip.index', compact('pageTitle', 'plans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|unique:vip_plans,name',
            'price'          => 'required|numeric|min:0',
            'daily_ad_limit' => 'required|integer|min:1',
            'validity'       => 'required|integer|min:1',
        ]);

        $plan = new VipPlan();
        $plan->name           = $request->name;
        $plan->price          = $request->price;
        $plan->daily_ad_limit = $request->daily_ad_limit;
        $plan->validity       = $request->validity;
        $plan->save();

        $notify[] = ['success', 'VIP Plan created successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'           => 'required|unique:vip_plans,name,' . $id,
            'price'          => 'required|numeric|min:0',
            'daily_ad_limit' => 'required|integer|min:1',
            'validity'       => 'required|integer|min:1',
        ]);

        $plan = VipPlan::findOrFail($id);
        $plan->name           = $request->name;
        $plan->price          = $request->price;
        $plan->daily_ad_limit = $request->daily_ad_limit;
        $plan->validity       = $request->validity;
        $plan->save();

        $notify[] = ['success', 'VIP Plan updated successfully'];
        return back()->withNotify($notify);
    }

    public function status($id)
    {
        $plan = VipPlan::findOrFail($id);
        $plan->status = $plan->status == 1 ? 0 : 1;
        $plan->save();

        $notify[] = ['success', 'Status changed successfully'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $plan = VipPlan::findOrFail($id);
        $plan->delete();

        $notify[] = ['success', 'VIP Plan deleted successfully'];
        return back()->withNotify($notify);
    }
}
