<?php

namespace Kompo\Tests\Unit\Element\Forms;

use Kompo\{Button, Form};

class TriggerIsAcceptableStringForm extends Form
{
	public function components()
	{
		return [
			Button::form('Save')->on('click', function() {})
		];
	}
}