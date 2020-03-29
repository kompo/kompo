<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Input;
use Kompo\Form;

class _InteractionNotFoundArrayForm extends Form
{
	public function components()
	{
		return [
			Input::form('Title')->on(['click', 'headbang'], function($e) {
				$e->get('bla');
			})
		];
	}
}