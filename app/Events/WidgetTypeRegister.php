<?php

namespace App\Events;

use App\Classes\WidgetType;
use App\Classes\WidgetTypeGroup;

class WidgetTypeRegister extends MenuTypeRegister
{
	/** @var \Illuminate\Support\Collection|WidgetType[] $types */
	protected $types;
	/** @var \Illuminate\Support\Collection|WidgetTypeGroup[] $groups */
	protected $groups;
}
