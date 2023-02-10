<?php

namespace Modules\ModHairWorld\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonBrand;
use Modules\ModHairWorld\Entities\SalonGallery;
use Modules\ModHairWorld\Entities\SalonLike;
use Modules\ModHairWorld\Entities\SalonOrder;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\SalonServiceCategory;
use Modules\ModHairWorld\Entities\SalonServiceIncludedOption;
use Modules\ModHairWorld\Entities\SalonServiceOption;
use Modules\ModHairWorld\Entities\SalonServiceReview;
use Modules\ModHairWorld\Entities\SalonServiceReviewImage;
use Modules\ModHairWorld\Entities\SalonServiceSale;
use Modules\ModHairWorld\Entities\SalonShowcase;
use Modules\ModHairWorld\Entities\SalonShowcaseItem;
use Modules\ModHairWorld\Entities\SalonStylist;
use Modules\ModHairWorld\Entities\UserAddress;
use Modules\ModHairWorld\Http\Controllers\api\SearchController;

class SalonController extends Controller
{
    // keep for compatible with old version
    function like(Request $request, Salon $salon)
    {
        $rs = false;
        $liked = $salon->liked_by_me;
        if (!$liked) {
            $new_like = new SalonLike();
            $new_like->salon_id = $salon->id;
            $new_like->user_id = me()->id;
            $new_like->save();
            $rs = true;
        } else {
            $liked->delete();
        }
        return \Response::json($rs);
    }

    function likeV2(Request $request, Salon $salon)
    {
        $rs = false;
        $liked = $salon->liked_by_me;
        if (!$liked) {
            $new_like = new SalonLike();
            $new_like->salon_id = $salon->id;
            $new_like->user_id = me()->id;
            $new_like->save();
            $rs = true;
        } else {
            $liked->delete();
        }
        $likes = SalonLike::where('salon_id', '=', $salon->id)->count();
        return \Response::json([
            'liked' => $rs,
            'likes' => $likes,
        ]);
    }

    function info(Request $request, Salon $salon)
    {
        $user = \Auth::user();
//        $addresses = UserAddress::with(['lv1', 'lv2', 'lv3'])->where('user_id', $user->id)->get()->map(function (UserAddress $address) {
//            return [
//                'id' => $address->id,
//                'info_address' => $address->getAddressLine(),
//                'info_name' => $address->name,
//                'info_phone' => $address->phone,
//                'info_email' => $address->email,
//            ];
//        });
        return \Response::json([
            'name' => $salon->name,
            'address' => $salon->getAddressLine(),
            'paymentMethods' => $salon->getPaymentMethod(),
            'addresses' => []
        ]);
    }

    function reviews(Request $request, Salon $salon)
    {
        $reviews = $salon->reviews()->withCount(['likes'])->with(['liked_by_me', 'user', 'user.avatar', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $reviews = [
            'items' => array_map(function (SalonServiceReview $review) {
                $images = $review->images->filter(function (SalonServiceReviewImage $image) {
                    return $image->image != null;
                });
                return [
                    'id' => $review->id,
                    'name' => $review->user ? $review->user->name : '',
                    'date' => $review->created_at->format('H:i d/m/Y'),
                    'rating' => $review->rating,
                    'title' => $review->title,
                    'content' => $review->content,
                    'avatar' => ($review->user && $review->user->avatar) ? $review->user->avatar->getThumbnailUrl('default', getNoAvatarUrl()) : getNoAvatarUrl(),
                    'liked' => $review->liked_by_me != null,
                    'likes' => $review->likes_count,
                    'images' => $images->map(function (SalonServiceReviewImage $image) {
                        return [
                            'thumb' => $image->image ? $image->image->getThumbnailUrl('default', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                            'image' => $image->image ? $image->image->getUrl() : getNoThumbnailUrl()
                        ];
                    })
                ];
            }, $reviews->items()),
            'inPromotion' => $salon->isInPromo() || $salon->hasSaleServices(),
            'total' => $reviews->total(),
            'isLast' => $reviews->lastPage() === $reviews->currentPage(),
            'next' => $reviews->currentPage() + 1
        ];
        return \Response::json($reviews);
    }

    function reviewsMore(Request $request, Salon $salon)
    {
        $reviews = $salon->reviews()->withCount(['likes'])
            ->with(
                [
                    'liked_by_me', 'user', 'user.avatar', 'images'
                ]
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $likeCount = $salon->likes()->count(['id']);
        $orderCount = SalonOrder::where('salon_id',$salon->id)->count();

        $current_start_of_week = \Carbon\Carbon::now()->startOfWeek();
        $current_end_of_week = \Carbon\Carbon::now()->endOfWeek();

        $goodRatingThisWeek = $salon->reviews()->whereDate('salon_service_reviews.created_at','>=', $current_start_of_week)
            ->whereDate('salon_service_reviews.created_at', '<=', $current_end_of_week)->where('salon_service_reviews.rating', '>=', 4)->count();

        $reviews = [
            'likeCount' => $likeCount,
            'orderCount' => $orderCount,
            'rating' => $salon->rating,
            'goodRatingThisWeek' => $goodRatingThisWeek,
            'ratingCount' => $salon->rating_count,
            'ratingDetails' => [
                $salon->five_star_reviews()->count(),
                $salon->four_star_reviews()->count(),
                $salon->three_star_reviews()->count(),
                $salon->two_star_reviews()->count(),
                $salon->one_star_reviews()->count()
            ],
            'items' => array_map(function (SalonServiceReview $review) {
                $images = $review->images->filter(function (SalonServiceReviewImage $image) {
                    return $image->image != null;
                });
                return [
                    'id' => $review->id,
                    'name' => $review->user ? $review->user->name : '',
                    'date' => $review->created_at->format('H:i d/m/Y'),
                    'rating' => $review->rating,
                    'title' => $review->title,
                    'content' => $review->content,
                    'avatar' => ($review->user && $review->user->avatar) ? $review->user->avatar->getThumbnailUrl('default', getNoAvatarUrl()) : getNoAvatarUrl(),
                    'liked' => $review->liked_by_me != null,
                    'likes' => $review->likes_count,
                    'images' => $images->map(function (SalonServiceReviewImage $image) {
                        return [
                            'thumb' => $image->image ? $image->image->getThumbnailUrl('default', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                            'image' => $image->image ? $image->image->getUrl() : getNoThumbnailUrl()
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

    function getRelatedSalons(Request $request, Salon $from_salon) {
        $from_lat = $request->get('from_lat');
        $from_lng = $request->get('from_lng');
        $related = Salon::where('quan_huyen_id', '=', $from_salon->quan_huyen_id)
            ->where('certified', 1)
            ->where('open', 1)
            ->where('id', '!=', $from_salon->id)
            ->limit(10)
            ->inRandomOrder()
            ->get();

        $related_salons = [];
        /** @var Salon $salon */
        foreach ($related as $salon) {
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
                    $distance = SearchController::getDistance($from_lat, $from_lng, $to_lat, $to_lng);
                }
            }
            $price_from_number = $salon->services->min(function(SalonService $service){
                return $service->getFinalPrice();
            });
            $related_salons[] = [
                'id' => $salon->id,
                'name' => $salon->name,
                'address' => $salon->address_cache,
                'rating'  => $salon->rating,
                'rating_count' => $salon->rating_count,
                'cover' => $salon->cover ? $salon->cover->getThumbnailUrl('large', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                'open' => $salon->open,
                'verified' => $salon->certified,
                'price_from' => $price_from,
                'price_from_number' => $price_from_number?$price_from_number:0,
                'sale_off_up_to' => $sale_of_up_to,
                'distance' => $distance,
                'liked' => $like,
                'location' => [
                    'latitude' => $salon->map_lat,
                    'longitude' => $salon->map_long,
                ]
            ];
        }
        return $related_salons;
    }

    function detail(Request $request, Salon $salon)
    {
        $from_lat = $request->get('from_lat');
        $from_lng = $request->get('from_lng');
        $salon->load([
            'location_lv1',
            'location_lv2',
            'location_lv3',
            'gallery',
            'saleServices' => function ($query) {
                /** @var Builder $query */
                $query->orderBy('sale_amount', 'desc');
            },
            'saleServices.service',
            'saleServices.service.options',
            'saleServices.service.logos',
            'saleServices.service.logos.image',
            'saleServices.service.images',
            'saleServices.service.images.image',
            'saleServices.service.sale_off',
            'service_categories' => function ($query) use ($salon) {
                /** @var Builder $query */
                $query->withCount(['services' => function ($query) use ($salon) {
                    /** @var Builder $query */
                    $query->where('salon_id', $salon->id);
                }]);
            },
            'service_categories.services' => function ($query) use ($salon) {
                /** @var Builder $query */
                $query->where('salon_id', $salon->id);
            },
            'service_categories.services.sale_off',
//            'service_categories.services.logos',
            'service_categories.services.options',
//            'service_categories.services.logos.image',
            'stylist',
            'brands',
            'showcases',
            'showcases.liked_by_me',
            'showcases.items',
            'liked_by_me'
        ]);
        $reviews = $salon->reviews()->withCount(['likes'])->with(['liked_by_me', 'user', 'user.avatar', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        $reviews = [
            'items' => array_map(function (SalonServiceReview $review) {
                $images = $review->images->filter(function (SalonServiceReviewImage $image) {
                    return $image->image != null;
                });
                return [
                    'id' => $review->id,
                    'name' => $review->user ? $review->user->name : '',
                    'date' => $review->created_at->format('H:i d/m/Y'),
                    'rating' => $review->rating,
                    'title' => $review->title,
                    'content' => $review->content,
                    'avatar' => ($review->user && $review->user->avatar) ? $review->user->avatar->getThumbnailUrl('default', getNoAvatarUrl()) : getNoAvatarUrl(),
                    'liked' => $review->liked_by_me != null,
                    'likes' => $review->likes_count,
                    'images' => $images->map(function (SalonServiceReviewImage $image) {
                        return [
                            'thumb' => $image->image ? $image->image->getThumbnailUrl('default', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                            'image' => $image->image ? $image->image->getUrl() : getNoThumbnailUrl()
                        ];
                    })
                ];
            }, $reviews->items()),
            'total' => $reviews->total(),
            'isLast' => $reviews->lastPage() === $reviews->currentPage(),
            'next' => $reviews->currentPage() + 1
        ];

        $distance = '??';
        $to_lat = $salon->map_lat;
        $to_lng = $salon->map_long;
        if (is_numeric($from_lat) && is_numeric($from_lng) && is_numeric($to_lat) && is_numeric($to_lng)) {
            $from_lat = $from_lat * 1.0;
            $from_lng = $from_lng * 1.0;
            $to_lat = $to_lat * 1.0;
            $to_lng = $to_lng * 1.0;
            $distance = SearchController::getDistance($from_lat, $from_lng, $to_lat, $to_lng);
            $distance = number_format($distance / 1000.0, $distance > 9 ? 0 : 2, '.', ',');
        }
        $likes = SalonLike::where('salon_id', '=', $salon->id)->count();
        $related_salons = $this->getRelatedSalons($request, $salon);

        return \Response::json([
            'id' => $salon->id,
            'verified' => $salon->certified,
            'cover' => $salon->cover ? $salon->cover->getUrl() : getNoThumbnailUrl(),
            'open' => $salon->open,
            'liked' => $salon->liked_by_me != null,
            'likes' => $likes,
            'reviews' => $reviews,
            'link' => $salon->url(),
            'name' => $salon->name,
            'rating' => $salon->rating,
            'ratingCount' => $salon->rating_count,
            'address' => $salon->getAddressLine(),
            'distance' => $distance,
            'map_lat' => $salon->map_lat,
            'map_lng' => $salon->map_long,
            'inPromotion' => $salon->isInPromo() || $salon->hasSaleServices(),
            'slides' => $salon->gallery->map(function (SalonGallery $gallery) {
                return [
                    'thumb' => $gallery->image ? $gallery->image->getThumbnailUrl('large', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                    'link' => $gallery->image ? $gallery->image->getUrl() : getNoThumbnailUrl()
                ];
            }),
            'workDays' => $salon->timeWeekDays(),
            'workTimes' => $salon->timeWorkingHours(),
            'info' => $salon->info,
            'showcase' => $salon->showcases->map(function (SalonShowcase $showcase) {
                $thumb = getNoThumbnailUrl();
                if ($showcase->items) {
                    /** @var SalonShowcaseItem $f */
                    $f = $showcase->items->first();
                    if($f){
                        $thumb = $f->image ? $f->image->getThumbnailUrl('medium', getNoThumbnailUrl()) : getNoThumbnailUrl();
                        $thumb_sq = $f->image ? $f->image->getThumbnailUrl('medium_sq', getNoThumbnailUrl()) : getNoThumbnailUrl();
                    }
                    else{
                        $thumb = getNoThumbnailUrl();
                        $thumb_sq = getNoThumbnailUrl();
                    }
                }
                return [
                    'id' => $showcase->id,
                    'name' => $showcase->name,
                    'liked' => $showcase->liked_by_me != null,
                    'items' => $showcase->items->map(function (SalonShowcaseItem $item) {
                        return [
                            'thumb' => $item->image ? $item->image->getThumbnailUrl('medium', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                            'image' => $item->image ? $item->image->getUrl() : getNoThumbnailUrl(),
                            'thumb_sq' => $item->image ? $item->image->getThumbnailUrl('medium_sq', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                        ];
                    }),
                    'thumb' => $thumb,
                    'thumb_sq' => $thumb_sq
                ];
            }),
            'stylists' => $salon->stylist->map(function (SalonStylist $stylist) {
                return [
                    'id' => $stylist->id,
                    'name' => $stylist->name,
                    'image' => $stylist->avatar ? $stylist->avatar->getThumbnailUrl('default', getNoAvatarUrl()) : getNoAvatarUrl()
                ];
            }),
            'brands' => $salon->brands->map(function (SalonBrand $brand) {
                return [
                    'image' => $brand->logo ? $brand->logo->getThumbnailUrl('medium_ka', getNoThumbnailUrl()) : getNoThumbnailUrl()
                ];
            }),
            'saleOff' => $salon->saleServices->map(function (SalonServiceSale $sale) use ($salon){
                $images = [];
                $logos = $sale->service->logos;
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
                $service_image_list = [];
                $service_images = $sale->service->images;
                if($service_images){
                    foreach ($service_images as $service_image){
                        $file = $service_image->image;
                        if($file){
                            $service_image_list[] = [
                                'thumb' => $file->getThumbnailUrl('default', false),
                                'image' => $file->getUrl()
                            ];
                        }
                    }
                }
                $service = $sale->service;
                return [
                    'id' => $sale->service_id,
                    'name' => $sale->service->name,
                    'cover' => $service->cover ? $service->cover->getThumbnailUrl('default', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                    'price' => $sale->service->getFinalPriceFrom() / 1000 . 'K',
                    'oldPrice' => $sale->service->getOrgPriceFrom() / 1000 . 'K',
                    'priceNumber' => $sale->service->getFinalPriceFrom(),
                    'oldPriceNumber' => $sale->service->getOrgPriceFrom(),
                    'ranged' => $sale->service->ranged_price,
                    'sale_percent' => $sale->service->price?(100 - floor($sale->service->getFinalPrice()*100/$sale->service->price)):0,
                    'time' => $sale->service->timeText(),
                    'priceRaw' => $sale->service->getFinalPrice() / 1000,
                    'priceRawNumber' => $sale->service->getFinalPrice(),
                    'salonID' => $sale->service->salon_id,
                    'color' => $sale->service->color,
                    'text_color' => $sale->service->text_color,
                    'logos' => $images,
                    'images' => $service_image_list,
                    'options' => $service->options->map(function(SalonServiceOption $option) use($service){
                        return [
                            'id' => $option->id,
                            'name' => $option->name,
                            'org_price' => $option->price,
                            'final_price' => $service->getOptionFinalPrice($option->id, false)
                        ];
                    }),
                    'includedOptions' => []
                ];
            }),
            'cats' => $salon->service_categories->map(function (SalonServiceCategory $category) use ($salon) {
                $price = $category->services->min(function (SalonService $service) {
                    return $service->final_price_cache;
                });
                return [
                    'image' => $category->cover ? $category->cover->getThumbnailUrl('default', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                    'price' => ($price / 1000) . 'K',
                    'priceNumber' => $price,
                    'name' => $category->title,
                    'count' => $category->services_count,
                    'services' => $category->services->map(function (SalonService $service) use ($salon) {
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
                        $service_image_list = [];
                        $service_images = $service->images;
                        if($service_images){
                            foreach ($service_images as $service_image){
                                $file = $service_image->image;
                                if($file){
                                    $service_image_list[] = [
                                        'thumb' => $file->getThumbnailUrl('default', false),
                                        'image' => $file->getUrl()
                                    ];
                                }
                            }
                        }
                        return [
                            'id' => $service->id,
                            'name' => $service->name,
                            'cover' => $service->cover ? $service->cover->getThumbnailUrl('default', getNoThumbnailUrl()) : getNoThumbnailUrl(),
                            'price' => ($service->getFinalPriceFrom() / 1000) . 'K',
                            'oldPrice' => ($service->getOrgPriceFrom() / 1000) . 'K',
                            'priceNumber' => $service->getFinalPriceFrom(),
                            'oldPriceNumber' => $service->getOrgPriceFrom(),
                            'ranged' => $service->ranged_price,
                            'sale_percent' => $service->price?100 - floor($service->getFinalPrice()*100/$service->price):0,
                            'time' => $service->timeText(),
                            'priceRaw' => $service->getFinalPrice() / 1000,
                            'priceRawNumber' => $service->getFinalPrice(),
                            'salonID' => $service->salon_id,
                            'color' => $service->color,
                            'text_color' => $service->text_color,
                            'logos' => $images,
                            'images' => $service_image_list,
                            'options' => $service->options->map(function(SalonServiceOption $option) use($service){
                                return [
                                    'id' => $option->id,
                                    'name' => $option->name,
                                    'org_price' => $option->price,
                                    'final_price' => $service->getOptionFinalPrice($option->id, false)
                                ];
                            }),
                            'includedOptions' => $service->included_options->map(function(SalonServiceIncludedOption $option) use($service){
                                return [
                                    'id' => $option->id,
                                    'name' => $option->name,
                                    'org_price' => $option->price,
                                ];
                            }),
                        ];
                    })
                ];
            }),
            'related' => $related_salons
        ]);
    }

    function openTimeList(Request $request, Salon $salon){
        $ls = $salon->getOrderTimeList();
        return \Response::json($ls);
    }
}