<?php

namespace App\Http\Controllers\API;

use App\Helpers\UserSystemInfo;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PragmaRX\Google2FA\Google2FA;

class TwoFAVerificationController extends Controller
{
    use ApiResponse;
    public function twoStepSecurity()
    {
        $basic = basicControl();
        $user = auth()->user();

        $google2fa = new Google2FA();
        $data['secret'] = $user->two_fa_code ?? $this->generateSecretKeyForUser($user);

        $data['qrCodeUrl'] = $google2fa->getQRCodeUrl(
            auth()->user()->username,
            $basic->site_title,
            $data['secret'],

        );
        $data['towFactorEnable'] = $user->two_fa == 1?true:false;
        $data['downloadApp'] = 'https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en';
        $data['iosApp'] = 'https://apps.apple.com/us/app/google-authenticator/id388497605';
        return response()->json($this->withSuccess($data));
    }

    private function generateSecretKeyForUser(User $user)
    {
        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        $user->update(['two_fa_code' => $secret]);

        return $secret;
    }


    public function twoStepEnable(Request $request)
    {

        $rules = [
            'code' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($this->withError(collect($validator->errors())->collapse()));
        }
        $user = Auth::user();
        $secret = auth()->user()->two_fa_code;
        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($secret, $request->code);
        if ($valid) {
            $user['two_fa'] = 1;
            $user['two_fa_verify'] = 1;
            $user->save();

            $this->mail($user, 'TWO_STEP_ENABLED', [
                'action' => 'Enabled',
                'code' => $request->code,
                'ip' => request()->ip(),
                'browser' => UserSystemInfo::get_browsers() . ', ' . UserSystemInfo::get_os(),
                'time' => date('d M, Y h:i:s A'),
            ]);

            return response()->json($this->withSuccess('Google Authenticator Has Been Enabled.'));
        } else {

            return response()->json($this->withError('Wrong Verification Code'));
        }


    }

    public function twoStepDisable(Request $request)
    {
        $rules = [
            'password' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($this->withError(collect($validator->errors())->collapse()));
        }

        if (!Hash::check($request->password, auth()->user()->password)) {
            return response()->json($this->withError('Incorrect password. Please try again.'));
        }

        // Disable two-factor authentication for the user
        auth()->user()->update([
            'two_fa' => 0,
            'two_fa_verify' => 1,
            //   'two_fa_code' => null, // Optionally clear the stored secret
        ]);

        return response()->json($this->withSuccess('Two-step authentication disabled successfully.'));
    }

    public function twoStepRegenerate()
    {
        $basic = basicControl();
        $user = Auth::user();
        $user->two_fa_code = null;
        $user->save();

        $google2fa = new Google2FA();
        $data['secret'] =  $this->generateSecretKeyForUser($user);

        $data['qrCodeUrl'] = $google2fa->getQRCodeUrl(
            $user->username,
            $basic->site_title,
            $data['secret']
        );
        return response()->json($this->withSuccess($data,'Re-generate Successfully'));
    }
}
