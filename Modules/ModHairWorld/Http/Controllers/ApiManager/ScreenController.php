<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/27/18
 * Time: 21:22
 */

namespace Modules\ModHairWorld\Http\Controllers\ApiManager;


use App\Http\Controllers\Controller;
use App\UploadedFile;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\ModHairWorld\Entities\Badge;
use Modules\ModHairWorld\Entities\PostTypes\MobileHelp;
use Modules\ModHairWorld\Entities\PostTypes\MobileHelpCategory;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonBankInfo;
use Modules\ModHairWorld\Entities\SalonBrand;
use Modules\ModHairWorld\Entities\SalonGallery;
use Modules\ModHairWorld\Entities\SalonOpenTime;
use Modules\ModHairWorld\Entities\SalonOrder;
use Modules\ModHairWorld\Entities\SalonOrderItem;
use Modules\ModHairWorld\Entities\SalonPaymentSupport;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\SalonServiceCategory;
use Modules\ModHairWorld\Entities\SalonServiceLogo;
use Modules\ModHairWorld\Entities\SalonServiceOption;
use Modules\ModHairWorld\Entities\SalonServiceReview;
use Modules\ModHairWorld\Entities\SalonServiceReviewImage;
use Modules\ModHairWorld\Entities\SalonServiceSale;
use Modules\ModHairWorld\Entities\SalonShowcase;
use Modules\ModHairWorld\Entities\SalonShowcaseItem;
use Modules\ModHairWorld\Entities\SalonStylist;

class ScreenController extends Controller
{

    function reviews(Request $request, Salon $salon){
        $filter = $request->get('filter', 'all');
        $reviews_query = $salon->reviews()->withCount(['likes'])->with(['user', 'images'])
            ->orderBy('salon_service_reviews.created_at', 'desc');

        switch ($filter){
            case 'today':
                $reviews_query = $reviews_query->whereDate('salon_service_reviews.created_at', Carbon::today());
                break;
            case 'week':
                $reviews_query = $reviews_query->whereBetween('salon_service_reviews.created_at', [Carbon::now()->startOfWeek(),Carbon::now()->endOfWeek()]);
                break;
            case 'month':
                $reviews_query = $reviews_query->whereMonth('salon_service_reviews.created_at', Carbon::now());
                break;
            case 'year':
                $reviews_query = $reviews_query->whereYear('salon_service_reviews.created_at', Carbon::now());
                break;
        }
        $reviews = $reviews_query->paginate(10);

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
            'next' => $reviews->currentPage() + 1,
        ];
        return \Response::json($reviews);
    }

    function rating(Request $request, Salon $salon)
    {
        $accept_count = SalonOrder::where('salon_id', $salon->id)
            ->where('status', '>=', 2)
            ->count();
        $cancel_count = SalonOrder::where('salon_id', $salon->id)
            ->whereNested(function ($query){
                $query->where('status', '=', -1)->orWhere('status', '=', -2);
            })
            ->count();
        $badge = $salon->reviews()->whereNotNull('badge_id')->count();
        return \Response::json([
            'rating' => $salon->rating,
            'ratingCount' => $salon->rating_count,
            'accept' => round(($accept_count+$cancel_count)>0?($accept_count*100.0)/($accept_count+$cancel_count):0, 0),
            'cancel' => round(($accept_count+$cancel_count)>0?($cancel_count*100.0)/($accept_count+$cancel_count):0,0),
            'badge' => $badge,
        ]);
    }

    function ratingDetail(Request $request, Salon $salon){
        $ratings = [
            $salon->one_star_reviews()->count(),
            $salon->two_star_reviews()->count(),
            $salon->three_star_reviews()->count(),
            $salon->four_star_reviews()->count(),
            $salon->five_star_reviews()->count()
        ];
        $rating_count = array_sum($ratings);
        return \Response::json([
            'rating' => $salon->rating,
            'desc' => getSetting('theme_mobile_manager_rating_tab_rating_desc', ''),
            'stars' => [
                $rating_count>0?round($ratings[0] * 100.0/$rating_count, 0):0,
                $rating_count>0?round($ratings[1] * 100.0/$rating_count, 0):0,
                $rating_count>0?round($ratings[2] * 100.0/$rating_count, 0):0,
                $rating_count>0?round($ratings[3] * 100.0/$rating_count, 0):0,
                $rating_count>0?round($ratings[4] * 100.0/$rating_count, 0):0
            ]
        ]);
    }

    function accept(){
        return \Response::json([
            'desc' => getSetting('theme_mobile_manager_rating_tab_accept', ''),
        ]);
    }

    function cancel(){
        return \Response::json([
            'desc' => getSetting('theme_mobile_manager_rating_tab_cancel', ''),
        ]);
    }

    function badges(Request $request, Salon $salon){
        $badges = Badge::with(['image'])->get();
        $ids = [];
        foreach ($badges as $badge){
            $ids[$badge->id] = 0;
        }

        $bcount = [];
        $cc = \DB::table('salon_service_reviews')->groupBy('badge_id')->selectRaw('badge_id, count(badge_id) as total')
            ->join('salon_orders', 'salon_service_reviews.order_id', '=', 'salon_orders.id')
            ->where('salon_orders.salon_id', '=', $salon->id)
            ->get();
        foreach ($cc as $item){
            $bcount[$item->badge_id] = $item->total;
        }

        return \Response::json($badges->map(function (Badge $badge) use($bcount){
            return [
                'title' => $badge->title,
                'cover' => $badge->image?$badge->image->getThumbnailUrl('default', false): false,
                'number' => array_key_exists($badge->id, $bcount)?$bcount[$badge->id]:0,
            ];
        }));
    }

    function home(Request $request, Salon $salon)
    {
        $rs = [
            'incomeThisWeek' => IncomeController::getThisWeekIncome($salon),
            'incomeToday' => IncomeController::getTodayIncome($salon),
            'nextWaitingBooking' => BookingController::getNextWaitingBooking($salon),
            'nextNewBooking' => BookingController::getNewBooking($salon),
            'nextMayDoneBooking' => BookingController::getNextMayDoneBooking($salon),
            'homeNews' => HomeNewsController::getNews()
        ];

        return \Response::json($rs);
    }

    function faqs(Request $request){
        $cats = MobileHelpCategory::getPublicIndexQuery()->with(['posts'=>function($query){
            /** @var Builder $query */
            $query->orderBy('created_at','asc');
        }]);
        $cats->getQuery()->orders = null;
        $cats->orderBy('created_at', 'asc');
        $cats = $cats->get()->map(function (MobileHelpCategory $category){
            return [
                'title' => $category->title,
                'data' => $category->posts->map(function (MobileHelp $help){
                    return [
                        'title' => $help->title,
                        'content' => $help->content
                    ];
                })
            ];
        });
        return \Response::json($cats);
    }

    function salonStylists(Request $request, Salon $salon){
        $salon->load(['stylist'=>function($query){
            /** @var Builder $query */
            $query->orderBy('id', 'asc');
        }, 'stylist.avatar']);
        $rs = $salon->stylist->map(function (SalonStylist $stylist){
            return [
                'id' => $stylist->id,
                'image' => $stylist->avatar?$stylist->avatar->getThumbnailUrl('default', getNoAvatarUrl()):getNoAvatarUrl(),
                'name' => $stylist->name
            ];
        });
        return \Response::json($rs);
    }

    function createStylist(Request $request, Salon $salon){
        \Validator::validate($request->all(), [
            'name' => ['required'],
            'image' => ['required', 'image']
        ], [

        ]);

        $name = $request->get('name');
        $image = $request->file('image');
        $upload_image = null;
        if($image){
            $upload_image = UploadedFile::upload($image,null,'stylist_avatar');
        }
        $stylist = new SalonStylist();
        $stylist->name = $name;
        $stylist->salon_id = $salon->id;
        if($upload_image){
            $stylist->avatar_id = $upload_image->id;
        }
        $stylist->save();
        return \Response::json([
            'name' => $stylist->name,
            'id' => $stylist->id,
            'image' => $stylist->avatar?$stylist->avatar->getThumbnailUrl('default', getNoAvatarUrl()):getNoAvatarUrl()
        ]);
    }

    function removeStylist(Request $request, SalonStylist $stylist, Salon $salon){
        $stylist->delete();
        return \Response::json(true);
    }

    function salonBrands(Request $request, Salon $salon){
        $rs = $salon->load(['brands'=>function($query){
            /** @var Builder $query */
            $query->orderBy('id', 'asc');
        }, 'brands.logo']);
        $rs = $rs->brands->map(function (SalonBrand $brand){
            return [
                'id' => $brand->id,
                'logo' => $brand->logo?$brand->logo->getThumbnailUrl('medium', getNoThumbnailUrl()):getNoThumbnailUrl()
            ];
        });
        return \Response::json($rs);
    }

    function createBrands(Request $request, Salon $salon){
        \Validator::validate($request->all(), [
            'logo' => ['required'],
            'logo.*' => ['image'],
        ], [

        ]);
        $rs = [];
        $images = $request->file('logo', []);
        foreach ($images as $image){
            $upload = UploadedFile::upload($image,null,'brand_logo');
            if($upload){
                $brand = new SalonBrand();
                $brand->salon_id = $salon->id;
                $brand->logo_id = $upload->id;
                $brand->save();
                $rs[] = [
                    'id' => $brand->id,
                    'logo' => $upload->getThumbnailUrl('default', getNoThumbnailUrl())
                ];
            }
        }
        return \Response::json($rs);
    }

    function removeBrand(Request $request, SalonBrand $brand, Salon $salon){
        $brand->delete();
        return \Response::json(true);
    }

    function salonShowcases(Request $request, Salon $salon){
        $rs = $salon->showcases()
            ->orderBy('id', 'asc')
            ->with(['items'=>function($query){
                /** @var Builder $query */
                $query->orderBy('id', 'asc');
            }, 'items.image'])->get()->map(function (SalonShowcase $showcase){
                return [
                    'id' => $showcase->id,
                    'name' => $showcase->name,
                    'image' => $showcase->cover()?($showcase->cover()->image?$showcase->cover()->image->getThumbnailUrl('medium', getNoThumbnailUrl()):getNoThumbnailUrl()):getNoThumbnailUrl(),
                    'items' => $showcase->items->map(function (SalonShowcaseItem $item){
                        return [
                            'id' => $item->id,
                            'image' => $item->image?$item->image->getThumbnailUrl('medium', getNoThumbnailUrl()):getNoThumbnailUrl()
                        ];
                    })
                ];
            });

        return \Response::json($rs);
    }

    function salonShowcasesCreate(Request $request, Salon $salon){
        \Validator::validate($request->all(), [
            'name' => ['required'],
            'image' => ['required', 'array'],
            'image.*' => ['image']
        ], [
            'name.required' => 'Vui lòng nhập tên album',
            'image.required' => 'Vui lòng thêm ít nhất 1 ảnh cho album',
            'image.*.image' => 'Dữ liệu ảnh không hợp lệ'
        ]);
        $album = new SalonShowcase();
        $album->name = $request->get('name');
        $album->salon_id = $salon->id;
        $album->save();
        foreach ($request->file('image') as $image){
            $upload = UploadedFile::upload($image,null,'showcase_item');
            if($upload){
                $item = new SalonShowcaseItem();
                $item->showcase_id = $album->id;
                $item->image_id = $upload->id;
                $item->save();
            }
        }

        return \Response::json(true);
    }

    function salonShowcasesUpdate(Request $request, SalonShowcase $showcase, Salon $salon){
        \Validator::validate($request->all(), [
            'name' => ['required'],
            'image' => ['nullable', 'array'],
            'old_item' => ['nullable', 'array'],
            'image.*' => ['nullable', 'image']
        ], [
            'name.required' => 'Vui lòng nhập tên album',
            'image.*.image' => 'Dữ liệu ảnh không hợp lệ'
        ]);
        $old_items = $request->get('old_item', []);
        $new_images = $request->file('image', []);
        $name = $request->get('name');
        if(!$old_items && !$new_images){
            abort(400, 'Vui lòng chọn ít nhất một ảnh cho album');
        }
        /** @var SalonShowcaseItem[] $deleted_items */
        $deleted_items = $showcase->items()->whereNotIn('salon_showcase_items.id',$old_items)->get();
        if($deleted_items){
            foreach ($deleted_items as $item){
                $item->delete();
            }
        }
        $showcase->name = $name;
        $showcase->save();
        foreach ($new_images as $image){
            $upload = UploadedFile::upload($image,null,'showcase_item');
            if($upload){
                $item = new SalonShowcaseItem();
                $item->showcase_id = $showcase->id;
                $item->image_id = $upload->id;
                $item->save();
            }
        }
        return \Response::json(true);
    }

    function removeShowcase(Request $request, SalonShowcase $showcase, Salon $salon){
        $showcase->delete();
        return \Response::json(true);
    }

    function locationMap(Request $request, Salon $salon){
        return [
            'latitude' => $salon->map_lat,
            'longitude' => $salon->map_long
        ];
    }

    function locationMapUpdate(Request $request, Salon $salon){
        \Validator::validate($request->all(), [
            'latitude' => ['required'],
            'longitude' => ['required'],
        ], [

        ]);
        $map_lat = $request->get('latitude');
        $map_long = $request->get('longitude');
        $salon->map_lat = $map_lat;
        $salon->map_long = $map_long;
        $salon->map_zoom = 15;
        $salon->save();
        return \Response::json(true);
    }

    function workTimes(Request $request, Salon $salon){
        return \Response::json($salon->times->map(function (SalonOpenTime $openTime){
            return [
                'weekday' => $openTime->weekday,
                'start' => Carbon::createFromTimeString($openTime->start)->format('H:i'),
                'end' => Carbon::createFromTimeString($openTime->end)->format('H:i'),
            ];
        }));
    }

    function workTimesUpdate(Request $request, Salon $salon){
        \Validator::validate($request->all(), [
            'data' => ['required', 'array'],
            'data.*.start' => ['date_format:H:i'],
            'data.*.end' => ['date_format:H:i'],
        ], [
			'data.required' => 'Ngày giờ không hợp lệ'
		]);
        $times = $request->get('data', []);
        $salon->times()->delete();
        foreach ($times as $time){
            $new = new SalonOpenTime();
            $new->salon_id = $salon->id;
            $new->weekday = $time['weekday'];
            $new->start = $time['start'];
            $new->end = $time['end'];
            $new->save();
        }
        return \Response::json($request->all());
    }

    function serviceCats(Request $request, Salon $salon){
        $cat = SalonServiceCategory::withCount([
            'services' => function($query) use ($salon){
                /** @var Builder $query */
                $query->where('salon_id', $salon->id);
            }
        ])
            ->with(['cover'])
            ->get();
        $rs = [];
        $ls = $cat->sortByDesc(function (SalonServiceCategory $category){
            return $category->services_count;
        })->map(function (SalonServiceCategory $category){
            return [
                'id' => $category->id,
                'name' => $category->title,
                'count' => $category->services_count,
                'image' => $category->cover?$category->cover->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl()
            ];
        });
        foreach ($ls as $l){
            $rs[] = $l;
        }
        return \Response::json($rs);
    }

    function serviceCatServices(Request $request, SalonServiceCategory $cat, Salon $salon){
        $cat->load(['services' => function($query) use($salon){
            /** @var Builder $query */
            $query->where('salon_id', $salon->id)
                ->orderBy('id', 'desc');
        }, 'services.cover', 'services.options']);
        $services = $cat->services->map(function (SalonService $service){
            return [
                'id' => $service->id,
                'name' => $service->name,
                'price' => $service->price,
                'option_count' => $service->options->count(),
                'price_from' => $service->getFinalPriceFrom(),
                'price_to' => $service->getFinalPriceTo(),
                'is_ranged' => $service->options->count()>0,
                'image' => $service->cover?$service->cover->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl()
            ];
        });
        return \Response::json($services);
    }

    function service(Request $request, SalonService $service, Salon $salon){
        if($service->salon_id !== $salon->id){
            abort(400,'Yêu cầu không hợp lệ');
        }
        $service->load(['cover', 'logos']);
        $logos = [];
        foreach ($service->logos as $logo){
            if($logo->image){
                $url = $logo->image->getThumbnailUrl('small_ka', false);
                if($url){
                    $logos[] = [
                        'id' => $logo->id,
                        'url' => $url
                    ];
                }
            }
        }
        return \Response::json([
            'id' => $service->id,
            'name' => $service->name,
            'price' => $service->price,
            'time_from' => $service->time_from,
            'time_to' => $service->time_to,
            'description' => $service->description,
            'cat_id' => $service->category_id,
            'color' => $service->color,
            'text_color' => $service->text_color,
            'image' => $service->cover?$service->cover->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl(),
            'logos' => $logos
        ]);
    }

    function serviceOptions(Request $request, SalonService $service, Salon $salon){
        $service->load(['options']);
        return \Response::json($service->options);
    }

    function sales(Request $request, Salon $salon){
        $sales = $salon->load(['saleServices'=>function($query){
            /** @var Builder $query */
            $query->orderBy('id', 'desc');
        }, 'saleServices.service', 'saleServices.service.cover', 'saleServices.service.options']);
        $sales = $sales->saleServices;
        return \Response::json($sales->map(function (SalonServiceSale $sale){
            $service = $sale->service;
            return [
                'sale_id' => $sale->id,
                'id' => $sale->service->id,
                'name' => $sale->service->name,
                'price' => $sale->service->price,
                'sale_amount' => $sale->sale_amount,
                'sale_percent' => $sale->sale_percent,
                'sale_type' => $sale->sale_type,
                'new_price' => $service->getFinalPriceFrom(),
                'price_from' => $service->getOrgPriceFrom(),
                'new_price_from' => $service->getFinalPriceFrom(),
                'price_to' => $service->getOrgPriceTo(),
                'new_price_to' => $service->getFinalPriceTo(),
                //'sale_percent' => round(100 - ($sale->service->price - $sale->sale_amount)*100.0/$sale->service->price),
                'image' => $sale->service->cover?$sale->service->cover->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl()
            ];
        }));
    }

    function notSales(Request $request, Salon $salon){
        $sales = $salon->saleServices->map(function (SalonServiceSale $sale){
            return $sale->service_id;
        });
        $salon->load(['services'=>function($query) use($sales){
            /** @var Builder $query */
            if($sales){
                $query->whereNotIn('id',$sales);
            }
        }, 'services.options']); 
        $services = $salon->services->map(function (SalonService $service){
            return [
                'id' => $service->id,
                'name' => $service->name,
                'price' => $service->price,
                'price_from' => $service->getFinalPriceFrom(),
                'price_to' => $service->getFinalPriceTo(),
                'image' => $service->cover?$service->cover->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl()
            ];
        });
        return \Response::json($services);
    }

    function salesCreate(Request $request, SalonService $service, Salon $salon){
        if($service->salon_id !== $salon->id){
            abort(400,'Yêu cầu không hợp lệ');
        }
        \Validator::validate($request->all(), [
            'amount' => ['required', 'integer', 'min:1000', 'max:'.$service->price]
        ], [
            'amount.required' => 'Vui lòng nhập số tiền giảm',
            'amount.integer' => 'Số tiền giảm phải là số',
            'amount.min' => 'Số tiền giảm phải lớn hơn 1000đ',
            'amount.max' => 'Số tiền giảm không được lớn hơn giá gốc của dịch vụ'
        ]);
        if($service->sale_off){
            $sale = $service->sale_off;
        }
        else{
            $sale = new SalonServiceSale();
        }
        $sale->service_id = $service->id;
        $sale->sale_amount = $request->get('amount', 0);
        $sale->sale_percent = $request->get('percent', 0);
        $sale->sale_type = $request->get('type', 1);
        $sale->save();
        return \Response::json(true);
    }

    function salesRemove(Request $request, SalonServiceSale $sale, Salon $salon){
        if($sale->service->salon_id != $salon->id){
            abort(400, 'Yêu cầu không hợp lệ');
        }
        $sale->delete();
        return \Response::json(true);
    }

    function serviceRemove(Request $request, SalonService $service, Salon $salon){
        if($service->salon_id != $salon->id){
            abort(400, 'Yêu cầu không hợp lệ');
        }
        $service->delete();
        return \Response::json($service);
    }

    function optionRemove(Request $request, SalonService $service, SalonServiceOption $option, Salon $salon){
        if($service->salon_id != $salon->id){
            abort(400, 'Yêu cầu không hợp lệ');
        }
        if($service->id != $option->service_id){
            abort(400, 'Yêu cầu không hợp lệ');
        }
        $option->delete();
        return \Response::json(true);
    }

    function serviceUpdate(Request $request, SalonService $service, Salon $salon){
        if($service->salon_id != $salon->id){
            abort(400, 'Yêu cầu không hợp lệ');
        }
        \Validator::validate($request->all(), [
            'name' => ['required'],
            'price' => ['required', 'numeric'],
            'image' => ['nullable', 'image'],
            'time_from' => ['required', 'numeric'],
            'time_to' => ['required', 'numeric'],
            'color' => ['required'],
            'text_color' => ['required'],
        ], [
            'name.required' => 'Tên dịch vụ không được bỏ trống',
            'price.required' => 'Giá dịch vụ không được bỏ trống',
            'price.numeric' => 'Giá dịch vụ phải là số',
        ]);
        $service->name = $request->get('name');
        $service->price = $request->get('price');
        $service->time_from = $request->get('time_from');
        $service->time_to = $request->get('time_to');
        $service->color = $request->get('color');
        $service->text_color = $request->get('text_color');
        $image_field = $request->file('image', null);
        $service->description = $request->get('description', '');
        if($service->description == null){
            $service->description = '';
        }
        if($image_field){
            $image = UploadedFile::upload($image_field, null,'service_logo');
            if($image){
                $service->cover_id = $image->id;
            }
        }
        $old_logos = $request->get('old_logos', []);
        $delete_logos = SalonServiceLogo::whereServiceId($service->id)->whereNotIn('id', $old_logos)->get();
        foreach ($delete_logos as $logo){
            $logo->delete();
        }

        $logos = $request->file('logos', []);
        foreach ($logos as $logo){
            $uploaded = UploadedFile::upload($logo,null,'service_logo');
            if($uploaded){
                $new = new SalonServiceLogo();
                $new->service_id = $service->id;
                $new->logo_id = $uploaded->id;
                $new->save();
            }
        }

        $service->save();
        return \Response::json(true);
    }

    function serviceCreate(Request $request, Salon $salon){
        \Validator::validate($request->all(), [
            'name' => ['required'],
            'price' => ['required', 'numeric'],
            'image' => ['nullable', 'image'],
            'time_from' => ['required', 'numeric'],
            'time_to' => ['required', 'numeric'],
            'color' => ['required'],
            'text_color' => ['required'],
            'cat_id' => ['required', 'exists:service_categories,id']
        ], [
            'name.required' => 'Tên dịch vụ không được bỏ trống',
            'price.required' => 'Giá dịch vụ không được bỏ trống',
            'price.numeric' => 'Giá dịch vụ phải là số',
        ]);

        $service = new  SalonService();
        $service->name = $request->get('name');
        $service->category_id = $request->get('cat_id');
        $service->price = $request->get('price');
        $service->time_from = $request->get('time_from');
        $service->time_to = $request->get('time_to');
        $service->color = $request->get('color');
        $service->text_color = $request->get('text_color');
        $service->salon_id = $salon->id;
        $image_field = $request->file('image', null);
        $service->description = $request->get('description', '');
        if($service->description == null){
            $service->description = '';
        }
        if($image_field){
            $image = UploadedFile::upload($image_field, null,'service_cover');
            if($image){
                $service->cover_id = $image->id;
            }
        }
        $service->save();

        $logos = $request->file('logos', []);
        foreach ($logos as $logo){
            $uploaded = UploadedFile::upload($logo,null,'service_logo');
            if($uploaded){
                $new = new SalonServiceLogo();
                $new->service_id = $service->id;
                $new->logo_id = $uploaded->id;
                $new->save();
            }
        }
        return \Response::json(true);
    }

    function OptionUpdate(Request $request, SalonService $service, SalonServiceOption $option, Salon $salon){
        if($service->salon_id != $salon->id){
            abort(400, 'Yêu cầu không hợp lệ');
        }
        if($service->id != $option->service_id){
            abort(400, 'Yêu cầu không hợp lệ');
        }
        \Validator::validate($request->all(), [
            'name' => ['required'],
            'price' => ['required', 'numeric'],
        ], [
            'name.required' => 'Tên tuỳ chọn không được bỏ trống',
            'price.required' => 'Giá tuỳ chọn không được bỏ trống',
            'price.numeric' => 'Giá tuỳ chọn phải là số',
        ]);
        $name = $request->get('name');
        $price = $request->get('price');
        $option->name = $name;
        $option->price = $price;
        $option->save();
        return \Response::json(true);
    }

    function OptionInfo(Request $request, SalonService $service, SalonServiceOption $option, Salon $salon){
        if($service->salon_id != $salon->id){
            abort(400, 'Yêu cầu không hợp lệ');
        }
        if($service->id != $option->service_id){
            abort(400, 'Yêu cầu không hợp lệ');
        }
        return \Response::json([
            'name' => $option->name,
            'price' => $option->price
        ]);
    }

    function optionCreate(Request $request, SalonService $service, Salon $salon){
        if($service->salon_id != $salon->id){
            abort(400, 'Yêu cầu không hợp lệ');
        }
        \Validator::validate($request->all(), [
            'name' => ['required'],
            'price' => ['required', 'numeric'],
        ], [
            'name.required' => 'Tên tuỳ chọn không được bỏ trống',
            'price.required' => 'Giá tuỳ chọn không được bỏ trống',
            'price.numeric' => 'Giá tuỳ chọn phải là số',
        ]);
        $name = $request->get('name');
        $price = $request->get('price');
        $option = new SalonServiceOption();
        $option->name = $name;
        $option->price = $price;
        $option->service_id = $service->id;
        $option->save();
        return \Response::json(true);
    }

    function basicInfo(Request $request, Salon $salon){
        $salon->load([
            'location_lv1',
            'location_lv2',
            'location_lv3',
            'cover',
            'gallery' => function($query){
                /** @var Builder $query */
                $query->orderBy('id', 'asc');
            },
            'gallery.image'
        ]);
        return \Response::json([
            'name' => $salon->name,
            'address' => $salon->address,
            'address_lv1' => [
                'label' => $salon->location_lv1->name,
                'id' => $salon->location_lv1->id
            ],
            'address_lv2' => [
                'label' => $salon->location_lv2->name,
                'id' => $salon->location_lv2->id
            ],
            'address_lv3' => [
                'label' => $salon->location_lv3->name,
                'id' => $salon->location_lv3->id
            ],
            'description' => $salon->info,
            'image' => $salon->cover?$salon->cover->getThumbnailUrl('default',getNoThumbnailUrl()):getNoThumbnailUrl(),
            'gallery' => $salon->gallery->map(function (SalonGallery $gallery){
                return [
                    'id' => $gallery->id,
                    'image' => $gallery->image?$gallery->image->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl()
                ];
            })
        ]);
    }

    function basicInfoSave(Request $request, Salon $salon){
        \Validator::validate($request->all(), [
            'name' => ['required'],
            'address' => ['required'],
            'address_lv1' => ['required', 'exists:dia_phuong_tinh_thanh_pho,id'],
            'address_lv2' => ['required', 'exists:dia_phuong_quan_huyen,id'],
            'address_lv3' => ['required', 'exists:dia_phuong_xa_phuong_thi_tran,id'],
            'image' => ['nullable', 'image'],
            'old_gallery.*' => ['numeric'],
            'gallery.*' => ['image']
        ], []);
        $salon->name = $request->get('name');
        $salon->address = $request->get('address');
        $salon->tinh_thanh_pho_id = $request->get('address_lv1');
        $salon->quan_huyen_id = $request->get('address_lv2');
        $salon->phuong_xa_thi_tran_id = $request->get('address_lv3');
        $image = $request->file('image');
        if($image){
            $uploaded = UploadedFile::upload($image,null,'salon_cover');
            if($uploaded){
                $salon->cover_id = $uploaded->id;
            }
        }
        $old_galleries = $request->get('old_gallery', []);
        $delete_galleries = SalonGallery::whereSalonId($salon->id)->whereNotIn('id', $old_galleries)->get();
        foreach ($delete_galleries as $gallery){
            $gallery->delete();
        }
        $galleries = $request->file('gallery', []);
        foreach ($galleries as $gallery){
            $uploaded = UploadedFile::upload($gallery,null,'gallery');
            if($uploaded){
                $new = new SalonGallery();
                $new->salon_id = $salon->id;
                $new->image_id = $uploaded->id;
                $new->save();
            }
        }
        $description = $request->get('description', '');
        if($description == null){
            $description = '';
        }
        $salon->info = $description;
        $salon->save();
        $rs = [
            'name' => $salon->name,
            'cover' => $salon->cover?$salon->cover->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl()
        ];
        return \Response::json($rs);
    }

    private function customerResultTranform(LengthAwarePaginator $data, $title){
        return [
            'title' => $title,
            'currentPage' => $data->currentPage(),
            'total' => $data->total(),
            'isLast' => $data->currentPage() == $data->lastPage(),
            'data' => array_map(function (SalonOrder $order){
                return [
                    'id' => $order->user_id,
                    'name' => $order->user->name,
                    'phone' => $order->user->phone,
                    'rating' => 0,
                    'avatar' => $order->user->avatar?$order->user->avatar->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl()
                ];
            }, $data->items())
        ];
    }

    function customerList(Request $request, Salon $salon){
        $normal = SalonOrder::has('user')
            ->whereSalonId($salon->id)
            ->selectRaw('id, salon_id, user_id, count(id) as total')
            ->with(['user', 'user.avatar'])
            ->where('status', 3)
            ->groupBy('user_id')
        ;

        $often = (clone $normal);

        $often->orderBy('total', 'desc')->havingRaw('COUNT(id) > 1')->limit(10);
        $often = $often->get();

        $often_ids = [];
        foreach ($often as $item){
            $often_ids[] = $item->user_id;
        }

        $normal = $normal
            ->whereNotIn('user_id', $often_ids)
            ->orderBy('id', 'desc')->paginate(10);

        $rs=  [
            [
                'title' => 'Khách hàng thường xuyên',
                'data' => $often->map(function (SalonOrder $order){
                    return [
                        'id' => $order->user_id,
                        'name' => $order->user->name,
                        'phone' => $order->user->phone,
                        'rating' => 0,
                        'avatar' => $order->user->avatar?$order->user->avatar->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl()
                    ];
                }),
            ],
            $this->customerResultTranform($normal, 'Khách hàng bình thường')
        ];

        return $rs;
    }

    function customerHistory(Request $request, User $user, Salon $salon){
        $list = SalonOrder::whereSalonId($salon->id)->where('user_id', $user->id)
        ->with(['items', 'user', 'user.avatar'])
            ->has('user')
            ->where('status', 3)
            ->orderBy('service_time', 'desc')->paginate(10)
        ;
        $rs = [
            'currentPage' => $list->currentPage(),
            'total' => $list->total(),
            'isLast' => $list->currentPage() == $list->lastPage(),
            'items' => array_map(function (SalonOrder $order){
                $services = [];
                $sum = 0;
                foreach ($order->items as $item){
                    $services[] = [
                        'id' => $item->id,
                        'name' => $item->service_name,
                        'qty' => $item->quatity,
                        'sum' => $item->quatity * $item->price
                    ];
                    $sum += $item->quatity * $item->price;
                }
                return [
                    'id' => $order->id,
                    'payment' => $order->getPaymentMethodText(),
                    'status' => $order->status,
                    'date' => $order->service_time->format('d/m/Y'),
                    'sum' => $sum,
                    'total' => count($services),
                    'time' => $order->service_time->format('H:i'),
                    'services' => $services,
                    'user' => [
                        'avatar' => $order->user->avatar?$order->user->avatar->getThumbnailUrl('default', getNoAvatarUrl()):getNoAvatarUrl(),
                        'id' => $order->user->id,
                        'name' => $order->user->name,
                        'phone' => $order->user->phone,
                        'rating' => 0,
                    ]
                ];
            },$list->items())
        ];
        return $rs;
    }

    function getTos(Request $request){
        $rs = getSetting('theme_mobile_manager_tos', '');
        return \Response::json($rs);
    }

    function getAppIntro(Request $request){
        $rs = getSetting('theme_mobile_manager_intro', '');
        return \Response::json($rs);
    }

    function bankInfo(Request $request, Salon $salon){
        $rs = [
            'name' => '',
            'account' => '',
            'bank_name' => ''
        ];
        $salon->load(['bank_info']);
        $info = $salon->bank_info;
        if($info){
            $rs = [
                'name' => $info->name,
                'account' => $info->account,
                'bank_name' => $info->bank_name
            ];
        }
        return \Response::json($rs);
    }

    function bankInfoSave(Request $request, Salon $salon){
        \Validator::validate($request->all(), [
            'name' => ['required'],
            'account' => ['required'],
            'bank_name' => ['required'],
        ], [
            'name.required' => 'Tên chủ tài khoản không được bỏ trống',
            'account.required' => 'Số tài khoản không được bỏ trống',
            'bank_name.required' => 'Tên ngân hàng/chi nhánh không được bỏ trống'
        ]);
        $name = $request->get('name');
        $account = $request->get('account');
        $bank_name = $request->get('bank_name');
        $salon->load(['bank_info']);
        $info = $salon->bank_info;
        if(!$info){
            $info = new SalonBankInfo();
        }
        $info->name = $name;
        $info->account = $account;
        $info->bank_name = $bank_name;
        $info->salon_id = $salon->id;
        $info->save();
        return \Response::json(true);
    }

    function paymentSupports(Request $request, Salon $salon){
        $rs = [
            'all' => [],
            'active' => []
        ];
        $all = SalonOrder::getPaymentMethods();
        $check = [];
        foreach ($all as $item){
            $rs['all'][] = [
                'id' => $item['id'],
                'title' => $item['title'],
            ];
            $check[] = $item['id'];
        }
        $salon->load(['payment_supports']);
        $ss = $salon->payment_supports;
        foreach ($ss as $s){
            if(in_array($s->payment_id, $check)){
                $rs['active'][] = $s->payment_id;
            }
        }

        return \Response::json($rs);
    }

    function paymentSupportsSave(Request $request, Salon $salon){
        $list_ = SalonOrder::getPaymentMethods();
        $list = [];
        foreach ($list_ as $item){
            $list[] = $item['id'];
        }
        \Validator::validate($request->all(), [
           'methods' => ['required', 'array', 'min:1'],
            'methods.*' => [
                Rule::in($list)
            ]
        ], [
            'methods.required' => 'Bạn phải chọn ít nhất 1 phương thức thanh toán',
            'methods.array' => 'Bạn phải chọn ít nhất 1 phương thức thanh toán',
            'methods.min' => 'Bạn phải chọn ít nhất 1 phương thức thanh toán',
            'methods.*.in' => 'Phương thức thanh toán không hợp lệ'
        ]);
        $salon->payment_supports()->delete();
        $methods = $request->get('methods', []);
        foreach ($methods as $method){
            $new = new SalonPaymentSupport();
            $new->salon_id = $salon->id;
            $new->payment_id = $method;
            $new->save();
        }
        return \Response::json($request->all());
    }
}