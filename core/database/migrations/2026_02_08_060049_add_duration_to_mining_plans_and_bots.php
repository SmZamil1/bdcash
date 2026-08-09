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
        if (!Schema::hasColumn('mining_plans', 'duration')) {
            Schema::table('mining_plans', function (Blueprint $table) {
                $table->integer('duration')->default(30)->after('price'); // Default 30 days
            });
        }

        if (!Schema::hasColumn('ai_bots', 'duration')) {
            Schema::table('ai_bots', function (Blueprint $table) {
                $table->integer('duration')->default(30)->after('price');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('mining_plans', 'duration')) {
            Schema::table('mining_plans', function (Blueprint $table) {
                $table->dropColumn('duration');
            });
        }

    // We only drop from ai_bots if we added it, but standard practice is to revert to "previous state".
    // Since ai_bots had duration originally, dropping it here might be wrong if this migration is rolled back but it was there before.
    // However, migration rollbacks usually undo exactly what `up` did.
    // Given complexity, let's keep it simple: if it exists, drop it? No, only drop if this transaction added it? difficult to track.
    // I will just drop it from mining_plans as that was definitely missing. 
    // For ai_bots, I'll add a check but if it existed prior, rollbacking this shouldn't remove it necessarily unless we are sure.
    // Let's just drop mining_plans duration.
    }
};
