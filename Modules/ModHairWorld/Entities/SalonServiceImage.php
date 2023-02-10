<?php

namespace Modules\ModHairWorld\Entities;


use App\UploadedFile;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\SalonServiceImage
 *
 * @property int $id
 * @property int $service_id
 * @property int $image_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceLogo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceLogo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceLogo whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceLogo whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceLogo whereUpdatedAt($value)
 * @property-read UploadedFile|null $image
 * @mixin \Eloquent
 */
class SalonServiceImage extends Model
{
    public $timestamps = [
        'created_at',
        'updated_at'
    ];

    function image(){
        return $this->hasOne(UploadedFile::class, 'id', 'image_id');
    }
}