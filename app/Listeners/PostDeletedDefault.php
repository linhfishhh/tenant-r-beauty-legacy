<?php

namespace App\Listeners;

use App\Classes\Comment;
use App\Events\PostType\PostDeleted;
use Illuminate\Database\Eloquent\Collection;

class PostDeletedDefault
{
	public function handle(PostDeleted $event)
    {
		$post = $event->model;
		if($post->isForceDeleting()){
            foreach ($post::getTaxonomies() as $taxonomy=>$rel){
                $post->removeTerms( $taxonomy);
            }
            if($post::getCommentType()){
                /** @var Comment $comment_type */
                $comment_type = $post::getCommentType();
                /** @var Comment[]|Collection $comments */
                $comments = $comment_type::where('target_id', '=', $post->id)->get();
                foreach ($comments as $comment){
                    $comment->delete();
                }
            }
        }
    }
}
