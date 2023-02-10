<?php

namespace App\Http\Requests;

use App\Classes\FormRequestExtended;
use Illuminate\Auth\Access\AuthorizationException;

class RoleMove extends FormRequestExtended {
	public function authorize() {
		if ( $this->ajax() ) {
			$ids    = $this->get( 'ids', [] );
			$new_id = $this->get( 'new_id', null );
			if ( ! me()->isUltimateUser() ) {
				if ( in_array( getUltimateRoleID(), $ids ) ) {
					return new AuthorizationException( __( 'Bạn không được chuyển vai trò hệ thống' ) );
				}
			}
			if ( ! $ids || ! $new_id ) {
				return new AuthorizationException( __( 'Yêu cầu không hợp lệ' ) );
			}
			if ( in_array( me()->role_id, $ids ) ) {
				return new AuthorizationException( 'Bạn không thể tự chuyển vai trò của chính bạn' );
			}

			return true;
		}

		return false;
	}

	public function rules() {
		return [//
		];
	}
}
