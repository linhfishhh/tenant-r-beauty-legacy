<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;
use App\Classes\PostType;
use Illuminate\Auth\AuthenticationException;

class PostStore extends FormRequestExtended
{

    public function authorize()
    {
    	if($this->ajax()){
		    /** @var PostType $post_type */
		    $post_type = $this->route()->parameter( 'post_type');
    		if(!me()->hasPermission( $post_type::getCreatePermissionID())){
				return new AuthenticationException(__('Yêu cầu không hợp lệ'));
		    }
    		return true;
	    }
        return false;
    }


    public function rules()
    {
        /** @var PostType $post_type */
        $post_type = $this->route()->parameter( 'post_type');
    	$rules = [];
	    $rules['title'] = [
		    'required'
	    ];
	    $rules['published'] = [
		    'nullable'
	    ];
	    $rules['user_id'] = [
		    'nullable'
	    ];
	    $rules['language'] = [
		    'required'
	    ];
	    $rules['published_at'] = [
		    'nullable'
	    ];
	    $rules['slug'] = [
		    'nullable'
	    ];
        $rules = $post_type::getStoreRules($rules);
        return $rules;
    }

	public function messages()
	{
        /** @var PostType $post_type */
        $post_type = $this->route()->parameter( 'post_type');
		$messages = [];
		$messages['title.required'] = __('Tiêu đề không được bỏ trống');
		$messages = $post_type::getStoreMessages($messages);
		return $messages;
	}
}
