<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Unit\Element\Forms\SetElementIdForm;

class ElementIdDeclarationTest extends EnvironmentBoot
{
	/** @test */
	public function id_is_set_on_element()
	{
		$form = new SetElementIdForm();

		$this->assertEquals('form-id', $form->id);
		$this->assertNull($form->components[0]->id);
		$this->assertEquals('some-id', $form->components[1]->id);
		$this->assertEquals('override-id', $form->components[2]->id);
	}

}