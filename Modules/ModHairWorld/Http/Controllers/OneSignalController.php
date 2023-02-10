<?php

namespace Modules\ModHairWorld\Http\Controllers;


use App\Http\Controllers\Controller;
use Berkayk\OneSignal\OneSignalClient;

class OneSignalController extends Controller
{

    static public function getManagerClient(){
        return new OneSignalClient(config('onesignal.ONESIGNAL_MANAGER_ID'),
            config('onesignal.ONESIGNAL_MANAGER_KEY'),
            config('onesignal.ONESIGNAL_AUTH_KEY'));
    }

    static public function getCustomerClient(){
        return new OneSignalClient(config('onesignal.ONESIGNAL_CUSTOMER_ID'),
            config('onesignal.ONESIGNAL_CUSTOMER_KEY'),
            config('onesignal.ONESIGNAL_AUTH_KEY'));
    }

//    public static function updateUserDevice(User $user, $scope = 'customer', $new_device_id = ''){
//        /** @var OneSignalClient $test */
//        $test = static::getManagerClient();
//    }
}