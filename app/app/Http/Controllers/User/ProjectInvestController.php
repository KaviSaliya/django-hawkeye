<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\DistributeBonus;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Models\Project;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Traits\PaymentValidationCheck;

class ProjectInvestController extends Controller
{
    use PaymentValidationCheck;
   public function invest(Request $request)
   {
       //validate request
       $validator = Validator::make($request->all(), [
           'balance_type' => ['required', Rule::in(['checkout', 'balance','profit'])],
           'amount' => 'required|numeric',
           'project_id' => 'required',
           'unit' => 'required | numeric'
       ], [
           'balance_type.required' => 'The balance type field is required.',
           'balance_type.in' => 'The selected balance type is invalid. It must be either checkout or balance.',
           'amount.required' => 'Unit price field is required.',
           'amount.numeric' => 'The amount must be a number.',
           'project_id.required' => 'The project ID field is required.',
           'unit.required' => 'The unit field is required.'
       ]);

       //if validation failed then return back with validation error message
       if ($validator->fails()) {
           return back()->with('error',$validator->getMessageBag()->first());
       }


       $balance_type = $request->balance_type;
       $user = Auth::guard('web')->user();

       try {

           $project = Project::with('details')
               ->where(['status' => 1 , 'id' => $request->project_id])->firstOr(function () {

                   //if project is not exist then throw error
                   throw new \Exception('Invalid Project request');
               });

           $amount = $request->amount;

           // validate invest amount , user balance ,unit
           if ($project->amount_has_fixed && $project->fixed_invest != $amount){
               throw new \Exception("Please invest " . $project->fixed_invest);
           }
           if (!$project->amount_has_fixed && $project->minimum_invest > $amount){
               throw new \Exception("Minimum Invest Limit " . currencyPosition($project->minimum_invest));
           }
           if (!$project->amount_has_fixed && $project->maximum_invest < $amount){
               throw new \Exception("Maximum Invest Limit " . currencyPosition($project->maximum_invest));
           }
           if ($project->available_units == 0){
               throw new \Exception("Unit is Not Available");
           }

           $amount = $amount * $request->unit;
            // make invest
           $invest =  BasicService::makeProjectInvest($user,$project,$request->amount,$request->unit);

          //if payment type or balance type is checkout then redirect to payment page
           if ($balance_type == 'checkout' && $invest){
               session()->put('totalAmount', encrypt($amount));
               session()->put('invest_id', encrypt($invest->id));
               return  redirect()->route('user.project.payment');
           }

           if ($balance_type == 'profit' && $invest){

               //throw error if user profit balance is low
               if($amount > $user->profit_balance){
                   throw  new  \Exception('Insufficient Balance');
               }
                // update invest payment status
               $invest->payment_status = 1;
               $invest->save();
               $project->available_units -= $request->unit;
               $project->save();

               //update user balance
               $user->profit_balance = getAmount($user->profit_balance - ($request->amount * $request->unit));
               $user->project_invest += $amount;
               $user->total_invest += $amount;
               $user->save();

               //make transaction
               $transactional_type = 'App\Models\Project';
               $transaction =  BasicService::makeTransaction($user , ($request->amount * $request->unit),0,'-',$invest->trx,'Investment from profit balance',$transactional_type,'profit');
               $project->transactional()->save($transaction);

               //distribute referral bonus for investment
               if (basicControl()->investment_commission && $user->referral_id){
                   DistributeBonus::dispatch($user, $amount, 'invest',$project);
               }

               //send notification
               BasicService::ProjectInvestNotify($user,$project,$request->unit,$request->amount);

               return redirect()->route('success')->with('success', 'Invest Successfully');
           }

            //throw error if user wallet balance is low
           if ($amount > $user->balance) {
               throw  new  \Exception('Insufficient Balance');
           }

           if ($invest){
               // update invest payment status
               $invest->payment_status = 1;
               $invest->save();
               $project->available_units -= $request->unit;
               $project->save();

               //update user balance
               $user->balance = getAmount($user->balance - ($request->amount * $request->unit));
               $user->project_invest += $amount;
               $user->total_invest += $amount;
               $user->save();

               //make transaction
               $transactional_type = 'App\Models\Project';
               $transaction =BasicService::makeTransaction($user , ($request->amount * $request->unit),0,'-',$invest->trx,'Investment from Wallet',$transactional_type,'wallet');
               $project->transactional()->save($transaction);

               //distribute referral bonus for investment
               if (basicControl()->investment_commission && $user->referral_id){
                   DistributeBonus::dispatch($user, $amount, 'invest',$project);
               }

               //send notification
               BasicService::ProjectInvestNotify($user,$project,$request->unit,$request->amount);

               return redirect()->route('success')->with('success', 'Invest Successfully');
           }
           return back()->with('error', 'Something Went Wrong');

       }catch (\Exception $exception){

           return back()->with('error', $exception->getMessage());
       }
   }


   public function payment()
   {
       $amount = session()->get('totalAmount');
       $data['amount'] = decrypt($amount);
       $data['gateways'] = Gateway::where('status', 1)->orderBy('sort_by', 'ASC')->get();
       return view(template().'pages.project_invest_payment',$data);
   }

   public function investRequest(Request $request)
   {
       $invest_id = decrypt(session()->get('invest_id'));
       $amount = decrypt(session()->get('totalAmount'));
       $gateway = $request->gateway_id;
       $currency = $request->supported_currency??'';
       $cryptoCurrency= $request->supported_crypto_currency;

       try {
           $checkAmountValidate = $this->validationCheck($amount, $gateway, $currency, $cryptoCurrency,'project_invest');
           if ($checkAmountValidate['status'] == 'error') {
               return back()->with('error', $checkAmountValidate['msg']);
           }
           $deposit = Deposit::create([
               'user_id' => Auth::user()->id,
               'payment_method_id' => $checkAmountValidate['data']['gateway_id'],
               'payment_method_currency' => $checkAmountValidate['data']['currency'],
               'amount' => $checkAmountValidate['data']['amount'],
               'depositable_id' => $invest_id,
               'depositable_type' => 'App\Models\Project',
               'percentage_charge' => $checkAmountValidate['data']['percentage_charge'],
               'fixed_charge' => $checkAmountValidate['data']['fixed_charge'],
               'payable_amount' => $checkAmountValidate['data']['payable_amount'],
               'base_currency_charge' => $checkAmountValidate['data']['base_currency_charge'],
               'payable_amount_in_base_currency' => $checkAmountValidate['data']['payable_amount_base_in_currency'],
               'status' => 0,
           ]);
           session()->forget('totalAmount');
           session()->forget('invest_id');
           return redirect(route('payment.process', $deposit->trx_id));
       }catch (\Exception $exception){
           return back()->with('error', $exception->getMessage());
       }
   }
}
