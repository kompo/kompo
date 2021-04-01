<?php

namespace Kompo\Tests\Feature\Form;

use Kompo\Core\KompoId;
use Kompo\Tests\EnvironmentBoot;

class FormRefreshTest extends EnvironmentBoot
{
    /** @test */
    public function form_refresh_returns_202_and_form()
    {
        $form = _FormRefreshForm::boot();

        $this->assertNull($form->model->created_at);

        $kompoId = KompoId::getFromElement($form);

        $response = $this->submit($form, []);

        $formResponse = $response->baseResponse->original;

        $this->assertTrue($formResponse instanceof _FormRefreshForm);

        $this->assertNotNull($formResponse->model->created_at);

        $response->assertStatus(202);
    }

    /** ------------------ PRIVATE --------------------------- */
}
