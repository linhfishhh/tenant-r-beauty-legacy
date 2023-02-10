<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 25-May-18
 * Time: 15:18
 */

namespace Modules\ModContact\Http\Requests;


use App\Http\Requests\Ajax;

class ContactReply extends Ajax
{
    public function rules()
    {
        $rs = [];
        $rs['content'] = ['required'];
        return $rs;
    }

    public function messages()
    {
        $rs = [];
        $rs['content.required'] = __('Vui lòng nhập nội dung email');
        return $rs;
    }

    public function authorize()
    {
        if($this->ajax()){
            return true;
        }
        return false;
    }
}