<?php

namespace Kompo\Tests\Unit\Element\Forms;

use Kompo\{Input, Form};

class SetElementStylesForm extends Form
{
	public function components()
	{
		return [
			Input::form('Title')->style('margin:0'),
			Input::form('Title')->style('anything')->style('margin:0'),
			Input::form('Title')->addStyle('margin:0'),
			Input::form('Title')->style('margin:0')->addStyle('padding:0'),
			Input::form('Title')->style('margin:0')->addStyle('padding:0;color:red')
		];
	}
}