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
        Schema::create('admin_ip_bans', function (Blueprint $探索) {
            $探索->id();
            $探索->string('ip', 40)->unique();
            $探索->string('reason')->nullable();
            $探索->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_ip_bans');
    }
};
