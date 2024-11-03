<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectInvestResource;
use App\Http\Resources\ProjectResource;
use App\Jobs\DistributeBonus;
use App\Models\Deposit;
use App\Models\Language;
use App\Models\Project;
use App\Models\ProjectInvestment;
use App\Traits\ApiResponse;
use Facades\App\Services\BasicService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProjectController extends Controller
{
    use ApiResponse;

    public function index($language_id = null)
    {
        if (!$language_id){
            $language_id = Language::where('default_status', 1)->first()->id;
        }

        $projects = Project::with(['details' => function ($query) use ($language_id) {
            $query->where('language_id', $language_id);
        }])
            ->where(function ($query) {
                $query->where('expiry_date', '>', Carbon::now())
                    ->orWhere('project_duration_has_unlimited', 1);
            })
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->orderBy('created_at', 'desc')
            ->paginate(15);


        return $this->jsonSuccess(ProjectResource::collection($projects));
    }

    public function invest(Request $request)
    {
        //validate request
        $validator = Validator::make($request->all(), [
            'balance_type' => ['required', Rule::in(['checkout', 'balance', 'profit'])],
            'amount' => 'required|numeric',
            'project_id' => 'required',
            'unit' => 'required | numeric',
            'gateway_id' => 'nullable',
            'supported_currency' => 'nullable',
            'supported_crypto_currency' => 'nullable'
        ], [
            'balance_type.required' => 'The balance type field is required.',
            'balance_type.in' => 'The selected balance type is invalid. It must be either checkout or balance.',
            'amount.required' => 'Unit price field is required.',
            'amount.numeric' => 'The amount must be a number.',
            'project_id.required' => 'The project ID field is required.',
            'unit.required' => 'The unit field is required.'
        ]);

        $balance_type = $request->balance_type;
        $user = Auth::guard('web')->user();

        //if validation failed then return back with validation error message
        if ($validator->fails()) {
            return $this->jsonError(collect($validator->errors())->collapse(), 403);
        }

        try {
            $project = Project::with('details')
                ->where(['status' => 1, 'id' => $request->project_id])->firstOr(function () {
                    throw new \Exception('Invalid Project request');
                });

            $amount = $request->amount;

            // validate invest amount , user balance ,unit
            if ($project->amount_has_fixed && $project->fixed_invest != $amount) {
                throw new \Exception("Please invest " . $project->fixed_invest);
            }
            if (!$project->amount_has_fixed && $project->minimum_invest > $amount) {
                throw new \Exception("Minimum Invest Limit " . currencyPosition($project->minimum_invest));
            }
            if (!$project->amount_has_fixed && $project->maximum_invest < $amount) {
                throw new \Exception("Maximum Invest Limit " . currencyPosition($project->maximum_invest));
            }
            if ($project->available_units == 0) {
                throw new \Exception("Unit is Not Available");
            }

            $amount = $amount * $request->unit;

            // make invest
            $invest = BasicService::makeProjectInvest($user, $project, $request->amount, $request->unit);

            //if payment type or balance type is checkout then redirect to payment page
            if ($balance_type == 'checkout' && $invest) {
                if ($request->supported_currency || $request->supported_crypto_currency) {
                    if (!$request->gateway_id) {
                        return $this->jsonError('Please select Gateway');
                    }
                    $checkout = $this->checkout($invest, $amount, $request->gateway_id, $request->supported_currency, $request->supported_crypto_currency);
                    if ($checkout['status'] === true) {
                        return $this->jsonSuccess(['trx_id' => $checkout['trx_id']]);
                    } else {
                        return $this->jsonError($checkout['message'], 400);
                    }
                } else {
                    return $this->jsonError('Please select supported Currency or Crypto Currency', 400);
                }
            }

            if ($balance_type == 'profit' && $invest) {

                //throw error if user profit balance is low
                if ($amount > $user->profit_balance) {
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
                $transaction = BasicService::makeTransaction($user, ($request->amount * $request->unit), 0, '-', $invest->trx, 'Investment from profit balance', $transactional_type, 'profit');
                $project->transactional()->save($transaction);

                //distribute referral bonus for investment
                if (basicControl()->investment_commission && $user->referral_id) {
                    DistributeBonus::dispatch($user, $amount, 'invest', $project);
                }

                //send notification
                BasicService::ProjectInvestNotify($user, $project, $request->unit, $request->amount);

                return $this->jsonSuccess([], 'Invest Successfully');

            }

            //throw error if user wallet balance is low
            if ($amount > $user->balance) {
                throw  new  \Exception('Insufficient Balance');
            }

            if ($invest) {

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
                $transaction = BasicService::makeTransaction($user, ($request->amount * $request->unit), 0, '-', $invest->trx, 'Investment from Wallet', $transactional_type, 'wallet');
                $project->transactional()->save($transaction);

                //distribute referral bonus for investment
                if (basicControl()->investment_commission && $user->referral_id) {
                    DistributeBonus::dispatch($user, $amount, 'invest', $project);
                }

                //send notification
                BasicService::ProjectInvestNotify($user, $project, $request->unit, $request->amount);

                return $this->jsonSuccess([], 'Invest Successfully');
            }

            return $this->jsonError('Something Went Wrong', 520);

        } catch (\Exception $e) {
            return $this->jsonError($e->getMessage(), 500);
        }
    }


    public function checkout(ProjectInvestment $investment, $amount, $gateway, $currency, $cryptoCurrency)
    {
        try {
            $checkAmountValidate = $this->validationCheck($amount, $gateway, $currency, $cryptoCurrency, 'project_invest');
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
                'depositable_id' => $investment->id,
                'depositable_type' => 'App\Models\Project',
                'percentage_charge' => $checkAmountValidate['data']['percentage_charge'],
                'fixed_charge' => $checkAmountValidate['data']['fixed_charge'],
                'payable_amount' => $checkAmountValidate['data']['payable_amount'],
                'base_currency_charge' => $checkAmountValidate['data']['base_currency_charge'],
                'payable_amount_in_base_currency' => $checkAmountValidate['data']['payable_amount_base_in_currency'],
                'status' => 0,
            ]);

            return [
                'status' => true,
                'trx_id' => $deposit->trx_id,
            ];

        } catch (\Exception $exception) {
            return [
                'status' => false,
                'message' => $exception->getMessage(),
            ];
        }
    }

    public function investHistory(Request $request ,$language_id)
    {
        $name = $request->name;
        $filterDate = explode('to', $request->date_range);
        $startDate = $filterDate[0];
        $endDate = isset($filterDate[1]) ? trim($filterDate[1]) : null;
        $projectInvestment = ProjectInvestment::with(['project.details'=>function ($query) use($language_id) {
            $query->where('language_id', $language_id);
        }])->where('user_id', Auth::id())
            ->where('payment_status', 1)
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
                return $query->whereHas('project.details', function ($query) use ($name) {
                    return $query->where('title', 'LIKE', '%' . $name . '%');
                });
            })
            ->orderBy('created_at', 'DESC')->paginate(12);

        return response()->json($this->withSuccess(ProjectInvestResource::collection($projectInvestment)), 200);

    }

}
