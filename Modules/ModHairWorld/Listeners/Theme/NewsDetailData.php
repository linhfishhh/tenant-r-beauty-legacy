<?php
/**
 * Created by PhpStorm.
 * User: TRANG
 * Date: 19-Jun-18
 * Time: 15:56
 */

namespace Modules\ModHairWorld\Listeners\Theme;


use App\Events\ThemePostViewData;
use App\UploadedFile;
use Modules\ModHairWorld\Entities\PostTypes\News;

class NewsDetailData
{
    function handle(ThemePostViewData $event){
        /** @var News $post_type */
        $post_type = $event->data['post_type'];
        if($post_type != News::class){
            return;
        }
        /** @var News $post */
        $post = $event->data['post'];
        if($post->content_type == 1){
            $content_blocks = json_decode($post->content_1, true);
            $imgs = [];
            if(!$content_blocks){
                $content_blocks = [];
            }
            foreach ($content_blocks as $k=>$block){
                $imgs[$block['cover_id']] = getNoThumbnailUrl();
            }
            /** @var UploadedFile[] $covers */
            $covers = UploadedFile::whereIn('id', array_keys($imgs))->get();
            foreach ($covers as $cover){
                $thumb = $cover->getUrl();
                $imgs[$cover->id] = $thumb;
            }

            foreach ($content_blocks as $k=>$block){
                $content_blocks[$k]['cover'] = $imgs[$block['cover_id']];
            }
            $content_blocks = array_values($content_blocks);
        }
        else{
            $content_blocks = [];
            $cover_ids = json_decode($post->content_2, true);
            if($cover_ids){
                $covers = UploadedFile::whereIn('id', $cover_ids)->get();
                $content_blocks = $covers;

            }

        }
        $event->data['content_blocks'] = $content_blocks;
    }
}