<?php

namespace App\Http\Requests;
use App\Classes\FormRequestExtended;
use Illuminate\Auth\Access\AuthorizationException;

class UserDestroy extends FormRequestExtended
{
    public function authorize()
    {
    	if($this->ajax()){
		    $ids = $this->get( 'ids', []);
		    if(in_array(me()->id, $ids)){
			    return new AuthorizationException(__('Bạn không thể xóa tài khoản của chính bạn!'));
		    }
		    if(me()->role_id != getUltimateRoleID()){
			    if(in_array(getUltimateRoleID(), $ids)){
				    return new AuthorizationException(__('Bạn không thể xóa tài khoản nhà phát triển!'));
			    }
		    }
    		return true;
	    }
        return false;
    }

    public function rules()
    {
        return [];
    }
}
