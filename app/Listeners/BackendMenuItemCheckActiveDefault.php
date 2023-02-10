<?php

namespace App\Listeners;

use App\Classes\Taxonomy;
use App\Events\BackendMenuItemCheckActive;

class BackendMenuItemCheckActiveDefault
{

    public function handle(BackendMenuItemCheckActive $event)
    {

	    $post_types = getPostTypes();
	    foreach ($post_types as $post_type){
	    	if($event->item_id == $post_type::getMenuIndexSlug()){
			    $event->include_routes[] = [
				    'backend.post.create',
				    [
					    'post_type' => $post_type::getTypeSlug(),
				    ],
				    [
					    'post_type' => $post_type,
				    ],
			    ];
			    $event->include_routes[] = [
				    'backend.post.edit',
				    [
					    'post_type' => $post_type::getTypeSlug(),
				    ],
				    [
					    'post_type' => $post_type,
				    ],
			    ];
		    }
		    $taxs = $post_type::getTaxonomies();
		    foreach ($taxs as $taxonomy=>$rel){
		    	/** @var Taxonomy $taxonomy */
				if($event->item_id == $taxonomy::getMenuSlug()){

					$event->include_routes[] = [
						'backend.taxonomy.create',
						[
							'post_type' => $post_type::getTypeSlug(),
							'taxonomy' => $taxonomy::getTaxSlug()
						],
						[
							'post_type' => $post_type,
							'taxonomy' => $taxonomy
						],
					];
					$event->include_routes[] = [
						'backend.taxonomy.edit',
						[
							'post_type' => $post_type::getTypeSlug(),
							'taxonomy' => $taxonomy::getTaxSlug()
						],
						[
							'post_type' => $post_type,
							'taxonomy' => $taxonomy
						],
					];
				}
		    }
	    }

        if($event->item_id == 'user.users'){
            $event->include_routes[] = 'backend.user.create';
            $event->include_routes[] = 'backend.user.edit';
        }

        if($event->item_id == 'user.roles'){
            $event->include_routes[] = 'backend.role.create';
            $event->include_routes[] = 'backend.role.edit';
        }

        if($event->item_id == 'frontend.nav.library'){
            $event->include_routes[] = 'backend.menu.create';
            $event->include_routes[] = 'backend.menu.edit';
        }

	    if($event->item_id == 'frontend.sidebar.library'){
		    $event->include_routes[] = 'backend.sidebar.create';
		    $event->include_routes[] = 'backend.sidebar.edit';
	    }
    }
}
