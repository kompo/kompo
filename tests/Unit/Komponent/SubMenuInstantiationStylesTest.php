<?php

namespace Kompo\Tests\Unit\Komponent;

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

        $this->assertEquals([], $el->komponents);
    }

    /** @test */
    public function submenu_is_instantianted_with_string()
    {
        $el = Collapse::form()->submenu('String is not taken into account'); //TODO: document when that is needed

        $this->assertEquals([], $el->komponents);
    }

    /** @test */
    public function submenu_is_instantianted_with_single_arg()
    {
        $el = Collapse::form()->submenu(
            Link::form()->id('A')
        );

        $this->assertIsArray($el->komponents);
        $this->assertCount(1, $el->komponents);
        $this->assertEquals('A', $el->komponents[0]->id);
    }

    /** @test */
    public function submenu_is_instantianted_with_closure_returning_one_component()
    {
        $el = Collapse::form()->submenu(function () {
            return Link::form()->id('A');
        });

        $this->assertIsArray($el->komponents);
        $this->assertCount(1, $el->komponents);
        $this->assertEquals('A', $el->komponents[0]->id);
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

        $this->assert_submenu_has_2_komponents_with_ids($el, 'A', 'B');
    }

    /** @test */
    public function submenu_is_instantianted_with_multiple_args()
    {
        $el = Collapse::form()->submenu(
            Link::form()->id('A'),
            Link::form()->id('B')
        );

        $this->assert_submenu_has_2_komponents_with_ids($el, 'A', 'B');
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

        $this->assertIsArray($el->komponents);
        $this->assertCount(2, $el->komponents);
        $this->assertEquals('A', $el->komponents[0]->id);

        $this->assert_submenu_has_2_komponents_with_ids($el->komponents[1], 'B', 'C');
    }

    /** @test */
    public function submenu_has_form_in_children()
    {
        $el = _LayoutInstantiationForm::boot();

        $this->assertIsArray($el->komponents);
        $this->assertCount(1, $el->komponents);
        $this->assertIsArray($el->komponents[0]->komponents);
        $this->assertCount(2, $el->komponents[0]->komponents); //test filtering out
        $this->assertEquals('A', $el->komponents[0]->komponents[0]->id);
        $this->assertIsArray($el->komponents[0]->komponents[1]->komponents);
        $this->assertCount(1, $el->komponents[0]->komponents[1]->komponents);

        $this->assert_submenu_has_2_komponents_with_ids($el->komponents[0]->komponents[1]->komponents[0], 'B', 'C');
    }

    /** ------------------ PRIVATE --------------------------- */
    private function assert_submenu_has_2_komponents_with_ids($layout, $id1, $id2)
    {
        $this->assertIsArray($layout->komponents);
        $this->assertCount(2, $layout->komponents);
        $this->assertEquals($id1, $layout->komponents[0]->id);
        $this->assertEquals($id2, $layout->komponents[1]->id);
    }
}
