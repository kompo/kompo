<?php

namespace Kompo\Tests\Feature\DateRange;

use Kompo\Form;
use Kompo\DateRange;
use Kompo\Tests\Models\Post;

class _DateRangeRelationForm extends Form
{
	public $model = Post::class;

	public function komponents()
	{
		return [
			DateRange::form('A')->name([
				'obj.start_date',
				'obj.end_date'
			]),
			DateRange::form('A')->name([
				'obj.start_datetime',
				'obj.end_datetime'
			])->value([
				date('Y-m-d'),
				date('Y-m-d', strtotime('+1 days'))
			])
		];
	}
}