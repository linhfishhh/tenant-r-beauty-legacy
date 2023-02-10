<?php

namespace Modules\ModHairWorld\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\UploadedFile;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Validation\ValidationException;
use Modules\ModHairWorld\Entities\Api\PhoneVerify;
use Modules\ModHairWorld\Entities\DiaPhuongQuanHuyen;
use Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho;
use Modules\ModHairWorld\Entities\DiaPhuongXaPhuongThiTran;
use Modules\ModHairWorld\Entities\UserAddress;
use Modules\ModHairWorld\Http\Controllers\BrandSmsController;
use Modules\ModHairWorld\Handlers\AuthHandler;
use Socialite;

class AuthController extends Controller
{
    use AuthHandler;

    function socialCreateAccount(Request $request){
        \Validator::validate($request->all(), [
            'token' => ['required'],
            'provider' => ['required', 'in:facebook,google'],
            'phone' => ['required', 'numeric'],
            'code' => ['required'],
            'uuid' => ['nullable'],
        ], [
            'token.required' => 'Có lỗi xảu ra khi xác nhận thông tin tài khoản',
            'provider.required' => 'Có lỗi xảu ra khi xác nhận thông tin tài khoản',
            'provider.in' => 'Có lỗi xảu ra khi xác nhận thông tin tài khoản',
            'phone.required' => 'Có lỗi xảu ra khi xác nhận thông tin tài khoản',
            'phone.numeric' => 'Có lỗi xảu ra khi xác nhận thông tin tài khoản',
            'code.required' => 'Vui lòng nhập mã xác nhận được gửi qua tin nhắn',
        ]);
        $access_token = $request->get('token');
        $phone = $request->get('phone');
        $code = $request->get('code');
        $provider = $request->get('provider');
        $uuid = $request->get('uuid');
        $verified = PhoneVerify::verify($phone, $code);
        if($verified instanceof \Exception){
            abort(400,$verified->getMessage());
        }

        $client = new Client(['verify' => false]);
        $login_data = [];
        $user = null;
        $email = null;

        if($request->get('provider') == 'facebook'){
            $body = $client->get('https://graph.facebook.com/v3.1/me', [
                'query' => [
                    'access_token' => $access_token,
                    'fields' => 'name,email,id'
                ]
            ])->getBody();
            $data = json_decode($body, true);
            if (isset($data['email'])) {
                $email = $data['email'];
            }
            $name = $data['name'];

            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->phone = $phone;
            $user->role_id = config('app.register_role_id');
            $user->password = \Hash::make(random_int(11111111, 99999999));
            if (isset($data['id'])) {
                $user->social_id = $data['id'];
            }
            $user->save();
            $this->syncData($user->password, $user);
        }
        else{
            $body = $client->get('https://www.googleapis.com/userinfo/v2/me', [
                'query' => [
                    'access_token' => $access_token,
                ]
            ])->getBody();
            $data = json_decode($body, true);
            if (isset($data['email'])) {
                $email = $data['email'];
            }
            $name = $data['name'];

            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->phone = $phone;
            $user->role_id = config('app.register_role_id');
            $user->password = \Hash::make(random_int(11111111, 99999999));
            $user->save();
            $this->syncData($user->password, $user);
        }

        $token = $user->createToken('customer', ['customer']);
        $stgToken = null;
        if ($uuid) {
            $stgToken = $this->loginStg($user, $uuid, 'CONSUMER');
            if (!$stgToken) {
                return \Response::json([
                    'code' => 500,
                    'message' => 'Lỗi đăng nhập hệ thống shop',
                ], 500);
            }
        }
        $this->deleteOldToken($user->id, 'customer');
        $login_data = [
            'token' => $token->accessToken,
            'user' => $this->getUserInfo($user),
            'secondaryToken' => $stgToken,
        ];

        return \Response::json($login_data);
    }

    function socialLogin(Request $request){
        \Validator::validate($request->all(), [
            'token' => ['required'],
            'provider' => ['required', 'in:facebook,google']
        ]);
        $access_token = $request->get('token');
        $rs = false;
        $client = new Client(['verify' => false]);
        $data = [];
        $login_data = [];
        $user = null;
        if($request->get('provider') == 'facebook'){
            $body = $client->get('https://graph.facebook.com/v3.1/me', [
                'query' => [
                    'access_token' => $access_token,
                    'fields' => 'email'
                ]
            ])->getBody();
            $data = json_decode($body, true);
            if (isset($data['email'])) {
                $email = $data['email'];
                $user = User::whereEmail($email)->first();
            }
        }
        else{
            $body = $client->get('https://www.googleapis.com/userinfo/v2/me', [
                'query' => [
                    'access_token' => $access_token,
                ]
            ])->getBody();
            $data = json_decode($body, true);
            if (isset($data['email'])) {
                $email = $data['email'];
                $user = User::whereEmail($email)->first();
            }
        }

        if($user){
            $rs = true;
            $token = $user->createToken('customer', ['customer']);
            $this->deleteOldToken($user->id, 'customer');
            $login_data = [
                'token' => $token->accessToken,
                'user' => $this->getUserInfo($user)
            ];
        }

        return \Response::json([
            'exist' => $rs,
            'login' => $login_data,
             'debug' => $data
        ]);
    }
    function socialLoginV2(Request $request){
        \Validator::validate($request->all(), [
            'token' => ['required'],
            'provider' => ['required', 'in:accountkit,facebook,google'],
            'email' => ['nullable', 'email'],
            'phone' => ['numeric'],
            'uuid' => ['nullable'],
        ]);
        $access_token = $request->get('token');
        $uuid = $request->get('uuid');
        $rs = false;
        $client = new Client(['verify' => false]);
        $data = [];
        $login_data = [];
        $user = null;
        
        $app_secret = env("FACEBOOK_SECRET");
        $app_id = env("FACEBOOK_ID");

        if($request->get('provider') == 'accountkit'){
            try {
                $token_exchange_url = 'https://graph.accountkit.com/v1.1/access_token';
                $txt_body_exchange = $client->get($token_exchange_url, [
                    'query' => [
                        'grant_type' => 'authorization_code',
                        'code' => $access_token,
                        'access_token' => "AA|$app_id|$app_secret"
                    ]
                ])->getBody();
                $body_exchange = json_decode($txt_body_exchange, true);
                $user_id = $body_exchange['id'];
                $access_token = $body_exchange['access_token'];
                $refresh_interval = $body_exchange['token_refresh_interval_sec'];
            } catch (BadResponseException $e) {
                return \Response::json([
                    'uri' => $e->getRequest()->getUri(),
                    'token' => $access_token,
                    'data'=> json_decode($e->getResponse()->getBody()->getContents())
                ], 400);
            }
            try {
                $body = $client->get('https://graph.accountkit.com/v1.1/me', [
                    'query' => [
                        'access_token' => $access_token
                    ]
                ])->getBody();
                $data = json_decode($body, true);
            } catch (BadResponseException $e) {
                return \Response::json([
                    'uri' => $e->getRequest()->getUri(),
                    'token' => $access_token,
                    'data'=> json_decode($e->getResponse()->getBody()->getContents())
                ], 400);
            }

            if (isset($data['error'])) {
                return \Response::json($data['error']['message'], 400);
            }

//            $email = $data['email'];
            $email = $request->get('email');

            if (isset($data['phone'])) $phone = '0'.$data['phone']['national_number'];
            else $phone = $request->get('phone');
            $name = 'iSalon Member';
            $user = User::wherePhone($phone)->first();
            if (!$user) {
                if (!$phone) {
                    return \Response::json('PROVIDE_PHONE', 422);
                }
                $user = new User();
                $user->name = $name;
                $user->email = $email;
                $user->phone = $phone;
                $user->role_id = config('app.register_role_id');
                $user->password = \Hash::make(random_int(11111111, 99999999));
                $user->save();
                $user->refresh();
                $this->syncData($user->password, $user);
            }
        } else if ($request->get('provider') == 'facebook') {
            $body = $client->get('https://graph.facebook.com/v3.1/me', [
                'query' => [
                    'access_token' => $access_token,
                    'fields' => 'email,id'
                ]
            ])->getBody();
            $data = json_decode($body, true);
            if (isset($data['email'])) {
                $email = $data['email'];
                $user = User::whereEmail($email)->first();
            } else if (isset($data['id'])) {
                $social_id = $data['id'];
                $user = User::whereSocialId($social_id)->first();
            }
        } else {
            $body = $client->get('https://www.googleapis.com/userinfo/v2/me', [
                'query' => [
                    'access_token' => $access_token,
                ]
            ])->getBody();
            $data = json_decode($body, true);
            $email = $data['email'];
            $user = User::whereEmail($email)->first();
        }

        if($user){
            $rs = true;
            $token = $user->createToken('customer', ['customer']);
            $this->deleteOldToken($user->id, 'customer');
            Auth::login($user, config('app.register_remember_login'));
            $stgToken = null;
            if ($uuid) {
                $stgToken = $this->loginStg($user, $uuid, 'CONSUMER');
                if (!$stgToken) {
                    return \Response::json([
                        'code' => 500,
                        'message' => 'Lỗi đăng nhập hệ thống shop',
                    ], 500);
                }
            }
            $login_data = [
                'token' => $token->accessToken,
                'user' => $this->getUserInfo($user),
                'secondaryToken' => $stgToken,
            ];
        }

        return \Response::json([
            'exist' => $rs,
            'login' => $login_data,
            'debug' => $data,
            'checkLogin' => Auth::check(),
            'ssid' => \Session::getId(),
        ]);
    }

    private function loginFirebase($id_token, $uuid, $email, $phone) {
        $client = new Client(['verify' => false]);
        $user = null;
        $login_data = [];
        $data = [];
        $rs = false;
        $stgToken = null;

        $server_key = env("FIREBASE_SERVER_KEY");
        try {
            $verify_token_url = 'https://identitytoolkit.googleapis.com/v1/accounts:lookup';
            $verify_user_result = $client->post($verify_token_url, [
                'query' => [
                    'idToken' => $id_token,
                    'key' => $server_key
                ]
            ])->getBody();
            $data = json_decode($verify_user_result, true);
            $users = $data['users'];
            $verified_user = null;
            if (count($users)) {
                $verified_user = $users[0];
            }

            if ($verified_user) {
                if (isset($verified_user['phoneNumber'])) {
                    $phone = str_replace("+84", "0", $verified_user['phoneNumber']);
                }
                $name = 'iSalon Member';
                $user = User::wherePhone($phone)->first();
                if (!$user) {
                    //try to create account if not exist
                    if (!$phone) {
                        return \Response::json('PROVIDE_PHONE', 422);
                    }
                    $user = new User();
                    $user->name = $name;
                    $user->email = $email;
                    $user->phone = $phone;
                    $user->role_id = config('app.register_role_id');
                    $user->password = \Hash::make(random_int(11111111, 99999999));
                    $user->save();
                    $user->refresh();
                    $this->syncData($user->password, $user);
                }
            }

        } catch (BadResponseException $e) {
            return \Response::json([
                'uri' => $e->getRequest()->getUri(),
                'idToken' => $id_token,
                'data'=> json_decode($e->getResponse()->getBody()->getContents())
            ], 400);
        }

        if($user){
            $rs = true;
            $token = $user->createToken('customer', ['customer']);
            $this->deleteOldToken($user->id, 'customer');
            Auth::login($user, config('app.register_remember_login'));
            $stgToken = null;
            if ($uuid) {
                $stgToken = $this->loginStg($user, $uuid, 'CONSUMER');
                if (!$stgToken) {
                    return \Response::json([
                        'code' => 500,
                        'message' => 'Lỗi đăng nhập hệ thống shop',
                    ], 500);
                }
            }
            $login_data = [
                'token' => $token->accessToken,
                'user' => $this->getUserInfo($user),
                'secondaryToken' => $stgToken,
            ];
        }

        return \Response::json([
            'exist' => $rs,
            'login' => $login_data,
            'debug' => $data,
            'checkLogin' => Auth::check(),
            'ssid' => \Session::getId(),
        ]);
    }

    function loginWithFirebase(Request $request){
        \Validator::validate($request->all(), [
            'idToken' => ['required'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable'],
            'uuid' => ['nullable'],
        ]);
        $id_token = $request->get('idToken');
        $uuid = $request->get('uuid');
        $email = $request->get('email');
        $phone = $request->get('phone');
        return $this->loginFirebase($id_token, $uuid, $email, $phone);
    }

    function sendVerificationCode(Request $request){
        \Validator::validate($request->all(), [
            'recaptchaToken' => ['nullable'],
            'phone' => ['required'],
            'retry' => ['nullable', 'numeric'],
        ]);
        $token = $request->get('recaptchaToken');
        $phone = $request->get('phone');
        $retry = $request->get('retry');
        $client = new Client(['verify' => false]);

        $data = [];
        $type = "firebase";

        $server_key = env("FIREBASE_SERVER_KEY");
        try {

            if ($retry && $retry == 1) {
                throw new \Exception('retry send sms by smsbrandname', 400);
            }

            if (str_start($phone, "0")) {
                $phone = implode("+84", explode("0", $phone, 1));
            }

            $body = [
                'phoneNumber' => $phone,
            ];
            if ($token) {
                $body['recaptchaToken'] = $token;
            }
//            \Log::debug('sendVerificationCode req body: ', $body);
            $verify_token_url = 'https://www.googleapis.com/identitytoolkit/v3/relyingparty/sendVerificationCode';
            $verify_user_result = $client->post($verify_token_url, [
                'query' => [
                    'key' => $server_key,
                ],
                'body' => json_encode($body)
            ])->getBody();
            $data = json_decode($verify_user_result, true);
//            \Log::debug('data:', $data);
            if ($data && isset($data['sessionInfo'])) {
                // ok
            } else {
                // switch to smsbrandname
                $type = "smsbrandname";
                PhoneVerify::newVerify($phone);
            }
        } catch (\Exception $e) {
            \Log::debug('identity toolkit failed: '. $e->getMessage());
            // switch to smsbrandname
            $type = "smsbrandname";
            $phone = str_replace("+84", "0", $phone);
            PhoneVerify::newVerify($phone);
        }

        return \Response::json([
            'data' => $data,
            'type' => $type,
        ]);
    }

    function loginWithFirebaseOrSms(Request $request){
        \Validator::validate($request->all(), [
            'type' => ['required'],
            'phone' => ['nullable'],
            'code' => ['nullable'],
            'token' => ['nullable'],
            'uuid' => ['nullable'],
        ]);
        $type = $request->get('type');
        $sessionInfoToken = $request->get('token');
        $code = $request->get('code');
        $uuid = $request->get('uuid');
        $phone = $request->get('phone');

        $client = new Client(['verify' => false]);
        $user = null;
        $login_data = [];
        $data = [];
        $rs = false;
        $stgToken = null;

        if ($type == 'firebase') {
            $server_key = env("FIREBASE_SERVER_KEY");
            try {
                $body = [
                    'sessionInfo' => $sessionInfoToken,
                    'code' => $code,
                ];
                $verify_token_url = 'https://www.googleapis.com/identitytoolkit/v3/relyingparty/verifyPhoneNumber';
                $verify_user_result = $client->post($verify_token_url, [
                    'query' => [
                        'key' => $server_key
                    ],
                    'body' => json_encode($body)
                ])->getBody();
                $data = json_decode($verify_user_result, true);
                if ($data && isset($data['idToken'])) {
                    $idToken = $data['idToken'];
                    return $this->loginFirebase($idToken, $uuid, null, $phone);
                }

            } catch (BadResponseException $e) {
                return \Response::json([
                    'uri' => $e->getRequest()->getUri(),
                    'token' => $sessionInfoToken,
                    'data' => json_decode($e->getResponse()->getBody()->getContents())
                ], 400);
            }
        } else {
            $phone = str_replace("+84", "0", $phone);
            $verified = PhoneVerify::verify($phone, $code);
            if($verified instanceof \Exception){
                abort(400,$verified->getMessage());
            }

            $name = 'iSalon Member';
            $user = User::wherePhone($phone)->first();
            if (!$user) {
                //try to create account if not exist
                if (!$phone) {
                    return \Response::json('PROVIDE_PHONE', 422);
                }
                $user = new User();
                $user->name = $name;
                $user->phone = $phone;
                $user->role_id = config('app.register_role_id');
                $user->password = \Hash::make(random_int(11111111, 99999999));
                $user->save();
                $user->refresh();
                $this->syncData($user->password, $user);
            }
        }

        if($user) {
            $rs = true;
            $token = $user->createToken('customer', ['customer']);
            $this->deleteOldToken($user->id, 'customer');
            Auth::login($user, config('app.register_remember_login'));
            $stgToken = null;
            if ($uuid) {
                $stgToken = $this->loginStg($user, $uuid, 'CONSUMER');
                if (!$stgToken) {
                    return \Response::json([
                        'code' => 500,
                        'message' => 'Lỗi đăng nhập hệ thống shop',
                    ], 500);
                }
            }
            $login_data = [
                'token' => $token->accessToken,
                'user' => $this->getUserInfo($user),
                'secondaryToken' => $stgToken,
            ];
        }

        return \Response::json([
            'exist' => $rs,
            'login' => $login_data,
            'debug' => $data,
            'checkLogin' => Auth::check(),
            'ssid' => \Session::getId(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws AuthenticationException
     */
    function login(Request $request){
        $username = $request->get('username');
        $password = $request->get('password');
        $rs = \Auth::attempt([
           is_numeric($username)?'phone':'email' => $username,
           'password' => $password
        ]);
        if($rs){
            $user = \Auth::user();
            $token = $user->createToken('customer', ['customer']);
            $this->deleteOldToken($user->id, 'customer');
            return \Response::json([
                'token' => $token->accessToken,
                'user' => $this->getUserInfo()
            ]);
        }
        throw new AuthenticationException('Thông tin đăng nhập không chính xác, vui lòng thử lại hoặc nhấn link "quên mật khẩu"');
    }

    function deleteOldToken($user_id, $name){
        $latest = \DB::table('oauth_access_tokens')
            ->where('name', $name)
            ->where('user_id', '=', $user_id)
            ->orderBy('created_at', 'desc')
            ->limit(20)
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

    private function getUserInfo(User $user=null){
        if(!$user){
            $user = me();
        }
        return [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar' => $user->avatar?$user->avatar->getThumbnailUrl('default', getNoAvatarUrl()):getNoAvatarUrl()
        ];
    }

    function getAccountInfo(Request $request){

        return \Response::json($this->getUserInfo());
    }

    private function getRegisterMessageRules($only = false){
        $rs = [
            'rules' => [],
            'messages' => []
        ];
        $temp_rules = [
            'phone' =>
                [
                    'required',
                    'numeric',
                    'unique:users,phone'
                ],
            'password' => [
                'required',
                'min:6',
            ]
        ];
        $temp_msg = [
            'phone.required' => 'Thông tin số điện thoại không được trống',
            'phone.numeric' => 'Số điện thoại không hợp lệ',
            'phone.unique' => 'Số điện thoại này đã được đăng ký',
            'password.required' => 'Thông tin mật khẩu không được trống',
            'password.min' => 'Mật khẩu phải từ 6 ký tự',
        ];
        if($only == 'login'){
            return [
                'rules' => $temp_rules,
                'messages' => $temp_msg
            ];
        }
        $rs['rules'] = array_merge($rs['rules'], $temp_rules);
        $rs['messages'] = array_merge($rs['messages'], $temp_msg);

        $temp_rules = [
            'email' =>
                [
                    'required',
                    'email',
                    'unique:users,email'
                ],
            'name' => [
                'required',
                'min:2',
            ]
        ];
        $temp_msg = [
            'email.required' => 'Email không được bỏ trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'name.min' => 'Tên phải từ 2 ký tự',
            'name.required' => 'Họ tên không được bỏ trống',
        ];

        if($only == 'info_step_1'){
            return [
                'rules' => $temp_rules,
                'messages' => $temp_msg
            ];
        }

        $rs['rules'] = array_merge($rs['rules'], $temp_rules);
        $rs['messages'] = array_merge($rs['messages'], $temp_msg);

        $temp_rules = [
//            'address' => [
//                'required',
//                'min:4',
//            ],
//            'lv1' => [
//                'required',
//                'numeric'
//            ],
//            'lv2' => [
//                'required',
//                'numeric'
//            ],
//            'lv3' => [
//                'required',
//                'numeric'
//            ],
        ];

        $temp_msg = [
//            'address.required' => 'Vui lòng nhập địa chỉ',
//            'address.min' => 'Địa chỉ quá ngắn',
//            'lv1.required' => 'vui lòng chọn tỉnh/thành phố',
//            'lv1.numeric' => 'Tỉnh/thành phố không hợp lệ',
//            'lv2.required' => 'Vui lòng chọn quận/huyện',
//            'lv2.numeric' => 'Quận/huyện không hợp lệ',
//            'lv3.required' => 'Vui lòng chọn phường xã',
//            'lv3.numeric' => 'Phường/xã không hợp lệ'
        ];

        if($only == 'info_step_2'){
            return [
                'rules' => $temp_rules,
                'messages' => $temp_msg
            ];
        }

        $rs['rules'] = array_merge($rs['rules'], $temp_rules);
        $rs['messages'] = array_merge($rs['messages'], $temp_msg);

        return $rs;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    function checkRegister(Request $request){
        $rule_message = $this->getRegisterMessageRules('login');
        \Validator::validate($request->all(), $rule_message['rules'], $rule_message['messages']);
        return \Response::json(true);
    }

    function sendPhoneVerify(Request $request){
        $phone = $request->get('phone');
        if(!$phone){
            abort(400, 'Vui lòng nhập số điện thoại');
        }
        if(!is_numeric($phone)){
            abort(400, 'Số điện thoại không hợp lệ');
        }
        if(User::wherePhone($phone)->count()>0){
            abort(400, 'Số điện thoại này đã được sử dụng, vui lòng dùng số khác');
        }
        $vrs = PhoneVerify::newVerify($phone);
        if($vrs instanceof \Exception){
            //comment when testing
            abort(400,$vrs->getMessage());
        }
        return \Response::json(true);
    }

    function verifyPhoneCode(Request $request){
        $code = $request->get('code');
        $phone = $request->get('phone');
        $verified = PhoneVerify::verify($phone, $code);
        if($verified instanceof \Exception){
            //comment when testing
            abort(400,$verified->getMessage());
        }
        return \Response::json(true);
    }



    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    function verifyInfoStepOne(Request $request){
        $name = $request->get('name');
        $email = $request->get('email');
        $rule_message = $this->getRegisterMessageRules('info_step_1');
        \Validator::validate($request->all(), $rule_message['rules'], $rule_message['messages']);
        return \Response::json(true);
    }

    function locationLv1(Request $request){
        $rs = DiaPhuongTinhThanhPho::all(['id', 'name'])->map(function($item){
            return [
                'label' => $item->name,
                'value' => ''.$item->id
            ];
        });
        return \Response::json($rs);
    }

    function locationLv2(Request $request){
        $parent_lv = $request->get('parent_lv');
        $rs = DiaPhuongQuanHuyen::getQuery()->where('matp', $parent_lv)->get(['id', 'name'])->map(function($item){
            return [
                'label' => $item->name,
                'value' => ''.$item->id.''
            ];
        });
        return \Response::json($rs);
    }

    function locationLv3(Request $request){
        $parent_lv = $request->get('parent_lv');
        $rs = DiaPhuongXaPhuongThiTran::getQuery()->where('maqh', $parent_lv)->get(['id', 'name'])->map(function($item){
            return [
                'label' => $item->name,
                'value' => ''.$item->id.''
            ];
        });
        return \Response::json($rs);
    }

    /**
     * @param Request $request
     * @throws \Illuminate\Validation\ValidationException
     */
    function verifyInfoStepTwo(Request $request){
        $address = $request->get('address');
        $lv1 = $request->get('lv1');
        $lv2 = $request->get('lv2');
        $lv3 = $request->get('lv3');
        $rule_message = $this->getRegisterMessageRules('info_step_2');
        \Validator::validate($request->all(),
            $rule_message['rules'],
            $rule_message['messages']
        );
    }

    function getJoinTos(Request $request){
        $rs = getSetting('theme_mobile_join_tos', '');
        return \Response::json($rs);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    function createAccount(Request $request){
        $username = $request->get('username');
        $password = $request->get('password');
        $phone = $request->get('phone');
        $code = $request->get('code');
        //$address = $request->get('address');
        //$lv1 = $request->get('lv1');
        //$lv2 = $request->get('lv2');
        //$lv3 = $request->get('lv3');
        $avatar = $request->file('avatar');
        $email = $request->get('email');
        $name = $request->get('name');
        $rules_messages = $this->getRegisterMessageRules();
        $rules = $rules_messages['rules'];
        $messages = $rules_messages['messages'];
        \Validator::validate($request->all(), $rules,$messages);
        $verified = PhoneVerify::verify($phone, $code);
        if($verified instanceof \Exception){
            //comment when testing
            abort(400,$verified->getMessage());
        }
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->phone = $phone;
        $user->role_id = config('app.register_role_id');
        $user->password = \Hash::make($password);
        $user->save();
        if($avatar){
            $uploaded_avatar = UploadedFile::upload($avatar, $user->id,'user_avatar');
            $user->avatar_id = $uploaded_avatar->id;
            $user->save();
        }
        //$contact = new UserAddress();
        //$contact->address = $address;
        //$contact->address_lv1 = $lv1;
        //$contact->address_lv2 = $lv2;
        //$contact->address_lv3 = $lv3;
        //$contact->name = $name;
        //$contact->email = $email;
        //$contact->phone = $phone;
       //$contact->user_id = $user->id;
        //$contact->save();

        $token = $user->createToken('customer', ['customer']);
        return \Response::json([
            'token' => $token->accessToken,
            'user' => $this->getUserInfo($user)
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    function resetPassword(Request $request){
        $username = $request->get('username');
        \Validator::validate($request->all(), [
            'username' => ['required', 'exists:users,'.(is_numeric($username)?'phone':'email')]
        ], [
            'username.required' => 'Vui lòng nhập email hoặc số điện thoại',
            'username.exists' => 'Số điện thoại hoặc email không tồn tại trong hệ thống'
        ]);
        $user = User::getQuery()->where((is_numeric($username)?'phone':'email'), $username)->first();
        $vrs = PhoneVerify::newVerify($user->phone);
        if($vrs instanceof \Exception){
            abort(400,$vrs->getMessage());
        }
        return \Response::json($user->phone);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    function newPassword(Request $request){
        $phone = $request->get('phone');
        $code = $request->get('code');
        $password = $request->get('password');
        \Validator::validate($request->all(), [
            'password' => [
                'required',
                'min:6',
                'confirmed'
            ]
        ],  [
            'password.required' => 'Thông tin mật khẩu không được trống',
            'password.min' => 'Mật khẩu phải từ 6 ký tự',
            'password.confirmed' => 'Mật khẩu và xác nhận không khớp'
        ]);
        $verified = PhoneVerify::verify($phone, $code);
        if($verified instanceof \Exception){
            abort(400,$verified->getMessage());
        }
        /** @var User $user */
        $user = User::wherePhone($phone)->first();
        if(!$user){
            abort(400, 'Tài khoản không tồn tại');
        }
        $user->password = \Hash::make($password);
        $user->save();
        return \Response::json(true);
    }
    function testSMS(Request $request) {
        $phone = $request->get('phone');
        $code = $request->get('code');
        $password = $request->get('password');
        \Validator::validate($request->all(), [
            'message' => [
                'required',
                'min:6',
                'min:23'
            ],
            'phone' => [
                'required',
                'min:10',
                'min:11'
            ]
        ],  [
            'message.required' => 'Message không được trống',
            'phone.required' => 'Phone không được trống',
            'message.min' => 'Message phải từ 6 ký tự',
            'phone.min' => 'Phone phải từ 10 ký tự',
            'message.max' => 'Message phải ít hơn 24 ký tự',
            'phone.max' => 'Phone phải ít hơn 12 ký tự',
        ]);
        $phone = $request->get('phone');
        $message = $request->get('message');

        try{
            $controller = new BrandSmsController();
            $rs = $controller->sendSms($phone, $message);
            if($rs instanceof \Exception){
               return \Response::json($rs);
            }
            return \Response::json('message send');
        }
        catch (\Exception $exception){
            return \Response::json(true);
        }
    }
}
