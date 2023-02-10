<?php

namespace App\Http\Controllers\Backend;

use App\RevSliderHandshake;
use App\Http\Controllers\Controller;
use App\RevSlider;
use Illuminate\Http\Request;
use Modules\ModHoiNghi\Entities\HoiNghi;
use Modules\ModTinTuc\Entities\News;

class SliderController extends Controller
{
    function test(){
        $h = RevSlider::whereAlias('slider-trang-chu')->first();
        return \Response::json(json_decode($h->getFrontEndData()));
    }

    function index(){
        $code = md5(\Auth::getSession()->getId().random_int(0, 999999999));
        $r = RevSliderHandshake::where('user_id', '=', me()->id)->first();
        if(!$r){
            $r = new RevSliderHandshake();
            $r->user_id = me()->id;
        }
        $r->code = $code;
        $r->save();
        return view('backend.pages.slider.index', ['code' => $code, 'id'=>me()->id]);
    }

    function handshake(Request $request){
        return json_encode(\Auth::getSession());
    }
}
