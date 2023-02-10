<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;
use App\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserEdit extends FormRequestExtended
{
    public function authorize()
    {
	    /** @var User $user */
	    $user = $this->route()->parameter( 'user');
	    if(!me()->isUltimateUser() && $user->isUltimateUser()){
		    return new NotFoundHttpException();
	    }
	    return true;
    }

    public function rules()
    {
        return [];
    }
}
