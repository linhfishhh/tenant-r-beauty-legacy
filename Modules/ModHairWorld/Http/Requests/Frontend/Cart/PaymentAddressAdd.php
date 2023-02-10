<?php
namespace Modules\ModHairWorld\Http\Requests\Frontend\Cart;


use App\Http\Requests\Ajax;

class PaymentAddressAdd extends Ajax
{
    public function rules()
    {
        $rs = [
            'name' => ['required'],
            'phone' => ['required'],
            'email' => ['required', 'email'],
            'address' => ['required'],
            'tinh_thanh_pho_id' =>  ['required'],
            'quan_huyen_id' =>  ['required'],
            'phuong_xa_thi_tran_id' =>  ['required'],
        ];
        return $rs;
    }

    public function messages()
    {
        $rs = [
            'name.required' => __('Vui lòng nhập họ tên'),
            'phone.required' => __('Vui lòng nhập số điện thoại'),
            'email.required' => __('Vui lòng nhập email'),
            'email.email' => __('Email không hợp lệ'),
            'address.required' => __('Vui lòng nhập địa chỉ'),
            'tinh_thanh_pho_id.required' =>  __('Vui lòng chọn tỉnh, thành phố'),
            'quan_huyen_id.required' =>  __('Vui lòng chọn quận huyện'),
            'phuong_xa_thi_tran_id.required' =>  __('Vui lòng chọn phường, xã, thị trấn'),
        ];
        return $rs;
    }
}