<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;

class RoleStore extends FormRequestExtended
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [];
        $rules['title'] = [
            'required'
        ];
        $rules['desc'] = [
            'required'
        ];
        $rules['permissions'] = [
            'nullable',
            'array'
        ];
        return $rules;
    }

    public function messages()
    {
        $messages = [];
        $messages['title.required'] = __('Vui lòng nhập tên vai trò');
        $messages['desc.required'] = __('Vui lòng nhập mô tả vai trò');
        $messages['permissions.array'] = __('Phân quyền không hợp lệ');
        return $messages;
    }
}
