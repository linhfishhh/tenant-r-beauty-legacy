<?php

namespace Modules\ModHairWorld\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\SalonPaymentSupport
 *
 * @property int $id
 * @property int $salon_id
 * @property string $payment_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonPaymentSupport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonPaymentSupport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonPaymentSupport wherePaymentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonPaymentSupport whereSalonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonPaymentSupport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SalonPaymentSupport extends Model
{
    public $timestamps = [
        'created_at',
        'updated_at'
    ];
}