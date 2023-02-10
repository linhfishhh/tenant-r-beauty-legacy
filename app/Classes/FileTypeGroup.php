<?php


namespace App\Classes;


class FileTypeGroup {
	private $id;
	private $title;
	private $extensions;

	/**
	 * FileTypeGroup constructor.
	 *
	 * @param string id
	 * @param string $title
	 * @param array $extensions
	 */
	public function __construct( $id, $title, $extensions ) {
		$this->id         = $id;
		$this->title      = $title;
		$this->extensions = $extensions;
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
	 * @return array
	 */
	public function getExtensions() {
		return $this->extensions;
	}

	public function addExtension($extensions){
		$this->extensions = array_merge( $this->extensions, $extensions);
	}
}