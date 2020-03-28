<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\TwilioException;

class TwilioChannel
{
    protected $sid;
    protected $token;
    protected $twilioNumber;

    /**
     * Create a new channel instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sid = config('twilio.twilio_account_sid');
        $this->token = config('twilio.twilio_auth_token');
        $this->twilioNumber = config('twilio.twilio_number');
    }

    /*
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $this->sid || ! $this->token || ! $this->twilioNumber) {
            Log::error('Twilio configs are invalid');

            return false;
        }

        $message = $notification->toSms($notifiable);

        $clientSms = new \Twilio\Rest\Client($this->sid, $this->token);

        try {
            $clientSms->messages->create(
                $notifiable->phone,
                [
                    'from' => $this->twilioNumber,
                    'body' => $notifiable->phone_confirmation_code,
                ]
            );
        } catch (TwilioException $exception) {
            Log::error('Send sms via twilio failed : ' . $exception->getMessage());

            return false;
        }

        return true;
    }
}
