<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/24/18
 * Time: 16:48
 */

namespace Modules\ModHairWorld\Listeners;


use Modules\ModHairWorld\Http\Controllers\BrandSmsController;

class UserCreated
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(\App\Events\User\UserCreated $event)
    {
        $user = $event->model;
        if($user->role_id == 3){
            $message = 'Chuc mung ban da dang ki thanh cong iSalon Manager. Thong tin tai khoan ID: '.$user->phone;
            $message .= "\nIOS APP:";
            $message .= "\nhttp://bit.ly/iSManger";
            $message .= "\nANDROID APP:";
            $message .= "\nhttp://bit.ly/iSManager";
            if(\Request::has('password')){
                $message .= ', Mat khau: '.\Request::get('password');
            }
            try{
                $controller = new BrandSmsController();
                $controller->sendSms($user->phone, $message);
            }
            catch (\Exception $exception){

            }
        }
    }
}