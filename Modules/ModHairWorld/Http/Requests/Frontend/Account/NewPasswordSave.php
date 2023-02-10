<?php

namespace Modules\ModHairWorld\Http\Requests\Frontend\Account;


use App\Http\Requests\Ajax;
use Modules\ModHairWorld\Rules\PasswordExist;

class NewPasswordSave extends Ajax
{
    public function messages()
    {
        $rs = [
            'old_password.required' => 'Vui lòng nhập mật khẩu cũ',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.confirmed' => 'Mật khẩu mới không khớp với xác nhận bên dưới',
            'password_confirmation.required' => 'Vui lòng nhập xác nhận mật khẩu',
            'password.min'       => __('Mật khẩu phải từ 6 ký tự'),
        ];
        return $rs;
    }

    public function rules()
    {
        $rs = [
            'old_password' => [
                'required',
                new PasswordExist(me())
            ],
            'password' => [
                'required',
                'min:6',
                'confirmed'
            ],
            'password_confirmation' => [
                'required'
            ]
        ];
        return $rs;

    }
}