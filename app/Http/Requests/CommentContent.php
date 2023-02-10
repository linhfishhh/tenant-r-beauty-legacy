<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;

class CommentContent extends CommentBase{
    public function authorize()
    {
        $rs = parent::authorize();
        if($rs instanceof AuthorizationException){
            return $rs;
        }
        if($this->ajax()){
            return true;
        }
        return false;
    }

    public function rules()
    {
        $rs = [];
        $rs['content'][] = 'required';
        return $rs;
    }

    public function messages()
    {
        $rs = [];
        $rs['content.required'] = __('Nội dung bình luận không được bỏ trống');
        return $rs;
    }
}
