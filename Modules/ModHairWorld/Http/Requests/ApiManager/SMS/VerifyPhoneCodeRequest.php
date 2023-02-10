<?php

namespace Modules\ModHairWorld\Http\Requests\ApiManager\SMS;


use Illuminate\Foundation\Http\FormRequest;

class VerifyPhoneCodeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    function rules(){
        return [
            'sms_verify_code' => [
                'required',
                'numeric'
            ],
            'phone' => ['required']
        ];
    }
    function messages()
    {
        return [
            'sms_verify_code.required'  => 'SMS code không để trống',
            'sms_verify_code.numeric'   => 'SMS code phải là số',
            'phone.required'            => 'Phone không để trống'
        ];
    }
}