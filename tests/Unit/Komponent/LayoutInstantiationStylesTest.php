<?php

namespace Kompo\Tests\Unit\Komponent;

use Kompo\Columns;
use Kompo\Input;
use Kompo\Rows;
use Kompo\Tests\EnvironmentBoot;

class LayoutInstantiationStylesTest extends EnvironmentBoot
{
	/** @test */
	public function layout_is_instantianted_with_no_args()
	{
		$el = Columns::form();

		$this->assertEquals([], $el->components);
	}

	/** @test */
	public function layout_is_instantianted_with_string()
	{
		$el = Columns::form('String is not taken into account'); //TODO: document when that is needed

		$this->assertEquals([], $el->components);
	}

	/** @test */
	public function layout_is_instantianted_with_single_arg()
	{
		$el = Columns::form(
			Input::form()->id('A')
		);

		$this->assertIsArray($el->components);
		$this->assertCount(1, $el->components);
		$this->assertEquals('A', $el->components[0]->id);
	}

	/** @test */
	public function layout_is_instantianted_with_closure_returning_one_component()
	{
		$el = Columns::form(function(){
			return Input::form()->id('A');
		});

		$this->assertIsArray($el->components);
		$this->assertCount(1, $el->components);
		$this->assertEquals('A', $el->components[0]->id);
	}

	/** @test */
	public function layout_is_instantianted_with_closure_returning_array()
	{
		$el = Columns::form(function(){
			return [
				Input::form()->id('A'),
				Input::form()->id('B')
			];
		});

		$this->assert_layout_has_2_components_with_ids($el, 'A', 'B');
	}

	/** @test */
	public function layout_is_instantianted_with_multiple_args()
	{
		$el = Columns::form(
			Input::form()->id('A'),
			Input::form()->id('B')
		);

		$this->assert_layout_has_2_components_with_ids($el, 'A', 'B');
	}

	/** @test */
	public function layout_has_layout_children()
	{
		$el = Columns::form(
			Input::form()->id('A'),
			Rows::form(
				Input::form()->id('B'),
				Input::form()->id('C')
			)
		);

		$this->assertIsArray($el->components);
		$this->assertCount(2, $el->components);
		$this->assertEquals('A', $el->components[0]->id);

		$this->assert_layout_has_2_components_with_ids($el->components[1], 'B', 'C');
	}

	/** @test */
	public function layout_has_form_in_children()
	{
		$el = new _LayoutInstantiationForm();

		$this->assertIsArray($el->components);
		$this->assertCount(1, $el->components);
		$this->assertIsArray($el->components[0]->components);
		$this->assertCount(2, $el->components[0]->components); //test filtering out
		$this->assertEquals('A', $el->components[0]->components[0]->id);
		$this->assertIsArray($el->components[0]->components[1]->components);
		$this->assertCount(1, $el->components[0]->components[1]->components);

		$this->assert_layout_has_2_components_with_ids($el->components[0]->components[1]->components[0], 'B', 'C');
	}

	/** ------------------ PRIVATE --------------------------- */ 

	private function assert_layout_has_2_components_with_ids($layout, $id1, $id2)
	{
		$this->assertIsArray($layout->components);
		$this->assertCount(2, $layout->components);
		$this->assertEquals($id1, $layout->components[0]->id);
		$this->assertEquals($id2, $layout->components[1]->id);
	}




}