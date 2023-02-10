<?php

namespace Modules\ModFAQ\Http\Controllers;

use App\Http\Requests\Ajax;
use App\User;
use Illuminate\Routing\Controller;
use Modules\ModFAQ\Emails\NotifyFAQ;
use Modules\ModFAQ\Entities\FAQ;

class ModFAQController extends Controller
{
    function sendNotify(Ajax $request){
        $ids = $request->get('ids', []);
        $faqs = FAQ::with('user')->whereIn('id', $ids)->where('need_notify', 1)->get();
        foreach ($faqs as $faq){
            /** @var User $user */
            $user = $faq->user;
            if($user){
                $mail = new NotifyFAQ($faq, $user);
                \Mail::to([$user->email])->queue($mail);
                $faq->notified = 1;
                $faq->save();
            }
        }
        return \Response::json();
    }
}
