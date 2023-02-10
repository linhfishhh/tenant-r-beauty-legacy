<?php

namespace App\Events;

use App\Classes\PostTaxRel;
use App\Classes\PostType;
use App\Classes\Taxonomy;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DefineContent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /** @var array|PostType[] */
    private $post_types;
	private $after_register;

    /**
     * @return array|PostType[]
     */
    public function getPostTypes(): array
    {
        return $this->post_types;
    }

	/**
	 * @param PostType|string $post_type_class
	 */
	public function registerPostType($post_type_class){
        if(!in_array($post_type_class, $this->post_types)){
            $this->post_types[] = $post_type_class;
        }
    }

    public function hasPostType($slug){
    	$rs = false;
    	foreach ($this->post_types as $post_type){
    		if($post_type::getTypeSlug() == $slug){
    			$rs = true;
    			break;
		    }
	    }
	    return $rs;
    }

    public function getPostTypeBySlug($slug){
    	$rs = null;
	    foreach ($this->post_types as $post_type){
		    if($post_type::getTypeSlug() == $slug){
			    $rs = $post_type;
			    break;
		    }
	    }
    	return $rs;
    }

    function getPostTypeByPublicSlug($slug){
        $slug = mb_strtolower($slug);
        $rs = null;
        /** @var \App\Classes\PostType $post_type */
        foreach ($this->post_types as $post_type){
            if(mb_strtolower($post_type::getPublicSlug()) == $slug){
                $rs = $post_type;
                break;
            }
        }
        return $rs;
    }

    public function getTaxonomyByPublicSlug($post_type_slug, $tax_slug, $with_rel = false){
        $rs = null;
        $post_type_slug = mb_strtolower($post_type_slug);
        $tax_slug = mb_strtolower($tax_slug);
        $post_type = $this->getPostTypeByPublicSlug( $post_type_slug);
        if($post_type){
            $taxs = $post_type::getTaxonomies();
            foreach ($taxs as $tax=>$rel){
                /** @var Taxonomy $tax */
                if(mb_strtolower($tax::getPublicSlug()) == $tax_slug){
                    if($with_rel){
                        $rs = [
                            'taxonomy' => $tax,
                            'relationship' => $rel
                        ];
                    }
                    else{
                        $rs = $tax;
                    }
                    break;
                }
            }
        }
        return $rs;
    }

    public function getTaxonomyBySlug($post_type_slug, $tax_slug, $with_rel = false){
	    $rs = null;
	    $post_type = $this->getPostTypeBySlug( $post_type_slug);
	    if($post_type){
		    $taxs = $post_type::getTaxonomies();
		    foreach ($taxs as $tax=>$rel){
			    /** @var Taxonomy $tax */
			    if($tax::getTaxSlug() == $tax_slug){
				    if($with_rel){
				    	$rs = [
				    	    'taxonomy' => $tax,
						    'relationship' => $rel
					    ];
				    }
				    else{
					    $rs = $tax;
				    }
				    break;
			    }
		    }
	    }
	    return $rs;
    }

    public function getPostTypePublicSlugs(){
	    $rs = [];
	    /** @var PostType $type */
        foreach ($this->post_types as $type){
	        if($type::isPublic()){
	            $rs[] = $type::getPublicSlug();
            }
        }
	    return $rs;
    }

    public function hasTaxonomy($post_type_slug, $tax_slug){
    	/** @var PostType $post_type_slug */
    	$rs = false;
    	if($this->hasPostType( $post_type_slug)){
    		$post_type = $this->getPostTypeBySlug( $post_type_slug);
    		$taxs = $post_type::getTaxonomies();
    		foreach ($taxs as $tax=>$rel){
			    /** @var Taxonomy $tax */
			    if($tax::getTaxSlug() == $tax_slug){
			    	$rs = true;
			    	break;
			    }
		    }
	    }
    	return $rs;
    }

	public function hook_after_register(\Closure $function){
		$this->after_register[] = $function;
	}

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->post_types = [];
	    $this->after_register = [];
    }

	public function do_after_register(){
		foreach ($this->after_register as $func){
			$func($this);
		}
	}

}
