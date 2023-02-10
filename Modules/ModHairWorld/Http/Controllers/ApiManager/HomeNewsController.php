<?php

namespace Modules\ModHairWorld\Http\Controllers\ApiManager;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\PostTypes\MobileNews;
use Modules\ModHairWorld\Entities\PostTypes\MobileNewsCategory;

class HomeNewsController extends Controller
{
    function newsDetail(Request $request, MobileNews $news){
        $news->load(['cover']);
        return \Response::json([
            'title' => $news->title,
            'cover' => $news->cover?$news->cover->getThumbnailUrl('large', false):false,
            'content' => $news->content
        ]);
    }

    static function getNews(){
        $cat_class = MobileNewsCategory::getShortClass();
        $rs = MobileNews::with([
            'cover',
            $cat_class
        ])
            ->limit(5)
            ->orderBy('created_at', 'desc')
            ->get([
                'id',
                'title',
                'description',
                'cover_id',
            ])->map(function (MobileNews $news) use ($cat_class){
            return [
                'id' => $news->id,
                'title' => $news->title,
                'desc' => $news->description,
                'cover' => $news->cover?$news->cover->getThumbnailUrl('medium', false):false,
                'cat' => [
                    'name' => $news->{$cat_class}?$news->{$cat_class}->first()->title:'Tin chưa phânn loại'
                ]
            ];
        });
        return $rs;
    }
}