<?php

namespace App\Http\Requests;

use Illuminate\Auth\Access\AuthorizationException;

class CommentDestroy extends CommentBase
{
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
}
