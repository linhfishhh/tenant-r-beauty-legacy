<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;

class UserPut extends FormRequestExtended
{
    public function authorize()
    {
	    if ($this->ajax()) {
		    if ($this->input('name') == 'role_id') {
			    if ($this->input('value') == getUltimateRoleID()) {
				    if (!me()->isUltimateUser()) {
					    return new AuthorizationException(__('Bạn không được phép đổi sang vai trò này'));
				    }
			    }
			    if ($this->input('pk') == me()->id) {
				    return new AuthorizationException(__('Bạn không được phép đổi vai trò chính bạn'));
			    }
		    }
		    $user = User::find($this->input('pk'));
		    if(!me()->isUltimateUser() && $user->isUltimateUser()){
			    return new AuthorizationException(__('Bạn không được phép chỉnh sửa người dùng này'));
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
                'exists:users,id'
            ]
        ];
    }

    public function messages()
    {
        return [
            'value.required' => __('Vui lòng nhập thông tin này')
        ];
    }

}
