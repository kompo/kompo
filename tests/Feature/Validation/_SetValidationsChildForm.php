<?php

namespace Kompo\Tests\Feature\Validation;

use Kompo\Form;
use Kompo\Input;

class _SetValidationsChildForm extends Form
{
	public function komponents()
	{
		return [
			Input::form('E')->name('name5'),
			Input::form('F')->name('name6')->rules(''),
			Input::form('G')->name('name7')->rules([
				'name7' => 'some-rule',
				'other-name' => 'rule-for-other-name'
			]),
		];
	}

	public function rules()
	{
		return [
			'name6' => 'size:2&de"fea',
			'name7' => 'unique|Testing spaces'
		];
	}
}