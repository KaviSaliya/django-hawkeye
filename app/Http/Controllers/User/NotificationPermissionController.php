<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\NotificationPermission;
use App\Models\NotificationTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationPermissionController extends Controller
{
    public function index()
    {
        try {
            $user = User::with('notifypermission')->findOrFail(Auth::id());
            $allTemplates = NotificationTemplate::where('notify_for', 0)
                ->get();
//            dd($allTemplates);

            return view(template().'user.notification.permission',compact('allTemplates','user'));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function notifyPermissionUpdate(Request $request)
    {
        try {
            $user = Auth::user();
            $userTemplate = NotificationPermission::where('notifyable_id', $user->id)
                ->where('notifyable_type', User::class)
                ->first();
            if ($userTemplate) {
                $userTemplate->template_email_key = $request->email_key;
                $userTemplate->template_in_app_key = $request->in_app_key;
                $userTemplate->template_push_key = $request->push_key;
                $userTemplate->template_sms_key = $request->sms_key;
                $userTemplate->save();
            }else{
                NotificationPermission::create([
                    'notifyable_id' => $user->id,
                    'notifyable_type' => User::class,
                    'template_email_key' => $request->email_key,
                    'template_in_app_key' => $request->in_app_key,
                    'template_push_key' => $request->push_key,
                    'template_sms_key' => $request->sms_key,

                ]);
            }

            return back()->with('success', 'Notification Permission Updated Successfully.');
        } catch (\Exception $e) {
            dd( $e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }
}
