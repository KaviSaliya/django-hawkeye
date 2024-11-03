<?php

namespace App\Console\Commands;

use App\Jobs\DistributeBonus;
use App\Models\InvestHistory;
use App\Traits\Notify;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Facades\App\Services\BasicService;
use Illuminate\Support\Facades\DB;

class PlanInvestmentStatus extends Command
{
    use Notify;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan-investment-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Plan Investment Distribute Interest';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        ini_set('max_execution_time', 300);

        $now = Carbon::now();
        $basic = basicControl();


        DB::transaction(function () use ($now, $basic) {
            InvestHistory::with(['plan:id,plan_name',
                'user:id,firstname,lastname,username,email,referral_id,language_id,phone_code,phone,balance,total_invest,total_profit,profit_balance,plan_invest,project_invest,plan_profit,project_profit,total_commission'])
                ->whereHas('user')
                ->whereHas('plan')
                ->where('status', 1)
                ->where('next_return', '<=', $now)
                ->chunk(100, function ($investments) use ($basic, $now) {

                    foreach ($investments as $invest) {
                        $next_return = $invest->nextReturn();
                        $invest->total_return += 1;
                        $invest->next_return = $next_return;
                        $invest->last_profit = $now;
                        $invest->save();


                        $user = $invest->user;
                        $user->profit_balance += $invest->profit;
                        $user->total_profit += $invest->profit;
                        $user->plan_profit += $invest->profit;
                        $user->save();

                        $remarks = getAmount($invest->profit) . ' ' . $basic->base_currency . ' Return From ' . optional($invest->plan)->plan_name;

                        $transactional_type = 'App\Models\InvestHistory';
                        $transaction = BasicService::makeTransaction($user,$invest->profit,0,'+',null,$remarks,$transactional_type , 'profit');
                        $invest->transactional()->save($transaction);

                        // Send notifications, emails, etc.

                        $params = [
                            'username' => $user->username,
                            'plan_name' => optional($invest->plan)->plan_name,
                            'amount' => currencyPosition(getAmount($invest->profit)),
                        ];
                        $action = [
                            'link' => route('user.referral.bonus'),
                            'icon' => 'fa fa-money-bill-alt text-white'
                        ];
                        $firebaseAction = route('user.plan.investment');
                        $this->userPushNotification($user, 'PLAN_RETURN', $params, $action);
                        $this->userFirebasePushNotification($user, 'PLAN_RETURN', $params, $firebaseAction);
                        $this->sendMailSms($user, 'PLAN_RETURN', $params);


                        // Send Profit/interest on level
                        if (basicControl()->profit_commission && $user->referral_id) {
                            DistributeBonus::dispatch($user, $invest->profit, 'profit_commission', $invest);
                        }

                        if ($invest->total_return >= $invest->number_of_return && !$invest->is_life_time) {
                            $invest->status = 0; // Interest Complete
                            $invest->save();

                            if ($invest->capital_back) {
                                $user->profit_balance += $invest->invest_amount;
                                $user->save();
                                // Capital back transaction and notifications.
                                $remarks = getAmount($invest->invest_amount) . ' ' . $basic->base_currency . ' Capital Back From ' . optional($invest->plan)->plan_name;


                                $transactional_type = 'App\Models\InvestHistory';
                                $transaction = BasicService::makeTransaction($user, $invest->invest_amount, 0, '+', null, $remarks,$transactional_type,'profit');
                                $invest->transactional()->save($transaction);

                                $msg = [
                                    'username' => $user->username,
                                    'plan' => optional($invest->plan)->plan_name,
                                    'amount' => currencyPosition(getAmount($invest->invest_amount)),
                                ];
                                $act = [
                                    'link' => route('user.transaction.list'),
                                    'icon' => 'fa fa-money-bill-alt text-white'
                                ];
                                $firebase = route('user.transaction.list');
                                $this->userPushNotification($user, 'PLAN_CAPITAL_BLACK', $msg, $act);
                                $this->userFirebasePushNotification($user, 'PLAN_CAPITAL_BLACK', $msg, $firebase);
                                $this->sendMailSms($user, 'PLAN_CAPITAL_BLACK', $msg);

                            }
                        }

                        if ($invest->is_life_time) {
                            $invest->status = 1;
                            $invest->save();
                        }

                    }
                });


        });


    }
}
