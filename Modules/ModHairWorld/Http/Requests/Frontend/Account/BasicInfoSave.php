<?php

namespace Modules\ModHairWorld\Http\Requests\Frontend\Account;


use App\Http\Requests\Ajax;
use Illuminate\Validation\Rule;

class BasicInfoSave extends Ajax
{
    public function rules()
    {
        $rs = [
            'name' => [
                'required'
            ],
            'gender' => [
                'required'
            ],
            'birthday' => [
                'required'
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users','email')->ignore(me()->id)
            ]
        ];
        return $rs;
    }

    public function messages()
    {
        $rs = [
            'name.required' => 'Vui lòng nhập họ tên',
            'gender.required' => 'Vui lòng chọn giới tính',
            'birthday.required' => 'Vui lòng nhập ngày sinh',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email này đã được sử dụng, vui lòng nhập email khác'
        ];
        return $rs;
    }
}