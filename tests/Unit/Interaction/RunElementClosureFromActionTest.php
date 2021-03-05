<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Tests\EnvironmentBoot;

class RunElementClosureFromActionTest extends EnvironmentBoot
{
    /** @test */
    public function element_receives_config_from_action()
    {
        $form = new _RunElementClosureFromActionForm();

        $this->assertEquals('includeMethod', $form->komponents[0]->config('includes'));
        $this->assertEquals('includeMethod', $form->komponents[1]->config('includes'));
        $this->assertEquals('includeMethod', $form->komponents[2]->config('includes'));
        $this->assertEquals('includeMethod', $form->komponents[3]->config('includes'));
    }

    /** ------------------ PRIVATE --------------------------- */
}
