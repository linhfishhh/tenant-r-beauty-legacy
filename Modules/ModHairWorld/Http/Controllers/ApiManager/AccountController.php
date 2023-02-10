<?php

namespace Modules\ModHairWorld\Http\Controllers\ApiManager;


use App\Http\Controllers\Controller;
use Modules\ModHairWorld\Http\Requests\ApiManager\Account\ChangePasswordRequest;

/**
 * @resource Account
 *
 * Những request này yêu cầu token ở header
 */
class AccountController extends Controller
{
    /**
     * Đổi mật khẩu*
     *
     * Đổi mật khẩu đăng nhập hiện tại
     * @response true
     */
    function changePassword(ChangePasswordRequest $request){

    }
}