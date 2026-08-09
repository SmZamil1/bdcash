<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('mining_plans', 'time_setting_id')) {
            Schema::table('mining_plans', function (Blueprint $table) {
                $table->integer('time_setting_id')->default(0);
            });
        }
        // User requested removing duration from mining plans, so we don't add it here.
        // If it was added by a partial run, we leave it or drop it manually later, but for now let's ensure we don't error.

        if (!Schema::hasColumn('ai_bots', 'time_setting_id')) {
            Schema::table('ai_bots', function (Blueprint $table) {
                $table->integer('time_setting_id')->default(0);
            });
        }

        if (!Schema::hasColumn('user_minings', 'end_at')) {
            Schema::table('user_minings', function (Blueprint $table) {
                $table->timestamp('end_at')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mining_plans', function (Blueprint $table) {
            $table->dropColumn('time_setting_id');
            $table->dropColumn('duration');
        });

        Schema::table('ai_bots', function (Blueprint $table) {
            $table->dropColumn('time_setting_id');
            $table->dropColumn('max_invest');
            $table->dropColumn('daily_trade_limit');
        });

        Schema::table('user_minings', function (Blueprint $table) {
            $table->dropColumn('end_at');
        });
    }
};
