<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Button;
use Kompo\Form;

class _InteractionNotAllowedStringForm extends Form
{
	public function components()
	{
		return [
			Button::form('A')->on('change', function($e) {
				$e->get('bla');
			})
		];
	}
}