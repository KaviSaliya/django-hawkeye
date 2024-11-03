<?php

namespace App\Http\Controllers\API;

use App\Helpers\GoogleAuthenticator;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VerificationController extends Controller
{
    use ApiResponse,Notify,Upload;
    public function check()
    {
        $user = Auth::user();
        if (!$user->status) {
            Auth::guard('web')->logout();
        } elseif (!$user->email_verification) {
            if (!$this->checkValidCode($user, $user->verify_code)) {
                $user->verify_code = code(6);
                $user->sent_at = Carbon::now();
                $user->save();
                $this->verifyToMail($user, 'VERIFICATION_CODE', [
                    'code' => $user->verify_code
                ]);
                return  response()->json($this->withSuccess('Email verification code has been sent'));
            }
            return  response()->json($this->withSuccess('Please Enter Email Verification Code'));

        } elseif (!$user->sms_verification) {
            if (!$this->checkValidCode($user, $user->verify_code)) {
                $user->verify_code = code(6);
                $user->sent_at = Carbon::now();
                $user->save();

                $this->verifyToSms($user, 'VERIFICATION_CODE', [
                    'code' => $user->verify_code
                ]);
                return  response()->json($this->withSuccess('SMS verification code has been sent'));

            }
            return  response()->json($this->withSuccess('Please Enter SMS Verification Code'));
        } elseif (!$user->two_fa_verify) {
            return  response()->json($this->withSuccess('Please Enter Two fa verification code'));

        }
        return response()->json($this->withSuccess(true));
    }

    public function checkValidCode($user, $code, $add_min = 10000)
    {
        if (!$code) return false;
        if (!$user->sent_at) return false;
        if (Carbon::parse($user->sent_at)->addMinutes($add_min) < Carbon::now()) return false;
        if ($user->verify_code !== $code) return false;
        return true;
    }


    public function resendCode()
    {
        try {
            $type = request()->type;
            $user = Auth::user();
            if ($this->checkValidCode($user, $user->verify_code, 2)) {
                $target_time = $user->sent_at->addMinutes(2)->timestamp;
                $delay = $target_time - time();

                return response()->json($this->withError('Please Try after ' . gmdate("i:s", $delay) . ' minutes'));
            }
            if (!$this->checkValidCode($user, $user->verify_code)) {
                $user->verify_code = code(6);
                $user->sent_at = Carbon::now();
                $user->save();
            } else {
                $user->sent_at = Carbon::now();
                $user->save();
            }

            if ($type === 'email') {
                $this->verifyToMail($user, 'VERIFICATION_CODE', [
                    'code' => $user->verify_code
                ]);

                return response()->json($this->withSuccess([],'Email verification code has been sent'));
            } elseif ($type === 'mobile') {
                $this->verifyToSms($user, 'VERIFICATION_CODE', [
                    'code' => $user->verify_code
                ]);
                return response()->json($this->withSuccess([],'SMS verification code has been sent'));
            } else {
                return response()->json($this->withError('Sending Failed'));
            }
        }catch (\Exception $e){
            return response()->json($this->withSuccess(['error' => $e->getMessage()]));
        }
    }

    public function mailVerify(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'code' => 'required',
            ],
            [
                'code.required' => 'Email verification code is required',
            ]
        );
        if ($validateUser->fails()) {
            return response()->json($this->withError(collect($validateUser->errors())->collapse()));
        }

        try {
            $user = $this->user;
            if ($this->checkValidCode($user, $request->code)) {
                $user->email_verification = 1;
                $user->verify_code = null;
                $user->sent_at = null;
                $user->save();
                return response()->json($this->withSuccess([],'Verified Successfully.'));
            }
            return response()->json($this->withError('Verification code didn\'t match!'));
        }catch (\Exception $e){
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function smsVerify(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'code' => 'required',
            ],
            [
                'code.required' => 'Sms verification code is required',
            ]
        );

        if ($validateUser->fails()) {
            return response()->json($this->withError(collect($validateUser->errors())->collapse()));
        }
        try {
            $user = Auth::user();
            if ($this->checkValidCode($user, $request->code)) {
                $user->sms_verification = 1;
                $user->verify_code = null;
                $user->sent_at = null;
                $user->save();

                return response()->json($this->withSuccess('Verified Successfully.'));
            }
            return response()->json($this->withError('Verification code didn\'t match!'));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function twoFAverify(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'code' => 'required',
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withError(collect($validateUser->errors())->collapse()));
        }

        try {
            $ga = new GoogleAuthenticator();
            $user = Auth::user();
            $getCode = $ga->getCode($user->two_fa_code);
            if ($getCode == trim($request->code)) {
                $user->two_fa_verify = 1;
                $user->save();
                return response()->json($this->withSuccess('Verified Successfully.'));
            }
            return response()->json($this->withError('Wrong Verification Code.'));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }

    }
}
