<?php

namespace App\Http\Controllers\Auth;

use App\Events\SocialLoginSuccess;
use App\Events\SocialRegisterSuccess;
use App\Events\SocialRegisterSuccessNoAccount;
use App\Http\Controllers\Controller;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Two\GoogleProvider;
use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Modules\ModHairWorld\Handlers\AuthHandler;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    use AuthHandler;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function social(Request $request, $provider){
        /** @var GoogleProvider $google */
        $google = \Socialite::driver($provider);
        $url = route('frontend.login.social.check', ['provider' => $provider]);
        $request->session()->flash('go_to', $request->get('go_to'));
        return $google->redirectUrl($url)->redirect();
    }

    /**
     * @param Request $request
     * @param $provider
     * @return \Illuminate\Http\RedirectResponse
     * @throws InternalErrorException
     */
    public function socialCheck(Request $request, $provider){
        $http = new Client([
                               'verify' => false
                           ]);
        /** @var GoogleProvider $google */
        $google = \Socialite::driver($provider);
        $url = route('frontend.login.social.check', ['provider' => $provider]);
        $google->redirectUrl($url);
        $google->setHttpClient($http);
        $user = $google->user();
        $go_to_url = $request->session()->get('go_to');
        $request->session()->forget(['go_to']);
        if(!$user){
            //throw new InternalErrorException('Lỗi kết nối đến tài khoản mạng xã hội');
            if($go_to_url) {
                return redirect()->to($go_to_url)->with('social_connect_message_error', 'Lỗi kết nối đến tài khoản mạng xã hội');
            }
        }
        $account = User::whereEmail($user->getEmail())->first();
        if($account){
            \Auth::login($account,config('app.register_remember_login'));
            event(new SocialLoginSuccess($request, $user, $account));
            $social_connect_message = __('Đã kết nối đến tài khoản mạng xã hội và đăng nhập thành công!');
        }
        else{
            if(config('app.social_login_create_account', 1)){
                $account = new User();
                $account->email = $user->getEmail();
                $account->name = $user->getName();
                $password = bcrypt(str_random(8));
                $account->password = $password;
                $role_id = getSetting(
                    'new_register_role_id',
                    config('app.register_role_id')
                );
                $account->role_id = $role_id;
                if(!$account->email){
                    //throw new InternalErrorException('Lỗi kết nối đến tài khoản mạng xã hội, lưu ý để kết nối yêu cầu tài khoản mạng xã hội phải có email');
                    if($go_to_url) {
                        return redirect()->to($go_to_url)->with('social_connect_message_error', 'Lỗi kết nối đến tài khoản mạng xã hội, lưu ý để kết nối yêu cầu tài khoản mạng xã hội phải có email, nếu tài khoản không có email xin vui lòng dùng phương thức khác');
                    }
                }
                $account->save();
                event(new SocialRegisterSuccess($request, $user, $account));
                \Auth::login($account, config('app.register_remember_login'));
                event(new SocialLoginSuccess($request, $user, $account));
                $social_connect_message = __('Đã kết nối đến tài khoản mạng xã hội thành công, tài khoản mới của bạn tại website chúng tôi đã được tạo!');
            }
            else{
                event(new SocialRegisterSuccessNoAccount($request, $user));
                $social_connect_message = __('Đã kết nối đến tài khoản mạng xã hội và đăng nhập thành công!');
            }
        }
        if($go_to_url){
            return redirect()->to($go_to_url)->with('social_connect_message', $social_connect_message);
        }
        return \Redirect::route('frontend.index');
    }

    function socialLoginV2(Request $request){
        \Validator::validate($request->all(), [
            'token' => ['required'],
            'provider' => ['required', 'in:accountkit,facebook,google'],
            'email' => ['nullable', 'email'],
            'phone' => ['numeric'],
        ]);
        $access_token = $request->get('token');
        $rs = false;
        $client = new Client(['verify' => false]);
        $data = [];
        $login_data = [];
        $user = null;

        $app_secret = "76af20048e9a99473885e9a70538d2ed";
        $app_id = '1017282308444421';
        if ($request->get('provider') == 'accountkit'){
            // Exchange authorization code for access token
            try {
                $token_exchange_url = 'https://graph.accountkit.com/v1.1/access_token';
//                return \Response::json($access_token);
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
        }
        if($request->get('provider') == 'facebook' || $request->get('provider') == 'accountkit'){

            $appsecret_proof = hash_hmac('sha256', $access_token, $app_secret);
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
        else{
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
//            $token = $user->createToken('customer', ['customer']);
//            $this->deleteOldToken($user->id, 'customer');
//            $sessionId = \Session::getId();
            \Auth::guard('web')->login($user, config('app.register_remember_login'));
//            \Session::setId($sessionId);
//            \Session::start();
            $login_data = [
//                'token' => $token->accessToken,
                'user' => $this->getUserInfo($user)
            ];
        }

        return response()->json([
            'exist' => $rs,
            'login' => $login_data,
            'debug' => $data,
            'checkLogin' => Auth::check(),
            'ssid' => \Session::getId(),
        ]);
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
}
