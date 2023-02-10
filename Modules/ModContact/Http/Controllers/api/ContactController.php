<?php

namespace Modules\ModContact\Http\Controllers\api;

use Illuminate\Routing\Controller;
use Modules\ModContact\Emails\NotifyAdmins;
use Modules\ModContact\Entities\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    public function contactStore(Request $request){
        $contact = new Contact();
        $contact->name = $request->get('name');
        $contact->email = $request->get('email');
        $contact->phone = $request->get('phone');
        $contact->content = $request->get('content');
        $contact->save();
        $tos = [];
        $mail_list = getSetting('contact_mail_list', '');
        $mail_list = preg_split('/\r\n|[\r\n]/',$mail_list);
        foreach ($mail_list as $item){
            $v = trim($item);
            if(!$v){
                continue;
            }
            $tos[] = $v;
        }
        if($tos){
            \Mail::to($tos)->queue(new NotifyAdmins($contact));
        }
        return response()->json($tos);
    }
}
