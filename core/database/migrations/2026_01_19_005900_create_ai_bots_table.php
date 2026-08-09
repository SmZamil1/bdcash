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
        Schema::create('ai_bots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable();
            $table->decimal('price', 28, 8);
            $table->integer('duration')->default(1); // In days
            $table->decimal('daily_profit', 28, 8); // Fixed amount or percentage? Let's assume % for now or fixed based on context. "AI Trading" often implies % return daily. Let's do percentage.
            // Wait, usually hyip scripts use fixed or percent. Let's add specific fields.
            $table->decimal('min_profit', 28, 8)->default(0); // For range
            $table->decimal('max_profit', 28, 8)->default(0); // For range
            $table->tinyInteger('profit_type')->default(1); // 1 = Fixed Amount, 2 = Percentage
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('user_ai_bots', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('ai_bot_id');
            $table->decimal('invest_amount', 28, 8); // The price paid
            $table->decimal('profit_earned', 28, 8)->default(0);
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->dateTime('last_profit_at')->nullable();
            $table->tinyInteger('status')->default(1); // 1 = Running, 2 = Completed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_bots');
        Schema::dropIfExists('user_ai_bots');
    }
};
