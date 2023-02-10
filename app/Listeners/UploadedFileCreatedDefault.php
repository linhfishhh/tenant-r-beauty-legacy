<?php

namespace App\Listeners;

use App\Events\ThumbnailSizeRegister;
use App\Events\UploadedFile\UploadedFileCreated;

class UploadedFileCreatedDefault
{
    public function handle(UploadedFileCreated $event)
    {
        $event->model->generateThumbnails();
    }
}
