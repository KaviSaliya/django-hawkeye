<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\LanguageResource;
use App\Http\Resources\UserResource;
use App\Models\Language;
use App\Models\Transaction;
use App\Rules\PhoneLength;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use ApiResponse;

    public function profile()
    {
        $languages = Language::where('status',1)->get();
        $data = [
            'languages' => LanguageResource::collection($languages),
            'profile' => new UserResource(Auth::user())
        ];

        return $this->jsonSuccess($data);
    }

    public function updateProfile(Request $request)
    {
        $languages = Language::all()->map(function ($item) {
            return $item->id;
        });
        $user = Auth::user();
        $phoneCode = $request->phone_code;
        $rules = [
            'firstname' => ['required'],
            'lastname' => ['required'],
            'email' => ['required'],
            'username' => ['required'],
            'address' => ['required'],
            'phone' => ['required', 'string', new PhoneLength($phoneCode),Rule::unique('users', 'phone')->ignore($user->id)],
            'phone_code' => 'required | max:15',
            'country_code' => 'required | string | max:80',
            'country' => 'required | string | max:80',
            'language' => Rule::in($languages),
            'image' => ['nullable','max:3072','image','mimes:jpg,jpeg,png']
        ];

        $validation = Validator::make($request->all(), $rules);
        if ($validation->fails()) {
            return response()->json($this->withError(collect($validation->errors())->collapse()));
        }

        if ($request->hasFile('image')) {
            $image = $this->fileUpload($request->image, config('filelocation.userProfile.path'), null, null, 'avif', 60, $user->image, $user->image_driver);
            if ($image) {
                $profileImage = $image['path'];
                $ImageDriver = $image['driver'];
            }
        }

        $user->firstname =  $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->address_one =  $request->address;
        $user->phone = $request->phone;
        $user->phone_code = $request->phone_code;
        $user->country = $request->country;
        $user->country_code = $request->country_code;
        $user->language_id =  $request->language;
        $user->image = $profileImage ?? $user->image;
        $user->image_driver = $ImageDriver ?? $user->image_driver;
        $user->save();

        return response()->json($this->withSuccess('Profile updated successfully.'));

    }

    public function changePassword(Request $request){
        $rules = [
            'current_password' => "required",
            'password' => "required|min:5|confirmed",
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($this->withError(collect($validator->errors())->collapse()));
        }
        $user = Auth::user();
        try {
            if (Hash::check($request->current_password, $user->password)) {
                $user->password = bcrypt($request->password);
                $user->save();
                return response()->json($this->withSuccess('Password updated successfully.'));
            } else {
                throw new \Exception('Current password did not match');
            }
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function transactions(Request $request)
    {

        $fromDate = $request->from_date;
        $toDate = $request->to_date;

        $transactions = Transaction::where('user_id', Auth::id())
            ->when($request->trx_id , function ($query) use ($request){
                $query->where('trx_id',$request->trx_id);
            })
            ->when($request->remark , function ($query) use ($request){
                $query->where('remarks','LIKE','%'.$request->remark.'%');
            })
            ->when($fromDate && $toDate , function ($query) use ($fromDate ,$toDate){
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->select('id','amount','charge','trx_type','trx_id','remarks','created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        return $this->jsonSuccess($transactions);

    }
}
