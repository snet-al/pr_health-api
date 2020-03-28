<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
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
            'id' => (int) $this->id,
            'uuid' => $this->uuid,
            'name' => $this->name,
            'phone' => $this->phone,
            'mobile_phone' => $this->mobile_phone,
            'gender' => $this->gender,
            'points' => (int) $this->points,
            'is_confirmed' => (bool) $this->is_confirmed,
            'is_phone_confirmed' => (bool) $this->is_confirmed,
            'subscribe_to_menu' => (bool) $this->subscribe_to_menu,
            'subscribe_to_newsletter' => (bool) $this->subscribe_to_newsletter,
            'nr_of_orders' => (int) $this->orders->count(),
            'phone_confirmation_code' => $this->phone_confirmation_code,
        ];
    }
}
