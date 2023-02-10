<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/26/18
 * Time: 15:47
 */

namespace Modules\ModHairWorld\Http\Controllers;


use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Modules\ModHairWorld\Entities\PostTypes\News;
use Modules\ModHairWorld\Entities\Salon;

class SiteMapController extends Controller
{
    function index(){
        \Debugbar::disable();

        $site_maps = collect();
        $site_maps->push([
            'type' => 'sitemap',
            'loc' => route('frontend.sitemap.basic'),
            'lastmod' => date('Y-m-d'),
        ]);

//        $list = \DB::table('news')->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DATE_FORMAT(max(updated_at), "%Y-%m-%d") as lastmod')
//            ->where('published', true)
//            ->groupBy('year', 'month')
//            ->orderBy('created_at', 'desc')
//            ->get();
//        foreach ($list as $item){
//            $site_maps->push([
//                'type' => 'sitemap',
//                'loc' => route('frontend.sitemap.news', ['year'=>$item->year, 'month'=>$item->month]),
//                'lastmod' => $item->lastmod,
//            ]);
//        }

        $list = \DB::table('salons')->selectRaw('tinh_thanh_pho_id, quan_huyen_id, DATE_FORMAT(max(updated_at), "%Y-%m-%d") as lastmod')
            ->whereNotNull('tinh_thanh_pho_id')
            ->whereNotNull('quan_huyen_id');
        if(app()->environment('production')){
            $list->where('certified', true);
        }
        $list->groupBy('tinh_thanh_pho_id', 'quan_huyen_id');
        $list->orderBy('tinh_thanh_pho_id', 'asc');
        $list->orderBy('quan_huyen_id', 'asc');
        $list = $list->get();
        foreach ($list as $item){
            $site_maps->push([
                'type' => 'sitemap',
                'loc' => route('frontend.sitemap.salons', ['lv1'=>$item->tinh_thanh_pho_id, 'lv2'=>$item->quan_huyen_id]),
                'lastmod' => $item->lastmod,
            ]);
        }


        return \Response::view(getThemeViewName('sitemap'), ['data' => $site_maps, 'index' => true ], 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    function newIndex(){
        \Debugbar::disable();

        $site_maps = collect();
//        $list = \DB::table('news')->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, DATE_FORMAT(max(updated_at), "%Y-%m-%d") as lastmod')
//            ->where('published', true)
//            ->groupBy('year', 'month')
//            ->orderBy('created_at', 'desc')
//            ->get();
//        foreach ($list as $item){
//            $site_maps->push([
//                'type' => 'sitemap',
//                'loc' => route('frontend.sitemap.news', ['year'=>$item->year, 'month'=>$item->month]),
//                'lastmod' => $item->lastmod,
//            ]);
//        }
        return \Response::view(getThemeViewName('sitemap'), ['data' => $site_maps, 'index' => true ], 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    function salonIndex(){
        \Debugbar::disable();

        $site_maps = collect();
        $list = \DB::table('salons')->selectRaw('tinh_thanh_pho_id, quan_huyen_id, DATE_FORMAT(max(updated_at), "%Y-%m-%d") as lastmod')
            ->whereNotNull('tinh_thanh_pho_id')
            ->whereNotNull('quan_huyen_id');
        if(app()->environment('production')){
            $list->where('certified', true);
        }
        $list->groupBy('tinh_thanh_pho_id', 'quan_huyen_id');
        $list->orderBy('tinh_thanh_pho_id', 'asc');
        $list->orderBy('quan_huyen_id', 'asc');
        $list = $list->get();
        foreach ($list as $item){
            $site_maps->push([
                'type' => 'sitemap',
                'loc' => route('frontend.sitemap.salons', ['lv1'=>$item->tinh_thanh_pho_id, 'lv2'=>$item->quan_huyen_id]),
                'lastmod' => $item->lastmod,
            ]);
        }
        return \Response::view(getThemeViewName('sitemap'), ['data' => $site_maps, 'index' => true ], 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    function news(\Request $request, $year, $month){
        \Debugbar::disable();

//        /** @var News[] $list */
//        $list = News::getPublicIndexQuery()->whereYear('created_at', $year)
//            ->whereMonth('created_at', $month)
//            ->orderBy('created_at', 'desc')
//            ->get(['id', 'title', 'slug', 'published', 'language', 'created_at', 'updated_at']);

        $urls = collect();
//        foreach ($list as $item){
//            $urls->push([
//                'type' => 'url',
//                'loc' => $item->getUrl(),
//                'lastmod' => $item->updated_at->format('Y-m-d'),
//                'changefreq' => 'daily',
//                'priority' => 0.7
//            ]);
//        }

        return \Response::view(getThemeViewName('sitemap'), ['data' => $urls, 'index' => false ], 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    function salons(\Request $request, $lv1, $lv2){
        \Debugbar::disable();
        $list = Salon::query();
        if(app()->environment('production')){
            $list->where('certified',true);
        }
        $list->where('tinh_thanh_pho_id', $lv1);
        $list->where('quan_huyen_id', $lv2);
        $list->orderBy('tinh_thanh_pho_id', 'asc');
        $list->orderBy('quan_huyen_id', 'asc');
        $list= $list->get(['id', 'name', 'tinh_thanh_pho_id', 'quan_huyen_id', 'certified', 'created_at', 'updated_at']);
        ;

        $urls = collect();
        /** @var Salon[] $list */
        foreach ($list as $item){
            $urls->push([
                'type' => 'url',
                'loc' => $item->url(),
                'lastmod' => $item->updated_at->format('Y-m-d'),
                'changefreq' => 'daily',
                'priority' => 0.8
            ]);
        }

        return \Response::view(getThemeViewName('sitemap'), ['data' => $urls, 'index' => false ], 200, [
            'Content-Type' => 'application/xml'
        ]);
    }

    function basic(){
        \Debugbar::disable();

        $urls = collect();
        $urls->push([
            'type' => 'url',
            'loc' => url(''),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'always',
            'priority' => '1.0'
        ]);

        $urls->push([
            'type' => 'url',
            'loc' => route('frontend.search'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'always',
            'priority' => '0.9'
        ]);

//        $urls->push([
//            'type' => 'url',
//            'loc' => News::getPublicIndexUrl(),
//            'lastmod' => date('Y-m-d'),
//            'changefreq' => 'always',
//            'priority' => '0.9'
//        ]);

        $urls->push([
            'type' => 'url',
            'loc' => route('frontend.contact'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'always',
            'priority' => '0.1'
        ]);

        $urls->push([
            'type' => 'url',
            'loc' => route('frontend.salon_register'),
            'lastmod' => date('Y-m-d'),
            'changefreq' => 'always',
            'priority' => '0.9'
        ]);
        return \Response::view(getThemeViewName('sitemap'), ['data' => $urls, 'index' => false ], 200, [
            'Content-Type' => 'application/xml'
        ]);    }
}