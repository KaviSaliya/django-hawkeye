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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('location', 100)->nullable();
            $table->integer('total_units');
            $table->integer('project_duration')->nullable();
            $table->string('project_duration_type', 10)->nullable();
            $table->decimal('return', 8, 2)->nullable();
            $table->string('return_type', 10)->nullable();
            $table->integer('return_period')->nullable();
            $table->string('return_period_type', 10)->nullable();
            $table->integer('number_of_return')->nullable();
            $table->decimal('minimum_invest', 10, 2)->nullable();
            $table->decimal('maximum_invest', 10, 2)->nullable();
            $table->decimal('fixed_invest', 10, 2)->nullable();
            $table->string('thumbnail_image', 255)->nullable();
            $table->string('thumbnail_image_driver', 255)->nullable();
            $table->longText('images')->nullable()->collation('utf8mb4_bin')->check('json_valid(`images`)');
            $table->string('images_driver', 255)->nullable();
            $table->date('start_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->tinyInteger('amount_has_fixed')->default(0);
            $table->tinyInteger('project_duration_has_unlimited')->default(0);
            $table->tinyInteger('number_of_return_has_unlimited')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->integer('available_units')->nullable();
            $table->integer('maturity');
            $table->tinyInteger('is_deleted')->default(0);
            $table->tinyInteger('capital_back')->default(0);
            $table->dateTime('invest_last_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
