<?php

namespace Kompo\Tests\Feature\Validation;

use Kompo\Tests\EnvironmentBoot;

class NestedNameValidationTest extends EnvironmentBoot
{

	/** @test */
	public function nested_name_is_correctly_validated_in_forms()
	{
		$requestData = [ //real life simulation
			'obj`title' => null,
			'obj`tag' => null,
			'postTag`title' => null
		];

		$this->submit_validation_on_request_all_and_individually($requestData);


		$requestData = [ //dot notation simulation
			'obj.title' => null,
			'obj.tag' => null,
			'postTag.title' => null
		];

		$this->submit_validation_on_request_all_and_individually($requestData);
		
	}


	/** @test */
	public function nested_name_is_correctly_validated_in_queries()
	{
		$requestData = [ //real life simulation
			'obj`title' => null,
			'obj`tag' => null,
			'postTag`title' => null
		];

		$this->browse_validation_on_request_all_and_individually($requestData);


		$requestData = [ //dot notation simulation
			'obj.title' => null,
			'obj.tag' => null,
			'postTag.title' => null
		];

		$this->browse_validation_on_request_all_and_individually($requestData);

	}



	/********* PRIVATE ***************/

	private function submit_validation_on_request_all_and_individually($requestData)
	{
		$this->assert_komposer_is_validated_for_action(new _NestedFieldNameValidationForm(), 'submit', $requestData);
	}

	private function browse_validation_on_request_all_and_individually($requestData)
	{
		$this->assert_komposer_is_validated_for_action(new _NestedFieldNameValidationQuery(), 'browse', $requestData);
	}


	/************ PRIVATE 2 *******************/
	private function assert_komposer_is_validated_for_action($komposer, $action, $requestData)
	{
		$response = $this->{$action}($komposer, $requestData)->assertStatus(422);
		$this->assertCount(3, $response['errors']);

		foreach ($requestData as $key => $value) {

			$response = $this->{$action}($komposer, [
				$key => $value
			])->assertStatus(422);

			$this->assertCount(3, $response['errors']);

			$this->assertNotNull($response['errors'][str_replace('`', '.', $key)]);
		}
	}
}