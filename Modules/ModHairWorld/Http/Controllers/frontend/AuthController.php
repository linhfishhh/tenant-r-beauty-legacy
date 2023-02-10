<?php
namespace Modules\ModHairWorld\Http\Controllers\frontend;


use App\Http\Controllers\Backend\LoginController;
use App\User;
use Illuminate\Http\Request;

class AuthController extends LoginController
{

    protected $redirectTo = '/';
    public function __construct()
    {
        parent::__construct();
        $this->redirectTo = route('frontend.index');
    }
    public function username()
    {
        return 'login';
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string',
        ],[
            $this->username().'.required' => __('Vui lòng nhập thông tin này'),
            $this->username().'.string' => __('Thông tin này không hợp lệ'),
            'password.required' => __('Vui lòng nhập thông tin này'),
            'password.string' => __('Thông tin này không hợp lệ'),
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        if($this->guard()->attempt(
            [
                'email' => $request->get('login'),
                'password' => $request->get('password')
            ], $request->filled('remember')
        )){
            return true;
        }

        if($this->guard()->attempt(
            [
                'phone' => $request->get('login'),
                'password' => $request->get('password')
            ], $request->filled('remember')
        )){
            return true;
        }

        return false;
    }
}