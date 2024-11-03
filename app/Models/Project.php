<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;

    protected $guarded =  ['id'];

    protected $casts = [
        'images' => 'array',
    ];

    public function details()
    {
        return $this->hasOne(ProjectDetails::class, 'project_id');
    }

    public function getLanguageEditClass($id, $languageId)
    {
        return DB::table('project_details')->where(['project_id' => $id, 'language_id' => $languageId])->exists() ? 'bi-check2' : 'bi-pencil';
    }

    public function getStatus()
    {
        $status = $this->status == 1 ? 'Active' : 'Inactive';
        $bg = $this->status == 1 ? 'success' : 'danger';
        return "<span class='badge  bg-soft-success text-$bg bg-soft-$bg text-$bg'><span class='legend-indicator bg-$bg'></span>".$status."</span>";
    }
    public function getCapitalBack()
    {
        $status = $this->capital_back == 1 ? 'Yes' : 'No';
        $bg = $this->capital_back == 1 ? 'success' : 'danger';
        return "<span class='badge  bg-soft-success text-$bg bg-soft-$bg text-$bg'><span class='legend-indicator bg-$bg'></span>".$status."</span>";
    }

    public function getUserCapitalBack()
    {
        $status = $this->capital_back == 1 ? 'Yes' : 'No';
        $bg = $this->capital_back == 1 ? 'success' : 'danger';
        return "<span class='badge   text-bg-$bg'>".$status."</span>";
    }

    public function getReturn(){
        if ($this->return_type == 'Percentage'){
            return ($this->return+0).'%';
        }
        return  currencyPosition($this->return+0);
    }

    public function getProjectDuration()
    {
        if ($this->project_duration_has_unlimited == 0){
            return $this->project_duration . ' ' . trans($this->project_duration_type);
        }
        return trans('Lifetime');

    }

    public function getAdminProjectDuration()
    {
        if ($this->project_duration_has_unlimited == 0){
            return $this->project_duration . ' ' . $this->project_duration_type;
        }
        return "<span class='badge  bg-soft-success text-success '><span class='legend-indicator bg-success'></span>".trans('Lifetime')."</span>";

    }



    public function getUserProjectDuration()
    {
        if ($this->project_duration_has_unlimited == 0){
            return $this->project_duration . ' ' . trans($this->project_duration_type);
        }
        return "<span class='badge text-bg-success'>".trans('Lifetime')."</span>";

    }

    public function returnPeriod()
    {
        return $this->return_period.' '.trans($this->return_period_type);
    }

    public function investAmount()
    {
        if ($this->amount_has_fixed) {
            return currencyPosition($this->fixed_invest+0);
        }
        return currencyPosition($this->minimum_invest+0) . ' - '.currencyPosition($this->maximum_invest+0);
    }

    public function getImages()
    {
        $images = [];
        foreach ($this->images as $image){
            $images[] = getFile($this->images_driver,$image);
        }
        return $images;
    }

    public function getThumbnailImage()
    {
        return getFile($this->thumbnail_image_driver,$this->thumbnail_image);
    }

    public function projectStatus()
    {
        if ($this->invest_last_date > Carbon::now()) {
            // The investment date is in the future
            return '<div class="ribon ribon-green">'.trans("New Open").'</div>';
        } elseif($this->available_units == 0) {
            // The investment date is in the past
            return '<div class="ribon ribon-red">'.trans("Sold Out").'</div>';
        }
        return '<div class="ribon ribon-warning">'.trans("Running").'</div>';
    }

    public function getProfit($unit,$unitPrice)
    {
        if ($this->return_type == 'Percentage'){
            $amount = $unit * $unitPrice;
            return ($this->return * $amount) / 100;
        }else{
            return $this->return * $unit;
        }
    }

    public function transactional()
    {
        return $this->morphOne(Transaction::class, 'transactional');
    }

    public function nexReturn()
    {
        if ($this->return_period_type == "Hour"){
            return Carbon::parse(now())->addHours($this->return_period);
        }elseif ($this->return_period_type == "Day"){
            return Carbon::parse(now())->addDays($this->return_period);
        }elseif ($this->return_period_type == "Month"){
            return  Carbon::parse(now())->addMonths($this->return_period);
        }elseif ($this->return_period_type == "Year"){
            return Carbon::parse(now())->addYears($this->return_period);
        }
    }

    public function maturity()
    {
        return Carbon::parse(now())->addDays($this->maturity);
    }

    public function projectExpiry()
    {
        if ($this->project_duration){
            if ($this->project_duration_type  == 'Day'){
                return Carbon::parse(now())->addDays($this->project_duration);
            }elseif ($this->project_duration_type == "Month"){
                return  Carbon::parse(now())->addMonths($this->project_duration);
            }elseif ($this->project_duration_type == "Year"){
                return Carbon::parse(now())->addYears($this->project_duration);
            }
        }else{
            return  false;
        }
    }

    public function checkInvestmentLastDate()
    {
        if ($this->invest_last_date >= Carbon::now()) {
            return true;
        }else{
            return false;
        }
    }

    public function investment()
    {
        return $this->hasMany(ProjectInvestment::class,'project_id');
    }

    public function getDetails()
    {
        $details = $this->details;

        if (!$details){
            return $details;
        }
        return [
            'id' => $details->id,
            'project_id' => $details->project_id,
            'language_id' => $details->language_id ,
            'title' => $details->title,
            'slug' => $details->slug,
            'short_description' => strip_tags($details->short_description),
            'description' => strip_tags($details->description),
            'created_at' => $details->created_at,
            'updated_at' => $details->updated_at,
        ];
    }


}
