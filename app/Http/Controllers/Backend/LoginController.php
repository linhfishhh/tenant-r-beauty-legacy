<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = route('backend.index');
    }

    public function showLoginForm()
    {
        return view('backend.pages.login');
    }

    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        $this->clearLoginAttempts($request);
        /** @var User $user */
        $user = $this->guard()->user();
        $has_access = $user->hasPermission('access_backend');
        if($request->ajax()){
            if($has_access){
                $path = $request->session()->pull('url.intended', $this->redirectPath());
            }
            else{
                $path = route('frontend.index');
            }
            return \Response::json([
                'RedirectTo' => $path,
            ]);

        }
        if($has_access){
            $path = $this->redirectPath();
        }
        else{
            $path = route('frontend.index');
        }
        return $this->authenticated($request, $this->guard()->user())
            ?: redirect()->intended($path);
    }
}
