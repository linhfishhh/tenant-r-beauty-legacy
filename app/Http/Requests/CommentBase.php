<?php

namespace App\Http\Requests;

use App\Classes\Comment;
use App\Classes\FormRequestExtended;
use App\Classes\PostType;
use Illuminate\Auth\Access\AuthorizationException;

class CommentBase extends FormRequestExtended
{
    public function authorize()
    {
        /** @var PostType $post_type */
        $post_type = $this->route()->parameter('post_type');
        /** @var Comment $comment_type */
        $comment_type = $post_type::getCommentType();
        if(!me()->hasPermission($comment_type::getManagePermissionID())){
            return new AuthorizationException('');
        }
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
