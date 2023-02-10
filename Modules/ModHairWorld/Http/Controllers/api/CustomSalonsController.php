<?php

namespace Modules\ModHairWorld\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ModHairWorld\Entities\CustomSalons;
use Modules\ModHairWorld\Entities\TopSalons;
use Modules\ModHairWorld\Entities\SalonLike;
use App\UploadedFile;

class CustomSalonsController extends SearchV2Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        $customList = CustomSalons::orderBy('created_at', 'asc')->get();
        foreach ($customList as $key => $list) {
            $topSalons = array();
            $listSalon = TopSalons::where('custom_id', $customList[$key]->id)->with('salon')->orderByRaw('RAND()')->get();
            foreach ($listSalon as $salon) {
                $like = false;
                if (auth()->guard('api')->user()) {
                    $salonlike = SalonLike::where('user_id', auth()->guard('api')->user()->id)->get();
                    foreach ($salonlike as $k => $value) {
                        if ($salonlike[$k]->salon_id == $salon->salon->id) {
                            $like = true;
                        }
                    }

                }
                $salon->salon['liked'] = $like;
                $distance = 0;
                if ($request) {
                    $from_lat = $request->get('from_lat', 0) * 1.0;
                    $from_lng = $request->get('from_lng', 0) * 1.0;

                    $to_lat = $salon->salon->map_lat;
                    $to_lng = $salon->salon->map_long;
                    if (is_numeric($from_lat) && is_numeric($from_lng) && is_numeric($to_lat) && is_numeric($to_lng)) {
                        if (
                            $from_lat != 0 && $from_lng != 0 && $to_lat != 0 && $to_lng != 0
                        ) {
                            $from_lat = $from_lat * 1.0;
                            $from_lng = $from_lng * 1.0;
                            $to_lat = $to_lat * 1.0;
                            $to_lng = $to_lng * 1.0;
                            $distance = static::getDistance($from_lat, $from_lng, $to_lat, $to_lng);
                        }
                    }

                }
                $salon->salon['distance'] = $distance;
                $salon->salon['cover'] = $salon->salon->cover ? $salon->salon->cover->getThumbnailUrl('large', getNoThumbnailUrl()) : getNoThumbnailUrl();
                $salon->salon['sale_off_up_to'] = $salon->salon->sale_up_to_percent_cache;
                array_push($topSalons, $salon->salon->getAttributes());
            }
            $customList[$key]['salons'] = $topSalons;
        }

        return response()->json($customList);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('modhairworld::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('modhairworld::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('modhairworld::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
