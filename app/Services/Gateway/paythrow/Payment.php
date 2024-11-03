<?php

namespace App\Services\Gateway\paythrow;
require 'php-sdk/vendor/autoload.php';

use App\Models\Deposit;
use App\Models\Fund;
use Facades\App\Services\BasicService;


use PayThrow\Api\RedirectUrls;


class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		//Payer Object
		$payer = new \PayThrow\Api\Payer();
		$payer->setPaymentMethod('PayThrow'); //preferably, your system name, example - paythrow

		//Amount Object
		$amountIns = new \PayThrow\Api\Amount();
		$amountIns->setTotal(round($deposit->payable_amount, 2))->setCurrency($deposit->payment_method_currency); //must give a valid currency code and must exist in merchant wallet list
		//Transaction Object
		$trans = new \PayThrow\Api\Transaction();
		$trans->setAmount($amountIns);
		//RedirectUrls Object
		$urls = new RedirectUrls();
		$urls->setSuccessUrl(route($gateway->extra_parameters->ipn_url, $gateway->code)) //success url - the merchant domain page,
		->setCancelUrl(route('fund.initialize')); //cancel url - the merchant domain page, to redirect after cancellation of payment


		//Payment Object
		$payment = new \PayThrow\Api\Payment();


		$payment->setCredentials([ //client id & client secret, see merchants->setting(gear icon)
			'client_id' => $gateway->parameters->client_id,
			'client_secret' => $gateway->parameters->client_secret
		])->setRedirectUrls($urls)
			->setPayer($payer)
			->setTransaction($trans);

		try {
			$payment->create(); //create payment
			header("Location: " . $payment->getApprovedUrl()); //checkout url
		} catch (\Exception $ex) {
			print $ex;
			exit;
		}
		exit;
	}

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{
		$encoded = json_encode($_GET);
		$decoded = json_decode(base64_decode($encoded), TRUE);

		if ($decoded["status"] == 200) {
			$order = Deposit::where('trx_id', $decoded["transaction_id"])->orderBy('id', 'DESC')->first();
			if ($order) {
				if ($decoded["currency"] == $order->payment_method_currency && ($decoded["amount"] == round($order->payable_amount, 2)) && $order->status == 0) {
					BasicService::preparePaymentUpgradation($order);
				}
			}

		}


	}
}
