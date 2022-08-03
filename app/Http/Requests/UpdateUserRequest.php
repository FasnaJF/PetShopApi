<?php

namespace App\Http\Requests;

use App\Models\User;

class UpdateUserRequest extends BaseRequest
{
    public function authorize()
    {
        $user = User::where('uuid',$this->uuid)->first();
        if($user){
            return true;
        }else{
           $this->resourceNotFound("User Not Found");
        }
    }

    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email,'.$this->uuid.',uuid',
            'password' => 'required_with:password_confirmation|same:password_confirmation|min:8',
            'password_confirmation' => 'min:8',
            'address' => 'required',
            'phone_number' => 'required',
        ];
    }

}
