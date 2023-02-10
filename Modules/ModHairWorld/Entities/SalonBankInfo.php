<?php

namespace Modules\ModHairWorld\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\SalonBankInfo
 *
 * @property int $id
 * @property int $salon_id
 * @property string|null $name
 * @property string|null $account
 * @property string|null $bank_name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonBankInfo whereAccount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonBankInfo whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonBankInfo whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonBankInfo whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonBankInfo whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonBankInfo whereSalonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonBankInfo whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SalonBankInfo extends Model
{
    protected $table = 'salon_bank_infos';

    public $timestamps = [
        'created_at',
        'updated_at'
    ];
}