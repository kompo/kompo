<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Input;
use Kompo\Tests\EnvironmentBoot;
use Illuminate\Support\Facades\Crypt;

class KompoInfoTest extends EnvironmentBoot
{
	/** @test */
	public function kompo_info_is_correctly_created_on_komposers()
	{
		$form = new _SetElementIdForm();

		$bootInfo = Crypt::decrypt($form->data('kompoInfo'));
		$this->assertNotNull($bootInfo);
		$this->assertEquals(_SetElementIdForm::class, $bootInfo['kompoClass'] );

		$form = new _SetElementIdForm();
		$bootInfo2 = Crypt::decrypt($form->data('kompoInfo'));
		$this->assertNotNull($bootInfo2);
		$this->assertEquals(_SetElementIdForm::class, $bootInfo['kompoClass'] );

		foreach($bootInfo as $key => $value)
        {
        	if($key !== 'kompoId')
            	$this->assertEquals($value, $bootInfo2[$key]);
        }		
	}

}