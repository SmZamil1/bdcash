<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('general_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('general_settings', 'system_info')) {
                $table->text('system_info')->nullable();
            }
            if (!Schema::hasColumn('general_settings', 'available_version')) {
                $table->string('available_version')->nullable();
            }
            if (!Schema::hasColumn('general_settings', 'broadcast_messages')) {
                $table->text('broadcast_messages')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            //
        });
    }
};
