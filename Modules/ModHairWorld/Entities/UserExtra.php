<?php

namespace Modules\ModHairWorld\Entities;


use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\UserExtra
 *
 * @property int $id
 * @property int $user_id
 * @property int|null $gender
 * @property Carbon|null $birthday
 * @property string|null $payment_method
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserExtra whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserExtra whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserExtra whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserExtra wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserExtra whereUserId($value)
 * @mixin \Eloquent
 */
class UserExtra extends Model
{
    public $timestamps = false;
    protected $table = 'user_extras';
    protected $dates = [
        'birthday'
    ];

    static function fromUserID($user_id){
        $rs =  UserExtra::find($user_id);
        if(!$rs){
            $rs = new UserExtra;
            $rs->id = $user_id;
        }
        return $rs;
    }

    function genderText(){
        $rs = 'Chưa xác định';
        switch ($this->gender){
            case 0:
                $rs = 'Nữ';
                break;
            case 1:
                $rs = 'Nam';
                break;
            case 2:
                $rs = 'Khác';
                break;

        }
        return $rs;
    }

    function paymentMethodText(){
        $rs = 'Chưa xác định';
        switch ($this->payment_method){
            case 'salon':
                $rs = 'Tại salon';
                break;
            case 'nganluong':
                $rs = 'nganluong.vn';
                break;
        }
        return $rs;
    }
}