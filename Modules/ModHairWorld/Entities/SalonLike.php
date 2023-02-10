<?php

namespace Modules\ModHairWorld\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\SalonLikeCreated;
use Modules\ModHairWorld\Events\SalonLikeDeleted;

/**
 * Modules\ModHairWorld\Entities\SalonLike
 *
 * @property int $id
 * @property int $salon_id
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonLike whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonLike whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonLike whereSalonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonLike whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonLike whereUserId($value)
 * @property-read Salon $salon
 * @property-read User $user
 * @mixin \Eloquent
 */
class SalonLike extends Model
{
    protected $fillable = [];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $dispatchesEvents = [
        'created' => SalonLikeCreated::class,
        'deleted' => SalonLikeDeleted::class
    ];

    function salon(){
        return $this->hasOne(Salon::class, 'id', 'salon_id');
    }

    function user(){
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
