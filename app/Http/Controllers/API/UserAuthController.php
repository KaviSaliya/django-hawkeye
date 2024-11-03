<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\ApiResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class UserAuthController extends Controller
{
    use ApiResponse;
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('username', 'password');
            $validator = Validator::make($credentials, [
                'username' => 'required|string',
                'password' => 'required|string',
            ]);
            if ($validator->fails()) {
                return response()->json($this->withError(collect($validator->errors())->collapse()));
            }
            $user = User::where('email', $credentials['username'])
                ->orWhere('username', $credentials['username'])
                ->first();
            if (!$user || !Hash::check($credentials['password'], $user->password)) {
                return response()->json($this->withError('credentials do not match'));
            }
            $data['message'] = 'User logged in successfully.';
            $data['token'] = $user->createToken($user->email)->plainTextToken;
            return response()->json($this->withSuccess($data));
        }catch (\Exception $exception){
            return response()->json($this->withError($exception->getMessage()));
        }
    }

    public function register(Request $request)
    {
        $basic = basicControl();
        $data = $request->all();
        $registerRules = [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'username' => 'required|string|alpha_dash|min:5|unique:users,username',
            'email' => 'required|string|email|unique:users,email',
            'password' => $basic->strong_password == 0 ?
                ['required', 'confirmed', 'min:6'] :
                ['required', 'confirmed',  Password::min(6)->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()],
            'phone' => ['required', 'numeric', 'unique:users,phone'],
            'phone_code' => ['required', 'numeric'],
            'country' => ['required'],
            'country_code' => ['required']

        ];
        $message = [
            'password.letters' => 'password must be contain letters',
            'password.mixed' => 'password must be contain 1 uppercase and lowercase character',
            'password.symbols' => 'password must be contain symbols',
        ];
        $validation = Validator::make($request->all(), $registerRules,$message);
        if ($validation->fails()) {
            return response()->json($this->withError(collect($validation->errors())->collapse()));
        }
        $user =  User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'phone_code' => $data['phone_code'],
            'country' => $data['country'],
            'country_code' => $data['country_code'],
            'password' => Hash::make($data['password']),
            'email_verification' => ($basic->email_verification) ? 0 : 1,
            'sms_verification' => ($basic->sms_verification) ? 0 : 1
        ]);
        return response()->json($this->withSuccess($user->createToken('token')->plainTextToken , 'Your Account Created Successfully'));
    }

    public function logout()
    {

        $user = Auth::user();

        // Revoke all tokens...
        $user->tokens()->delete();

        return response()->json($this->withSuccess('Logged out successfully'));
    }

    public function getEmailForRecoverPass(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withError(collect($validateUser->errors())->collapse()));
        }

        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json($this->withError('Email does not exit on record'));
            }

            $code = rand(10000, 99999);
            $data['email'] = $request->email;
            $data['message'] = 'OTP has been send';
            $user->verify_code = $code;
            $user->save();

            $basic = basicControl();
            $message = 'Your Password Recovery Code is ' . $code;
            $email_from = $basic->sender_email;
            @Mail::to($user)->queue(new SendMail($email_from, "Recovery Code", $message));

            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function getCodeForRecoverPass(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'code' => 'required',
                'email' => 'required|email',
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withError(collect($validateUser->errors())->collapse()));
        }

        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json($this->withError('Email does not exit on record'));
            }

            if ($user->verify_code == $request->code && $user->updated_at > Carbon::now()->subMinutes(5)) {
                $user->verify_code = null;
                $user->save();
                return response()->json($this->withSuccess('Code Matching'));
            }

            return response()->json($this->withError('Invalid Code'));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function updatePass(Request $request)
    {
        $basic = basicControl();
        $rules = [
            'email' => 'required|email|exists:users,email',
            'password' => $basic->strong_password == 0 ?
                ['required', 'confirmed', 'min:6'] :
                ['required', 'confirmed', $this->strongPassword()],
            'password_confirmation' => 'required| min:6',
        ];
        $message = [
            'email.exists' => 'Email does not exist on record'
        ];
        $validateUser = Validator::make($request->all(), $rules,$message);
        if ($validateUser->fails()) {
            return response()->json($this->withError(collect($validateUser->errors())->collapse()));
        }
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->json($this->withSuccess('Password Updated'));
    }
}
