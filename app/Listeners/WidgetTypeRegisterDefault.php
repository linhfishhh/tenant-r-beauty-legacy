<?php

namespace App\Listeners;

use App\Classes\WidgetType\WidgetTypeMenu;
use App\Classes\WidgetType\WidgetTypeText;
use App\Classes\WidgetType\WidgetTypeTinyMCE;
use App\Classes\WidgetTypeGroup;
use App\Events\WidgetTypeRegister;

class WidgetTypeRegisterDefault
{
    public function handle(WidgetTypeRegister $event)
    {
	    $group = new WidgetTypeGroup('basic', __('Cơ bản'), -1);
	    $event->registerTypeGroup($group);

	    $type = new WidgetTypeText();
	    $event->registerType( $type);

        $type = new WidgetTypeTinyMCE();
        $event->registerType( $type);

	    $type = new WidgetTypeMenu();
	    $event->registerType( $type);
    }
}
