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
        Schema::create('deposits', function (Blueprint $table) {
            $table->id();
            $table->integer('depositable_id')->nullable(); // depositable_id column, int(11), nullable
            $table->string('depositable_type')->nullable(); // depositable_type column, varchar(255), nullable
            $table->bigInteger('user_id')->unsigned()->nullable(); // user_id column, bigint(20) unsigned, nullable
            $table->bigInteger('payment_method_id')->unsigned()->nullable(); // payment_method_id column, bigint(20) unsigned, nullable
            $table->string('payment_method_currency')->nullable(); // payment_method_currency column, varchar(255), nullable
            $table->decimal('amount', 18, 8)->default(0.00000000); // amount column, decimal(18,8), not nullable, default 0.00000000
            $table->decimal('percentage_charge', 18, 8)->default(0.00000000); // percentage_charge column, decimal(18,8), not nullable, default 0.00000000
            $table->decimal('fixed_charge', 18, 8)->default(0.00000000); // fixed_charge column, decimal(18,8), not nullable, default 0.00000000
            $table->decimal('payable_amount', 18, 8)->default(0.00000000)->comment('Amount paid'); // payable_amount column, decimal(18,8), not nullable, default 0.00000000, with comment
            $table->double('base_currency_charge', 18, 8)->default(0.00000000); // base_currency_charge column, double(18,8), default 0.00000000
            $table->double('payable_amount_in_base_currency', 18, 8)->default(0.00000000); // payable_amount_in_base_currency column, double(18,8), not nullable, default 0.00000000
            $table->decimal('btc_amount', 18, 8)->nullable(); // btc_amount column, decimal(18,8), nullable
            $table->string('btc_wallet')->nullable(); // btc_wallet column, varchar(255), nullable
            $table->string('payment_id', 191)->nullable(); // payment_id column, varchar(191), nullable
            $table->text('information')->nullable(); // information column, text, nullable
            $table->char('trx_id', 36)->nullable(); // trx_id column, char(36), nullable
            $table->tinyInteger('status')->default(0)->comment('0=pending, 1=success, 2=request, 3=rejected'); // status column, tinyint(4), not nullable, default 0, with comment
            $table->text('note')->nullable(); // note column, text, nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deposits');
    }
};
