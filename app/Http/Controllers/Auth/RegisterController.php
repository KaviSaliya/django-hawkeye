<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\UserSystemInfo;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\User;
use App\Models\UserLogin;
use App\Rules\PhoneLength;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Facades\App\Services\Google\GoogleRecaptchaService;
use Facades\App\Services\BasicService;
use App\Traits\Notify;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers,Notify;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/user/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->theme = template();
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm(Request $request)
    {
        $referUser = null;
       if ($request->ref){
           $referUser =  User::where('id', $request->ref)->first();
           if ($referUser){
               session()->put('referral', $referUser->id);
           }
       }


        $pageSeo = Page::where('slug', 'register')->first();
        $pageSeo->meta_keywords = isset($pageSeo->meta_keywords) && $pageSeo->meta_keywords? implode(",", $pageSeo->meta_keywords):'';
        $pageSeo->image = getFile($pageSeo->meta_image_driver, $pageSeo->meta_image);
        $basic = basicControl();
        if ($basic->registration == 0) {
            return redirect('/')->with('warning', 'Registration Has Been Disabled.');
        }
        return view(template() . 'auth.register',compact('pageSeo','referUser'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $phoneCode = $data['phone_code'];

        $basicControl = basicControl();
        if ($basicControl->strong_password == 0) {
            $rules['password'] = ['required', 'min:6', 'confirmed'];
        } else {
            $rules['password'] = ["required", 'confirmed',
                Password::min(6)->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()];
        }


        if ($basicControl->google_recaptcha &&basicControl()->google_user_registration_recaptcha_status) {
            $rules['g-recaptcha-response'] = ['sometimes', 'required'];
        }
        if ($basicControl->manual_recaptcha && basicControl()->reCaptcha_status_registration){
            $rules['captcha'] = ['required',function($attribute, $value, $fail){
                if ($value !== session()->get('captcha')){
                    $fail('The captcha does not match');
                }
            }];
        }

        $rules['first_name'] = ['required', 'string', 'max:91'];
        $rules['last_name'] = ['required', 'string', 'max:91'];
        $rules['username'] = ['required', 'alpha_dash', 'min:5', 'unique:users,username'];
        $rules['email'] = ['required', 'string', 'email', 'max:255',  'unique:users,email'];
        $rules['phone'] = ['required', 'string'];
        $rules['phone_code'] = ['required', 'max:15'];
        $rules['country'] = ['required', 'string', 'max:80'];
        $rules['country_code'] = ['required', 'string', 'max:80'];

        return Validator::make($data, $rules, [
            'first_name.required' => 'First Name Field is required',
            'last_name.required' => 'Last Name Field is required',
            'g-recaptcha-response.required' => 'The reCAPTCHA field is required.',
        ]);
    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $referUser = session()->get('referral');
        $basic = basicControl();
        return User::create([
            'firstname' => $data['first_name'],
            'lastname' => $data['last_name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verification' => ($basic->email_verification) ? 0 : 1,
            'sms_verification' => ($basic->sms_verification) ? 0 : 1,
            'referral_id' => $referUser??null,
            'phone' => $data['phone'],
            'phone_code' => $data['phone_code'],
            'country_code' => strtoupper($data['country_code']),
            'country' => $data['country'],

        ]);
    }

    public function register(Request $request)
    {

        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        session()->forget('referral');


        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        if ($request->ajax()) {
            return route('user.home');
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    protected function registered(Request $request, $user)
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
        $this->sendWelcomeNotification($user);
    }

    public function sendWelcomeNotification($user)
    {
        $params = [
            'username' => $user->username,

        ];
        $action = [
            'link' => "#",
            'icon' => 'fa fa-money-bill-alt text-white'
        ];
        $firebaseAction = "#";
        $this->userPushNotification($user, 'USER_REGISTRATION', $params, $action);
        $this->userFirebasePushNotification($user, 'USER_REGISTRATION', $params, $firebaseAction);
        $this->sendMailSms($user, 'USER_REGISTRATION', $params);
    }

    protected function guard()
    {
        return Auth::guard();
    }

}
