<?php

namespace Modules\ModHairWorld\Http\Controllers\frontend;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\ModHairWorld\Entities\DiaPhuongQuanHuyen;
use Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho;
use Modules\ModHairWorld\Entities\DiaPhuongXaPhuongThiTran;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\SalonServiceCategory;
use Modules\ModHairWorld\Entities\SalonServiceOption;
use Modules\ModHairWorld\Entities\SalonServiceSale;

class SearchController extends Controller
{

    static function fullTextKeyword($keyword){
//        $rs = '';
//        $ls = explode(' ', trim($keyword));
//        foreach ($ls as $l){
//            $rs .= '+'.$l.' ';
//        }
//        return trim($rs);
        return '"'.trim($keyword).'"';
     }


     static function rawSearchV2(Request $request, $type = 1, $mobile = false){
         $keyword = mb_strtolower($request->get('keyword'));
         $search_type = (mb_strpos($keyword, 'salon')===0)?'salon':'service';
         $is_sale = $request->get('is_sale', false);
         $rating = $request->get('rating', 0);
         $price = $request->get('price', 0)*1000;
         $workday = $request->get('workday', '');
         $address_lat = $request->get('address_lat',null);
         $address_lng = $request->get('address_lng',null);
         $distance = $request->get('distance', null);
         $location = $request->get('location', []);
         $cat =$request->get('cat', []);
         $location_lv = $request->get('location_lv', null);
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
             if($location && $location_lv){
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
             if($price>0){
                 $q->where('salons.price_from_cache', '<=', $price);
                 $q->where('salons.price_to_cache', '>=', $price);
             }
             if($keyword) {
                 $keyword = trim(str_replace_first('salon', '', $keyword));
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
                 $q->WhereRaw("MATCH(wa_salon_services.name) AGAINST('$keyword')");
                 $selects[] = DB::raw("SUM(MATCH(wa_salon_services.name) AGAINST ('$keyword')) as relevance_service");
                 if($type == 1){
                     $q->orderBy('relevance_service', 'desc');
                 }
             }
             if($is_sale){
                 $q->where('salon_services.sale_cache', '>', 0);
             }
             if($price>0){
                 $q->where('salon_services.final_price_cache', '>=', $price);
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
             'services' => function($query) use($search_type, $keyword, $is_sale, $rating, $price, $cat){
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
                     'sale_percent_cache'
                 ];

                 if($is_sale){
                     $query->where('sale_cache', '>', 0);
                 }

                 if($price > 0){
                     $query->where('final_price_cache', '>=', $price);
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
         ];

         if($mobile){
             $withs['liked_by_me'] = function($query){
                 $query->select([
                     'id',
                     'salon_id',
                     'user_id'
                 ]);
             };
         }

         if($type == 1){
             $q->with($withs);
             $q->orderBy('salons.rating', 'desc');
             $q->orderBy('salons.rating_count', 'desc');
         }
         //$q = $q->paginate(10);
         //$ls = $q->items();
         return $q;
     }

    static function searchv2Output(Request $request, $q, $limit_service = 4, $mobile = false){
        /** @var \Illuminate\Database\Query\Builder $q */
         $q = $q->paginate(10);
         /** @var LengthAwarePaginator $q */
         $ls = $q->items();
         $result = [];
         /** @var Salon $salon */
         foreach ($ls as $salon){
             $services = [];
             $service_bases = $salon->services;
             $price_from = $salon->price_from_cache;
             $sale_up_to = $salon->sale_up_to_cache;
             $sale_of_up_to = $salon->sale_up_to_percent_cache;

             if($limit_service>0){
                 $service_bases = $service_bases->take($limit_service);
             }
             /** @var SalonService $service */
             foreach ($service_bases as $service){
                 $services[] = [
                     'service_id' => $service->id,
                     'salon_id' => $service->salon_id,
                     'service_name' => $service->name,
                     'service_time' => $service->timeText(),
                     'sale_off' => $service->sale_cache,
                     'sale_percent' => $service->sale_percent_cache,
                     'price_org' => $service->priceHtml(),
                     'price_final' => $service->finalPriceHtmlV2(),
                     'color' => $service->color,
                     'text_color' => $service->text_color
                 ];
             }
             $liked = false;
             if($mobile){
                 $liked = $salon->liked_by_me != null;
             }
             $result[] = [
                 'address' => $salon->address_cache,
                 'liked' => $liked,
                 'location_lat' => $salon->map_lat,
                 'location_lng' => $salon->map_long,
                 'location_zoom' => $salon->map_zoom,
                 'open' => $salon->open,
                 'price_from' => number_format($price_from/1000.0, 0,'.', ',').'K',
                 'rating'  => number_format($salon->rating, 1, '.', '.'),
                 'rating_count' => $salon->rating_count,
                 'rating_stars' => view(getThemeViewName('components.rating_stars'), ['score' => $salon->rating])->render(),
                 'sale_of_up_to' => $sale_of_up_to,
                 'sale_up_to' => $sale_up_to,
                 'salon_cover' => $salon->cover ? $salon->cover->getThumbnailUrl('large', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                 'salon_id' => $salon->id,
                 'salon_name' => $salon->name,
                 'salon_url' => $salon->url(),
                 'services' => $services,
                 'verified' => $salon->certified,
             ];
         }
         $rs = [
             'is_last_page' => $q->lastPage() == $q->currentPage(),
             'next_page' => $q->currentPage() + 1,
             'total' => $q->total(),
             'location_lv' => 1,//do
             'cats' => [],//do
             'locations' => [],//do
             'result' => $result
         ];
        return $rs;
     }

     function searchv2Cats(Request $request){
         $rs =  static::rawSearchV2($request, 2);
         $rs->join('service_categories','service_categories.id', '=', 'salon_services.category_id');
         $selects = [
             DB::raw('count(DISTINCT wa_salons.id) as salons_count'),
             'service_categories.id',
             'service_categories.title',
         ];
         $groups = ['service_categories.id'];
         $rs->limit(50)
             ->select($selects)
             ->orderBy('salons_count', 'desc')
         ;
         $nrs = $rs->getQuery();
         $nrs->groups = null;
         $nrs->groupBy($groups);
         return \Response::json($nrs->get());
     }

     function searchv2Locations(Request $request){
         $address_lat = $request->get('address_lat', null);
         $address_lng = $request->get('address_lng', null);
         $address_type = $request->get('address_type', '');
         if ($address_lat && $address_lng && ($address_type == 'administrative_area_level_1')) {
             $location_lv = 2;
         } else if ($address_lat && $address_lng && ($address_type == 'administrative_area_level_2')) {
             $location_lv = 3;
         } else {
             $location_lv = 1;
         }

         $rs =  static::rawSearchV2($request, 2);

         $selects = [
             DB::raw('count(DISTINCT wa_salons.id) as salons_count'),
         ];
         $groups = [];
         if ($location_lv == 1) {
             $rs->join('dia_phuong_tinh_thanh_pho','salons.tinh_thanh_pho_id', '=', 'dia_phuong_tinh_thanh_pho.id');
             $selects[] = 'dia_phuong_tinh_thanh_pho.id';
             $selects[] = 'dia_phuong_tinh_thanh_pho.name';
             $groups[] = 'salons.tinh_thanh_pho_id';
         } else if ($location_lv == 2) {
             $rs->join('dia_phuong_quan_huyen','salons.quan_huyen_id', '=', 'dia_phuong_quan_huyen.id');
             $selects[] = 'dia_phuong_quan_huyen.id';
             $selects[] = 'dia_phuong_quan_huyen.name';
             $groups[] = 'salons.quan_huyen_id';
         } else {
             $rs->join('dia_phuong_xa_phuong_thi_tran','salons.phuong_xa_thi_tran_id', '=', 'dia_phuong_xa_phuong_thi_tran.id');
             $selects[] = 'dia_phuong_xa_phuong_thi_tran.id';
             $selects[] = 'dia_phuong_xa_phuong_thi_tran.name';
             $groups[] = 'salons.phuong_xa_thi_tran_id';
         }



             $rs->limit(50)
             ->select($selects)
            ->orderBy('salons_count', 'desc')
            //->get(['tinh_thanh_pho_id'])
         ;
         $nrs = $rs->getQuery();
         $nrs->groups = null;
         $nrs->groupBy($groups);
         return \Response::json($nrs->get());
     }

     function searchv2(Request $request){
        return '';
         //$rs =  static::rawSearchV2($request);
         //return \Response::json($rs->paginate(10)->items());
         if($request->ajax()){
             $rs =  static::rawSearchV2($request);
             $rs = static::searchv2Output($request, $rs);
             return \Response::json($rs);
         }
         return view(getThemeViewName('searchv2'), [
         ]);

        //dd(config('onesignal'));
        $ls = static::rawSearchV2($request);
        return \Response::json($ls);
     }

    function search(Request $request){

        if($request->ajax()){
            $rs =  static::rawSearch($request);

            return \Response::json($rs);
        }
        return view(getThemeViewName('search_old'), [
        ]);
    }
    /**
     * @param Builder $query
     * @param Request $request
     */
    static function builSalonQuery($query, Request $request, $type = 0){
        $is_sale = $request->get('is_sale', 0);
        $rating = $request->get('rating', -1);
        $price = $request->get('price', 0);
        $location = $request->get('location', []);
        $cat =$request->get('cat', []);
        $location_lv = $request->get('location_lv', null);
        $keyword = $request->get('keyword', '');
        $workday = $request->get('workday', '');
        $order_by = $request->get('order_by');
        if(!is_array($location)){
            $location = [
                $location
            ];
        }

        if(!is_array($cat)){
            $cat = [
                $cat
            ];
        }

        $query->with(['services'=>function($query) use($is_sale, $price, $cat, $keyword, $order_by){
            /** @var Builder $query */
            if(!$order_by){
                $query->orderByDesc('salon_services.rating');
            }

            if($is_sale){
                //$query->has('services.sale_off');
                $query->whereIn('id', function ($query){
                    /** @var Builder $query */
                    $query->select(['service_id'])->from('service_sales')->whereRaw('wa_service_sales.service_id = wa_salon_services.id');
                });

            }

            if($price>0){
                $query->where('price', '>=', $price*1000);
            }

            if($cat){
                $query->has('category', '>=',1,'and', function ($query) use($cat){
                    /** @var Builder $query */
                    $query->whereIn('id', $cat);
                });
            }

            if($keyword){
                $query->whereNested(function ($query) use ($keyword) {
                    /** @var Builder $query */
                    $query->where('name', 'like', "%{$keyword}%");
                });
            }

            switch ($order_by){
                case 'price':
                    $query->orderBy('salon_services.price', 'asc');
                    break;
                case 'sale':
                    $query->orderBy('salon_services.sale_percent_cache', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('salon_services.rating', 'desc');
                    $query->orderBy('salon_services.rating_count', 'desc');
                    break;
                case 'booking':
                    $query->orderBy('salon_services.booked_cache', 'desc');
                    break;
                default:
                    $query->orderByDesc('salon_services.rating_count')->orderByDesc('salon_services.rating');
                    break;
            }
        }]);

        //refine
        if(
            $price>0
            || $is_sale
            || $cat
            || $keyword
            || $workday
        ){
            $query->whereIn('id', function ($query) use($price, $is_sale, $cat, $keyword, $workday){
                /** @var Builder $query */
                $query->select(['salon_services.salon_id'])->from('salon_services');
                if($price>0){
                    $query->where('salon_services.price', '>=', $price*1000);
                }
                if($cat){
                    $query->whereIn('salon_services.category_id', $cat);
                }
                if($is_sale){
                    $query->whereIn('salon_services.id', function ($query){
                        /** @var Builder $query */
                        $query->select('service_id')->from('service_sales')->whereRaw('wa_salon_services.id=wa_service_sales.service_id');
                    });
                }
                if($keyword){
                    $query->whereNested(function ($query) use ($keyword){
                        /** @var Builder $query */
                        $query->where('salon_services.name', 'like', "%{$keyword}%")
                        ;
                        $query->orWhereRaw("wa_salons.name like '%{$keyword}%'");
                    });
                }
                $query->whereRaw('wa_salons.id = wa_salon_services.salon_id');
                if($workday){
                    $workday = Carbon::createFromFormat('d/m/Y', $workday);
                    $workday = $workday->dayOfWeek;
                    if($workday == 0){
                        $workday = 7;
                    }
                    $query->join('salon_open_times', 'salon_services.salon_id', '=', 'salon_open_times.salon_id')->where('salon_open_times.weekday',$workday);
                }
            });
        }


        if($rating>0){
            switch ($rating){
                case 1:
                    $query->where('salons.rating', '>=', 1);
                    //->where('salons.rating', '<', 2);
                    break;
                case 2:
                    $query->where('salons.rating', '>=', 2);
                    //->where('salons.rating', '<', 3);
                    break;
                case 3:
                    $query->where('salons.rating', '>=', 3);
                    //->where('salons.rating', '<', 4);
                    break;
                case 4:
                    $query->where('salons.rating', '>=', 4);
                    //->where('salons.rating', '<', 5);
                    break;
                case 5:
                    $query->where('salons.rating', '>=', 5);
                    break;
            }
        }

        if($type != 1){
            if($location && $location_lv){
                if($location_lv == 1){
                    $query->whereIn('tinh_thanh_pho_id', $location);
                }
                else if($location_lv == 2){
                    $query->whereIn('quan_huyen_id', $location);
                }
                else if($location_lv == 3){
                    $query->whereIn('phuong_xa_thi_tran_id', $location);
                }
            }


        }

        $query->where('certified', 1);
        $query->where('open', 1);

        $address_lat = $request->get('address_lat',null);
        $address_lng = $request->get('address_lng',null);
        //$address_type = $request->get('address_type', '');
        $distance = $request->get('distance', null);
        if($address_lat && $address_lng){
            $query->whereRaw(
                "6371 * 2 * ASIN(SQRT(
            POWER(SIN((map_lat - abs({$address_lat})) * pi()/180 / 2),
            2) + COS(map_lat * pi()/180 ) * COS(abs({$address_lat}) *
            pi()/180) * POWER(SIN((map_long - {$address_lng}) *
            pi()/180 / 2), 2) ))<={$distance}"
            );
        }
    }

    static function rawSearch(Request $request, $limit = 10, $service_limit = 4, $mobile = false): array
    {
        $rs = Salon::query();
        $load = [
            'cover',
            'services',
            'services.sale_off',
            'services.options',
            'saleServices',
            'location_lv1',
            'location_lv2',
            'location_lv3',
            'liked_by_me'
        ];
        $rs->with($load);
        static ::builSalonQuery($rs, $request);
        $keyword = $request->get('keyword');
        $order_by = $request->get('order_by');
        if(!$order_by){
//            $rs->orderByDesc('rating_count');
//            $rs->orderByDesc('rating');
            $rs->orderByRaw('RAND()');
        }
        else{
            switch ($order_by){
                case 'price':
                    $rs->orderBy('salons.price_from_cache', 'asc');
                    break;
                case 'sale':
                    $rs->orderBy('salons.sale_up_to_percent_cache', 'desc');
                    break;
                case 'rating':
                    $rs->orderBy('salons.rating', 'desc');
                    $rs->orderBy('salons.rating_count', 'desc');
                    break;
                case 'booking':
                    $rs->orderBy('salons.booked_cache', 'desc');
                    break;
            }
        }
        $rs->whereNested(function($q){
            $q->where('salons.price_from_cache', '>', 0)
                ->orWhere('salons.price_to_cache', '>', 0);
        });
        $rs = $rs->paginate($limit);
        $address_lat = $request->get('address_lat', null);
        $address_lng = $request->get('address_lng', null);
        $address_type = $request->get('address_type', '');
        if ($address_lat && $address_lng && ($address_type == 'administrative_area_level_1')) {
            $location_lv = 2;
        } else if ($address_lat && $address_lng && ($address_type == 'administrative_area_level_2')) {
            $location_lv = 3;
        } else {
            $location_lv = 1;
        }

        if ($location_lv == 1) {
            $location_model = DiaPhuongTinhThanhPho::query();
        } else if ($location_lv == 2) {
            $location_model = DiaPhuongQuanHuyen::query();
        } else {
            $location_model = DiaPhuongXaPhuongThiTran::query();
        }

        $locations = [];
        if(!$mobile){
              // $locations = $location_model->get();
//            $locations = $location_model->has('salons', '>=', 1, 'and',
//                function ($query) use ($request) {
//                    /** @var Builder $query */
//                    static::builSalonQuery($query, $request, 1);
//                })->limit(50)->get();
        }

        $cats = [];
        if(!$mobile){
              //$cats = SalonServiceCategory::orderBy('ordering', 'asc')->get();
//            $cats = SalonServiceCategory::select(['service_categories.id', 'service_categories.title',
//                \DB::raw("(select count(DISTINCT wa_salons.id) from wa_salons, wa_salon_services where wa_salons.id=wa_salon_services.salon_id and wa_salon_services.category_id=wa_service_categories.id and wa_salons.certified=1 and wa_salons.open=1) as 'salons_count'")
//            ])
//                ->orderByDesc('salons_count')->limit(50)
//                ->get();
        }
        $final_rs = [];

        $settings = getSettingsFromPage('promo_configs');
        $settings = collect($settings);

        /** @var Salon $item */
        foreach ($rs as $item) {
            $add = [
                'salon_id' => $item->id,
                'open' => $item->open,
                'verified' => $item->certified,
                'salon_name' => $item->name,
                'salon_url' => $item->url(),
                'liked' => $item->liked_by_me != null,
                'salon_cover' => $item->cover ? $item->cover->getThumbnailUrl('large', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                'address' => $item->address_cache,
                'rating' => number_format($item->rating, 1, '.', '.'),
                'rating_count' => $item->rating_count,
                'rating_stars' => view(getThemeViewName('components.rating_stars'), ['score' => $item->rating])->render(),
                'location_lat' => $item->map_lat,
                'location_lng' => $item->map_long,
                'location_zoom' => $item->map_zoom,
                'services' => [],
                'price_from' => ($item->price_from_cache/1000).'K',
                'sale_of_up_to' => $item->sale_up_to_percent_cache,
            ];
            /** @var SalonService[]|Collection $services */
            $services = $item->services->sort(function(SalonService $service_a, SalonService $service_b) use($keyword) {
                $a = 0;
                $b = 0;
                $am = similar_text(str_slug($service_a->name), str_slug($keyword), $a);
                $bm = similar_text(str_slug($service_b->name), str_slug($keyword),$b);

                if ($a == $b) {
                    return 0;
                }
                return ($a < $b) ? 1 : -1;
            });
            $salon_in_promo = $item->isInPromo();
            $promo_cat = -1;
            if($salon_in_promo){
                $promo_cat = $settings->get('promo_cats', -1) * 1;
                $services =  $services->sortByDesc(function(SalonService $service) use($promo_cat){
                    return $promo_cat == $service->category_id;
                });
            }
            if($service_limit){
                $services = $services->take($service_limit);
            }
            foreach ($services as $service) {
                $a = [
                    'service_id' => $service->id,
                    'salon_id' => $service->salon_id,
                    'service_name' => $service->name,
                    'service_time' => $service->timeText(),
                    'sale_off' => $service->sale_off != null,
                    'sale_percent' => $service->sale_percent_cache,
                    'price_org' => $service->priceHtml(),
                    'price_final' => $service->finalPriceHtml(),
                    'color' => $service->color,
                    'text_color' => $service->text_color,
                    'options' => $service->options->map(function(SalonServiceOption $option) use($service){
                        return [
                            'id' => $option->id,
                            'name' => $option->name,
                            'org_price' => $option->price,
                            'final_price' => $service->getOptionFinalPrice($option->id)
                        ];
                    })
                ];
                if($salon_in_promo){
                    if($service->category_id == $promo_cat){
                        $a['promo'] = [
                            'percent' => $settings->get('promo_percent'),
                            'limit' => $settings->get('promo_limit'),
                            'current' => $item->countPromoOrder(),
                            'price' => SalonService::formatPrice($service->getOrgPriceFrom() - ($service->getOrgPriceFrom()*$settings->get('promo_percent')/100))
                        ];
                    }
                }
                $add['services'][] = $a;
            }

            $big_sale = false;

            $sale_list = $item->saleServices;
            if($sale_list){
                $big_sale = $sale_list->max(function(SalonServiceSale $sale){
                    return $sale->sale_amount;
                });
            }
            $add['sale_up_to'] = $big_sale;
            $final_rs[] = $add;
        }
        return [
            'result' => $final_rs,
            'next_page' => $rs->currentPage() + 1,
            'is_last_page' => $rs->currentPage() == $rs->lastPage(),
            'locations' => $locations,
            'location_lv' => $location_lv,
            'cats' => $cats,
            'total' => $rs->total()
        ];
    }
}