<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PhotoResource extends JsonResource
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
            'path' => $this->path,
            'thumbnail_path' => $this->thumbnail_path,
            'full_path' => url($this->path),
            'full_thumbnail_path' => url($this->thumbnail_path),
        ];
    }
}
