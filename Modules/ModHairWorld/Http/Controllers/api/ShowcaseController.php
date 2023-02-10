<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/1/18
 * Time: 14:07
 */

namespace Modules\ModHairWorld\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\SalonShowcase;
use Modules\ModHairWorld\Entities\SalonShowcaseLike;

class ShowcaseController extends Controller
{
    function like(Request $request, SalonShowcase $showcase){
        $rs = false;
        $liked = $showcase->liked_by_me;
        if(!$liked){
            $new_like = new SalonShowcaseLike();
            $new_like->showcase_id = $showcase->id;
            $new_like->user_id = me()->id;
            $new_like->save();
            $rs = true;
        }
        else{
            $liked->delete();
        }
        return \Response::json($rs);
    }
}