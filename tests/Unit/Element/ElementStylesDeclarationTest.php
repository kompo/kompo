<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Tests\EnvironmentBoot;

class ElementStylesDeclarationTest extends EnvironmentBoot
{
    /** @test */
    public function styles_are_set_on_element()
    {
        $form = _SetElementStylesForm::boot();

        $this->assertEquals('margin:0', $form->elements[0]->style);
        $this->assertEquals('margin:0', $form->elements[1]->style);
        $this->assertEquals('margin:0', $form->elements[2]->style);
        $this->assertEquals('margin:0;padding:0', $form->elements[3]->style);
        $this->assertEquals('margin:0;padding:0;color:red', $form->elements[4]->style);
    }
}
