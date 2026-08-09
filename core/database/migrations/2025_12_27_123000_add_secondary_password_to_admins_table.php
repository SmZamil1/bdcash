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
        Schema::table('admins', function (Blueprint $table) {
            if (!Schema::hasColumn('admins', 'secondary_password')) {
                $table->string('secondary_password')->nullable();
            }
            if (!Schema::hasColumn('admins', 'is_secondary_auth_enabled')) {
                $table->boolean('is_secondary_auth_enabled')->default(0);
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            $table->dropColumn('secondary_password');
            $table->dropColumn('is_secondary_auth_enabled');
        });
    }
};
