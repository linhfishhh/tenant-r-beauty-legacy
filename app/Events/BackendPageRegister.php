<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BackendPageRegister extends BaseRegisterEvent
{
    use Dispatchable, SerializesModels;

    public $pages;
    public function __construct()
    {
        parent::__construct();
        $this->pages = collect();
    }

    public function register($page_class){
        if($this->pages->has($page_class)){
            return;
        }
        $this->pages->put(
            $page_class,
            $page_class);
    }
}
