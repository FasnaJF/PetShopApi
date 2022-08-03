<?php

namespace App\Http\Resources;


class FileResource extends BaseResource
{
    public function toArray($request)
    {
        if (isset($this->uuid)) {
            return [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'path' => $this->path,
                'size' => $this->size,
                'type' => $this->type,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ];
        }
        return [
            'data' => $this->map(function ($data) {
                return [
                    'uuid' => $data->uuid,
                    'name' => $data->name,
                    'path' => $data->path,
                    'size' => $data->size,
                    'type' => $data->type,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
            })
        ];
    }
}
