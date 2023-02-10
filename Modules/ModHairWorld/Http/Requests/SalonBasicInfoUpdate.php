<?php

namespace Modules\ModHairWorld\Http\Requests;


use App\Http\Requests\Ajax;

class SalonBasicInfoUpdate extends Ajax
{
    public function rules()
    {
        $rs = [
            'name' => ['required'],
            'certified' => ['required'],
            'open' => ['required'],
            'address' => ['required'],
            'tinh_thanh_pho_id' => ['required'],
            'quan_huyen_id' => ['required'],
            'phuong_xa_thi_tran_id' => ['required'],
            'info' => ['required'],
        ];
        return $rs;
    }

    public function messages()
    {
        $rs = [
            'name.required' => __('Vui lòng nhập thông tin này'),
            'certified.required' => __('Vui lòng nhập thông tin này'),
            'open.required' => __('Vui lòng nhập thông tin này'),
            'address.required' => __('Vui lòng nhập thông tin này'),
            'tinh_thanh_pho_id.required' => __('Vui lòng nhập thông tin này'),
            'quan_huyen_id.required' => __('Vui lòng nhập thông tin này'),
            'phuong_xa_thi_tran_id.required' => __('Vui lòng nhập thông tin này'),
            'info.required' => __('Vui lòng nhập thông tin này'),
        ];
        return $rs;
    }
}