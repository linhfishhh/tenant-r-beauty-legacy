<?php

namespace Modules\ModHairWorld\Http\Controllers\ApiManager;


use App\Http\Controllers\Controller;
use Modules\ModHairWorld\Http\Requests\ApiManager\SMS\NewPhoneCodeRequest;
use Modules\ModHairWorld\Http\Requests\ApiManager\SMS\VerifyPhoneCodeRequest;
use Modules\ModHairWorld\Entities\Api\PhoneVerify;
use App\User;

/**
 * @resource SMS Verify
 *
 * Những request này không yêu cầu token ở header
 */
class SMSController extends Controller
{
    /**
     * Yêu cầu mã xác thực mới
     *
     * Yêu cầu mã xác thực gửi qua sms<br/>
     * Tham số <strong>username</strong> có thể là <i>email</i> hoặc <i>số điện thoại</i>
     *
     * @response true
     * @param NewPhoneCodeRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    function newCode(NewPhoneCodeRequest $request){
        $email_phone = $request->get('email_phone');
        $user = User::getQuery()->where((is_numeric($email_phone)?'phone':'email'), $email_phone)->first();
        $vrs = PhoneVerify::newVerify($user->phone);
        if($vrs instanceof \Exception){
            abort(400,$vrs->getMessage());
        }
        return \Response::json($user->phone);
    }

    /**
     * Kiểm tra mã xác thực
     *
     * Kiểm tra mã xác thực nhận được qua sms
     * @response true|false
     */
    function checkCode(VerifyPhoneCodeRequest $request){
        $sms_verify_code = $request->get('sms_verify_code');
        $phone = $request->get('phone');
        $verified = PhoneVerify::verify($phone, $sms_verify_code);
        if($verified instanceof \Exception){
            abort(400,$verified->getMessage());
        }
        return \Response::json($phone);
    }
}