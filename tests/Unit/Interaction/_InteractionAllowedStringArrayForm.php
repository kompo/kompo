<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Input;
use Kompo\Form;

class _InteractionAllowedStringArrayForm extends Form
{
	public function components()
	{
		return [
			Input::form('Title')->on('change', function($e) {
				$e->get('bla');
			}),
			Input::form('Title')->on(['click', 'change'], function($e) {
				$e->get('bla');
			})
		];
	}
}