<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Unit\Element\Forms\SetElementClassForm;

class ElementClassDeclarationTest extends EnvironmentBoot
{
	/** @test */
	public function class_is_set_on_element()
	{
		$form = new SetElementClassForm();

		$this->assertEquals('class-0', $form->components[0]->class);
		$this->assertEquals('class-1', $form->components[1]->class);
		$this->assertEquals('class-2', $form->components[2]->class);
		$this->assertEquals('class-3a class-3b', $form->components[3]->class);
		$this->assertEquals('class-4a class-4b class-4c', $form->components[4]->class);
	}

}