<?php
namespace Modules\ModHairWorld\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\SalonOrderItem
 *
 * @property int $id
 * @property int $order_id
 * @property int $service_id
 * @property int $included_options_id
 * @property int $included_options_name
 * @property int $quatity
 * @property int $price
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderIncludedItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderIncludedItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderIncludedItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderIncludedItem whereQuatity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderItem whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderIncludedItem whereIncludedOptionsId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderIncludedItem whereIncludedOptionsName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderIncludedItem whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOrderIncludedItem whereCreatedAt($value)
 * @property-read SalonService|null $service
 * @property-read SalonServiceReview|null $reviewedByMe
 * @mixin \Eloquent
 */
class SalonOrderIncludedItem extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    function service(){
        return $this->hasOne(SalonService::class,'id', 'service_id');
    }
}