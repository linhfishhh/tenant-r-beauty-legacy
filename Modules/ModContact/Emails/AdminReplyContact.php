<?php

namespace Modules\ModContact\Emails;

use App\Mail\NotifyAsMail;
use Illuminate\Contracts\Queue\ShouldQueue;

class AdminReplyContact extends NotifyAsMail implements ShouldQueue
{
    public function __construct(
        $content,
        $subject
    ) {
        parent::__construct(
            $content,
            $subject
        );
        $this->onQueue('normal');
    }

}
