<?php

namespace Kompo\Tests\Unit\Form;

use Kompo\Tests\EnvironmentBoot;

class IncludesOrElementsTest extends EnvironmentBoot
{
    /** @test */
    public function load_elements_method_by_default()
    {
        $form = _IncludesOrElementsForm::boot();

        $this->assertEquals('title', $form->elements[0]->name);
    }

    /** @test */
    public function load_other_method_if_header_includes_is_present()
    {
        $this->getElements(_IncludesOrElementsForm::boot(), 'newkompos')
            ->assertJson([
                [
                    'name' => 'content',
                ],
            ]);
    }
}
