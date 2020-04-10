<?php

namespace Kompo\Tests\Unit\Form;

use Kompo\Form;
use Kompo\Input;

class _IncludesOrComponentsForm extends Form
{
	public function components()
	{
		return [
			Input::form('Title')->getKomponents('newKompos')
		];
	}


	public function newkompos()
	{
		return [
			Input::form('Content')
		];
	}

}