<?php

namespace Modules\ModHairWorld\Entities;


use Illuminate\Database\Eloquent\Model;


/**
 * Modules\ModHairWorld\Entities\UserAddress
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $address
 * @property int|null $address_lv1
 * @property int|null $address_lv2
 * @property int|null $address_lv3
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserAddress whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserAddress whereAddressLv1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserAddress whereAddressLv2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserAddress whereAddressLv3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserAddress whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserAddress whereUserId($value)
 * @property-read DiaPhuongTinhThanhPho|null $lv1
 * @property-read DiaPhuongQuanHuyen|null $lv2
 * @property-read DiaPhuongXaPhuongThiTran|null $lv3
 * @mixin \Eloquent
 * @property string|null $name
 * @property string|null $email
 * @property string|null $phone
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserAddress whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserAddress whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserAddress wherePhone($value)
 */
class UserAddress extends Model
{
    public $timestamps = false;

    function lv1(){
        return $this->hasOne(DiaPhuongTinhThanhPho::class,'id', 'address_lv1');
    }

    function lv2(){
        return $this->hasOne(DiaPhuongQuanHuyen::class,'id', 'address_lv2');
    }

    function lv3(){
        return $this->hasOne(DiaPhuongXaPhuongThiTran::class,'id', 'address_lv3');
    }

    public function getLV1Text(){
        $rs = '';
        $model = $this->lv1;
        if($model){
            $rs = $model->name;
        }
        return $rs;
    }

    public function getLV2Text(){
        $rs = '';
        $model = $this->lv2;
        if($model){
            $rs = $model->name;
        }
        return $rs;
    }

    public function getLV3Text(){
        $rs = '';
        $model = $this->lv3;
        if($model){
            $rs = $model->name;
        }
        return $rs;
    }

    public function getAddressLine(){
        $rs = '';
        $tinh = $this->getLV1Text();
        $quan = $this->getLV2Text();
        $phuong = $this->getLV3Text();
        $address = $this->address;
        if($tinh){
            $rs = $tinh;
            if($quan){
                $rs = $quan . ', ' . $rs;
                if($phuong){
                    $rs = $phuong . ', ' . $rs;
                    if($address){
                        $rs = $address . ', ' . $rs;
                    }
                }
            }
        }
        return $rs;
    }
}