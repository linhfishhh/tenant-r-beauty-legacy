<?php

namespace App\Events\Taxonomy;

use App\Events\BaseRegisterEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TermDetailQuery extends BaseRegisterEvent{
    use Dispatchable, SerializesModels;

    /** @var Builder $query */
    public $query;
    public function __construct($query)
    {
        parent::__construct();
        $this->query = $query;
    }
}