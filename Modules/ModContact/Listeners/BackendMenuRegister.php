<?php

namespace Modules\ModContact\Listeners;

use App\Classes\BackendMenuItem;
use App\Events\BackendMenuItemRegister;

class BackendMenuRegister
{
    public function handle(BackendMenuItemRegister $event)
    {
        $event->register([
                            new BackendMenuItem(
                                'contact',
                                __('Liên hệ của khách'),
                                'interaction',
                                'backend.contact.index',
                                'icon-phone2',
                                [
                                    'manage_contact'
                                ],
                                false,
                                3
                            )
                         ]);
    }
}
