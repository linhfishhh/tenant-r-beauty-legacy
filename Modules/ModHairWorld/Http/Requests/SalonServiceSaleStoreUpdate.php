<?php

namespace Modules\ModHairWorld\Http\Requests;


use App\Http\Requests\Ajax;
use Illuminate\Validation\Rule;

class SalonServiceSaleStoreUpdate extends Ajax
{
    public function rules()
    {
        $rs = [
            'sale_amount' => ['required'],
        ];
        return $rs;
    }

    public function messages()
    {
        $rs = [
            'service_id.required' => __('Chọn dịch vụ cần giảm giá'),
            'service_id.unique' => __('Dịch vụ này đã khuyến mãi rồi'),
            'sale_amount.required' => __('Nhập số tiền giảm')
        ];
        return $rs;
    }
}