<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/30/18
 * Time: 17:39
 */

namespace Modules\ModHairWorld\Entities;

use App\UploadedFile;
use Illuminate\Database\Eloquent\Model;


/**
 * Modules\ModHairWorld\Entities\SearchHistory
 *
 * @mixin \Eloquent
 * @property int $id
 */
class SearchHistory extends Model
{
    protected $fillable = [];
    protected $table = 'search_history';
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    function salon(){
        return $this->hasOne(Salon::class,'id', 'salon_id');
    }
}