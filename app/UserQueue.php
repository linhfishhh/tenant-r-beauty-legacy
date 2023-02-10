<?php
namespace App;


/**
 * App\UserQueue
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $role_id
 * @property int|null $avatar_id
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\UploadedFile $avatar
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\UserMeta[] $metas
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read \App\Role $role
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserQueue whereAvatarId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserQueue whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserQueue whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserQueue whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserQueue whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserQueue wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserQueue whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserQueue whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserQueue whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $active_key
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserQueue whereActiveKey($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Client[] $clients
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Passport\Token[] $tokens
 */
class UserQueue extends User
{

}