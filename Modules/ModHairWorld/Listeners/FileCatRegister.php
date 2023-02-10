<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 30-May-18
 * Time: 15:07
 */

namespace Modules\ModHairWorld\Listeners;


use App\Events\FileCategoryRegister;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\SalonBrand;
use Modules\ModHairWorld\Entities\SalonGallery;
use Modules\ModHairWorld\Entities\SalonService;
use Modules\ModHairWorld\Entities\SalonShowcaseItem;
use Modules\ModHairWorld\Entities\SalonStylist;

class FileCatRegister
{
    function handle(FileCategoryRegister $event){
        foreach (Salon::getFileCatIDs() as $id=>$title){
            $event->register(
                $id,
                $title);
        }
        foreach (SalonService::getFileCatIDs() as $id=>$title){
            $event->register(
                $id,
                $title);
        }
        foreach (SalonStylist::getFileCatIDs() as $id=>$title){
            $event->register(
                $id,
                $title);
        }
        foreach (SalonBrand::getFileCatIDs() as $id=>$title){
            $event->register(
                $id,
                $title);
        }
        foreach (SalonShowcaseItem::getFileCatIDs() as $id=>$title){
            $event->register(
                $id,
                $title);
        }
        foreach (SalonGallery::getFileCatIDs() as $id=>$title){
            $event->register(
                $id,
                $title);
        }
        $event->register(
            'badge',
            __('Ảnh lời khen'));
        $event->register(
            'review',
            __('Ảnh đánh giá nhận xét'));
        $event->register(
            'theme_files',
            __('Giao diện'));
        $event->register(
            'mobile_files',
            __('App di động'));
    }
}