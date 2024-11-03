<?php
namespace App\Traits;



use App\Models\PayoutMethod;

trait PayoutValidationCheck {
    public function checkAmountValidate($amount, $selectedCurrency, $selectedPayoutMethod)
    {

        $selectedPayoutMethod = PayoutMethod::where('id', $selectedPayoutMethod)->where('is_active', 1)->first();

        if (!$selectedPayoutMethod) {
            return ['status' => false, 'message' => "Payment method not available for this transaction"];
        }

        $selectedCurrency = array_search($selectedCurrency, $selectedPayoutMethod->supported_currency);

        if ($selectedCurrency !== false) {
            $selectedPayCurrency = $selectedPayoutMethod->supported_currency[$selectedCurrency];
        } else {
            return ['status' => false, 'message' => "Please choose the currency you'd like to use for payment"];
        }

        if ($selectedPayoutMethod) {
            $payoutCurrencies = $selectedPayoutMethod->payout_currencies;
            if (is_array($payoutCurrencies)) {
                if ($selectedPayoutMethod->is_automatic == 1) {
                    $currencyInfo = collect($payoutCurrencies)->where('name', $selectedPayCurrency)->first();
                } else {
                    $currencyInfo = collect($payoutCurrencies)->where('currency_symbol', $selectedPayCurrency)->first();
                }
            } else {
                return null;
            }
        }

        $currencyType = $selectedPayoutMethod->currency_type;
        $limit = $currencyType == 0 ? 8 : 2;

        $status = false;
        $amount = getAmount($amount, $limit);

        if ($currencyInfo) {
            $percentage = getAmount($currencyInfo->percentage_charge, $limit);
            $percentage_charge = getAmount(($amount * $percentage) / 100, $limit);
            $fixed_charge = getAmount($currencyInfo->fixed_charge, $limit);
            $min_limit = getAmount($currencyInfo->min_limit, $limit);
            $max_limit = getAmount($currencyInfo->max_limit, $limit);
            $charge = getAmount($percentage_charge + $fixed_charge, $limit);
        }
        $payout_amount = getAmount($amount + $charge, $limit);
        $payout_amount_in_base_currency = getAmount($amount / $currencyInfo->conversion_rate ?? 1, $limit);
        $charge_in_base_currency = getAmount($charge / $currencyInfo->conversion_rate ?? 1, $limit);
        $net_amount_in_base_currency = $payout_amount_in_base_currency + $charge_in_base_currency;


        $basicControl = basicControl();
        if ($amount < $min_limit || $amount > $max_limit) {
            $message = "minimum payment $min_limit and maximum payment limit $max_limit";
        } else {
            $status = true;
            $message = "Amount : $amount" . " " . $selectedPayCurrency;
        }

        $data['status'] = $status;
        $data['message'] = $message;
        $data['payout_method_id'] = $selectedPayoutMethod->id;
        $data['fixed_charge'] = $fixed_charge;
        $data['percentage'] = $percentage;
        $data['percentage_charge'] = $percentage_charge;
        $data['min_limit'] = $min_limit;
        $data['max_limit'] = $max_limit;
        $data['charge'] = $charge;
        $data['amount'] = $amount;
        $data['payout_charge'] = $charge;
        $data['net_payout_amount'] = $payout_amount;
        $data['amount_in_base_currency'] = $payout_amount_in_base_currency;
        $data['charge_in_base_currency'] = $charge_in_base_currency;
        $data['net_amount_in_base_currency'] = $net_amount_in_base_currency;
        $data['conversion_rate'] = getAmount($currencyInfo->conversion_rate) ?? 1;
        $data['currency'] = $currencyInfo->name ?? $currencyInfo->currency_symbol;
        $data['base_currency'] = $basicControl->base_currency;
        $data['currency_limit'] = $limit;

        return $data;

    }
}
