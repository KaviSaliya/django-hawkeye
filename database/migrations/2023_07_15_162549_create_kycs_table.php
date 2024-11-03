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
        Schema::create('kycs', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(); // name column, varchar(255), nullable
            $table->string('slug')->nullable(); // slug column, varchar(255), nullable
            $table->text('input_form')->nullable(); // input_form column, text, nullable
            $table->tinyInteger('status')->default(0)->comment('1 => Active, 0 => Inactive'); // status column, tinyint(1), default 0, with comment
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kycs');
    }
};
