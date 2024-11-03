<?php

namespace App\Http\Controllers\API;

use App\Events\UpdateAdminNotification;
use App\Events\UpdateUserNotification;
use App\Http\Controllers\Controller;
use App\Models\InAppNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function show()
    {
        $siteNotifications = InAppNotification::whereHasMorph(
            'inAppNotificationable',
            [User::class],
            function ($query) {
                $query->where([
                    'in_app_notificationable_id' => Auth::id()
                ]);
            }
        )->latest()->get();
        return response()->json($this->withSuccess($siteNotifications));
    }
    public function readAt($id)
    {
        if (!$id){
            return response()->json($this->withError('id is required'));
        }
        $siteNotification = InAppNotification::find($id);
        if ($siteNotification) {
            $siteNotification->delete();
            if (Auth::guard('admin')->check()) {
                event(new UpdateAdminNotification(Auth::id()));
            }
            else {
                event(new UpdateUserNotification(Auth::id()));
            }
            $data['status'] = true;
        } else {
            $data['status'] = false;
        }
        return response()->json($this->withSuccess($data));
    }
    public function readAll()
    {

        $siteNotification = InAppNotification::whereHasMorph(
            'inAppNotificationable',
            [User::class],
            function ($query) {
                $query->where([
                    'in_app_notificationable_id' => Auth::id()
                ]);
            }
        )->delete();
        if ($siteNotification) {
            event(new UpdateUserNotification(Auth::id()));
        }

        $data['status'] = true;
        return response()->json($this->withSuccess($data));
    }
}
