<?php

namespace Modules\ModHairWorld\Http\Controllers\api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\ModHairWorld\Entities\SearchHistory;

class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        return view('modhairworld::index');
    }

    public function get()
    {
        $rs = SearchHistory::where('user_id', me()->id)->get();

        $list = $rs->map(function ($item , $key) {
            $item->salon_name = $item->salon->name;
            $item->salon_image = $item->salon->cover ? $item->salon->cover->getThumbnailUrl('large', getNoThumbnailUrl()) : getNoThumbnailUrl();
            return [
                "salon_id" => $item->salon_id,
                "name" => $item->salon->name,
                "image" => $item->salon_image,
                "address" => $item->salon->address_cache,
                "created_at" => $item->created_at,
                "updated_at" => $item->updated_at,
            ];
        });
        return \Response::json($list);
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
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $search = SearchHistory::where('user_id', me()->id)
            ->where('salon_id' , $request->salon_id)
            ->first();

        if ($search){
            $search->updated_at = Carbon::now();
            $search->update();
        } else {
            $history = new SearchHistory();
            $history->user_id = me()->id;
            $history->salon_id = $request->salon_id;
            $history->save();
        }

        return \Response::json('success');
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
     * @param  Request $request
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
