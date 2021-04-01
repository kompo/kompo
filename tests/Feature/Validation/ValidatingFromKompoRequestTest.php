<?php

namespace Kompo\Tests\Feature\Validation;

use Illuminate\Validation\ValidationException;
use Kompo\Http\Requests\KompoFormRequest;
use Kompo\Tests\EnvironmentBoot;

class ValidatingFromKompoRequestTest extends EnvironmentBoot
{
    /** @test */
    public function validation_error_from_kompo_request()
    {
        \Route::post('submit-route', function (KompoFormRequest $request) {});

        $this->expectException(ValidationException::class);

        $this->withoutExceptionHandling()
            ->submitToRoute(_KompoRequestValidationForm::boot())
            ->assertStatus(422);
    }

    /** @test */
    public function validation_success_from_kompo_request()
    {
        \Route::post('submit-route', function (KompoFormRequest $request) {});

        $this->submitToRoute(_KompoRequestValidationForm::boot(), ['name' => 'blabla'])
            ->assertStatus(200);
    }
}
