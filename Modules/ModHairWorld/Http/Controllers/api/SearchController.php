<?php

namespace Modules\ModHairWorld\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\UploadedFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\SalonServiceCategory;
use Modules\ModHairWorld\Entities\SalonLike;
use DB;

class SearchController extends Controller
{
    function listSalons(Request $request) {
        $per_page = $request->get('per_page', 10);
        $salons = Salon::where('certified', 1)
            ->where('open', 1)
            ->paginate($per_page);
        $rs = [
            'data' => array_map(function($salon) {
                return [
                    'id' => $salon->id,
                    'name' => $salon->name,
                    'cover' => $salon->cover ? $salon->cover->getThumbnailUrl('large', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                    'address' => $salon->address_cache,
                ];
            }, $salons->items()),
            'paging' => [
                'size' => $salons->perPage(),
                'number' => $salons->currentPage(),
                'isLastPage' => $salons->currentPage() === $salons->lastPage(),
                'totalElements' => $salons->total(),
            ]
        ];

        return \Response::json($rs);
    }

    function searchSalons(Request $request){
        $rs = SearchV2Controller::rawSearch($request);
        $salons = SearchV2Controller::searchOutput($request, $rs, 4, true);
        if($salons) {
            return [
                'data' => array_map(function($salon) {
                    return [
                        'id' => $salon['id'],
                        'name' => $salon['name'],
                        'cover' => $salon['cover'],
                        'address' => $salon['address'],
                    ];
                }, $salons['items']),
                'paging' => [
                    'size' => $salons['per_page'],
                    'number' => $salons['page'],
                    'isLastPage' => $salons['is_last_page'],
                    'totalElements' => $salons['total'],
                ],
            ];
        }
        return \Response::json([
            'data' => [],
            'paging' => [
                'size' => 0,
                'number' => 0,
                'isLastPage' => true,
                'totalElements' => 0,
            ]
        ]);
    }

    function topSalons(Request $request) {
        \Validator::validate($request->all(), [
            'provinceId' => ['required'],
        ]);
        $from_lat = $request->get('from_lat');
        $from_lng = $request->get('from_lng');
        $provinceId = $request->get('provinceId');
        $salons = Salon::where(['tinh_thanh_pho_id' => $provinceId, 'open' => 1])
            ->orderBy('rating', 'desc')->orderBy('rating_count', 'desc')->limit(20)->get();
        $marker_colors = getSettings([
            'theme_mobile_show_unverified_map_marker' => true,
        ]);
        if(!$marker_colors['theme_mobile_show_unverified_map_marker']){
            $salons = $salons->where('certified', 1);
        }
        $result = [];
        /** @var Salon $salon */
        foreach ($salons as $salon) {
            $price_from = $salon->price_from_cache;
            $sale_of_up_to = $salon->sale_up_to_percent_cache;
            $like = false;
            if(auth()->guard('api')->user()) {
                $salonlike = SalonLike::where('user_id', auth()->guard('api')->user()->id)->get();
                foreach ($salonlike as $uk => $value) {
                    if ($salonlike[$uk]->salon_id == $salon->id) {
                        $like = true;
                    }
                }
            }
            $distance = -1;
            $to_lat = $salon->map_lat;
            $to_lng = $salon->map_long;
            if (is_numeric($from_lat) && is_numeric($from_lng) && is_numeric($to_lat) && is_numeric($to_lng)) {
                if ($from_lat>0 && $from_lng>0 && $to_lat>0 && $to_lng>0) {
                    $from_lat = $from_lat * 1.0;
                    $from_lng = $from_lng * 1.0;
                    $to_lat = $to_lat * 1.0;
                    $to_lng = $to_lng * 1.0;
                    $distance = static::getDistance($from_lat, $from_lng, $to_lat, $to_lng);
                }
            }
            $result[] = [
                'id' => $salon->id,
                'name' => $salon->name,
                'address' => $salon->address_cache,
                'rating'  => $salon->rating,
                'rating_count' => $salon->rating_count,
                'cover' => $salon->cover ? $salon->cover->getThumbnailUrl('large', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                'open' => $salon->open,
                'verified' => $salon->certified,
                'price_from' => $price_from,
                'sale_off_up_to' => $sale_of_up_to,
                'distance' => $distance,
                'liked' => $like,
//                'services' => $services,
                'location' => [
                    'latitude' => $salon->map_lat,
                    'longitude' => $salon->map_long,
                ]
            ];
        }
        return \Response::json($result);
    }

    function topCities(Request $request) {
        // select tinh_thanh_pho_id, count(*) as count_city from wa_salons group by tinh_thanh_pho_id order by count_city desc;
//        $cities = Salon::groupBy(['tinh_thanh_pho_id'])->select(['tinh_thanh_pho_id', DB::raw('count(*) as count_salons')])->orderBy('count_salons', 'desc')->get();
//        return \Response::json($cities);
        $popular_cities_json = DB::table('settings')->select('value')->where('name', '=', 'theme_home_popular_cities')->get();
        $popular_cities_value = json_decode($popular_cities_json, true);
        $popular_cities = [];
        if (count($popular_cities_value) > 0) {
            $popular_cities = json_decode($popular_cities_value[0]['value'], true);
        }
        $popular_cities_rs = [];
        foreach ($popular_cities as $city) {
            $img_id = $city['img'];
            $city_id = $city['city_id'];
            $city_name = DB::table('dia_phuong_tinh_thanh_pho')->select(['name'])->where('id', '=', $city_id)->first();
//            $img = DB::table('uploaded_files')->select(['name', 'extension', 'path'])->where('id', '=', $img_id)->first();
            $img = UploadedFile::where('id', '=', $img_id)->first();
            $amount = Salon::where('tinh_thanh_pho_id', '=', $city_id)->select([DB::raw('count(*) as count_salons')])->first();
            array_push($popular_cities_rs, [
                'city_id' => $city_id,
                'name' => $city_name->name,
                'img_url' => $img->getThumbnailUrl('medium', getNoThumbnailUrl()),
                'amount' => $amount->count_salons,
            ]);
        }
        return \Response::json($popular_cities_rs);
    }

    function topDeals(Request $request) {
        $top_deals_json = DB::table('settings')->select('value')->where('name', '=', 'theme_home_deals')->get();
        $top_deals_value = json_decode($top_deals_json, true);
        $top_deals = [];
        if (count($top_deals_value) > 0) {
            $top_deals = json_decode($top_deals_value[0]['value'], true);
        }
        $top_deals_rs = [];
        foreach ($top_deals as $deal) {
            $img_id = $deal['image'];
            $link = $deal['link'];
            $title = $deal['title'];
            $img = UploadedFile::where('id', '=', $img_id)->first();
            array_push($top_deals_rs, [
                'title' => $title,
                'img_url' => $img->getThumbnailUrl('medium', getNoThumbnailUrl()),
                'link' => $link,
            ]);
        }
        return \Response::json($top_deals_rs);
    }

    function topBrands(Request $request) {
        $top_deals_json = DB::table('settings')->select('value')->where('name', '=', 'theme_config_famous_brand')->get();
        $top_deals_value = json_decode($top_deals_json, true);
        $top_deals = [];
        if (count($top_deals_value) > 0) {
            $top_deals = json_decode($top_deals_value[0]['value'], true);
        }
        $top_deals_rs = [];
        foreach ($top_deals as $deal) {
            $img_id = $deal['image'];
            $link = $deal['link'];
            $title = $deal['title'];
            $img = UploadedFile::where('id', '=', $img_id)->first();
            array_push($top_deals_rs, [
                'title' => $title,
                'img_url' => $img->getUrl(),
                'link' => $link,
            ]);
        }
        return \Response::json($top_deals_rs);
    }

    function serviceCategories(Request $request){
        $ls = SalonServiceCategory::all()->map(function (SalonServiceCategory $category){
            return [
                'id' => $category->id,
                'title' => $category->title
            ];
        });
        return \Response::json($ls);
    }

    function getFeaturedItems(Request $request){
        $rs = [
            'cats' => [],
            'salons' => []
        ];
        $cats = SalonServiceCategory::with('cover')->limit(6)->get()->map(function (SalonServiceCategory $item){
            return [
                'id' => $item->id,
                'name' => $item->title,
                'image' => $item->cover?$item->cover->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl()
            ];
        });
        if($cats){
            $rs['cats'] = $cats;
        }
        $salons = Salon::with(['liked_by_me', 'location_lv1', 'location_lv2', 'location_lv3', 'services', 'services.sale_off'])
            ->orderBy('rating', 'desc')->orderBy('rating_count', 'desc')->limit(5)->get();
        if($salons){
            $from_lat = $request->get('from_lat');
            $from_lng = $request->get('from_lng');
            $rs['salons'] = $salons->map(function(Salon $item) use ($from_lat, $from_lng){
                $distance = '??';
                $to_lat = $item->map_lat;
                $to_lng = $item->map_long;
                if(is_numeric($from_lat) && is_numeric($from_lng) && is_numeric($to_lat) && is_numeric($to_lng)){
                    $from_lat = $from_lat * 1.0;
                    $from_lng = $from_lng * 1.0;
                    $to_lat = $to_lat * 1.0;
                    $to_lng = $to_lng * 1.0;
                    $distance = static::getDistance($from_lat, $from_lng, $to_lat, $to_lng);
                    $distance = number_format($distance/1000.0, $distance>9?0:2,'.', ',');
                }
                return [
                    'id' => $item->id,
                    'image' => $item->cover?$item->cover->getThumbnailUrl('medium', getNoThumbnailUrl()):getNoThumbnailUrl(),
                    'name' => $item->name,
                    'address' => $item->getAddressLine(),
                    'distance' => $distance,
                    'rating' => $item->rating*1.0,
                    'ratingCount' => $item->rating_count+0,
                    'price' => $item->services->min(function(SalonService $service) {
                        return ($service->getFinalPrice() / 1000.0) . 'K';
                    }),
                    'priceNumber' => $item->services->min(function(SalonService $service) {
                        return $service->getFinalPrice();
                    }),
                    'liked' => $item->liked_by_me != null
                ];
            });
        }
        return \Response::json($rs);
    }

    function mapRadius(Request $request){
        $setting = getSetting('theme_mobile_map_search_radius', 250);
        $setting = $setting*1;
        return \Response::json($setting);
    }

    function salonNearMe(Request $request){
        $from_lat = $request->get('from_lat');
        $from_lng = $request->get('from_lng');
        $user_from_lat = $request->get('user_from_lat');
        $user_from_lng = $request->get('user_from_lng');
        $marker_colors = getSettings([
            'theme_mobile_map_marker_color_1' => '#ef5248',
            'theme_mobile_map_marker_color_2' => '#ffffff',
            'theme_mobile_map_marker_alt_color_1' => '#ef5248',
            'theme_mobile_map_marker_alt_color_2' => '#ffffff',
            'theme_mobile_map_marker_sl_color_1' => '#ef5248',
            'theme_mobile_map_marker_sl_color_2' => '#ffffff',
            'theme_mobile_show_unverified_map_marker' => true,
            'theme_mobile_map_search_radius' => 250,
            'theme_mobile_map_salon_limit' => 20,
        ]);
        $limit = $marker_colors['theme_mobile_map_salon_limit']*1;
        $radius = $marker_colors['theme_mobile_map_search_radius']*1.0;
        $version = $request->get('version', 0);
        if($version == 1){
            $distance = ($marker_colors['theme_mobile_map_search_radius']*1.0/1000);
        }
        else{
            $distance = (250.0/1000);
        }
        /** @var Salon[]|Collection $salons */
        $salons = Salon::with([
            'cover',
            'services',
            'services.sale_off',
            'location_lv1',
            'location_lv2',
            'location_lv3',
            'liked_by_me'
        ]);
        if(!$marker_colors['theme_mobile_show_unverified_map_marker']){
            $salons = $salons->where('certified', 1);
        }
        $salons = $salons->where('open', 1);
        $salons = $salons->whereRaw(
            "6371 * 2 * ASIN(SQRT(
            POWER(SIN((map_lat - abs({$from_lat})) * pi()/180 / 2),
            2) + COS(map_lat * pi()/180 ) * COS(abs({$from_lat}) *
            pi()/180) * POWER(SIN((map_long - {$from_lng}) *
            pi()/180 / 2), 2) ))<={$distance}"
        )->orderBy('certified', 'desc')->orderBy('rating', 'desc');

        if($limit){
            $salons = $salons->limit($limit);
        }

        $salons = $salons->get();
        $salons = $salons->map(function(Salon $item) use ($from_lat, $from_lng, $user_from_lat, $user_from_lng, $marker_colors){
            $distance = '??';
            $to_lat = $item['map_lat']*1.0;
            $to_lng = $item['map_long']*1.0;
            if(is_numeric($user_from_lat) && is_numeric($user_from_lng) && is_numeric($to_lat) && is_numeric($to_lng)){
                if(
                    $user_from_lat>0 && $user_from_lng>0 && $to_lat>0 && $to_lng>0
                ) {
                    $user_from_lat = $user_from_lat * 1.0;
                    $user_from_lng = $user_from_lng * 1.0;
                    $to_lat = $to_lat * 1.0;
                    $to_lng = $to_lng * 1.0;
                    $distance = static::getDistance($user_from_lat, $user_from_lng, $to_lat, $to_lng);
                    $distance = number_format($distance / 1000.0, $distance > 9 ? 0 : 2, '.', ',');
                }
            }
            $price_from = $item->services->min(function(SalonService $service){
                return ($service->getFinalPrice()/1000.0);
            });
            $price_from_number = $item->services->min(function(SalonService $service){
                return $service->getFinalPrice();
            });
            $like = false;
             if(me()) {
               $salonlike = SalonLike::where('user_id', me()->id)->get();
               foreach ($salonlike as $key =>$value) {
                  if($salonlike[$key]->salon_id == $salon->id){
                    $like = true;
                }
            }
        } 
            return [
                'id' => $item->id, 
                'image' => $item->cover ? $item->cover->getThumbnailUrl('large', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                'name' => $item->name,
                'distance' => $distance,
                'address' => $item->getAddressLine(),
                'lat' => $item->map_lat,
                'lng' => $item->map_long,
                'rating' => $item->rating,
                'rating_count' => $item->rating_count,
                'price_from' => $price_from?$price_from.'K':'??K',
                'price_from_number' => $price_from_number?$price_from_number:0,
                'marker_color_1' => $item->certified?$marker_colors['theme_mobile_map_marker_color_1']:$marker_colors['theme_mobile_map_marker_alt_color_1'],
                'marker_color_2' => $item->certified?$marker_colors['theme_mobile_map_marker_color_2']:$marker_colors['theme_mobile_map_marker_alt_color_2'],
                'marker_color_sl_1' => $item->certified?$marker_colors['theme_mobile_map_marker_sl_color_1']:$marker_colors['theme_mobile_map_marker_sl_color_1'],
                'marker_color_sl_2' => $item->certified?$marker_colors['theme_mobile_map_marker_sl_color_2']:$marker_colors['theme_mobile_map_marker_sl_color_2'],
                'liked' => $like,

            ];
        });
        if($version == 0){
            return \Response::json(array_reverse($salons->all()));
        }
        else{
            return \Response::json([
                'salons' => array_reverse($salons->all()),
                'radius' => $radius
            ]);
        }
    }



    function fullSearch(Request $request){
        $from_lat = $request->get('from_lat');
        $from_lng = $request->get('from_lng');
        $temp = \Modules\ModHairWorld\Http\Controllers\frontend\SearchController::rawSearch($request,10, false, true);
        //$raw = \Modules\ModHairWorld\Http\Controllers\frontend\SearchController::rawSearchV2($request, 1,true);
        //$temp = \Modules\ModHairWorld\Http\Controllers\frontend\SearchController::searchv2Output($request, $raw, 10,true);
        if($temp['result']){
            $temp['result'] = array_map(function($item) use($from_lat, $from_lng){
                $services = array_map(function($sub) use($from_lat, $from_lng){
                    return [
                        'id' => $sub['service_id'],
                        'salonID' => $sub['salon_id'],
                        'name' => $sub['service_name'],
                        'price' =>  $sub['price_final'],
                        'oldPrice' =>  $sub['price_final'] == $sub['price_org']?0:$sub['price_org'],
                        'color' => $sub['color'],
                        'text_color' => $sub['text_color'],
                        'options' => $sub['options']
                    ];
                }, $item['services']);
                $distance = '??';
                $to_lat = $item['location_lat'];
                $to_lng = $item['location_lng'];
                if(is_numeric($from_lat) && is_numeric($from_lng) && is_numeric($to_lat) && is_numeric($to_lng)){
                    if(
                        $from_lat>0 && $from_lng>0 && $to_lat>0 && $to_lng>0
                    ){
                        $from_lat = $from_lat * 1.0;
                        $from_lng = $from_lng * 1.0;
                        $to_lat = $to_lat * 1.0;
                        $to_lng = $to_lng * 1.0;
                        $distance = static::getDistance($from_lat, $from_lng, $to_lat, $to_lng);
                        $distance = number_format($distance/1000.0, $distance>9?0:2,'.', ',');
                    }
                }
                return [
                    'id' => $item['salon_id'],
                    'image' => $item['salon_cover'],
                    'name' => $item['salon_name'],
                    'open' => $item['open'],
                    'verified' => $item['verified'],
                    'distance' => $distance,
                    'address' => $item['address'],
                    'services' => $services,
                    'rating' => $item['rating'],
                    'rating_count' => $item['rating_count'],
                    'price' => $item['price_from'],
                    'liked' => $item['liked'],
                    'sale_up_to' => $item['sale_up_to'],
                    'sale_up_to_percent' => $item['sale_of_up_to'],
                ];
            }, $temp['result']);
        }
        return \Response::json($temp);
    }

    function searchByKeyword(Request $request){
        $keyword = $request->get('keyword');
        $from_lat = $request->get('from_lat');
        $from_lng = $request->get('from_lng');
        $rs = [
            'services' => [],
            'salons' => [],
            'total' => 0
        ];
        $services = SalonServiceCategory::whereHas('services', function($query) use ($keyword){
            /** @var Builder $query */
            $query->where('name', 'like', "%{$keyword}%");
        })->orWhere('title', 'like', "%{$keyword}%")
            ->get()
            ->map(function(SalonServiceCategory $category){
                return [
                    'id' => $category->id,
                    'name' => $category->title
                ];
            });
        if($services){
            $rs['services'] = $services;
        }
        $temp = \Modules\ModHairWorld\Http\Controllers\frontend\SearchController::rawSearch($request,3);
        //$raw = \Modules\ModHairWorld\Http\Controllers\frontend\SearchController::rawSearchV2($request, 1,true);
        //$temp = \Modules\ModHairWorld\Http\Controllers\frontend\SearchController::searchv2Output($request, $raw, 1,true);
        $rs['total'] = $temp['total'];
        if($temp['result']){
            $rs['salons'] = array_map(function($item) use($from_lat, $from_lng){
                $services = array_map(function($sub) use($from_lat, $from_lng){
                    return [
                            'name' => $sub['service_name'],
                            'price' =>  $sub['price_final'],
                            'oldPrice' =>  $sub['price_final'] == $sub['price_org']?0:$sub['price_org'],
                            'color' => $sub['color'],
                            'text_color' => $sub['text_color']
                    ];
                }, $item['services']);
                $services = array_splice($services, 0, 1);
                $distance = '??';
                $to_lat = $item['location_lat'];
                $to_lng = $item['location_lng'];
                if(is_numeric($from_lat) && is_numeric($from_lng) && is_numeric($to_lat) && is_numeric($to_lng)){
                    if(
                        $from_lat>0 && $from_lng>0 && $to_lat>0 && $to_lng>0
                    ){
                        $from_lat = $from_lat * 1.0;
                        $from_lng = $from_lng * 1.0;
                        $to_lat = $to_lat * 1.0;
                        $to_lng = $to_lng * 1.0;
                        $distance = static::getDistance($from_lat, $from_lng, $to_lat, $to_lng);
                        $distance = number_format($distance/1000.0, $distance>9?0:2,'.', ',');
                    }
                }
                return [
                    'id' => $item['salon_id'],
                    'image' => $item['salon_cover'],
                    'name' => $item['salon_name'],
                    'distance' => $distance,
                    'address' => $item['address'],
                    'services' => $services,
                ];
            }, $temp['result']);
        }
        return \Response::json($rs);
    }

    static function getDistance(
        $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $lonDelta = $lonTo - $lonFrom;
        $a = pow(cos($latTo) * sin($lonDelta), 2) +
            pow(cos($latFrom) * sin($latTo) - sin($latFrom) * cos($latTo) * cos($lonDelta), 2);
        $b = sin($latFrom) * sin($latTo) + cos($latFrom) * cos($latTo) * cos($lonDelta);

        $angle = atan2(sqrt($a), $b);
        return $angle * $earthRadius;
    }
}