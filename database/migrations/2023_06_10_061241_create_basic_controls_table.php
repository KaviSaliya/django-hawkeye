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
        Schema::create('basic_controls', function (Blueprint $table) {
            $table->id();
            $table->string('theme', 50)->nullable();
            $table->string('site_title', 255)->nullable();
            $table->string('navbar_style', 20)->nullable();
            $table->string('primary_color', 50)->nullable();
            $table->string('secondary_color', 50)->nullable();
            $table->string('time_zone', 50)->nullable();
            $table->string('base_currency', 20)->nullable();
            $table->string('currency_symbol', 20)->nullable();
            $table->string('admin_prefix', 191)->nullable();
            $table->string('is_currency_position', 191)->default('left')->comment('left, right');
            $table->tinyInteger('has_space_between_currency_and_amount')->default(0);
            $table->tinyInteger('is_force_ssl')->default(0);
            $table->tinyInteger('is_maintenance_mode')->default(0);
            $table->integer('paginate')->nullable();
            $table->tinyInteger('strong_password')->default(0);
            $table->tinyInteger('registration')->default(0);
            $table->integer('fraction_number')->nullable();
            $table->string('sender_email', 255)->nullable();
            $table->string('sender_email_name', 255)->nullable();
            $table->text('email_description')->nullable();
            $table->tinyInteger('push_notification')->default(0);
            $table->tinyInteger('in_app_notification')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('email_notification')->default(0);
            $table->tinyInteger('email_verification')->default(0);
            $table->tinyInteger('sms_notification')->default(0);
            $table->tinyInteger('sms_verification')->default(0);
            $table->string('tawk_id', 255)->nullable();
            $table->tinyInteger('tawk_status')->default(0);
            $table->tinyInteger('fb_messenger_status')->default(0);
            $table->string('fb_app_id', 255)->nullable();
            $table->string('fb_page_id', 255)->nullable();
            $table->tinyInteger('manual_recaptcha')->default(0)->comment('0 =>inactive, 1 => active');
            $table->tinyInteger('google_recaptcha')->default(0)->comment('0=>inactive, 1 =>active');
            $table->tinyInteger('recaptcha_admin_login')->default(0)->comment('0 => inactive, 1 => active');
            $table->tinyInteger('reCaptcha_status_login')->default(0)->comment('0 = inactive, 1 = active');
            $table->tinyInteger('google_admin_login_recaptcha_status')->default(0);
            $table->tinyInteger('google_user_login_recaptcha_status')->default(0);
            $table->tinyInteger('google_user_registration_recaptcha_status')->default(0);
            $table->tinyInteger('reCaptcha_status_registration')->default(0)->comment('0 = inactive, 1 = active');
            $table->string('measurement_id', 255)->nullable();
            $table->tinyInteger('analytic_status')->nullable();
            $table->tinyInteger('error_log')->nullable();
            $table->tinyInteger('is_active_cron_notification')->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('logo_driver', 20)->nullable();
            $table->string('favicon', 255)->nullable();
            $table->string('favicon_driver', 20)->nullable();
            $table->string('admin_logo', 255)->nullable();
            $table->string('admin_logo_driver', 20)->nullable();
            $table->string('admin_dark_mode_logo', 255)->nullable();
            $table->string('admin_dark_mode_logo_driver', 50)->nullable();
            $table->string('currency_layer_access_key', 255)->nullable();
            $table->string('currency_layer_auto_update_at', 255)->nullable();
            $table->string('currency_layer_auto_update', 1)->nullable();
            $table->string('coin_market_cap_app_key', 255)->nullable();
            $table->string('coin_market_cap_auto_update_at', 255);
            $table->tinyInteger('coin_market_cap_auto_update')->nullable();
            $table->tinyInteger('automatic_payout_permission')->default(0);
            $table->string('date_time_format', 255)->nullable();
            $table->string('cookie_title', 60);
            $table->string('cookie_button_name', 50);
            $table->string('cookie_button_url', 255);
            $table->tinyInteger('cookie_status')->default(1);
            $table->string('cookie_short_text', 255);
            $table->string('cookie_image', 150);
            $table->string('cookie_driver', 60);
            $table->tinyInteger('deposit_commission')->default(0);
            $table->tinyInteger('investment_commission')->default(0);
            $table->tinyInteger('profit_commission')->default(0);
            $table->integer('register_bonus')->nullable();
            $table->string('deposit_commission_type', 10)->nullable();
            $table->string('investment_commission_type', 10)->nullable();
            $table->string('profit_commission_type', 10)->nullable();
            $table->decimal('deposit', 10, 2)->nullable();
            $table->decimal('invest', 10, 2)->nullable();
            $table->decimal('profit', 10, 2)->nullable();
            $table->decimal('register', 11, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basic_controls');
    }
};
