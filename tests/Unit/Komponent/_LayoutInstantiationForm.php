<?php

namespace Kompo\Tests\Unit\Komponent;

use Kompo\Columns;
use Kompo\Form;
use Kompo\Input;

class _LayoutInstantiationForm extends Form
{
	public function components()
	{
		return Columns::form(
			Input::form()->id('A'),
			new _LayoutInstantiationChildForm(),
			null //to test filtering out
		);
	}
}