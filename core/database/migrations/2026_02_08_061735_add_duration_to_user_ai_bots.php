<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_ai_bots', function (Blueprint $table) {
            if (!Schema::hasColumn('user_ai_bots', 'duration')) {
                $table->integer('duration')->default(0)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_ai_bots', function (Blueprint $table) {
            if (Schema::hasColumn('user_ai_bots', 'duration')) {
                $table->dropColumn('duration');
            }
        });
    }
};
