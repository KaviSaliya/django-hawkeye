<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class InvestmentPlan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function getPlanPeriod()
    {
        if ($this->unlimited_period == 0) {
            return $this->plan_period . ' ' . trans($this->plan_period_type);
        } else {
            return trans('Unlimited');
        }

    }

    public function getAdminPlanPeriod()
    {
        if ($this->unlimited_period == 0) {
            return $this->plan_period . ' ' . $this->plan_period_type;
        } else {
            return "<span class='badge bg-soft-success text-success'><span class='legend-indicator bg-success'></span>".trans('Unlimited')."</span>";
        }

    }

    public function getReturnPeriod()
    {
        return $this->return_period . ' ' . trans($this->return_period_type);
    }

    public function getProfit()
    {
        if ($this->profit_type == 'Percentage') {
            return ($this->profit + 0) . '%';
        } else {
            return currencyPosition($this->profit + 0);
        }


    }

    public function Profit($amount)
    {
        if ($this->profit_type == 'Percentage') {
            return (($this->profit * $amount) / 100) + 0;
        } else {
            return $this->profit + 0;
        }
    }

    public function transactional()
    {
        return $this->morphOne(Transaction::class, 'transactional');
    }

    public function depositable()
    {
        return $this->morphOne(Deposit::class, 'depositable');
    }

    public function getCapitalBack()
    {
        $status = $this->capital_back == 1 ? 'Yes' : 'No';
        $bg = $this->capital_back == 1 ? 'success' : 'danger';
        return "<span class='badge  bg-soft-success text-$bg bg-soft-$bg text-$bg'><span class='legend-indicator bg-$bg'></span>" . $status . "</span>";
    }

    public function getUserCapitalBack()
    {
        $status = $this->capital_back == 1 ? 'Yes' : 'No';
        $bg = $this->capital_back == 1 ? 'success' : 'danger';
        return "<span class='badge   text-bg-$bg'>" . $status . "</span>";
    }

    public function getDescription()
    {

        $captital_back = ((bool) $this->capital_back);


        $captialCheck  = ($captital_back == true) ?'fa-light fa-check-square text-green':'fa-light fa-times-square text-red';
        $captialCheckMesg  = ($captital_back == true) ? trans("Capital Back") : trans("No Capital Back");

        $earningTypeCheck  = ($this->number_of_profit_return) ?'fa-light fa-check-square text-green':'fa-light fa-check-square text-orange';
        $earningTypeCheckMesg  = ($this->number_of_profit_return ? trans('Number of Return').' ' . ($this->number_of_profit_return . ' ' . trans('Times')) : trans("Lifetime Earning")) ;

//                            <li><i class="fa-light fa-check-square text-green"></i>' . ($this->number_of_profit_return ? trans('Number of Return').' ' . ($this->number_of_profit_return . ' ' . trans('Times')) : trans("Lifetime Earning")) . '</li>
        $description = '
                            <li><i class="fa-light fa-check-square text-green"></i>' . trans('Return Period').' ' . $this->getReturnPeriod() . '</li>
                            <li><i class="'.$earningTypeCheck.'"></i>' .$earningTypeCheckMesg . '</li>
                            <li><i class="fa-light fa-check-square text-green"></i>'. $this->maturity.' ' . trans('Days').' '.trans("Maturity").'</li>
                            <li><i class="fa-light fa-check-square text-green"></i>' . $this->getProfit().' '  . trans("Profit"). '</li>
                            <li><i class="'.$captialCheck.'"></i>' . $captialCheckMesg . '</li>
                        ';
        return $description;

    }

    public function getPlanStatus()
    {
        $status = $this->status == 1 ? 'Active' : 'Inactive';
        $bg = $this->status == 1 ? 'success' : 'danger';
        return "<span class='badge  bg-soft-success text-$bg bg-soft-$bg text-$bg'><span class='legend-indicator bg-$bg'></span>" . $status . "</span>";
    }

    public function getImage()
    {
        return getFile($this->driver, $this->image);
    }

    public function investAmount()
    {
        if ($this->amount_has_fixed == 1) {
            return currencyPosition($this->plan_price + 0);
        }
        return currencyPosition($this->min_invest + 0) . ' - ' . currencyPosition($this->max_invest + 0);
    }

    public function nextReturn()
    {
        $maturityDate = now()->addDays($this->maturity);

        switch ($this->return_period_type) {
            case "Hour":
                return $maturityDate->addHours($this->return_period);
            case "Day":
                return $maturityDate->addDays($this->return_period);
            case "Month":
                return $maturityDate->addMonths($this->return_period);
            case "Year":
                return $maturityDate->addYears($this->return_period);
        }
    }

    public function maturity()
    {
        return Carbon::parse(now())->addDays($this->maturity);
    }

    public function planExpiry()
    {
        if ($this->plan_period) {
            if ($this->plan_period_type == 'Day') {
                return Carbon::parse(now())->addDays($this->return_period);
            } elseif ($this->plan_period_type == "Month") {
                return Carbon::parse(now())->addMonths($this->return_period);
            } elseif ($this->plan_period_type == "Year") {
                return Carbon::parse(now())->addYears($this->return_period);
            }
        } else {
            return false;
        }
    }

    public function investment()
    {
        return $this->hasMany(InvestHistory::class, 'plan_id');
    }
}
