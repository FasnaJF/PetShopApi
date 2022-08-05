<?php

namespace App\Http\Requests;

class CreatePaymentRequest extends BaseRequest
{

    public function authorize()
    {
        if (\auth()->user()) {
            return true;
        } else {
            return $this->unauthorizedError();
        }
    }

    public function rules()
    {
        return [
            'type' => 'required|string|max:255',
            'details' => 'required|string|json'
        ];
    }
}
