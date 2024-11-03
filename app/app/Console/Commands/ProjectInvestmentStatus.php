<?php

namespace App\Console\Commands;

use App\Jobs\DistributeBonus;
use App\Models\ProjectInvestment;
use App\Traits\Notify;
use Facades\App\Services\BasicService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProjectInvestmentStatus extends Command
{
    use Notify;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project-investment-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Project Investment Status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('max_execution_time', 300);
        $now = Carbon::now();
        $basic = basicControl();

        DB::transaction(function () use ($basic, $now) {
             ProjectInvestment::with(['user','project.details'])
                ->where('payment_status',1)
                ->whereHas('user')
                ->whereHas('project')
                ->where('status',1)
                ->where('next_return', '<=', $now)
                ->chunk(100, function ($projectInvestments) use ($basic, $now) {
                    foreach ($projectInvestments as $invest) {
                        $next_return = $invest->nextReturn();
                        $invest->total_return += 1;
                        $invest->next_return = $next_return;
                        $invest->last_return = $now;
                        $invest->save();

                        $user = $invest->user;
                        $user->profit_balance += $invest->return;
                        $user->total_profit += $invest->return;
                        $user->project_profit += $invest->return;
                        $user->save();

                        $remarks =  getAmount($invest->return) . ' ' . $basic->base_currency . ' Return From '.optional($invest->project->details)->title;
                        $transactional_type = 'App\Models\ProjectInvestment';
                        $transaction = BasicService::makeTransaction($user,$invest->return,0,'+',null,$remarks,$transactional_type,'profit');
                        $invest->transactional()->save($transaction);

                        $params = [
                            'username' => $user->username,
                            'project' => optional($invest->project->details)->title,
                            'amount' => currencyPosition(getAmount($invest->return)),
                        ];
                        $action = [
                            'link' => route('user.project.investment'),
                            'icon' => 'fa fa-money-bill-alt text-white'
                        ];
                        $firebaseAction = route('user.project.investment');
                        $this->userPushNotification($user,'PROJECT_RETURN',$params,$action);
                        $this->userFirebasePushNotification($user,'PROJECT_RETURN',$params,$firebaseAction);
                        $this->sendMailSms($user,'PROJECT_RETURN',$params);


                        if (basicControl()->profit_commission && $user->referral_id){
                            DistributeBonus::dispatch($user, $invest->return, 'profit_commission', $invest);
                        }

                        if ($invest->total_return >= $invest->number_of_return && !$invest->is_life_time){
                            $invest->status = 0;
                            $invest->save();

                            if ($invest->capital_back){
                                $user->profit_balance += $invest->per_unit_price * $invest->unit;
                                $user->save();

                                $transactional_type = 'App\Models\ProjectInvestment';
                                $remarks = getAmount($invest->per_unit_price * $invest->unit) . ' ' . $basic->base_currency . ' Capital Back From '.optional($invest->project->details)->title;
                                $transaction =  BasicService::makeTransaction($user,($invest->per_unit_price * $invest->unit),0,'+',null,$remarks,$transactional_type,'profit');
                                $invest->transactional()->save($transaction);

                                $msg = [
                                    'username' => $user->username,
                                    'project' => optional($invest->project->details)->title,
                                    'amount' => currencyPosition(getAmount($invest->per_unit_price * $invest->unit)),
                                ];
                                $act = [
                                    'link' => route('user.transaction.list'),
                                    'icon' => 'fa fa-money-bill-alt text-white'
                                ];
                                $firebase = route('user.transaction.list');
                                $this->userPushNotification($user,'PROJECT_CAPITAL_BACK',$msg,$act);
                                $this->userFirebasePushNotification($user,'PROJECT_CAPITAL_BACK',$msg,$firebase);
                                $this->sendMailSms($user,'PROJECT_CAPITAL_BACK',$msg);
                            }
                        }

                        if ($invest->is_life_time){
                            $invest->status =  1 ;
                            $invest->save();
                        }
                    }
                });
        });

    }
}
