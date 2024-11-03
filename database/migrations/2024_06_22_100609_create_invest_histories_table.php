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
        Schema::create('invest_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained('investment_plans')->cascadeOnDelete();
            $table->decimal('invest_amount', 11, 2);
            $table->decimal('profit', 11, 2);
            $table->integer('number_of_return');
            $table->boolean('is_life_time');
            $table->integer('return_period');
            $table->string('return_period_type');
            $table->timestamp('next_return');
            $table->boolean('capital_back');
            $table->timestamp('plan_expiry_date');
            $table->boolean('status')->default(false);
            $table->boolean('plan_period_is_lifetime')->default(false);
            $table->timestamp('last_profit');
            $table->integer('total_return');
            $table->string('trx');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invest_histories');
    }
};
