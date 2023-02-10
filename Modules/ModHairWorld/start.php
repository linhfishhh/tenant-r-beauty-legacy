<?php

/*
|--------------------------------------------------------------------------
| Register Namespaces And Routes
|--------------------------------------------------------------------------
|
| When a module starting, this file will executed automatically. This helps
| to register some namespaces like translator or view. Also this file
| will load the routes file for each module. You may also modify
| this file as you want.
|
*/

use Modules\ModHairWorld\Notifications\CommonNotify;

//function UpdateOrder($transaction_info, $order_code, $payment_id, $payment_type, $secure_code)
//{
//    $secure_pass = getSetting('nl_pass');
//    $secure_code_new = md5($transaction_info.' '.$order_code.' '.$payment_id.' '.$payment_type.' '.$secure_pass);
//    if($secure_code_new != $secure_code)
//    {
//        return -1; // Sai mã bảo mật
//    }
//    else // Thanh toán thành công
//    {
//        $order = \Modules\ModHairWorld\Entities\SalonOrder::find($order_code);
//        if(!$order){
//            return -1;
//        }
//        $order->status = \Modules\ModHairWorld\Entities\SalonOrder::_CHO_XU_LY_;
//        $order->payment_time = \Carbon\Carbon::now();
//        $order->online_payment_code = $payment_id;
//        $order->save();
//        $user = $order->user;
//        if($user){
//            $user->notify(new CommonNotify(
//                $user->avatar_id?$user->avatar_id:false,
//                "<strong>Đơn đặt chỗ #{$order->id}</strong> đã được thanh toán qua cổng thanh toán <strong>nganluong.vn</strong>",
//                false,
//                '#FF5C39'
//            ));
//        }
//        // Trường hợp là thanh toán tạm giữ. Hãy đưa thông báo thành công và cập nhật hóa đơn phù hợp
//        if($payment_type == 2)
//        {
//            // Lập trình thông báo thành công và cập nhật hóa đơn
//        }
//        // Trường hợp thanh toán ngay. Hãy đưa thông báo thành công và cập nhật hóa đơn phù hợp
//        elseif($payment_type == 1)
//        {
//            // Lập trình thông báo thành công và cập nhật hóa đơn
//        }
//    }
//}
//
//function RefundOrder($transaction_info, $order_code, $payment_id, $refund_payment_id, $refund_amount, $refund_type, $refund_description, $secure_code){
//
//}

if (!app()->routesAreCached()) {
    require __DIR__ . '/Http/routes.php';
}
