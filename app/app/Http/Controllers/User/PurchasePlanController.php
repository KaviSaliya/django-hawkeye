<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\PaymentValidationCheck;

class PurchasePlanController extends Controller
{
    use PaymentValidationCheck;
    public function paymentRequest(Request $request)
    {

        $amount = session()->get('amount');
        $decryptAmount = decrypt($amount);
        $getPlan = session()->get('plan_id');
        $plan_id = decrypt($getPlan);
        $amount =$decryptAmount;
        $gateway = $request->gateway_id;
        $currency = $request->supported_currency??'';
        $cryptoCurrency= $request->supported_crypto_currency;

        try {
            $checkAmountValidate = $this->validationCheck($amount, $gateway, $currency, $cryptoCurrency,'purchasePlan');

            if ($checkAmountValidate['status'] == 'error') {
                return back()->with('error', $checkAmountValidate['msg']);
            }

            $deposit = Deposit::create([
                'user_id' => Auth::user()->id,
                'payment_method_id' => $checkAmountValidate['data']['gateway_id'],
                'payment_method_currency' => $checkAmountValidate['data']['currency'],
                'amount' => $checkAmountValidate['data']['amount'],
                'depositable_id' => $plan_id,
                'depositable_type' => 'App\Models\InvestmentPlan',
                'percentage_charge' => $checkAmountValidate['data']['percentage_charge'],
                'fixed_charge' => $checkAmountValidate['data']['fixed_charge'],
                'payable_amount' => $checkAmountValidate['data']['payable_amount'],
                'base_currency_charge' => $checkAmountValidate['data']['base_currency_charge'],
                'payable_amount_in_base_currency' => $checkAmountValidate['data']['payable_amount_base_in_currency'],
                'status' => 0,
            ]);

            session()->forget('amount');
            session()->forget('plan_id');

            return redirect(route('payment.process', $deposit->trx_id));
        }catch (\Exception $exception){
            return back()->with('error', $exception->getMessage());
        }
    }
}
