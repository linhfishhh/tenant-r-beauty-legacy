<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;
use App\Events\User\UserStoreRequestAuthorize;
use App\Events\User\UserStoreRequestMessages;
use App\Events\User\UserStoreRequestRules;

class UserStore extends FormRequestExtended
{

    public function authorize(){
		$rs = true;
		$event = new UserStoreRequestAuthorize($this, $rs);
		event($event);
        return $event->authorize;
	}

    public function rules()
    {
        $rules = [];
	    $rules['email'] = [
		    'required',
		    'email',
		    'unique:users,email'
	    ];
	    $rules['password'] = [
		    'required'
	    ];
        $rules['password'] = array_merge($rules['password'], [
            'min:6',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*(_|[^\w])).+$/',
            'confirmed'
        ]);
        $rules['name'] = [
            'required'
        ];
        $rules['role_id'] = [
            'required'
        ];
        $rules['avatar_id'] = [
            'nullable',
            'exists:uploaded_files,id'
        ];
        $event = new UserStoreRequestRules($this, $rules);
        event($event);
        return $event->rules;
    }

    public function messages()
    {
        $messages = [
            'email.required' => __('Vui lòng nhập email'),
            'email.email' => __('Email không hợp lệ'),
            'email.unique' => __('Email này đã tồn tại'),
            'password.required' => __('Vui lòng nhập mật khẩu'),
            'password.min' => __('Mật khẩu phải từ 6 ký tự'),
            'password.regex' => __('Mật khẩu phải chứ ký tự hoa, thường và ký tự đặt biệt'),
            'password.confirmed' => __('Mật khẩu xác nhận không khớp'),
            'name.required' => __('Vui lòng nhập họ tên'),
            'role_id.required' => __('Vui lòng chọn vai trò tài khoản')
        ];
        $event = new UserStoreRequestMessages($this, $messages);
        event($event);
        return $event->messages;
    }
}
