<?php

namespace App\Http\Resources;

class UserResource extends BaseResource
{

    public function toArray($request)
    {
        if (isset($this->uuid)) {
            return [
                'uuid' => $this->uuid,
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'email_verified_at' => $this->email_verified_at,
                'avatar' => $this->avatar,
                'address' => $this->address,
                'phone_number' => $this->phone_number,
                'is_marketing' => $this->is_marketing,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'last_login_at' => $this->last_login_at,
            ];
        }
        return [
            'data' => $this->map(function ($data) {
                return [
                    'uuid' => $data->uuid,
                    'first_name' => $data->first_name,
                    'last_name' => $data->last_name,
                    'email' => $data->email,
                    'email_verified_at' => $data->email_verified_at,
                    'avatar' => $data->avatar,
                    'address' => $data->address,
                    'phone_number' => $data->phone_number,
                    'is_marketing' => $data->is_marketing,
                    'created_at' => $data->created_at,
                    'updated_at' => $data->updated_at,
                    'last_login_at' => $data->last_login_at,
                ];
            })
        ];
    }
}
