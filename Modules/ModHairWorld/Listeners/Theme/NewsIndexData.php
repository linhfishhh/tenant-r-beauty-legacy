<?php

namespace Modules\ModHairWorld\Listeners\Theme;


use App\Events\ThemePostTypeIndexViewData;
use Illuminate\Database\Eloquent\Builder;
use Modules\ModHairWorld\Entities\PostTypes\News;

class NewsIndexData
{
    function handle(ThemePostTypeIndexViewData $event){
        /** @var News $post_type */
        $post_type = $event->data['post_type'];
        if($post_type != News::class){
            return;
        }
        /** @var Builder $query */
        $query = $event->data['posts_query'];
        $posts = $query->where('listable', 1)->with(['cover'])->paginate(10);
        $event->data['posts'] = $posts;
    }
}