<?php

namespace Modules\ModHairWorld\Http\Requests\Frontend\Account;


use App\Http\Requests\Ajax;

class RegisterStepTwoForm extends Ajax
{
    public function rules()
    {
        $rs = [
            'code' => ['required', 'numeric'],
            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],
            'phone' => [
                'required',
                'numeric',
                'unique:users,phone'
            ],
            'password' => [
                'required',
                'min:6',
                'confirmed'
            ]
        ];
        return $rs;
    }

    public function messages()
    {
        $rs = [
            'code.required' => 'Vui lòng nhập mã xác nhận',
            'code.numeric' => 'Sai mã xác nhận',
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email này đã được đăng ký',
            'phone.required' => 'Thông tin số điện thoại không được trống',
            'phone.numeric' => 'Số điện thoại không hợp lệ',
            'phone.unique' => 'Số điện thoại này đã được đăng ký',
            'password.required' => 'Thông tin mật khẩu không được trống',
            'password.min' => 'Mật khẩu phải từ 6 ký tự',
        ];
        return $rs;
    }
}