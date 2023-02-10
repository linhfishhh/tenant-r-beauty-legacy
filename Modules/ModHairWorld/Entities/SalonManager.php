<?php

namespace Modules\ModHairWorld\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\SalonManager
 *
 * @property int $id
 * @property int $salon_id
 * @property int $user_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read User $user
 * @property-read User $manager
 * @property-read Salon $salon
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonManager whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonManager whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonManager whereSalonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonManager whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonManager whereUserId($value)
 * @mixin \Eloquent
 */
class SalonManager extends Model
{
    protected $fillable = [];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    public function salon(){
        return $this->hasOne(
            Salon::class,
            'id',
            'salon_id');
    }

    public function user(){
        return $this->hasOne(
            User::class,
            'id',
            'user_id');
    }

    public function manager(){
        return $this->user();
    }

    public static function userHasSalon($user_id){
        return SalonManager::whereUserId($user_id)->count()>0;
    }
}
