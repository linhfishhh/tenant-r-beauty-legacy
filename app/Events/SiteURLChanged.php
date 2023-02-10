<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SiteURLChanged extends BaseRegisterEvent
{
    use Dispatchable, SerializesModels;

    public $old_site_url;
    public $new_site_url;
    private $changers;
    public function __construct($old, $new)
    {
        parent::__construct();
        $this->old_site_url = $old;
        $this->new_site_url = $new;
        $this->changers = [];
    }

    public function registerDBChanger($table, $column_name){
        $this->changers[$table][] = $column_name;
        $this->changers[$table] = array_unique($this->changers[$table]);
    }

    function getChangers(){
         return $this->changers;
    }

    function getChangerByTable($table){
        if(isset($this->changers[$table])){
            return $this->changers[$table];
        }
        else{
            return false;
        }
    }
}
