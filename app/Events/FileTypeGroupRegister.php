<?php

namespace App\Events;

use App\Classes\FileTypeGroup;
use function GuzzleHttp\Psr7\str;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FileTypeGroupRegister
{
    use Dispatchable, SerializesModels;

    private $groups;
    private $after_register;

    public function __construct()
    {
        $this->groups = collect();
        $this->after_register = [];
        $list = [];
        $image = getSetting(
            'file_group_image',
            implode(
                PHP_EOL,
                [
                    'jpg',
                    'jpeg',
                    'gif',
                    'png',
                    'psd',
                    'ai'
                ]
            )
        );
        $list['image'] = [
            __('Hình ảnh'),
            $image
        ];
        $text = getSetting(
            'file_group_text',
            implode(
                PHP_EOL,
                [
                    'txt',
                    'css',
                    'js',
                    'doc',
                    'docx',
                    'rtf',
                    'xls'
                ]
            )
        );
        $list['text'] = [
            __('Văn bản'),
            $text
        ];
        $media = getSetting(
            'file_group_media',
            implode(
                PHP_EOL,
                [
                    'aac',
                    'oga',
                    'wav',
                    'weba',
                    'mp3',
                    'mp4',
                    'wmv',
                    'avi',
                    'asf',
                    'mkv',
                    'mov'
                ]
            )
        );
        $list['media'] = [
            __('Đa phương tiện'),
            $media
        ];
        $compressed = getSetting(
            'file_group_compressed',
            implode(
                PHP_EOL,
                [
                    'zip',
                    'rar',
                    '7z',
                    'bz',
                    'bz2'
                ]
            )
        );
        $list['compressed'] = [
            __('File nén'),
            $compressed
        ];

        foreach ($list as $k=>$item){
            $exts_ = explode(
                PHP_EOL,
                $item[1]);
            $exts = [];
            foreach ($exts_ as $e){
                $e = str_replace(
                    "\r",
                    '',
                    $e);
                $e = str_replace(
                    "\n",
                    '',
                    $e);
                if(trim($e)){
                    $exts[] = mb_strtolower($e);
                }
            }
            $exts = array_unique($exts);
            $this->register(
                new FileTypeGroup(
                    $k,
                    $item[0],
                    $exts
                )
            );
        }

//        $this->register(
//            new FileTypeGroup(
//                'image',
//                __('Hình ảnh'),
//                [
//                    'jpg',
//                    'jpeg',
//                    'gif',
//                    'png',
//                    'psd',
//                    'ai'
//                ]
//            )
//        );
//
//        $this->register(
//            new FileTypeGroup(
//                'text',
//                __('Văn bản'),
//                [
//                    'txt',
//                    'css',
//                    'js',
//                    'doc',
//                    'docx',
//                    'rtf',
//                    'xls'
//                ]
//            )
//        );
//
//        $this->register(
//            new FileTypeGroup(
//                'media',
//                __('Âm thanh'),
//                [
//                    'aac',
//                    'oga',
//                    'wav',
//                    'weba',
//                    'mp3'
//                ]
//            )
//        );
//
//        $this->register(
//            new FileTypeGroup(
//                'compressed',
//                __('File nén'),
//                [
//                    'zip',
//                    'rar',
//                    '7z',
//                    'bz',
//                    'bz2'
//                ]
//            )
//        );
    }

    public function do_after_register()
    {
        foreach ($this->after_register as $func) {
            $func($this);
        }
    }

    public function hook_after_register(\Closure $function)
    {
        $this->after_register[] = $function;
    }

    public function getGroupTile(
        $group_id,
        $default = null
    ) {
        return $this->groups->get(
            $group_id,
            $default
        );
    }

    public function hasGroup($group_id)
    {
        return $this->groups->has($group_id);
    }

    public function register(FileTypeGroup $group)
    {
        if ($this->groups->has($group->getId())) return;
        $this->groups->put(
            $group->getId(),
            $group
        );
    }

    /**
     * @return \Illuminate\Support\Collection|FileTypeGroup[]
     */
    public function getGroups(): \Illuminate\Support\Collection
    {
        return $this->groups;
    }

}
