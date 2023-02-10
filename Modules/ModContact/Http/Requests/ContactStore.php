<?php

namespace Modules\ModContact\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactStore extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'phone' => ['required'],
            'content' => ['required'],
            'captcha' => ['required', 'captcha']
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('Vui lòng nhập tên bạn'),
            'email.required' => __('Vui lòng nhập email'),
            'email.email' => __('Email không hợp lệ'),
            'phone.required' => __('Vui lòng nhập số điện thoại'),
            'content.required' => __('Vui lòng nhập nội dung cần liên hệ'),
            'captcha.required' => __('Vui lòng nhập mã bảo mật'),
            'captcha.captcha' => __('Mã bảo mật nhập không đúng'),
        ];
    }


    public function authorize()
    {
        if($this->ajax()){
            return true;
        }
        return false;
    }
}
