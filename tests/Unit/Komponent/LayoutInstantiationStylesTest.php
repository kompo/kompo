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

        $this->assertEquals([], $el->komponents);
    }

    /** @test */
    public function layout_is_instantianted_with_string()
    {
        $el = Columns::form('String is not taken into account'); //TODO: document when that is needed

        $this->assertEquals([], $el->komponents);
    }

    /** @test */
    public function layout_is_instantianted_with_single_arg()
    {
        $el = Columns::form(
            Input::form()->id('A')
        );

        $this->assertIsArray($el->komponents);
        $this->assertCount(1, $el->komponents);
        $this->assertEquals('A', $el->komponents[0]->id);
    }

    /** @test */
    public function layout_is_instantianted_with_closure_returning_one_component()
    {
        $el = Columns::form(function () {
            return Input::form()->id('A');
        });

        $this->assertIsArray($el->komponents);
        $this->assertCount(1, $el->komponents);
        $this->assertEquals('A', $el->komponents[0]->id);
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

        $this->assert_layout_has_2_komponents_with_ids($el, 'A', 'B');
    }

    /** @test */
    public function layout_is_instantianted_with_multiple_args()
    {
        $el = Columns::form(
            Input::form()->id('A'),
            Input::form()->id('B')
        );

        $this->assert_layout_has_2_komponents_with_ids($el, 'A', 'B');
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

        $this->assertIsArray($el->komponents);
        $this->assertCount(2, $el->komponents);
        $this->assertEquals('A', $el->komponents[0]->id);

        $this->assert_layout_has_2_komponents_with_ids($el->komponents[1], 'B', 'C');
    }

    /** @test */
    public function layout_has_form_in_children()
    {
        $el = _LayoutInstantiationForm::boot();

        $this->assertIsArray($el->komponents);
        $this->assertCount(1, $el->komponents);
        $this->assertIsArray($el->komponents[0]->komponents);
        $this->assertCount(2, $el->komponents[0]->komponents); //test filtering out
        $this->assertEquals('A', $el->komponents[0]->komponents[0]->id);
        $this->assertIsArray($el->komponents[0]->komponents[1]->komponents);
        $this->assertCount(1, $el->komponents[0]->komponents[1]->komponents);

        $this->assert_layout_has_2_komponents_with_ids($el->komponents[0]->komponents[1]->komponents[0], 'B', 'C');
    }

    /** ------------------ PRIVATE --------------------------- */
    private function assert_layout_has_2_komponents_with_ids($layout, $id1, $id2)
    {
        $this->assertIsArray($layout->komponents);
        $this->assertCount(2, $layout->komponents);
        $this->assertEquals($id1, $layout->komponents[0]->id);
        $this->assertEquals($id2, $layout->komponents[1]->id);
    }
}
