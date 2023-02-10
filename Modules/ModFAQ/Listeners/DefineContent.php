<?php

namespace Modules\ModFAQ\Listeners;

use Modules\ModFAQ\Entities\FAQ;

class DefineContent
{
    public function handle(\App\Events\DefineContent $event)
    {
        $event->registerPostType(FAQ::class);
    }
}
