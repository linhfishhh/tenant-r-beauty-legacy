<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/30/18
 * Time: 17:39
 */

namespace Modules\ModHairWorld\Entities;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Modules\ModHairWorld\Entities\SalonLike;
use App\User;

/**
 * Modules\ModHairWorld\Entities\PromoSalon
 *
 * @property int $id
 * @property int $salon_id
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\PromoSalon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\PromoSalon whereSalonId($value)
 * @mixin \Eloquent
 */
class PromoSalon extends Model
{

   public $timestamps = false;

   function salon(){
       return $this->hasOne(Salon::class, 'id', 'salon_id');
   }

   static function getPromoSalons(){
       $rs = false;
       $settings = getSettingsFromPage('promo_configs');
       $settings = collect($settings);
       $promo_enable = $settings->get('promo_enable', false);

       if(!$promo_enable){
           return $rs;
       }

       $promo_date_range = $settings->get('promo_date_range', null);
       $promo_date_range = explode(' - ', $promo_date_range);

       if(!$promo_date_range){
           return $rs;
       }


       $start_date = Carbon::createFromFormat('Y-m-d', $promo_date_range[0])->startOfDay();
       $end_date = Carbon::createFromFormat('Y-m-d', $promo_date_range[1])->startOfDay();
       $today = Carbon::today()->startOfDay();
       if(!$today->greaterThanOrEqualTo($start_date)){
           return $rs;
       }

       if(!$today->lessThanOrEqualTo($end_date)){
           return $rs;
       }

       $promo_days = $settings->get('promo_days', []);

       if(!in_array($today->dayOfWeekIso.'', $promo_days)){
           return $rs;
       }

       $cats = $settings->get('promo_cats', []);
       if(!is_array($cats)){
           $cats = [$cats];
       }

       if(!$cats){
           return $rs;
       }

       $cat = $cats[0];

       $cat = SalonServiceCategory::find($cat);

       if(!$cat){
           return $rs;
       }

       $promo_limit = $settings->get('promo_limit', 0);
       $promo_percent =  $settings->get('promo_percent', 0);

       $rs = [
           'items' => [],
           'start_date' => $start_date->format('d/m/Y'),
           'end_date' => $end_date->format('d/m/Y'),
           'percent' => $promo_percent * 1,
           'limit' => $promo_limit * 1,
           'cat' => [
               'id' => $cat->id,
               'name' => $cat->title
           ]
       ];

       $rs['items'] = static::with(['salon' ,'salon.promo'])
           ->join('salons', 'salons.id', '=', 'salon_id')
           ->orderBy('salons.rating', 'desc')
           ->get()->map(function(PromoSalon $promoSalon) use($promo_limit){
           /** @var Salon $salon */
           $salon = $promoSalon->salon;
           $like = false;
           if(auth()->guard('api')->user()) {
             $salonlike = SalonLike::where('user_id', auth()->guard('api')->user()->id)->get();
             foreach ($salonlike as $k =>$value) {
              if($salonlike[$k]->salon_id == $salon->id){
                $like = true;
              }
            }
          } 
           $done_percent = ($salon->countPromoOrder()*100/$promo_limit);
           return [
               'id' => $salon->id,
               'name' => $salon->name,
               'image' => $salon->cover? $salon->cover->getThumbnailUrl('medium',getNoThumbnailUrl()): getNoThumbnailUrl(),
               'rating' => $salon->rating,
               'rating_count' => $salon->rating_count,
               'address' => $salon->address_cache,
               'deal_done' => $salon->countPromoOrder(),
               'deal_done_percent' => $done_percent,
               'price' => $salon->services->where('category_id' ,$salon->getPromoCatIDs())->pluck('price')->first(),
               'url' => $salon->url(),
               'liked' => $like,
           ];
       });

       return $rs;
   }
}

