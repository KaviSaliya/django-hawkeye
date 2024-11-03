<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferralBonus extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    /*
     * User who got bonus
     */
    public function user(){
        return $this->belongsTo(User::class,'from_user_id','id');
    }
    public function bonusBy(){
        return $this->belongsTo(User::class,'to_user_id','id');
    }

    public function getUser() :string
    {
        $url = route('admin.user.view.profile', optional($this->user)->id??1);
        return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                  ' . optional($this->user)->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($this->user)->firstname . ' ' . optional($this->user)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($this->user)->username . '</span>
                                </div>
                              </a>';
    }

    public function getBonsuByUser() :string
    {
        $url = route('admin.user.view.profile', optional($this->bonusBy)->id??1);
        return '<a class="d-flex align-items-center me-2" href="' . $url . '">
                                <div class="flex-shrink-0">
                                  ' . optional($this->bonusBy)->profilePicture() . '
                                </div>
                                <div class="flex-grow-1 ms-3">
                                  <h5 class="text-hover-primary mb-0">' . optional($this->bonusBy)->firstname . ' ' . optional($this->bonusBy)->lastname . '</h5>
                                  <span class="fs-6 text-body">@' . optional($this->bonusBy)->username . '</span>
                                </div>
                              </a>';
    }
}
