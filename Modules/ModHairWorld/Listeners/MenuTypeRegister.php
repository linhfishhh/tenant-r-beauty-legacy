<?php
namespace Modules\ModHairWorld\Listeners;


use Modules\ModHairWorld\Entities\MenuType\MenuTypeContactPage;
use Modules\ModHairWorld\Entities\MenuType\MenuTypeSearchPage;

class MenuTypeRegister
{
    function handle(\App\Events\MenuTypeRegister $event){
        $type = new MenuTypeContactPage();
        $event->registerType($type);

        $type = new MenuTypeSearchPage();
        $event->registerType($type);


    }
}