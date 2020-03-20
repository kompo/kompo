<?php

namespace Kompo\Tests\Unit\Element\Forms;

use Kompo\{Input, Form};

class TriggerNotAllowedForm extends Form
{
	public function components()
	{
		return [
			Input::form('Title')->on('headbang', function() {})
		];
	}
}