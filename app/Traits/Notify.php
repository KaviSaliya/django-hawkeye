<?php

namespace App\Traits;

use App\Events\AdminNotification;
use App\Events\UserNotification;
use App\Mail\SendMail;
use App\Models\Admin;
use App\Models\FireBaseToken;
use App\Models\InAppNotification;
use App\Models\ManualSmsConfig;
use App\Models\NotificationPermission;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Facades\App\Services\BasicCurl;
use Facades\App\Services\SMS\BaseSmsService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

trait Notify
{
    public function sendMailSms($user, $templateKey, $params = [], $subject = null, $requestMessage = null)
    {
        $this->mail($user, $templateKey, $params, $subject, $requestMessage);
        $this->sms($user, $templateKey, $params, $requestMessage = null);
    }

    public function mail($user, $templateKey = null, $params = [], $subject = null, $requestMessage = null)
    {
        $notificationPermission = NotificationPermission::where('notifyable_id', $user->id)->first();

        try {
            if ($notificationPermission && $notificationPermission->template_email_key) {
                if (!in_array($templateKey, $notificationPermission->template_email_key)) {
                    return false;
                }
            }

            $basic = basicControl();
            $email_body = $basic->email_description;

            $templateObj = NotificationTemplate::where('template_key', $templateKey)
                ->where('language_id', $user->language_id)
                ->where('notify_for', 0)
                ->first();
            if (!$templateObj) {
                $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 0)->first();
            }

            $message = str_replace('[[name]]', $user->username, $email_body);

            if (!$templateObj && $subject == null) {
                return false;
            } else {
                if ($templateObj) {
                    $message = str_replace('[[message]]', $templateObj->email, $message);
                    if (empty($message)) {
                        $message = $email_body;
                    }
                    foreach ($params as $code => $value) {
                        $message = str_replace('[[' . $code . ']]', $value, $message);
                    }
                } else {
                    $message = str_replace('[[message]]', $requestMessage, $message);
                }
            }

            $subject = $subject == null ? $templateObj->subject : $subject;
            $email_from = $basic->sender_email;

            Mail::to($user)->queue(new SendMail($email_from, $subject, $message));
            Artisan::call('queue:work', ['--stop-when-empty' => true]);
        } catch (\Exception $exception) {
            return true;
        }
    }

    public function sms($user, $templateKey, $params = [], $requestMessage = null)
    {
        $basic = basicControl();
        if ($basic->sms_notification != 1) {
            return false;
        }

        $notificationPermission = NotificationPermission::where('notifyable_id', $user->id)->first();
        if ($notificationPermission && !in_array($templateKey, $notificationPermission->template_sms_key)) {
            return false;
        }

        $smsControl = ManualSmsConfig::firstOrCreate();

        $templateObj = NotificationTemplate::where('template_key', $templateKey)
            ->where('language_id', $user->language_id)
            ->where('notify_for', 0)
            ->first();
        if (!$templateObj) {
            $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 0)->first();
        }
        if (!$templateObj) {
            return 0;
        }

        if (!$templateObj->status['sms']) {
            return false;
        }

        if (!$templateObj && $requestMessage == null) {
            return false;
        } else {
            if ($templateObj) {
                $template = $templateObj->sms;
                foreach ($params as $code => $value) {
                    $template = str_replace('[[' . $code . ']]', $value, $template);
                }
            } else {
                $template = $requestMessage;
            }
        }
        
        if (config('SMSConfig.default') == 'manual') {
            // Prepare headers
            $headerData = [
                "Authorization: Bearer 99|F0kn7khJdyJSssyS0yLkNGqLo6n5rhrsukcYHLdk8b05aebf", // Replace with your actual API key
                "Content-Type: application/json",
                "Accept: application/json"
            ];
        
            // Prepare form data
            $formData = is_null($smsControl->form_data) ? [] : json_decode($smsControl->form_data, true);
        
            // Replace placeholders with actual values
            $formData = recursive_array_replace('[[receiver]]', $user->phone, recursive_array_replace('[[message]]', $template, $formData));
        
            // Set up the data for the SMS gateway
            $data = [
                "recipient" => $formData['recipient'], // Use the recipient phone number
                "sender_id" => $formData['sender_id'], // Sender ID
                "type" => $formData['type'],           // Message type (e.g., "plain")
                "message" => $formData['message']      // Message content
            ];
        
            // Encode the data as JSON
            $jsonData = json_encode($data);
        
            // Initialize cURL request
            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://app.text.lk/api/v3/sms/send", // SMS gateway URL
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $jsonData,
                CURLOPT_HTTPHEADER => $headerData,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            ]);
        
            // Execute the request and close cURL
            $response = curl_exec($curl);
            curl_close($curl);
        
            return $response;
        }

        else if (config('SMSConfig.default') == 'manual' && false) {
            $headerData = is_null($smsControl->header_data) ? [] : json_decode($smsControl->header_data, true);
            $paramData = is_null($smsControl->header_data) ? [] : json_decode($smsControl->header_data, true);
            $paramData = http_build_query($paramData);
            $actionUrl = $smsControl->action_url;
            $actionMethod = $smsControl->action_method;
            $formData = is_null($smsControl->form_data) ? [] : json_decode($smsControl->form_data, true);
            
            $queryString = 'FUN=' . $formData['FUN'] . '&with_get=' . $formData['with_get'] . '&un=' . $formData['un'] . '&up=' . $formData['up'] . '&senderID=' . $formData['senderID'] . '&msg=' . urlencode($formData['msg']) . '&to=' . $formData['to'];
            $fullUrl = $actionUrl . '?' . $queryString;

            $formData = recursive_array_replace('[[receiver]]', $user->phone, recursive_array_replace('[[message]]', $template, $formData));
            $formData = isset($headerData['Content-Type']) && $headerData['Content-Type'] == 'application/x-www-form-urlencoded' ? http_build_query($formData) : (isset($headerData['Content-Type']) && $headerData['Content-Type'] == 'application/json' ? json_encode($formData) : $formData);

            foreach ($headerData as $key => $data) {
                $headerData[] = "{$key}:$data";
            }

            if ($actionMethod == 'GET') {
                $actionUrl = $fullUrl;
            }


            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $actionUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $actionMethod,
                CURLOPT_POSTFIELDS => $formData,
                CURLOPT_HTTPHEADER => $headerData,
            ]);

            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } else {
            BaseSmsService::sendSMS($user->phone_code . $user->phone, $template);
            return true;
        }
    }

    public function verifyToMail($user, $templateKey = null, $params = [], $subject = null, $requestMessage = null)
    {
        $basic = basicControl();

        if ($basic->email_verification != 1) {
            return false;
        }

        $email_body = $basic->email_description;
        $templateObj =
            NotificationTemplate::where('template_key', $templateKey)
                ->where('language_id', $user->language_id)
                ->first() ?? NotificationTemplate::where('template_key', $templateKey)->first();
        if ($templateKey == 'VERIFICATION_CODE' && $templateObj == null) {
            DB::table('notification_templates')->insert([
                'language_id' => 1,
                'name' => 'Verification Code',
                'email_from' => NotificationTemplate::whereNotNull('email_from')->value('email_from'),
                'template_key' => 'VERIFICATION_CODE',
                'subject' => 'verify your email',
                'short_keys' => json_encode(['code' => 'code']),
                'email' => 'Your email verification code [[code]]',
                'sms' => 'Your sms verification code [[code]]',
                'in_app' => null,
                'push' => null,
                'status' => json_encode(['mail' => '1', 'sms' => '1', 'in_app' => '0', 'push' => '0']),
                'notify_for' => 0,
                'lang_code' => 'en',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $templateObj = NotificationTemplate::where('template_key', $templateKey)->first();
        }
        $message = str_replace('[[name]]', $user->username, $email_body);

        if (!$templateObj && $subject == null) {
            return false;
        } else {
            if ($templateObj) {
                $message = str_replace('[[message]]', $templateObj->email, $message);
                if (empty($message)) {
                    $message = $email_body;
                }
                foreach ($params as $code => $value) {
                    $message = str_replace('[[' . $code . ']]', $value, $message);
                }
            } else {
                $message = str_replace('[[message]]', $requestMessage, $message);
            }
        }

        $subject = $subject == null ? $templateObj->subject : $subject;
        $email_from = $templateObj ? $templateObj->email_from : $basic->sender_email;

        Mail::to($user)->send(new SendMail($email_from, $subject, $message));
        Artisan::call('queue:work', ['--stop-when-empty' => true]);
    }

    public function verifyToSms($user, $templateKey, $params = [], $requestMessage = null)
    {
        $basic = basicControl();
        if ($basic->sms_verification != 1) {
            return false;
        }

        $templateObj = NotificationTemplate::where('template_key', $templateKey)
            ->where('language_id', $user->language_id)
            ->first();
        if (!$templateObj) {
            $templateObj = NotificationTemplate::where('template_key', $templateKey)->first();
        }

        if (!$templateObj && $requestMessage == null) {
            return false;
        } else {
            if ($templateObj) {
                $template = $templateObj->sms;
                foreach ($params as $code => $value) {
                    $template = str_replace('[[' . $code . ']]', $value, $template);
                }
            } else {
                $template = $requestMessage;
            }
        }

        $smsControl = ManualSmsConfig::firstOrCreate(['id' => 1]);
        if (config('SMSConfig.default') == 'manual') {
            $headerData = is_null($smsControl->header_data) ? [] : json_decode($smsControl->header_data, true);
            $paramData = is_null($smsControl->header_data) ? [] : json_decode($smsControl->header_data, true);
            $paramData = http_build_query($paramData);
            $actionUrl = $smsControl->action_url;
            $actionMethod = $smsControl->action_method;
            $formData = is_null($smsControl->form_data) ? [] : json_decode($smsControl->form_data, true);
            $phone = preg_replace('/\D/', '', $user->phone);

            $formData = recursive_array_replace('[[receiver]]', $phone, recursive_array_replace('[[message]]', $template, $formData));

            $queryString = 'FUN=' . $formData['FUN'] . '&with_get=' . $formData['with_get'] . '&un=' . $formData['un'] . '&up=' . $formData['up'] . '&senderID=' . $formData['senderID'] . '&msg=' . urlencode($formData['msg']) . '&to=' . $formData['to'];
            $fullUrl = $actionUrl . '?' . $queryString;

            $formData = isset($headerData['Content-Type']) && $headerData['Content-Type'] == 'application/x-www-form-urlencoded' ? http_build_query($formData) : (isset($headerData['Content-Type']) && $headerData['Content-Type'] == 'application/json' ? json_encode($formData) : $formData);

            foreach ($headerData as $key => $data) {
                $headerData[] = "{$key}:$data";
            }

            if ($actionMethod == 'GET') {
                $actionUrl = $fullUrl;
            }

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => $actionUrl,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $actionMethod,
                CURLOPT_POSTFIELDS => $formData,
                CURLOPT_HTTPHEADER => $headerData,
            ]);

            $response = curl_exec($curl);
           
            curl_close($curl);
            return $response;
        } else {
            BaseSmsService::sendSMS($user->phone_code . $user->phone, $template);
            return true;
        }
    }

    public function userFirebasePushNotification($user, $templateKey, $params = [], $action = null)
    {
        try {
            $basic = basicControl();
            $notify = config('firebase');

            $notificationPermission = NotificationPermission::where('notifyable_id', $user->id)->first();
            if ($notificationPermission && is_array($notificationPermission->template_push_key) && !in_array($templateKey, $notificationPermission->template_push_key)) {
                return false;
            }


            if (!$basic->push_notification) {
                return false;
            }
            if ($notify['user_foreground'] == 0 && $notify['user_background'] == 0) {
                return false;
            }

            $templateObj = NotificationTemplate::where('template_key', $templateKey)
                ->where('language_id', $user->language_id)
                ->first();
            if (!$templateObj->status['push']) {
                return false;
            }

            if (!$templateObj) {
                $templateObj = NotificationTemplate::where('template_key', $templateKey)->first();
                if (!$templateObj->status['push']) {
                    return false;
                }
            }

            $template = '';
            if ($templateObj) {
                $template = $templateObj->push;
                foreach ($params as $code => $value) {
                    $template = str_replace('[[' . $code . ']]', $value, $template);
                }
            }

            $users = FireBaseToken::where('tokenable_id', $user->id)->get();

            foreach ($users as $user) {
                $data = [
                    'to' => $user->token,
                    'notification' => [
                        'title' => $templateObj->name . ' from ' . $basic->site_title,
                        'body' => $template,
                        'icon' => getFile(config('filesystems.default'), basicControl()->favicon),
                    ],
                    'data' => [
                        'foreground' => (int) $notify['user_foreground'],
                        'background' => (int) $notify['user_background'],
                        'click_action' => $action,
                    ],
                    'content_available' => true,
                    'mutable_content' => true,
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'key=' . $notify['serverKey'],
                ])
                    ->acceptJson()
                    ->post('https://fcm.googleapis.com/fcm/send', $data);
            }
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function userPushNotification($user, $templateKey, $params = [], $action = [])
    {
        try {
            $basic = basicControl();

            if ($basic->in_app_notification != 1) {
                return false;
            }

            $notificationPermission = NotificationPermission::where('notifyable_id', $user->id)->first();

            if ($notificationPermission && is_array($notificationPermission->template_in_app_key) && !in_array($templateKey, $notificationPermission->template_in_app_key)) {
                    return false;
                }


            $templateObj = NotificationTemplate::where('template_key', $templateKey)
                ->where('language_id', $user->language_id)
                ->where('notify_for', 0)
                ->first();

            if (!$templateObj) {
                $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 0)->first();
                if (!$templateObj || !$templateObj->status['in_app']) {
                    return false;
                }
            }

            if ($templateObj) {
                $template = $templateObj->in_app;
                foreach ($params as $code => $value) {
                    $template = str_replace('[[' . $code . ']]', $value, $template);
                }
                $action['text'] = $template;
            }

            $inAppNotification = new InAppNotification();
            $inAppNotification->description = $action;
            $user->inAppNotification()->save($inAppNotification);
            event(new UserNotification($inAppNotification, $user->id));
        } catch (\Exception $e) {
            dd($e->getMessage());
            return 0;
        }
    }

    public function adminFirebasePushNotification($templateKey, $params = [], $action = null)
    {
        try {
            $basic = basicControl();
            $notify = config('firebase');
            if (!$notify) {
                return false;
            }

            if (!$basic->push_notification) {
                return false;
            }

            $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 1)->first();
            if (!$templateObj->status['push']) {
                return false;
            }

            if (!$templateObj) {
                return false;
            }

            $template = '';
            if ($templateObj) {
                $template = $templateObj->push;
                foreach ($params as $code => $value) {
                    $template = str_replace('[[' . $code . ']]', $value, $template);
                }
            }
            $admins = FireBaseToken::where('tokenable_type', Admin::class)->get();

            foreach ($admins as $admin) {
                $data = [
                    'to' => $admin->token,
                    'notification' => [
                        'title' => $templateObj->name,
                        'body' => $template,
                        'icon' => getFile(config('filesystems.default'), basicControl()->favicon),
                        'data' => [
                            'foreground' => (int) $notify['admin_foreground'],
                            'background' => (int) $notify['admin_background'],
                            'click_action' => $action,
                        ],
                        'content_available' => true,
                        'mutable_content' => true,
                    ],
                ];

                $response = Http::withHeaders([
                    'Authorization' => 'key=' . $notify['serverKey'],
                ])
                    ->acceptJson()
                    ->post('https://fcm.googleapis.com/fcm/send', $data);
            }
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function adminPushNotification($templateKey, $params = [], $action = [])
    {
        try {
            $basic = basicControl();
            if ($basic->in_app_notification != 1) {
                return false;
            }

            $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 1)->first();
            if (!$templateObj->status['in_app']) {
                return false;
            }

            if ($templateObj) {
                $template = $templateObj->in_app;
                foreach ($params as $code => $value) {
                    $template = str_replace('[[' . $code . ']]', $value, $template);
                }
                $action['text'] = $template;
            }

            $admins = Admin::all();
            foreach ($admins as $admin) {
                $inAppNotification = new InAppNotification();
                $inAppNotification->description = $action;
                $admin->inAppNotification()->save($inAppNotification);
                event(new AdminNotification($inAppNotification, $admin->id));
            }
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function adminMail($templateKey = null, $params = [], $subject = null, $requestMessage = null)
    {
        $basic = basicControl();

        if ($basic->email_notification != 1) {
            return false;
        }

        $email_body = $basic->email_description;
        $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 1)->first();
        if (!$templateObj) {
            $templateObj = NotificationTemplate::where('template_key', $templateKey)->where('notify_for', 1)->first();
        }
        if (!$templateObj->status['mail']) {
            return false;
        }

        $message = $email_body;
        if ($templateObj) {
            $message = str_replace('[[message]]', $templateObj->email, $message);

            if (empty($message)) {
                $message = $email_body;
            }
            foreach ($params as $code => $value) {
                $message = str_replace('[[' . $code . ']]', $value, $message);
            }
        } else {
            $message = str_replace('[[message]]', $requestMessage, $message);
        }

        $subject = $subject == null ? $templateObj->subject : $subject;
        $email_from = $basic->sender_email;
        $admins = Admin::all();
        foreach ($admins as $admin) {
            $message = str_replace('[[name]]', $admin->username, $message);
            Mail::to($admin)->queue(new SendMail($email_from, $subject, $message));
        }
    }
}
