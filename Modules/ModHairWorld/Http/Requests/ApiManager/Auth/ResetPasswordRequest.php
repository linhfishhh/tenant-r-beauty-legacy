<?php

namespace Modules\ModHairWorld\Http\Requests\ApiManager\Auth;


use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    function rules(){
        return [
            'email_phone' => ['required'],
            'sms_verify_code' => [
                'required',
                'numeric',
            ],
            'new_password' => [
                'required',
                'min:6',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
            ]
        ];
    }

    function messages()
    {
        return [
            'email_phone.required'      => 'Email hoặc phone không để trống',
            'sms_verify_code.required'  => 'SMS code không được trống',
            'sms_verify_code.numeric'   => 'SMS code phải là số',
            'new_password.required'     => 'Password không để trống',
            'new_password.min'          => 'Password ít nhất 6 ký tự',
            'new_password.regex'        => 'Password phải gồm ký tự hoa, ký tự thường và ký tự đặc biệt'
        ];
    }
}