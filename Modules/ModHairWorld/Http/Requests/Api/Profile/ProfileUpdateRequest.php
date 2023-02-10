<?php

namespace Modules\ModHairWorld\Http\Requests\Api\Profile;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    function rules(){
        return [
            'name' => ['required'],
            'email' => [
                'required',
                'email',
                Rule::unique('users','email')->ignore(me()->id),
            ],
            'addresses.*.address' => 'required',
            'addresses.*.lv1' => 'required',
            'addresses.*.lv2' => 'required',
            'addresses.*.lv3' => 'required',

        ];
    }

    function messages()
    {
        return [
            'name.required' => 'Vui lòng nhập họ tên',
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Định dạng email không hợp lệ',
            'email.unique' => 'Email đã được sử dụng'
        ];
    }
}