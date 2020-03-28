<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GlobalConfigurationsResource extends JsonResource
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
            'require_email_verification' => env('REQUIRE_EMAIL_VERIFICATION', true),
            'require_phone_number_verification' => env('REQUIRE_PHONE_NUMBER_VERIFICATION', true),
            'allow_guest_checkout' => env('ALLOW_GUEST_CHECKOUT', true),
        ];
    }
}
