<?php

namespace Modules\ModHairWorld\Entities;

use App\UploadedFile;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\SalonGallery
 *
 * @property int $id
 * @property int $salon_id
 * @property int $image_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonGallery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonGallery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonGallery whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonGallery whereSalonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonGallery whereUpdatedAt($value)
 * @property-read UploadedFile $image
 * @mixin \Eloquent
 */
class SalonGallery extends Model
{
    protected $fillable = [];
    protected $dates = [
        'created_at',
        'updated_at'
    ];
    static function getFileCatIDs(){
        $rs = [];
        $rs['gallery'] = __('Ảnh giới thiệu');
        return $rs;
    }

    function image(){
        return $this->hasOne(UploadedFile::class,'id','image_id');
    }
}
