<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MiningPlan;
use Illuminate\Http\Request;

class MiningPlanController extends Controller
{
    public function index()
    {
        $pageTitle = "Cloud Mining Plans";
        $plans = MiningPlan::orderBy('id', 'desc')->paginate(getPaginate());
        $timeSettings = \App\Models\TimeSetting::where('status', 1)->get();
        return view('admin.mining.index', compact('pageTitle', 'plans', 'timeSettings'));
    }

    public function store(Request $request)
    {
        $this->validation($request);

        $plan = new MiningPlan();
        $this->saveData($plan, $request);

        $notify[] = ['success', 'Mining Plan added successfully'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $this->validation($request, $id);
        $plan = MiningPlan::findOrFail($id);
        $this->saveData($plan, $request);

        $notify[] = ['success', 'Mining Plan updated successfully'];
        return back()->withNotify($notify);
    }

    protected function saveData($plan, $request)
    {
        $plan->name = $request->name;
        $plan->coin_code = $request->coin_code;
        $plan->tag = $request->tag;
        $plan->price = $request->price;
        $plan->min_return = $request->min_return;
        $plan->time_setting_id = $request->time_setting_id;
        $plan->duration = $request->duration;

        if ($request->hasFile('icon')) {
            try {
                $path = 'assets/images/mining';
                $plan->icon = fileUploader($request->icon, $path, null, $plan->icon);
            }
            catch (\Exception $e) {
            // Handle upload error if necessary, or let global handler catch it
            }
        }

        $plan->status = $request->status ? 1 : 0;
        $plan->save();
    }

    protected function validation($request, $id = 0)
    {
        $rules = [
            'name' => 'required',
            'coin_code' => 'required',
            'tag' => 'required',
            'price' => 'required|numeric|gt:0',
            'min_return' => 'required|numeric|gt:0',
            'time_setting_id' => 'required|exists:time_settings,id',
            'duration' => 'required|integer|min:1',
        ];

        if ($id) {
            $rules['icon'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg';
        }
        else {
            $rules['icon'] = 'required|image|mimes:jpeg,png,jpg,gif,svg';
        }

        $request->validate($rules);
    }

    public function status($id)
    {
        $plan = MiningPlan::findOrFail($id);
        $plan->status = $plan->status == 1 ? 0 : 1;
        $plan->save();

        $notify[] = ['success', 'Status changed successfully'];
        return back()->withNotify($notify);
    }

    public function delete($id)
    {
        $plan = MiningPlan::findOrFail($id);
        if ($plan->icon) {
            $path = 'assets/images/mining';
            fileManager()->removeFile($path . '/' . $plan->icon);
        }
        $plan->delete();

        $notify[] = ['success', 'Mining Plan deleted successfully'];
        return back()->withNotify($notify);
    }
}
