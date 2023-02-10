<?php

namespace Modules\ModContact\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Modules\ModContact\Entities\Contact;

class NotifyAdmins extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
        $this->onQueue('normal');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject(__('Thông báo - liên hệ mới'));
        $content = '<div style="text-align: left">';
        $content .= '<div>'.__('Xin thông báo đã có khách liên hệ với những thông tin như sau:').'</div>';
        $content .= '<ul>';
        $content .= __('<li><strong>Tên khách:</strong> :name</li>', ['name' => $this->contact->name]);
        $content .= __('<li><strong>Địa chỉ email:</strong> :email</li>', ['email' => $this->contact->email]);
        $content .= __('<li><strong>Điện thoại liên hệ:</strong> :phone</li>', ['phone' => $this->contact->phone]);
        $content .= '</ul>';
        $content .= __('<div><strong>Nội dung liên hệ:</strong></div>');
        $content .= '<div>'.$this->contact->content.'</div>';
        $content .= '<div style="margin: 15px 0; color: brown">'.__('*Bạn cũng có thể quản lý thông tin liên hệ này trong hệ thống quản trị của website').'</div>';
        $content .= '</div>';

        $this->view('mail', [
            'title' => __('Liên hệ của khách'),
            'content' => $content,
        ]);
        return $this;
    }
}
