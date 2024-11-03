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
        Schema::create('investment_plans', function (Blueprint $table) {
            $table->id();
            $table->string('plan_name', 50)->nullable();
            $table->decimal('plan_price', 10, 2)->nullable();
            $table->integer('plan_period')->nullable();
            $table->string('plan_period_type', 10)->nullable();
            $table->decimal('min_invest', 10, 2)->nullable();
            $table->decimal('max_invest', 10, 2)->nullable();
            $table->tinyInteger('return_typ_has_lifetime')->default(0);
            $table->tinyInteger('amount_has_fixed')->default(0);
            $table->integer('return_period')->nullable();
            $table->string('return_period_type', 10)->nullable();
            $table->tinyInteger('unlimited_period')->default(0);
            $table->integer('number_of_profit_return')->nullable();
            $table->decimal('profit', 10, 2)->nullable();
            $table->string('profit_type', 10)->nullable();
            $table->tinyInteger('capital_back')->default(0);
            $table->integer('maturity');
            $table->tinyInteger('status')->default(1);
            $table->string('image', 255)->nullable();
            $table->string('driver', 255)->nullable();
            $table->tinyInteger('soft_delete')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_plans');
    }
};
