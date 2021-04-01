<?php

namespace Kompo\Tests\Unit\Form;

use Kompo\Tests\EnvironmentBoot;

class IncludesOrKomponentsTest extends EnvironmentBoot
{
    /** @test */
    public function load_komponents_method_by_default()
    {
        $form = _IncludesOrKomponentsForm::boot();

        $this->assertEquals('title', $form->komponents[0]->name);
    }

    /** @test */
    public function load_other_method_if_header_includes_is_present()
    {
        $this->getKomponents(_IncludesOrKomponentsForm::boot(), 'newkompos')
            ->assertJson([
                [
                    'name' => 'content',
                ],
            ]);
    }
}
