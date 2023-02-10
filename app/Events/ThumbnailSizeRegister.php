<?php

namespace App\Events;

use App\Classes\ThumbnailSize;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Image;

class ThumbnailSizeRegister
{
    use Dispatchable, SerializesModels;

    private $sizes;
    private $after_register;

    /**
     * @return \Illuminate\Support\Collection|ThumbnailSize[]
     */
    public function getSizes()
    {
        return $this->sizes;
    }

    /**
     * @param $size_id
     * @param null $default
     * @return mixed|ThumbnailSize
     */
    public function getSize(
        $size_id,
        $default = null
    ) {
        return $this->sizes->get(
            $size_id,
            $default
        );
    }

    public function hasSize($size_id)
    {
        return $this->sizes->has($size_id);
    }

    public function register(ThumbnailSize $size)
    {
        if ($this->sizes->has($size->getId())) {
            return;
        }
        $this->sizes->put(
            $size->getId(),
            $size
        );
    }

    public function hook_after_register(\Closure $function)
    {
        $this->after_register[] = $function;
    }

    public function __construct()
    {
        $this->sizes = collect();
        $this->after_register = [];
        $this->register(
            new ThumbnailSize(
                config('app.default_thumbnail_name'),
                __('Thumbnail mặc định'),
                150,
                150,
                function (
                    Image $img,
                    $width,
                    $height
                ) {
                    $img->fit(
                        $width,
                        $height
                    );
                    return $img;
                }
            )
        );
    }

    public function do_after_register()
    {
        foreach ($this->after_register as $func) {
            $func($this);
        }
    }
}
