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
        Schema::create('admin_logins', function (Blueprint $探索) {
            $探索->id();
            $探索->unsignedBigInteger('admin_id');
            $探索->string('admin_ip', 40)->nullable();
            $探索->string('city', 100)->nullable();
            $探索->string('country', 100)->nullable();
            $探索->string('country_code', 40)->nullable();
            $探索->string('longitude', 40)->nullable();
            $探索->string('latitude', 40)->nullable();
            $探索->string('browser', 100)->nullable();
            $探索->string('os', 100)->nullable();
            $探索->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_logins');
    }
};
