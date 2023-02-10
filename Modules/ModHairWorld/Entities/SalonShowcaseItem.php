<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 31-May-18
 * Time: 11:08
 */

namespace Modules\ModHairWorld\Entities;


use App\UploadedFile;
use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\SalonShowcaseItemDeleted;

/**
 * Modules\ModHairWorld\Entities\SalonShowcaseItems
 *
 * @property int $id
 * @property int $showcase_id
 * @property int $image_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseItem whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseItem whereShowcaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonShowcaseItem whereUpdatedAt($value)
 * @property-read UploadedFile|null $image
 * @mixin \Eloquent
 */
class SalonShowcaseItem extends Model
{
    protected $table = 'salon_showcase_items';

    protected $dispatchesEvents = [
        'deleted' => SalonShowcaseItemDeleted::class
    ];

    static function getFileCatIDs(){
        $rs = [];
        $rs['showcase_item'] = __('Ảnh tác phẩm');
        return $rs;
    }

    function image(){
        return $this->hasOne(
            UploadedFile::class,
            'id',
            'image_id');
    }
}