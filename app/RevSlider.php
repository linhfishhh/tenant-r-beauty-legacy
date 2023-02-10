<?php

namespace App;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * App\RevSliderSlider
 *
 * @property int $id
 * @property string $title
 * @property string|null $alias
 * @property string $params
 * @property string|null $settings
 * @property string $type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSlider whereAlias($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSlider whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSlider whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSlider whereSettings($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSlider whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RevSlider whereType($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RevSlides[] $slides
 */
class RevSlider extends Model
{
    protected $table = 'slider_sliders';

    public static function getSliders($colunms = []){
        if($colunms){
            return RevSlider::where('type', '!=', 'template')->get($colunms);
        }
        else{
            return RevSlider::where('type', '!=', 'template')->get();
        }
    }

    public function getFrontEndData(){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, url('apps/revslider/index.php?c=embed'));

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, 'alias='.$this->alias.'&key='.rand());
        $headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output);
    }

    public function slides(){
        return $this->hasMany(RevSlides::class,'slider_id', 'id')->orderBy('slide_order', 'asc');
    }
}
