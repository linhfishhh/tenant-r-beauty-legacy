<?php

namespace App\Events\UploadedFile;

use App\UploadedFile;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UploadedFileBase
{
    use Dispatchable, SerializesModels;

    /** @var UploadedFile $model */
    public $model;

    public function __construct($model)
    {
        $this->model = $model;
    }
}
