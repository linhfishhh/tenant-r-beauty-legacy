<?php

namespace Modules\ModContact\Http\Controllers;

use App\Http\Requests\Ajax;
use DataTables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\ModContact\Emails\AdminReplyContact;
use Modules\ModContact\Emails\NotifyAdmins;
use Modules\ModContact\Entities\Contact;
use Modules\ModContact\Http\Requests\ContactReply;
use Modules\ModContact\Http\Requests\ContactStore;

class ModContactController extends Controller
{
    function contactStore(ContactStore $request){
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
        return \Response::json($tos);
    }

    function backendIndex(Request $request){
        if ($request->ajax()) {
            $subs = Contact::query();
            $rs = DataTables::eloquent($subs);
            $rs->filter(
                function ($query) use
                (
                    $request
                ) {
                    /** @var Builder $query */
                    $keyword = "%{$request->get( 'search')['value']}%";
                    if ($keyword) {
                        $query->where(
                            function ($query) use
                            (
                                $keyword
                            ) {
                                /** @var Builder $query */
                                $query->where(
                                    'name',
                                    'like',
                                    $keyword
                                );
                                $query->orWhere(
                                    'email',
                                    'like',
                                    $keyword
                                );
                                $query->orWhere(
                                    'phone',
                                    'like',
                                    $keyword
                                );
                            }
                        );
                    }

                    /** @var Builder $query */
                    $date_start = $request->get('date_start', '');
                    $date_end = $request->get('date_end', '');
                    if($date_end && $date_start){
                        $query->whereBetween(
                            'created_at',
                            [
                                $date_start,
                                $date_end
                            ]
                        );
                    }
                    $handled = $request->get('handled', -1);
                    if($handled != -1){
                        $query->where('handled', $handled);
                    }
                }
            );
            $rs = $rs->make();
            return $rs;
        }
        return view('modcontact::backend.pages.index');
    }

    function backendDestroy(Ajax $request){
        $ids = $request->get('ids', []);
        Contact::whereIn('id', $ids)->delete();
        return \Response::json();
    }

    function backendHandle(Ajax $request){
        $ids = $request->get('ids', []);
        $handled = $request->get('handled', 0);
        Contact::whereIn('id', $ids)->update([
                                                'handled' => $handled
                                             ]);
        return \Response::json();
    }

    function backendGetMailList(Ajax $request){
        $rs = getSetting('contact_mail_list','');
        return \Response::json($rs);
    }

    function backendSetMailList(Ajax $request){
        $list = $request->get('mail_list', '');
        if($list == null){
            $list = '';
        }
        setSetting(
            'contact_mail_list',
            $list);
        return \Response::json($list);
    }

    function reply(ContactReply $request, Contact $contact){
        $content = '<div style="font-weight: bold">'.__('Bạn đã hỏi:').'</div>';
        $content .= '<blockquote>'.$contact->content.'</blockquote>';
        $content .= '<div style="font-weight: bold">'.__('Chúng tôi xin trả lời:').'</div>';
        $content .= $request->get('content');
        $email = $contact->email;
        $subject = $request->get('subject', __('Trả lời liên hệ'));
        \Mail::to([$email])->queue(new AdminReplyContact($content, $subject));
        $contact->handled = 1;
        $contact->save();
        return \Response::json();
    }
}
