<?php

namespace App;

use App\Classes\Permission;
use App\Classes\PermissionGroup;
use App\Classes\ResetPassword;
use App\Events\User\UserCreated;
use App\Events\User\UserCreating;
use App\Events\User\UserDeleted;
use App\Events\User\UserDeleting;
use App\Events\User\UserRetrieved;
use App\Events\User\UserSaved;
use App\Events\User\UserSaving;
use App\Events\User\UserUpdated;
use App\Events\User\UserUpdating;
use App\Trails\ModelMeta;
use App\Trails\PermissionHelper;
use Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $social_id
 * @property string $email
 * @property string $password
 * @property int $role_id
 * @property int|null $avatar_id
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications $role
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereSocialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\Role $role
 * @property-read \App\UploadedFile|null $avatar
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserMeta[] $metas
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereAvatarId($value)
 * @property string $phone
 * @property string $booking_device_id
 * @property string $manager_device_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePhone($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 */
class User extends Authenticatable
{
    use HasApiTokens, Notifiable, PermissionHelper, ModelMeta;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    protected $meta_class = UserMeta::class;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $dispatchesEvents = [
        'retrieved' => UserRetrieved::class,
        'creating'  => UserCreating::class,
        'created'   => UserCreated::class,
        'updating'  => UserUpdating::class,
        'updated'   => UserUpdated::class,
        'saving'    => UserSaving::class,
        'saved'     => UserSaved::class,
        'deleting'  => UserDeleting::class,
        'deleted'   => UserDeleted::class,
    ];

    public static function getFileID()
    {
        return 'user_avatar';
    }

    public function avatar()
    {
        return $this->hasOne(
            UploadedFile::class,
            'id',
            'avatar_id'
        )->where(
            'category',
            '=',
            User::getFileID());
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    protected function permissions()
    {
        return $this->hasMany(
            RolePermission::class,
            'role_id',
            'role_id'
        );
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getRoleDesc()
    {
        return $this->role->desc;
    }

    public function getRoleTitle()
    {
        return $this->role->title;
    }

    public function getRoleID()
    {
        return $this->role->id;
    }

    public function isUltimateUser()
    {
        return $this->role_id == config('app.ultimate_role_id');
    }

    public function isMyID($id)
    {
        return $this->id == $id;
    }

    public function isMyRole($role_id)
    {
        return $this->role_id == $role_id;
    }

    public function getPermissions()
    {
        $rs = [];
        /** @var RolePermission[] $role_permissions */
        $role_permissions = $this->permissions;
        foreach ($role_permissions as $role_permission) {
            $rs[] = $role_permission->permission;
        }
        return $rs;
    }

    public function hasPermission($permission)
    {
        if ($this->isUltimateUser()) {
            return true;
        }
        $permissions = $this->getPermissions();
        return in_array(
            $permission,
            $permissions
        );
    }

    public function hasAnyPermissions(array $permissions_to_check)
    {
        if ($this->isUltimateUser()) {
            return true;
        }
        $permissions = $this->getPermissions();
        return count(
                array_intersect(
                    $permissions_to_check,
                    $permissions
                )
            ) > 0;
    }

    public function hasAllPermissions(array $permissions_to_check)
    {
        if ($this->isUltimateUser()) {
            return true;
        }
        $permissions = $this->getPermissions();
        return !array_diff(
            $permissions_to_check,
            $permissions
        );
    }

    public function getAllowPermissionToSet()
    {
        $rs = [];
        $valid = [];
        $all = [];
        $e = app('permissions');

        foreach ($e->permissions as $permission) {
            /** @var Permission $permission */
            $all[] = $permission->id;
        }

        /** @var RolePermission[] $role_permissions */
        $role_permissions = $this->permissions;
        foreach ($role_permissions as $role_permission) {
            if (!in_array(
                $role_permission->permission,
                $all
            )) {
                continue;
            }
            $valid[] = $role_permission->permission;
        }

        /** @var Permission[] $pers */
        $pers = [];

        foreach ($e->permissions as $permission) {
            /** @var Permission $permission */
            if ($this->isUltimateUser()) {
                $pers[] = $permission;
            } else {
                if (in_array(
                    $permission->id,
                    $valid
                )) {
                    $pers[] = $permission;
                }
            }
        }

        /** @var PermissionGroup[] $role_groups */
        $role_groups = $e->groups;
        foreach ($role_groups as $group) {
            $g = [
                'group'       => $group,
                'permissions' => []
            ];

            foreach ($pers as $per) {
                if ($per->group == $group->id) {
                    $g['permissions'][] = $per;
                }
            }

            if (count($g['permissions']) > 0) {
                $rs[] = $g;
            }
        }
        return $rs;
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(
            new ResetPassword(
                $token,
                $this
            )
        );
    }

    public static function sendPasswordResetEmail($email)
    {
        $broker = \Password::broker();
        $response = $broker->sendResetLink(['email' => $email]);
        if ($response == Password::RESET_LINK_SENT) {
            return true;
        }
        return false;
    }

    public static function checkPasswordResetToken(
        $email,
        $token
    ) {
        $record = PasswordReset::whereEmail($email)->first();
        if (!$record) {
            return false;
        }
        $can_reset = \Hash::check(
            $token,
            $record->token
        );
        if (!$can_reset) {
            return false;
        }
        return true;
    }

    public static function resetPassword(
        $email,
        $new_password,
        $password_confirmation,
        $token
    ) {
        $broker = \Password::broker();
        $response = $broker->reset(
            [
                'email'                 => $email,
                'password'              => $new_password,
                'password_confirmation' => $password_confirmation,
                'token'                 => $token
            ],
            function (
                $user,
                $password
            ) {
                /** @var  User $user */
                $user->password = Hash::make($password);

                $user->setRememberToken(Str::random(60));

                $user->save();
                event(new \Illuminate\Auth\Events\PasswordReset($user));
                \Auth::login($user);
            }
        );
        if ($response == Password::PASSWORD_RESET) {
            return true;
        }
        return false;
    }
}
