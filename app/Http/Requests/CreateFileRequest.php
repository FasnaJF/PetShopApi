<?php

namespace App\Http\Requests;

class CreateFileRequest extends BaseRequest
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
            'file' => 'required|max:1024'
        ];
    }
}
