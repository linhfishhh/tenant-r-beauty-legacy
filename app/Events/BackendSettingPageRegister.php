<?php

namespace App\Events;

use App\Classes\BackendSettingPage;

class BackendSettingPageRegister extends BaseRegisterEvent{
    protected $pages;
    public function __construct()
    {
        parent::__construct();
        $this->pages = collect();
    }

    /**
     * @param string $page_class_name
     */
    public function register($page_class_name){
        if(!class_exists($page_class_name)){
            return;
        }
        /** @var BackendSettingPage $page */
        $page = new $page_class_name();
        if(!$this->pages->has($page->getSlug())){
            $this->pages->put($page->getSlug(), $page);
        }
    }

    public function getPage($page_slug, $default = null){
        return $this->pages->get($page_slug,$default);
    }

    public function unRegisterPage($page_slug){
        $this->pages->forget($page_slug);
    }

    /**
     * @return \Illuminate\Support\Collection|BackendSettingPage[]
     */
    public function getPages(){
        return $this->pages;
    }
}
