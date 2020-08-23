<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Input;
use Kompo\Tests\EnvironmentBoot;

class ElementClassDeclarationTest extends EnvironmentBoot
{
	/** @test */
	public function class_is_set_on_element()
	{
		$el = Input::form('Title')->class('class-0');
		$this->assertEquals('class-0', $el->class);

		$el = Input::form('Title')->class('anything')->class('class-1');
		$this->assertEquals('class-1', $el->class);

		$el = Input::form('Title')->addClass('class-2');
		$this->assertEquals('class-2', $el->class);

		$el = Input::form('Title')->class('class-3a')->addClass('class-3b');
		$this->assertEquals('class-3a class-3b', $el->class);

		$el = Input::form('Title')->class('class-4a')->addClass('class-4b class-4c');
		$this->assertEquals('class-4a class-4b class-4c', $el->class);
	}
	
	/** @test */
	public function class_is_removed_from_element()
	{
		$el = Input::form()->addClass('class-0');
		$this->assertEquals('class-0', $el->class);

		$el->class('class-1');
		$this->assertEquals('class-1', $el->class);

		$el->addClass('class-2');
		$this->assertEquals('class-1 class-2', $el->class);

		$el->removeClass('class-3');
		$this->assertEquals('class-1 class-2', $el->class);

		$el->removeClass('class-2');
		$this->assertEquals('class-1', $el->class);
	}

}