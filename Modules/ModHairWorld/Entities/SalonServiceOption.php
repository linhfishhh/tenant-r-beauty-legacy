<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-02-26
 * Time: 08:40
 */

namespace Modules\ModHairWorld\Entities;


use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\SalonServiceOptionDeleted;
use Modules\ModHairWorld\Events\SalonServiceOptionSaved;

/**
 * Modules\ModHairWorld\Entities\SalonServiceOption
 *
 * @property int $id
 * @property int $service_id
 * @property string $name
 * @property int $price
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceOption whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceOption wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceOption whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceOption whereUpdatedAt($value)
 * @property-read SalonService $service
 * @mixin \Eloquent
 */
class SalonServiceOption extends Model
{
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $dispatchesEvents = [
        'deleted' => SalonServiceOptionDeleted::class,
        'saved' => SalonServiceOptionSaved::class
    ];

    function service(){
        return $this->belongsTo(SalonService::class, 'service_id', 'id');
    }
}