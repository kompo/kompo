<?php

namespace Kompo\Tests\Unit\Komponent;

use Kompo\DateRange;
use Kompo\Form;
use Kompo\Tests\Models\Post;

class _DateRangeForm extends Form
{
	public $model = Post::class;

	public function components()
	{
		return [
			DateRange::form('DateRange')->name([
				'created_at', //just for simulation
				'updated_at' //just for simulation
			])
		];
	}
}