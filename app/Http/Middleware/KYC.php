<?php

namespace App\Http\Middleware;

use App\Models\Kyc as KYCModel;
use App\Models\UserKyc;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class KYC
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        $kyc = KYCModel::where('status',1)->get();
        foreach($kyc as $item){
            $validateForm =  UserKyc::where('user_id', $user->id)->where('kyc_type',$item->name)->get();
            if(!$validateForm->first()){
                return redirect()->route('user.profile')->with(['warning'=>'You are not submitted '.$item->name.' form','kycForm' => $item->name]);
            }else{

                $check = checkKycForm($validateForm);
                if (isset($check['is_pending'])){
                    return back()->with('warning',"Your ". $check['is_pending']." is pending");
                }elseif (isset($check['rejected'])){
                    return back()->with('warning',"Your ". $check['rejected']." form has been rejected");
                }
            }
        }
        return $next($request);
    }


}
