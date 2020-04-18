<?php

namespace Kompo\Tests\Unit\Interaction;

use BadMethodCallException;
use Kompo\Button;
use Kompo\Html;
use Kompo\Input;
use Kompo\Panel;
use Kompo\Tests\EnvironmentBoot;

class ActionForwardingTest extends EnvironmentBoot
{
	/** @test */
	public function element_does_not_forward_actions()
	{
		$this->expectException(BadMethodCallException::class);

		Html::form()->submit();
	}

	/** @test */
	public function element_does_not_accept_on_method()
	{
		$this->expectException(BadMethodCallException::class);

		Html::form()->on('click', null);
	}

	/** @test */
	public function actions_are_forwarded_with_default_interaction_on_elements()
	{
		$interactions = Button::form()->submit()->interactions;

		$this->assertCount(1, $interactions);
		$this->assertEquals('click', $interactions[0]->interactionType);
		$this->assertEquals('submitForm', $interactions[0]->action->actionType);

		$interactions = Input::form()->submit()->interactions;

		$this->assertCount(1, $interactions);
		$this->assertEquals('change', $interactions[0]->interactionType);
		$this->assertEquals('submitForm', $interactions[0]->action->actionType);

		$interactions = Panel::form()->submit()->interactions;

		$this->assertCount(1, $interactions);
		$this->assertEquals('load', $interactions[0]->interactionType);
		$this->assertEquals('submitForm', $interactions[0]->action->actionType);
	}
	
	/** ------------------ PRIVATE --------------------------- */  

	
	
}