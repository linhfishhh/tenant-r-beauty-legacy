<?php

namespace Modules\ModHairWorld\Entities;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\SalonServiceSaleDeleted;
use Modules\ModHairWorld\Events\SalonServiceSaleSaved;

/**
 * Modules\ModHairWorld\Entities\SalonServiceSale
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $service_id
 * @property int $sale_amount
 * @property string|null $description
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read SalonService $service
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceSale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceSale whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceSale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceSale whereSaleAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceSale whereServiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceSale whereUpdatedAt($value)
 * @property int $sale_type
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceSale whereSaleType($value)
 * @property int $sale_percent
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonServiceSale whereSalePercent($value)
 */
class SalonServiceSale extends Model
{
    protected $fillable = [];
    protected $table = 'service_sales';
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $dispatchesEvents = [
        'deleted' => SalonServiceSaleDeleted::class,
        'saved' => SalonServiceSaleSaved::class,
    ];

    function service(){
        return $this->hasOne(SalonService::class,'id','service_id');
    }

    function applySale($price){
        if($this->sale_type == 1){
            $final = $price - $this->sale_amount;
        }
        else{
            $desc = ($price * $this->sale_percent)/100.0;
            $final = floor($price - $desc);
        }
        if($final < 0){
            $final = 0;
        }
        return $final;
    }

    function getSaleAmount($price){
        $final_price = $this->applySale($price);
        $rs = $price - $final_price;
        if($rs < 0){
            $rs = 0;
        }
        return $rs;
    }

    function getSalePercent($price){
        if($price == 0){
            return 0;
        }
        $amount = $this->getSaleAmount($price);
        $percent = floor($amount * 100.0 / $price);
        return $percent;
    }

    /**
     * @param SalonServiceOption[]|Collection $options
     * @return int
     */
    function getMinSaleAmount($options){
        $has_options = $options && $options->count();
        if(!$has_options){
            return 0;
        }
        return $options->min(function(SalonServiceOption $option){
           return $this->getSaleAmount($option->price);
        });
    }

    /**
     * @param SalonServiceOption[]|Collection $options
     * @return int
     */
    function getMinSalePercent($options){
        $has_options = $options && $options->count();
        if(!$has_options){
            return 0;
        }
        return $options->min(function(SalonServiceOption $option){
            return $this->getSalePercent($option->price);
        });
    }

    /**
     * @param SalonServiceOption[]|Collection $options
     * @return int
     */
    function getMaxSaleAmount($options){
        $has_options = $options && $options->count();
        if(!$has_options){
            return 0;
        }
        return $options->max(function(SalonServiceOption $option){
            return $this->getSaleAmount($option->price);
        });
    }

    /**
     * @param SalonServiceOption[]|Collection $options
     * @return int
     */
    function getMaxSalePercent($options){
        $has_options = $options && $options->count();
        if(!$has_options){
            return 0;
        }
        return $options->max(function(SalonServiceOption $option){
            return $this->getSalePercent($option->price);
        });
    }
}
