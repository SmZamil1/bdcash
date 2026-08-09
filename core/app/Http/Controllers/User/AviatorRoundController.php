<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AviatorRound;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AviatorRoundController extends Controller
{
    public function createRound(Request $request)
    {
        $request->validate([
            'crash_point' => 'required|numeric|min:1.00'
        ]);

        $round = new AviatorRound();
        // $round->game_id = 1; // Column does not exist
        $round->round_number = (AviatorRound::max('round_number') ?? 0) + 1;
        $round->crash_point = $request->crash_point;
        $round->status = 'waiting';
        $round->started_at = Carbon::now();
        $round->save();

        return response()->json([
            'status' => 'success',
            'game_id' => $round->id,
            'round_number' => $round->round_number
        ]);
    }

    public function endRound(Request $request)
    {
        $request->validate(['game_id' => 'required|integer']);
        $round = AviatorRound::find($request->game_id);
        if($round) {
            $round->status = 'completed';
            $round->save();
        }
        return response()->json(['status' => 'success']);
    }
}
