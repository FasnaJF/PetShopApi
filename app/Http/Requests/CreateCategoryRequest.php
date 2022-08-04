<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateCategoryRequest extends BaseRequest
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
            'title' => 'required|string|max:255',
        ];
    }
}
