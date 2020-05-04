<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Button;
use Kompo\Exceptions\NotApplicableInteractionException;
use Kompo\Input;
use Kompo\Tests\EnvironmentBoot;

class SuccessErrorInteractionsTest extends EnvironmentBoot
{
	/** @test */
	public function on_success_with_no_previous_interaction_throws_error()
	{
		$this->expectException(NotApplicableInteractionException::class);

		Input::form()->onSuccess(function($e){
			$e->submit();
		});
	}

	/** @test */
	public function on_error_with_no_previous_interaction_throws_error()
	{
		$this->expectException(NotApplicableInteractionException::class);

		Input::form()->onError(function($e){
			$e->submit();
		});
	}

	/** @test */
	public function first_level_on_success_error_interactions_are_applied()
	{
		$interactions = Input::form()->submit()->onSuccess(function($e){
			$e->post('route');
			$e->sort();
		})->onError(function($e){
			$e->filter();
		})->interactions;

		$this->assertCount(1, $interactions);
		$this->assertEquals('input', $interactions[0]->interactionType);
		$this->assertEquals('submitForm', $interactions[0]->action->actionType);

		$nested = $interactions[0]->action->interactions;

		$this->assertCount(3, $nested);
		$this->assertEquals('success', $nested[0]->interactionType);
		$this->assertEquals('axiosRequest', $nested[0]->action->actionType);
		$this->assertEquals('success', $nested[1]->interactionType);
		$this->assertEquals('sortQuery', $nested[1]->action->actionType);
		$this->assertEquals('error', $nested[2]->interactionType);
		$this->assertEquals('browseQuery', $nested[2]->action->actionType);
	}

	/** @test */
	public function nested_on_success_error_interactions_are_applied()
	{
		$interactions = Input::form()->onChange(function($e){
			$e->post('route')->onSuccess(function($e){
				$e->sort();
				$e->submit()->onSuccess(function($e){
					$e->get('route2');
				});
			})->onError(function($e){
				$e->filter();
			});
		})->interactions;

		$this->assertCount(1, $interactions);
		$this->assertEquals('change', $interactions[0]->interactionType);
		$this->assertEquals('axiosRequest', $interactions[0]->action->actionType);

		$nested = $interactions[0]->action->interactions;

		$this->assertCount(3, $nested);
		$this->assertEquals('success', $nested[0]->interactionType);
		$this->assertEquals('sortQuery', $nested[0]->action->actionType);
		$this->assertEquals('success', $nested[1]->interactionType);
		$this->assertEquals('submitForm', $nested[1]->action->actionType);

		$nested2 = $nested[1]->action->interactions;
		$this->assertCount(1, $nested2);
		$this->assertEquals('success', $nested2[0]->interactionType);
		$this->assertEquals('axiosRequest', $nested2[0]->action->actionType);


		$this->assertEquals('error', $nested[2]->interactionType);
		$this->assertEquals('browseQuery', $nested[2]->action->actionType);
	}
	
	/** ------------------ PRIVATE --------------------------- */  

	
	
}