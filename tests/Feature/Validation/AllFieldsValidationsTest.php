<?php

namespace Kompo\Tests\Feature\Validation;

use Kompo\Core\ValidationManager;
use Kompo\Tests\EnvironmentBoot;

class AllFieldsValidationsTest extends EnvironmentBoot
{
	/** @test */
	public function validating_all_fields_rules_in_forms()
	{
		$form = new _AllFieldsValidationsForm();

		$response = $this->submit(new _AllFieldsValidationsForm())->assertStatus(422);

		$this->assertCount(count($form->components), $response['errors']);

		foreach (ValidationManager::getRules($form) as $key => $value) {
			$this->assertEquals(substr($response['errors'][$key][0], -12), 'is required.');
		}
	}

}