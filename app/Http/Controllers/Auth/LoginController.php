<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\UserSystemInfo;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\UserLogin;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Facades\App\Services\Google\GoogleRecaptchaService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->theme = template();
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        $pageSeo = Page::where('slug', 'login')->first();
        $pageSeo->meta_keywords = isset($pageSeo->meta_keywords) && $pageSeo->meta_keywords?implode(",", $pageSeo->meta_keywords):'';
        $pageSeo->image = getFile($pageSeo->meta_image_driver, $pageSeo->meta_image);
        return view($this->theme . 'auth/login',compact('pageSeo'));
    }


    public function username()
    {
        $login = request()->input('username');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $login]);
        return $field;
    }

    public function login(Request $request)
    {
        $basicControl = basicControl();

        $validator = Validator::make($request->all(), [
            $this->username() => ['required', 'string'],
            'password' => ['required', 'string', 'max:100'],
            'captcha' => [
                Rule::requiredIf(function () use ($basicControl) {
                    return $basicControl->manual_recaptcha == 1 && $basicControl->reCaptcha_status_login == 1;
                }),
                function ($attribute, $value, $fail) use ($basicControl) {
                    if ($basicControl->manual_recaptcha == 1 && $basicControl->reCaptcha_status_login == 1) {
                        if (!$value) {
                            $fail('The captcha field is required.');
                        } else {
                            if (session()->get('captcha') !== $value) {
                                $fail('The captcha does not match.');
                            }
                        }
                    }
                },
            ],
            'g-recaptcha-response' => [
                Rule::requiredIf(function () use ($basicControl) {
                    return $basicControl->google_user_login_recaptcha_status && $basicControl->google_recaptcha == 1;
                }),
                function ($attribute, $value, $fail) use ($basicControl) {
                    if ($basicControl->google_user_login_recaptcha_status && $basicControl->google_recaptcha == 1) {
                        if (!$value) {
                            $fail('The reCAPTCHA field is required.');
                        }
                        // Optionally, you can add the code to verify Google's reCAPTCHA response here
                    }
                },
            ],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }



        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        if ($this->guard()->validate($this->credentials($request))) {
            if (Auth::attempt([$this->username() => $request->username, 'password' => $request->password])) {
                return $this->sendLoginResponse($request);
            } else {
                return back()->with('error', 'You are banned from this application. Please contact with system Administrator.');
            }
        }
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }


    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard('admin')->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
    }

    /**
     * The user has been authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @param mixed $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        $user->last_login = Carbon::now();
        $user->last_seen = Carbon::now();
        $user->two_fa_verify = ($user->two_fa == 1) ? 0 : 1;
        $user->save();

        $info = @json_decode(json_encode(getIpInfo()), true);
        $ul['user_id'] = $user->id;

        $ul['longitude'] = (!empty(@$info['long'])) ? implode(',', $info['long']) : null;
        $ul['latitude'] = (!empty(@$info['lat'])) ? implode(',', $info['lat']) : null;
        $ul['country_code'] = (!empty(@$info['code'])) ? implode(',', $info['code']) : null;
        $ul['location'] = (!empty(@$info['city'])) ? implode(',', $info['city']) . (" - " . @implode(',', @$info['area']) . "- ") . @implode(',', $info['country']) . (" - " . @implode(',', $info['code']) . " ") : null;
        $ul['country'] = (!empty(@$info['country'])) ? @implode(',', @$info['country']) : null;

        $ul['ip_address'] = UserSystemInfo::get_ip();
        $ul['browser'] = UserSystemInfo::get_browsers();
        $ul['os'] = UserSystemInfo::get_os();
        $ul['get_device'] = UserSystemInfo::get_device();

        UserLogin::create($ul);

    }


}
