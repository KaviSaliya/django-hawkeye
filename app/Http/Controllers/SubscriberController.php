<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller
{
   public function subscribe(Request $request)
   {
       $validator = Validator::make($request->all(), [
           'email' => [
               'required',
               'email',
               'unique:subscribers,email'
           ],
       ],
           [
               'email.required' => 'The email field is required.',
               'email.email' => 'The email must be a valid email address.',
               'email.unique' => 'You have already subscribed to this email.',
           ]);

       if ($validator->fails()) {
          return back()->with('error', $validator->messages()->first());
       }

       $subscribe = new Subscriber();
       $subscribe->email = $request->email;
       $subscribe->save();
       return back()->with('success', 'You have been subscribed');

   }
}
