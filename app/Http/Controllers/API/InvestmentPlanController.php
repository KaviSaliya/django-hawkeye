<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\InvestmentPlanResource;
use App\Http\Resources\PlanInvestResource;
use App\Jobs\DistributeBonus;
use App\Models\Deposit;
use App\Models\InvestHistory;
use App\Models\InvestmentPlan;
use App\Models\Language;
use App\Traits\ApiResponse;
use App\Traits\PaymentValidationCheck;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class InvestmentPlanController extends Controller
{
    use ApiResponse , PaymentValidationCheck;

    public function index(Request $request)
    {

        $plans = InvestmentPlan::where(['status' => 1, 'soft_delete' => 0])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return response()->json($this->withSuccess(InvestmentPlanResource::collection($plans)), 200);

    }

    public function investPlan(Request $request)
    {
        // validation rules
        $rules = [
            'balance_type' => 'required | in:checkout,balance,profit',
            'amount' => 'required|numeric',
            'plan_id' => 'required',
            'supported_currency' => 'nullable',
            'supported_crypto_currency' => 'nullable',
            'gateway_id' => 'nullable'
        ];

        // validate request
        $validator = Validator::make($request->all(), $rules);

        //if validation failed then return back with validation error message
        if ($validator->fails()) {
            return $this->jsonError(collect($validator->errors())->collapse());
        }

        $balance_type = $request->balance_type;
        $user = Auth::user();

        try {
            $plan = InvestmentPlan::where(['status' => 1, 'id' => $request->plan_id])->firstOr(function () {
                throw new \Exception('Invalid plan request');
            });

            $amount = $request->amount;

            // validate invest amount , user balance & unit
            if ($plan->amount_has_fixed && $plan->plan_price != $amount){
                throw new \Exception("Please invest " . currencyPosition($plan->plan_price));
            }

            if (!$plan->amount_has_fixed && $plan->min_invest > $amount){
                throw new \Exception("Minimum Invest Limit " . currencyPosition($plan->min_invest));
            }

            if (!$plan->amount_has_fixed && $plan->max_invest < $amount){
                throw new \Exception("Maximum Invest Limit " . currencyPosition($plan->max_invest));
            }

            if ($balance_type == 'checkout'){
                if($request->supported_currency || $request->supported_crypto_currency){
                    if (!$request->gateway_id){
                        return $this->jsonError('Please select Gateway');
                    }
                   $checkout = $this->checkout($plan,$amount,$request->gateway_id,$request->supported_currency,$request->supported_crypto_currency);
                    if ($checkout['status'] === true){
                        return $this->jsonSuccess(['trx_id' => $checkout['trx_id']]);
                    }else{
                        return $this->jsonError($checkout['message'],400);
                    }
                }else{
                    return $this->jsonError('Please select supported Currency or Crypto Currency', 400);
                }
            }

            // check balance type is profit balance or wallet balance
            if ($balance_type == 'profit'){
                //throw error if user profit balance is low
                if ($amount > $user->profit_balance) {
                    throw  new  \Exception('Insufficient Balance');
                }
                $profit = $plan->Profit($amount);
                //make invest
                $invest =  BasicService::makeInvest($user,$plan,$profit,null,$amount);

                if ($invest){
                    //make transaction
                    $transactional_type = 'App\Models\InvestmentPlan';
                    $transaction = BasicService::makeTransaction($user,$amount,0,'-',$invest->trx,'Investment from profit balance',$transactional_type,'profit');
                    $plan->transactional()->save($transaction);

                    //update user balance
                    $user->profit_balance = getAmount($user->profit_balance - $amount);
                    $user->plan_invest += $amount;
                    $user->total_invest += $amount;
                    $user->save();

                    //distribute referral bonus for investment
                    if (basicControl()->investment_commission && $user->referral_id){
                        DistributeBonus::dispatch($user, $amount, 'invest',$plan);
                    }

                    return $this->jsonSuccess([],'Plan has been Purchased Successfully');
                }else{
                    return  $this->jsonError('Something Went Wrong',520);
                }
            }

            // if user balance type is wallet

            //throw error if user wallet balance is low
            if ($amount > $user->balance) {
                throw  new  \Exception('Insufficient Balance');
            }

            $profit = $plan->Profit($amount);

            // make invest
            $invest =  BasicService::makeInvest($user,$plan,$profit,null,$amount);

            if ($invest){

                //make transaction
                $transactional_type = 'App\Models\InvestmentPlan';
                $transaction = BasicService::makeTransaction($user,$amount,0,'-',$invest->trx,'Investment from wallet',$transactional_type,'wallet');
                $plan->transactional()->save($transaction);

                //update user balance
                $user->balance = getAmount($user->balance - $amount);
                $user->total_invest += $amount;
                $user->plan_invest += $amount;
                $user->save();

                //distribute referral bonus for investment
                if (basicControl()->investment_commission && $user->referral_id){
                    DistributeBonus::dispatch($user, $amount, 'invest',$plan);
                }
                return $this->jsonSuccess([],'Plan has been Purchased Successfully');
            }else{
                return  $this->jsonError('Something Went Wrong',520);
            }

        }catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function checkout(InvestmentPlan $plan,$gateway,$amount , $currency = null , $crypto_currency = null)
    {
        try {
            $checkAmountValidate = $this->validationCheck($amount, $gateway, $currency, $crypto_currency,'purchasePlan');
            if ($checkAmountValidate['status'] == 'error') {
                return [
                    'status' => false,
                    'message' => $checkAmountValidate['msg']
                ];
            }

            $deposit = Deposit::create([
                'user_id' => Auth::user()->id,
                'payment_method_id' => $checkAmountValidate['data']['gateway_id'],
                'payment_method_currency' => $checkAmountValidate['data']['currency'],
                'amount' => $checkAmountValidate['data']['amount'],
                'percentage_charge' => $checkAmountValidate['data']['percentage_charge'],
                'fixed_charge' => $checkAmountValidate['data']['fixed_charge'],
                'payable_amount' => $checkAmountValidate['data']['payable_amount'],
                'base_currency_charge' => $checkAmountValidate['data']['base_currency_charge'],
                'payable_amount_in_base_currency' => $checkAmountValidate['data']['payable_amount_base_in_currency'],
                'status' => 0,
            ]);

            $plan->depositable()->save($deposit);

            return [
                'status' => true,
                'trx_id' => $deposit->trx_id,
            ];

        }catch (\Exception $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

   public function investHistory(Request $request)
   {
       $name = $request->name;
       $filterDate = explode('to', $request->date_range);
       $startDate = $filterDate[0];
       $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;

       $planInvestment = InvestHistory::with('plan')->where('user_id', Auth::id())
           ->when(!empty($request->date_range) && $endDate == null, function ($query) use ($startDate) {
               $startDate = Carbon::parse(trim($startDate))->startOfDay();
               $query->whereDate('created_at', $startDate);
           })
           ->when(!empty($request->date_range) && $endDate != null, function ($query) use ($startDate, $endDate) {
               $startDate = Carbon::parse(trim($startDate))->startOfDay();
               $endDate = Carbon::parse(trim($endDate))->endOfDay();
               $query->whereBetween('created_at', [$startDate, $endDate]);
           })
           ->when(!empty($name), function ($query) use ($name) {
               return $query->whereHas('plan',function ($query)use($name){
                   return $query->where('plan_name','LIKE','%'.$name.'%');
               });
           })
           ->orderBy('created_at','DESC')->paginate(12);


       return response()->json($this->withSuccess(PlanInvestResource::collection($planInvestment)), 200);
   }
}
