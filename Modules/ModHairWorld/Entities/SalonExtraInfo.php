<?php

namespace Modules\ModHairWorld\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\SalonExtraInfo
 *
 * @property int $id
 * @property int $salon_id
 * @property string $title
 * @property string|null $icon
 * @property string|null $content
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonExtraInfo whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonExtraInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonExtraInfo whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonExtraInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonExtraInfo whereSalonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonExtraInfo whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonExtraInfo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SalonExtraInfo extends Model
{
    protected $fillable = [];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
}
