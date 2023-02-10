<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2019-02-19
 * Time: 10:17
 */

namespace Modules\ModHairWorld\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\Http\Requests\Ajax;
use Modules\ModHairWorld\Entities\SalonServiceCategory;

class ServiceCatController extends Controller
{
    function index(Ajax $request){
        $q = SalonServiceCategory::query()->orderBy('ordering', 'asc')->get(['id', 'ordering', 'title']);
        return \Response::json($q);
    }

    function update(Ajax $request){
        $orders = [];
        $list = $request->get('cat', []);
        foreach ($list as $order=>$id){
            $orders[$id] = $order;
        }
        if($orders){
            $cats = SalonServiceCategory::whereIn('id', $list)->get();
            foreach ($cats as $cat){
                if(array_key_exists($cat->id, $orders)){
                    $cat->ordering = $orders[$cat->id];
                    $cat->save();
                }
            }
        }
        return \Response::json($list);
    }
}