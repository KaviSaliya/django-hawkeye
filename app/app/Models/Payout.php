<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class Payout extends Model
{
    use HasFactory, Prunable;
    protected $fillable = ['user_id', 'payout_method_id', 'payout_currency_code', 'amount', 'charge', 'net_amount', 'amount_in_base_currency',
    'charge_in_base_currency', 'net_amount_in_base_currency', 'response_id', 'last_error', 'information', 'meta_field', 'feedback', 'trx_id', 'status','balance_type'];

    protected $table = 'payouts';

    protected $casts = [
        'information' => 'object',
        'meta_field' => 'object',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function method()
    {
        return $this->belongsTo(PayoutMethod::class, 'payout_method_id');
    }

    protected static function boot()
    {
        parent::boot();
        static::saved(function () {
            Cache::forget('payoutRecord');
        });

        static::creating(function (Payout $payout) {
            if (empty($payout->trx_id)) {
                $payout->trx_id = self::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber()
    {
        return DB::transaction(function () {
            // Lock the last order to prevent race conditions
            $lastOrder = self::lockForUpdate()->orderBy('id', 'desc')->first();

            if ($lastOrder && isset($lastOrder->trx_id)) {
                $lastOrderNumber = (int)filter_var($lastOrder->trx_id, FILTER_SANITIZE_NUMBER_INT);
                $newOrderNumber = $lastOrderNumber + 1;
            } else {
                $newOrderNumber = strRandomNum(12);
            }

            // Check again to ensure the new trx_id doesn't already exist (extra safety)
            while (self::where('trx_id', 'P'.$newOrderNumber)->exists()) {
                $newOrderNumber = (int)$newOrderNumber + 1;
            }

            return 'P' . $newOrderNumber;
        });
    }

    public function getStatusClass()
    {
        return [
            '1' => 'text-pending',
            '2' => 'text-success',
            '3' => 'text-danger',
        ][$this->status] ?? 'text-success';
    }

    public function payoutMethod()
    {
        return $this->belongsTo(PayoutMethod::class, 'payout_method_id', 'id');
    }

    public function transactional()
    {
        return $this->morphOne(Transaction::class, 'transactional');
    }

    public function picture()
    {
        $image = $this->method->image;
        if (!$image) {
            $url = getFile($this->method->driver, $this->method->logo);
            return '<div class="avatar avatar-sm avatar-circle">
                        <img class="avatar-img" src="' . $url . '" alt="Image Description">
                     </div>';

        } else {
            $firstLetter = substr($this->method->name, 0, 1);
            return '<div class="avatar avatar-sm avatar-soft-primary avatar-circle">
                        <span class="avatar-initials">' . $firstLetter . '</span>
                     </div>';
        }
    }

    public function getStatusBadge() {
        switch ($this->status) {
            case 0:
                return '<span class="badge text-bg-warning">' . trans('Pending') . '</span>';
            case 1:
                return '<span class="badge text-bg-info">' . trans('Generated') . '</span>';
            case 2:
                return '<span class="badge text-bg-success">' . trans('Payment Done') . '</span>';
            case 3:
                return '<span class="badge text-bg-danger">' . trans('Canceled') . '</span>';
            default:
                return ''; // Handle any other cases as needed
        }
    }

    public function prunable(): Builder
    {
        return static::where('created_at', '<=', now()->subDays(5))->where('status', 0);
    }



}
