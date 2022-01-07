<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Collapse;
use Kompo\Dropdown;
use Kompo\Link;
use Kompo\Tests\EnvironmentBoot;

class SubMenuInstantiationStylesTest extends EnvironmentBoot
{
    /** @test */
    public function submenu_is_instantianted_with_no_args()
    {
        $el = Collapse::form()->submenu();

        $this->assertEquals([], $el->elements);
    }

    /** @test */
    public function submenu_is_instantianted_with_string()
    {
        $el = Collapse::form()->submenu('String is not taken into account');

        $this->assertEquals([], $el->elements);
    }

    /** @test */
    public function submenu_is_instantianted_with_single_arg()
    {
        $el = Collapse::form()->submenu(
            Link::form()->id('A')
        );

        $this->assertIsArray($el->elements);
        $this->assertCount(1, $el->elements);
        $this->assertEquals('A', $el->elements[0]->id);
    }

    /** @test */
    public function submenu_is_instantianted_with_closure_returning_one_component()
    {
        $el = Collapse::form()->submenu(function () {
            return Link::form()->id('A');
        });

        $this->assertIsArray($el->elements);
        $this->assertCount(1, $el->elements);
        $this->assertEquals('A', $el->elements[0]->id);
    }

    /** @test */
    public function submenu_is_instantianted_with_closure_returning_array()
    {
        $el = Collapse::form()->submenu(function () {
            return [
                Link::form()->id('A'),
                Link::form()->id('B'),
            ];
        });

        $this->assert_submenu_has_2_elements_with_ids($el, 'A', 'B');
    }

    /** @test */
    public function submenu_is_instantianted_with_multiple_args()
    {
        $el = Collapse::form()->submenu(
            Link::form()->id('A'),
            Link::form()->id('B')
        );

        $this->assert_submenu_has_2_elements_with_ids($el, 'A', 'B');
    }

    /** @test */
    public function submenu_has_submenu_children()
    {
        $el = Collapse::form()->submenu(
            Link::form()->id('A'),
            Dropdown::form()->submenu(
                Link::form()->id('B'),
                Link::form()->id('C')
            )
        );

        $this->assertIsArray($el->elements);
        $this->assertCount(2, $el->elements);
        $this->assertEquals('A', $el->elements[0]->id);

        $this->assert_submenu_has_2_elements_with_ids($el->elements[1], 'B', 'C');
    }

    /** @test */
    public function submenu_has_form_in_children()
    {
        $el = _LayoutInstantiationForm::boot();

        $this->assertIsArray($el->elements);
        $this->assertCount(1, $el->elements);
        $this->assertIsArray($el->elements[0]->elements);
        $this->assertCount(2, $el->elements[0]->elements); //test filtering out
        $this->assertEquals('A', $el->elements[0]->elements[0]->id);
        $this->assertIsArray($el->elements[0]->elements[1]->elements);
        $this->assertCount(1, $el->elements[0]->elements[1]->elements);

        $this->assert_submenu_has_2_elements_with_ids($el->elements[0]->elements[1]->elements[0], 'B', 'C');
    }

    /** ------------------ PRIVATE --------------------------- */
    private function assert_submenu_has_2_elements_with_ids($layout, $id1, $id2)
    {
        $this->assertIsArray($layout->elements);
        $this->assertCount(2, $layout->elements);
        $this->assertEquals($id1, $layout->elements[0]->id);
        $this->assertEquals($id2, $layout->elements[1]->id);
    }
}
