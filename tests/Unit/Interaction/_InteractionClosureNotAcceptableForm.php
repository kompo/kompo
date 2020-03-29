<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Input;
use Kompo\Form;

class _InteractionClosureNotAcceptableForm extends Form
{
	public function components()
	{
		return [
			Input::form('Title')->on('change', 'not a closure')
		];
	}
}