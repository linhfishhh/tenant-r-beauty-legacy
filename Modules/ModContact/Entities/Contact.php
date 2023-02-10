<?php

namespace Modules\ModContact\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $content
 * @property boolean $handled
 *@mixin \Eloquent
 */
class Contact extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
