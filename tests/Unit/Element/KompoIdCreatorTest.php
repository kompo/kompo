<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Input;
use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Unit\Element\Forms\SetElementIdForm;

class KompoIdCreatorTest extends EnvironmentBoot
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
		$form = new SetElementIdForm();

		$kompoId = $form->data('kompoId');
		$this->assertNotNull($kompoId);
		$this->assertEquals('SetElementIdForm', substr($kompoId, 0, 16) );

		$form = new SetElementIdForm();
		$kompoId2 = $form->data('kompoId');
		$this->assertNotNull($kompoId2);
		$this->assertEquals('SetElementIdForm', substr($kompoId2, 0, 16) );
		$this->assertFalse($kompoId == $kompoId2); //testing uniqid() generation		
	}

}