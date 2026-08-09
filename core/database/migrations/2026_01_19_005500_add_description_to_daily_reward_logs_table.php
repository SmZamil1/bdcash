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
        if (Schema::hasTable('daily_reward_logs')) {
            Schema::table('daily_reward_logs', function (Blueprint $table) {
                if (!Schema::hasColumn('daily_reward_logs', 'description')) {
                    $table->string('description')->nullable()->after('amount');
                }
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
        if (Schema::hasTable('daily_reward_logs')) {
            Schema::table('daily_reward_logs', function (Blueprint $table) {
                if (Schema::hasColumn('daily_reward_logs', 'description')) {
                    $table->dropColumn('description');
                }
            });
        }
    }
};
