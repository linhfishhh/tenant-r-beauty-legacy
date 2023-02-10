<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1/3/19
 * Time: 17:05
 */

namespace Modules\ModHairWorld\Http\Controllers\frontend;


use App\Http\Controllers\Controller;
use App\UploadedFile;


class FacebookThumbController extends Controller
{
    function thumb(\Request $request, $upload, $file_name){
        $file = UploadedFile::find($upload);
        if(!$file){
            $file_path = public_path('assets/images/no-thumb.png');
        }
        else
        {
            $file_path = public_path($file->getUploadFilePath());
        }
        //dd($file_path);
        $img = \Image::make( $file_path);
        $width = 1200;
        $height = 628;
        if($img->width() < 0){
            $width = 600;
            $height = 315;
        }
        $img->fit(
            $width,
            $height
        );
        //dd($img);
        return $img->response('jpg');
    }
}