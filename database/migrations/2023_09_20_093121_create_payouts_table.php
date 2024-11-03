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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('payout_method_id')->nullable();
            $table->string('payout_currency_code', 50)->nullable();
            $table->decimal('amount', 18, 8)->default(0.00000000);
            $table->decimal('charge', 18, 8)->default(0.00000000);
            $table->decimal('net_amount', 18, 8)->default(0.00000000);
            $table->decimal('amount_in_base_currency', 18, 8)->default(0.00000000);
            $table->decimal('charge_in_base_currency', 18, 8)->default(0.00000000);
            $table->decimal('net_amount_in_base_currency', 18, 8)->default(0.00000000);
            $table->string('response_id')->nullable();
            $table->string('last_error')->nullable();
            $table->text('information')->nullable();
            $table->string('meta_field')->comment('for fullerwave');
            $table->text('feedback')->nullable();
            $table->string('trx_id', 50)->nullable();
            $table->tinyInteger('status')->nullable()->comment('0=> pending, 1=> generated, 2=>success 3=> cancel');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
