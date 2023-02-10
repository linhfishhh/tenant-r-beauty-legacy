<?php

namespace Modules\ModHairWorld\Http\Controllers\ApiManager;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\Salon;

/**
 * @resource Salon
 *
 * Những request này yêu cầu token ở header
 */
class SalonController extends Controller
{
    /**
     * Lấy thông tin ngắn gọn*
     *
     * Thông tin ngắn gọn của salon: ảnh đại diện, tên salon
     *
     * @response {
     *  "name": "Salon Tấn Can",
     *  "avatar": "http://mysite.com/xyz.jpg"
     * }
     */
    function shortInfo(Request $request, Salon $salon){
        return \Response::json(static::getShortInfo($salon));
    }

    function open(Request $request, Salon $salon){
        $open = $request->get('open', null);
        if($open == 1 || $open == 0){
            $salon->open = $open?1:0;
            $salon->save();
        }
        return \Response::json($open);
    }

    /**
     * @param Salon $salon
     * @return array
     */
    static function getShortInfo($salon){
        $cover = $salon->cover;
        $lv1 = $salon->location_lv1;
        $avatar_url = $cover?$cover->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl();
        return [
            'salon_id' => $salon->id,
            'user_id' => me()->id,
            'open' => boolval($salon->open),
            'name'      => $salon->name,
            'avatar'    => $avatar_url,
            'location_id' => $lv1?$lv1->id:0
        ];
    }
}