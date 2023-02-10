<?php

namespace Modules\ModHairWorld\Http\Controllers\ApiAdmin;


use App\Http\Controllers\Controller;
use App\User;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  function login(Request $request){
    $username = $request->get('username');
    $password = $request->get('password');

    $rs = \Auth::attempt([
     is_numeric($username)?'phone':'email' => $username,
     'password' => $password
    ]);
    if($rs) {
      $user = \Auth::user();
      $token = $user->createToken('admin', ['admin']);
      $this->deleteOldToken($user->id, 'admin');
      return \Response::json([
        'token' => $token->accessToken,
      ]);
    }
    throw new AuthenticationException('Thông tin đăng nhập không chính xác');
  }

  private function deleteOldToken($user_id, $name){
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
}