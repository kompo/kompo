<?php

namespace Kompo\Tests\Unit\Element\Forms;

use Kompo\{Input, Form};

class TriggerIsArrayButNotAccetableForm extends Form
{
	public function components()
	{
		return [
			Input::form('Title')->on(['click', 'headbang'], function() {})
		];
	}
}