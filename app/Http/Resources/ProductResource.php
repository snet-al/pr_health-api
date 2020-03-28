<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class ProductResource extends Resource
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
        $photo = $this->photos()->first() ?? null;

        return [
            'id' => (int) $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            //TODO: STATIC DECLARED PARAMS WILL BE IMPLEMENTED LATER
            'measurement_unit_id' => 1,
            'measurement_unit' => 'COPE/KG',
            'min_sale_quantity' => (float) $this->min_sale_quantity,
            'sale_price' => 190,
            'old_price' => 190,
            'total_quantity' => (float) $this->total_quantity,
            'groups' => ProductGroupResource::collection($this->productGroups),
            'image_default' => $photo ? $photo->path : null,
            'full_image_default' => $photo ? config('url.product_url') . $photo->path : null,
            'photos' => $this->photo(),
            'full_photos' => $this->fullPhoto(),
            'rating' => 5,
        ];
    }

    private function fullPhoto()
    {
        $photos = [];
        foreach ($this->photos as $photo) {
            $photos[] = config('url.product_url')  . $photo->path;
        }

        return $photos;
    }

    private function photo()
    {
        $photos = [];
        foreach ($this->photos as $photo) {
            $photos[] = $photo->path;
        }

        return $photos;
    }
}
