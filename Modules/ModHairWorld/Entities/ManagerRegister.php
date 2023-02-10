<?php

namespace Modules\ModHairWorld\Entities;


use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\ManagerRegister
 *
 * @property int $id
 * @property string $salon_name
 * @property string $salon_address
 * @property string $salon_location
 * @property string $manager_name
 * @property string $manager_phone
 * @property string $manager_email
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\ManagerRegister whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\ManagerRegister whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\ManagerRegister whereManagerEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\ManagerRegister whereManagerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\ManagerRegister whereManagerPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\ManagerRegister whereSalonAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\ManagerRegister whereSalonLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\ManagerRegister whereSalonName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\ManagerRegister whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ManagerRegister extends Model
{
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $table = 'manager_registers';
}