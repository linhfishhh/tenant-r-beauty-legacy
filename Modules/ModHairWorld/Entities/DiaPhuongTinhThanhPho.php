<?php

namespace Modules\ModHairWorld\Entities;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $type
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho whereType($value)
 * @property-read DiaPhuongQuanHuyen[]|Collection $lv2
 * @property-read Salon[] $salon
 */
class DiaPhuongTinhThanhPho extends Model
{
    protected $fillable = [];
    protected $table = 'dia_phuong_tinh_thanh_pho';

    function salons(){
        return $this->hasMany(Salon::class, 'tinh_thanh_pho_id', 'id');
    }

    function lv2(){
        return $this->hasMany(DiaPhuongQuanHuyen::class,'matp', 'id');
    }
}
