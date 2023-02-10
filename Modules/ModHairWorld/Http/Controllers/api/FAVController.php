<?php

namespace Modules\ModHairWorld\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\SalonLike;
use Modules\ModHairWorld\Entities\SalonShowcaseItem;
use Modules\ModHairWorld\Entities\SalonShowcaseLike;

class FAVController extends Controller
{
    function deleteShowcaseLike(Request $request){
        $id = $request->get('id');
        $like = SalonShowcaseLike::where('user_id', \Auth::user()->id)
            ->where('id', $id)
            ->first();
        if($like){
            $like->delete();
        }
        return \Response::json([]);
    }

    function deleteSalonLike(Request $request){
        $id = $request->get('id');
        $like = SalonLike::where('user_id', \Auth::user()->id)
            ->where('id', $id)
            ->first();
        if($like){
            $like->delete();
        }
        return \Response::json([]);
    }

    function listFav(){
        $favs = [
            'salons' => [],
            'showcases' => []
        ];
        $salons = SalonLike::where('user_id', \Auth::user()->id)
            ->has('salon')
            ->with(['salon', 'salon.cover', 'salon.location_lv1', 'salon.location_lv2', 'salon.location_lv3'])
            ->orderBy('id', 'desc')
            ->get();
        if($salons){
            $favs['salons'] = $salons->map(function (SalonLike $like){
               return [
                   'id' => $like->id,
                   'salon' => [
                       'id' => $like->salon->id,
                       'name' => $like->salon->name,
                       'address' => $like->salon->getAddressLine(),
                       'rating' => $like->salon->rating,
                       'ratingCount' => $like->salon->rating_count,
                       'avatar' => $like->salon->cover?$like->salon->cover->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl()
                   ]
               ];
            });
        }

        $showcases = SalonShowcaseLike::where('user_id', \Auth::user()->id)
            ->has('showcase')
            ->with(['showcase', 'showcase.items', 'showcase.items.image', 'showcase.salon'])
            ->orderBy('id', 'desc')
            ->get();

        if($showcases){
            $favs['showcases'] = $showcases->map(function (SalonShowcaseLike $like){
                $cover = $like->showcase->cover();
                return [
                    'id' => $like->id,
                    'showcase' => [
                        'id' => $like->showcase->id,
                        'name' => $like->showcase->name,
                        'cover' => $cover->image?$cover->image->getThumbnailUrl('default', getNoThumbnailUrl()):getNoThumbnailUrl(),
                        'salon' => [
                            'id' => $like->showcase->salon->id,
                            'name' => $like->showcase->salon->name
                        ],
                        'items' => $like->showcase->items->map(function (SalonShowcaseItem $item){
                            return [
                                'image' => $item->image?$item->image->getUrl():getNoThumbnailUrl()
                            ];
                        })
                    ],
                ];
            });
        }

        return \Response::json($favs);
    }
}