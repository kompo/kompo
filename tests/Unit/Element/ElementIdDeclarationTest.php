<?php

namespace Kompo\Tests\Unit\Element;

use Kompo\Tests\EnvironmentBoot;

class ElementIdDeclarationTest extends EnvironmentBoot
{
    /** @test */
    public function id_is_set_on_element()
    {
        $form = new _SetElementIdForm();

        $this->assertEquals('form-id', $form->id);
        $this->assertNull($form->komponents[0]->id);
        $this->assertEquals('some-id', $form->komponents[1]->id);
        $this->assertEquals('override-id', $form->komponents[2]->id);
    }
}
