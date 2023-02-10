<?php

namespace App\Classes;


use App\Events\Comment\CommentCreated;
use App\Events\Comment\CommentCreating;
use App\Events\Comment\CommentDeleted;
use App\Events\Comment\CommentDeleting;
use App\Events\Comment\CommentRetrieved;
use App\Events\Comment\CommentSaved;
use App\Events\Comment\CommentSaving;
use App\Events\Comment\CommentUpdated;
use App\Events\Comment\CommentUpdating;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

/**
 * App\Classes\Comment
 * @property int $id
 * @property string $name
 * @property string $email
 * @property int $target_id
 * @property int $parent_id
 * @property int $user_id
 * @property string $ip
 * @property bool $published
 * @property bool $checked
 * @property string $content
 * @property-read  PostType $post
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read User|null $user
 * @mixin \Eloquent
 */
abstract class Comment extends Model
{
    use Notifiable;

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $dispatchesEvents = [
        'retrieved' => CommentRetrieved::class,
        'creating' => CommentCreating::class,
        'created' => CommentCreated::class,
        'updating' => CommentUpdating::class,
        'updated' => CommentUpdated::class,
        'saving' => CommentSaving::class,
        'saved' => CommentSaved::class,
        'deleting' => CommentDeleting::class,
        'deleted' => CommentDeleted::class,
    ];

    abstract public static function getMenuTitle():string;
    abstract public static function getSingular():string;
    abstract public static function getPlural():string;
    abstract public static function getMenuIcon():string;
    abstract public static function getMenuOrder():int;
    abstract public static function getPostType():string;
    abstract public static function getDBTable(): string ;

    function post(){
        /** @var Comment $comment_type */
        $comment_type = get_called_class();
        $post_type = $comment_type::getPostType();
        if(!$comment_type){
            return null;
        }
        return $this->belongsTo($post_type,'target_id','id');
    }

    function user(){
        /** @var Comment $comment_type */
        $comment_type = get_called_class();
        if(!$comment_type){
            return null;
        }
        return $this->belongsTo(User::class,'user_id','id');
    }

    function parent(){
        /** @var Comment $comment_type */
        $comment_type = get_called_class();
        if(!$comment_type){
            return null;
        }
        return $this->belongsTo($comment_type,'parent_id','id');
    }

    function children(){
        /** @var Comment $comment_type */
        $comment_type = get_called_class();
        if(!$comment_type){
            return null;
        }
        return $this->hasMany($comment_type,'parent_id', 'id');
    }

    public function getTable()
    {
        /** @var PostType $class */
        $class = get_called_class();
        return $class::getDBTable();
    }

    public static function getSlug(){
        /** @var Comment $class */
        $class = get_called_class();
        /** @var PostType $post_type */
        $post_type = $class::getPostType();
        return 'comment-'.$post_type::getTypeSlug();
    }

    public static function getMenuSlug(){
        /** @var Comment $class */
        $class = get_called_class();
        return $class::getSlug();
    }

    public static function getManagePermissionID(){
        /** @var Comment $class */
        $class = get_called_class();
        return 'manage_'.$class::getSlug();
    }

    public static function getManagePermission(){
        /** @var Comment $class */
        $class = get_called_class();
        /** @var PostType $post_type */
        $post_type = $class::getPostType();
        return new Permission(
            $class::getManagePermissionID(),
            __('Quản lý :comment_type', ['comment_type'=>mb_strtolower( $class::getSingular())]),
            $post_type::getPermissionGroupID(),
            100
        );
    }

    public static function getPublicCommentQueryByID($target_id){
        /** @var Comment $class */
        $class = get_called_class();
        $query = $class::where('target_id', '=', $target_id)->where('published', '=', 1);
        return $query;
    }

    /**
     * @param User|null $user
     * @param PostType $post
     * @return array
     */
    public static function isAllowComment($user, $post){
        return [
            'allow' => 1,
            'message' => ''
        ];
    }

    public static function getCommentDefaultStatus($user){
        return 1;
    }
}