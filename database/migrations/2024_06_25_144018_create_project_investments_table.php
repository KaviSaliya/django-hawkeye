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
        Schema::create('project_investments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->decimal('per_unit_price', 11, 2)->nullable();
            $table->integer('unit')->nullable();
            $table->decimal('return', 11, 2)->nullable();
            $table->integer('number_of_return')->nullable();
            $table->boolean('is_life_time')->nullable();
            $table->integer('return_period')->nullable();
            $table->string('return_period_type')->nullable();
            $table->timestamp('next_return')->nullable();
            $table->boolean('capital_back')->nullable();
            $table->timestamp('project_expiry_date')->nullable();
            $table->boolean('status')->default(false);
            $table->boolean('project_period_is_lifetime')->default(false);
            $table->timestamp('last_return')->nullable();
            $table->integer('total_return')->nullable();
            $table->string('trx');
            $table->boolean('payment_status')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_investments');
    }
};
