<?php

namespace Modules\ModHairWorld\Entities;

use App\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\SalonBrandDeleted;

/**
 * Modules\ModHairWorld\Entities\SalonBrand
 *
 * @property int $id
 * @property int $salon_id
 * @property string $name
 * @property int $logo_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonBrand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonBrand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonBrand whereLogoId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonBrand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonBrand whereSalonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonBrand whereUpdatedAt($value)
 * @property-read UploadedFile|null $logo
 * @mixin \Eloquent
 */
class SalonBrand extends Model
{
    protected $fillable = [];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $dispatchesEvents = [
        'deleted' => SalonBrandDeleted::class
    ];

    static function getFileCatIDs(){
        $rs = [];
        $rs['brand_logo'] = __('Logo thương hiệu');
        return $rs;
    }

    function logo(){
        return $this->hasOne(UploadedFile::class,'id', 'logo_id');
    }
}
