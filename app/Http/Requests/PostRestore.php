<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;
use App\Classes\PostType;
use Illuminate\Auth\AuthenticationException;

class PostRestore extends FormRequestExtended
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool|AuthenticationException
     */
    public function authorize()
    {
    	if($this->ajax()){
		    /** @var PostType $post_type */
		    $post_type = $this->route()->parameter( 'post_type');
    		if(!me()->hasPermission($post_type::getTrashPermissionID())){
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
