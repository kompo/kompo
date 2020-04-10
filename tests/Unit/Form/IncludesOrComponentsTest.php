<?php

namespace Kompo\Tests\Unit\Form;

use Kompo\Tests\EnvironmentBoot;

class IncludesOrComponentsTest extends EnvironmentBoot
{
	/** @test */
	public function load_components_method_by_default()
	{
		$form = new _IncludesOrComponentsForm();

		$this->assertEquals('title', $form->components[0]->name);
	}

	/** @test */
	public function load_other_method_if_header_includes_is_present()
	{
		$this->getKomponents(new _IncludesOrComponentsForm(), 'newkompos')
			->assertJson([
				[
					'name' => 'content'
				]
			]);
	}


}