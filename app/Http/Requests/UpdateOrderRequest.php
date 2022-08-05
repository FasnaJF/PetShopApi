<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderRequest extends BaseRequest
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
            'order_status_uuid' => ['required', Rule::exists('order_statuses', 'uuid')],
            'payment_uuid' => ['required', Rule::exists('payments', 'uuid')],
            'products' => 'required|string|json',
            'address' => 'required|string|json',
        ];
    }
}
