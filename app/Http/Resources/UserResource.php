<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
        $photo = $this->photo()->first() ?? null;

        return [
            'id' => (int) $this->id,
            'uuid' => $this->uuid,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'birthday' => $this->birthday,
            'is_active' => (bool) $this->is_active,
            'client' => new ClientResource($this->client),
            'photo_path' => $photo ? $photo->path : null,
            'photo_thmb_path' => $photo ? $photo->thumbnail_path : null,
            'full_photo_path' => $photo ? url($photo->path) : null,
            'full_photo_thmb_path' => $photo ? url($photo->thumbnail_path) : null,
            'addresses' => AddressResource::collection($this->addresses),
            'is_confirmed' => (bool) $this->is_confirmed,
            'is_email_confirmed' => (bool) $this->is_confirmed,
            'bearer' => $this->bearer ?? null,
        ];
    }
}
