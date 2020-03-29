<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Input;
use Kompo\Form;

class _InteractionNotFoundTypeForm extends Form
{
	public function components()
	{
		return [
			Input::form('Title')->on(1, function($e) {
				$e->get('bla');
			})
		];
	}
}