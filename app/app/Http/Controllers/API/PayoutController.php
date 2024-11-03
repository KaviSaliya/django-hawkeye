<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\PayoutMethodResource;
use App\Http\Resources\PayoutResource;
use App\Models\Payout;
use App\Models\PayoutMethod;
use App\Traits\ApiResponse;
use App\Traits\Notify;
use App\Traits\PayoutValidationCheck;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class PayoutController extends Controller
{
    use ApiResponse , PayoutValidationCheck ,Upload , Notify;

    public function payoutHistory(Request $request)
    {
        $trx = $request->trx_id;

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $payouts = Payout::with('user')
            ->where(['user_id' => Auth::id()])
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
            ->orderBy('id', 'desc')->paginate(15);

        return $this->jsonSuccess(PayoutResource::collection($payouts));
    }

    public function payoutMethod()
    {
        $payoutMethod = PayoutMethod::where('is_active', 1)->get();

        return $this->jsonSuccess(PayoutMethodResource::collection($payoutMethod));
    }

    public function payout(Request $request)
    {
       $rules = [
           'balance_type' => ['required', 'in:balance,profit_balance'],
           'amount' => ['required', 'numeric'],
           'payout_method_id' => ['required'],
           'supported_currency' => ['required'],
       ];
      $validator = Validator::make($request->all(), $rules);
      if ($validator->fails()) {
          return $this->jsonError(collect($validator->errors())->collapse(),400);
      }

        $amount = $request->amount;
        $payoutMethod = $request->payout_method_id;
        $supportedCurrency = $request->supported_currency;

        $checkAmountValidateData = $this->checkAmountValidate($amount,$supportedCurrency,$payoutMethod);

        if (!$checkAmountValidateData['status']) {
            return $this->jsonError($checkAmountValidateData['message'],400);
        }
        $user = Auth::user();

        if ($request->balance_type ==  'balance'){
            if ($user->balance < $checkAmountValidateData['net_amount_in_base_currency']){
                return $this->jsonError("Insufficient Balance",400);
            }
        }else{
            if ($user->profit_balance < $checkAmountValidateData['net_amount_in_base_currency']){
                return $this->jsonError("Insufficient Balance",400);
            }
        }

        $payout = new Payout();
        $payout->user_id = $user->id;
        $payout->payout_method_id = $checkAmountValidateData['payout_method_id'];
        $payout->payout_currency_code = $checkAmountValidateData['currency'];
        $payout->amount = $checkAmountValidateData['amount'];
        $payout->charge = $checkAmountValidateData['payout_charge'];
        $payout->net_amount = $checkAmountValidateData['net_payout_amount'];
        $payout->amount_in_base_currency = $checkAmountValidateData['amount_in_base_currency'];
        $payout->charge_in_base_currency = $checkAmountValidateData['charge_in_base_currency'];
        $payout->net_amount_in_base_currency = $checkAmountValidateData['net_amount_in_base_currency'];
        $payout->information = null;
        $payout->feedback = null;
        $payout->status = 0;
        $payout->balance_type = $request->balance_type;
        $payout->save();

        return $this->jsonSuccess(['trx_id' =>$payout->trx_id]);
    }
    public function confirmPayout(Request $request, $trx_id)
    {
        $payout = Payout::where('trx_id', $trx_id)->first();
        $payoutMethod = PayoutMethod::findOrFail($payout->payout_method_id);
        $basic = basicControl();

        $params = $payoutMethod->inputForm;
        $rules = [];

        if ($params !== null) {
            foreach ($params as $key => $cus) {
                $rules[$key] = [$cus->validation == 'required' ? $cus->validation : 'nullable'];
                if ($cus->type === 'file') {
                    $rules[$key][] = 'image';
                    $rules[$key][] = 'mimes:jpeg,jpg,png';
                    $rules[$key][] = 'max:4048';
                } elseif ($cus->type === 'text') {
                    $rules[$key][] = 'max:191';
                } elseif ($cus->type === 'number') {
                    $rules[$key][] = 'integer';
                } elseif ($cus->type === 'textarea') {
                    $rules[$key][] = 'min:3';
                    $rules[$key][] = 'max:300';
                }
            }
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->jsonError(collect($validator->errors())->collapse(),400);
        }

        $checkAmountValidate = $this->checkAmountValidate($payout->amount_in_base_currency, $payout->payout_currency_code, $payout->payout_method_id);
        if (!$checkAmountValidate['status']) {
            return $this->jsonError($checkAmountValidate['message'],400);
        }
        $params = $payoutMethod->inputForm;
        $reqField = [];

        foreach ($request->except('_token', '_method', 'type', 'currency_code', 'bank') as $k => $v) {
            foreach ($params as $inKey => $inVal) {
                if ($k == $inVal->field_name) {
                    if ($inVal->type == 'file' && $request->hasFile($inKey)) {
                        try {
                            $file = $this->fileUpload($request[$inKey], config('filelocation.payoutLog.path'),null,null,'webp',60);
                            $reqField[$inKey] = [
                                'field_name' => $inVal->field_name,
                                'field_value' => $file['path'],
                                'field_driver' => $file['driver'],
                                'validation' => $inVal->validation,
                                'type' => $inVal->type,
                            ];
                        } catch (\Exception $exp) {
                            session()->flash('error', 'Could not upload your ' . $inKey);
                            return back()->withInput();
                        }
                    } else {
                        $reqField[$inKey] = [
                            'field_name' => $inVal->field_name,
                            'validation' => $inVal->validation,
                            'field_value' => $v,
                            'type' => $inVal->type,
                        ];
                    }
                }
            }
        }

        $payoutMethod = PayoutMethod::find($payout->payout_method_id);

        $payoutCurrencies = $payoutMethod->payout_currencies;
        if ($payoutMethod->is_automatic == 1) {
            $currencyInfo = collect($payoutCurrencies)->where('name', $payout->payout_currency_code)->first();

        } else {
            $currencyInfo = collect($payoutCurrencies)->where('currency_symbol', $payout->payout_currency_code)->first();
        }

        $reqField['amount'] = [
            'field_name' => 'amount',
            'field_value' => currencyPosition($payout->amount / $currencyInfo->conversion_rate),
            'type' => 'text',
        ];

        if ($payoutMethod->code == 'paypal') {
            $reqField['recipient_type'] = [
                'field_name' => 'receiver',
                'validation' => $inVal->validation,
                'field_value' => $request->recipient_type,
                'type' => 'text',
            ];
        }
        $payout->information = $reqField;
        $payout->status = 1;

        $user = Auth::user();
        if ($user->{$payout->balance_type} < $payout->net_amount){
            return $this->jsonError('Insufficient Balance to deducted');
        }
        $updateBalance = updateBalance($payout->user_id, $payout->net_amount_in_base_currency, 0,$payout->balance_type); //update user balance
        $this->userNotify($user, $payout); // send user notification
        $payout->save();
        return $this->jsonSuccess([],'Payout generated successfully');
    }
    public function paystackPayout(Request $request, $trx_id)
    {
        $basicControl = basicControl();
        $payout = Payout::where('trx_id', $trx_id)->first();
        $payoutMethod = PayoutMethod::findOrFail($payout->payout_method_id);

        if (empty($request->bank)) {
            return $this->jsonError('Bank field is required');
        }

        $checkAmountValidate = $this->checkAmountValidate($payout->amount_in_base_currency, $payout->payout_currency_code, $payout->payout_method_id);
        if (!$checkAmountValidate['status']) {
            return $this->jsonError($checkAmountValidate['message'],400);
        }

        $rules = [];
        if ($payoutMethod->inputForm != null) {
            foreach ($payoutMethod->inputForm as $key => $cus) {
                $rules[$key] = [$cus->validation == 'required' ? $cus->validation : 'nullable'];
                if ($cus->type === 'file') {
                    $rules[$key][] = 'image';
                    $rules[$key][] = 'mimes:jpeg,jpg,png';
                    $rules[$key][] = 'max:2048';
                } elseif ($cus->type === 'text') {
                    $rules[$key][] = 'max:191';
                } elseif ($cus->type === 'number') {
                    $rules[$key][] = 'integer';
                } elseif ($cus->type === 'textarea') {
                    $rules[$key][] = 'min:3';
                    $rules[$key][] = 'max:300';
                }
            }
        }

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return $this->jsonError(collect($validate->errors())->collapse(),400);
        }

        $params = $payoutMethod->inputForm;
        $reqField = [];

        foreach ($request->except('_token', '_method', 'type', 'currency_code', 'bank') as $k => $v) {
            foreach ($params as $inKey => $inVal) {
                if ($k == $inVal->field_name) {
                    if ($inVal->type == 'file' && $request->hasFile($inKey)) {
                        try {
                            $file = $this->fileUpload($request[$inKey], config('filelocation.payoutLog.path'));
                            $reqField[$inKey] = [
                                'field_name' => $inVal->field_name,
                                'field_value' => $file['path'],
                                'field_driver' => $file['driver'],
                                'validation' => $inVal->validation,
                                'type' => $inVal->type,
                            ];
                        } catch (\Exception $exp) {
                            return response()->json($this->withError('Could not upload your ' . $inKey));
                        }
                    } else {
                        $reqField[$inKey] = [
                            'field_name' => $inVal->field_name,
                            'validation' => $inVal->validation,
                            'field_value' => $v,
                            'type' => $inVal->type,
                        ];
                    }
                }
            }
        }

        $reqField['type'] = [
            'field_name' => "type",
            'field_value' => $request->type,
            'type' => 'text',
        ];
        $reqField['bank_code'] = [
            'field_name' => "bank_id",
            'field_value' => $request->bank,
            'type' => 'number',
        ];

        $payout->information = $reqField;
        $payout->status = 1;
        $payout->save();
        $user = Auth::user();
        if ($user->{$payout->balance_type} < $payout->net_amount){
            return $this->jsonError('Insufficient Balance to deducted');
        }
        if (optional($payout->payoutMethod)->is_automatic == 1 && $basicControl->automatic_payout_permission) {
           $autoPayout =  $this->automaticPayout($payout);
           if ($autoPayout['status'] === false){
               return  $this->jsonError($autoPayout['data']);
           }else{
               $updateBalance = updateBalance($payout->user_id, $payout->net_amount_in_base_currency, 0,$payout->balance_type); //update user balance
               $this->userNotify($user, $payout); // send user notification
               return $this->jsonSuccess([],'Payout Successfully Completed');
           }
        }

        $updateBalance = updateBalance($payout->user_id, $payout->net_amount_in_base_currency, 0,$payout->balance_type); //update user balance
        $this->userNotify($user, $payout); // send user notification
        return $this->jsonSuccess([],'Payout generated successfully');
    }
    public function automaticPayout($payout)
    {
        $methodObj = 'App\\Services\\Payout\\' . optional($payout->payoutMethod)->code . '\\Card';
        $data = $methodObj::payouts($payout);

        if (!$data) {
            return [
                'status' => false,
                'data' => 'Method not available or unknown errors occur'
            ];
        }

        if ($data['status'] == 'error') {
            $payout->last_error = $data['data'];
            $payout->status = 3;
            $payout->save();
            return ['status' => false, 'data' => $data['data']];
        }
        return true;
    }
    public function flutterwavePayout(Request $request, $trx_id)
    {
        $user = Auth::user();
        $payout = Payout::with('method')->where('trx_id', $trx_id)->first();
        $purifiedData = Purify::clean($request->all());
        if (empty($purifiedData['transfer_name'])) {
            return $this->jsonError('Transfer field is required',400);
        }
        $validation = config('banks.' . $purifiedData['transfer_name'] . '.validation');


        $rules = [];
        if ($validation != null) {
            foreach ($validation as $key => $cus) {
                $rules[$key] = 'required';
            }
        }

        if ($request->transfer_name == 'NGN BANK' || $request->transfer_name == 'NGN DOM' || $request->transfer_name == 'GHS BANK'
            || $request->transfer_name == 'KES BANK' || $request->transfer_name == 'ZAR BANK' || $request->transfer_name == 'ZAR BANK') {
            $rules['bank'] = 'required';
        }

        $rules['currency_code'] = 'required';


        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {

            return $this->jsonError(collect($validate->errors())->collapse(),400);
        }

        $checkAmountValidate = $this->checkAmountValidate($payout->amount, $payout->payout_currency_code, $payout->payout_method_id);

        if (!$checkAmountValidate['status']) {
            return $this->jsonError($checkAmountValidate['message'],400);
        }


        $collection = collect($purifiedData);
        $reqField = [];
        $metaField = [];

        if (config('banks.' . $purifiedData['transfer_name'] . '.input_form') != null) {
            foreach ($collection as $k => $v) {
                foreach (config('banks.' . $purifiedData['transfer_name'] . '.input_form') as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal == 'meta') {
                            $metaField[$inKey] = $v;
                            $metaField[$inKey] = [
                                'field_name' => $k,
                                'field_value' => $v,
                                'type' => 'text',
                            ];
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $k,
                                'field_value' => $v,
                                'type' => 'text',
                            ];
                        }
                    }
                }
            }


            if ($request->transfer_name == 'NGN BANK' || $request->transfer_name == 'NGN DOM' || $request->transfer_name == 'GHS BANK'
                || $request->transfer_name == 'KES BANK' || $request->transfer_name == 'ZAR BANK' || $request->transfer_name == 'ZAR BANK') {

                $reqField['account_bank'] = [
                    'field_name' => 'Account Bank',
                    'field_value' => $request->bank,
                    'type' => 'text',
                ];
            } elseif ($request->transfer_name == 'XAF/XOF MOMO') {
                $reqField['account_bank'] = [
                    'field_name' => 'MTN',
                    'field_value' => 'MTN',
                    'type' => 'text',
                ];
            } elseif ($request->transfer_name == 'FRANCOPGONE' || $request->transfer_name == 'mPesa' || $request->transfer_name == 'Rwanda Momo'
                || $request->transfer_name == 'Uganda Momo' || $request->transfer_name == 'Zambia Momo') {
                $reqField['account_bank'] = [
                    'field_name' => 'MPS',
                    'field_value' => 'MPS',
                    'type' => 'text',
                ];
            }

            if ($request->transfer_name == 'Barter') {
                $reqField['account_bank'] = [
                    'field_name' => 'barter',
                    'field_value' => 'barter',
                    'type' => 'text',
                ];
            } elseif ($request->transfer_name == 'flutterwave') {
                $reqField['account_bank'] = [
                    'field_name' => 'barter',
                    'field_value' => 'barter',
                    'type' => 'text',
                ];
            }


            $payoutMethod = PayoutMethod::find($payout->payout_method_id);
            $payoutCurrencies = $payoutMethod->payout_currencies;
            $currencyInfo = collect($payoutCurrencies)->where('name', $payout->payout_currency_code)->first();

            $reqField['amount'] = [
                'field_name' => 'amount',
                'field_value' => $payout->amount * $currencyInfo->conversion_rate,
                'type' => 'text',
            ];

            $payout->information = $reqField;
            $payout->meta_field = $metaField;


        } else {
            $payout->information = null;
            $payout->meta_field = null;
        }

        $payout->status = 1;
        $payout->payout_currency_code = $request->currency_code;
        $payout->save();
        if ($user->{$payout->balance_type} < $payout->net_amount){
            return $this->jsonError('Insufficient Balance to deducted');
        }
        $updateBalance = updateBalance($payout->user_id, $payout->net_amount_in_base_currency, 0,$payout->balance_type); //update user balance
        $this->userNotify($user, $payout); // send user notification
        return $this->jsonSuccess([],'Payout generated successfully');

    }
    public function getBankList(Request $request)
    {
        $rules = [
            'currencyCode' => 'required',
        ];
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return $this->jsonError(collect($validate->errors())->collapse(),400);
        }
        $currencyCode = $request->currencyCode;
        $methodObj = 'App\\Services\\Payout\\paystack\\Card';
        $data = $methodObj::getBank($currencyCode);
        return $this->jsonSuccess($data);
    }
    public function getBankForm(Request $request)
    {
        $rules = [
            'bankName' => 'required',
        ];
        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return $this->jsonError(collect($validate->errors())->collapse(),400);
        }
        $bankName = $request->bankName;
        $bankArr = config('banks.' . $bankName);

        if ($bankArr['api'] != null) {

            $methodObj = 'App\\Services\\Payout\\flutterwave\\Card';
            $data = $methodObj::getBank($bankArr['api']);
            $value['bank'] = $data;
        }
        $value['input_form'] = $bankArr['input_form'];
        return $this->jsonSuccess($value);
    }
    public function userNotify($user, $payout)
    {
        $params = [
            'sender' => $user->name,
            'amount' => currencyPosition(getAmount($payout->amount_in_base_currency)),
            'transaction' => $payout->trx_id,
        ];

        $action = [
            "link" => route('admin.payout.log'),
            "icon" => "fa fa-money-bill-alt text-white",
            "name" => optional($payout->user)->firstname . ' ' . optional($payout->user)->lastname,
            "image" => getFile(optional($payout->user)->image_driver, optional($payout->user)->image),
        ];
        $firebaseAction = route('admin.payout.log');
        $this->adminMail('PAYOUT_REQUEST_TO_ADMIN', $params);
        $this->adminPushNotification('PAYOUT_REQUEST_TO_ADMIN', $params, $action);
        $this->adminFirebasePushNotification('PAYOUT_REQUEST_TO_ADMIN', $params, $firebaseAction);

        $params = [
            'amount' => getAmount($payout->amount) ,
            'currency' => $payout->payout_currency_code,
            'transaction' => $payout->trx_id,
        ];
        $action = [
            "link" => route('user.payout.index'),
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $firebaseAction = route('user.payout.index');
        $this->sendMailSms($user, 'PAYOUT_REQUEST_FROM', $params);
        $this->userPushNotification($user, 'PAYOUT_REQUEST_FROM', $params, $action);
        $this->userFirebasePushNotification($user, 'PAYOUT_REQUEST_FROM', $params, $firebaseAction);

        return true;
    }

}
