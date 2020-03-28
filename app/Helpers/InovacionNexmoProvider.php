<?php

namespace App\Helpers;

use Nexmo\Laravel\Facade\Nexmo;

class InovacionNexmoProvider
{
    public static function notifyViaNexmo($to, $message)
    {
        try {
            Nexmo::message()->send([
                'to' => $to,
                'from' => env('NEXMO_NUMBER'),
                'text' => $message,
            ]);
            \Log::info('Message sent to: ' . $to);
        } catch (\Exception $ex) {
            \Log::error($ex->getMessage());
        }
    }
}
