<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;
use Illuminate\Auth\Access\AuthorizationException;

class RolePut extends FormRequestExtended
{
    public function authorize()
    {
	    if($this->ajax()){
		    $role_id = $this->get('pk', null);
		    if($role_id == getUltimateRoleID() && !me()->isUltimateUser()){
			    return new AuthorizationException(__('Bạn không được phép chỉnh sửa vai trò này'));
		    }
		    return true;
	    }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
            ],
            'value' => [
                'required'
            ],
            'pk' => [
                'required',
                'exists:roles,id'
            ]
        ];
    }

    public function messages()
    {
        return [
            'value.required' => __('Vui lòng nhập thông tin này'),
            'value.exists' => __('id không hợp lệ')
        ];
    }
}
