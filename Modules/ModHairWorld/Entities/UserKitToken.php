<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 10/22/18
 * Time: 17:19
 */

namespace Modules\ModHairWorld\Entities;


use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\UserKitToken
 *
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserKitToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserKitToken whereToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserKitToken whereUserId($value)
 * @mixin \Eloquent
 */
class UserKitToken extends Model
{
    public $timestamps = false;

    public static function newToken(User $user){
        $token = static::whereUserId($user->id)->first();
        if(!$token){
            $token = new static();
            $token->user_id = $user->id;
        }
        $token->token = \Hash::make(rand(111111, 999999));
        $token->save();
        return $token->token;
    }

    public static function verify($user_id,  $token){
        $rs = true;
        $m = static::whereUserId($user_id)->where('token', $token)->first();
        if(!$m){
            $rs = false;
        }
        return $rs;
    }
}