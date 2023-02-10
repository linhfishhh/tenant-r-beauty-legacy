<?php

namespace Modules\ModHairWorld\Entities;

use App\UploadedFile;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Modules\ModHairWorld\Events\SalonDeleted;
use Modules\ModHairWorld\Events\SalonSaving;

/**
 * Modules\ModHairWorld\Entities\Salon
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $cover_id
 * @property-read UploadedFile $cover
 * @property int $tinh_thanh_pho_id
 * @property int $quan_huyen_id
 * @property int $phuong_xa_thi_tran_id
 * @property string $address
 * @property int $map_lat
 * @property int $map_long
 * @property int $map_zoom
 * @property string $info
 * @property string $training_info
 * @property-read User[]|Collection $users
 * @property-read User[]|Collection $managers
 * @property-read SalonGallery[]|Collection $gallery
 * @property-read SalonService[]|Collection $services
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereCoverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereMapLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereMapLong($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereMapZoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon wherePhuongXaThiTranId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereQuanHuyenId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereTinhThanhPhoId($value)
 * @property int $open
 * @property int $certified
 * @property int $admin_order
 * @property float $rating
 * @property int $rating_count
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereAdminOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereCertified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereOpen($value)
 * @property string|null $phone
 * @property string|null $meta_keywords
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\ModHairWorld\Entities\SalonLike[] $likes
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\ModHairWorld\Entities\SalonServiceReview[] $reviews
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\ModHairWorld\Entities\SalonServiceReview[] $approved_reviews
 * @property-read DiaPhuongTinhThanhPho $location_lv1
 * @property-read DiaPhuongQuanHuyen $location_lv2
 * @property-read DiaPhuongXaPhuongThiTran $location_lv3
 * @property-read SalonOpenTime[]|Collection $times
 * @property-read SalonServiceCategory[]|Collection $service_categories
 * @property-read SalonStylist[]|Collection $stylist
 * @property-read SalonBrand[]|Collection $brands
 * @property-read SalonExtraInfo[]|Collection $extended_info
 * @property-read SalonShowcase[]|Collection $showcases
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon wherePhone($value)
 * @property string|null $rating_criterias
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\ModHairWorld\Entities\SalonServiceSale[] $saleServices
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereRatingCriterias($value)
 * @property int $auto_cache_rating
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereAutoCacheRating($value)
 * @property-read SalonLike|Builder $liked_by_me
 * @property-read SalonBankInfo $bank_info
 * @property-read SalonPaymentSupport[] $payment_supports
 * @property int $price_from_cache
 * @property int $sale_up_to_cache
 * @property int $sale_up_to_percent_cache
 * @property int $booked_cache
 * @property string $address_cache
 * @property string|null $verified_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Modules\ModHairWorld\Entities\SalonOrder[] $bookings
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read PromoSalon promo
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereAddressCache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereBookedCache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereMetaKeywords($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon wherePriceFromCache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereSaleUpToCache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereSaleUpToPercentCache($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon whereVerifiedAt($value)
 * @property int $price_to_cache
 * @method static \Illuminate\Database\Eloquent\Builder|\Modules\ModHairWorld\Entities\Salon wherePriceToCache($value)
 */
class Salon extends Model
{
    use Notifiable;

    protected $fillable = [];
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    protected $dispatchesEvents = [
        'deleted' => SalonDeleted::class,
        'saving' => SalonSaving::class,
    ];

    static function getFileCatIDs(){
        $rs = [
            'salon_cover' => __('Ảnh đại diện salon')
        ];
        return $rs;
    }

    function cacheSale(){
        $price_from_cache = $this->services()->min('final_price_cache');
        $this->price_from_cache = $price_from_cache;

        $price_to_cache = $this->services()->max('final_price_cache');
        $this->price_to_cache = $price_to_cache;

        $sale_up_to_cache = $this->services()->max('sale_cache');
        $this->sale_up_to_cache = $sale_up_to_cache;

        $sale_up_to_percent_cache = $this->services()->max('sale_percent_cache');
        $this->sale_up_to_percent_cache = $sale_up_to_percent_cache;

        $this->save();
    }

    function cacheBookingCount(){
        $rs = $this->services()->sum('booked_cache');
        $this->booked_cache	 = $rs;
        $this->save();
    }

    function bookings(){
        return $this->hasMany(SalonOrder::class, 'salon_id', 'id');
    }

    function getPaymentMethod(){
        return SalonOrder::getPaymentMethods();
    }

    function bank_info(){
        return $this->hasOne(SalonBankInfo::class, 'salon_id', 'id');
    }

    function payment_supports(){
        return $this->hasMany(SalonPaymentSupport::class, 'salon_id', 'id');
    }


    function cover(){
        return $this->hasOne(UploadedFile::class, 'id', 'cover_id');
    }

    public function users(){
        return $this->hasManyThrough(
            User::class,
            SalonManager::class,
            'salon_id',
            'id',
            'id',
            'user_id');
    }

    public function managers(){
        return $this->users();
    }


    public function getManagerIds(){
        $rs = [];
        foreach ($this->managers as $manager){
            $rs[] = $manager->id;
        }
        return $rs;
    }

    public static function userHasSalon($user_id){
        return SalonManager::userHasSalon($user_id);
    }

    public function likes(){
        return $this->hasMany(SalonLike::class,'salon_id', 'id');
    }

    public function reviews(){
        return $this->hasManyThrough(
            SalonServiceReview::class,
            SalonService::class,
            'salon_id',
            'service_id',
            'id',
            'id'
        );
    }

    public function approved_reviews(){
        return $this->hasManyThrough(
            SalonServiceReview::class,
            SalonService::class,
            'salon_id',
            'service_id',
            'id',
            'id'
        )->where('approved', 1);
    }

    public function one_star_reviews(){
        return $this->approved_reviews()
            ->where('salon_service_reviews.rating', '>=', 1)
        ->where('salon_service_reviews.rating', '<', 2);
    }

    public function two_star_reviews(){
        return $this->approved_reviews()
            ->where('salon_service_reviews.rating', '>=', 2)
            ->where('salon_service_reviews.rating', '<', 3);
            ;
    }

    public function three_star_reviews(){
        return $this->approved_reviews()
            ->where('salon_service_reviews.rating', '>=', 3)
            ->where('salon_service_reviews.rating', '<', 4);
            ;
    }

    public function four_star_reviews(){
        return $this->approved_reviews()
            ->where('salon_service_reviews.rating', '>=', 4)
            ->where('salon_service_reviews.rating', '<', 5);
            ;
    }

    public function five_star_reviews(){
        return $this->approved_reviews()
            ->where('salon_service_reviews.rating', '>=', 5)
            ;
    }

    function location_lv1(){
        return $this->hasOne(DiaPhuongTinhThanhPho::class, 'id', 'tinh_thanh_pho_id');
    }

    function location_lv2(){
        return $this->hasOne(DiaPhuongQuanHuyen::class, 'id', 'quan_huyen_id');
    }

    function location_lv3(){
        return $this->hasOne(DiaPhuongXaPhuongThiTran::class, 'id', 'phuong_xa_thi_tran_id');
    }

    public function getTinhThanhPhoText(){
        $rs = '';
        $model = $this->location_lv1;
        if($model){
            $rs = $model->name;
        }
        return $rs;
    }

    public function getQuanHuyenText(){
        $rs = '';
        $model = $this->location_lv2;
        if($model){
            $rs = $model->name;
        }
        return $rs;
    }

    public function getPhuongXaTranText(){
        $rs = '';
        $model = $this->location_lv3;
        if($model){
            $rs = $model->name;
        }
        return $rs;
    }

    public function getAddressLine(){
        $rs = '';
        $tinh = $this->getTinhThanhPhoText();
        $quan = $this->getQuanHuyenText();
        $phuong = $this->getPhuongXaTranText();
        $address = $this->address;
        if($tinh){
            $rs = $tinh;
            if($quan){
                $rs = $quan . ', ' . $rs;
                if($phuong){
                    $rs = $phuong . ', ' . $rs;
                    if($address){
                        $rs = $address . ', ' . $rs;
                    }
                }
            }
        }
        return $rs;
    }

    function services(){
        return $this->hasMany(SalonService::class, 'salon_id', 'id');
    }

    function comboServices(){
        return $this->services()->where('is_combo', '=', 1);
    }


    function saleServices(){
        return $this->hasManyThrough(SalonServiceSale::class, SalonService::class,
            'salon_id',
        'service_id',
        'id',
        'id'
        );
    }

    function url(){
        $location = $this->location_lv1;
        $location = $location->name ? $location->name : 'other';
        $location = str_slug($location);
        return route('frontend.salon',
            [
                'salon'=>$this->id,
                'location_slug' => $location,
                'slug' => str_slug($this->name)
            ]
        );
    }

    function gallery(){
        return $this->hasMany(SalonGallery::class, 'salon_id', 'id');
    }

    function likedBy($user_id){
        return SalonLike::whereSalonId($this->id)->where('user_id', $user_id)->exists();
    }

    function liked_by_me(){
        return $this->hasOne(SalonLike::class, 'salon_id', 'id')
            ->where('user_id', me()?me()->id:-1)
            ;
    }

    function getHotServices(){
        return [];
    }

    function times(){
        return $this->hasMany(SalonOpenTime::class,'salon_id','id')
            ->orderBy('weekday', 'asc');
    }

    function timeWorkingHours(){
        $min = '23:59:59';
        $max = '00:00:00';
        $times = $this->times;
        foreach ($times as $time){
            if($time->start<$min){
                $min = $time->start;
            }
            if($time->end > $max){
                $max = $time->end;
            }
        }
        if($max < $min){
            $temp = $max;
            $max = $min;
            $min = $temp;
        }
        $min = Carbon::createFromFormat('H:i:s', $min);
        $max = Carbon::createFromFormat('H:i:s', $max);
        return $min->format('H:i') . ' - ' .$max->format('H:i');
    }

    function timeWeekDays(){
        $times = $this->times;
        $rs = '';
        if($times->count() == 7){
            return __('Tất cả ngày trong tuần');
        }
        foreach ($times as $k=>$time){
            if($k>0){
                $rs .= ', ';
            }
            $d = $time->weekday;
            switch ($d){
                case 1:
                    $rs .= 'T2';
                    break;
                case 2:
                    $rs .= 'T3';
                    break;
                case 3:
                    $rs .= 'T4';
                    break;
                case 4:
                    $rs .= 'T5';
                    break;
                case 5:
                    $rs .= 'T6';
                    break;
                case 6:
                    $rs .= 'T7';
                    break;
                case 7:
                    $rs .= 'CN';
                    break;
            }
        }
        if(!$rs){
            $rs = 'Chưa xác định';
        }
        return $rs;
    }

    function service_categories(){
        return $this->hasManyThrough(SalonServiceCategory::class, SalonService::class,
            'salon_id',
        'id',
        'id',
        'category_id'
        )->orderBy(SalonServiceCategory::dbTableName().'.ordering', 'asc')->groupBy(SalonServiceCategory::dbTableName().'.id');
    }

    function countServiceByCat($cat_id){
        $rs = 0;
        /** @var SalonService $service */
        foreach ($this->services as $service){
            if($service->category_id == $cat_id){
                $rs++;
            }
        }
        return $rs;
    }

    function stylist(){
        return $this->hasMany(SalonStylist::class,'salon_id','id');
    }

    function brands(){
        return $this->hasMany(SalonBrand::class,'salon_id', 'id');
    }

    function extended_info(){
        return $this->hasMany(SalonExtraInfo::class,'salon_id','id');
    }

    function showcases(){
        return $this->hasMany(SalonShowcase::class,'salon_id', 'id');
    }



    function cacheRating($refresh = false){
        $services = $this->services;
        if($refresh){
            foreach ($services as $service){
                $service->cacheRating(true);
            }
        }
        $reviews = $this->approved_reviews;
        $reviews->load([]);
        $count = $reviews->count();
        $rating = 0;
        if($count){
            $rating = $reviews->average(function ($item){
                /** @var SalonServiceReview $item */
                return $item->rating;
            });
        }
        $this->rating = $rating;
        $this->rating_count = $count;
        $review_c_ratings = [];
        foreach ($reviews as $review){
            $review_c_ratings[$review->id] = $review->getCriteriaRatings();
        }
        /** @var SalonServiceReviewCriteria[] $cris */
        $cris = app('review_criterias');
        $cs = [];
        foreach ($cris as $item){
            $sum = 0;
            $c = 0;
            $av = 0;
            foreach ($review_c_ratings as $review_c_rating){
                $sum += $review_c_rating[$item->id];
                $c++;
            }
            if($sum>0){
                $av = $sum*1.0/$c;
            }
            $cs[$item->id] = $av;
        }
        $this->rating_criterias = json_encode($cs);
        $this->save();
    }

    function getOrderTimeList(){
        $ls = $this->times;
        $rs = [
            1 => [],
            2 => [],
            3 => [],
            4 => [],
            5 => [],
            6 => [],
            7 => [],
        ];
        foreach ($ls as $l){
            $data = [];
            $from = Carbon::createFromFormat('H:i:s', $l->start);
            $temp = $from;
            $end = Carbon::createFromFormat('H:i:s', $l->end);
            while($temp->lessThanOrEqualTo($end)){
                $data[] = $temp->format('H:i');
                $minute = $temp->format('i') * 1;
                if($minute<30){
                    $add_minute = 30 - $minute;
                }
                else if($minute > 30){
                    $add_minute = 60 - $minute;
                }
                else{
                    $add_minute = 30;
                }
                $temp = $temp->addMinute($add_minute);
            }
            $rs[$l->weekday] = $data;
        }
        return $rs;
    }

    function promo(){
        return $this->hasOne(PromoSalon::class, 'salon_id', 'id');
    }

    function getServicePromoPrice(SalonService $service){
        if($service->salon_id != $this->id){
            return false;
        }
        if(!$this->isInPromo()){
            return false;
        }
        $settings = getSettingsFromPage('promo_configs');
        $settings = collect($settings);
        $cat_id = $service->category_id.'';
        $promo_cats = $settings->get('promo_cats', []);
        if(!is_array($promo_cats)){
            $promo_cats = [$promo_cats];
        }
        $promo_percent = $settings->get('promo_percent', 0) * 1;
        if($promo_percent <= 0){
            return false;
        }
        if(!in_array($cat_id, $promo_cats)){
            return false;
        }

        $ordered = $this->countPromoOrder();
        $max_order = $settings->get('promo_limit', 0) * 1;
        if($ordered >= $max_order){
            return false;
        }

        if($promo_percent > 100){
            $promo_percent = 100;
        }
        $price = $service->price;
        $price = $price - ($price * $promo_percent / 100);
        $price = round($price);
        return $price;
    }

    function countPromoOrder(){
        $settings = getSettingsFromPage('promo_configs');
        $settings = collect($settings);
        $promo_cats = $settings->get('promo_cats', []);
        if(!is_array($promo_cats)){
            $promo_cats = [$promo_cats];
        }
        $promo_cats = array_map(function($item){
            return $item * 1;
        }, $promo_cats);
        $today = Carbon::today();
        $statuses[] = $settings->get('promo_count_status', [SalonOrder::_CHO_THUC_HIEN_]);
        if ($statuses == null){
            $statuses = [];
        }
        $count = SalonOrderItem::
        join('salon_orders', 'salon_orders.id', '=', 'salon_order_items.order_id')
            ->join('salon_services', 'salon_services.id', '=', 'salon_order_items.service_id')
            ->where('salon_orders.salon_id', $this->id)
            ->whereIn('salon_services.category_id', $promo_cats)
            ->whereDate('salon_orders.created_at', $today)
            ->whereIn('salon_orders.status', $statuses)
            ->count();
        return $count;
    }

    function getPromoCatIDs(){
        $rs = [];
        if($this->isInPromo()){
            $settings = getSettingsFromPage('promo_configs');
            $settings = collect($settings);
            $promo_cats = $settings->get('promo_cats', []);
            $rs = $promo_cats;
        }
        return $rs;
    }

    function isInPromo(){
        $promo  = $this->promo;
        if(!$promo){
            return false;
        }

        $settings = getSettingsFromPage('promo_configs');
        $settings = collect($settings);
        $promo_enable = $settings->get('promo_enable', false);

        if(!$promo_enable){
            return false;
        }
        $promo_date_range = $settings->get('promo_date_range', null);
        $promo_date_range = explode(' - ', $promo_date_range);

        if(!$promo_date_range){
            return false;
        }

        $start_date = Carbon::createFromFormat('Y-m-d', $promo_date_range[0])->startOfDay();
        $end_date = Carbon::createFromFormat('Y-m-d', $promo_date_range[1])->startOfDay();
        $today = Carbon::today()->startOfDay();
        if($today->lessThan($start_date)){
            return false;
        }

        if($today->greaterThan($end_date)){
            return false;
        }
        $promo_days = $settings->get('promo_days') && !is_array($settings->get('promo_days')) ? [$settings->get('promo_days')] : $settings->get('promo_days') ;

        if(!in_array($today->dayOfWeekIso.'', $promo_days)){
            return false;
        }

        return true;
    }

    function hasSaleServices () {
        $services = $this->saleServices;
        if(count($services) == 0){
            return false;
        } else {
            return true;
        }
    }
}
