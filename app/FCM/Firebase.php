<?php

namespace App\FCM;

class Firebase
{
    public function sendNotification($registrationIds, $message, $data)
    {
        if (! $registrationIds || count($registrationIds) == 0) {
            return false;
        }

        $fields = [
            'notification' => $message,
            'data' => $data,
        ];

        if (count($registrationIds) == 1) {
            $fields['to'] = $registrationIds[0];
        } else {
            $fields['registration_ids'] = $registrationIds;
        }

        return $this->sendPushNotification($fields);
    }

    private function sendPushNotification($fields)
    {
        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = [
            'Authorization: key=' . env('CLOUD_MESSAGE_SERVER_KEY'),
            'Content-Type: application/json',
        ];

        $ch = curl_init();

        //Setting the curl url
        curl_setopt($ch, CURLOPT_URL, $url);

        //setting the method as post
        curl_setopt($ch, CURLOPT_POST, true);

        //adding headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //disabling ssl support
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        //adding the fields in json format
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        //finally executing the curl request
        $result = curl_exec($ch);

        curl_close($ch);

        return $result;
    }
}
