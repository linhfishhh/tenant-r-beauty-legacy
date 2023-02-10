<?php

namespace Modules\ModHairWorld\Http\Requests\Frontend\Account;


use App\Http\Requests\Ajax;

class AvatarSave extends Ajax
{
    public function rules()
    {
        $rs = [
            'avatar' => [
                'file',
                'max:1024'
            ],
            'filename' => [
                'required'
            ]
        ];
        return $rs;
    }
}