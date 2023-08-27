<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Support\Facades\Auth;

class ModifyUserRequest extends BaseFormRequest
{
    public function rules(): array
    {
        $except = $this->getMethod() === 'PUT' ? $this->route('user')->id : 0;

        $rules = [
            'first_name'      => "min:2|max:255|nullable",
            'last_name'       => "min:2|max:255|nullable",
            'password'        => 'min:3|max:255',
            'email'           => "email|min:3|max:255|unique:users,email,$except",
            'email_verified_at' => 'date_format:Y-m-d H:i:s',
        ];

        if ($this->method() === 'POST') {
            $rules['email']    = 'required|'.$rules['email'];
            $rules['password'] = 'required|'.$rules['password'];
        }

        return $rules;
    }

    public function messages()
    {
        return [
        ];
    }
}
