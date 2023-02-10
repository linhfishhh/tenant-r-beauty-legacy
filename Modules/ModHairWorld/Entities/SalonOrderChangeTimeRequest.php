<?php

namespace Modules\ModHairWorld\Entities;


use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\ChangeTimeRequestCreated;

/**
 * Modules\ModHairWorld\Entities\SalonOrderChangeTimeRequest
 *
 * @property int $id
 * @property int $order_id
 * @property \Carbon\Carbon $service_time
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderChangeTimeRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderChangeTimeRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderChangeTimeRequest whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderChangeTimeRequest whereServiceTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderChangeTimeRequest whereUpdatedAt($value)
 * @property-read SalonOrder $order
 * @mixin \Eloquent
 */
class SalonOrderChangeTimeRequest extends Model
{
    public $timestamps = [
        'created_at',
        'updated_at',
    ];

    protected $dates =[
        'service_time'
    ];


    protected $dispatchesEvents = [
        'created' => ChangeTimeRequestCreated::class
    ];

    function order(){
        return $this->hasOne(SalonOrder::class, 'id', 'order_id');
    }
}