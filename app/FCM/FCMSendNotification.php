<?php

namespace App\FCM;

class FCMSendNotification
{
    public static function send($request, $deviceTokens)
    {
        $push = new Push(
            $request['title'],
            $request['message'],
            $request['image'] ?? '',
            $request['model'] ?? '',
            $request['model_id'] ?? 0
        );

        $firebase = new Firebase();

        $pushNotification = $push->getNotificationMessage();
        $pushNotificationData = $push->getNotificationMessageData();
        $result = $firebase->sendNotification($deviceTokens, $pushNotification, $pushNotificationData);

        $result = json_decode($result, true);

        if (isset($result['success']) && $result['success'] != 0) {
            return true;
        }

        return false;
    }
}
