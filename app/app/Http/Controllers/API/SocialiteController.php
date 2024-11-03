<?php

namespace App\Http\Controllers\API;

use App\Helpers\UserSystemInfo;
use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\User;
use App\Models\UserLogin;
use App\Traits\ApiResponse;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    use Upload , ApiResponse;

    public function socialiteLogin($socialite)
    {
        $validProviders = ['google', 'facebook', 'github']; // Add more as needed
        if (!in_array($socialite, $validProviders)) {
            return response()->json($this->withError('Invalid social login provider'), 400);
        }

        if (config('socialite.' . $socialite . '_status')) {
            try {
                return Socialite::driver($socialite)->stateless()  // Enable stateless mode
                ->redirect();
            } catch (\Exception $e) {

                return response()->json($this->withError($e->getMessage()), 500);
            }

        }
        return response()->json($this->withError('Unauthorized') , 401);
    }

    public function socialiteCallback($socialite)
    {
        try {
            $user = Socialite::driver($socialite)->stateless()->user();
            $columName = $socialite . '_id';
            $searchUser = User::where($columName, $user->id)->first();

            if ($searchUser) {
                $data['message'] = 'User logged in successfully.';
                $data['token'] = $searchUser->createToken($searchUser->email?$searchUser->email:'fnsdfhsidcnkchnisdhfsdkncvsidhfvdsuihvajsdbv')->plainTextToken;
                return response()->json($this->withSuccess($data));
            } else {
                $languageId = Language::select('id')->where('default_status', 1)->first()->id ?? null;

                $newUser = User::create([
                    'first_name' => $user->name,
                    'last_name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->email,
                    'password' => Hash::make($user->name),
                    $columName => $user->id,
                    'language_id' => $languageId,
                    'email_verification' => (basicControl()->email_verification) ? 0 : 1,
                    'sms_verification' => (basicControl()->sms_verification) ? 0 : 1,
                ]);

                $this->extraWorkWithRegister($newUser);
                return response()->json($this->withSuccess($newUser->createToken('token')->plainTextToken , 'Your Account Created Successfully'));
            }

        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()) , 401);
        }
    }

    public function extraWorkWithRegister($newUser): void
    {
        $newUser->last_login = Carbon::now();
        $newUser->last_seen = Carbon::now();
        $newUser->two_fa_verify = ($newUser->two_fa == 1) ? 0 : 1;
        $newUser->save();

        $info = @json_decode(json_encode(getIpInfo()), true);
        $ul['user_id'] = $newUser->id;

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
