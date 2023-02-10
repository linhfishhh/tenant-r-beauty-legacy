<?php

namespace Modules\ModHairWorld\Entities\Api;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Http\Controllers\BrandSmsController;

/**
 * Modules\ModHairWorld\Entities\Api\PhoneVerify
 *
 * @property int $id
 * @property string $phone
 * @property string $code
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Api\PhoneVerify whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Api\PhoneVerify whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Api\PhoneVerify whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Api\PhoneVerify wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Api\PhoneVerify whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PhoneVerify extends Model
{
    protected $table = 'phone_verifies';
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    static function newVerify($phone){
        $code = rand(100000, 999999);
        //$code = 123456;
        $setting = getSetting('sms_interval',0);
        if($setting<0){
            return new \Exception('Hệ thống gửi tin nhắn xác nhận tạm thời bảo trì, vui lòng thử lại sau', 400);
        }
        if($setting != 0){
            /** @var PhoneVerify $last_verify */
            $last_verify = PhoneVerify::where('phone', $phone)->orderBy('created_at','desc')->first();
            if($last_verify){
                \Log::info("{$phone}: {$last_verify->created_at->format('H:i:s d/m/Y')}");
                $now = Carbon::now();
                if($now->subMinute($setting)->lessThan($last_verify->created_at)){
                    $now = Carbon::now();
                    $ta = $last_verify->created_at->addMinute($setting);
                    $interval = $ta->diffAsCarbonInterval($now);
                    $interval_text = $interval->totalMinutes<1?round($interval->totalSeconds).' giây':round($interval->totalMinutes).' phút';
                    return new \Exception("Hệ thống chỉ có thể gửi 1 tin nhắn xác nhận đến 1 thuê bao trong vòng {$setting} phút, vui lòng thử lại sau: {$interval_text}", 400);
                }
            }
        }
        $controller = new BrandSmsController();
        $message = $code.' la ma OTP xac thuc dang nhap iSalon cua ban';
        $sms_result = $controller->sendSms($phone, $message);
        if(!$sms_result instanceof \Exception){
            static::getQuery()->where('phone', $phone)->delete();
            $verify = new static();
            $verify->phone = $phone;
            $verify->code = bcrypt($code);
            $verify->save();
        } else {
            \Log::info('error on sendSMS: '.$sms_result->getMessage());
        }
        return $sms_result;
    }

    static function verify($phone, $code){
        $setting = getSetting('verify_code_life', 10);
        $rs = static::getQuery()->where('phone', $phone)
            ->whereDate('created_at', '<=', Carbon::now()->subMinutes($setting))
            ->first();
        if(!$rs){
            return new \Exception("Mã xác nhận không chính xác hoặc đã quá {$setting} phút không được sử dụng");
        }
        return \Hash::check($code, $rs->code)?true:new \Exception("Mã xác nhận không chính xác hoặc đã quá {$setting} phút không được sử dụng");;
    }
}