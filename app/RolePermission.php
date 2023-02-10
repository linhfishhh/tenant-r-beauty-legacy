<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\RolePermission
 *
 * @property int $id
 * @property int $role_id
 * @property string $permission
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RolePermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RolePermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RolePermission wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RolePermission whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RolePermission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class RolePermission extends Model
{
}
