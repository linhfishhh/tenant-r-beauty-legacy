<?php

namespace App\Listeners;

use App\Classes\BackendSettingPage\BackendSettingPageFileUpload;
use App\Classes\BackendSettingPage\BackendSettingPageUserRelations;
use App\Events\BackendSettingPageRegister;

class BackendSettingPageRegisterDefault
{
    public function handle(BackendSettingPageRegister $event)
    {
        $event->register(BackendSettingPageFileUpload::class);
        //$event->register(BackendSettingPageTest::class);
        $event->register(BackendSettingPageUserRelations::class);
    }
}
