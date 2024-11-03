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
        Schema::create('content_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id')->nullable(); // Content_id column, bigint(20), nullable
            $table->unsignedBigInteger('language_id')->nullable(); // Language_id column, bigint(20), nullable
            $table->longText('description')->nullable(); // Description column, longtext, nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_details');
    }
};
