<?php
/**
 * Created by PhpStorm.
 * User: TRANG
 * Date: 18-Jun-18
 * Time: 17:18
 */

namespace Modules\ModHairWorld\Listeners;


use App\Events\DefineContent;
use Modules\ModHairWorld\Entities\Badge;
use Modules\ModHairWorld\Entities\PostTypes\MobileHelp;
use Modules\ModHairWorld\Entities\PostTypes\MobileNews;
use Modules\ModHairWorld\Entities\PostTypes\News;
use Modules\ModHairWorld\Entities\SalonServiceReviewCriteria;

class PostTypeRegister
{
    function handle(DefineContent $event){
        $event->registerPostType(News::class);
        $event->registerPostType(MobileNews::class);
        $event->registerPostType(MobileHelp::class);
        $event->registerPostType(SalonServiceReviewCriteria::class);
        $event->registerPostType(Badge::class);
    }
}