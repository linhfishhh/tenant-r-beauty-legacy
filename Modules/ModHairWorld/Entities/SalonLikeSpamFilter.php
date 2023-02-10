<?php

namespace Modules\ModHairWorld\Entities;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\SalonLikeSpamFilter
 *
 * @property int $id
 * @property int $user_id
 * @property int $target_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonLikeSpamFilter whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonLikeSpamFilter whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonLikeSpamFilter whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonLikeSpamFilter whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonLikeSpamFilter whereUserId($value)
 * @mixin \Eloquent
 */
class SalonLikeSpamFilter extends Model
{
    protected $table = 'salon_like_spam_filters';
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    static function createFilter($target_id, $user_id){
        $check = static::whereUserId($user_id)->where('target_id', $target_id)->first();
        if($check){
           $minute = getSetting('notification_interval', 0);
           if(Carbon::now()->subMinute($minute)->lessThanOrEqualTo($check->created_at)){
               return false;
           }
           else{
               $check->delete();
           }
        }
        $new_check = new static();
        $new_check->user_id = $user_id;
        $new_check->target_id = $target_id;
        $new_check->save();
        return true;
    }

}