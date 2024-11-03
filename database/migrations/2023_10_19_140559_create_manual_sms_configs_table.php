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
        Schema::create('manual_sms_configs', function (Blueprint $table) {
            $table->id();
            $table->string('action_method', 255)->nullable(); // action_method column, varchar(255), nullable
            $table->string('action_url', 255)->nullable(); // action_url column, varchar(255), nullable
            $table->text('header_data')->nullable(); // header_data column, text, nullable
            $table->text('param_data')->nullable(); // param_data column, text, nullable
            $table->text('form_data')->nullable(); // form_data column, text, nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_sms_configs');
    }
};
