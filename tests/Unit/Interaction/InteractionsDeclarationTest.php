<?php

namespace Kompo\Tests\Unit\Interaction;

use Kompo\Exceptions\NotAcceptableInteractionClosureException;
use Kompo\Exceptions\NotAcceptableInteractionException;
use Kompo\Exceptions\NotAllowedInteractionException;
use Kompo\Exceptions\NotFoundInteractionException;
use Kompo\Tests\EnvironmentBoot;

class InteractionsDeclarationTest extends EnvironmentBoot
{
	/** @test */
	public function interaction_is_acceptable_string_or_array()
	{
		$form = new _InteractionAllowedStringArrayForm();

		$this->assertCount(1, $form->components[0]->interactions);
		$this->assertEquals('change', $form->components[0]->interactions[0]->interactionType);

		$this->assertCount(2, $form->components[1]->interactions);
		$this->assertEquals('click', $form->components[1]->interactions[0]->interactionType);
		$this->assertEquals('change', $form->components[1]->interactions[1]->interactionType);
	}

	/** @test */
	public function interactions_is_not_a_string_or_array()
	{
		$this->expectException(NotAcceptableInteractionException::class);

		$form = new _InteractionNotFoundTypeForm();
	}

	/** @test */
	public function interaction_is_string_but_not_found()
	{
		$this->expectException(NotFoundInteractionException::class);

		$form = new _InteractionNotFoundStringForm();
	}

	/** @test */
	public function interaction_is_array_but_not_found()
	{
		$this->expectException(NotFoundInteractionException::class);

		$form = new _InteractionNotFoundArrayForm();
	}

	/** @test */
	public function interaction_is_array_but_not_allowed()
	{
		$this->expectException(NotAllowedInteractionException::class);

		$form = new _InteractionNotAllowedArrayForm();
	}

	/** @test */
	public function interaction_is_string_but_not_allowed()
	{
		$this->expectException(NotAllowedInteractionException::class);

		$form = new _InteractionNotAllowedStringForm();
	}

	/** @test */
	public function interaction_closure_is_not_an_acceptable_closure_action()
	{
		$this->expectException(NotAcceptableInteractionClosureException::class);

		$form = new _InteractionClosureNotAcceptableForm();
	}
}