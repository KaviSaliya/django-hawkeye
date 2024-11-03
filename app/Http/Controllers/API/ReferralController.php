<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ReferralBonus;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReferralController extends Controller
{
    use ApiResponse;
    public function referralUsers()
    {
        $userId = Auth::id();
        $directReferralUsers = getDirectReferralUsers($userId);
        return $this->jsonSuccess($directReferralUsers);
    }

    public function getReferralUser(Request $request)
    {
        $rules = [
            'userId' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->jsonError(collect($validator->errors())->collapse());
        }

        $data = getDirectReferralUsers($request->userId);
        $directReferralUsers = $data->map(function ($user) {
            return [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'count_direct_referral' => count(getDirectReferralUsers($user->id)),
                'joined_at' => dateTime($user->created_at),
            ];
        });

        return $this->jsonSuccess($directReferralUsers);
    }

    public function referralBonusHistory(Request $request)
    {
        $remark = $request->remark;
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $commission_type = $request->type;


        $referrals = ReferralBonus::query()->with(['user:id,username,firstname,lastname,image,image_driver'])
            ->where('from_user_id', Auth::id())
            ->orderBy('created_at','desc')
            ->when(!empty($request->date_range) && $endDate == null, function ($query) use ($startDate) {
                $startDate = Carbon::parse(trim($startDate))->startOfDay();
                $query->whereDate('created_at', $startDate);
            })
            ->when(!empty($request->date_range) && $endDate != null, function ($query) use ($startDate, $endDate) {
                $startDate = Carbon::parse(trim($startDate))->startOfDay();
                $endDate = Carbon::parse(trim($endDate))->endOfDay();
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->when(!empty($remark), function ($query) use ($remark) {
                return $query->where('remarks', $remark);
            })
            ->when(!empty($commission_type),function ($query) use ($commission_type){
                return $query->where('commission_type', $commission_type);
            })
            ->paginate(15);
        return $this->jsonSuccess($referrals);
    }
}
