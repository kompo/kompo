<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Input;
use Kompo\Tests\EnvironmentBoot;

class ElementLabelDeclarationTest extends EnvironmentBoot
{
    /** @test */
    public function data_is_set_correctly_on_elements()
    {
        $el = Input::form('SomeLabel');

        $this->assertEquals('SomeLabel', $el->label);

        $el = Input::form();
        $this->assertEquals('', $el->label);

        $el = Input::form('<span></span>');
        $this->assertEquals('<span></span>', $el->label);
    }
}
