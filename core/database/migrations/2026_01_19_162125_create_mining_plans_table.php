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
        Schema::create('mining_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('coin_code')->nullable(); // e.g. BTC
            $table->string('tag')->nullable(); // e.g. SHA-256
            $table->decimal('price', 28, 8)->default(0);
            $table->string('price_currency')->default('USD'); // e.g. USD or PKR or cur_text
            $table->decimal('min_return', 28, 8)->default(0); 
            $table->decimal('max_return', 28, 8)->default(0);
            $table->string('return_type')->default('percentage'); // percentage or fixed
            $table->string('icon')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mining_plans');
    }
};
