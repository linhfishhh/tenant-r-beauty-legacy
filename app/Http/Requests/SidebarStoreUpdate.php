<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SidebarStoreUpdate extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];
        $rules['title'] = [
            'required'
        ];
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        $messages['title.required'] = __('Vui lòng nhập tên nhận biết sidebar');
        return $messages;
    }
}
