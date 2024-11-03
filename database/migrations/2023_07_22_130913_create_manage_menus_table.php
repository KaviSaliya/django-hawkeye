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
        Schema::create('manage_menus', function (Blueprint $table) {
            $table->id();
            $table->string('menu_section', 50)->nullable(); // menu_section column, varchar(50), nullable
            $table->string('menu_items', 255)->nullable(); // menu_items column, varchar(255), nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manage_menus');
    }
};
