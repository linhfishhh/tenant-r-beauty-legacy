<?php

namespace Modules\ModHairWorld\Entities;

use App\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\SalonStylistDeleted;

/**
 * Modules\ModHairWorld\Entities\SalonStylist
 *
 * @property int $id
 * @property int $salon_id
 * @property string $name
 * @property int|null $avatar_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonStylist whereAvatarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonStylist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonStylist whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonStylist whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonStylist whereSalonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonStylist whereUpdatedAt($value)
 * @property-read UploadedFile|null $avatar
 * @mixin \Eloquent
 */
class SalonStylist extends Model
{
    protected $fillable = [];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $dispatchesEvents = [
        'deleted' => SalonStylistDeleted::class
    ];

    static function getFileCatIDs(){
        $rs = [];
        $rs['stylist_avatar'] = __('Avatar stylist');
        return $rs;
    }

    function avatar(){
        return $this->hasOne(UploadedFile::class,'id', 'avatar_id');
    }
}
