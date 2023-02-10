<?php

namespace Modules\ModHairWorld\Http\Requests;


use App\Http\Requests\Ajax;

class SalonGalleryStoreUpdate extends Ajax
{
    public function rules()
    {
        $rs = [
            'image_ids' => ['required', 'array']
        ];
        return $rs;
    }

    public function messages()
    {
        $rs = [
            'image_ids.required' => __('Chọn ảnh giới thiệu')
        ];
        return $rs;
    }
}