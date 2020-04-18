<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Button;
use Kompo\Exceptions\NotAcceptableInteractionClosureException;
use Kompo\Exceptions\NotAcceptableInteractionException;
use Kompo\Exceptions\NotAllowedInteractionException;
use Kompo\Exceptions\NotFoundInteractionException;
use Kompo\Input;
use Kompo\Panel;
use Kompo\Tests\EnvironmentBoot;

class InteractionsDeclarationTest extends EnvironmentBoot
{
	/** @test */
	public function interaction_is_acceptable_string_or_array()
	{
		$interactions = Input::form()->on('change', function($e) {
			$e->submit();
		})->interactions;

		$this->assertCount(1, $interactions);
		$this->assertEquals('change', $interactions[0]->interactionType);

		$interactions = Input::form()->on(['input', 'change'], function($e) {
			$e->submit();
		})->interactions;

		$this->assertCount(2, $interactions);
		$this->assertEquals('input', $interactions[0]->interactionType);
		$this->assertEquals('change', $interactions[1]->interactionType);
	}

	/** @test */
	public function interactions_is_not_a_string_or_array()
	{
		$this->expectException(NotAcceptableInteractionException::class);

		Input::form()->on(1, function($e) {
			$e->get('bla');
		});
	}

	/** @test */
	public function interaction_is_string_but_not_found()
	{
		$this->expectException(NotFoundInteractionException::class);

		Input::form()->on('change', function($e){
			$e->on('headbang', function($e) {});
		});
	}

	/** @test */
	public function interaction_is_array_but_not_found()
	{
		$this->expectException(NotFoundInteractionException::class);

		Input::form()->on(['change', 'headbang'], function($e) {});
	}

	/** @test */
	public function interaction_is_array_but_not_allowed()
	{
		$this->expectException(NotAllowedInteractionException::class);

		Panel::form()->on(['load'], function($e) {
			$e->get('bla')->on('change', function($e){}); //nested not allowed
		});
	}

	/** @test */
	public function interaction_is_string_but_not_allowed()
	{
		$this->expectException(NotAllowedInteractionException::class);

		Button::form('A')->on('change', function($e) {});
	}

	/** @test */
	public function interaction_closure_is_not_an_acceptable_closure_action()
	{
		$this->expectException(NotAcceptableInteractionClosureException::class);

		Input::form()->on('change', 'not a closure');
	}
}