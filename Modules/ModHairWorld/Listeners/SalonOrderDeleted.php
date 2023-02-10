<?php
namespace Modules\ModHairWorld\Listeners;

use Modules\ModHairWorld\Entities\SalonOrderItem;

class SalonOrderDeleted
{
    function handle(\Modules\ModHairWorld\Events\SalonOrderDeleted $event){
        $order = $event->model;
        /** @var SalonOrderItem[] $items */
        $items = $order->items;
        foreach ($items as $item){
            $service = $item->service;
            $item->delete();
            if($service){
                $service->cacheBookingCount();
            }
        }
        $review = $order->review;
        if($review){
            $review->delete();
        }
        if($order->salon){
            $order->salon->cacheBookingCount();
        }
    }
}