<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;


class FCMService
{ 
    public static function send($token, $notification)
    {
        $settingData = DB::table('business_settings')->where(['key' => 'push_notification_key'])->first();

        $token = $settingData->value;

        Http::acceptJson()->withToken($token)->post(
            'https://fcm.googleapis.com/fcm/send',
            [
                'to' => $token,
                'notification' => $notification,
            ]
        );
    }
}