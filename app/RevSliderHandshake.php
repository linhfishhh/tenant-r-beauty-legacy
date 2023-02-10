<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Handshake
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $code
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSliderHandshake whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSliderHandshake whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSliderHandshake whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSliderHandshake whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSliderHandshake whereUserId($value)
 */
class RevSliderHandshake extends Model
{
    //
    protected $table = 'slider_handshake';
    protected $fillable = ['code'];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
