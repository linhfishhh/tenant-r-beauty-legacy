<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;
use App\Classes\PostType;
use Illuminate\Auth\AuthenticationException;

class PostCreate extends FormRequestExtended
{

    public function authorize()
    {
	    /** @var PostType $post_type */
	    $post_type = $this->route()->parameter( 'post_type');
    	if(!me()->hasPermission( $post_type::getCreatePermissionID())){
    		return new AuthenticationException(__('Bạn không có quyền truy cập chức năng này'));
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
