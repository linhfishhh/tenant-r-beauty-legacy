<?php

namespace App\Listeners;

use App\Events\Taxonomy\TaxonomyUpdated;

class TaxonomyUpdatedDefault
{
    public function handle(TaxonomyUpdated $event)
    {
    	if($event->model->isDirty('language')){
		    $event->model->relations()->delete();
	    }
		$children =  $event->model->children;
		foreach ($children as $child){
			$child->language = $event->model->language;
			$child->save();
		}
    }
}
