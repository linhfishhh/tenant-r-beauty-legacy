<?php

namespace Modules\ModHairWorld\Channels;

use App\User;
use GuzzleHttp\Psr7\Response;
use Modules\ModHairWorld\Entities\Salon;
use Modules\ModHairWorld\Entities\UserDevice;
use Modules\ModHairWorld\Http\Controllers\OneSignalController;
use Modules\ModHairWorld\Notifications\CommonNotify;

class OneSignalChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param CommonNotify $notification
     * @return void
     */
    public function send($notifiable, CommonNotify $notification){
        $class = get_class($notifiable);
        $is_salon = $class == Salon::class;
        $client = null;

        $data = $notification->data;
        if($data == null){
            $data = [];
        }
        $data['type'] = $notification->type;
        $data['notification_id'] = $notification->id;
        $params =           [
            'headings' => [
                'en' => $notification->mobile_heading?$notification->mobile_heading:''
            ],
            'contents' => [
                'en' => $notification->mobile_title
            ],
            'data' => $data,
            'url' => $notification->url,
            'priority' => 10,
            'large_icon' => 'ic_stat_onesignal_large_icon',
            'android_sound' => 'noti2',
            'ios_sound' => 'noti2.wav',
            'ios_badgeType' => 'Increase',
            'ios_badgeCount' => 1
        ];

        if($is_salon){
            /** @var Salon $salon */
            $params['data']['scope'] = 'salon';
            $salon = $notifiable;
            $salon_id = $salon->id;
            $client = OneSignalController::getManagerClient();
            $params['tags'] = [
                [
                    'key' => 'salon_id',
                    'relation' => '=',
                    'value' => $salon_id
                ]
            ];
        }
        else{
            /** @var User $user */
            $params['data']['scope'] = 'customer';
            $user = $notifiable;
            $user_id = $user->id;
            $client = OneSignalController::getCustomerClient();
            $params['tags'] = [
                [
                    'key' => 'user_id',
                    'relation' => '=',
                    'value' => $user_id
                ]
            ];
        }

        $params = array_merge($params,$notification->overwrite);
        /** @var Response $rs */
        $rs = $client->sendNotificationCustom(
            $params
        );

        if($notification->callback instanceof \Closure){
            try{
                $fn = $notification->callback;
                $content = $rs->getBody()->getContents();
                $object = \GuzzleHttp\json_decode($content, true);
                $fn($object);
            }
            catch (\Exception $exception){

            }
        }
    }
}