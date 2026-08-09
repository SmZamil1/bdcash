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
            if (!Schema::hasColumn('user_minings', 'next_profit_at')) {
                $table->dateTime('next_profit_at')->nullable()->after('last_paid');
            }
        });

        Schema::table('mining_plans', function (Blueprint $table) {
            if (Schema::hasColumn('mining_plans', 'max_return')) {
                $table->dropColumn('max_return');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_minings', function (Blueprint $table) {
            if (Schema::hasColumn('user_minings', 'next_profit_at')) {
                $table->dropColumn('next_profit_at');
            }
        });

        Schema::table('mining_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('mining_plans', 'max_return')) {
                $table->decimal('max_return', 28, 8)->default(0)->after('min_return');
            }
        });
    }
};
