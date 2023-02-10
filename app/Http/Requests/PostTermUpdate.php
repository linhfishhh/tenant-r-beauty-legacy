<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;
use App\Classes\PostType;
use Illuminate\Auth\Access\AuthorizationException;

class PostTermUpdate extends FormRequestExtended
{
	/**
	 * @return PostType|string
	 */
	private function getPostType(){
		return $this->route()->parameter( 'post_type');
	}

    public function authorize()
    {
        if($this->ajax()){
        	$post_id = $this->get( 'pk');
        	/** @var PostType $post_type */
        	$post_type = $this->route()->parameter( 'post_type');
        	$post = $post_type::find($post_id);
        	if(!$post){
		        return new AuthorizationException(__('Yêu cầu không hợp lệ'));
	        }
	        if($post->deleted_at != null){
		        return new AuthorizationException(__('Yêu cầu không hợp lệ'));
	        }

	        if(!me()->hasPermission($post_type::getCatalogizePermissionID())){
                return new AuthorizationException(__('Yêu cầu không hợp lệ'));
            }

        	return true;
        }
        return false;
    }

	public function rules()
    {
	    return [
		    'value' => [
			    'required'
		    ],
		    'pk' => [
			    'required',
			    sprintf( 'exists:%s,id', $this->getPostType()::getDBTable())
		    ]
	    ];
    }

    public function messages() {
	    return [
		    'value.required' => __('Vui lòng nhập thông tin này'),
		    'pk.exists' => __('id không hợp lệ')
	    ];
    }
}
