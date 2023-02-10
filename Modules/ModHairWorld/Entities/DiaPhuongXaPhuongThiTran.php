<?php

namespace Modules\ModHairWorld\Entities;

use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\DiaPhuongXaPhuongThiTran
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $maqh
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\DiaPhuongXaPhuongThiTran whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\DiaPhuongXaPhuongThiTran whereMaqh($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\DiaPhuongXaPhuongThiTran whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\DiaPhuongXaPhuongThiTran whereType($value)
 */
class DiaPhuongXaPhuongThiTran extends Model
{
    protected $fillable = [];
    protected $table = 'dia_phuong_xa_phuong_thi_tran';

    function salons(){
        return $this->hasMany(Salon::class, 'phuong_xa_thi_tran_id', 'id');
    }
}
