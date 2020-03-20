<?php

namespace Kompo\Tests\Unit\Komponent;

use Kompo\Input;
use Kompo\Tests\EnvironmentBoot;

class KomponentLabelDeclarationTest extends EnvironmentBoot
{
	/** @test */
	public function data_is_set_correctly_on_komponents()
	{
		$el = Input::form('SomeLabel');
		
		$this->assertEquals('SomeLabel', $el->label);

		$el = Input::form();
		$this->assertEquals('', $el->label);

		$el = Input::form('<span></span>');
		$this->assertEquals('<span></span>', $el->label);
	}

}