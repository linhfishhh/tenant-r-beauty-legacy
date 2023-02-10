<?php

namespace Modules\ModHairWorld\Entities;

use App\UploadedFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Events\SalonServiceDeleted;
use Modules\ModHairWorld\Events\SalonServiceSaved;
use Modules\ModHairWorld\Events\SalonServiceSaving;

/**
 * Modules\ModHairWorld\Entities\SalonService
 *
 * @property int $id
 * @property int $salon_id
 * @property int $name
 * @property string $color
 * @property string $text_color
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereSalonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $price
 * @property int $cover_id
 * @property int $category_id
 * @property int $time_from
 * @property int $time_to
 * @property string $description
 * @property int $is_combo
 * @property-read UploadedFile $cover
 * @property-read SalonServiceCategory $category
 * @property-read SalonServiceReview[]|Collection $reviews
 * @property-read SalonServiceReview[]|Collection $approved_reviews
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereIsCombo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereTimeFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereTimeTo($value)
 * @property float $rating
 * @property int $rating_count
 * @property-read Salon $salon
 * @property-read SalonServiceSale $sale_off
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereCoverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereRatingCount($value)
 * @property-read SalonServiceLogo[] $logos
 * @property-read SalonServiceImage[] $images
 * @property int $sale_cache
 * @property int $sale_percent_cache
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereColor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereSaleCache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereSalePercentCache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereTextColor($value)
 * @property int $final_price_cache
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereFinalPriceCache($value)
 * @property int|null $booked_cache
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereBookedCache($value)
 * @property-read SalonServiceOption[]|Collection $options
 * @property-read SalonServiceIncludedOption[]|Collection $included_options
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\ModHairWorld\Entities\SalonOrder[] $bookings
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService wherePriceFromCache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService wherePriceToCache($value)
 * @property int $ranged_price
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\SalonService whereRangedPrice($value)
 */
class SalonService extends Model
{
    protected $fillable = [
        'category_id'
    ];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $dispatchesEvents = [
        'deleted' => SalonServiceDeleted::class,
        'saved' => SalonServiceSaved::class,
        'saving' => SalonServiceSaving::class,
    ];

    static function getFileCatIDs(){
        $rs = [];
        $rs['service_cover'] = __('Dịch vụ - ảnh đại diện');
        $rs['service_description'] = __('Dịch vụ - Mô tả');
        $rs['service_logo'] = 'Dịch vụ - logo thương hiệu';
        $rs['service_images'] = 'Dịch vụ - Hình ảnh';
        return $rs;
    }

    function options(){
        return $this->hasMany(SalonServiceOption::class, 'service_id', 'id');
    }
    function included_options(){
        return $this->hasMany(SalonServiceIncludedOption::class, 'service_id', 'id');
    }

    function salon(){
        return $this->hasOne(Salon::class, 'id', 'salon_id');
    }

    function cover(){
        return $this->hasOne(UploadedFile::class,'id', 'cover_id');
    }

    function category(){
        return $this->hasOne(SalonServiceCategory::class,'id', 'category_id');
    }

    function getOptionFinalPrice($option_id, $with_promotion = true){
        if($with_promotion) {
            $salon = $this->salon;
            $promotion_price = $salon->getServicePromoPrice($this);
            if ($promotion_price !== false) {
                return $promotion_price;
            }
        }
        $options = $this->options;
        $price = $this->price;
        if($options->count()){
            foreach ($options as $option){
                if($option->id == $option_id){
                    $price = $option->price;
                    break;
                }
            }
        }
        if($this->sale_off){
            $price = $this->sale_off->applySale($price);
        }
        return $price;
    }

    function getOptionFinalPriceHtml($option_id){
        return static::formatPrice($this->getOptionFinalPrice($option_id));
    }

    function getOptionName($option_id){
        $name = $this->name;
        $options = $this->options;
        if($options->count()){
            foreach ($options as $option){
                if($option->id == $option_id){
                    $name = $name.' / '.$option->name;
                    break;
                }
            }
        }
        $salon = $this->salon;
        if($salon->getServicePromoPrice($this) !== false){
            $settings = getSettingsFromPage('promo_configs');
            $settings = collect($settings);
            $promo_limit = $settings->get('promo_limit');
            $promo_percent = $settings->get('promo_percent');
            // $promo_date_range = $settings->get('promo_date_range');
            $name .=  " (-$promo_percent% cho $promo_limit đơn đặt chỗ đầu tiên)";
        }
        return $name;
    }

    function getFinalPrice(){
        $sale_off = $this->sale_off;
        $price = $this->price;
        if($sale_off){
            $price = $sale_off->applySale($price);
        }
        if($price<0){
            $price = 0;
        }
        return $price;
    }

    function getFinalPriceFrom(){
        $sale_off = $this->sale_off;
        $price = $this->price;
        $options = $this->options;
        if($options->count()){
            if($sale_off){
                return $options->min(function(SalonServiceOption $option) use ($sale_off){
                    return $sale_off->applySale($option->price);
                });
            }
            else{
                return $options->min('price');
            }
        }
        else{
            if($sale_off){
                return $sale_off->applySale($price);
            }
            else{
                return $price;
            }
        }
    }

    function getFinalPriceTo(){
        $sale_off = $this->sale_off;
        $price = $this->price;
        $options = $this->options;
        if($options->count()){
            if($sale_off){
                return $options->max(function(SalonServiceOption $option) use ($sale_off){
                    return $sale_off->applySale($option->price);
                });
            }
            else{
                return $options->max('price');
            }
        }
        else{
            if($sale_off){
                return $sale_off->applySale($price);
            }
            else{
                return $price;
            }
        }
    }

    function getOrgPriceFrom(){
        $price = $this->price;
        $options = $this->options;
        if($options->count()){
            return $options->min('price');
        }
        else{
            return $price;
        }
    }

    function getOrgPriceTo(){
        $price = $this->price;
        $options = $this->options;
        if($options->count()){
            return $options->max('price');
        }
        else{
            return $price;
        }
    }

    function finalPriceHtmlV2(){
//        $price = $this->price - $this->sale_cache;
//        if($price<0){
//            $price = 0;
//        }
        return static::formatPrice($this->final_price_cache);
    }

    function finalPriceHtml(){
        $options = $this->options;
        if($options->count()){
            return static::formatPrice($this->getFinalPriceFrom());
        }
        else{
            return static::formatPrice($this->getFinalPriceFrom());
        }
    }

    function priceHtml(){
        $options = $this->options;
        if($options->count()){
            return static::formatPrice($this->getOrgPriceFrom());
        }
        else{
            return static::formatPrice($this->getOrgPriceFrom());
        }
    }


    public static function formatPrice($price){
        return number_format($price/1000.0, 3, '.', '.').'đ';
    }

    function sale_off(){
        return $this->hasOne(SalonServiceSale::class, 'service_id', 'id');
    }

    function timeText(){
        $s = $this->time_from;
        $t = $this->time_to;
        if($s > $t){
            $s = $t;
        }
        $rs = $s;
        if($s != $t){
            $rs .= ' - '.$t;
        }
        $rs .= ' phút';
        return $rs;
    }

    function reviews(){
        return $this->hasMany(SalonServiceReview::class, 'service_id', 'id');
    }

    public function approved_reviews(){
        return $this->hasMany(SalonServiceReview::class,
            'service_id',
            'id')
            ->where('approved', 1);
    }

    public function one_star_reviews(){
        return $this->approved_reviews()
            ->where('rating', '>=', 1)
            ->where('rating', '<', 2);
            ;
    }

    public function two_star_reviews(){
        return $this->approved_reviews()
            ->where('rating', '>=', 2)
            ->where('rating', '<', 3);
            ;
    }

    public function three_star_reviews(){
        return $this->approved_reviews()
            ->where('rating', '>=', 3)
            ->where('rating', '<', 4);
            ;
    }

    public function four_star_reviews(){
        return $this->approved_reviews()
            ->where('rating', '>=', 4)
            ->where('rating', '<', 5);
            ;
    }

    public function five_star_reviews(){
        return $this->approved_reviews()
            ->where('rating', '>=', 5)
            ;
    }

    function cacheSale($save = true){
        if(!$this->salon){
            return;
        }



        $sale_off = $this->sale_off;
        $options = $this->options;
        $has_options = $options && $options->count();
        $this->sale_cache = 0;
        $this->sale_percent_cache = 0;
        $this->final_price_cache = 0;
        $this->ranged_price = 0;
        if($has_options){
            $this->ranged_price = $options->min('price') != $options->max('price');
        }

        if($sale_off){
            if($has_options){
                $this->sale_cache = $sale_off->getMaxSaleAmount($options);
                $this->sale_percent_cache = $sale_off->getMaxSalePercent($options);
            }
            else{
               $this->sale_cache = $sale_off->getSaleAmount($this->price);
               $this->sale_percent_cache = $sale_off->getSalePercent($this->price);
            }
        }
        else{
            $this->sale_cache = 0;
            $this->sale_percent_cache = 0;
        }

        if($sale_off){
            if($has_options){
                $this->final_price_cache = $sale_off->applySale($options->min('price'));
            }
            else{
                $this->final_price_cache = $sale_off->applySale($this->price);
            }
        }
        else{
            if($has_options){
                $this->final_price_cache = $options->min('price');
            }
            else{
                $this->final_price_cache = $this->price;
            }
        }

        if($save){
            $this->save();
        }
    }

    function bookings(){
        return $this->hasManyThrough(SalonOrder::class, SalonOrderItem::class,'service_id', 'id', 'id', 'order_id');
    }

    function cacheBookingCount(){
        $rs = $this->bookings()->where('salon_orders.status', '=', SalonOrder::_DA_HOAN_THANH_)->count(['salon_orders.id']);
        $this->booked_cache	 = $rs;
        $this->save();
    }

    function cacheRating($refresh = false){
        if(!$this->salon){
            return;
        }
        $reviews = $this->approved_reviews;
        if($refresh){
            foreach ($reviews as $review){
                $review->cacheRating();
            }
        }
        $ratings = 0;
        $count = $reviews->count();
        if($count){
            $ratings = $reviews->average(function ($item){
                /** @var SalonServiceReview $item */
                return $item->rating;
            });
        }
        $this->rating = $ratings;
        $this->rating_count = $count;
        $this->save();
    }

    function logos(){
        return $this->hasMany(SalonServiceLogo::class, 'service_id', 'id');
    }

    function images(){
        return $this->hasMany(SalonServiceImage::class, 'service_id', 'id');
    }

}
