<?php

namespace Kompo\Tests\Feature\Validation;

use Kompo\Tests\EnvironmentBoot;

class NestedNameValidationTest extends EnvironmentBoot
{

	/** @test */
	public function real_life_nested_name_is_correctly_validated_in_forms()
	{
		$this->submit_validation_on_request_all_and_individually([ 
			'obj_title' => 'title',  //real life simulation
			'obj_tag' => 1,
			'postTag_title' => 'title'
		]);
	}


	/** @test */
	public function real_life_nested_name_is_correctly_validated_in_queries()
	{
		$this->browse_validation_on_request_all_and_individually([ 
			'obj_title' => 'title', //real life simulation
			'obj_tag' => 1,
			'postTag_title' => 'title'
		]);
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
		$arrayKeys = array_keys($requestData);

		foreach ($arrayKeys as $arrayKey) {
			$permutedRequest = $requestData;
			$permutedRequest[$arrayKey] = null;

			$response = $this->{$action}($komposer, $permutedRequest)->assertStatus(422);
			$this->assertCount(1, $response['errors']);
		}
	}
}