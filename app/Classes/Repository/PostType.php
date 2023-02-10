<?php

namespace App\Classes\Repository;


class PostType {
	private $post_type;
	public function __construct($post_type) {
		$this->post_type = getPostType( $post_type);
	}
}