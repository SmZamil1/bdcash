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
        Schema::table('user_minings', function (Blueprint $table) {
            if (!Schema::hasColumn('user_minings', 'duration')) {
                $table->integer('duration')->default(0)->after('amount');
            }
            if (!Schema::hasColumn('user_minings', 'start_at')) {
                $table->dateTime('start_at')->nullable()->after('duration');
            }
            if (!Schema::hasColumn('user_minings', 'end_at')) {
                $table->dateTime('end_at')->nullable()->after('start_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_minings', function (Blueprint $table) {
            if (Schema::hasColumn('user_minings', 'duration')) {
                $table->dropColumn('duration');
            }
            if (Schema::hasColumn('user_minings', 'start_at')) {
                $table->dropColumn('start_at');
            }
            if (Schema::hasColumn('user_minings', 'end_at')) {
                $table->dropColumn('end_at');
            }
        });
    }
};
