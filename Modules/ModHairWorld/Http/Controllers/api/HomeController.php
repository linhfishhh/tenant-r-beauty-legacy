<?php

namespace Modules\ModHairWorld\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\UploadedFile;
use function GuzzleHttp\Psr7\parse_query;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\PromoSalon;
use Modules\ModHairWorld\Entities\Salon;

class HomeController extends Controller
{
    function index(Request $request){
        $home_items = getSetting('theme_mobile_home_banners', []);
        $home_items_id = array_map(function($item){
            return $item['banner'];
        },array_values($home_items));
        /* @var UploadedFile[]  $home_item_rs */
        $home_item_rs = UploadedFile::whereIn('id', $home_items_id)->get();
        $libs = [];
        foreach ($home_item_rs as $home_item_r){
            $img = $home_item_r->getUrl();
            $libs[$home_item_r->id] = $img?$img:getNoThumbnailUrl();
        }
        $rs = [];
        foreach ($home_items as $home_item){
            $url = parse_url($home_item['query']);
            $query = false;
            if($url){
                parse_str($url['query'], $query_);
                if($query_){
                    foreach ($query_ as $name=>$item){
                        if($item){
                            $query[$name] = $item;
                        }
                    }
                }
            }
            $rs[] = [
                'image' => $libs[$home_item['banner']],
                'query' => $query
            ];
        }

        $promos = PromoSalon::getPromoSalons();
        $v2 = $request->get('v2', false);
        if($v2){
            return \Response::json([
                'banners' => $rs,
                'promo_salons' => $promos
            ]);
        }
        else{
            return \Response::json($rs);
        }
    }
}