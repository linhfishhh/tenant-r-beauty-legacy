<?php

namespace Modules\ModHairWorld\Http\Requests\ApiManager\SMS;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class NewPhoneCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    function rules(){
        $username_phone = Input::get('email_phone');
        $column = is_numeric($username_phone)?'phone':'email';
        return [
            'email_phone' => [
                'required',
                'exists:users,'.$column
            ]
        ];
    }
    function messages()
    {
        return [
            'email_phone.required'  => 'Email hoặc phone không để trống',
            'email_phone.exists'    => 'Email hoặc phone không tồn tại'
        ];
    }
}