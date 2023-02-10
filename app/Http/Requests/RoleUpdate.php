<?php

namespace App\Http\Requests;

use App\Role;
use Illuminate\Auth\Access\AuthorizationException;

class RoleUpdate extends RoleStore
{
	public function authorize() {
		if($this->ajax()){
			/** @var Role $role */
			$role = $this->route()->parameter('role', null);
			if($role->isUltimateRole() && !me()->isUltimateUser()){
				return new AuthorizationException(__('Bạn không được phép chỉnh sửa vai trò này'));
			}
			return true;
		}
		return false;
	}
}
