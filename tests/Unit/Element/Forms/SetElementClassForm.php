<?php

namespace Kompo\Tests\Unit\Element\Forms;

use Kompo\{Input, Form};

class SetElementClassForm extends Form
{
	public function components()
	{
		return [
			Input::form('Title')->class('class-0'),
			Input::form('Title')->class('anything')->class('class-1'),
			Input::form('Title')->addClass('class-2'),
			Input::form('Title')->class('class-3a')->addClass('class-3b'),
			Input::form('Title')->class('class-4a')->addClass('class-4b class-4c')
		];
	}
}