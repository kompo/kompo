<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Input;
use Kompo\Tests\EnvironmentBoot;
use Kompo\Tests\Unit\Element\Forms\SetElementClassForm;

class ElementDataDeclarationTest extends EnvironmentBoot
{
	/** @test */
	public function data_is_set_correctly_on_elements()
	{
		$el = Input::form('SomeLabel');
		
		$this->assertIsArray($el->data);
		$this->assertNotNull($el->data['kompoId']);

		$el->data(['some-key' => 'some-value']);
		$this->assertIsArray($el->data);
		$this->assertEquals('some-value', $el->data['some-key']);

		$el->data([
			'some-key' => 'another-value',
			'another-key' => ['txt1', 'txt2']
		]);
		$this->assertEquals('another-value', $el->data['some-key']);
		$this->assertIsArray($el->data['another-key']);
		$this->assertEquals('txt1', $el->data['another-key'][0]);
		$this->assertEquals('txt2', $el->data['another-key'][1]);


		$el->data([
			'another-key' => ['txt3'],
			0 => 2
		]);
		$this->assertEquals('another-value', $el->data['some-key']);
		$this->assertIsArray($el->data['another-key']);
		$this->assertCount(1, $el->data['another-key']);
		$this->assertEquals('txt3', $el->data['another-key'][0]);
		$this->assertEquals(2, $el->data[0]);
	}

}