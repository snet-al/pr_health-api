<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductGroupTreeResource extends JsonResource
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
        $categories = $this->subcategory;
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'parent_id' => (int) $this->parent_id,
            'properties' => $this->properties,
            'children' => self::collection($this->whenLoaded('subcategory')),
        ];
    }
}
