<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 30-May-18
 * Time: 15:17
 */

namespace Modules\ModHairWorld\Http\Requests;


use App\Http\Requests\Ajax;

class SalonStylistStoreUpdate extends Ajax
{
    public function rules()
    {
        $rs = [
            'name' => ['required']
        ];
        return $rs;
    }

    public function messages()
    {
        $rs = [
            'name.required' => __('Vui lòng nhập tên stylist')
        ];
        return $rs;
    }
}