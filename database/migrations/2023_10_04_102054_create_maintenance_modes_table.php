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
        Schema::create('maintenance_modes', function (Blueprint $table) {
            $table->id();
            $table->string('heading', 255)->nullable(); // heading column, varchar(255), nullable
            $table->text('description')->nullable(); // description column, text, nullable
            $table->string('image', 255)->nullable(); // image column, varchar(255), nullable
            $table->string('image_driver', 50)->nullable(); // image_driver column, varchar(50), nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_modes');
    }
};
