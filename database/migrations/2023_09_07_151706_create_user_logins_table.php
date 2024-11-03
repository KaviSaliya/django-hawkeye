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
        Schema::create('user_logins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('longitude', 191)->nullable();
            $table->string('latitude', 191)->nullable();
            $table->string('country_code', 50)->nullable();
            $table->string('location', 191)->nullable();
            $table->string('country', 100)->nullable();
            $table->string('ip_address', 100)->nullable();
            $table->string('browser', 191)->nullable();
            $table->string('os', 191)->nullable();
            $table->string('get_device', 191)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_logins');
    }
};
