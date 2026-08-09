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
        Schema::create('promocodes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('amount', 28, 8);
            $table->string('wallet_type')->default('interest_wallet'); // interest_wallet, interest_wallet
            $table->integer('usage_limit')->default(0); // Global limit, 0 = unlimited
            $table->integer('used_count')->default(0);
            $table->string('frequency')->default('one_time'); // one_time, daily, weekly, monthly
            $table->boolean('status')->default(1);
            $table->timestamps();
        });

        Schema::create('promocode_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('promocode_id');
            $table->decimal('amount', 28, 8);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promocodes');
    }
};
