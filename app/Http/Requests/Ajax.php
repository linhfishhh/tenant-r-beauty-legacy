<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;

class Ajax extends FormRequestExtended
{
    public function authorize()
    {
        if($this->ajax()){
            return true;
        }
        return false;
    }

    public function rules(){
        return [];
    }
}
