<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Input;
use Kompo\Form;

class _InteractionNotFoundStringForm extends Form
{
	public function components()
	{
		return [
			Input::form('Title')->on('change', function($e){
				$e->on('headbang', function($e) {
					$e->get('bla');
				});
			})
		];
	}
}