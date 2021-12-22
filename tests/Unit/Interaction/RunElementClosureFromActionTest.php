<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Tests\EnvironmentBoot;

class RunElementClosureFromActionTest extends EnvironmentBoot
{
    /** @test */
    public function element_receives_config_from_action()
    {
        $form = _RunElementClosureFromActionForm::boot();

        $this->assertEquals('includeMethod', $form->elements[0]->config('includes'));
        $this->assertEquals('includeMethod', $form->elements[1]->config('includes'));
        $this->assertEquals('includeMethod', $form->elements[2]->config('includes'));
        $this->assertEquals('includeMethod', $form->elements[3]->config('includes'));
    }

    /** ------------------ PRIVATE --------------------------- */
}
