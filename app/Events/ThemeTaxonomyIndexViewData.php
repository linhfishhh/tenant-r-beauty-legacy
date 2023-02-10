<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ThemeTaxonomyIndexViewData
{
    use Dispatchable, SerializesModels;

    public $data;
    public $request;

    public function __construct($request, $data = [])
    {
        $this->data = $data;
        $this->request = $request;
    }
}
