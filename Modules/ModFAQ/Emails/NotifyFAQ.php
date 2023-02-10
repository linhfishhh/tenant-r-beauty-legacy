<?php

namespace Modules\ModFAQ\Emails;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\ModFAQ\Entities\FAQ;

class NotifyFAQ extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $faq;
    public $user;
    public function __construct(FAQ $faq, User $user)
    {
        $this->faq = $faq;
        $this->user = $user;
        $this->onQueue('low');
    }


    public function build()
    {
        $content = '<div style="text-align: left"><div style="font-weight: bold">Vào ngày '.$this->faq->created_at->format('d/m/Y').' bạn có gửi câu hỏi đến chúng tôi như sau:</div>';
        $content .= '<div><p>'.$this->faq->title.'</p></div>';
        $content .= '<div style="font-weight: bold">Nay chúng tôi trả lời thắc mắc của bạn như sau:</div>';
        $content .= '<div>'.$this->faq->answer.'</div></div>';
        $this->subject(__('Câu hỏi của bạn đã được trả lời'));
        return $this->view('mail', [
            'greeting' => __('Chào :name!', ['name' => $this->user->name]),
            'title' => __('Trả lời câu hỏi của bạn'),
            'content' => $content
        ]);
    }
}
