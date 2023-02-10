<?php

namespace App;

use App\Events\ThumbnailSizeRegister;
use App\Events\UploadedFile\UploadedFileCreated;
use App\Events\UploadedFile\UploadedFileCreating;
use App\Events\UploadedFile\UploadedFileDeleted;
use App\Events\UploadedFile\UploadedFileDeleting;
use App\Events\UploadedFile\UploadedFileRetrieved;
use App\Events\UploadedFile\UploadedFileSaved;
use App\Events\UploadedFile\UploadedFileSaving;
use App\Events\UploadedFile\UploadedFileUpdated;
use App\Events\UploadedFile\UploadedFileUpdating;
use File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * App\UploadedFile
 *
 * @property int $id
 * @property string $name
 * @property string $path
 * @property string $mime
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UploadedFile whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UploadedFile whereMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UploadedFile whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UploadedFile wherePath($value)
 * @mixin \Eloquent
 * @property string $extension
 * @property int $size
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UploadedFile whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UploadedFile whereExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UploadedFile whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UploadedFile whereUpdatedAt($value)
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UploadedFile whereUserId($value)
 * @property string $category
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UploadedFile whereCategory($value)
 * @property-read User|null $user
 */
class UploadedFile extends Model
{
	use Notifiable;

	protected $dates = [
		'created_at',
		'updated_at',
	];

    protected $dispatchesEvents = [
        'retrieved' => UploadedFileRetrieved::class,
        'creating' => UploadedFileCreating::class,
        'created' => UploadedFileCreated::class,
        'updating' => UploadedFileUpdating::class,
        'updated' => UploadedFileUpdated::class,
        'saving' => UploadedFileSaving::class,
        'saved' => UploadedFileSaved::class,
        'deleting' => UploadedFileDeleting::class,
        'deleted' => UploadedFileDeleted::class,
    ];

    public function user(){
        return $this->belongsTo(
            User::class,
            'user_id',
            'id');
    }

    public function canGenerateThumbnails(){
        return in_array($this->extension, config('app.thumbnail_support_files'));
    }


    public function generateThumbnails(){
        if(!$this->canGenerateThumbnails()){
            return false;
        }
        /** @var ThumbnailSizeRegister $sizes */
        $sizes = app('thumbnail_sizes');
        foreach ($sizes->getSizes() as $thumbnail_id=>$size){
            $this->generateThumbnail($thumbnail_id);
        }
        return true;
    }

    public function generateThumbnail($thumbnail_id){
        if(!$this->canGenerateThumbnails()){
            return false;
        }
        /** @var ThumbnailSizeRegister $sizes */
        $sizes = app('thumbnail_sizes');
        $size = $sizes->getSize($thumbnail_id, null);
        if(!$size){
            return false;
        }

        $thumbnail_path = public_path($this->getThumbnailDirPath());
        $thumbnail_file_path = public_path($this->getThumbnailFilePath($thumbnail_id));
        if(file_exists($thumbnail_file_path)){
            File::delete($thumbnail_file_path);
        }
        if(!is_dir( $thumbnail_path)){
            mkdir( $thumbnail_path,0777,true);
        }
        // new \Exception(public_path($this->getUploadFilePath()));
        $thumb = $size->resize( public_path($this->getUploadFilePath()));
        $thumb->save($thumbnail_file_path);
        return true;
    }

    public function deleteThumbnails(){
        $target = public_path($this->getThumbnailDirPath());
        if(is_dir($target)){
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $fileinfo) {
                $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
                $todo($fileinfo->getRealPath());
            }

            rmdir($target);
        }
    }

	public function getUploadDirPath(){
		$upload_path = config('app.upload_dir');
		$upload_path = $upload_path .'/'.$this->path;
		return $upload_path;
	}

	public function getUploadFilePath(){
	    return $this->getUploadDirPath().'/'.$this->name.'.'.$this->extension;
    }

	public function getThumbnailDirPath(){
		return $this->getUploadDirPath() . '/thumbs/'.str_slug($this->name.'.'.$this->extension);
	}

	public function getThumbnailFilename($thumbnail_id){
		return $thumbnail_id.'.'.$this->extension;
	}

	public function getThumbnailFilePath($thumbnail_id){
		return $this->getThumbnailDirPath().'/'.$this->getThumbnailFilename($thumbnail_id);
	}

	public function hasThumbnail($thumbnail_id){
		$event = app('thumbnail_sizes');
		if(!$event->hasSize( $thumbnail_id)){
			return false;
		}
		$thumbnail_file_path = $this->getThumbnailFilePath( $thumbnail_id);
		return file_exists( public_path($thumbnail_file_path));
	}

	public function getUrl(){
        return url($this->getUploadFilePath()) ;
    }

	public function getThumbnailUrl($thumbnail_id, $no_thumbnail_url = null){
		if(!$no_thumbnail_url){
            $no_thumb = getNoThumbnailUrl();
        }
        else{
            $no_thumb = $no_thumbnail_url;
        }
		if(!in_array( $this->extension, config('app.thumbnail_support_files'))){
			return $no_thumb;
		}
		if(!$this->hasThumbnail( $thumbnail_id)){
			return $no_thumb;
		}

		return url($this->getThumbnailFilePath( $thumbnail_id));
	}

	public static function upload(\Illuminate\Http\UploadedFile $file, $owned_by = null, $category, $overwrite_filename = ''){
        $upload_dir = config('app.upload_dir');
        $path = date('Y/m');
        $upload_path = $upload_dir.'/'.$path;
        $upload_file_path_tmp = $upload_path;
        if($overwrite_filename){
            $file_name = $overwrite_filename;
        }
        else{
            $file_name = mb_strtolower($file->getClientOriginalName());
        }
        $info = pathinfo($upload_file_path_tmp.'/'.$file_name);
        $c = 0;
        $file_name_no_ext = $info['filename'];
        $ext = $info['extension'];
        if(strtolower($ext) == 'heic' || strtolower($ext) == 'heif'){
            $ext = 'jpg';
        }
        $file_name = $file_name_no_ext.'.'.$ext;
        while(file_exists( public_path($upload_file_path_tmp.'/'.$file_name))){
            $c++;
            $file_name = $info['filename'].'_'.$c.'.'.$ext;
            $file_name_no_ext = $info['filename'].'_'.$c;
        }
        $upload_file_path = public_path($upload_file_path_tmp);
        $file->move( $upload_file_path,$file_name);
        $file_upload = new UploadedFile();
        $file_upload->name = $file_name_no_ext;
        $file_upload->extension = $ext;
        $file_upload->mime = $file->getClientMimeType();
        $file_upload->path = $path;
        $file_upload->size = $file->getClientSize();
        $owned = $owned_by;
        $owned = intval($owned);
        if($owned){
            $file_upload->user_id = $owned;
        }
        else{
            $file_upload->user_id = me()->id;
        }
        $file_upload->category = $category;
        $file_upload->save();
        return $file_upload;
    }
}
