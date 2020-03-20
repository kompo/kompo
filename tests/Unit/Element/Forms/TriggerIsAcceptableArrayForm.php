<?php

namespace Kompo\Tests\Unit\Element\Forms;

use Kompo\{Select, Form};

class TriggerIsAcceptableArrayForm extends Form
{
	public function components()
	{
		return [
			Select::form('Title')->on(['click', 'change'], function() {})
		];
	}
}