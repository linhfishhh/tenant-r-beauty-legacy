<?php

namespace App\Http\Requests;

use App\Events\User\UserUpdateRequestAuthorize;
use App\Events\User\UserUpdateRequestMessages;
use App\Events\User\UserUpdateRequestRules;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;

class UserUpdate extends UserStore {

    private function baseAuthorize(){
        if ( $this->ajax() ) {
            /** @var User $user */
            $user = $this->route()->parameter( 'user' );
            if ( ! me()->isUltimateUser() && $user->isUltimateUser() ) {
                return new AuthorizationException( __( 'Bạn được quyền chỉnh sửa vai trò nhà phát triển' ) );
            }
            if ( ! me()->isUltimateUser() && ( $this->get( 'role_id', null ) == getUltimateRoleID() ) ) {
                return new AuthorizationException( __( 'Bạn được quyền thiết lập vai trò nhà phát triển' ) );
            }
            if ( me()->isMyID( $user->id ) ) {
                if ( $user->role_id != $this->get( 'role_id', null ) ) {
                    return new AuthorizationException( __( 'Bạn không thể thay đổi vai trò chính bạn' ) );
                }
            }
            return true;
        }
        return false;
    }
	public function authorize() {
		$rs = $this->baseAuthorize();
        $event = new UserUpdateRequestAuthorize($this, $rs);
        event($event);
        return $event->authorize;
	}

	public function rules() {
		$rules             = parent::rules();
		$rules['password'] = [
			'nullable'
		];
		unset( $rules['email'] );
        $event = new UserUpdateRequestRules($this, $rules);
        event($event);
        return $event->rules;
	}

	public function messages()
    {
        $messages = parent::messages();
        $event = new UserUpdateRequestMessages($this, $messages);
        event($event);
        return $event->messages;
    }
}
