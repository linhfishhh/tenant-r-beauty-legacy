<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;
use App\Role;
use Illuminate\Auth\Access\AuthorizationException;

class RoleDestroy extends FormRequestExtended
{
	protected function authorize() {
		if($this->ajax()){
			$ids = $this->get('ids', []);
			if(in_array( getUltimateRoleID(), $ids)){
				return new AuthorizationException(__('Vai trò này phục vụ cho việc bảo trì và phát triển của hệ thống bạn không thể xóa'));
			}
			if(count(array_intersect( config('app.system_role_ids',[]), $ids))>0){
				return new AuthorizationException(__('Vai trò này là mặc định của hệ thống bạn không thể xóa'));
			}
			$count = Role::whereIn('id', $ids)->whereHas('users')->count() ;
			if($count > 0){
				return new AuthorizationException(__('Chỉ có thể xóa những vai trò nào không có tài khoản, vui lòng chuyển những tài khoản hiện tại sang vai trò khác'));
			}
			return true;
		}
		return false;
	}

	public function rules()
    {
        return [
            //
        ];
    }
}
