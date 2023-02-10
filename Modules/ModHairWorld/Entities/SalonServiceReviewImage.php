<?php

namespace Modules\ModHairWorld\Entities;

use App\UploadedFile;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\SalonServiceReviewImage
 *
 * @property int $id
 * @property int $review_id
 * @property int $image_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewImage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewImage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewImage whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewImage whereReviewId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceReviewImage whereUpdatedAt($value)
 * @property-read UploadedFile|null $image
 * @mixin \Eloquent
 */
class SalonServiceReviewImage extends Model
{
    protected $fillable = [];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    function image(){
        return $this->hasOne(UploadedFile::class, 'id', 'image_id');
    }
}
