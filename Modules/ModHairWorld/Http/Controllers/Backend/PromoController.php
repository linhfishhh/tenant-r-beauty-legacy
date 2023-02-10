<?php

namespace Modules\ModHairWorld\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use App\Http\Requests\Ajax;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\PromoSalon;
use Modules\ModHairWorld\Entities\Salon;
use mysql_xdevapi\Exception;


class PromoController extends Controller
{

    function addSalon(Ajax $request, Salon $salon){
        $id = $salon->id;
        $count = PromoSalon::whereSalonId($id)->count();
        if($count>0){
            return \Response::json(false);
        }
        $new = new PromoSalon();
        $new->salon_id = $id;
        $new->save();
        return \Response::json(true);
    }

    function salons(Ajax $request){
        $search = $request->get('search', '');
        $q = Salon::select(['id', 'name', 'address_cache']);
        if($search){
            $q->where('name', 'like', "%{$search}%");
        }
        $q->where('certified', 1);
        $q->limit(20);
        $salons = $q->get();
        return \Response::json($salons);
    }

    function index(Request $request){
        if($request->ajax()){
            $search = $request->get('search', ['value'=>'']);
            $keyword = $search['value'];
            $models = PromoSalon::query()->join('salons', 'salons.id', '=', 'promo_salons.salon_id')
                ->select([
                'promo_salons.id',
                'salon_id',
                'salons.name',
                'salons.address_cache',
            ]);
            if($keyword){
                $models = $models->where('salons.name', 'like', '%'.$keyword.'%');
            }
            $rs = \DataTables::eloquent($models)->smart(false);
            $rs = $rs->make(true);
            return $rs;
        }
        return view('modhairworld::backend.pages.promo.index');
    }

    function destroy(Ajax $request){
        $ids = $request->get('ids', []);
        PromoSalon::whereIn('id', $ids)->delete();
        return \Response::json(1);
    }
}