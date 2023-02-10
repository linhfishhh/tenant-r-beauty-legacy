<?php

namespace App\Events\Comment;

use App\Classes\Comment;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CommentBaseEvent
{
    use Dispatchable, SerializesModels;

    /** @var Comment $model */
    public $model;
    public function __construct($model)
    {
        $this->model = $model;
    }
}
