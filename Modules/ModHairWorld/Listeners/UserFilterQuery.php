<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 12/24/18
 * Time: 16:48
 */

namespace Modules\ModHairWorld\Listeners;


use Illuminate\Database\Query\Builder;
use Modules\ModHairWorld\Http\Controllers\BrandSmsController;

class UserFilterQuery
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(\App\Events\UserFilterQuery $event)
    {
        /** @var Builder $query */
        $query = $event->query;
        /** @var \Request $request */
        $request = $event->request;
        $keyword = "%{$request->get( 'search')['value']}%";
        $query->orWhere('phone', 'like', $keyword);
    }
}