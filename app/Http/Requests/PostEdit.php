<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;
use App\Classes\PostType;
use Illuminate\Auth\Access\AuthorizationException;

class PostEdit extends FormRequestExtended
{

    public function authorize()
    {
    	/** @var PostType $post */
	    $post = $this->route()->parameter( 'post');
	    if(!me()->hasPermission( $post::getEditPermissionID())){
	    	return new AuthorizationException('Bạn không có quyền truy cập chức năng này');
	    }
	    if(!me()->hasPermission( $post::getManageGlobalPermissionID())){
	    	if(me()->id != $post->user_id){
			    return new AuthorizationException('Bạn không có quyền truy cập chức năng này');
		    }
	    }
        return true;
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
