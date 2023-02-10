<?php

namespace Modules\ModHairWorld\Http\Requests;


use App\Http\Requests\Ajax;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\ModHairWorld\Entities\Salon;

class SalonManagerStore extends Ajax
{
    public function rules()
    {
        /** @var Salon $salon */
        $salon = $this->route()->parameter('salon');
        $rs = [
            'user_id' => [
                'required',
                'unique:salon_managers,user_id'
            ]
        ];
        return $rs;
    }

    public function messages()
    {
        $rs = [
            'user_id.required' => __('Vui lòng chọn tài khoản'),
            'user_id.unique' => __('Tài khoản này đã là quản lý salon rồi')
        ];
        return $rs;
    }
}