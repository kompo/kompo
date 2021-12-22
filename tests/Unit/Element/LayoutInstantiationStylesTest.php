<?php

namespace Kompo\Tests\Unit\Element;

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

        $this->assertEquals([], $el->elements);
    }

    /** @test */
    public function layout_is_instantianted_with_string()
    {
        $el = Columns::form('String is not taken into account'); //TODO: document when that is needed

        $this->assertEquals([], $el->elements);
    }

    /** @test */
    public function layout_is_instantianted_with_single_arg()
    {
        $el = Columns::form(
            Input::form()->id('A')
        );

        $this->assertIsArray($el->elements);
        $this->assertCount(1, $el->elements);
        $this->assertEquals('A', $el->elements[0]->id);
    }

    /** @test */
    public function layout_is_instantianted_with_closure_returning_one_component()
    {
        $el = Columns::form(function () {
            return Input::form()->id('A');
        });

        $this->assertIsArray($el->elements);
        $this->assertCount(1, $el->elements);
        $this->assertEquals('A', $el->elements[0]->id);
    }

    /** @test */
    public function layout_is_instantianted_with_closure_returning_array()
    {
        $el = Columns::form(function () {
            return [
                Input::form()->id('A'),
                Input::form()->id('B'),
            ];
        });

        $this->assert_layout_has_2_elements_with_ids($el, 'A', 'B');
    }

    /** @test */
    public function layout_is_instantianted_with_multiple_args()
    {
        $el = Columns::form(
            Input::form()->id('A'),
            Input::form()->id('B')
        );

        $this->assert_layout_has_2_elements_with_ids($el, 'A', 'B');
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

        $this->assertIsArray($el->elements);
        $this->assertCount(2, $el->elements);
        $this->assertEquals('A', $el->elements[0]->id);

        $this->assert_layout_has_2_elements_with_ids($el->elements[1], 'B', 'C');
    }

    /** @test */
    public function layout_has_form_in_children()
    {
        $el = _LayoutInstantiationForm::boot();

        $this->assertIsArray($el->elements);
        $this->assertCount(1, $el->elements);
        $this->assertIsArray($el->elements[0]->elements);
        $this->assertCount(2, $el->elements[0]->elements); //test filtering out
        $this->assertEquals('A', $el->elements[0]->elements[0]->id);
        $this->assertIsArray($el->elements[0]->elements[1]->elements);
        $this->assertCount(1, $el->elements[0]->elements[1]->elements);

        $this->assert_layout_has_2_elements_with_ids($el->elements[0]->elements[1]->elements[0], 'B', 'C');
    }

    /** ------------------ PRIVATE --------------------------- */
    private function assert_layout_has_2_elements_with_ids($layout, $id1, $id2)
    {
        $this->assertIsArray($layout->elements);
        $this->assertCount(2, $layout->elements);
        $this->assertEquals($id1, $layout->elements[0]->id);
        $this->assertEquals($id2, $layout->elements[1]->id);
    }
}
