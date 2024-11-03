<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BasicControl extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme', 'site_title', 'primary_color', 'secondary_color', 'time_zone', 'base_currency', 'currency_symbol',
        'admin_prefix', 'is_currency_position', 'has_space_between_currency_and_amount', 'is_force_ssl', 'is_maintenance_mode',
        'paginate', 'strong_password', 'registration', 'fraction_number', 'sender_email', 'sender_email_name', 'email_description',
        'push_notification', 'in_app_notification', 'email_notification', 'email_verification', 'sms_notification', 'sms_verification',
        'tawk_id', 'tawk_status', 'fb_messenger_status', 'fb_app_id','fb_page_id', 'manual_recaptcha', 'google_recaptcha', 'recaptcha_admin_login',
        'reCaptcha_status_login', 'reCaptcha_status_registration', 'measurement_id', 'analytic_status', 'error_log', 'is_active_cron_notification',
        'logo', 'logo_driver', 'favicon', 'favicon_driver', 'admin_logo', 'admin_logo_driver', 'admin_dark_mode_logo', 'admin_dark_mode_logo_driver',
        'currency_layer_access_key', 'currency_layer_auto_update_at', 'currency_layer_auto_update', 'coin_market_cap_app_key', 'coin_market_cap_auto_update_at',
        'coin_market_cap_auto_update', 'automatic_payout_permission', 'date_time_format','google_admin_login_recaptcha_status',
        'google_user_login_recaptcha_status', 'google_user_registration_recaptcha_status','cookie_title', 'cookie_button_name', 'cookie_button_url',
        'cookie_short_text', 'cookie_status', 'cookie_image', 'cookie_driver','deposit_commission','investment_commission','profit_commission','register_bonus',
        'deposit_commission_type','investment_commission_type','profit_commission_type','deposit','invest','profit','register','navbar_style'
    ];

    protected function metaKeywords(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => explode(", ", $value),
        );
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            \Cache::forget('ConfigureSetting');
        });
    }
}
