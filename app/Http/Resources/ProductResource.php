<?php

namespace App\Http\Resources;


class ProductResource extends BaseResource
{

    public function toArray($request)
    {
        if (isset($this->uuid)) {
            return [
                'category_uuid' => $this->category_uuid,
                'title' => $this->title,
                'uuid' => $this->uuid,
                'price' => $this->price,
                'description' => $this->description,
                'metadata' => $this->metadata,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'deleted_at' => $this->deleted_at,
                'category' => new CategoryResource($this->category),
                'brand' => new BrandResource($this->brand),
            ];
        }
        return [
            'data' => $this->map(function ($data) {
                return [
                    'category_uuid' => $data->category_uuid,
                    'title' => $data->title,
                    'uuid' => $data->uuid,
                    'price' => $data->price,
                    'description' => $data->description,
                    'metadata' => $data->metadata,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'deleted_at' => $data->deleted_at,
                    'category' => new CategoryResource($data->category),
                    'brand' => new BrandResource($data->brand),
                ];
            })
        ];
    }
}
