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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(); // name column, varchar(100), nullable
            $table->string('short_name', 20)->nullable(); // short_name column, varchar(20), nullable
            $table->string('flag', 100)->nullable(); // flag column, varchar(100), nullable
            $table->string('flag_driver', 20)->nullable(); // flag_driver column, varchar(20), nullable
            $table->tinyInteger('status')->default(1)->comment('0 => Inactive, 1 => Active'); // status column, tinyint(1), not nullable, default 1, with comment
            $table->tinyInteger('rtl')->default(0)->comment('0 => Inactive, 1 => Active'); // rtl column, tinyint(1), not nullable, default 0, with comment
            $table->tinyInteger('default_status')->default(0); // default_status column, tinyint(1), not nullable, default 0
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
