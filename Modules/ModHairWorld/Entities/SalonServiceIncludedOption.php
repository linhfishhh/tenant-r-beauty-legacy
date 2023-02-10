<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-02-26
 * Time: 08:40
 */

namespace Modules\ModHairWorld\Entities;


use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\SalonServiceIncludedOptionDeleted;
use Modules\ModHairWorld\Events\SalonServiceIncludedOptionSaved;

/**
 * Modules\ModHairWorld\Entities\SalonServiceIncludedOption
 *
 * @property int $id
 * @property int $service_id
 * @property string $name
 * @property int $price
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceIncludedOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceIncludedOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceIncludedOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceIncludedOption wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceIncludedOption whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceIncludedOption whereUpdatedAt($value)
 * @property-read SalonService $service
 * @mixin \Eloquent
 */
class SalonServiceIncludedOption extends Model
{
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $dispatchesEvents = [
        'deleted' => SalonServiceIncludedOptionDeleted::class,
        'saved' => SalonServiceIncludedOptionSaved::class
    ];

    function service(){
        return $this->belongsTo(SalonService::class, 'service_id', 'id');
    }
}