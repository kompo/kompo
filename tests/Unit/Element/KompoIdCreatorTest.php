<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Input;
use Kompo\Tests\EnvironmentBoot;
use Illuminate\Support\Facades\Crypt;

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

		$bootInfo = Crypt::decrypt($form->data('kompoId'));
		$this->assertNotNull($bootInfo);
		$this->assertEquals(_SetElementIdForm::class, $bootInfo['kompoClass'] );

		$form = new _SetElementIdForm();
		$bootInfo2 = Crypt::decrypt($form->data('kompoId'));
		$this->assertNotNull($bootInfo2);
		$this->assertEquals(_SetElementIdForm::class, $bootInfo['kompoClass'] );

		foreach($bootInfo as $key => $value)
        {
            $this->assertEquals($value, $bootInfo2[$key]);
        }		
	}

}