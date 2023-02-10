<?php

namespace Modules\ModHairWorld\Entities;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\SalonShowcaseDeleted;

/**
 * Modules\ModHairWorld\Entities\SalonShowcase
 *
 * @property int $id
 * @property int $salon_id
 * @property string $name
 * @property int|null $image_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcase whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcase whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcase whereSalonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcase whereUpdatedAt($value)
 * @property-read SalonShowcaseItem[]|Collection $items
 * @property-read Salon $salon
 * @property-read SalonShowcaseLike $liked_by_me
 * @mixin \Eloquent
 */
class SalonShowcase extends Model
{
    protected $fillable = [];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $dispatchesEvents = [
        'deleted' => SalonShowcaseDeleted::class
    ];
    
    function items(){
        return $this->hasMany(
            SalonShowcaseItem::class,
            'showcase_id','id')->orderBy('id', 'asc');
    }

    function likedBy($user_id){
        return SalonShowcaseLike::whereShowcaseId($this->id)->where('user_id', $user_id)->exists();
    }

    function liked_by_me(){
        return $this->hasOne(SalonShowcaseLike::class, 'showcase_id', 'id')
            ->where('user_id', me()?me()->id:-1)
            ;
    }

    /**
     * @return SalonShowcaseItem|null
     */
    function cover(){
        if($this->items->count()==0){
            return null;
        }
        /** @var SalonShowcaseItem $cover */
        return $this->items->first();
    }

    function salon(){
        return $this->belongsTo(Salon::class,'salon_id', 'id');
    }
}
