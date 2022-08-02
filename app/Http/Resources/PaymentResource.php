<?php

namespace App\Http\Resources;


class PaymentResource extends BaseResource
{

    public function toArray($request)
    {

        if (isset($this->uuid)) {
            return [
                'uuid' => $this->uuid,
                'type' => $this->type,
                'details' => $this->details,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ];
        }
        return [
            'data' => $this->map(function ($data) {
                return [
                    'uuid' => $data->uuid,
                    'type' => $data->type,
                    'details' => $data->details,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                ];
            })
        ];

    }
}
