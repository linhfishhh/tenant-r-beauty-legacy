<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/26/18
 * Time: 15:47
 */

namespace Modules\ModHairWorld\Http\Controllers;


use App\Http\Controllers\Controller;
use App\UploadedFile;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Response;


class AdsController extends Controller
{
    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    function exportGoogleTag(Request $request){
        $key = $request->get('key');
        if($key != 'aon88ne34e'){
            throw new UnauthorizedException();
        }
        $lv1 = $request->get('locations', '');
        if(!$lv1){
            throw new UnauthorizedException();
        }
        $lv1 = explode(',', $lv1);
        if(!is_array($lv1)){
            throw new UnauthorizedException();
        }
        $salons = \Modules\ModHairWorld\Entities\Salon::with(['cover'])
            ->select(
                [
                    'id',
                    'name',
                    'cover_id',
                    'tinh_thanh_pho_id',
                    'price_from_cache',
                    'price_to_cache'
                ]
            )
            ->where('open', 1)
            ->where('certified', 1)
            ->whereNested(function($q){
                $q->where('price_from_cache', '!=',0);
                $q->where('price_to_cache', '!=',0);
            })
            ->whereIn('tinh_thanh_pho_id', $lv1)
            ->get();
        $headers = array(
            'Content-Encoding: UTF-8',
            "Content-type" => "text/csv; charset=UTF-8'",
            "Content-Disposition" => "attachment; filename=file.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $columns = array('ID', 'Item title', 'Price', 'Sale price', 'Final URL', 'Image URL');
        $callback = function() use ($columns, $salons)
        {
            $file = fopen('php://output', 'w');
            fputs( $file, "\xEF\xBB\xBF" );
            fputcsv($file, $columns);

            foreach ($salons as $salon){
                /** @var UploadedFile $cover */
                $cover = $salon->cover;
                if(!$cover){
                    $cover = getNoThumbnailUrl();
                }
                else{
                    $cover = $cover->getThumbnailUrl('large');
                }
                fputcsv($file, [
                    $salon->id,
                    $salon->name,
                    $salon->price_to_cache.' VND',
                    $salon->price_from_cache.' VND',
                    $salon->url(),
                    $cover

                ]);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
}