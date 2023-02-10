<?php

namespace Modules\ModHairWorld\Listeners;


use App\Classes\ThumbnailSize;
use Intervention\Image\Image;

class ThumbnailSizeRegister
{
    function handle(\App\Events\ThumbnailSizeRegister $event){
        $event->register(new ThumbnailSize(
            'small - vuông',
            'Nhỏ',
            64,
            64,
            function (
                Image $img,
                $width,
                $height
            ) {
                $img->fit(
                    $width,
                    $height
                );
                return $img;
            }
        ));
        $event->register(new ThumbnailSize(
            'small_ka',
            'nhỏ - giữ tỉ lệ',
            '?',
            64,
            function (
                Image $img,
                $width,
                $height
            ) {
                $img->resize(null, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });
                return $img;
            }
        ));
        $event->register(new ThumbnailSize(
            'medium',
            'Trung bình',
            350,
            196,
            function (
                Image $img,
                $width,
                $height
            ) {
                $img->fit(
                    $width,
                    $height
                );
                return $img;
            }
        ));
        $event->register(new ThumbnailSize(
            'medium_sq',
            'Trung bình - vuông',
            350,
            350,
            function (
                Image $img,
                $width,
                $height
            ) {
                $img->fit(
                    $width,
                    $height
                );
                return $img;
            }
        ));

        $event->register(new ThumbnailSize(
            'medium_ka',
            'Trung bình - giữ tỉ lệ',
            350,
            '?',
            function (
                Image $img,
                $width,
                $height
            ) {
                $img->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                return $img;
            }
        ));

        $event->register(new ThumbnailSize(
            'large',
            'Lớn',
            450,
            340,
            function (
                Image $img,
                $width,
                $height
            ) {
                $img->fit(
                    $width,
                    $height
                );
                return $img;
            }
        ));
    }
}