<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Tests\EnvironmentBoot;

class ElementIdDeclarationTest extends EnvironmentBoot
{
    /** @test */
    public function id_is_set_on_element()
    {
        $form = _SetElementIdForm::boot();

        $this->assertEquals('form-id', $form->id);
        $this->assertNull($form->elements[0]->id);
        $this->assertEquals('some-id', $form->elements[1]->id);
        $this->assertEquals('override-id', $form->elements[2]->id);
    }
}
