<?php

namespace App\Http\Resources;

class CategoryResource extends BaseResource
{

    public function toArray($request)
    {
        if (isset($this->uuid)) {
            return [
                'uuid' => $this->uuid,
                'title' => $this->title,
                'slug' => $this->slug,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ];
        }
        return [
            'data' => $this->map(function ($data) {
                return [
                    'uuid' => $data->uuid,
                    'title' => $data->title,
                    'slug' => $data->slug,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
            })
        ];
    }
}
