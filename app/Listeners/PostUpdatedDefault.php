<?php

namespace App\Listeners;

use App\Events\PostType\PostUpdated;

class PostUpdatedDefault
{
    public function handle(PostUpdated $event)
    {
    	$post = $event->model;
    	if($post->isDirty('language')){
		    foreach ($event->model::getTaxonomies() as $taxonomy=>$rel){
				$post->removeTerms( $taxonomy);
		    }
	    }
    }
}
