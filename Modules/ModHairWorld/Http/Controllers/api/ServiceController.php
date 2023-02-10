<?php

namespace Modules\ModHairWorld\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\SalonServiceOption;
use Modules\ModHairWorld\Entities\SalonServiceReview;
use Modules\ModHairWorld\Entities\SalonServiceReviewImage;

class ServiceController extends Controller
{

    function reviews(Request $request, SalonService $service){
        $reviews = $service->reviews()->withCount(['likes'])
            ->with(['liked_by_me', 'user', 'user.avatar', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $reviews = [
            'items' => array_map(function(SalonServiceReview $review){
                $images = $review->images->filter(function (SalonServiceReviewImage $image){
                    return $image->image != null;
                });
                return [
                    'id' => $review->id,
                    'name' => $review->user ? $review->user->name : '',
                    'date' => $review->created_at->format('H:i d/m/Y'),
                    'rating' => $review->rating,
                    'title' => $review->title,
                    'content' => $review->content,
                    'avatar' => ($review->user && $review->user->avatar) ? $review->user->avatar->getThumbnailUrl('default', getNoAvatarUrl()):getNoAvatarUrl(),
                    'liked' => $review->liked_by_me != null,
                    'likes' => $review->likes_count,
                    'images' => $images->map(function (SalonServiceReviewImage $image){
                        return [
                            'thumb' => $image->image?$image->image->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl(),
                            'image' => $image->image?$image->image->getUrl():getNoThumbnailUrl()
                        ];
                    })
                ];
            }, $reviews->items()),
            'total' => $reviews->total(),
            'isLast' => $reviews->lastPage() === $reviews->currentPage(),
            'next' => $reviews->currentPage() + 1
        ];
        return \Response::json($reviews);
    }

    function service(Request $request, SalonService $service){
        $from_lat = $request->get('from_lat');
        $from_lng = $request->get('from_lng');
        $service->load([
            'category',
            'category.cover',
            'cover',
            'salon',
            'sale_off',
            'options',
            'salon.location_lv1',
            'salon.location_lv2',
            'salon.location_lv3',
            'logos',
            'logos.image'
        ]);
        $distance = '??';
        $to_lat = $service->salon->map_lat;
        $to_lng = $service->salon->map_long;
        if(is_numeric($from_lat) && is_numeric($from_lng) && is_numeric($to_lat) && is_numeric($to_lng)){
            $from_lat = $from_lat * 1.0;
            $from_lng = $from_lng * 1.0;
            $to_lat = $to_lat * 1.0;
            $to_lng = $to_lng * 1.0;
            $distance = SearchController::getDistance($from_lat, $from_lng, $to_lat, $to_lng);
            $distance = number_format($distance/1000.0, $distance>9?0:2,'.', ',');
        }
        $reviews = $service->reviews()->withCount(['likes'])->with(['liked_by_me', 'user', 'user.avatar','images'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $reviews = [
            'items' => array_map(function(SalonServiceReview $review){
                $images = $review->images->filter(function (SalonServiceReviewImage $image){
                    return $image->image != null;
                });
                return [
                    'id' => $review->id,
                    'name' => $review->user ? $review->user->name : '',
                    'date' => $review->created_at->format('H:i d/m/Y'),
                    'rating' => $review->rating,
                    'title' => $review->title,
                    'content' => $review->content,
                    'avatar' => ($review->user && $review->user->avatar) ? $review->user->avatar->getThumbnailUrl('default', getNoAvatarUrl()):getNoAvatarUrl(),
                    'liked' => $review->liked_by_me != null,
                    'likes' => $review->likes_count,
                    'images' => $images->map(function (SalonServiceReviewImage $image){
                        return [
                            'thumb' => $image->image?$image->image->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl(),
                            'image' => $image->image?$image->image->getUrl():getNoThumbnailUrl()
                        ];
                    })
                ];
            }, $reviews->items()),
            'total' => $reviews->total(),
            'isLast' => $reviews->lastPage() === $reviews->currentPage(),
            'next' => $reviews->currentPage() + 1
        ];
        $images = [];
        $logos = $service->logos;
        if($logos){
            foreach ($logos as $logo){
                $file = $logo->image;
                if($file){
                    $url = $file->getThumbnailUrl('small_ka', false);
                    if($url){
                        $images[] = $url;
                    }
                }
            }
        }
        $cover = getNoThumbnailUrl();
        if($service->category){
            if($service->category->cover){
                $cover = $service->category->cover->getThumbnailUrl('large', getNoThumbnailUrl());
            }
        }
        if($service->cover){
            $cover = $service->cover->getThumbnailUrl('large', getNoThumbnailUrl());
        }
        return \Response::json([
            'id' => $service->id,
            'name' => $service->name,
            'reviews' => $reviews,
            'salon' => [
                'id' => $service->salon_id,
                'name' => $service->salon->name,
                'open' => $service->salon->open,
                'distance' => $distance,
                'address' => $service->salon->getAddressLine(),
                'location' => [
                    'lat' => $service->salon->map_lat,
                    'lng' => $service->salon->map_long,
                    'zoom' => $service->salon->map_zoom
                ]
            ],
            'catName' => $service->category?$service->category->title:'Danh mục chưa phân loại',
            'cover' => $cover,
            'info' => $service->description,
            'rating' => $service->rating,
            'ratingCount' => $service->rating_count,
            'time' => $service->timeText(),
            'price' => $service->finalPriceHtml(),
            'oldPrice' => $service->priceHtml(),
            'priceRaw' => $service->getFinalPrice()/1000.0,
            'priceNumber' => $service->getFinalPriceFrom(),
            'oldPriceNumber' => $service->getOrgPriceFrom(),
            'priceRawNumber' => $service->getFinalPrice(),
            'logos' => $images,
            'sale_percent' => $service->price?100 - floor($service->getFinalPrice()*100/$service->price):0,
            'options' => $service->options->map(function(SalonServiceOption $option) use($service){
                return [
                    'id' => $option->id,
                    'name' => $option->name,
                    'org_price' => $option->price,
                    'final_price' => $service->getOptionFinalPrice($option->id, false)
                ];
            })
        ]);
    }
}