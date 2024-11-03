<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Helpers\UserSystemInfo;
use App\Models\User;
use App\Traits\Notify;
use Illuminate\Support\Facades\Hash;
use PragmaRX\Google2FA\Google2FA;

class TwoStepSecurityController extends Controller
{
    use Notify;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function twoStepSecurity()
    {
        $pageSeo = Page::where('slug','twofasecurity')->select('page_title','breadcrumb_image','breadcrumb_image_driver','breadcrumb_status')->first();
        $basic = basicControl();
        $user = auth()->user();

        $google2fa = new Google2FA();
        $secret = $user->two_fa_code ?? $this->generateSecretKeyForUser($user);

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            auth()->user()->username,
            $basic->site_title,
            $secret
        );



        return view($this->theme . 'user.twoFA.index', compact('secret', 'qrCodeUrl','pageSeo'));
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
        $request->validate([
            'code' => 'required',
        ]);
        $user = $this->user;
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

            return back()->with('success', 'Google Authenticator Has Been Enabled.');
        } else {

            return back()->with('error', 'Wrong Verification Code.');
        }


    }

    public function twoStepDisable(Request $request)
    {
        $this->validate($request, [
            'password' => 'required',
        ]);

        if (!Hash::check($request->password, auth()->user()->password)) {
            return back()->with('error', 'Incorrect password. Please try again.');
        }

        // Disable two-factor authentication for the user
        auth()->user()->update([
            'two_fa' => 0,
            'two_fa_verify' => 1,
            //   'two_fa_code' => null, // Optionally clear the stored secret
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Two-step authentication disabled successfully.');
    }

    public function twoStepRegenerate()
    {
        $user = $this->user;
        $user->two_fa_code = null;
        $user->save();
        session()->flash('success','Re-generate Successfully');
        return redirect()->route('user.twostep.security');
    }
}
