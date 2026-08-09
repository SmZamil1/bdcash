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
        Schema::create('game_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('game_key')->unique();
            $table->decimal('win_chance', 5, 2)->default(30.00);
            $table->decimal('min_bet', 28, 8)->default(1.00);
            $table->decimal('max_bet', 28, 8)->default(100.00);
            $table->text('bet_options')->nullable(); // comma separated or json
            $table->boolean('status')->default(1);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_settings');
    }
};
