<?php

namespace App\Http\Resources;

use App\ClientPushNotificationSetting;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientPushNotificationSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
             'section' => $this->section,
             'details' => ClientPushNotificationSettingsDetailsResource::collection(
                 ClientPushNotificationSetting::bySection($this->section)),
        ];
    }
}
