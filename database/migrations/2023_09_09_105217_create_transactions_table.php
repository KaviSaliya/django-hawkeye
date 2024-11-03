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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transactional_id')->nullable();
            $table->string('transactional_type')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->double('amount', 11, 2)->nullable();
            $table->string('balance', 20)->nullable();
            $table->decimal('charge', 11, 2)->default(0.00);
            $table->string('trx_type', 10)->nullable();
            $table->string('remarks', 191);
            $table->string('trx_id', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
