<?php

namespace Modules\ModHairWorld\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\SalonShowcaseLikeCreated;
use Modules\ModHairWorld\Events\SalonShowcaseLikeDeleted;

/**
 * Modules\ModHairWorld\Entities\SalonShowcaseLike
 *
 * @property int $id
 * @property int $showcase_id
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseLike whereShowcaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseLike whereUserId($value)
 * @property-read SalonShowcase $showcase
 * @property-read User $user
 * @mixin \Eloquent
 */
class SalonShowcaseLike extends Model
{
    protected $fillable = [];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $dispatchesEvents = [
        'created' => SalonShowcaseLikeCreated::class,
        'deleted' => SalonShowcaseLikeDeleted::class
    ];

    function showcase(){
        return $this->hasOne(SalonShowcase::class, 'id', 'showcase_id');
    }

    function user(){
        return $this->hasOne(User::class,'id', 'user_id');
    }
}
