<?php

namespace App\Events;

class TinyMCEScriptHook extends BaseRegisterEvent
{
    public $scripts;
    public $body_classes;

    public function addScript($id, $script_urls = []){
        if(!$this->scripts->has($id)){
            $this->scripts->put(
                $id,
                $script_urls);
        }
        else{
            $scs = $this->body_classes->get($id);
            $scs = array_merge($scs, $script_urls);
            $scs = array_unique($scs);
            $this->scripts->put(
                $id,
                $scs);
        }
    }

    public function addBodyClass($id, $classes = []){
        if(!$this->body_classes->has($id)){
            $this->body_classes->put(
                $id,
                $classes);
        }
        else{
            $scs = $this->body_classes->get($id);
            $scs = array_merge($scs, $classes);
            $scs = array_unique($scs);
            $this->scripts->set(
                $id,
                $scs);
        }
    }

    public function __construct()
    {
        parent::__construct();
        $this->scripts = collect();
        $this->body_classes = collect();
    }

    public function getScriptFor($id){
        return $this->scripts->get($id, []);
    }

    public function getBodyClassFor($id){
        return $this->body_classes->get($id, []);
    }
}
