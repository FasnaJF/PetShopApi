<?php

namespace App\Http\Resources;

class UserResource extends BaseResource
{

    public function toArray($request)
    {
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
                'last_login_at' =>  $this->when($request->method() === 'GET', function () {
                    return $this->last_login_at;
                }),
                'token' =>  $this->when($request->method() === 'POST', function () {
                    return $this->jwtToken->unique_id;
                }),
            ];
    }
}
