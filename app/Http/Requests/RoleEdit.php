<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;
use App\Role;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleEdit extends FormRequestExtended
{

    public function authorize()
    {
    	/** @var Role $role */
	    $role = $this->route()->parameter('role', null);
	    if($role->isUltimateRole() && !me()->isUltimateUser()){
		    return new NotFoundHttpException();
	    }
        return true;
    }

    public function rules()
    {
        return [];
    }
}
