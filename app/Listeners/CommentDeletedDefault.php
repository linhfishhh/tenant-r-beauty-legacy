<?php

namespace App\Listeners;

use App\Classes\Comment;
use App\Events\Comment\CommentDeleted;

class CommentDeletedDefault
{
    public function handle(CommentDeleted $event)
    {
        /** @var Comment $model */
        $model = $event->model;
        $parent_id = $model->parent_id;
        $model->children()->update([
            'parent_id' => $parent_id
        ]);
    }
}
