<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Tests\EnvironmentBoot;

class ElementStylesDeclarationTest extends EnvironmentBoot
{
	/** @test */
	public function styles_are_set_on_element()
	{
		$form = new _SetElementStylesForm();

		$this->assertEquals('margin:0', $form->komponents[0]->style);
		$this->assertEquals('margin:0', $form->komponents[1]->style);
		$this->assertEquals('margin:0', $form->komponents[2]->style);
		$this->assertEquals('margin:0;padding:0', $form->komponents[3]->style);
		$this->assertEquals('margin:0;padding:0;color:red', $form->komponents[4]->style);
	}

}