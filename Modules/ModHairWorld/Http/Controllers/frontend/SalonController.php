<?php

namespace Modules\ModHairWorld\Http\Controllers\frontend;


use App\Http\Controllers\Controller;
use App\Http\Requests\Ajax;
use App\UploadedFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonLike;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\SalonServiceOption;
use Modules\ModHairWorld\Entities\SalonServiceReview;
use Modules\ModHairWorld\Entities\SalonServiceReviewImage;
use Modules\ModHairWorld\Entities\SalonServiceReviewLike;
use Modules\ModHairWorld\Entities\SalonServiceReviewRating;
use Modules\ModHairWorld\Entities\SalonShowcase;
use Modules\ModHairWorld\Entities\SalonShowcaseLike;

class SalonController extends Controller
{

    function getOptions(Ajax $request, SalonService $service)
    {
        $rs = [
            'service' => [],
            'options' => []
        ];
        // $salon = $service->salon;

        $options = $service->options;
        if ($options->count() == 0) {
            return \Response::json(false);
        }

        $sale = $service->sale_off;

        $settings = getSettingsFromPage('promo_configs');
        $settings = collect($settings);
        $promo_cat = $settings->get('promo_cats') * 1;
        $promo_percent = $settings->get('promo_percent') * 1;

        $salon = $service->salon;
        $in_promo = $salon->isInPromo();
        $is_promo = ($service->category_id == $promo_cat) && $in_promo;

        $rs['options'] = $options->map(function (SalonServiceOption $option) use ($sale, $is_promo, $promo_percent) {
            $org = $option->price;
            if (!$is_promo) {
                $final = !$sale ? $option->price : $sale->applySale($option->price);
            } else {
                $final = $option->price - ($option->price * $promo_percent / 100);
            }
            return [
                'id' => $option->id,
                'name' => $option->name,
                'price_org_html' => number_format($org / 1000.0, 0, '.', '.') . 'K',
                'price_final_html' => number_format($final / 1000.0, 0, '.', '.') . 'K',
                'price_org' => $org,
                'price_final' => $final
            ];
        });

        $rs['service'] = [
            'id' => $service->id,
            'name' => $service->name,

        ];

        return \Response::json($rs);
    }

    function addToCart(Ajax $request, SalonService $service)
    {
        $overwrite = $request->get('overwrite', false);
        $option_id = $request->get('option_id', false);
        if (!$request->session()->has('wa_cart_salon') && $request->session()->has('wa_cart_items')) {
            return \Response::json(0);
        }
        $cart_salon_id = $request->session()->get('wa_cart_salon');
        $cart_items = $request->session()->get('wa_cart_items');
        if ($cart_salon_id != $service->salon_id) {
            $request->session()->put('wa_cart_salon', $service->salon_id);
            $request->session()->put('wa_cart_items', []);
            $cart_salon_id = $service->salon_id;
            $cart_items = [];
        }

        if (!is_array($cart_items)) {
            $cart_items = [];
        }

        $cart_items = collect($cart_items);

        if ($cart_items->has($service->id)) {
            if (!$overwrite) {
                $added = 0;
                $cart_items->pull($service->id);
            } else {
                $cart_items->put($service->id, [
                    'amount' => 1,
                    'option_id' => $option_id
                ]);
                $added = 1;
            }
        } else {
            $cart_items->put($service->id, [
                'amount' => 1,
                'option_id' => $option_id
            ]);
            $added = 1;
        }

        $total = 0;

        $ids = $cart_items->keys()->all();

        if ($ids) {
            $salon = $service->salon;
            $in_promo = $salon->isInPromo();
            $settings = getSettingsFromPage('promo_configs');
            $settings = collect($settings);
            $promo_cat = $settings->get('promo_cats') * 1;
            $promo_percent = $settings->get('promo_percent') * 1;
            /** @var SalonService[] $services */
            $services = SalonService::whereIn('id', $ids)
                ->with(['sale_off', 'options'])
                ->get(['id', 'price', 'category_id']);
            foreach ($services as $service) {
                $is_promo = $in_promo && ($service->category_id == $promo_cat);
                $option_id = $cart_items->get($service->id)['option_id'];
                $options = $service->options;
                if ($options->count()) {
                    foreach ($options as $option) {
                        if ($option->id == $option_id) {
                            if ($service->sale_off || $is_promo) {
                                if ($is_promo) {
                                    $price = $option->price - ($option->price * $promo_percent / 100);
                                } else {
                                    $price = $service->sale_off->applySale($option->price);
                                }
                            } else {
                                $price = $option->price;
                            }
                            $total += $price * $cart_items->get($service->id)['amount'];
                            break;
                        }
                    }
                } else {
                    if ($is_promo) {
                        $price = $service->price - ($service->price * $promo_percent / 100);
                    } else {
                        $price = $service->getFinalPrice();
                    }
                    $total += $price * $cart_items->get($service->id)['amount'];
                }
            }
        }

        $request->session()->put('wa_cart_salon', $cart_salon_id);
        $request->session()->put('wa_cart_items', $cart_items->all());
        $request->session()->put('wa_cart_total', $total);

        return \Response::json([
            'count' => $cart_items->sum('amount'),
            'added' => $added,
            'total' => number_format($total / 1000.0, 0) . 'K',
            'debug' => $request->all()
        ]);
    }

    function serviceDetail(Ajax $request, SalonService $service)
    {
        $html = view(getThemeViewName('includes.service_detail_ajax'), [
            'service' => $service
        ])->render();
        $service->load(['salon' => function ($query) {
            /** @var Builder $query */
            $query->select(['id', 'name', 'open']);
        }]);
        return \Response::json([
            'html' => $html,
            'salon' => $service->salon,
        ]);
    }

    function likeReview(Ajax $request, SalonServiceReview $review)
    {
        $like = SalonServiceReviewLike::whereReviewId($review->id)->where('user_id', me()->id)->first();
        if ($like) {
            $like->delete();
            $rs = 0;
        } else {
            $like = new SalonServiceReviewLike();
            $like->review_id = $review->id;
            $like->user_id = me()->id;
            $like->save();
            $rs = 1;
        }
        return \Response::json([
            'liked' => $rs,
            'count' => $review->likes()->count()
        ]);
    }

    function getReviews(Ajax $request, Salon $salon)
    {
        $service_id = $request->get('service_id', -1);
        $rating = $request->get('rating', -1);
        $sort = $request->get('sort', 0);
        $rs = [];
        if ($service_id == -1) {
            switch ($rating) {
                case -1:
                    $rs = $salon->approved_reviews();
                    break;
                case 1:
                    $rs = $salon->one_star_reviews();
                    break;
                case 2:
                    $rs = $salon->two_star_reviews();
                    break;
                case 3:
                    $rs = $salon->three_star_reviews();
                    break;
                case 4:
                    $rs = $salon->four_star_reviews();
                    break;
                case 5:
                    $rs = $salon->five_star_reviews();
                    break;
            }
        } else {
            /** @var SalonService $service */
            $service = $salon->services()->where('salon_services.id', $service_id)->first();
            switch ($rating) {
                case -1:
                    $rs = $service->approved_reviews();
                    break;
                case 1:
                    $rs = $service->one_star_reviews();
                    break;
                case 2:
                    $rs = $service->two_star_reviews();
                    break;
                case 3:
                    $rs = $service->three_star_reviews();
                    break;
                case 4:
                    $rs = $service->four_star_reviews();
                    break;
                case 5:
                    $rs = $service->five_star_reviews();
                    break;
            }
        }
        $load = [
            'user',
            'user.avatar',
            'service',
            'service.sale_off',
            'images'
        ];
        if (me()) {
            $load[] = 'liked_by_me';
        }
        $rs->with($load);
        $rs->withCount([
            'likes'
        ]);
        switch ($sort) {
            case 0:
                $rs->orderByDesc('created_at');
                break;
            case 1:
                $rs->orderBy('created_at');
                break;
            case 2:
                $rs->orderByDesc('likes_count');
                break;
        }
        //        $rs->orderByDesc('likes_count');
        //        $rs->orderByDesc('created_at');
        $rs = $rs->paginate(10);
        $html = view(getThemeViewName('components.review_items_ajax'), [
            'items' => $rs,
        ])->render();
        return \Response::json([
            'current_page' => $rs->currentPage(),
            'last_page' => $rs->lastPage(),
            'html' => $html
        ]);
    }

    function getReviewFilterRatingListByServiceCat(Ajax $request, Salon $salon)
    {
        $cat_id = $request->get('id');
        if ($cat_id == -1) {
            $rs = [
                0 => $salon->approved_reviews()->count(),
                1 => $salon->one_star_reviews()->count(),
                2 => $salon->two_star_reviews()->count(),
                3 => $salon->three_star_reviews()->count(),
                4 => $salon->four_star_reviews()->count(),
                5 => $salon->five_star_reviews()->count(),
            ];
        } else {
            /** @var SalonService $service */
            $service = $salon->services()->where('salon_services.id', $cat_id)->first();
            $rs = [
                0 => $service->approved_reviews()->count(),
                1 => $service->one_star_reviews()->count(),
                2 => $service->two_star_reviews()->count(),
                3 => $service->three_star_reviews()->count(),
                4 => $service->four_star_reviews()->count(),
                5 => $service->five_star_reviews()->count(),
            ];
        }
        return \Response::json($rs);
    }

    function addCommentReview(Request $request)
    {
        if ($request) {
            $rules = [
                'rating' =>'required',
                'title' => 'required|max:100|min:3',
                'description' => 'required|max:1000|min:3',
            ];
            $customMessages = [
                'title.required' => 'Tiêu đề không được bỏ trống!',
                'rating.required' => 'Vui lòng chọn đánh giá!',
                'title.min' => 'Tiêu đề quá ngắn, ít nhất 3 kí tự!',
                'title.max' => 'Tiêu đề quá dài, tối đa 100 kí tự!',
                'description.required' => 'Mô tả không được bỏ trống!',
                'description.min' => 'Mô tả quá ngắn, ít nhất 3 kí tự!',
                'description.max' => 'Mô tả quá dài, tối đa 1000 kí tự!',
            ];
            $validatedData = $this->validate($request, $rules, $customMessages);
            if ($validatedData) {
                $order_id = 0;
                $service_id = $request->service_id;
                $user_id = me()->id;
                $title = $request->title;
                $content = $request->description;

                $review = new SalonServiceReview();
                $review->order_id = $order_id;
                $review->service_id = $service_id;
                $review->title = $title;
                $review->content = $content;
                $review->approved = 1;
                $review->user_id = $user_id;

                /** @var SalonService $service */
                $service = SalonService::has('salon')->where('id', $service_id)->with('salon')->first();
                if(!$service){
                    abort(400, 'Yêu cầu không hợp lệ');
                }
                $salon = $service->salon;

                $review->save();

                $new_crit = new SalonServiceReviewRating();
                $new_crit->review_id = $review->id;
                $new_crit->criteria_id = 1;
                $new_crit->rating = $request->rating;
                $new_crit->save();


                $review->cacheRating();
                $service->cacheRating(false);
                $salon->cacheRating(false);


                $c = 0;
                if ($request->images) {
                    foreach ($request->images as $key => $image) {
                        try{
                            $uploaded = UploadedFile::upload($image, me()->id, 'review');
                        }catch (\Exception $ex){
                            SalonServiceReview::find($review->delete());
                            SalonServiceReviewImage::where('review_id' , $review->id)->delete();
                            return \Response::json([
                                'status' => 'errorImage',
                                'message' => 'Lỗi khi tải ảnh lên!, dung lượng ảnh tối đa 2MB'], 422);
                        }
                        if ($c > 9) {
                            break;
                        }
                        if ($uploaded) {
                            $new_review_image = new SalonServiceReviewImage();
                            $new_review_image->review_id = $review->id;
                            $new_review_image->image_id = $uploaded->id;
                            $new_review_image->save();
                            $c++;
                        }
                    }
                }
                return \Response::json('Add comment success !');
            }
        }
    }

    function post(Request $request, Salon $salon)
    {
        if ($salon->auto_cache_rating) {
            $salon->cacheRating(true);
            $salon->auto_cache_rating = 0;
            $salon->save();
        }
        $salon->load([
            'location_lv1',
            'location_lv2',
            'location_lv3',
            'times',
            'services',
            'services.options',
            'services.images',
            'services.logos', 'services.logos.image',
            'stylist',
            'stylist.avatar',
            'brands',
            'promo',
            'brands.logo',
            'extended_info',
            'showcases',
            'showcases.items',
            'showcases.items.image',
            'service_categories',
        ]);
        $criterias = app('review_criterias');
        $criteria_ratings = collect(json_decode($salon->rating_criterias, true));

        if (!$request->session()->has('wa_cart_salon')) {
            $request->session()->put('wa_cart_salon', $salon->id);
        }

        if ($request->session()->get('wa_cart_salon') != $salon->id) {
            $request->session()->put('wa_cart_salon', $salon->id);
            $request->session()->put('wa_cart_items', []);
        }

        if (!$request->session()->has('wa_cart_items')) {
            $request->session()->put('wa_cart_items', []);
        }

        $related_salons = Salon::query()
            ->selectRaw("*, 6371 * 2 * ASIN(SQRT(
            POWER(SIN((map_lat - abs({$salon->map_lat})) * pi()/180 / 2),
            2) + COS(map_lat * pi()/180 ) * COS(abs({$salon->map_lat}) *
            pi()/180) * POWER(SIN((map_long - {$salon->map_long}) *
            pi()/180 / 2), 2) )) as distance")
            ->whereRaw("6371 * 2 * ASIN(SQRT(
            POWER(SIN((map_lat - abs({$salon->map_lat})) * pi()/180 / 2),
            2) + COS(map_lat * pi()/180 ) * COS(abs({$salon->map_lat}) *
            pi()/180) * POWER(SIN((map_long - {$salon->map_long}) *
            pi()/180 / 2), 2) )) <=50 and id != {$salon->id}");
        $related_salons = $related_salons
            ->where('id', '!=', $salon->id)
            ->with(['location_lv1', 'location_lv3', 'location_lv2', 'cover'])
            ->orderBy('distance')
            ->orderByDesc('rating')
            ->where('open', 1)
            ->where('certified', 1)
            ->limit(10)->get();
        $remain_salon = 10 - $related_salons->count();
        $related_salon_ids = [
            $salon->id
        ];
        foreach ($related_salons as $related_salon) {
            $related_salon_ids[] = $related_salon->id;
        }
        if ($remain_salon > 0) {
            $ids = [];
            foreach ($salon->services as $service) {
                $ids[] = $service->category_id;
            }

            $remain_salon_list = Salon::with(['location_lv1', 'location_lv3', 'location_lv2', 'cover'])
                ->select('salons.*')
                ->join('salon_services', 'salons.id', '=', 'salon_services.salon_id')
                ->whereIn('salon_services.category_id', $ids)
                ->whereNotIn('salons.id', $related_salon_ids)
                ->orderByDesc('salons.rating')
                ->limit($remain_salon)
                ->get();

            $related_salons = $related_salons->merge($remain_salon_list);
        }
        //dump($related_salons);

        $service_limit = getSetting('theme_salon_service_limit', 10);

        return view(getThemeViewName('salon.post'), [
            'salon' => $salon,
            'related_salons' => $related_salons,
            'criterias' => $criterias,
            'criteria_ratings' => $criteria_ratings,
            'service_limit' => $service_limit
        ]);
    }

    function like(Ajax $request, Salon $salon)
    {
        $like = SalonLike::whereSalonId($salon->id)->where('user_id', me()->id)->first();
        if ($like) {
            $like->delete();
            return \Response::json(0);
        } else {
            $like = new SalonLike();
            $like->salon_id = $salon->id;
            $like->user_id = me()->id;
            $like->save();
            return \Response::json(1);
        }
    }

    function showcaseLike(Ajax $request, Salon $salon, SalonShowcase $showcase)
    {
        if ($showcase->salon_id == $salon->id) {
            $like = SalonShowcaseLike::whereShowcaseId($showcase->id)->where('user_id', me()->id)->first();
            if ($like) {
                $like->delete();
                return \Response::json(0);
            } else {
                $like = new SalonShowcaseLike();
                $like->user_id = me()->id;
                $like->showcase_id = $showcase->id;
                $like->save();
                return \Response::json(1);
            }
        }
        return \Response::json(0);
    }
}
