<?php

namespace App\Listeners;

use App\Events\UploadedFile\UploadedFileDeleted;

class UploadedFileDeletedDefault
{
    public function handle(UploadedFileDeleted $event)
    {
        $file = $event->model->getUploadFilePath();
        \File::delete(public_path($file));
        $event->model->deleteThumbnails();
    }
}
