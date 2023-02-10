<?php

namespace App\Listeners;

use App\Classes\BackendDashBoardWidget;
use App\Classes\PostType;
use App\Events\BackendDashboardWidgetRegister;

class BackendDashboardWidgetRegisterDefault
{
    public function handle(BackendDashboardWidgetRegister $event)
    {
        /** @var PostType[] $post_types */
        $post_types = getPostTypes();
        foreach ($post_types as $type) {
            if (!$type::showDashboardWidget()) {
                continue;
            }
            if (!\View::exists($type::getDashboardWidgetViewName())) {
                continue;
            }
            $event->register(
                new BackendDashBoardWidget(
                    'post_type_' . $type::getTypeSlug(),
                    $type::getDashboardWidgetViewName(),
                    $type::getDashboardWidgetViewData(),
                    $type::getDashboardWidgetPermissions(),
                    $type::getDashboardWidgetPermissionHasOne(),
                    $type::getDashboardWidgetOrder()
                )
            );
        }
    }
}
