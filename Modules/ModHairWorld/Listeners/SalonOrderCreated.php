<?php
namespace Modules\ModHairWorld\Listeners;


use Modules\ModHairWorld\Notifications\CommonNotify;
use Modules\ModContact\Emails\AdminReplyContact;
use Modules\ModContact\Emails\NotifyAdmins;
use Modules\ModHairWorld\Entities\SalonOrder;

class SalonOrderCreated
{
    function handle(\Modules\ModHairWorld\Events\SalonOrderCreated $event) {
        $setting = [
            'send_mail_new_booking_order' => true,
            'booking_order_recipients' => '',
            'send_mail_new_shop_order' => true,
            'shop_order_recipients' => '',
        ];
        $setting = getSettings($setting);
        $emails = $setting['booking_order_recipients'];
        if ($setting['send_mail_new_booking_order'] == true && strlen($emails) > 0) {
            $order = $event->model;
            $user = $order->user;

            $content = '<div style="font-weight: bold">'.__('Đơn đặt chỗ:').'</div>';
            $content .= "<div>Khách hàng <b>{$user->name}</b> vừa tạo một đơn đặt chỗ mang mã số <b>#{$order->id}</b>. Vui lòng xét duyệt đơn đặt chỗ. Hạn chót: {$order->created_at->addMinute(SalonOrder::getProcessTimeOut())->format('H:i d/m/Y')}</div></br></br>";

            $subject = "Đơn đặt chỗ mới";
            $emailComps = explode(';', $emails);
            try {
                foreach ($emailComps as $email) {
                    $email = preg_replace('/\s+/', '', $email);
                    \Mail::to([$email])->queue(new AdminReplyContact($content, $subject));
                }
            } catch (\Exception $e) {
                // ignored
            }
        }
    }
}