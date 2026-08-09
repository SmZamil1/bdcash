<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove both Head & Tail AND Cyber Dice
        DB::table('game_settings')
            ->whereIn('game_key', [
                'head_tail', 'head-tail', 'headtail',
                'cyber_dice', 'cyber-dice', 'cyberdice'
            ])
            ->delete();
            
        DB::table('game_settings')
            ->where('name', 'like', '%Heads & Tails%')
            ->orWhere('name', 'like', '%Cyber Dice%')
            ->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
