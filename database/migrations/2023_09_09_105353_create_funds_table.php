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
        Schema::create('funds', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->nullable(); // user_id column, int(11) unsigned, nullable
            $table->integer('gateway_id')->unsigned()->nullable(); // gateway_id column, int(11) unsigned, nullable
            $table->integer('fundable_id')->unsigned()->nullable(); // fundable_id column, int(11) unsigned, nullable
            $table->string('fundable_type', 91); // fundable_type column, varchar(91), not nullable
            $table->string('gateway_currency', 191)->nullable(); // gateway_currency column, varchar(191), nullable
            $table->decimal('amount', 18, 8)->default(0.00000000); // amount column, decimal(18,8), not nullable, default 0.00000000
            $table->decimal('charge', 18, 8)->default(0.00000000); // charge column, decimal(18,8), not nullable, default 0.00000000
            $table->decimal('percentage_charge', 18, 8)->default(0.00000000); // percentage_charge column, decimal(18,8), not nullable, default 0.00000000
            $table->decimal('fixed_charge', 18, 8)->default(0.00000000); // fixed_charge column, decimal(18,8), default 0.00000000
            $table->decimal('final_amount', 18, 8)->default(0.00000000); // final_amount column, decimal(18,8), not nullable, default 0.00000000
            $table->decimal('payable_amount_base_currency', 18, 8)->default(0.00000000); // payable_amount_base_currency column, decimal(18,8), not nullable, default 0.00000000
            $table->decimal('btc_amount', 18, 8)->nullable(); // btc_amount column, decimal(18,8), nullable
            $table->string('btc_wallet', 191)->nullable(); // btc_wallet column, varchar(191), nullable
            $table->string('transaction', 25)->nullable(); // transaction column, varchar(25), nullable
            $table->tinyInteger('status')->default(0)->comment('1=> Complete, 2=> Pending, 3 => Cancel, 4=> failed'); // status column, tinyint(4), not nullable, default 0, with comment
            $table->text('detail')->nullable(); // detail column, text, nullable
            $table->text('feedback')->nullable(); // feedback column, text, nullable
            $table->string('validation_token', 191)->nullable(); // validation_token column, varchar(191), nullable
            $table->string('referenceno', 191)->nullable(); // referenceno column, varchar(191), nullable
            $table->string('reason', 191)->nullable(); // reason column, varchar(191), nullable
            $table->text('information')->nullable(); // information column, text, nullable
            $table->text('api_response')->nullable(); // api_response column, text, nullable
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funds');
    }
};
