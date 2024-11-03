<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserKycResource;
use App\Models\Kyc;
use App\Models\UserKyc;
use App\Traits\ApiResponse;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class KycController extends Controller
{
    use ApiResponse , Upload;
   public function index()
   {
       $allKyc = Kyc::with('userKyc')->where('status', 1)->get();
      return $this->jsonSuccess($allKyc);
   }

   public function submitKyc(Request $request)
   {

       $validator = Validator::make($request->all(), [
           'kyc_id' => 'required',
       ]);
       if ($validator->fails()) {
           return $this->jsonError(collect($validator->errors())->collapse(),400);
       }
       $kyc = Kyc::where('id', $request->kyc_id)->where('status', 1)->first();
       if (!$kyc){
           return  $this->jsonError("KYC not found");
       }

       $params = $kyc->input_form;
       $reqData = $request->except('_token', '_method');
       $rules = [];
       if ($params !== null) {
           foreach ($params as $key => $cus) {
               $rules[$key] = [$cus->validation == 'required' ? $cus->validation : 'nullable'];
               if ($cus->type === 'file') {
                   $rules[$key][] = 'image';
                   $rules[$key][] = 'mimes:jpeg,jpg,png';
                   $rules[$key][] = 'max:2048';
               } elseif ($cus->type === 'text') {
                   $rules[$key][] = 'max:191';
               } elseif ($cus->type === 'number') {
                   $rules[$key][] = 'integer';
               } elseif ($cus->type === 'textarea') {
                   $rules[$key][] = 'min:3';
                   $rules[$key][] = 'max:300';
               }
           }
       }



       $params = $kyc->input_form;
       $validator = Validator::make($reqData, $rules);
       if ($validator->fails()) {
           return $this->jsonError(collect($validator->errors()),400);
       }

       $reqField = [];
       foreach ($request->except('_token', '_method', 'type') as $k => $v) {
           foreach ($params as $inKey => $inVal) {
               if ($k == $inKey) {
                   if ($inVal->type == 'file' && $request->hasFile($inKey)) {
                       try {
                           $file = $this->fileUpload($request[$inKey], config('filelocation.kyc.path'),null,null,'webp',60);
                           $reqField[$inKey] = [
                               'field_name' => $inVal->field_name,
                               'field_label' => $inVal->field_label,
                               'field_value' => $file['path'],
                               'field_driver' => $file['driver'],
                               'validation' => $inVal->validation,
                               'type' => $inVal->type,
                           ];
                       } catch (\Exception $exp) {
                          return $this->jsonError('Could not upload your ' . $inKey,400);
                       }
                   } else {
                       $reqField[$inKey] = [
                           'field_name' => $inVal->field_name,
                           'field_label' => $inVal->field_label,
                           'validation' => $inVal->validation,
                           'field_value' => $v,
                           'type' => $inVal->type,
                       ];
                   }
               }
           }
       }

       UserKyc::create([
           'user_id' => auth()->id(),
           'kyc_id' => $kyc->id,
           'kyc_type' => $kyc->name,
           'kyc_info' => $reqField
       ]);

       return $this->jsonSuccess("KYC submitted successfully");
   }

   public function userKyc()
   {
       $userKycs = UserKyc::where('user_id',Auth::user()->id)->get();
       return $this->jsonSuccess(UserKycResource::collection($userKycs));
   }
}
