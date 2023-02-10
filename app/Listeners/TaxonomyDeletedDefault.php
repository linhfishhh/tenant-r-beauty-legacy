<?php

namespace App\Listeners;

use App\Events\Taxonomy\TaxonomyDeleted;

class TaxonomyDeletedDefault
{
    public function handle(TaxonomyDeleted $event)
    {
		$parent_id = $event->model->parent_id;
		$children = $event->model->children;
		foreach ($children as $child){
			$child->parent_id = $parent_id;
			$child->save();
		}
		$event->model->relations()->delete();
    }
}
