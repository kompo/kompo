<?php

namespace Kompo\Tests\Unit\Element\Forms;

use Kompo\{Input, Form};

class SetElementIdForm extends Form
{
	public $id = 'form-id';

	public function components()
	{
		return [
			Input::form('Title'),
			Input::form('Title')->id('some-id'),
			Input::form('Title')->id('some-id')->id('override-id')
		];
	}
}