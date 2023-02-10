<?php

namespace Modules\ModHairWorld\Http\Controllers;

use App\Http\Requests\Ajax;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ModHairWorld\Entities\DiaPhuongQuanHuyen;
use Modules\ModHairWorld\Entities\DiaPhuongTinhThanhPho;
use Modules\ModHairWorld\Entities\DiaPhuongXaPhuongThiTran;
use Response;

class ModHairWorldController extends Controller
{

    public function getInfoTinhThanhPho(Ajax $request){
        $search = $request->get('search', '');
        $list = DiaPhuongTinhThanhPho::where('name', 'like', "%{$search}%")->paginate(30);
        return Response::json($list);
    }
    public function getInfoTinhThanhPhoFromIds(Ajax $request){
        $search = $request->get('ids', '');
        $list = DiaPhuongTinhThanhPho::whereIn('id', $search)->get();
        return Response::json($list);
    }

    public function getInfoQuanHuyen(Ajax $request){
        $search = $request->get('search', '');
        $tp_id = $request->get('matp');
        $list = DiaPhuongQuanHuyen::where('matp', '=', $tp_id)->where('name', 'like', "%{$search}%")->paginate(30);
        return Response::json($list);
    }

    public function getInfoPhuongXa(Ajax $request){
        $search = $request->get('search', '');
        $tp_id = $request->get('maqh');
        $list = DiaPhuongXaPhuongThiTran::where('maqh', '=', $tp_id)->where('name', 'like', "%{$search}%")->paginate(30);
        return Response::json($list);
    }
}
