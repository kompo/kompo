<?php
namespace Kompo\Tests\Unit\Element;

use Kompo\Card;
use Kompo\Tests\EnvironmentBoot;

class SetVueComponentTest extends EnvironmentBoot
{
    /** @test */
	public function element_is_assigned_a_vue_component()
	{
		$element = new Card();
		$this->assertEquals('Card', $element->component);

		$element->component('SomethingElse');
		$this->assertEquals('SomethingElse', $element->component);
	}

}