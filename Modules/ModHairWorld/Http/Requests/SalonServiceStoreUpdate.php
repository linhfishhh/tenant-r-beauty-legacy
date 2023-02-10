<?php

namespace Modules\ModHairWorld\Http\Requests;


use App\Http\Requests\Ajax;

class SalonServiceStoreUpdate extends Ajax
{
    public function rules()
    {
        $rs = [
            'name' => ['required'],
            'description' => ['required'],
            'price' => ['required', 'numeric'],
            'service_cat_id' => ['required'],
            'time_from' => ['required'],
            'time_to' => ['required'],
        ];
        return $rs;
    }

    public function messages()
    {
        $rs = [
            'name.required' => __('Vui lòng nhập thông tin này'),
            'description.required' => __('Vui lòng nhập thông tin này'),
            'price.required' => __('Vui lòng nhập thông tin này'),
            'price.numeric' => __('Giá phải là số'),
            'service_cat_id.required' => __('Vui lòng nhập thông tin này'),
            'time_from.required' => __('Vui lòng nhập thông tin này'),
            'time_to.required' => __('Vui lòng nhập thông tin này'),
        ];
        return $rs;
    }
}