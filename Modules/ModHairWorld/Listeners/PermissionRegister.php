<?php

namespace Modules\ModHairWorld\Listeners;

use App\Classes\Permission;
use App\Classes\PermissionGroup;

class PermissionRegister
{
    public function handle(\App\Events\PermissionRegister $event)
    {
        $event->registerGroups([
            new PermissionGroup(
                'salon',
                __('Quản lý salon tóc'),
                'icon-store',5)
                               ]);
        $event->registerPermissions([
            new Permission(
                'manage_salons',
                __('Quản lý các salon của các chủ tiệm'),
                'salon',0),
                                        ]
        );

        $event->registerPermissions([
            new Permission(
                'manage_salon_register',
                __('Quản lý đăng ký chủ salon'),
                'backend',1)
        ]);

        $event->registerPermissions([
            new Permission(
                'salon_import_tool',
                __('Sử dụng công cụ import thông tin salon'),
                'tools',3)
        ]);

    }
}
