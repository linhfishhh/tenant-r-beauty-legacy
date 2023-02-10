<?php

namespace Modules\ModHairWorld\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * \Modules\ModHairWorld\Entities\UserDevice
 *
 * @property int $id
 * @property string $scope
 * @property int $user_id
 * @property string $device_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserDevice whereDeviceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserDevice whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\UserDevice whereUserId($value)
 * @mixin \Eloquent
 */
class UserDevice extends Model
{
    public $timestamps = false;
    protected $table = 'user_devices';
}