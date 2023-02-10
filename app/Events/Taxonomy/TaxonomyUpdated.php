<?php

namespace App\Events\Taxonomy;

use App\Classes\Taxonomy;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaxonomyUpdated
{
	use Dispatchable, SerializesModels;

	/** @var Taxonomy $model */
	public $model;
	public function __construct($model)
	{
		$this->model = $model;
	}
}
