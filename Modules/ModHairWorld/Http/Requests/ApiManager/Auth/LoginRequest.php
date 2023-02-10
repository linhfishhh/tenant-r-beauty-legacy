<?php

namespace Modules\ModHairWorld\Http\Requests\ApiManager\Auth;


use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    function rules(){
        return [
            'email_phone' => ['required'],
            'password' => ['required']
        ];
    }

    function messages()
    {
        return [
            'email_phone.required' => 'Email hoặc phone không để trống',
            'password.required' => 'Password không được trống'
        ];
    }
}