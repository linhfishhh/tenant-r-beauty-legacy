<?php

namespace App\Events\PostType;

use App\Classes\PostType;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostUpdating
{
	use Dispatchable, SerializesModels;

	/** @var PostType $model */
	public $model;
	public function __construct($model)
	{
		$this->model = $model;
	}
}
