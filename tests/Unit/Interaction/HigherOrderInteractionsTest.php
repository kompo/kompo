<?php

namespace Kompo\Tests\Unit\Interaction;

use Exception;
use ErrorException;
use Kompo\Button;
use Kompo\Exceptions\NotAllowedInteractionException;
use Kompo\Html;
use Kompo\Input;
use Kompo\Panel;
use Kompo\Tests\EnvironmentBoot;

class HigherOrderInteractionsTest extends EnvironmentBoot
{
	/** @test */
	public function element_does_not_accept_higher_order_interaction()
	{
		$this->expectException(ErrorException::class);

		Html::form()->onClick->submit();
	}

	/** @test */
	public function element_higher_order_interaction_does_not_exist()
	{
		$this->expectException(Exception::class);

		Button::form()->onTouch->submit();
	}

	/** @test */
	public function element_higher_order_interaction_is_not_allowed()
	{
		$this->expectException(NotAllowedInteractionException::class);

		Input::form()->onClick->submit();
	}

	/** @test */
	public function not_allowed_nested_higher_order_interaction_throws_exception()
	{
		$this->expectException(NotAllowedInteractionException::class);

		Input::form()->onInput(function($e){
			$e->submit()->onChange->post('route');
		})->interactions;
	}

	/** @test */
	public function element_higher_order_interaction_is_applied()
	{
		$interactions = Input::form()->onInput->submit()->interactions;

		$this->assertCount(1, $interactions);
		$this->assertEquals('input', $interactions[0]->interactionType);
		$this->assertEquals('submitForm', $interactions[0]->action->actionType);
	}

	/** @test */
	public function element_chained_higher_order_interaction_is_nested()
	{
		$interactions = Input::form()->onInput->submit()->post('route')->interactions;

		$this->assertCount(1, $interactions);
		$this->assertEquals('input', $interactions[0]->interactionType);
		$this->assertEquals('submitForm', $interactions[0]->action->actionType);

		$nested = $interactions[0]->action->interactions;
		$this->assertCount(1, $nested);
		$this->assertEquals('success', $nested[0]->interactionType);
		$this->assertEquals('axiosRequest', $nested[0]->action->actionType);
	}

	/** @test */
	public function element_higher_order_interaction_in_closure_is_nested()
	{
		$interactions = Input::form()->onInput(function($e){
			$e->submit()->onSuccess->post('route');
		})->interactions;

		$this->assertCount(1, $interactions);
		$this->assertEquals('input', $interactions[0]->interactionType);
		$this->assertEquals('submitForm', $interactions[0]->action->actionType);

		$nested = $interactions[0]->action->interactions;
		$this->assertCount(1, $nested);
		$this->assertEquals('success', $nested[0]->interactionType);
		$this->assertEquals('axiosRequest', $nested[0]->action->actionType);
	}

	
	/** ------------------ PRIVATE --------------------------- */  

	
	
}