<?php
namespace Modules\ModHairWorld\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\SalonOrderItem
 *
 * @property int $id
 * @property int $order_id
 * @property int $service_id
 * @property string $service_name
 * @property int $quatity
 * @property int $price
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderItem whereQuatity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderItem whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderItem whereServiceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderItem whereUpdatedAt($value)
 * @property-read SalonService|null $service
 * @property-read SalonServiceReview|null $reviewedByMe
 * @mixin \Eloquent
 */
class SalonOrderItem extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    function service(){
        return $this->hasOne(SalonService::class,'id', 'service_id');
    }

    function review(){
        return $this->hasMany(SalonServiceReview::class, 'service_id', 'service_id');
    }
}