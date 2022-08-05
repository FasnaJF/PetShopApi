<?php

namespace App\Http\Requests;

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
