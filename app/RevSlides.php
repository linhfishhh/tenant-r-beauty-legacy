<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\RevSlides
 *
 * @property int $id
 * @property int $slider_id
 * @property int $slide_order
 * @property string $params
 * @property string $layers
 * @property string $settings
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSlides whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSlides whereLayers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSlides whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSlides whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSlides whereSlideOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSlides whereSliderId($value)
 * @mixin \Eloquent
 */
class RevSlides extends Model
{
    protected $table = 'slider_slides';
}
