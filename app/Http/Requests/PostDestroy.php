<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;
use App\Classes\PostType;
use Illuminate\Auth\AuthenticationException;

class PostDestroy extends FormRequestExtended
{
    public function authorize()
    {
    	if($this->ajax()){
		    /** @var PostType $post_type */
		    $post_type = $this->route()->parameter( 'post_type');
            if(!me()->hasPermission( $post_type::getDeletePermissionID())){
            	return new AuthenticationException(__('Yêu cầu không hợp lệ'));
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
            //
        ];
    }
}
