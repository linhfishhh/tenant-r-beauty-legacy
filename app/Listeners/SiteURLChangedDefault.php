<?php

namespace App\Listeners;

use App\Events\SiteURLChanged;

class SiteURLChangedDefault
{
    public function handle(SiteURLChanged $event)
    {
        $event->registerDBChanger(
            'settings',
            'value');
        $event->registerDBChanger(
            'menu_items',
            'options');
        $event->registerDBChanger(
            'user_metas',
            'value');
        $event->registerDBChanger(
            'user_widgets',
            'value');

        $event->registerDBChanger(
            'slider_options',
            'option');

        $event->registerDBChanger(
        'slider_layer_animations',
        'params');
        $event->registerDBChanger(
            'slider_layer_animations',
            'settings');

        $event->registerDBChanger(
            'wa_slider_navigations',
            'css');
        $event->registerDBChanger(
            'wa_slider_navigations',
            'markup');
        $event->registerDBChanger(
            'wa_slider_navigations',
            'settings');

        $event->registerDBChanger(
            'slider_revslider_backup_slides',
            'params');
        $event->registerDBChanger(
            'slider_revslider_backup_slides',
            'settings');
        $event->registerDBChanger(
            'slider_revslider_backup_slides',
            'layers');

        $event->registerDBChanger(
            'slider_sliders',
            'params');
        $event->registerDBChanger(
            'slider_sliders',
            'settings');

        $event->registerDBChanger(
            'slider_slides',
            'params');
        $event->registerDBChanger(
            'slider_slides',
            'settings');
        $event->registerDBChanger(
            'slider_slides',
            'layers');

        $event->registerDBChanger(
            'slider_static_slides',
            'params');
        $event->registerDBChanger(
            'slider_static_slides',
            'settings');
        $event->registerDBChanger(
            'slider_static_slides',
            'layers');
    }
}
