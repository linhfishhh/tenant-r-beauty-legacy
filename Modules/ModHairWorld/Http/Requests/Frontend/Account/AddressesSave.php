<?php

namespace Modules\ModHairWorld\Http\Requests\Frontend\Account;


use App\Http\Requests\Ajax;

class AddressesSave extends Ajax
{
    public function rules()
    {
        $rs = [
            'address' => [
                'required'
            ],
            'tinh_thanh_pho_id' =>[
                'required'
            ],
            'quan_huyen_id' =>[
                'required_with:tinh_thanh_pho_id'
            ],
            'phuong_xa_thi_tran_id' =>[
                'required_with:quan_huyen_id'
            ],
        ];
        return $rs;
    }

    public function messages()
    {
        $rs = [
            'address.required' => 'Vui lòng nhập số nhà, tên đường, ngỏ...',
            'tinh_thanh_pho_id.required' => 'Vui lòng chọn thành phố tỉnh',
            'quan_huyen_id.required_with' => 'Vui lòng chọn quận huyện',
            'phuong_xa_thi_tran_id.required_with' => 'Vui lòng chọn phường xã, thị trấn'
        ];
        return $rs;
    }
}