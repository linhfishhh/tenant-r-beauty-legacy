<?php

namespace Modules\ModHairWorld\Http\Requests\Frontend\Account;


use App\Http\Requests\Ajax;

class ResetPasswordForm extends Ajax
{
    public function rules()
    {
        $rs = [
            'phone' => [
                'exists:users,phone'
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

    public function messages()
    {
        $rs = [
            'phone.exists' => 'Số điện thoại không tồn tại trong hệ thống',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.confirmed' => 'Mật khẩu mới không khớp với xác nhận bên dưới',
            'password_confirmation.required' => 'Vui lòng nhập xác nhận mật khẩu',
            'password.min'       => __('Mật khẩu phải từ 6 ký tự'),
        ];
        return $rs;
    }
}