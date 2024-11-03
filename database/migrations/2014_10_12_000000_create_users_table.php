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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('firstname', 255)->nullable();
            $table->string('lastname', 255)->nullable();
            $table->string('username', 255)->nullable();
            $table->integer('referral_id')->nullable();
            $table->integer('language_id')->nullable();
            $table->string('email', 255)->notNull();
            $table->string('country_code', 255)->nullable();
            $table->string('country', 255)->nullable();
            $table->string('phone_code', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->decimal('balance', 8, 2)->nullable();
            $table->decimal('total_invest', 10, 0)->nullable();
            $table->decimal('total_profit', 10, 0)->nullable();
            $table->decimal('plan_invest', 10, 0)->nullable();
            $table->decimal('project_invest', 10, 0)->nullable();
            $table->decimal('plan_profit', 10, 0)->nullable();
            $table->decimal('project_profit', 10, 0)->nullable();
            $table->integer('ref_id')->nullable();
            $table->decimal('total_commission', 10, 2)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('image_driver', 50)->nullable();
            $table->string('state', 255)->nullable();
            $table->string('city', 255)->nullable();
            $table->string('zip_code', 255)->nullable();
            $table->text('address_one')->nullable();
            $table->text('address_two')->nullable();
            $table->string('provider', 191)->nullable();
            $table->integer('provider_id')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('identity_verify')->nullable();
            $table->tinyInteger('address_verify')->nullable();
            $table->tinyInteger('two_fa')->nullable();
            $table->tinyInteger('two_fa_verify')->nullable();
            $table->string('two_fa_code', 255)->nullable();
            $table->tinyInteger('email_verification')->nullable();
            $table->tinyInteger('sms_verification')->nullable();
            $table->string('verify_code', 255)->nullable();
            $table->dateTime('sent_at')->nullable();
            $table->dateTime('last_login')->nullable();
            $table->dateTime('last_seen')->nullable();
            $table->string('time_zone', 255)->nullable();
            $table->string('password', 255)->notNull();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
