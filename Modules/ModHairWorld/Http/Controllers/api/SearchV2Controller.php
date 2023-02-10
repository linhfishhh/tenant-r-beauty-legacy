<?php

namespace Modules\ModHairWorld\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use function foo\func;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\ModFAQ\Entities\FAQ;
use Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonLike;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\SalonServiceCategory;
use Modules\ModHairWorld\Entities\SalonServiceOption;

class SearchV2Controller extends Controller
{
    function searchLocationListLV1(Request $request){
        $q = DiaPhuongTinhThanhPho::query()->orderBy('name')->get(['id', 'name', 'lat', 'lng']);
        return \Response::json($q);
    }

    function searchLocationFind(Request $request){
        $find = $request->get('find', '');
        if(!$find){
            return \Response::json(false);
        }
        $rs = DiaPhuongTinhThanhPho::where('name', 'like', "%{$find}%")->first();
        if($rs){
            $name = $rs->name;
            $name = str_replace('Tỉnh','', $name);
            $name = str_replace('Thành phố', '', $name);
            $name = trim($name);
            //$name = ucwords($name);
            $rs = [
                'id' => $rs->id,
                'name' => $name,
                'lat' => $rs->lat,
                'lng' => $rs->lng
            ];
        }
        return \Response::json($rs);
    }

    function searchLocationListLV2(Request $request, DiaPhuongTinhThanhPho $lv1){
        $lv1->load(['lv2' => function($query){
            $query->orderBy('name')->get(['id', 'name']);
        }]);
        $rs = $lv1->lv2->map(function($item){
            return [
                'id' => $item->id,
                'name' => $item->name
            ];
        });
        return \Response::json($rs);
    }

    function searchConfigs(Request $request){
        $q = SalonServiceCategory::with('cover')
            ->where('published', '=', 1)
            ->orderBy('ordering')
            ->get();
        $rs = $q->map(function($item, $index){
            /** @var SalonServiceCategory $item */
            return [
                'id' => $item->id,
                'name' => $item->title,
                'cover' => $item->cover?$item->cover->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl()
            ];
        });

        $settings = getSettings([
            'theme_mobile_map_marker_color_1' => '#ef5248',
            'theme_mobile_map_marker_color_2' => '#ffffff',
            'theme_mobile_map_marker_alt_color_1' => '#ef5248',
            'theme_mobile_map_marker_alt_color_2' => '#ffffff',
            'theme_mobile_map_marker_sl_color_1' => '#ef5248',
            'theme_mobile_map_marker_sl_color_2' => '#ffffff',
            'theme_mobile_show_unverified_map_marker' => true,
            'theme_mobile_map_salon_limit' => 20,
            'theme_mobile_map_search_radius_list' => [
                1000,
                1500,
                2000
            ],
        ]);

        return \Response::json([
            'cats' => $rs,
            'settings' => [
                'search_radius_list' => array_map(function($item){
                    return $item['radius']*1;
                }, array_values($settings['theme_mobile_map_search_radius_list'])),
                'marker_color_1_normal' => $settings['theme_mobile_map_marker_color_1'],
                'marker_color_2_normal' => $settings['theme_mobile_map_marker_color_2'],
                'marker_color_1_active' => $settings['theme_mobile_map_marker_sl_color_1'],
                'marker_color_2_active' => $settings['theme_mobile_map_marker_sl_color_2'],
            ]
        ]);
    }

    function searchLatestSalons(Request $request){

    }

    function searchMostBookedSalons(Request $request){

    }

    function searchNearMe(Request $request){

    }

    static function fullTextKeyword($keyword){
//        $rs = '';
//        $ls = explode(' ', trim($keyword));
//        foreach ($ls as $l){
//            $rs .= '+'.$l.' ';
//        }
//        return trim($rs);
        return '"'.trim($keyword).'"';
    }

    function search(Request $request){
        $rs = static::rawSearch($request);
        $output = static::searchOutput($request, $rs, 20, true);
        return \Response::json($output);
    }

    static function rawSearch(Request $request, $type = 1, $mobile = false){
        $keyword = mb_strtolower($request->get('keyword'));
        $search_type =  $request->get('search_type', 'search_type');
        $is_sale = $request->get('is_sale', false);
        $rating = $request->get('rating', 0);
        $price_from = $request->get('price_from', 0);
        $price_to = $request->get('price_to', 0);
        $workday = $request->get('workday', '');
        $address_lat = $request->get('address_lat',null);
        $address_lng = $request->get('address_lng',null);
        $from_lat = $request->get('from_lat');
        $from_lng = $request->get('from_lng');
        $distance = $request->get('distance', null);
        $location = $request->get('location', []);
        $cat =$request->get('cat', []);
        $location_lv = $request->get('location_lv', null);
        $view_type = $request->get('view_type', null);
        $q = Salon::query();
        $q->join('salon_services', 'salons.id', '=', 'salon_services.salon_id');
        $q->groupBy('salons.id');
        if($type == 1){
            $selects =   [
                'salons.id',
                'salons.name',
                'salons.cover_id',
                'salons.tinh_thanh_pho_id',
                'salons.quan_huyen_id',
                'salons.phuong_xa_thi_tran_id',
                'salons.address',
                'salons.map_lat',
                'salons.map_long',
                'salons.open',
                'salons.certified',
                'salons.rating',
                'salons.rating_count',
                'salons.price_from_cache',
                'salons.sale_up_to_cache',
                'salons.sale_up_to_percent_cache',
                'salons.booked_cache',
                'salons.address_cache',
                'salons.verified_at'
            ];
        }
        else{
            $selects =   [
            ];
        }

        if($from_lat && $from_lng){
            $selects[] = DB::raw(
                "6371 * 2 * ASIN(SQRT(
                    POWER(SIN((map_lat - abs({$from_lat})) * pi()/180 / 2),
                    2) + COS(map_lat * pi()/180 ) * COS(abs({$from_lat}) *
                    pi()/180) * POWER(SIN((map_long - {$from_lng}) *
                    pi()/180 / 2), 2) )) as distance"
            );
        }

        if($address_lat && $address_lng){
            $q->whereRaw(
                "6371 * 2 * ASIN(SQRT(
                    POWER(SIN((map_lat - abs({$address_lat})) * pi()/180 / 2),
                    2) + COS(map_lat * pi()/180 ) * COS(abs({$address_lat}) *
                    pi()/180) * POWER(SIN((map_long - {$address_lng}) *
                    pi()/180 / 2), 2) ))<={$distance}"
            );
        }

        if($type == 1){
            if($cat){
                $q->join('service_categories','service_categories.id', '=', 'salon_services.category_id');
                /** @var Builder $query */
                $q->whereIn('salon_services.category_id', $cat);
            }
            if($location && $location_lv && ($view_type != 'near_me')){
                if($location_lv == 1){
                    $q->join('dia_phuong_tinh_thanh_pho','salons.tinh_thanh_pho_id', '=', 'dia_phuong_tinh_thanh_pho.id');
                    $q->whereIn('salons.tinh_thanh_pho_id',$location );
                }
                else if($location_lv == 2){
                    $q->join('dia_phuong_quan_huyen','salons.quan_huyen_id', '=', 'dia_phuong_quan_huyen.id');
                    $q->whereIn('salons.quan_huyen_id',$location );
                }
                else if($location_lv == 3){
                    $q->join('dia_phuong_xa_phuong_thi_tran','salons.phuong_xa_thi_tran_id', '=', 'dia_phuong_xa_phuong_thi_tran.id');
                    $q->whereIn('salons.phuong_xa_thi_tran_id',$location );
                }
            }
        }

        if($rating){
            $q->where('salons.rating', '>=', $rating);
        }


        if($search_type == 'salon'){
            if($is_sale){
                $q->where('salons.sale_up_to_cache', '>', 0);
            }
            if($price_from || $price_to){
                if($price_from){
                    $q->where('salons.price_from_cache', '>=', $price_from);
                }
                if($price_to){
                    $q->where('salons.price_to_cache', '<=', $price_to);
                }
            }
            if($keyword) {
                //$keyword = trim(str_replace_first('salon', '', $keyword));
                $keyword = static::fullTextKeyword($keyword);
                //dd($keyword);
                //$q->where('salons.name', 'like', "%{$keyword}%");
                $q->WhereRaw("MATCH(wa_salons.name) AGAINST ('$keyword') > 0");
                $selects[] = DB::raw("MATCH(wa_salons.name) AGAINST ('$keyword') as relevance_salon");
                if($type == 1){
                    $q->orderBy('relevance_salon', 'desc');
                }
            }
        }
        //service
        else{
            if($keyword) {
                $keyword = static::fullTextKeyword($keyword);
                $q->WhereRaw("MATCH(wa_salon_services.name) AGAINST('$keyword') > 0");
                $selects[] = DB::raw("SUM(MATCH(wa_salon_services.name) AGAINST ('$keyword')) as relevance_service");
                if($type == 1){
                    $q->orderBy('relevance_service', 'desc');
                }
            }
            if($is_sale){
                $q->where('salon_services.sale_cache', '>', 0);
            }

            if($price_from || $price_to){
                if($price_from){
                    $q->where('salon_services.final_price_cache', '>=', $price_from);
                }
                if($price_to){
                    $q->where('salon_services.final_price_cache', '<=', $price_to);
                }
            }
        }

        if($workday){
            $workday = Carbon::createFromFormat('d/m/Y', $workday);
            $workday = $workday->dayOfWeek;
            if($workday == 0){
                $workday = 7;
            }
            $q->join('salon_open_times', 'salons.id', '=', 'salon_open_times.salon_id')->where('salon_open_times.weekday',$workday);
            $q->groupBy('salons.id');
        }

        $q->select(
            $selects
        );

        $q->where('salons.certified', true);
        $q->where('salons.open', true);

        $withs = [
            'cover',
            'services' => function($query) use($search_type, $keyword, $is_sale, $rating, $price_to, $price_from, $cat){
                $selects = [
                    'id',
                    'salon_id',
                    'name',
                    'color',
                    'text_color',
                    'price',
                    'category_id',
                    'rating',
                    'rating_count',
                    'time_from',
                    'time_to',
                    'sale_cache',
                    'sale_percent_cache',
                    'final_price_cache'
                ];

                if($is_sale){
                    $query->where('sale_cache', '>', 0);
                }

                if($price_from || $price_to){
                    if($price_from){
                        $query->where('final_price_cache', '>=', $price_from);
                    }
                    if($price_to){
                        $query->where('final_price_cache', '<=', $price_to);
                    }
                }

                if($cat){
                    $query->whereIn('category_id', $cat);
                }

                if($search_type == 'salon'){
                }
                //service
                else{
                    if($keyword){
                        $query->WhereRaw("MATCH(wa_salon_services.name) AGAINST('$keyword')");
                        $selects[] = DB::raw("MATCH(wa_salon_services.name) AGAINST ('$keyword') as relevancez");
                    }
                }
                $query->orderBy('sale_percent_cache', 'desc');
                $query->orderBy('sale_cache', 'desc');
                $query->select($selects);
            },
            'services.options',
            'services.sale_off'
        ];

        if($type == 1){
            $q->with($withs);
            if($view_type){
                switch ($view_type){
                    case 'latest':
                        $q->orderBy('salons.created_at', 'desc');
                        break;
                    case 'most_booking':
                        $q->orderBy('salons.booked_cache', 'desc');
                        break;
                    case 'near_me':
                        $q->orderBy('distance', 'asc');
                        break;
                }
            }
            // $q->orderBy('salons.rating', 'desc');
            // $q->orderBy('salons.rating_count', 'desc');
        }
        if (is_null($view_type)) {
            $q->orderByRaw('RAND()');
        }
        //$q = $q->paginate(10);
        //$ls = $q->items();
//        \Log::debug('query:'.$q->toSql());
        return $q;
    }

    function searchHint(Request $request){
        $keyword = $request->get('keyword');
        $tinh_thanh_pho_id = $request->get('location_lv1');
        $from_lat = $request->get('from_lat', 0)*1.0;
        $from_lng = $request->get('from_lng', 0)*1.0;
        $limit = $request->get('limit', false);

        $cats = SalonServiceCategory::where('title', 'like', "%{$keyword}%")
            ->with([
                'cover'
            ])
            ->get();
        $services = SalonService::where('salon_services.name', 'like', "%{$keyword}%")
            ->with([
                'salon',
                'salon.location_lv1'
            ])
            ->join('salons', 'salons.id', 'salon_services.salon_id')
            ->where('salons.certified', 1)
            ->where('salons.open', 1)
            ->groupBy('salon_services.salon_id')
            ->orderBy('distance', 'asc')
            ->orderBy('salon_services.booked_cache', 'desc')
            ->orderBy('salon_services.sale_percent_cache', 'desc')
            ->orderBy('salon_services.rating_count', 'desc')
            ->orderBy('salon_services.rating', 'desc')
            ->limit($limit?2:5);

        if($tinh_thanh_pho_id){
            $services->where('salons.tinh_thanh_pho_id', $tinh_thanh_pho_id);
        }

        $services = $services->select([
            'salons.tinh_thanh_pho_id',
            'salon_services.salon_id',
            'salon_services.id',
            'salon_services.name',
            'salon_services.final_price_cache',
            'salon_services.ranged_price',
            DB::raw(
                "6371 * 2 * ASIN(SQRT(
                    POWER(SIN((wa_salons.map_lat - abs({$from_lat})) * pi()/180 / 2),
                    2) + COS(wa_salons.map_lat * pi()/180 ) * COS(abs({$from_lat}) *
                    pi()/180) * POWER(SIN((wa_salons.map_long - {$from_lng}) *
                    pi()/180 / 2), 2) )) as distance"
            )
        ])->get();

        $salons = Salon::where('salons.name', 'like', "%{$keyword}%")
            ->with([
                'location_lv1'
            ])
            ->where('certified', 1)
            ->where('open', 1)
            ->limit(5);

        if($tinh_thanh_pho_id){
            $salons->where('tinh_thanh_pho_id', $tinh_thanh_pho_id);
        }

        $salons->orderBy('distance', 'asc');
        $salons->orderBy('salons.booked_cache', 'desc')
            ->orderBy('salons.sale_up_to_percent_cache', 'desc')
            ->orderBy('salons.rating_count', 'desc')
            ->orderBy('salons.rating', 'desc');

        $salons = $salons     ->select(
            [
                'salons.id',
                'salons.name',
                'salons.tinh_thanh_pho_id',
                'salons.rating',
                'salons.rating_count',
                'salons.price_from_cache',
                'salons.map_lat',
                'salons.map_long',
                'salons.address_cache',
                DB::raw(
                    "6371 * 2 * ASIN(SQRT(
                    POWER(SIN((wa_salons.map_lat - abs({$from_lat})) * pi()/180 / 2),
                    2) + COS(wa_salons.map_lat * pi()/180 ) * COS(abs({$from_lat}) *
                    pi()/180) * POWER(SIN((wa_salons.map_long - {$from_lng}) *
                    pi()/180 / 2), 2) )) as distance"
                )
            ]
        )->get();

        return \Response::json([
            'cats' => $cats->map(function(SalonServiceCategory $cat){
                $cover = $cat->cover?$cat->cover->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl();
                return [
                    'id' => $cat->id,
                    'name' => $cat->title,
                    'cover' => $cover,
                    'link' => route('frontend.search', ['cat' => [$cat->id]])
                ];
            }),
            'services' => $services->map(function(SalonService $service) use ($from_lat, $from_lng){
                $salon = $service->salon;
                $to_lat = $salon->map_lat;
                $to_lng = $salon->map_long;
                $distance = 0;
                if(is_numeric($from_lat) && is_numeric($from_lng) && is_numeric($to_lat) && is_numeric($to_lng)){
                    if(
                        $from_lat!=0 && $from_lng!=0 && $to_lat!=0 && $to_lng!=0
                    ){
                        $from_lat = $from_lat * 1.0;
                        $from_lng = $from_lng * 1.0;
                        $to_lat = $to_lat * 1.0;
                        $to_lng = $to_lng * 1.0;
                        $distance = static::getDistance($from_lat, $from_lng, $to_lat, $to_lng);
                    }
                }
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'price_from' => $service->final_price_cache,
                    'price_from_html' => number_format($service->final_price_cache, 0, 0, '.').'đ',
                    'ranged' => $service->ranged_price,
                    'link' => $salon->url().'?service='.$service->id,
                    'salon' => [
                        'id' => $service->salon->id,
                        'name' => $service->salon->name,
                        'location_name' => $service->salon->location_lv1->name,
                        'distance' => $distance
                    ]
                ];
            }),
            'salons' => $salons->map(function(Salon $salon) use ($from_lat, $from_lng){
                $to_lat = $salon->map_lat;
                $to_lng = $salon->map_long;
                $distance = 0;
                if(is_numeric($from_lat) && is_numeric($from_lng) && is_numeric($to_lat) && is_numeric($to_lng)){
                    if(
                        $from_lat!=0 && $from_lng!=0 && $to_lat!=0 && $to_lng!=0
                    ){
                        $from_lat = $from_lat * 1.0;
                        $from_lng = $from_lng * 1.0;
                        $to_lat = $to_lat * 1.0;
                        $to_lng = $to_lng * 1.0;
                        $distance = static::getDistance($from_lat, $from_lng, $to_lat, $to_lng);
                    }
                }
                return [
                    'id' => $salon->id,
                    'name' => $salon->name,
                    'price_from' => $salon->price_from_cache,
                    'rating' => $salon->rating,
                    'location_name' => $salon->location_lv1->name,
                    'address' => $salon->address_cache,
                    'distance' => $distance,
                    'link' => $salon->url()
                ];
            }),
            'debug' => $request->all()
        ]);
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

    static function searchOutput(Request $request, $q, $limit_service = 4, $mobile = false){
        $from_lat = $request->get('from_lat');
        $from_lng = $request->get('from_lng');
        $view_type = $request->get('view_type', null);
        $distance = -1;
        $settings = getSettings([
            'theme_mobile_map_marker_color_1' => '#ef5248',
            'theme_mobile_map_marker_color_2' => '#ffffff',
            'theme_mobile_map_marker_alt_color_1' => '#ef5248',
            'theme_mobile_map_marker_alt_color_2' => '#ffffff',
            'theme_mobile_map_marker_sl_color_1' => '#ef5248',
            'theme_mobile_map_marker_sl_color_2' => '#ffffff',
            'theme_mobile_map_salon_limit' => 20,
            'theme_mobile_map_search_radius_list' => [
                1000,
                1500,
                2000
            ],
        ]);

        /** @var \Illuminate\Database\Query\Builder $q */
        //$sql = $q->toSql();
        //\Log::info($sql);
        //return 1;
        $per_page = $request->get('per_page', 20);
        if($view_type == 'near_me'){
            $per_page = $settings['theme_mobile_map_salon_limit'];
        }
        $q = $q->paginate($per_page);
        /** @var LengthAwarePaginator $q */
        $ls = $q->items();
        $result = [];
        /** @var Salon $salon */
        foreach ($ls as $salon){
            $to_lat = $salon->map_lat;
            $to_lng = $salon->map_long;
            if(is_numeric($from_lat) && is_numeric($from_lng) && is_numeric($to_lat) && is_numeric($to_lng)){
                if(
                    $from_lat>0 && $from_lng>0 && $to_lat>0 && $to_lng>0
                ){
                    $from_lat = $from_lat * 1.0;
                    $from_lng = $from_lng * 1.0;
                    $to_lat = $to_lat * 1.0;
                    $to_lng = $to_lng * 1.0;
                    $distance = static::getDistance($from_lat, $from_lng, $to_lat, $to_lng);
                }
            }
            $services = [];
            $service_bases = $salon->services;
            $price_from = $salon->price_from_cache;
            $sale_of_up_to = $salon->sale_up_to_percent_cache;

            if($limit_service>0){
                $service_bases = $service_bases->take($limit_service);
            }
            /** @var SalonService $service */
            foreach ($service_bases as $service){
                $services[] = [
                    'id' => $service->id,
                    'salon_id' => $service->salon_id,
                    'name' => $service->name,
                    'sale_off_up_to' => $service->sale_percent_cache,
                    'base_price' => $service->price,
                    'final_price' => $service->final_price_cache,
                    'price_from' => $service->getOrgPriceFrom(),
                    'price_to' => $service->getOrgPriceTo(),
                    'final_price_from' => $service->getFinalPriceFrom(),
                    'final_price_to' => $service->getFinalPriceTo(),
                    'color' => '#f2f2f2',
                    'text_color' => '#21232c',
                    'options' => $service->options->map(function(SalonServiceOption $option) use($service){
                        return [
                            'id' => $option->id,
                            'name' => $option->name,
                            'org_price' => $option->price,
                            'final_price' => $service->getOptionFinalPrice($option->id, false)
                        ];
                    })
                ];
            }
            $like = false;
            if(auth()->guard('api')->user()) {
               $salonlike = SalonLike::where('user_id', auth()->guard('api')->user()->id)->get();
               foreach ($salonlike as $uk =>$value) {
                  if($salonlike[$uk]->salon_id == $salon->id){
                    $like = true;
                }
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
                'services' => $services,
                'location' => [
                    'latitude' => $salon->map_lat,
                    'longitude' => $salon->map_long,
                ]
            ];
        }
        $rs = [
            'is_last_page' => $q->lastPage() == $q->currentPage(),
            //'next_page' => $q->currentPage() + 1,
            'per_page' => $q->perPage(),
            'page' => $q->currentPage(),
            'total' => $q->total(),
            'items' => $result
        ];
        return $rs;
    }
}