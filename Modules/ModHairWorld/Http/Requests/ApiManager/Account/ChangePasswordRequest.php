<?php

namespace Modules\ModHairWorld\Http\Requests\ApiManager\Account;


use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    function rules(){
        return [
            'old_password' => ['required'],
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
            'old_password.required'     => 'Password cũ không để trống',
            'new_password.required'     => 'Password mới không để trống',
            'new_password.min'          => 'Password mới ít nhất 6 ký tự',
            'new_password.regex'        => 'Password phải gồm ký tự hoa, ký tự thường và ký tự đặc biệt'
        ];
    }
}