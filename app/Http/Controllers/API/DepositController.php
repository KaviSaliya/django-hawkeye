<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\DepositResource;
use App\Traits\ApiResponse;
use App\Traits\PaymentValidationCheck;
use Illuminate\Http\Request;
use App\Models\Deposit;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DepositController extends Controller
{
    use ApiResponse , PaymentValidationCheck;
    public function paymentRequest(Request $request)
    {
        $rules = [
            'amount' => 'required',
            'gateway_id' => 'required',
            'supported_currency' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if (empty($request->supported_crypto_currency) && empty($value)) {
                        $fail('Either Supported Currency or Supported Crypto Currency is required.');
                    }
                },
            ],
            'supported_crypto_currency' => [
                'nullable',
                function ($attribute, $value, $fail) use ($request) {
                    if (empty($request->supported_currency) && empty($value)) {
                        $fail('Either Supported Crypto Currency or Supported Currency is required.');
                    }
                },
            ],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->jsonError(collect($validator->errors())->collapse());
        }
        if (!isset($request->supported_currency) && !isset($request->supported_crypto_currency)){
            return response()->json($this->jsonError('Either Supported Crypto Currency or Supported Currency is required.',400));
        }

        $amount = $request->amount;
        $gateway = $request->gateway_id;
        $currency = $request->supported_currency??'';
        $cryptoCurrency= $request->supported_crypto_currency;

        try {
            $checkAmountValidate = $this->validationCheck($amount, $gateway, $currency, $cryptoCurrency);

            if ($checkAmountValidate['status'] == 'error') {
                return back()->with('error', $checkAmountValidate['msg']);
            }

            $deposit = Deposit::create([
                'user_id' => Auth::user()->id,
                'depositable_type' => 'App\Models\Deposit',
                'payment_method_id' => $checkAmountValidate['data']['gateway_id'],
                'payment_method_currency' => $checkAmountValidate['data']['currency'],
                'amount' => $amount,
                'percentage_charge' => $checkAmountValidate['data']['percentage_charge'],
                'fixed_charge' => $checkAmountValidate['data']['fixed_charge'],
                'payable_amount' => $checkAmountValidate['data']['payable_amount'],
                'base_currency_charge' => $checkAmountValidate['data']['base_currency_charge'],
                'payable_amount_in_base_currency' => $checkAmountValidate['data']['payable_amount_base_in_currency'],
                'status' => 0,
            ]);

            return $this->jsonSuccess(['trx_id' =>$deposit->trx_id],null,200);

        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(),500);
        }
    }

    public function depositHistory(Request $request)
    {
        $trx = $request->trx_id;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $userId = Auth::id();

        $funds = Deposit::with(['depositable', 'gateway'])
            ->whereHas('gateway')
            ->where('depositable_type',Deposit::class)
            ->where('user_id', $userId)
            ->when(!empty($request->date_range) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::parse(trim($startDate))->startOfDay();
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->date_range) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::parse(trim($startDate))->startOfDay();
                $endDate = Carbon::parse(trim($endDate))->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when(!empty($trx), function ($query) use ($trx) {
                return $query->where('trx_id', $trx);
            })
            ->orderBy('id', 'desc')
            ->latest()->paginate(12);

        return $this->jsonSuccess(DepositResource::collection($funds));

    }
}
