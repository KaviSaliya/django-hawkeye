<?php

namespace App\Jobs;

use App\Models\Referral;
use App\Models\ReferralBonus;
use App\Models\User;
use App\Traits\Notify;
use Facades\App\Services\BasicService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DistributeBonus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Notify;

    public $user;
    public $amount;
    public $commissionType;
    public $object;

    /**
     * Create a new job instance.
     */
    public function __construct($user, $amount, $commissionType = '',$object = null)
    {
        $this->user = $user;
        $this->amount = $amount;
        $this->commissionType = $commissionType;
        $this->object = $object;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $user = $this->user;
        $amount = $this->amount;
        $commissionType = $this->commissionType;
        $object = $this->object;
        try {
            $userId = $user->id;
            $i = 1;
            $level = Referral::where('commission_type', $commissionType)->count();

            while ($userId != "" || $userId != "0" || $i < $level) {
                $me = User::with('referral')->find($userId);
                $refer = $me->referral;
                if (!$refer) {
                    break;
                }
                $commission = Referral::where('commission_type', $commissionType)->where('level', $i)->first();
                if (!$commission) {
                    break;
                }
                if ($commission->amount_type == '%') {
                    $com = ($amount * $commission->commission) / 100;
                } else {
                    $com = $commission->commission;
                }

                $new_bal = getAmount($refer->profit_balance + $com);
                $refer->profit_balance = $new_bal;
                $refer->total_profit += $com;
                $refer->total_commission += $com;
                $refer->save();

                $remarks = 'level'.' ' . $i . ' '.'Referral bonus From' . ' '.$user->username;


                $object_type = get_class($object);
                $transaction = BasicService::makeTransaction($refer, $com, 0, '+', null, $remarks,$object_type,'Profit');
                $object->transactional()->save($transaction);

                $trx = $transaction->trx_id;

                $bonus = new ReferralBonus();
                $bonus->from_user_id = $refer->id; // those person who Referred for investment
                $bonus->to_user_id = $user->id; // those person who invested
                $bonus->level = $i;
                $bonus->amount = getAmount($com);
                $bonus->commission_type = $commissionType;
                $bonus->trx_id = $trx;
                $bonus->remarks = $remarks;
                $bonus->save();

                $params = [
                    'transaction_id' => $trx,
                    'amount' => currencyPosition(getAmount($com)),
                    'bonus_from' => $user->username,
                    'final_balance' => $refer->profit_balance,
                    'level' => $i
                ];
                $action = [
                    'link' => route('user.referral.bonus'),
                    'icon' => 'fa fa-money-bill-alt text-white'
                ];
                $firebaseAction = route('user.referral.bonus');

                $this->userPushNotification($refer, 'REFERRAL_BONUS', $params, $action);
                $this->userFirebasePushNotification($refer, 'REFERRAL_BONUS', $params, $firebaseAction);
                $this->sendMailSms($refer, 'REFERRAL_BONUS', $params);

                $userId = $refer->id;
                $i++;
            }

        } catch (\Exception $exception) {

        }
    }
}
