<?php


namespace App\Classes;


class ThumbnailSize {
	private $id;
	private $title;
	private $width;
	private $height;
	private $action;

	/**
	 * @return \Closure
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * ThumbnailSize constructor.
	 *
	 * @param string $id
	 * @param string $title
	 * @param int $width
	 * @param int $height
	 * @param \Closure $action
	 */
	public function __construct( $id, $title, $width, $height, $action) {
		$this->id     = $id;
		$this->title  = $title;
		$this->width  = $width;
		$this->height = $height;
		$this->action = $action;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle() {
		return $this->title;
	}

	/**
	 * @return int|null
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * @return int|null
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @param $file_path
	 *
	 * @return \Intervention\Image\Image|mixed
	 */
	public function resize($file_path){
		$img = \Image::make( $file_path);
		$action = $this->action;
		$img = $action($img, $this->width, $this->height);
		return $img;
	}
}