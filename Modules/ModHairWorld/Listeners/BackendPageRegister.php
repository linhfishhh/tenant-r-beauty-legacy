<?php
namespace Modules\ModHairWorld\Listeners;

use Modules\ModHairWorld\Entities\AdminConfigPages\BookingFlowConfigs;

use Modules\ModHairWorld\Entities\AdminConfigPages\BrandSmsConfigs;
use Modules\ModHairWorld\Entities\AdminConfigPages\CustomerSupportConfigs;
use Modules\ModHairWorld\Entities\AdminConfigPages\PromoCampaignConfigs;
use Modules\ModHairWorld\Entities\ThemeConfigPages\Contact;
use Modules\ModHairWorld\Entities\ThemeConfigPages\FamousBrand;
use Modules\ModHairWorld\Entities\ThemeConfigPages\Home;
use Modules\ModHairWorld\Entities\ThemeConfigPages\Deals;
use Modules\ModHairWorld\Entities\ThemeConfigPages\HomeSlider;
use Modules\ModHairWorld\Entities\ThemeConfigPages\HomeSliderMobile;
use Modules\ModHairWorld\Entities\ThemeConfigPages\Manager;
use Modules\ModHairWorld\Entities\ThemeConfigPages\Master;
use Modules\ModHairWorld\Entities\ThemeConfigPages\Mobile;
use Modules\ModHairWorld\Entities\ThemeConfigPages\Salon;
use Modules\ModHairWorld\Entities\ThemeConfigPages\Search;

class BackendPageRegister
{
    function handle(\App\Events\BackendSettingPageRegister $event){
        $event->register(Master::class);
        $event->register(Home::class);
        $event->register(Deals::class);
        $event->register(FamousBrand::class);
        $event->register(HomeSlider::class);
        $event->register(HomeSliderMobile::class);
        $event->register(Contact::class);
        $event->register(BookingFlowConfigs::class);
        $event->register(Mobile::class);
        $event->register(Search::class);
        $event->register(Salon::class);
        $event->register(Manager::class);
        $event->register(BrandSmsConfigs::class);
        $event->register(CustomerSupportConfigs::class);
        $event->register(PromoCampaignConfigs::class);
    }
}