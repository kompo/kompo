<?php

namespace Kompo\Tests\Unit\Form;

use Kompo\Tests\EnvironmentBoot;

class FormModelHidingTest extends EnvironmentBoot
{
    /** @test */
    public function model_is_hidden_in_response_by_default()
    {
        $form = new _ModelHidingForm();

        $this->assertNotNull($form->model);

        $this->assertNull(json_decode((string) $form)->model ?? null);
    }

    /** @test */
    public function model_is_shown_in_response_with_config()
    {
        $form = new _ModelHidingForm();

        $this->assertNotNull($form->model);

        config()->set('kompo.eloquent_form.hide_model_in_forms', false);

        $this->assertNotNull(json_decode((string) $form)->model);
    }

    /** @test */
    public function model_is_shown_in_response_with_hide_model()
    {
        $form = new _ModelHidingForm();

        $this->assertNotNull($form->model);

        $form->showModel();

        $this->assertNotNull(json_decode((string) $form)->model);

        $form->hideModel();

        $this->assertNull(json_decode((string) $form)->model ?? null);
    }
}
