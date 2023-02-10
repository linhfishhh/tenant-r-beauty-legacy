<?php

namespace Modules\ModHairWorld\Http\Requests;

use Modules\ModHairWorld\Entities\Salon;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MySalonUpdate extends MySalonRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rs = [
            'name' => ['required'],
            'address' => ['required'],
            'tinh_thanh_pho_id' => ['required'],
            'quan_huyen_id' => ['required'],
            'phuong_xa_thi_tran_id' => ['required'],
        ];
        if($this->has('extra_infos')){
            $rs['extra_infos.*.icon'] = ['required'];
            $rs['extra_infos.*.title'] = ['required'];
            $rs['extra_infos.*.content'] = ['required'];
        }
        return $rs;
    }

    public function messages()
    {
        $rs = [
            'name.required' => __('Vui lòng nhập thông tin này'),
            'address.required' => _('Vui lòng nhập thông tin này'),
            'tinh_thanh_pho_id.required' => _('Vui lòng nhập thông tin này'),
            'quan_huyen_id.required' => _('Vui lòng nhập thông tin này'),
            'phuong_xa_thi_tran_id.required' => _('Vui lòng nhập thông tin này'),
        ];
        if($this->has('extra_infos')){
            $rs['extra_infos.*.icon.required'] = _('Vui lòng nhập thông tin này');
            $rs['extra_infos.*.title.required'] = _('Vui lòng nhập thông tin này');
            $rs['extra_infos.*.content.required'] = _('Vui lòng nhập thông tin này');
        }
        return $rs;
    }

    public function authorize()
    {
        $rs = parent::authorize();
        if($rs !== true){
            return $rs;
        }
        if($this->ajax()){

            return true;
        }
        return false;
    }
}
