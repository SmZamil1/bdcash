<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyReward;
use Illuminate\Http\Request;

class DailyRewardController extends Controller
{
    public function index()
    {
        $pageTitle = 'Daily Login Rewards';
        
        // Seed if empty (One time setup helper)
        if (DailyReward::count() == 0) {
            $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            foreach ($days as $day) {
                DailyReward::create(['day' => $day, 'amount' => 0]);
            }
        }

        $rewards = DailyReward::all(); // Should check ordering? Maybe ID is fine as it respects insertions
        return view('admin.daily_reward.index', compact('pageTitle', 'rewards'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'status' => 'required|boolean',
        ]);

        $reward = DailyReward::findOrFail($id);
        $reward->amount = $request->amount;
        $reward->status = $request->status;
        $reward->save();

        $notify[] = ['success', $reward->day . ' reward updated successfully'];
        return back()->withNotify($notify);
    }
}
