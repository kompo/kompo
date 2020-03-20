<?php

namespace Kompo\Tests\Unit\Element\Forms;

use Kompo\{Input, Form};

class BadTriggerForm extends Form
{
	public function components()
	{
		return [
			Input::form('Title')->on(1, function() {})
		];
	}
}