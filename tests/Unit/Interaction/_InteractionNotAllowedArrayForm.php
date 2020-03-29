<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Panel;
use Kompo\Form;

class _InteractionNotAllowedArrayForm extends Form
{
	public function components()
	{
		return [
			Panel::form()->on(['load'], function($e) {

				$e->get('bla')->on('change', function($e){ //nested not allowed
					$e->submit();
				});

			})
		];
	}
}