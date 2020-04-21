<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Tests\EnvironmentBoot;

class ElementClassDeclarationTest extends EnvironmentBoot
{
	/** @test */
	public function class_is_set_on_element()
	{
		$form = new _SetElementClassForm();

		$this->assertEquals('class-0', $form->komponents[0]->class);
		$this->assertEquals('class-1', $form->komponents[1]->class);
		$this->assertEquals('class-2', $form->komponents[2]->class);
		$this->assertEquals('class-3a class-3b', $form->komponents[3]->class);
		$this->assertEquals('class-4a class-4b class-4c', $form->komponents[4]->class);
	}

}