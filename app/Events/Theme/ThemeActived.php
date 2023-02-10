<?php

namespace App\Events\Theme;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Nwidart\Modules\Laravel\Module;

class ThemeActived
{
    use Dispatchable, SerializesModels;

    /** @var Module $theme */
    public $theme;

    public function __construct($theme)
    {
        $this->theme = $theme;
    }
}
