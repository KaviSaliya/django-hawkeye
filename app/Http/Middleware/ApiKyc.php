<?php

namespace App\Http\Middleware;

use App\Models\Kyc ;
use App\Models\UserKyc;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiKyc
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {$user = Auth::user();

        $kyc = Kyc::where('status',1)->get();
        foreach($kyc as $item){
            $validateForm =  UserKyc::where('user_id', $user->id)->where('kyc_type',$item->name)->get();
            if(!$validateForm->first()){
                return response()->json(['You are not submitted '.$item->name.' form']);
            }else{

                $check = checkKycForm($validateForm);
                if (isset($check['is_pending'])){
                    $message = "Your ". $check['is_pending']." is pending";
                    return response()->json($message);
                }elseif (isset($check['rejected'])){
                    return response()->json("Your ". $check['rejected']." form has been rejected");
                }
            }
        }
        return $next($request);
    }
}
