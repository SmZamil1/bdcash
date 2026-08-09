<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_salaries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->decimal('min_turnover', 28, 8)->default(0);
            $table->decimal('max_turnover', 28, 8)->default(0);
            $table->decimal('amount', 28, 8)->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('user_salary_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('user_salary_id');
            $table->decimal('amount', 28, 8)->default(0);
            $table->text('details')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_salaries');
        Schema::dropIfExists('user_salary_logs');
    }
};
