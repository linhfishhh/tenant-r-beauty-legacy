<?php

namespace App;

use App\Classes\Meta;

/**
 * App\UserMeta
 *
 * @property int $id
 * @property string $name
 * @property int $target_id
 * @property string $value
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereTargetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserMeta whereValue($value)
 * @mixin \Eloquent
 */
class UserMeta extends Meta
{
}
