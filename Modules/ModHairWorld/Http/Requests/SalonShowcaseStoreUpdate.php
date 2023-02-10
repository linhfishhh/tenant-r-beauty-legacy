<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 31-May-18
 * Time: 11:25
 */

namespace Modules\ModHairWorld\Http\Requests;


use App\Http\Requests\Ajax;

class SalonShowcaseStoreUpdate extends Ajax
{
    public function rules()
    {
        $rs = [
            'name' => ['required'],
            'items' => ['required', 'array', 'min:1']
        ];
        return $rs;
    }

    public function messages()
    {
        $rs = [
            'name.required' => __('Vui lòng nhập tên tác phẩm'),
            'items.required' => __('Vui lòng chọn ảnh tách phẩm'),
            'items.min' => __('Bạn phải chọn ít nhất một ảnh cho tác phẩm')
        ];
        return $rs;
    }
}