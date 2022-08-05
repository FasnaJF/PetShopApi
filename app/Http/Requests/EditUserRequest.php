<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class EditUserRequest extends BaseRequest
{
    private $user;

    public function authorize()
    {
        $this->user = Auth::user();
        if ($this->user) {
            return true;
        } else {
            $this->resourceNotFound("User Not Found");
        }
    }

    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,' . $this->user->uuid . ',uuid',
            'password' => 'required_with:password_confirmation|same:password_confirmation|min:8',
            'password_confirmation' => 'min:8',
            'address' => 'required',
            'phone_number' => 'required',
        ];
    }

}
