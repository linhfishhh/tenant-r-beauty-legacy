<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/1/18
 * Time: 14:07
 */

namespace Modules\ModHairWorld\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Modules\ModHairWorld\Entities\PromoSalon;

class PromoController extends Controller
{
    function salons(){
        $rs = PromoSalon::getPromoSalons();
        return \Response::json($rs);
    }

    function salonsHtml(){
        \Debugbar::disable();
        $salon = PromoSalon::getPromoSalons();
        return \Response::view(getThemeViewName('includes.promo_salons'), [
            'salons' => $salon
        ]);
    }
}