<?php

namespace Modules\ModHairWorld\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\DiaPhuongQuanHuyen
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $matp
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\DiaPhuongQuanHuyen whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\DiaPhuongQuanHuyen whereMatp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\DiaPhuongQuanHuyen whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\DiaPhuongQuanHuyen whereType($value)
 */
class DiaPhuongQuanHuyen extends Model
{
    protected $fillable = [];
    protected $table = 'dia_phuong_quan_huyen';

    function salons(){
        return $this->hasMany(Salon::class, 'quan_huyen_id', 'id');
    }
}
