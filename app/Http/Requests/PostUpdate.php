<?php

namespace App\Http\Requests;

use App\Classes\PostType;
use Illuminate\Auth\Access\AuthorizationException;

class PostUpdate extends PostStore
{
    public function authorize()
    {
	    if($this->ajax()){
		    /** @var PostType $post */
		    $post = $this->route()->parameter( 'post');
		    if(!me()->hasPermission( $post::getEditPermissionID())){
			    return new AuthorizationException();
		    }
		    if(!me()->hasPermission( $post::getManageGlobalPermissionID())){
			    if(me()->id != $post->user_id){
				    return new AuthorizationException();
			    }
		    }
		    return true;
	    }
	    return false;
    }

    public function rules()
    {
        $rs = parent::rules();
        /** @var PostType $post_type */
        $post_type = $this->route()->parameter( 'post_type');
        $rs = $post_type::getUpdateRules($rs);
        return $rs;
    }

    public function messages()
    {
        $rs = parent::messages();
        /** @var PostType $post_type */
        $post_type = $this->route()->parameter( 'post_type');
        $rs = $post_type::getUpdateMessages($rs);
        return $rs;
    }
}
