<?php

namespace Modules\ModHairWorld\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\BrandSmsErrorLog
 *
 * @property int $id
 * @property string $phone
 * @property string $message
 * @property string $error_message
 * @property string $error_data
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\BrandSmsErrorLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\BrandSmsErrorLog whereErrorData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\BrandSmsErrorLog whereErrorMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\BrandSmsErrorLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\BrandSmsErrorLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\BrandSmsErrorLog wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\BrandSmsErrorLog whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BrandSmsErrorLog extends Model
{
    protected $table = 'brandsms_error_logs';
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public static function newLog($phone, $message, $error_message, $error_data){
        $new = new static();
        $new->phone = $phone;
        $new->message = $message;
        $new->error_message = $error_message;
        $new->error_data = $error_data;
        $new->save();
    }
}