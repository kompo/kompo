<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Tests\EnvironmentBoot;

class RunElementClosureFromActionTest extends EnvironmentBoot
{
		/** @test */
	public function element_receives_data_from_action()
	{
		$form = new _RunElementClosureFromActionForm();

		$this->assertEquals('includeMethod', $form->komponents[0]->data('includes'));
		$this->assertEquals('includeMethod', $form->komponents[1]->data('includes'));
		$this->assertEquals('includeMethod', $form->komponents[2]->data('includes'));
		$this->assertEquals('includeMethod', $form->komponents[3]->data('includes'));
	}
	
	/** ------------------ PRIVATE --------------------------- */  

	
	
}