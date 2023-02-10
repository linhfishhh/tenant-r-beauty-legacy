<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 8/18/18
 * Time: 16:33
 */

namespace Modules\ModHairWorld\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\UploadedFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\SalonOrder;
use Modules\ModHairWorld\Entities\SalonOrderItem;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\SalonServiceReview;
use Modules\ModHairWorld\Entities\SalonServiceReviewCriteria;
use Modules\ModHairWorld\Entities\SalonServiceReviewImage;
use Modules\ModHairWorld\Entities\SalonServiceReviewLike;
use Modules\ModHairWorld\Entities\SalonServiceReviewRating;

class ReviewController extends Controller
{
    function newReview(Request $request){
        $order_id = $request->get('order_id');
        $service_id = $request->get('service_id');
        $user_id = me()->id;
        $title = $request->get('title');
        $content = $request->get('content');
        $badge_id = $request->get('badge_id');
        $crits = $request->get('crits');
        $images = $request->file('images');
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

        if($badge_id){
            $review->badge_id = $badge_id;
        }
        $review->save();
        foreach ($crits as $crit){
            $new_crit = new SalonServiceReviewRating();
            $new_crit->review_id = $review->id;
            $new_crit->criteria_id = $crit['id'];
            $new_crit->rating = $crit['rating'];
            $new_crit->save();
        }
        $review->cacheRating();
        $service->cacheRating(false);
        $salon->cacheRating(false);
        $c = 0;
        if($images){
            foreach ($images as $image){
               if($c == 3){
                   break;
               }
                $uploaded = UploadedFile::upload($image, $user_id,'review');
               if($uploaded){
                   $new_review_image = new SalonServiceReviewImage();
                   $new_review_image->review_id = $review->id;
                   $new_review_image->image_id = $uploaded->id;
                   $new_review_image->save();
                   $c++;
               }
            }
        }
        return \Response::json($images);
    }

    function like(Request $request, SalonServiceReview $review){
        $rs = [
            'liked' => false,
            'count' => 0
        ];
        $liked = $review->liked_by_me;
        if(!$liked){
            $new_like = new SalonServiceReviewLike();
            $new_like->review_id = $review->id;
            $new_like->user_id = me()->id;
            $new_like->save();
            $rs['liked'] = true;
        }
        else{
            $liked->delete();
        }
        $rs['count'] = $review->likes()->count();
        return \Response::json($rs);
    }

    function ServiceToReview(Request $request){
        $user = me();
        $rs = [];
        $salon_id = $request->get('salon_id');
        $service_id = $request->get('service_id');
        $query = SalonOrder::whereUserId($user->id)->where('status', 3)
            ->where('salon_id', $salon_id)
            ->whereHas('items', function($query) use ($service_id){
                /** @var Builder $query */
                $query->whereHas('service');
            })
            ->with(['items', 'reviewedItems'])
        ;
        $orders = $query->get();
        if($orders){
            /** @var SalonOrder $order */
            foreach ($orders as $order){
                $reviewed_ids = $order->reviewedItems->map(function (SalonServiceReview $review){
                    return $review->service_id;
                })->all();
                /** @var SalonOrderItem $item */
                foreach ($order->items as $item){
                    if(in_array($item->service_id, $reviewed_ids)){
                        continue;
                    }
                    if($service_id){
                        if($item->service_id != $service_id){
                            continue;
                        }
                    }
                    if(!$order->service_time) {
                        continue;
                    }
                    $rs[] = [
                        'orderID' => $order->id,
                        'serviceID' => $item->service_id,
                        'serviceName' => $item->service_name,
                        'dateTime' => $order->service_time->format('H:i d/m/Y'),
                    ];
                }
            }
        }
        return \Response::json($rs);
    }
}