<?php

namespace App\Http\Requests;

use App\Events\User\UserUpdateRequestAuthorize;
use App\Events\User\UserUpdateRequestMessages;
use App\Events\User\UserUpdateRequestRules;
use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdate extends FormRequest
{
    public function authorize()
    {
        $rs =  true;
        $event = new UserUpdateRequestAuthorize($this, $rs);
        event($event);
        return $event->authorize;
    }

    public function rules()
    {
        $rules = [];
        $rules['password'] = [
            'nullable'
        ];
        $rules['password'] = array_merge($rules['password'], [
            'min:6',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
            'confirmed'
        ]);
        $rules['name'] = [
            'required'
        ];
        $event = new UserUpdateRequestRules($this, $rules);
        event($event);
        return $event->rules;
    }

    public function messages()
    {
        $messages = [
            'password.required' => __('Vui lòng nhập mật khẩu'),
            'password.min' => __('Mật khẩu phải từ 6 ký tự'),
            'password.regex' => __('Mật khẩu phải chứ ký tự hoa, thường và ký tự đặt biệt'),
            'password.confirmed' => __('Mật khẩu xác nhận không khớp'),
            'name.required' => __('Vui lòng nhập họ tên'),
        ];
        $event = new UserUpdateRequestMessages($this, $messages);
        event($event);
        return $event->messages;
    }
}
