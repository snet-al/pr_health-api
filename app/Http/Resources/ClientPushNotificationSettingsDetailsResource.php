<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientPushNotificationSettingsDetailsResource extends JsonResource
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
                'title' => $this->mobileAppNotificationType->title,
                'notification_type_id' => (int) $this->mobileAppNotificationType->id,
                'notification_type' => $this->mobileAppNotificationType->name,
                'notification_status' => (bool) $this->notification_status,
                'notification_description' => $this->mobileAppNotificationType->description,
                'icon' => $this->mobileAppNotificationType->icon,
        ];
    }
}
