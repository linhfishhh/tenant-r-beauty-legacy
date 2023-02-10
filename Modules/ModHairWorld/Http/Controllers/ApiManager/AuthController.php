<?php

namespace Modules\ModHairWorld\Http\Controllers\ApiManager;


use App\Http\Controllers\Controller;
use Illuminate\Auth\AuthenticationException;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\UserDevice;
use Modules\ModHairWorld\Http\Controllers\OneSignalController;
use Modules\ModHairWorld\Http\Requests\ApiManager\Auth\LoginRequest;
use Modules\ModHairWorld\Http\Requests\ApiManager\Auth\ResetPasswordRequest;
use Modules\ModHairWorld\Entities\SalonManager;
use Modules\ModHairWorld\Handlers\AuthHandler;
use App\User;

/**
 * @resource Authentication
 *
 * Những request này không yêu cầu token ở header
 */
class AuthController extends Controller
{
    use AuthHandler;

    /**
     * Login
     *
     * Đăng nhập trả về token<br/>
     * Tham số <strong>username</strong> có thể là <i>email</i> hoặc <i>số điện thoại</i>
     *
     * @response eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjJkZDQ0ZDYxOWI5YTZmYjY5N2QwYzJmOGQwYmVhZGExMGE3MWU2ZjM3Y2EyMTEyNjRlZGFkYjYzZmMwMjMzZWYwYWRmZTNlOGVhYzQ0NTM1In0.eyJhdWQiOiIxIiwianRpIjoiMmRkNDRkNjE5YjlhNmZiNjk3ZDBjMmY4ZDBiZWFkYTEwYTcxZTZmMzdjYTIxMTI2NGVkYWRiNjNmYzAyMzNlZjBhZGZlM2U4ZWFjNDQ1MzUiLCJpYXQiOjE1MzQ5NDcxMTMsIm5iZiI6MTUzNDk0NzExMywiZXhwIjoxNTY2NDgzMTEzLCJzdWIiOiI3NyIsInNjb3BlcyI6WyJjdXN0b21lciJdfQ.Tvv5vbxy-rkQrBq2sQFx5nfJT40vPpFJNrIC01k62zy0PQvUJdregoiLFp4tJfPfFvPnYWRb9b3ovXYyGmb-5du5cme0JV1wpjNQYZlpVMni_CfTUVg2JMqP62tylW_oI7nM1WK25xr2FOP1IrzqpLeYqsjwG0VideLgX2imsv-ZcHdi10c90ejPT4uCvnwvrhCR6gY9fPOApjJGhdJ6tJxa-gUP42LH0JdBrfgK45XAEKs_bXbgS5wwnR2b3R8PWX8_pCaxz0fBlna7M7X8BLGHY9kPWBStOfjocEFIJyH20gHSHYKViCgPK-i3gFVA5SA17mGNBUCYJQA3PGiEkVXM6V8td4VLJD-NUxXSvc7vWaESuXv2poE7g2tO90aFiyenXNmIJlGRqaFXLoevodIi78CYjjQMzv-TqUE7CfBsPpzIe32gcoopunecwKsuKNLgT6t5zsAYUp1pKMOA3WYnpJ4iu1x0AVvzckg549pfvVLPJktB5epZf11Araxs7nme9oIiBqwuPDFItlWh3LxtoC7-ajkstQQxFtO6K-dA2DHnEwBJPJbHbTnEhSDJDSdd2baZbF12Zz07tiHMxcB1Jtm7kb_zOWdxcpiNenYPd1R0EOi1aUC-aXa7qEypbj-Tt6LZajAw9eWg-OjASf_QhPHR5vu2-7qsxgUPBJc
     */
    function login(LoginRequest $request)
    {
        $email_phone = $request->get('email_phone');
        $password = $request->get('password');
        $uuid = $request->get('uuid');
        
        $result = \Auth::attempt([
           is_numeric($email_phone)?'phone':'email' => $email_phone,
           'password' => $password
        ]);
        if(!$result){
            throw new AuthenticationException('Thông tin đăng nhập không chính xác');
        }
        $user = \Auth::user();
        /** @var SalonManager $salon */
        $salon = SalonManager::with('salon')->where('user_id', \Auth::user()->id)->get()->first();
        if(!$salon){
            throw new AuthenticationException('Tài khoản này không có salon');
        }
        $token = $user->createToken('manager', ['manager']);
        $this->deleteOldToken($user->id, 'manager');
        $rs = [];
        $cover = $salon->salon->cover;
        $location = $salon->salon->location_lv1;
        $avatar_url = $cover?$cover->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl();
        $rs['salon'] = [
            'user_id' => $user->id,
            'salon_id' => $salon->salon->id,
            'open' => $salon->salon->open,
            'name' => $salon->salon->name,
            'avatar' => $avatar_url,
            'location_id' => $location?$location->id:0
        ];
        $rs['token'] = $token->accessToken;
        if ($uuid) {
            $stgToken = $this->loginStg($user, $uuid, 'PROVIDER');
            if (!$stgToken) {
                return \Response::json([
                    'code' => 500,
                    'message' => 'Lỗi đăng nhập hệ thống shop',
                ], 500);
            }
            $rs['secondaryToken'] = $stgToken;
        }
        return \Response::json($rs);
    }

    /**
     * Delete old token
     *
     * Xóa token cũ<br/>
     * Tham số <strong>user_id</strong> và <strong>name</strong>
     *
     * @response 
     */
    function deleteOldToken($user_id, $name){
        $latest = \DB::table('oauth_access_tokens')
            ->where('name', $name)
            ->where('user_id', '=', $user_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id'])->map(function ($item, $index) {
                return $item->id;
            })->all();
        ;
        if(count($latest)>0){
            \DB::table('oauth_access_tokens')
                ->where('name', $name)
                ->where('user_id', '=', $user_id)
                ->whereNotIn('id', $latest)
                ->delete();
        }
    }

    /**
     * Tạo lại mật khẩu
     *
     * Khởi tạo lại mật khẩu sử dụng mã xác thực sms<br/>
     *
     * @response true|false
     * @param ResetPasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    function resetPassword(ResetPasswordRequest $request)
    {
        $email_phone = $request->get('email_phone');
        $sms_verify_code = $request->get('sms_verify_code');
        $new_password = $request->get('new_password');

        $user = User::where((is_numeric($email_phone)?'phone':'email'), $email_phone)->first();
        if(count($user)>0){
            $user->password = \Hash::make($new_password);
            $user->save(); 
        }
        return \Response::json($user->phone);
    }
}