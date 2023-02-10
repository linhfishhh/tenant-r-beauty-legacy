<?php

namespace Modules\ModHairWorld\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Requests\Ajax;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ModHairWorld\Entities\TopSalons;
use Modules\ModHairWorld\Entities\CustomSalons;
use Modules\ModHairWorld\Entities\Salon;
use mysql_xdevapi\Exception;
use Carbon\Carbon;
use Validator;
class TopSalonController extends Controller
{

  // cac function cua custom_salon
    function indexcustom(){
        return view('modhairworld::backend.pages.list_salon.index');
    }
    function list(Request $request){

     if($request->ajax()){             
        $rs = \DataTables::of(CustomSalons::all())
        ->editColumn('name', function ($list) {
            return '<a style="color:#009688;" href="'. route('backend.top_salons.show', $list->id).'">'.$list->name.'</a>';
        })
        ->rawColumns(['name'])
        ->make(true);
        return $rs;
    }
}
function addCustomsalons(Request $request){ 
    $name = $request->name ;
    $new = new CustomSalons();
    $new->name = $name;
    $new->created_at = Carbon::now();
    $new->updated_at = Carbon::now();
    $new->save();
    return \Response::json(true);
}
function destroycustom(Ajax $request){
    $ids = $request->get('ids', []);
    CustomSalons::whereIn('id', $ids)->delete();
    return \Response::json(1);
}
function index(Request $request){
    if($request->ajax()){
        $id = (int)explode("?", $request->fullUrl())[1];
        $search = $request->get('search', ['value'=>'']);
        $keyword = $search['value'];
        $models = TopSalons::query()
        ->orderByRaw('RAND()')
        ->join('salons', 'salons.id', '=', 'top_salons.salon_id')
        ->where('custom_id',$id, 'top_salons.custom_id')
        ->select([
            'top_salons.id',
            'custom_id',
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
    return view('modhairworld::backend.pages.top_salon.index');
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

function addSalon(Request $request, $ids){

    $id = $ids;
    $customs_id =$request->id;
    $count = TopSalons::where('salon_id' , $id)->where('custom_id' , $customs_id)->count();
    if($count>0){
        return \Response::json(false);
    }
    $new = new TopSalons();
    $new->salon_id = $id;
    $new->custom_id = $customs_id;
    $new->save();
    return \Response::json($request);
}
function destroySalon(Ajax $request){
   $ids = $request->get('ids', []);
    TopSalons::whereIn('id', $ids)->delete();
    return \Response::json(1);
}
public function show(Request $request , $id)
{
    if($request->ajax()){
        $search = $request->get('search', ['value'=>'']);
        $keyword = $search['value'];
        $models = TopSalons::query()
        -> join('salons', 'salons.id', '=', 'top_salons.salon_id')
        -> where('custom_id',$id, 'top_salons.custom_id')
        ->select([
            'top_salons.id',
            'custom_id',
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
    $data['id']= $id;
    $data['salon'] = CustomSalons::where('id','=',$id)->first();
    return view('modhairworld::backend.pages.top_salon.index', $data);
}
public function postshow(Request $request ,$id)
{
    $request->validate([
        'title' => 'required|min:3|max:25',

    ],
    [
        'title.required' => "Tên không được bỏ trống",
        'title.min' => "Tên phải từ 3 đến 25 ký tự",
        'title.max' => "Tên phải từ 3 đến 25 ký tự",

    ]
);
    $name=$request->title;
    $new=CustomSalons::find($id);
    $new->name=$name;
    $new->updated_at =Carbon::now();
    $new->save();
   return redirect()->route('backend.top_salons.postshow',['id'=>$id]);
    
}
}