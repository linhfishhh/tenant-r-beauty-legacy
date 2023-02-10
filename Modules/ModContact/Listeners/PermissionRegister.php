<?php

namespace Modules\ModContact\Listeners;

use App\Classes\Permission;

class PermissionRegister
{
    public function handle(\App\Events\PermissionRegister $event)
    {
        $event->registerPermissions([
                                        new Permission(
                                            'manage_contact',
                                            __('Quản lý liên hệ'),
                                            'backend',1)
                                    ]);
    }
}
