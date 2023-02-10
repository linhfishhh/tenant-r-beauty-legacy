<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;
use App\Classes\PostType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PostIndex extends FormRequestExtended
{
    public function authorize()
    {
    	/** @var PostType $post_type */
    	$post_type = $this->route()->parameter( 'post_type');
    	if(me()->hasAnyPermissions( [
		    $post_type::getCreatePermissionID(),
		    $post_type::getEditPermissionID(),
		    $post_type::getDeletePermissionID(),
		    $post_type::getCatalogizePermissionID(),
		    $post_type::getPublishPermissionID(),
		    $post_type::getTrashPermissionID(),
	    ])){
    		return true;
	    }
        return new NotFoundHttpException();
    }

    public function rules()
    {
        return [
            //
        ];
    }
}
