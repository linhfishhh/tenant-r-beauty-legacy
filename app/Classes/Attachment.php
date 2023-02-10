<?php
/**
 * Created by PhpStorm.
 * User: CLARENCE
 * Date: 17-Apr-18
 * Time: 10:14
 */

namespace App\Classes;


use App\UploadedFile;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Classes\Attachment
 * @property int $id
 * @property int $target_id
 * @property int $file_id
 * @property string $type
 * @property-read UploadedFile|null $file
 * @property-read PostType|null $post
 */
abstract class Attachment extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    function file(){
        return $this->hasOne(UploadedFile::class,'id', 'file_id');
    }

    function post(){
        /** @var Attachment $class */
        $class= get_called_class();
        return $this->hasOne($class::getPostType(), 'id', 'target_id');
    }

    abstract public static function getDBTable(): string ;
    abstract public static function getPublicSlug(): string ;
    abstract public static function getPostType():string;

    public static function getRouteName(){
        /** @var Attachment $class */
        $class= get_called_class();
        /** @var PostType $post_type */
        $post_type = $class::getPostType();
        return 'frontend.post.attachment.'.$post_type::getTypeSlug();
    }

    public function getDownloadLink(){
        return route($this::getRouteName(), ['attachment' => $this->id]);
    }

    function isDownloadable(){
        if(!$this->post){
            return false;
        }
        return $this->post->checkAttachmentDownload($this);
    }

    public function getTable()
    {
        /** @var PostType $class */
        $class = get_called_class();
        return $class::getDBTable();
    }
}