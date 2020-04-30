<?php

namespace Kompo\Tests\Feature\Validation;

use Kompo\Http\Requests\KompoFormRequest;
use Kompo\Tests\EnvironmentBoot;
use Illuminate\Validation\ValidationException;

class ValidatingFromKompoRequestTest extends EnvironmentBoot
{
	/** @test */
	public function validation_error_from_kompo_request()
	{
		\Route::post('submit-route', function(KompoFormRequest $request){});

		$this->expectException(ValidationException::class);

		$this->withoutExceptionHandling()
			->submitToRoute(new _KompoRequestValidationForm())
			->assertStatus(422);
	}

	/** @test */
	public function validation_success_from_kompo_request()
	{
		\Route::post('submit-route', function(KompoFormRequest $request){});

		$this->submitToRoute(new _KompoRequestValidationForm(), ['name' => 'blabla'])
			->assertStatus(200);
	}

}