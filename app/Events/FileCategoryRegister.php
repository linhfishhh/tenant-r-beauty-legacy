<?php

namespace App\Events;

use App\Classes\PostType;
use App\Classes\Taxonomy;
use App\User;

class FileCategoryRegister extends BaseRegisterEvent
{
    public $categories;

    public function __construct()
    {
        parent::__construct();
        $this->categories = collect();
        /** @var DefineContent $post_type_events */
        $post_type_events = app('post_types');
        /** @var PostType[] $post_types */
        $post_types = $post_type_events->getPostTypes();
        foreach ($post_types as $post_type){
            $file_cat_ids = $post_type::getFileCatIDS();
            foreach ($file_cat_ids as $cat_id=>$title){
                $this->register($cat_id, $title);
            }
            $taxonomies = $post_type::getTaxonomies();
            /**
             * @var Taxonomy $taxonomy
             */
            foreach ($taxonomies as $taxonomy=>$rel){
                $file_cat_ids = $taxonomy::getFileCatIDS();
                foreach ($file_cat_ids as $cat_id=>$title){
                    $this->register($cat_id, $title);
                }
            }
        }
        $this->register(User::getFileID(), __('Avatar người dùng'));
        $this->register('uncategorized', __('Chưa phân loại'));
    }

    public function register($cat_id, $cat_title){
        if($this->categories->has($cat_id)){
            return;
        }
        $this->categories->put($cat_id, $cat_title);
    }

    public function getCategoryIds(){
        $rs = [];
        foreach ($this->categories as $cat_id=>$cat_title){
            $rs[] = $cat_id;
        }
        return $rs;
    }

}
