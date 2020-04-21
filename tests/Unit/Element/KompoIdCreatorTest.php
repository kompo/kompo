<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Input;
use Kompo\Tests\EnvironmentBoot;

class KompoIdTest extends EnvironmentBoot
{
	/** @test */
	public function kompo_id_is_correctly_created_on_komponents()
	{
		$el = Input::form('<span>Some Label()*</span>&#');

		$kompoId = $el->data('kompoId');
		$this->assertNotNull($kompoId);
		$this->assertEquals('SomeLabel', substr($kompoId, 0,9) );

		$el = Input::form('<span>Some Label()*</span>&#');

		$kompoId2 = $el->data('kompoId');
		$this->assertNotNull($kompoId2);
		$this->assertEquals('SomeLabel', substr($kompoId2, 0,9) );
		$this->assertFalse($kompoId == $kompoId2); //testing uniqid() generation
	}

	/** @test */
	public function kompo_id_is_correctly_created_on_komposers()
	{
		$form = new _SetElementIdForm();

		$kompoId = $form->data('kompoId');
		$this->assertNotNull($kompoId);
		$this->assertEquals('_SetElementIdForm', substr($kompoId, 0, 17) );

		$form = new _SetElementIdForm();
		$kompoId2 = $form->data('kompoId');
		$this->assertNotNull($kompoId2);
		$this->assertEquals('_SetElementIdForm', substr($kompoId2, 0, 17) );
		$this->assertFalse($kompoId == $kompoId2); //testing uniqid() generation		
	}

}