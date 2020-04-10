<?php

namespace Kompo\Tests\Feature\DateRange;

use Kompo\Form;
use Kompo\DateRange;

class _DateRangeHandledForm extends Form
{
	public function components()
	{
		return [
			DateRange::form('A')->name([
				'start_date',
				'end_date'
			]),
			DateRange::form('A')->name([
				'start_datetime',
				'end_datetime'
			])->value([
				date('Y-m-d'),
				date('Y-m-d', strtotime('+1 days'))
			])
		];
	}
}