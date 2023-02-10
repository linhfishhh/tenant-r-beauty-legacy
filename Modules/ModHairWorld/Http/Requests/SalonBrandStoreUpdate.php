<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 30-May-18
 * Time: 15:17
 */

namespace Modules\ModHairWorld\Http\Requests;


use App\Http\Requests\Ajax;

class SalonBrandStoreUpdate extends Ajax
{
    public function rules()
    {
        $rs = [
            'logo_id' => ['required']
        ];
        return $rs;
    }

    public function messages()
    {
        $rs = [
            'logo_id.required' => __('Vui lòng chọn logo thương hiệu')
        ];
        return $rs;
    }
}