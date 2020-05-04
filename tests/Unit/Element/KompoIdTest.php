<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Core\KompoId;
use Kompo\Input;
use Kompo\Tests\EnvironmentBoot;

class KompoIdTest extends EnvironmentBoot
{
	/** @test */
	public function kompo_id_is_correctly_created_on_komponents()
	{
		$el = Input::form('<span>Some Label()*</span>&#');

		$kompoId = KompoId::getFromElement($el);
		$this->assertNotNull($kompoId);
		$this->assertEquals('SomeLabel', substr($kompoId, 0,9) );

		$el = Input::form('<span>Some Label()*</span>&#');

		$kompoId2 = KompoId::getFromElement($el);
		$this->assertNotNull($kompoId2);
		$this->assertEquals('SomeLabel', substr($kompoId2, 0,9) );
		$this->assertFalse($kompoId == $kompoId2); //testing uniqid() generation
	}

	/** @test */
	public function kompo_id_is_correctly_created_on_komposers()
	{
		$form = new _SetElementIdForm();

		$kompoId = KompoId::getFromElement($form);
		$this->assertNotNull($kompoId);
		$this->assertEquals('_SetElementIdForm', substr($kompoId, 0, strlen($kompoId) - 13));

		$form = new _SetElementIdForm();
		$kompoId2 = KompoId::getFromElement($form);
		$this->assertNotNull($kompoId2);
		$this->assertEquals('_SetElementIdForm', substr($kompoId2, 0, strlen($kompoId2) - 13));

		$this->assertNotEquals($kompoId, $kompoId2);
	}

}