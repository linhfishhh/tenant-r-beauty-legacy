<?php

namespace Modules\ModHairWorld\Http\Controllers\ApiAdmin;

use App\Http\Controllers\Controller;
use Modules\ModHairWorld\Notifications\CommonNotify;
use Modules\ModContact\Emails\AdminReplyContact;
use Modules\ModContact\Emails\NotifyAdmins;
use Modules\ModHairWorld\Entities\SalonOrder;
use Illuminate\Http\Request;
use Modules\ModHairWorld\Entities\Salon;

class NotificationController extends Controller
{
  function notifyNewProductOrder(Request $request) {
    $username = $request->get('username');
    $orderNumber = $request->get('order_number');
    
    $setting = [
      'send_mail_new_shop_order' => true,
      'shop_order_recipients' => '',
    ];
    $setting = getSettings($setting);
    $emails = $setting['shop_order_recipients'];
    if ($setting['send_mail_new_shop_order'] == true && strlen($emails) > 0) {
      
      $content = '<div style="font-weight: bold">'.__('Đơn mua sản phẩm:').'</div>';
      $content .= "<div>Khách hàng <b>{$username}</b> vừa tạo một đơn mua sản phẩm mã số <b>#{$orderNumber}</b>. Vui lòng xét duyệt đơn hàng.</div></br></br>";
      
      $subject = "Đơn mua sản phẩm mới";
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
    
    return \Response::json(['status' => true]);
  }
  
  function notifyCreateSalonWallet(Request $request) {
    \Validator::validate($request->all(), [
      'salonId' => ['required']
    ]);

    $salonId = $request->get("salonId");
    $salon = Salon::find($salonId);
    
    if ($salon) {
      $salon->notify(new CommonNotify(
        false,
        "Salon {$salon->name} vừa được tạo ví tại hệ thống iSalon",
        '',
        '#FF5C39',
        true
      ));
          
      return \Response::json(['status' => true]);
    }

    return \Response::json(['status' => false]);
  }

  function notifyUpdateSalonWallet(Request $request) {
    \Validator::validate($request->all(), [
      'salonId' => ['required']
    ]);

    $salonId = $request->get("salonId");
    $isSettlement = $request->get("isSettlement");
    $description = $request->get("description");

    $salon = Salon::find($salonId);
    
    if ($salon) {
      $msg = $isSettlement == true ? 'quyết toán' : 'cập nhật ví';
      $salon->notify(new CommonNotify(
        false,
        "Salon {$salon->name} vừa được {$msg} với nội dung: {$description}",
        '',
        '#FF5C39',
        true
      ));
          
      return \Response::json(['status' => true]);
    }

    return \Response::json(['status' => false]);
  }

  function notifySalonManager(Request $request) {
    \Validator::validate($request->all(), [
      'salonId' => ['required'],
      'title' => ['required'],
      'content' => ['required']
    ]);

    $salonId = $request->get("salonId");
    $title = $request->get("title");
    $content = $request->get("content");

    $salon = Salon::find($salonId);
    if ($salon) {
      $salon->notify(new CommonNotify(
        false,
        $title,
        '',
        '#FF5C39',
        true,
        'text',
        $title,
        $content
      ));

      return \Response::json(['status' => true]);
    }
        
    return \Response::json(['status' => false]);
  }
}