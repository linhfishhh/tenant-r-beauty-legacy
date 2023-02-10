<?php

namespace Modules\ModHairWorld\Entities;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Modules\ModHairWorld\Entities\SalonOpenTime
 *
 * @property int $id
 * @property int $salon_id
 * @property int $weekday
 * @property string $start
 * @property string $end
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOpenTime whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOpenTime whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOpenTime whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOpenTime whereSalonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOpenTime whereStart($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOpenTime whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonOpenTime whereWeekday($value)
 * @mixin \Eloquent
 */
class SalonOpenTime extends Model
{
    protected $fillable = [];
    protected $dates = [
        'created_at',
        'updated_at',
    ];


    function weekDayText(){
        $rs = '';
        switch ($this->weekday){
            case 1:
                $rs = 'Thứ hai';
                break;
            case 2:
                $rs = 'Thứ ba';
                break;
            case 3:
                $rs = 'Thứ tư';
                break;
            case 4:
                $rs = 'Thứ năm';
                break;
            case 5:
                $rs = 'Thứ sáu';
                break;
            case 6:
                $rs = 'Thứ bảy';
                break;
            case 7:
                $rs = 'Chủ nhật';
                break;
        }
        return $rs;
    }

    function workHourText(){
        $from = Carbon::createFromFormat('H:i:s', $this->start);
        $to = Carbon::createFromFormat('H:i:s', $this->end);
        $from = $from->format('H:i');
        $to = $to->format('H:i');
        if($from>$to){
            $from = $to;
        }
        return $from. ' : '. $to;
    }
}
