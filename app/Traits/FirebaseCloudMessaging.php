<?php

namespace App\Traits;

use App\ClientDevice;
use App\ClientPushNotificationSetting;
use App\FCM\FCMSendNotification;
use App\Helpers\FcmNotificationConstants;

trait FirebaseCloudMessaging
{
    public function sendFCMNotification($request, $notificationModelType, $title, $message, $model)
    {
        $clientId = $model->client_id;

        if (! $clientId) {
            $clientId = $model->receiver_id;
            if (! $clientId) {
                return false;
            }
        }

        $clientNotificationSettings = ClientPushNotificationSetting::where('client_id', $clientId)
            ->where('mobile_app_notification_type_id', FcmNotificationConstants::NOTIFICATION_MAPPING[$notificationModelType])
            ->first();

        if ($clientNotificationSettings && $clientNotificationSettings->notification_status) {
            $deviceTokens = $request['device_tokens'] ?? ClientDevice::where('client_id', $clientId)->pluck('unique_identifier');
            $request['title'] = $title;
            $request['message'] = $message;
            $request['image'] = $request['image'] ?? '';
            $request['model'] = $request['model'] ?? $notificationModelType;
            $request['model_id'] = $model->id ?? '';

            return FCMSendNotification::send($request, $deviceTokens);
        }

        return true;
    }
}
