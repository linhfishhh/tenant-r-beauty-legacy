<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BaseRegisterEvent
{
    use Dispatchable, SerializesModels;

    private $after_register;

    public function __construct()
    {
        $this->after_register = [];
    }

    public function do_after_register(){
        foreach ($this->after_register as $func){
            $func($this);
        }
    }

    public function hook_after_register(\Closure $function){
        $this->after_register[] = $function;
    }
}
