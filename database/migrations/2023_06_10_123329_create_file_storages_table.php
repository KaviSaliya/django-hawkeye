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
        Schema::create('file_storages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->nullable(); // Code column, varchar(20), nullable
            $table->string('name', 191)->nullable(); // Name column, varchar(191), nullable
            $table->text('logo')->nullable(); // Logo column, text, nullable
            $table->string('driver', 20)->nullable(); // Driver column, varchar(20), nullable
            $table->tinyInteger('status')->default(0)->comment('1 => active, 0 => inactive'); // Status column, tinyint(1), not nullable, default 0, with comment
            $table->text('parameters')->nullable(); // Parameters column, text, nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_storages');
    }
};
