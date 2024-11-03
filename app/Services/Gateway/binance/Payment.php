<?php

namespace App\Services\Gateway\binance;

use Facades\App\Services\BasicCurl;
use Facades\App\Services\BasicService;


class Payment
{
	public static function prepareData($deposit, $gateway)
    {

        $url = "https://bpay.binanceapi.com/binancepay/openapi/v3/order";
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $nonce = '';
        for ($i = 1; $i <= 32; $i++) {
            $pos = mt_rand(0, strlen($chars) - 1);
            $char = $chars[$pos];
            $nonce .= $char;
        }
        $merchantTradeNo = $deposit->trx_id;

        $timestamp = round(microtime(true) * 1000);
        // Request body
        $request = array(
            "env" => array(
                "terminalType" => "WEB"
            ),
            "merchantTradeNo" => $merchantTradeNo,
            "fiatAmount" => round($deposit->payable_amount, 2),
            "fiatCurrency" => 'USD',
            "description" => config('basic.site_title') . " Payment",
            "goodsDetails" => array(
                array(
                    "goodsType" => "01",
                    "goodsCategory" => "D000",
                    "referenceGoodsId" => "7876763A3B",
                    "goodsName" => config('basic.site_title') . " Payment",
                    "goodsDetail" => "Payment to " . config('basic.site_title')
                ),
            ),
            'returnUrl' => route('ipn', [$gateway->code, $merchantTradeNo]),
            'webhookUrl' => route('ipn', [$gateway->code, $merchantTradeNo]),
            'cancelUrl' => route('failed'),
            'supportPayCurrency' => $deposit->payment_method_currency
        );

        $json_request = json_encode($request);
        $payload = $timestamp . "\n" . $nonce . "\n" . $json_request . "\n";
        $binance_pay_key = $gateway->parameters->mercent_api_key;
        $binance_pay_secret = $gateway->parameters->mercent_secret;
        $signature = strtoupper(hash_hmac('SHA512', $payload, $binance_pay_secret));

        $headers = array();
        $headers[] = "Content-Type: application/json";
        $headers[] = "BinancePay-Timestamp: $timestamp";
        $headers[] = "BinancePay-Nonce: $nonce";
        $headers[] = "BinancePay-Certificate-SN: $binance_pay_key";
        $headers[] = "BinancePay-Signature: $signature";
        $response = BasicCurl::binanceCurlOrderRequest($url, $headers, $json_request);

        $result = json_decode($response);

        if (isset($result)) {
            if (isset($result->data)) {
                $send['redirect'] = true;
                $send['redirect_url'] = $result->data->checkoutUrl;
            } else {
                $send['error'] = true;
                $send['message'] = 'Unexpected Error! Please Try Again';
            }
        } else {
            $send['error'] = true;
            $send['message'] = 'Unexpected Error! Please Try Again';
        }
        return json_encode($send);
    }

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{
		$url = "https://bpay.binanceapi.com/binancepay/openapi/v2/order/query";
		$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$nonce = '';
		for ($i = 1; $i <= 32; $i++) {
			$pos = mt_rand(0, strlen($chars) - 1);
			$char = $chars[$pos];
			$nonce .= $char;
		}
		$ch = curl_init();
		$timestamp = round(microtime(true) * 1000);
		$request = array(
			"merchantTradeNo" => $trx,
		);

		$json_request = json_encode($request);
		$payload = $timestamp . "\n" . $nonce . "\n" . $json_request . "\n";
		$binance_pay_key = $gateway->parameters->mercent_api_key;
		$binance_pay_secret = $gateway->parameters->mercent_secret;
		$signature = strtoupper(hash_hmac('SHA512', $payload, $binance_pay_secret));

		$headers = array();
		$headers[] = "Content-Type: application/json";
		$headers[] = "BinancePay-Timestamp: $timestamp";
		$headers[] = "BinancePay-Nonce: $nonce";
		$headers[] = "BinancePay-Certificate-SN: $binance_pay_key";
		$headers[] = "BinancePay-Signature: $signature";

		$response = BasicCurl::binanceCurlOrderRequest($url, $headers, $json_request);
		$result = json_decode($response);


		if (isset($result)) {
			if ($result->status == 'SUCCESS') {
				if (isset($result->data)) {
					if ($result->data->status = 'PAID') {
						BasicService::preparePaymentUpgradation($deposit);
						$data['status'] = 'success';
						$data['msg'] = 'Transaction was successful.';
						$data['redirect'] = route('success');
					}
				}
			} else {
				$data['status'] = 'error';
				$data['msg'] = 'unexpected error!';
				$data['redirect'] = route('failed');
			}
		}
		return $data;
	}
}
